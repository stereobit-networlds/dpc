<?php

/*. DOC PHPMailer - PHP email transport class

	<@package PHPMailer-ico>
	<@author Brent R. Matzelle>
	<@author Umberto Salsi (porting to PHP5)>
	<@version ico_20070613>
	<@license LGPL, see LICENSE>
	<@copyright 2001 - 2003 Brent R. Matzelle>

	Class for sending email using either sendmail, PHP <@item mail()>, or
	SMTP. A message can be either a plain text or an HTML text, possibly
	with embedded images and attachments.

	<p>
	PHPMailer-ico has been converted from PHP 4 to PHP 5 from the original
	PHPMailer 1.73 version. This package can be downloaded from
	<a href="http://www.icosaedro.it/phpmailer/">www.icosaedro.it/phpmailer</a>.

	<p>
	FIXME: SmtpSend() fails and no email is sent if any of the recipients
	is not accepted by the server; the program cannot know which one failed
	or it must send every copy of the message to any recipient, one by one.
	The interface to the SMTP class should be redesigned to resolve this
	issue.

	<p>
	BUG: filenames are not encoded as per the RFC 2047 "MIME headers".
.*/

/*.
	require_module 'standard';
	require_module 'pcre';
	require_module 'hash';
.*/


/*. private .*/ class PHPMailer_Attachment
{
	public /*. string .*/ $path;
		/* pathfile on the server (if !$isStringAttachment)
		   or binary content (if $isStringAttachment) */
	public /*. string .*/ $filename;  # basename($path)
	public /*. string .*/ $name;      # proposed name to the recipient user
	public /*. string .*/ $encoding;
	public /*. string .*/ $type;      # MIME type
	public /*. bool   .*/ $isStringAttachment;
	public /*. string .*/ $disposition; # "inline" or "attachment"
	public /*. string .*/ $cid;    # content ID of embedded image/sound/...
}


