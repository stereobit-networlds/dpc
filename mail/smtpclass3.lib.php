<?php
/*. DOC    SMTP - PHP SMTP class

	<@package SMTP>
	<@version 1.02>
	<@author Chris Ryan>
	<@license LGPL, see LICENSE>

	Define an SMTP class that can be used to connect
	and communicate with any SMTP server. It implements
	all the SMTP functions defined in RFC 821 except TURN.
	<p>

	From: <a href="http://phpmailer.sourceforge.net/">phpmailer.sourceforge.net</a>.

	<p>
	2006-12-22 Umberto Salsi:<br>
	Converted from PHP4 to PHP5.
	Added PHPLint meta-code.
	Exceptions support added.
	Source available at
	<a href="http://www.icosaedro.it/phpmailer/">www.icosaedro.it/phpmailer</a>.

.*/

/*.
	require_module 'standard';
	require_module 'streams';
.*/

class SMTP
/*. DOC
   SMTP is RFC 821 compliant and implements all the RFC 821 SMTP
   commands except TURN which will always return a not implemented
   error. SMTP also provides some utility methods for sending mail
   to an SMTP server.

   <p>
   Some methods can return exceptions of the class ErrorException
   indicating something goes wrong talking to the server, or the server
   did not understand the command. Some others methods 
.*/
{
    public /*. bool .*/ $debug = FALSE;
    /*. DOC Enable debugging messages.*/

	public /*. bool .*/ $debug_stderr = FALSE;
	/*. DOC Send debugging messages to stderr rather than to stdout. .*/

	/*. private .*/ const ERR_STILL_NOT_CONNECTED = "still not connected";

    private /*. resource .*/  $smtp_conn = NULL;  # the socket to the server
    private /*. string .*/  $helo_rply;  # the reply the server sent to us for HELO

	private /*. void .*/ function log(/*. string .*/ $s)
	{
		if($this->debug_stderr)
			error_log($s);
		else
			echo $s;
	}


    /*. void .*/ function __construct()
    /*. DOC Initialize the class so that the data is in a known state.  .*/
    {
        $this->smtp_conn = NULL;
        $this->helo_rply = NULL;

        $this->debug = FALSE;
    }

    /************************************************************
                          CONNECTION FUNCTIONS                  *
      **********************************************************/

    /*. void .*/ function Close()
    /*. DOC
       Closes the socket and cleans up the state of the class.
       It is not considered good to use this function without
       first trying to use QUIT.
     .*/
    {
        $this->helo_rply = NULL;
        if( $this->smtp_conn !== NULL ) {
            # close the connection and cleanup
            fclose($this->smtp_conn);
            $this->smtp_conn = NULL;
        }
    }


    /*. bool .*/ function Connected()
    /*. DOC Returns TRUE if connected to a server otherwise FALSE .*/
    {
        if($this->smtp_conn === NULL)
			return FALSE;

		$sock_status = socket_get_status($this->smtp_conn);
		if(/*. (bool) .*/ $sock_status["eof"]) {
			# hmm this is an odd situation... the socket is
			# valid but we aren't connected anymore
			if( $this->debug ) {
				# FIXME: should raise exception instead?
				$this->log("SMTP -> NOTICE: EOF caught while checking if connected\n");
			}
			try { $this->Close(); }
			catch ( Exception $e ) { }
			return FALSE;
		}
		return TRUE; # everything looks good
    }

	private /*. void .*/ function put_line(/*. string .*/ $line)
	{
		if($this->debug) {
			$this->log("SMTP Client: $line\n");
		}
        fputs($this->smtp_conn, $line);
        fputs($this->smtp_conn, "\r\n");
	}

    private /*. string .*/ function get_lines()
    /*. DOC
       Read in as many lines as possible
       either before eof or socket timeout occurs on the operation.
       With SMTP we can tell if we have more lines to read if the
       4th character is '-' symbol. If it is a space then we don't
       need to read anything else.
     .*/
    {
        $data = "";
        while(FALSE !== ($str = fgets($this->smtp_conn,515))) {
            if($this->debug) {
                $this->log("SMTP Server: $str\n");
            }
            $data .= $str;
            if(substr($str,3,1) === " ") { break; }
        }
        return $data;
    }

    /*. void .*/ function Connect(/*. string .*/ $host, $port=25, $tval=30)
    /*. DOC Connect to the server specified on the port specified.
		$tval is the max time to wait (seconds) for the connection
		be established with the server, then an exception is raised.
       <p>
      
       SMTP CODE SUCCESS: 220<br>
       SMTP CODE FAILURE: 421
     .*/
    {
        # make sure we are __not__ connected
        if($this->Connected()) {
            # ok we are connected! what should we do?
			throw new ErrorException("Already connected to a server");
        }

        #connect to the smtp server
        $this->smtp_conn = @fsockopen($host, $port,
			 $errno, $errstr,
			 $tval);   # give up after ? secs
        # verify we connected properly
        if( $this->smtp_conn === FALSE ) {
			$this->smtp_conn = NULL;
			throw new ErrorException("Failed to connect to server: $errstr ($errno)");
        }

        # sometimes the SMTP server takes a little longer to respond
        # so we will give it a longer timeout for the first read
        // Windows still does not have support for this timeout function
        if(substr(PHP_OS, 0, 3) != "WIN")
           socket_set_timeout($this->smtp_conn, $tval, 0);

        # get any announcement stuff
        /* $ignore_announce = */ $this->get_lines();

        # set the timeout  of any socket functions at 1/10 of a second
        //if(function_exists("socket_set_timeout"))
        //   socket_set_timeout($this->smtp_conn, 0, 100000);
    }

    /*. void .*/ function Authenticate(/*. string .*/ $username, /*. string .*/ $password)
    /*. DOC
       Performs SMTP authentication.  Must be run after running the
       <@item ::Hello()> method.
     .*/
    {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        // Start authentication
        $this->put_line("AUTH LOGIN");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 334) {
			throw new ErrorException("AUTH not accepted from server: "
			. substr($rply,4) . " ($code)");
        }

        // Send encoded username
        $this->put_line(base64_encode($username));

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 334) {
			throw new ErrorException("Username not accepted from server: "
			. substr($rply,4) . " ($code)");
        }

        // Send encoded password
        $this->put_line(base64_encode($password));

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 235) {
			throw new ErrorException("Password not accepted from server: "
			. substr($rply,4) . " ($code)");
        }
    }

    /**************************************************************
                              SMTP COMMANDS                       *
      ************************************************************/

    /*. void .*/ function Data(/*. string .*/ $msg_data)
    /*. DOC
       Issues a data command and sends the msg_data to the server
       finializing the mail transaction. $msg_data is the message
       that is to be send with the headers. Each header needs to be
       on a single line followed by a &lt;CRLF&gt; with the message headers
       and the message body being seperated by and additional &lt;CRLF&gt;.
       <p>
      
       Implements RFC 821: DATA <CRLF>
       <p>
      
       SMTP CODE INTERMEDIATE: 354<br>
           [data]<br>
           &lt;CRLF&gt;.&lt;CRLF&gt;<br>
           SMTP CODE SUCCESS: 250<br>
           SMTP CODE FAILURE: 552,554,451,452<br>
       SMTP CODE FAILURE: 451,554<br>
       SMTP CODE ERROR  : 500,501,503,421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("DATA");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 354) {
			throw new ErrorException("DATA command not accepted from server: "
			. substr($rply,4) . " ($code)");
        }

        # the server is ready to accept data!
        # according to RFC 821 we should not send more than 1000
        # including the CRLF
        # characters on a single line so we will break the data up
        # into lines by \r and/or \n then if needed we will break
        # each of those into smaller lines to fit within the limit.
        # in addition we will be looking for lines that start with
        # a period '.' and append and additional period '.' to that
        # line. NOTE: this does not count towards are limit.

        # normalize the line breaks so we know the explode works
        $msg_data = /*. (string) .*/ str_replace("\r\n","\n",$msg_data);
        $msg_data = /*. (string) .*/ str_replace("\r","\n",$msg_data);
        $lines = explode("\n",$msg_data);

        # we need to find a good way to determine is headers are
        # in the msg_data or if it is a straight msg body
        # currently I'm assuming RFC 822 definitions of msg headers
        # and if the first field of the first line (':' sperated)
        # does not contain a space then it _should_ be a header
        # and we can process all lines before a blank "" line as
        # headers.
        $field = substr($lines[0],0,strpos($lines[0],":"));
        $in_headers = FALSE;
        if(!empty($field) && FALSE===strstr($field," ")) {
            $in_headers = TRUE;
        }

        $max_line_length = 998; # used below; set here for ease in change

        foreach($lines as $line) {
            $lines_out = /*. (array[int]string) .*/ NULL;
            if($line === "" && $in_headers) {
                $in_headers = FALSE;
            }
            # ok we need to break this line up into several
            # smaller lines
            while(strlen($line) > $max_line_length) {
                $pos = strrpos(substr($line,0,$max_line_length)," ");

                # Patch to fix DOS attack
                if($pos===FALSE) {
                    $pos = $max_line_length - 1;
                }

                $lines_out[] = substr($line,0,$pos);
                $line = substr($line,$pos + 1);
                # if we are processing headers we need to
                # add a LWSP-char to the front of the new line
                # RFC 822 on long msg headers
                if($in_headers) {
                    $line = "\t" . $line;
                }
            }
            $lines_out[] = $line;

            # now send the lines to the server
            # FIXED
            #while(list(,$line_out) = @each($lines_out)) {
            foreach($lines_out as $line_out) {
                if(strlen($line_out) > 0)
                {
                    if(substr($line_out, 0, 1) === ".") {
                        $line_out = "." . $line_out;
                    }
                }
                $this->put_line($line_out);
            }
        }

        # ok all the message data has been sent so lets get this
        # over with aleady
        $this->put_line(".");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250) {
			throw new ErrorException("DATA not accepted from server: "
			. substr($rply,4) . " ($code)");
        }
    }

    /*. array[int]string .*/ function Expand(/*. string .*/ $name)
    /*. DOC
       Expand takes the name and asks the server to list all the
       people who are members of the _list_. Expand will return
       back and array of the result or FALSE if an error occurs.
       Each value in the array returned has the format of:
           [ &lt;full-name&gt; &lt;sp&gt; ] &lt;path&gt;
       The definition of &lt;path&gt; is defined in RFC 821
       <p>
      
       Implements RFC 821: EXPN &lt;SP&gt; &lt;string&gt; &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250<br>
       SMTP CODE FAILURE: 550<br>
       SMTP CODE ERROR  : 500,501,502,504,421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("EXPN " . $name);

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250) {
			throw new ErrorException("EXPN not accepted from server: "
			. substr($rply,4) . " ($code)");
        }

        # parse the reply and place in our array to return to user
        $entries = explode("\r\n",$rply);
        $list_ = /*. (array[int]string) .*/ array();
        foreach($entries as $l) {
            $list_[] = substr($l,4);
        }

        return $list_;
    }

    /*. void .*/ function SendHello(/*. string .*/ $hello, /*. string .*/ $host)
    /*. DOC Sends a HELO/EHLO command.  .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line($hello . " " . $host);

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250) {
			throw new ErrorException($hello . " not accepted from server: "
			. substr($rply,4) . " ($code)");
        }

        $this->helo_rply = $rply;
    }

    /*. void .*/ function Hello($host="")
    /*. DOC Sends the HELO command to the smtp server.
       This makes sure that we and the server are in
       the same known state.
       <p>
      
       Implements from RFC 821: HELO &lt;SP&gt; &lt;domain&gt; &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250<br>
       SMTP CODE ERROR  : 500, 501, 504, 421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        # if a hostname for the HELO wasn't specified determine
        # a suitable one to send
        if(empty($host)) {
            # we need to determine some sort of appropriate default
            # to send to the server
            $host = "localhost";
        }

        // Send extended hello first (RFC 2821)
		try {
			$this->SendHello("EHLO", $host);
		}
		catch ( ErrorException $e ) {
            $this->SendHello("HELO", $host);
        }
    }

    /*. string .*/ function Help($keyword="")
    /*. DOC Gets help information on the keyword specified.
       If the keyword
       is not specified then returns generic help, ussually containing
       a list of keywords that help is available on. This function
       returns the results back to the user. It is up to the user to
       handle the returned data.
       <p>
      
       Implements RFC 821: HELP [ &lt;SP&gt; &lt;string&gt; ] &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 211,214<br>
       SMTP CODE ERROR  : 500,501,502,504,421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $extra = "";
        if(!empty($keyword)) {
            $extra = " " . $keyword;
        }

        $this->put_line("HELP" . $extra);

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 211 && $code != 214) {
			throw new ErrorException("HELP not accepted from server: "
			. substr($rply,4) . " ($code)");
        }

        return $rply;
    }

    /*. void .*/ function Mail(/*. string .*/ $from)
    /*. DOC
       Starts a mail transaction from the email address specified in
       $from and then one or more <@item ::Recipient()> commands may be
       called followed by a <@item ::Data()> command.
       <p>
      
       Implements RFC 821: MAIL &lt;SP&gt; FROM:&lt;reverse-path&gt; &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250<br>
       SMTP CODE SUCCESS: 552,451,452<br>
       SMTP CODE SUCCESS: 500,501,421<br>
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("MAIL FROM: <$from>");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250) {
			throw new ErrorException("MAIL not accepted from server: "
			. substr($rply,4) . " ($code)");
        }
    }

    /*. void .*/ function Noop()
    /*. DOC Sends the command NOOP to the SMTP server.
      
       Implements from RFC 821: NOOP &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250<br>
       SMTP CODE ERROR  : 500, 421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("NOOP");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250) {
			throw new ErrorException("NOOP not accepted from server: "
			. substr($rply,4) . " ($code)");
        }
    }

    /*. void .*/ function Quit()
    /*. DOC Sends the quit command to the server and then closes the socket.
       <p>
      
       Implements from RFC 821: QUIT &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 221<br>
       SMTP CODE ERROR  : 500
     .*/
     {
	 	if( $this->smtp_conn === NULL )
			return;

        # send the quit command to the server
        $this->put_line("QUIT");

        # get any good-bye messages
        $byemsg = $this->get_lines();

		$this->Close();

        $code = (int) substr($byemsg,0,3);
        if($code != 221) {
			throw new ErrorException("SMTP server rejected quit command: "
			. substr($byemsg,4) . " ($code)");
        }
    }

    /*. void .*/ function Recipient(/*. string .*/ $to)
    /*. DOC
       Sends the command RCPT to the SMTP server with the TO: argument of $to.
       Returns TRUE if the recipient was accepted FALSE if it was rejected.
       <p>
      
       Implements from RFC 821: RCPT &lt;SP&gt; TO:&lt;forward-path&gt; &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250,251<br>
       SMTP CODE FAILURE: 550,551,552,553,450,451,452<br>
       SMTP CODE ERROR  : 500,501,503,421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("RCPT TO: <$to>");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250 && $code != 251) {
			throw new ErrorException("RCPT not accepted from server: "
			. substr($rply,4) . " ($code)");
        }
    }

    /*. void .*/ function Reset()
    /*. DOC
       Sends the RSET command to abort and transaction that is
       currently in progress.
       <p>
      
       Implements RFC 821: RSET &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250<br>
       SMTP CODE ERROR  : 500,501,504,421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("RSET");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250) {
			throw new ErrorException("RSET failed: $rply");
        }
    }

    /*. void .*/ function Send(/*. string .*/ $from)
    /*. DOC
       Starts a mail transaction from the email address specified in
       $from. Returns TRUE if successful or FALSE otherwise. If TRUE
       the mail transaction is started and then one or more <@item
       ::Recipient()> commands may be called followed by a <@item
       ::Data()> command. This command will send the message to the
       users terminal if they are logged in.
       <p>
      
       Implements RFC 821: SEND &lt;SP&gt; FROM:&lt;reverse-path&gt;
       &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250<br>
       SMTP CODE SUCCESS: 552,451,452<br>
       SMTP CODE SUCCESS: 500,501,502,421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("SEND FROM: $from");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250) {
			throw new ErrorException("SEND not accepted from server: $rply");
        }
    }

    /*. void .*/ function SendAndMail(/*. string .*/ $from)
    /*. DOC Starts a mail transaction from the email address specified in $from.
       Returns TRUE if successful or FALSE otherwise. If TRUE the mail
       transaction is started and then one or more <@item ::Recipient()>
       commands may be called followed by a <@item ::Data()> command. This
       command will send the message to the users terminal if they are
       logged in and send them an email.
       <p>
      
       Implements RFC 821: SAML &lt;SP&gt; FROM:&lt;reverse-path&gt;
       &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250<br>
       SMTP CODE SUCCESS: 552,451,452<br>
       SMTP CODE SUCCESS: 500,501,502,421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("SAML FROM: $from ");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250) {
			throw new ErrorException("SAML not accepted from server: $rply");
        }
    }

    /*. void .*/ function SendOrMail(/*. string .*/ $from)
    /*. DOC
       Starts a mail transaction from the email address specified in
       $from. Returns TRUE if successful or FALSE otherwise. If TRUE
       the mail transaction is started and then one or more <@item
       ::Recipient()> commands may be called followed by a <@item
       ::Data()> command. This command will send the message to the users
       terminal if they are logged in or mail it to them if they are not.
       <p>
      
       Implements RFC 821: SOML &lt;SP&gt; FROM:&lt;reverse-path&gt; &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250<br>
       SMTP CODE SUCCESS: 552,451,452<br>
       SMTP CODE SUCCESS: 500,501,502,421
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("SOML FROM: $from");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250) {
			throw new ErrorException("SOML not accepted from server: $rply");
        }
    }

    /*. void .*/ function Turn()
    /*. DOC
       This is an optional command for SMTP that this class does not
       support. This method is here to make the RFC 821 Definition
       complete for this class and __may__ be implemented in the future
       <p>
      
       Implements from RFC 821: TURN &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250<br>
       SMTP CODE FAILURE: 502<br>
       SMTP CODE ERROR  : 500, 503
     .*/
     {
		throw new ErrorException("This method, TURN, of the SMTP is not implemented");
     }

    /*. int .*/ function Verify(/*. string .*/ $name)
    /*. DOC
       Verifies that the name is recognized by the server.
	   Returns the result code from the server (see below).
       <p>
      
       Implements RFC 821: VRFY &lt;SP&gt; &lt;string&gt; &lt;CRLF&gt;
       <p>
      
       SMTP CODE SUCCESS: 250,251<br>
       SMTP CODE FAILURE: 550,551,553<br>
       SMTP CODE ERROR  : 500,501,502,421

		<p>
	   NOTE. See RFC 2821 for even more result codes.
     .*/
     {
        if(!$this->Connected()) {
			throw new ErrorException(self::ERR_STILL_NOT_CONNECTED);
        }

        $this->put_line("VRFY $name");

        $rply = $this->get_lines();
        $code = (int) substr($rply,0,3);

        if($code != 250 && $code != 251) {
			#throw new ErrorException("VRFY failed on name '$name': $rply");
        }
        return $code;
    }

}

?>
