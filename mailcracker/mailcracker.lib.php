<?php
//text_funcs.php at crackermail

class cracker {

  var $type;
  var $encoding;

  function cracker() {
  
   // define some constants
   // message types
   $this->type = array("text", "multipart", "message", "application", "audio", "image", "video", "other");
   // message encodings
   $this->encoding = array("7bit", "8bit", "binary", "base64", "quoted-printable", "other");
  
  }
//get text functions

//get mime type
function get_mime_type(&$structure) {

	$primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
	
	if ($structure->subtype) {
		return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
	}
	
	return "TEXT/PLAIN";

}
//end get mime type

//get message text
function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false) {

	if (!$structure) {

		$structure = imap_fetchstructure($stream, $msg_number);

	}

	if ($structure) {

		if($mime_type == $this->get_mime_type($structure)) {
			if(!$part_number) {
				$part_number = "1";
   			}
   			$text = imap_fetchbody($stream, $msg_number, $part_number);
   			if($structure->encoding == 3) {
   				return imap_base64($text);
   			} else if($structure->encoding == 4) {
   				return imap_qprint($text);
   			} else {
   				return $text;
   			}
   		}
   
		if($structure->type == 1) /* multipart */ {
   			while(list($index, $sub_structure) = each($structure->parts)) {
   				if($part_number) {
   					$prefix = $part_number . '.';
   				}
   				$data = $this->get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
   				if($data) {
   					return $data;
   				}
   			} // end while
   		} // end multipart
   	} // end structure
   	return false;
}
//end get message text
//end get text functions


//get attachment functions

// define some constants
// message types
//$type = array("text", "multipart", "message", "application", "audio", "image", "video", "other");
// message encodings
//$encoding = array("7bit", "8bit", "binary", "base64", "quoted-printable", "other");
//MOVED TO CONSTRUCTOR

// parse message body
function parse($structure) {
	//global $type;
	//global $encoding;

	// create an array to hold message sections
	$ret = array();

	// split structure into parts
	$parts = $structure->parts;

	for($x = 0; $x < sizeof($parts); $x++) {
		$ret[$x]["pid"] = ($x+1);

		$__this = $parts[$x];

		// default to text
		if ($__this->type == "") { $__this->type = 0; }
		$ret[$x]["type"] = $this->type[$__this->type] . "/" . strtolower($__this->subtype);

		// default to 7bit
		if ($__this->encoding == "") { $__this->encoding = 0; }
		$ret[$x]["encoding"] = $this->encoding[$__this->encoding];

		$ret[$x]["size"] = strtolower($__this->bytes);

		$ret[$x]["disposition"] = strtolower($__this->disposition);

		if (strtolower($__this->disposition) == "attachment") {
			$params = $__this->dparameters;
			foreach ($params as $p) {
				if ($p->attribute == "FILENAME") {
					$ret[$x]["name"] = $p->value;
					break;
				}
			}
		}
	}

	return $ret;

}

// iterate through object returned by parse()
// create a new array holding information only on message attachments
function get_attachments($arr) {

	unset($ret);

	for ($x = 0; $x < sizeof($arr); $x++) {
	
		if($arr[$x]["disposition"] == "attachment") {
			$ret[] = $arr[$x];
		}

	}
	
	return $ret;

}
//end get attachment functions


//char replace - able to replace arrays of characters
function char_replace($bad_char, $good_char, $str) {
	if (is_array($bad_char)) {
		for ($i = 0; $i < sizeof($bad_char); $i++) {
			$str = ereg_replace($bad_char[$i], $good_char, $str);
		}
	}
	else {
			$str = ereg_replace($bad_char, $good_char, $str);
	}
	return $str;
}

//get headers
function __get_headers($mbox, $msgno) {
	if ($headers = @imap_headerinfo($mbox, $msgno)) {
		if ($headers->fromaddress) {
			$fromAddr = $headers->fromaddress;
			if (strstr(trim($fromAddr), "<")) {
				$fromAddr = strstr(trim($fromAddr), "<");
				$fromAddr = $this->char_replace(array("<",">"), "", $fromAddr);
			}
			$header_info['fromAddr'] = $fromAddr;
			$fromName = $headers->fromaddress;
			if (strstr($fromName, "<")) {
				$pos1 = strrpos($fromName, "<");
				$fromName = trim(substr($fromName, 0, $pos1-1));
				$fromName = $this->char_replace(array("<",">","\"","'"), "", $fromName);
			}
			$header_info['fromName'] = $fromName;
		}
		unset($to);
		if ($headersTo = $headers->to) {
			if (sizeof($headersTo) > 1) {
				$toMailbox = $headersTo[0]->mailbox . "@" . $headersTo[0]->host;
				if (!strstr($toMailbox, "UNEXPECTED_DATA")) {
					$to .= $toMailbox;
				}
				for ($i = 1; $i < sizeof($headersTo)-1; $i++) {
					$toMailbox = $headersTo[$i]->mailbox . "@" . $headersTo[$i]->host;
					if (!strstr($toMailbox, "UNEXPECTED_DATA")) {
						$to .= ", " . $toMailbox;
					}
				}
				$header_info['to'] = $to;
			}
			else {
				$header_info['to'] = $headersTo[0]->mailbox . "@" . $headersTo[0]->host;
			}
		}
		else {
			$header_info['to'] = "&nbsp;";
		}
		unset($cc);
		if ($headersCc = $headers->cc) {
			if (sizeof($headersCc) > 1) {
				for ($i = 0; $i < sizeof($headersCc)-1; $i++) {
					$ccMailbox = $headersCc[$i]->mailbox . "@" . $headersCc[$i]->host;
					$cc .= $ccMailbox . ", ";
				}
				$ccMailbox = $headersCc[sizeof($headersCc)-1]->mailbox . "@" . $headersCc[sizeof($headersCc)-1]->host;
				$cc .= $ccMailbox;
				$header_info['cc'] = $cc;
			}
			else {
				$header_info['cc'] = $headersCc[0]->mailbox . "@" . $headersCc[0]->host;
			}
		}
		if ($headers->Date) {
			$header_info['date'] = htmlspecialchars($headers->Date);
		}
		else {
			$header_info['date'] = "&nbsp;";
		}
		if ($headers->subject) {
			$header_info['subject'] = htmlspecialchars($headers->subject);
		}
		else {
			$header_info['subject'] = "{none}";
		}
	}
	else {
		$header_info = false;
	}
	return $header_info;
}
//end get headers
}
?>