class PHPMailer
{

const VERSION       = "ico_20070415";
/*. DOC Holds PHPMailer-ico version.  .*/

const SENDMAIL_PATH = "/usr/sbin/sendmail";
const QMAIL_PATH = "/var/qmail/bin/sendmail";

public $Priority          = 3;
/*. DOC Email priority (1 = High, 3 = Normal, 5 = low).  .*/

public $CharSet           = "iso-8859-1";
/*. DOC Sets the CharSet of the message

	This sets the charset you will use either for the subject and
	the body (text and HTML) of the message.
.*/

public $Encoding          = "8bit";
/*. DOC Sets the Encoding of the message.
	Options for this are "8bit", "7bit", "binary", "base64", and
	"quoted-printable".
.*/

public $ErrorInfo         = "";
/*. DOC Holds the most recent mailer error message.  .*/

public $From              = "root@localhost";
/*. DOC Sets the From email address for the message.

	Remember to validate the email through <@item ::IsValidEmailAddress()>.
.*/

public $FromName          = "Root User";
/*. DOC Sets the From name of the message.  .*/

public $Sender            = "";
/*. DOC Sets the Sender email (Return-Path) of the message.

	If not empty, will be sent via -f to sendmail or as
	'MAIL FROM' in smtp mode.
	Remember to validate the email through <@item ::IsValidEmailAddress()>.
.*/

public $Subject           = "";
/*. DOC Sets the subject of the message

	The string must be encoded accordingly to the <@item ::$CharSet>
	variable. The "Subject:" header will be encoded properly
	to render the given charset.
.*/

public $Body              = "";
/*. DOC Sets the Body of the message.

	This can be either an HTML or text body.
	If HTML then run <@item ::IsHTML(TRUE)>.
	The charset of this string must be <@item ::$CharSet>.
	See also <@item ::$AltBody> if you want to set an alternate
	plain text version of the message.
.*/

public $AltBody           = "";
/*. DOC Sets the text-only body of the message

	This automatically sets the email to
	multipart/alternative.	This body can be read by mail
	clients that do not have HTML email capability such as
	mutt. Clients that can read HTML will view the normal
	<@item ::$Body>.
	The charset of this string must be <@item ::$CharSet>.
.*/

public $WordWrap          = 0;
/*. DOC Sets word wrapping on the body of the message to a given number of characters

	Lines longher than that are splitted. 0 = no word wrap.
.*/

private $ContentType      = "text/plain";
/* The Content-Type of the message */

private $Mailer           = "mail";
/*. DOC Method to send mail: ("mail", "sendmail", or "smtp").  .*/

public $Sendmail          = self::SENDMAIL_PATH;
/*. DOC The path of the sendmail program .*/

public $ConfirmReadingTo  = "";
/*. DOC Sets the email address that a reading confirmation will be sent.

	Remember to validate the email through <@item ::IsValidEmailAddress()>.
.*/

public $Hostname          = "";
/*. DOC Sets the hostname to use in Message-Id and Received headers and as default HELO string

	If empty, the value returned by SERVER_NAME is used or
	'localhost.localdomain'.
.*/

public $Host        = "localhost";
/*. DOC Sets the SMTP hosts

	You can list one or several servers separated by semicolon
	and possibly with a port number separated with a colon,
	for example: "host1;host2:25;host3:1025".  The default
	TCP port is 25.

	The listed hosts are tried in order, the first accepting
	the connection and possibly the authentication login
	is used.
.*/

public $Helo        = "";
/*. DOC Sets the SMTP HELO of the message (Default is <@item ::$Hostname>).  .*/

public $SMTPAuth     = FALSE;
/*. DOC Sets SMTP authentication.
	Utilizes the <@item ::$Username> and <@item ::$Password> variables.
.*/

public $Username     = "";
/*. DOC Sets SMTP username.  .*/

public $Password     = "";
/*. DOC Sets SMTP password.  .*/

public $Timeout      = 10;
/*. DOC Sets the SMTP server timeout in seconds.
	This function will not work with the win32 version.
.*/

public $SMTPDebug    = FALSE;
/*. DOC Sets SMTP class debugging on or off.  .*/

public $SMTPKeepAlive = FALSE;
/*. DOC Prevents the SMTP connection from being closed after each mail sending.
	If this is set to TRUE then to close the connection
	requires an explicit call to <@item ::SmtpClose()>.
.*/

const   LE           = "\n";
private $smtp        = /*. (SMTP) .*/ NULL;
private $to          = /*. (array[int][int]string) .*/ NULL;
private $cc          = /*. (array[int][int]string) .*/ NULL;
private $bcc         = /*. (array[int][int]string) .*/ NULL;
private $ReplyTo     = /*. (array[int][int]string) .*/ NULL;
private $attachment  = /*. (array[int]PHPMailer_Attachment) .*/ NULL;
private $CustomHeader = /*. (array[int][int]string) .*/ NULL;
private $message_type = "";
private $boundary    = /*. (array[int]string) .*/ NULL;

private $language_type = "en";
private $language_path = "language/";
private $language    = /*. (array[string]string) .*/ NULL;

private $error_count = 0;

/*. void .*/ function IsHTML(/*. bool .*/ $xbool)
/*. DOC Sets message type to HTML (TRUE) or plain text (FALSE) .*/
{
	if($xbool == TRUE)
		$this->ContentType = "text/html";
	else
		$this->ContentType = "text/plain";
}

/*. void .*/ function IsSMTP()
/*. DOC Sets Mailer to send message using SMTP protocol  .*/
{
	$this->Mailer = "smtp";
}

/*. void .*/ function IsMail()
/*. DOC Sets Mailer to send message using PHP <@item mail()> function  .*/
{
	$this->Mailer = "mail";
}

/*. void .*/ function IsSendmail()
/*. DOC Sets Mailer to send message using <@item ::$Sendmail> program  .*/
{
	$this->Sendmail = self::SENDMAIL_PATH;
	$this->Mailer = "sendmail";
}

/*. void .*/ function IsQmail()
/*. DOC Sets Mailer to send message using the qmail MTA. .*/
{
	$this->Sendmail = self::QMAIL_PATH;
	$this->Mailer = "sendmail";
}

static /*. bool .*/ function IsValidEmailAddress(/*. string .*/ $email)
/*.
	DOC  Return TRUE is $email is a valid email address

	Valid email address are specified by RFC 2822. This method implements
	a sub-set of this syntax that can be described as follows.
	The address has the form user@domain where the user part and the domain
	part are made of one or more "atoms" separated by a dot. Every atom
	is a sequence of one or more letters of the US-ASCII charset,
	digits or any of the following characters:
	<p>
	<code>! # $ % & ' * + - / = ? ^ _ ` { | } ~</code>
.*/
{
	static $re = "";

	if( $re === "" ){
		$atext = "[-!#-'*+/0-9=?A-Z^-~]{1,}";
		$atom = $atext . "+";
		$dot_atom = $atom . "(\\.$atom)*";
		$re = ":^". $dot_atom ."@". $dot_atom ."\$:";
	}
	return preg_match($re, $email) === 1;
}

/*. void .*/ function AddAddress(/*. string .*/ $address, $name = "")
/*. DOC Adds a "To" address.

	The $name, if provided, must be encoded using the charset specified by
	<@item ::$CharSet>.
	<p>
	An exception is thrown if the address provided isn't a valid email
	address according to <@item ::IsValidEmailAddress()>.
.*/
{
	if( ! self::IsValidEmailAddress($address) )
		throw new ErrorException("invalid email address $address");
	$cur = count($this->to);
	$this->to[$cur][0] = trim($address);
	$this->to[$cur][1] = $name;
}

/*. void .*/ function AddCC(/*. string .*/ $address, $name = "")
/*. DOC Adds a "Cc" address.

	Note: this function works with the SMTP mailer on win32,
	not with the "mail" mailer.
	<p>
	An exception is thrown if the address provided isn't a valid email
	address according to <@item ::IsValidEmailAddress()>.
.*/
{
	if( ! self::IsValidEmailAddress($address) )
		throw new ErrorException("invalid email address $address");
	$cur = count($this->cc);
	$this->cc[$cur][0] = trim($address);
	$this->cc[$cur][1] = $name;
}

/*. void .*/ function AddBCC(/*. string .*/ $address, $name = "")
/*. DOC Adds a "Bcc" address.
	Note: this function works with the SMTP mailer on win32,
	not with the "mail" mailer.
	FIXME: verify last sentence.
	<p>
	An exception is thrown if the address provided isn't a valid email
	address according to <@item ::IsValidEmailAddress()>.
.*/
{
	if( ! self::IsValidEmailAddress($address) )
		throw new ErrorException("invalid email address $address");
	$cur = count($this->bcc);
	$this->bcc[$cur][0] = trim($address);
	$this->bcc[$cur][1] = $name;
}

/*. void .*/ function AddReplyTo(/*. string .*/ $address, $name = "")
/*. DOC Adds a "Reply-to" address.

	An exception is thrown if the address provided isn't a valid email
	address according to <@item ::IsValidEmailAddress()>.
.*/
{
	if( ! self::IsValidEmailAddress($address) )
		throw new ErrorException("invalid email address $address");
	$cur = count($this->ReplyTo);
	$this->ReplyTo[$cur][0] = trim($address);
	$this->ReplyTo[$cur][1] = $name;
}


/*. void .*/ function AddCustomHeader(/*. string .*/ $custom_header)
/*. DOC Adds a custom header

	This method accept only one header, with header name and header
	body separated by a colon.
	Several headers can be added calling this method several
	times.
	If the header body contains non-ASCII characters, when the
	email will be sent, it is encoded properly using the given
	<@item ::$CharSet>.
.*/
{
	$this->CustomHeader[] = explode(":", $custom_header, 2);
}


private /*. void .*/ function SetError(/*. string .*/ $msg)
/*
	Adds the error message to the error container.
*/
{
	$this->error_count++;
	if( empty($this->ErrorInfo) )
		$this->ErrorInfo = $msg;
	else
		$this->ErrorInfo .= "\n" . $msg;
}

/*. void .*/ function SetLanguage(/*. string .*/ $lang_type, $lang_path = "language/")
/*. DOC Sets the language for all class error messages.

	This method simply save internally these parameters;
	for efficiency, the translation file isn't actually
	loaded until a first error message is actually raised.
	The default language type is "en" and the default directory
	of the translation files is "language/".
.*/
{
	$this->language_type = $lang_type;
	$this->language_path = $lang_path;
	$this->language = /*. (array[string]string) .*/ array();
}

private /*. string .*/ function Lang(/*. string .*/ $key)
/*
	Returns a message in the appropriate language.
*/
{
	if( count($this->language) < 1 ){

		$PHPMAILER_LANG = /*. (array[string]string) .*/ array();

		/*include($this->language_path . "/phpmailer.lang-"
			. $this->language_type . ".php");*/  //<<<<<<<<<<<<<<<<<<<<<<<<<<< NO LANG
			
$PHPMAILER_LANG = array();

$PHPMAILER_LANG["provide_address"] = 'You must provide at least one ' .
                                     'recipient email address.';
$PHPMAILER_LANG["mailer_not_supported"] = ' mailer is not supported.';
$PHPMAILER_LANG["execute"] = 'Could not execute: ';
$PHPMAILER_LANG["instantiate"] = 'Could not instantiate mail function.';
$PHPMAILER_LANG["authenticate"] = 'SMTP Error: Could not authenticate.';
$PHPMAILER_LANG["from_failed"] = 'The following From address failed: ';
$PHPMAILER_LANG["recipients_failed"] = 'SMTP Error: The following ' .
                                       'recipients failed: ';
$PHPMAILER_LANG["data_not_accepted"] = 'SMTP Error: Data not accepted.';
$PHPMAILER_LANG["connect_host"] = 'SMTP Error: Could not connect to SMTP host.';
$PHPMAILER_LANG["file_access"] = 'Could not access file: ';
$PHPMAILER_LANG["file_open"] = 'File Error: Could not open file: ';
$PHPMAILER_LANG["encoding"] = 'Unknown encoding: ';			

		$this->language = $PHPMAILER_LANG;
	}

	if(isset($this->language[$key]))
		return "[$key] " . $this->language[$key];
	else
		return "[$key] ";
}

private /*. void .*/ function SetMessageType()
/*
	Sets the message type.
*/
{
	if(count($this->attachment) < 1 && strlen($this->AltBody) < 1)
		$this->message_type = "plain";
	else
	{
		if(count($this->attachment) > 0)
			$this->message_type = "attachments";
		if(strlen($this->AltBody) > 0 && count($this->attachment) < 1)
			$this->message_type = "alt";
		if(strlen($this->AltBody) > 0 && count($this->attachment) > 0)
			$this->message_type = "alt_attachments";
	}
}

private static /*. string .*/ function HeaderLine(/*. string .*/ $name, /*. string .*/ $value)
/*
	Returns a formatted header line.
*/
{
	return $name . ": " . $value . self::LE;
}

private static /*. string .*/ function RFCDate()
/*
	Returns the proper RFC 822 formatted date. 
*/
{
	$tz = (int) date("Z");
	$tzs = ($tz < 0) ? "-" : "+";
	$tz = (int) abs($tz);
	$tz = (int) ( ($tz/3600)*100 + ($tz%3600)/60 );
	$result = sprintf("%s %s%04d", date("D, j M Y H:i:s"), $tzs, $tz);

	return $result;
}

private static /*. string .*/ function EncodeQ (/*. string .*/ $str, $position = "text")
/*
	Encode string to q encoding.  
*/
{
	// There should not be any EOL in the string
	$encoded = preg_replace("[\r\n]", "", $str);

	switch (strtolower($position)) {
	  case "phrase":
		$encoded = preg_replace("/([^A-Za-z0-9!*+/ -])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
		break;
	  case "comment":
		$encoded = preg_replace("/([()\"])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
			/*. missing_break; .*/
	  case "text":
			/*. missing_break; .*/
	  default:
		// Replace every high ascii, control =, ? and _ characters
		$encoded = preg_replace('/([\\000-\\011\\013\\014\\016-\\037\\075\\077\\137\\177-\\377])/e',
			  "'='.sprintf('%02X', ord('\\1'))", $encoded);
		break;
	}
	
	// Replace every spaces to _ (more readable than =20)
	$encoded = /*. (string) .*/ str_replace(" ", "_", $encoded);

	return $encoded;
}

private static /*. string .*/ function FixEOL(/*. string .*/ $str)
/*
	Changes every end of line from CR or LF to CRLF.  
*/
{
	$str = /*. (string) .*/ str_replace("\r\n", "\n", $str);
	$str = /*. (string) .*/ str_replace("\r", "\n", $str);
	$str = /*. (string) .*/ str_replace("\n", self::LE, $str);
	return $str;
}

private static /*. string .*/ function WrapText(/*. string .*/ $message, /*. int .*/ $length, $qp_mode = FALSE)
/*
	Wraps message for use with mailers that do not
	automatically perform wrapping and for quoted-printable.
	Original written by philippe.  
*/
{
	$soft_break = ($qp_mode) ? sprintf(" =%s", self::LE) : self::LE;

	$message = self::FixEOL($message);
	if (substr($message, -1) === self::LE)
		$message = substr($message, 0, -1);

	$line = explode(self::LE, $message);
	$message = "";
	for ($i=0 ;$i < count($line); $i++)
	{
	  $line_part = explode(" ", $line[$i]);
	  $buf = "";
	  for ($e = 0; $e<count($line_part); $e++)
	  {
		  $word = $line_part[$e];
		  if ($qp_mode and (strlen($word) > $length))
		  {
			$space_left = $length - strlen($buf) - 1;
			if ($e != 0)
			{
				if ($space_left > 20)
				{
					$len = $space_left;
					if (substr($word, $len - 1, 1) === "=")
					  $len--;
					elseif (substr($word, $len - 2, 1) === "=")
					  $len -= 2;
					$part = substr($word, 0, $len);
					$word = substr($word, $len);
					$buf .= " " . $part;
					$message .= $buf . sprintf("=%s", self::LE);
				}
				else
				{
					$message .= $buf . $soft_break;
				}
				$buf = "";
			}
			while (strlen($word) > 0)
			{
				$len = $length;
				if (substr($word, $len - 1, 1) === "=")
					$len--;
				elseif (substr($word, $len - 2, 1) === "=")
					$len -= 2;
				$part = substr($word, 0, $len);
				$word = substr($word, $len);

				if (strlen($word) > 0)
					$message .= $part . sprintf("=%s", self::LE);
				else
					$buf = $part;
			}
		  }
		  else
		  {
			$buf_o = $buf;
			$buf .= ($e == 0) ? $word : (" " . $word); 

			if (strlen($buf) > $length and $buf_o != "")
			{
				$message .= $buf_o . $soft_break;
				$buf = $word;
			}
		  }
	  }
	  $message .= $buf . self::LE;
	}

	return $message;
}

private /*. string .*/ function EncodeHeader (/*. string .*/ $str, $position = 'text')
/*
	Encode a header string to best of Q, B, quoted or none.  
*/
{
  $x = 0;
  
  switch (strtolower($position)) {
	case 'phrase':
	  if (FALSE === preg_match('/[\\200-\\377]/', $str)) {
		// Can't use addslashes as we don't know what value has magic_quotes_sybase.
		$encoded = addcslashes($str, "\0..\37\177\\\"");

		if (($str === $encoded) && FALSE===preg_match('/[^A-Za-z0-9!#$%&\'*+\\/=?^_`{|}~ -]/', $str))
		  return ($encoded);
		else
		  return ("\"$encoded\"");
	  }
	  $x = preg_match_all('/[^\\040\\041\\043-\\133\\135-\\176]/', $str, $matches);
	  break;
	case 'comment':
	  $x = preg_match_all('/[()"]/', $str, $matches);
	  /*. missing_break; .*/
	case 'text': /*. missing_break; .*/
	default:
	  $x += preg_match_all('/[\\000-\\010\\013\\014\\016-\\037\\177-\\377]/', $str, $matches);
	  break;
  }

  if ($x == 0)
	return ($str);

  $maxlen = 75 - 7 - strlen($this->CharSet);
  // Try to select the encoding which should produce the shortest output
  if (strlen($str)/3 < (float)$x) {
	$encoding = 'B';
	$encoded = base64_encode($str);
	$maxlen -= $maxlen % 4;
	$encoded = trim(chunk_split($encoded, $maxlen, "\n"));
  } else {
	$encoding = 'Q';
	$encoded = PHPMailer::EncodeQ($str, $position);
	$encoded = self::WrapText($encoded, $maxlen, TRUE);
	$encoded = /*. (string) .*/ str_replace("=".self::LE, "\n", trim($encoded));
  }

  $encoded = preg_replace('/^(.*)$/m', " =?".$this->CharSet."?$encoding?\\1?=", $encoded);
  $encoded = trim(/*. (string) .*/ str_replace("\n", self::LE, $encoded));
  
  return $encoded;
}

private /*. string .*/ function AddrFormat(/*. array[int]string .*/ $addr)
/*
	Formats an address correctly. 
*/
{
	if(empty($addr[1]))
		$formatted = $addr[0];
	else
		$formatted = $this->EncodeHeader($addr[1], 'phrase') . " <" . 
			 $addr[0] . ">";

	return $formatted;
}

private /*. string .*/ function AddrAppend(/*. string .*/ $type, /*. array[int][int]string .*/ $addr)
/*
	Creates recipient headers.  
*/
{
	$addr_str = $type . ": ";
	$addr_str .= $this->AddrFormat($addr[0]);
	if(count($addr) > 1)
	{
		for($i = 1; $i < count($addr); $i++)
			$addr_str .= ", " . $this->AddrFormat($addr[$i]);
	}
	$addr_str .= self::LE;

	return $addr_str;
}

private /*. string .*/ function ServerHostname()
/*
	Returns the server hostname or 'localhost.localdomain' if unknown.
*/
{
	if ( ! empty($this->Hostname) )
		$result = $this->Hostname;
	elseif ( isset($_SERVER['SERVER_NAME']) )
		$result = $_SERVER['SERVER_NAME'];
	else
		$result = "localhost.localdomain";

	return $result;
}

private /*. bool .*/ function InlineImageExists()
/*
	Returns TRUE if an inline attachment is present.
*/
{
	$result = FALSE;
	for($i = 0; $i < count($this->attachment); $i++)
	{
		if($this->attachment[$i]->disposition === "inline")
		{
			$result = TRUE;
			break;
		}
	}
	
	return $result;
}

private static /*. string .*/ function TextLine(/*. string .*/ $value)
/*
	Returns a formatted mail line.
*/
{
	return $value . self::LE;
}

private /*. string .*/ function CreateHeader()
/*
	Assembles message header.  
*/
{
	$result = "";
	
	// Set the boundaries
	$uniq_id = md5(uniqid((string)time()));
	$this->boundary[1] = "b1_" . $uniq_id;
	$this->boundary[2] = "b2_" . $uniq_id;

	$result .= self::HeaderLine("Date", PHPMailer::RFCDate());
	if($this->Sender === "")
		$result .= self::HeaderLine("Return-Path", trim($this->From));
	else
		$result .= self::HeaderLine("Return-Path", trim($this->Sender));
	
	// To be created automatically by mail()
	if($this->Mailer != "mail")
	{
		if(count($this->to) > 0)
			$result .= $this->AddrAppend("To", $this->to);
		else if (count($this->cc) == 0)
			$result .= self::HeaderLine("To", "undisclosed-recipients:;");
		if(count($this->cc) > 0)
			$result .= $this->AddrAppend("Cc", $this->cc);
	}

	$from = /*. (array[int][int]string) .*/ array();
	$from[0][0] = trim($this->From);
	$from[0][1] = $this->FromName;
	$result .= $this->AddrAppend("From", $from); 

	// sendmail and mail() extract Bcc from the header before sending
	if((($this->Mailer === "sendmail") || ($this->Mailer === "mail")) && (count($this->bcc) > 0))
		$result .= $this->AddrAppend("Bcc", $this->bcc);

	if(count($this->ReplyTo) > 0)
		$result .= $this->AddrAppend("Reply-to", $this->ReplyTo);

	// mail() sets the subject itself
	if($this->Mailer != "mail")
		$result .= self::HeaderLine("Subject", $this->EncodeHeader(trim($this->Subject)));

	$result .= sprintf("Message-ID: <%s@%s>%s", $uniq_id, $this->ServerHostname(), self::LE);
	$result .= self::HeaderLine("X-Priority", (string) $this->Priority);
	$result .= self::HeaderLine("X-Mailer", "PHPMailer [version " . self::VERSION . "]");
	
	if($this->ConfirmReadingTo != "")
	{
		$result .= self::HeaderLine("Disposition-Notification-To", 
				   "<" . trim($this->ConfirmReadingTo) . ">");
	}

	// Add custom headers
	for($index = 0; $index < count($this->CustomHeader); $index++)
	{
		$result .= self::HeaderLine(trim($this->CustomHeader[$index][0]), 
				   $this->EncodeHeader(trim($this->CustomHeader[$index][1])));
	}
	$result .= self::HeaderLine("MIME-Version", "1.0");

	switch($this->message_type)
	{
		case "plain":
			$result .= self::HeaderLine("Content-Transfer-Encoding", $this->Encoding);
			$result .= sprintf("Content-Type: %s; charset=\"%s\"",
								$this->ContentType, $this->CharSet);
			break;
		case "attachments":
			// fall through
		case "alt_attachments":
			if($this->InlineImageExists())
			{
				$result .= sprintf("Content-Type: %s;%s\ttype=\"text/html\";%s\tboundary=\"%s\"%s", 
								"multipart/related", self::LE, self::LE, 
								$this->boundary[1], self::LE);
			}
			else
			{
				$result .= self::HeaderLine("Content-Type", "multipart/mixed;");
				$result .= self::TextLine("\tboundary=\"" . $this->boundary[1] . '"');
			}
			break;
		case "alt":
			$result .= self::HeaderLine("Content-Type", "multipart/alternative;");
			$result .= self::TextLine("\tboundary=\"" . $this->boundary[1] . '"');
			break;

		/*. missing_default: .*/
	}

	if($this->Mailer != "mail")
		$result .= self::LE.self::LE;

	return $result;
}

/////////////////////////////////////////////////
// MAIL SENDING METHODS
/////////////////////////////////////////////////

private /*. void .*/ function SetWordWrap()
/*
	Set the body wrapping.
*/
{
	if($this->WordWrap < 1)
		return;
		
	switch($this->message_type)
	{
	   case "alt":
		  // fall through
	   case "alt_attachments":
		  $this->AltBody = self::WrapText($this->AltBody, $this->WordWrap);
		  break;
	   default:
		  $this->Body = self::WrapText($this->Body, $this->WordWrap);
		  break;
	}
}

private /*. string .*/ function GetBoundary(/*. string .*/ $boundary, /*. string .*/ $charSet, /*. string .*/ $contentType, /*. string .*/ $encoding)
/*
	Returns the start of a message boundary.
*/
{
	$result = "";
	if($charSet === "") { $charSet = $this->CharSet; }
	if($contentType === "") { $contentType = $this->ContentType; }
	if($encoding === "") { $encoding = $this->Encoding; }

	$result .= self::TextLine("--" . $boundary);
	$result .= sprintf("Content-Type: %s; charset = \"%s\"", 
						$contentType, $charSet);
	$result .= self::LE;
	$result .= self::HeaderLine("Content-Transfer-Encoding", $encoding);
	$result .= self::LE;
   
	return $result;
}

private static /*. string .*/ function EncodeQP (/*. string .*/ $str)
/*
	Encode string to quoted-printable.  
*/
{
	$encoded = self::FixEOL($str);
	if (substr($encoded, -(strlen(self::LE))) != self::LE)
		$encoded .= self::LE;

	// Replace every high ascii, control and = characters
	$encoded = preg_replace('/([\\000-\\010\\013\\014\\016-\\037\\075\\177-\\377])/e',
			  "'='.sprintf('%02X', ord('\\1'))", $encoded);
	// Replace every spaces and tabs when it's the last character on a line
	$encoded = preg_replace("/([\\011\\040])".self::LE."/e",
			  "'='.sprintf('%02X', ord('\\1')).'".self::LE."'", $encoded);

	// Maximum line length of 76 characters before CRLF (74 + space + '=')
	$encoded = self::WrapText($encoded, 74, TRUE);

	return $encoded;
}

private /*. string .*/ function EncodeString (/*. string .*/ $str, $encoding = "base64")
/*
	Encodes string to requested format. Returns an
	empty string on failure.
*/
{
	$encoded = "";
	switch(strtolower($encoding)) {
	  case "base64":
		  // chunk_split is found in PHP >= 3.0.6
		  $encoded = chunk_split(base64_encode($str), 76, self::LE);
		  break;
	  case "7bit":
	  case "8bit":
		  $encoded = self::FixEOL($str);
		  if (substr($encoded, -(strlen(self::LE))) != self::LE)
			$encoded .= self::LE;
		  break;
	  case "binary":
		  $encoded = $str;
		  break;
	  case "quoted-printable":
		  $encoded = self::EncodeQP($str);
		  break;
	  default:
		  $this->SetError($this->Lang("encoding") . $encoding);
		  break;
	}
	return $encoded;
}

private static /*. string .*/ function EndBoundary(/*. string .*/ $boundary)
/*
	Returns the end of a message boundary.
*/
{
	return self::LE . "--" . $boundary . "--" . self::LE; 
}

/*. bool .*/ function IsError()
/*. DOC Returns TRUE if an error occurred.  .*/
{
	return ($this->error_count > 0);
}

private /*. string .*/ function EncodeFile (/*. string .*/ $path, $encoding = "base64")
/*
	Encodes attachment in requested format.  Returns an empty string
	on failure.
*/
{
	if( ($fd = @fopen($path, "rb")) === FALSE )
	{
		$this->SetError($this->Lang("file_open") . $path);
		return "";
	}
	$magic_quotes = get_magic_quotes_runtime();
	set_magic_quotes_runtime(0);
	$file_buffer = fread($fd, filesize($path));
	$file_buffer = $this->EncodeString($file_buffer, $encoding);
	fclose($fd);
	set_magic_quotes_runtime($magic_quotes);

	return $file_buffer;
}

private /*. string .*/ function AttachAll()
/*
	Attaches all fs, string, and binary attachments to the message.
	Returns an empty string on failure.
*/
{
	// Return text of body
	$mime = /*. (array[int]string) .*/ array();

	// Add all attachments
	for( $i = 0; $i < count($this->attachment); $i++ ){
		$att = $this->attachment[$i];

		$mime[] = sprintf("--%s%s", $this->boundary[1], self::LE);
		$mime[] = sprintf("Content-Type: %s; name=\"%s\"%s",
			$att->type, $att->name, self::LE);
		$mime[] = sprintf("Content-Transfer-Encoding: %s%s",
			$att->encoding, self::LE);

		if( $att->disposition === "inline" )
			$mime[] = sprintf("Content-ID: <%s>%s", $att->cid, self::LE);

		$mime[] = sprintf("Content-Disposition: %s; filename=\"%s\"%s", 
			$att->disposition, $att->name, self::LE.self::LE);

		// Encode as string attachment
		if( $att->isStringAttachment )
			$mime[] = $this->EncodeString($att->path, $att->encoding);
		else
			$mime[] = $this->EncodeFile($att->path, $att->encoding);
		if( $this->IsError() )
			return "";
		$mime[] = self::LE.self::LE;
	}

	$mime[] = sprintf("--%s--%s", $this->boundary[1], self::LE);

	return join("", $mime);
}

private /*. string .*/ function CreateBody()
/*
	Assembles the message body.  Returns an empty string on failure.
*/
{
	$result = "";

	$this->SetWordWrap();

	switch($this->message_type)
	{
		case "alt":
			$result .= $this->GetBoundary($this->boundary[1], "", 
										  "text/plain", "");
			$result .= $this->EncodeString($this->AltBody, $this->Encoding);
			$result .= self::LE.self::LE;
			$result .= $this->GetBoundary($this->boundary[1], "", 
										  "text/html", "");
			
			$result .= $this->EncodeString($this->Body, $this->Encoding);
			$result .= self::LE.self::LE;

			$result .= self::EndBoundary($this->boundary[1]);
			break;
		case "plain":
			$result .= $this->EncodeString($this->Body, $this->Encoding);
			break;
		case "attachments":
			$result .= $this->GetBoundary($this->boundary[1], "", "", "");
			$result .= $this->EncodeString($this->Body, $this->Encoding);
			$result .= self::LE;
 
			$result .= $this->AttachAll();
			break;
		case "alt_attachments":
			$result .= sprintf("--%s%s", $this->boundary[1], self::LE);
			$result .= sprintf("Content-Type: %s;%s" .
							   "\tboundary=\"%s\"%s",
							   "multipart/alternative", self::LE, 
							   $this->boundary[2], self::LE.self::LE);

			// Create text body
			$result .= $this->GetBoundary($this->boundary[2], "", 
										  "text/plain", "") . self::LE;

			$result .= $this->EncodeString($this->AltBody, $this->Encoding);
			$result .= self::LE.self::LE;

			// Create the HTML body
			$result .= $this->GetBoundary($this->boundary[2], "", 
										  "text/html", "") . self::LE;

			$result .= $this->EncodeString($this->Body, $this->Encoding);
			$result .= self::LE.self::LE;

			$result .= self::EndBoundary($this->boundary[2]);
			
			$result .= $this->AttachAll();
			break;
		/*. missing_default: .*/
	}
	if($this->IsError())
		$result = "";

	return $result;
}

private /*. bool .*/ function SendmailSend(/*. string .*/ $header, /*. string .*/ $body)
/*
	Sends mail using the $Sendmail program.  
*/
{
	if ($this->Sender != "")
		$sendmail = sprintf("%s -oi -f %s -t", $this->Sendmail, $this->Sender);
	else
		$sendmail = sprintf("%s -oi -t", $this->Sendmail);

	if( ($mail = @popen($sendmail, "w")) === FALSE )
	{
		$this->SetError($this->Lang("execute") . $this->Sendmail);
		return FALSE;
	}

	fputs($mail, $header);
	fputs($mail, $body);
	
	$result = pclose($mail) >> 8 & 0xFF;
	if($result != 0)
	{
		$this->SetError($this->Lang("execute") . $this->Sendmail);
		return FALSE;
	}

	return TRUE;
}

private /*. bool .*/ function MailSend(/*. string .*/ $header, /*. string .*/ $body)
/*
	Sends mail using the PHP mail() function.  
*/
{
	$to = "";
	for($i = 0; $i < count($this->to); $i++)
	{
		if($i != 0) { $to .= ", "; }
		$to .= $this->to[$i][0];
	}

	if ($this->Sender != "" && strlen(ini_get("safe_mode"))< 1)
	{
		$old_from = ini_get("sendmail_from");
		ini_set("sendmail_from", $this->Sender);
		$params = sprintf("-oi -f %s", $this->Sender);
		$rt = @mail($to, $this->EncodeHeader($this->Subject), $body, 
					$header, $params);
	}
	else
		$rt = @mail($to, $this->EncodeHeader($this->Subject), $body, $header);

	if (isset($old_from))
		ini_set("sendmail_from", $old_from);

	if(!$rt)
	{
		$this->SetError($this->Lang("instantiate"));
		return FALSE;
	}

	return TRUE;
}

private /*. void .*/ function SmtpConnect()
/*
	Initiates a connection to an SMTP server.
	Raises an ErrorException if none of the hosts accepted
	the connection and possibly the user authentication.

	FIXME: if debug enabled, log the actual server used and those
	that failed.
*/
{
	if($this->smtp == NULL) { $this->smtp = new SMTP(); }

	// Connection+authentication already available?
	if( $this->smtp->Connected() )
		return;

	// Try all the hosts in turn:
	$this->smtp->debug = $this->SMTPDebug;
	$hosts = explode(";", $this->Host);
	if ( count($hosts) < 1 )
		throw new ErrorException("empty SMTP hosts list");
	for( $i = 0; $i < count($hosts); $i++ ){

		if(strstr($hosts[$i], ":") !== FALSE){
			$a = explode(":", $hosts[$i]);
			$host = $a[0];
			$port = (int) $a[1];

		} else {
			$host = $hosts[$i];
			$port = 25;
		}

		try {

			$this->smtp->Connect($host, $port, $this->Timeout);

			if ($this->Helo != '')
				$this->smtp->Hello($this->Helo);
			else
				$this->smtp->Hello($this->ServerHostname());
	
			if($this->SMTPAuth)
				$this->smtp->Authenticate($this->Username, $this->Password);

			return;
		}
		catch ( ErrorException $e ) {
			if ( $this->smtp->Connected() ){
				try {
					$this->smtp->Quit();
					$this->smtp->Close();
				} catch ( ErrorException $e ) { }
			}
			$this->smtp = NULL;
		}
	}

	// All the hosts failed -- propagate last exception:
	throw $e;
}

/*. void .*/ function SmtpClose()
/*. DOC Closes the active SMTP session if one exists.  .*/
{
	if($this->smtp != NULL)
	{
		if($this->smtp->Connected())
		{
			$this->smtp->Quit();
			$this->smtp->Close();
		}
		$this->smtp = NULL;
	}
}

/*. bool .*/ function SmtpSend(/*. string .*/ $header, /*. string .*/ $body)
/*. DOC Sends mail via SMTP using PhpSMTP.
	<@author Chris Ryan>

	FIXME: should return the list of invalid recipients.
.*/
{
	$rcpt_total = count($this->to) + count($this->cc) + count($this->bcc);
	if( $rcpt_total == 0 )
		throw new Exception("no TO/CC/BCC recipients");

	$bad_rcpt = /*. (array[int]string) .*/ array();

	$this->SmtpConnect();

	$smtp_from = (strlen($this->Sender) == 0) ? $this->From : $this->Sender;
	try {
		$this->smtp->Mail($smtp_from);
	}
	catch ( ErrorException $e )
	{
		$this->smtp->Reset();
		throw $e;
	}

	// Attempt to send all recipients
	for($i = 0; $i < count($this->to); $i++)
	{
		$to = $this->to[$i][0];
		try { $this->smtp->Recipient($to); }
		catch ( ErrorException $e ){
			$bad_rcpt[] = $to;
		}
	}
	for($i = 0; $i < count($this->cc); $i++)
	{
		$to = $this->cc[$i][0];
		try { $this->smtp->Recipient($to); }
		catch ( ErrorException $e ){
			$bad_rcpt[] = $to;
		}
	}
	for($i = 0; $i < count($this->bcc); $i++)
	{
		$to = $this->bcc[$i][0];
		try { $this->smtp->Recipient($to); }
		catch ( ErrorException $e ){
			$bad_rcpt[] = $to;
		}
	}

	if(count($bad_rcpt) > 0) // Create error message
	{
		$this->SetError( $this->Lang("recipients_failed")
			. implode(", ", $bad_rcpt) );
		$this->smtp->Reset();
		return FALSE;
	}

	$this->smtp->Data($header . $body);

	if($this->SMTPKeepAlive == TRUE)
		$this->smtp->Reset();
	else
		$this->SmtpClose();

	return TRUE;
}

/*. bool .*/ function Send()
/*. DOC Creates message and assigns <@item ::$Mailer>.
	If the message is not sent successfully then it returns
	FALSE.	Use the <@item ::$ErrorInfo> variable to view
	description of the error.
.*/
{
	if((count($this->to) + count($this->cc) + count($this->bcc)) < 1)
	{
		$this->SetError($this->Lang("provide_address"));
		return FALSE;
	}

	// Set whether the message is multipart/alternative
	if(!empty($this->AltBody))
		$this->ContentType = "multipart/alternative";

	$this->error_count = 0; // reset errors
	$this->SetMessageType();
	$header = $this->CreateHeader();
	$body = $this->CreateBody();

	if($body === "") { return FALSE; }

	// Choose the mailer
	switch($this->Mailer)
	{
		case "sendmail":
			$result = $this->SendmailSend($header, $body);
			break;
		case "mail":
			$result = $this->MailSend($header, $body);
			break;
		case "smtp":
			$result = $this->SmtpSend($header, $body);
			break;
		default:
			$this->SetError($this->Mailer . $this->Lang("mailer_not_supported"));
			$result = FALSE;
			break;
	}

	return $result;
}

/*. void .*/ function AddAttachment(/*. string .*/ $path, $name = "", $encoding = "base64", $type = "application/octet-stream")
/*. DOC Adds an attachment from a path on the filesystem.
	$path is the path where the file is stored.
	$name is the name the recipient of the message will see, possibly
	different from the base name of the original file $path.
	$type is the MIME type of the file.
	This example will add an image as an attachment:
	<p>

	<code>
	$img = "mypicts/summer/flower.png";<br>
	$m-&gt;AddAttachment($img, basename($img),
		"base64", mime_content_type($img));
	</code>

	<p>
	WARNING! Do not rely on the default generic application/octet-stream
	MIME type, always provide a proper type that match the contents of
	the file. The function mime_content_type() from the mime_maginc PHP
	extension does just this.
	</p>
.*/
{
	$att = new PHPMailer_Attachment();

	$att->isStringAttachment = FALSE;
	$att->path = $path;
	$att->filename = basename($path);
	$att->name = empty($name)? basename($path) : $name;
	$att->encoding = $encoding;
	$att->type = $type;
	$att->disposition = "attachment";
	$att->cid = "0";

	$this->attachment[] = $att;
}


/*. void .*/ function AddStringAttachment(/*. string .*/ $str, /*. string .*/ $filename, $encoding = "base64", $type = "application/octet-stream")
/*. DOC Adds a string or binary attachment (non-filesystem) to the list.
	This method can be used to attach ASCII or binary data,
	such as a BLOB record from a database.
.*/
{
	$att = new PHPMailer_Attachment();

	$att->isStringAttachment = TRUE;
	$att->path = $str;
	$att->filename = $filename;
	$att->name = $filename;
	$att->encoding = $encoding;
	$att->type = $type;
	$att->disposition = "attachment";
	$att->cid = "0";

	$this->attachment[] = $att;
}

/*. void .*/ function AddEmbeddedImage(/*. string .*/ $path, /*. string .*/ $cid, $name = "", $encoding = "base64", $type = "application/octet-stream")
/*. DOC Adds an embedded attachment, typically an image

	This can include images, sounds, and just about any other document a
	mail client can display embedded inside an HTML document.  Make sure
	to set the proper MIME $type.  A $name for the file can be provided;
	it will be proposed to the recipient uses in case he want to save the
	embedded content into a file.
	NOTE: probably this method should have been called
	<code>AddEmbeddedAttachment()</code> instead, but since images are the
	typical embedded content, the chosen name seems to be more appropriate.

	<p>
	<b>Parameters</b><br>
	$path is the pathfile of the attachment.<br>
	$cid is the content ID of the attachment to be used in a URL
	of the type cid:ID. Every embedded attachment must have a different
	content ID.<br>
	$name overrides the attachment name.<br>
	$encoding is the file encoding used to include the file
	inside the email body, typically "base64" (see $Encoding).<br>
	$type is the file MIME type.<br>

	<p>
	<b>Example</b><br>
	<code>
	$m = new PHPMailer();<br>
	$m-&gt;IsHTML();<br>
	$cid = "1";<br>
	$m-&gt;Body = "&lt;html&gt;&lt;body&gt;Photo: &lt;img src='cid:$cid'&gt; &lt;/body&gt;&lt;/html&gt;";<br>
	$m-&gt;AddEmbeddedImage("photo.jpg", $cid, "base64", "image/jpeg");
	</code>

.*/
{
	$att = new PHPMailer_Attachment();

	$att->path = $path;
	$att->filename = basename($path);
	$att->name = empty($name)? basename($path) : $name;
	$att->encoding = $encoding;
	$att->type = $type;
	$att->isStringAttachment = FALSE;
	$att->disposition = "inline";
	$att->cid = $cid;

	$this->attachment[] = $att;
}

/////////////////////////////////////////////////
// MESSAGE RESET METHODS
/////////////////////////////////////////////////

/*. void .*/ function ClearAddresses()
/*. DOC Clears all recipients assigned in the TO array.  .*/
{
	$this->to = NULL;
}

/*. void .*/ function ClearCCs()
/*. DOC Clears all recipients assigned in the CC array. .*/
{
	$this->cc = NULL;
}

/*. void .*/ function ClearBCCs()
/*. DOC Clears all recipients assigned in the BCC array. .*/
{
	$this->bcc = NULL;
}

/*. void .*/ function ClearReplyTos()
/*. DOC Clears all recipients assigned in the ReplyTo array. .*/
{
	$this->ReplyTo = NULL;
}

/*. void .*/ function ClearAllRecipients()
/*. DOC Clears all recipients assigned in the TO, CC and BCC array. .*/
{
	$this->to = NULL;
	$this->cc = NULL;
	$this->bcc = NULL;
}

/*. void .*/ function ClearAttachments()
/*. DOC Clears all previously set filesystem, string, and binary attachments. .*/
{
	$this->attachment = NULL;
}

/*. void .*/ function ClearCustomHeaders()
/*. DOC Clears all custom headers. .*/
{
	$this->CustomHeader = NULL;
}


}

?>
