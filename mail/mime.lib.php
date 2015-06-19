<?php

class mime_mail {

var $parts;
var $to;
var $from;
var $headers;
var $subject;
var $body;


//class constructor
function mime_mail($ctype='') {
  $this->parts   = array();
  $this->to      = "";
  $this->from    = "";
  $this->subject = "";
  $this->body    = "";
  $this->headers = "";
  
  $this->ctype = $ctype;
}

//add attachements to mail objects
function add_attachment($message,$name = "",$ctype = "application/octet-stream") {
  $this->parts[] = array (
                           "ctype" => $ctype,
						   "message" => $message,
						   "encode" => $encode,
						   "name" => $name
						 ); 

}

//build message parts of an multipart mail
function build_message($part) {
  $message = $part["message"];
  $message = chunk_split(base64_encode($message));
  $encoding = "base64"; 
  
  //return ("Content-Type: text/html " . "\nContent-Transfer-Encoding: $encoding\n\n$message\n");
  if ($this->ctype) $part[ "ctype" ] = $this->ctype;
  
  
  return "Content-Type: ". $part[ "ctype" ].
    ($part[ "name" ]? "; name = \"".$part[ "name" ]."\"" : "") . "\nContent-Transfer-Encoding: $encoding\n\n$message\n";
}

//build a multipart mail
function build_multipart() {
  $boundary = "b".md5(uniqid(time()));
  $multipart = "Content-Type: multipart/mixed; boundary = $boundary\n\nThis is a MIME encoded message.\n\n--$boundary";
  
  for ($i = sizeof($this->parts)-1; $i >= 0; $i--) {
    $multipart .= "\n".$this->build_message($this->parts[$i])."--$boundary";
  }
  
  return $multipart .= "--\n";
}


//returns the constructed mail
function get_mail($complete = true) {
  $mime = "";
  if (!empty($this->from))
    $mime .= "From: ".$this->from."\n";
  if (!empty($this->headers))
    $mime .= $this->headers."\n";
  
  if ($complete) {
    if (!empty($this->to)) {
      $mime .= "To: $this->to\n";
	}
    if (!empty($this->subject)) {
      $mime .= "Subject: $this->subject\n";
	}	  
  }
  
  if (!empty($this->body))
    $this->add_attachment($this->body, "", "text/plain");
  $mime .= "MIME-Version: 1.0\n".$this->build_multipart();

  return $mime;		
}


//send the mail
function send() {
  $mime = $this->get_mail(false);
  if (@mail($this->to, $this->subject, "", $mime)) setInfo("ok");
                                             else setInfo("Error!"); 
}

}; //end of class
?>