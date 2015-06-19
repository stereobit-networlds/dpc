<?php

class smtp_mail {

var $fp = false;
var $lastmsg = "";

//reads a line from the socket and returns the numeric code and the rest of the line
function read_line() {
  $ret = false;
  
  $line = fgets($this->fp,1024);
  
  if (ereg("^([0-9]+).(.*)$", $line, $data)) {
    $recv_code = $data[1];
	$recv_msg = $data[2];
	
	$ret = array($recv_code,$recv_msg);
  }
  
  return $ret;
} 


//send a command $cmd to the remote server and checks whether the numeric return code is the expected
function dialogue($code,$cmd) {
  $ret = true;
  
  fwrite($this->fp, $cmd."\r\n");
  
  $line = $this->read_line($this_fp);
  
  if ($line == false) {
    $ret = false;
	$this->lastmsg = "";
  }
  else {
    $this->lastmsg = "$line[0] $line[1]";
	
	if ($line[0] != $code) {
	  $ret = false;
	}
  }
  
  return $ret;
}

//error message
function error_message() {
  setInfo("SMTP protocol failure (".$this->lastmsg.").");
}


//fixes line endings - RFC 788 specifies CRLF as line endings
function crlf_encode($data) {
  $data .= "\n";
  $data = str_replace("\n","\r\n", str_replace("\r", "", $data));
  $data = str_replace("\n.\r\n", "\n. \r\n", $data);
  
  return $data;
}


//talks to SMTP server
function handle_email($from,$to,$data) {
  //split recipient list
  $rcpts = explode(",", $to);
  
  $err = false;
  if (!$this->dialogue(250, "HELO phpclient") ||
      !$this->dialogue(250, "MAIL FROM:$from")) {
	$err = true;  
  }
  
  for ($i = 0; !$err && $i < count($rcpts); $i++) {
    if (!$this->dialogue(250, "RCPT TO:$rcpts[$i]")) {
	  $err = true;
	}
  }
  
  if ($err || !$this->dialogue(354, "DATA") ||
      !fwrite($this->fp,$data) ||
	  !$this->dialogue(250, ".") ||
	  !$this->dialogue(221, "QUIT")) {
	  
	$err = true; 
	echo 'ZZZZZ'; 
  }
  	  
  if ($err) {
    $this->error_message();
  }	  
  
  return ($err);
}


//connects to an SMTP server on port 25
function connect($hostname) {
  $ret = false;
  
  $this->fp = fsockopen($hostname, 25);
  
  if ($this->fp) {
    $ret = true;
  }  
  
  return $ret;
}


//connect to an SMTP server, encodes the message optionally and sends $data
function send_email($hostname, $from, $to, $data, $crlf_encode = 0) {

  if (!$this->connect($hostname)) {
    setInfo("cannot open socket");
	return false;
  }
  
  $line = $this->read_line();
  $ret = false;
  
  if ($line && $line[0] == "220") {
    if ($crlf_encode) {
	  $data = $this->crlf_encode($data);
	}
	$ret = $this->handle_email($from, $to, $data);
  }
  else {
    $this->error_message();
  }
  
  fclose($this->fp);
  
  return $ret;
}

}; //end of class
?>