<?php

$__DPCSEC['SMSGUI_DPC']='1;1;1;1;1;1;1;1;2';

if ( (!defined("SMSGUI_DPC")) && (seclevel('SMSGUI_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SMSGUI_DPC",true);

$__DPC['SMSGUI_DPC'] = 'smsgui';

$__EVENTS['SMSGUI_DPC'][0]='smsgui';

$__ACTIONS['SMSGUI_DPC'][0]='smsgui';

$__LOCALE['SMSGUI_DPC'][0]='SMSGUI_DPC;Sms gui;Sms gui';


class smsgui {

    var $userLevelID;
	var $tolist,$smsgui_url;
	var $user,$pwd;
	var $pxhost,$pxport;
	
	var $content;

	function smsgui() {
	   $UserSecID = GetGlobal('UserSecID');
       
       $this->tolist = paramload('SMSGUI','to');
	   $this->smsgui_url = paramload('SMSGUI','url');
	   
	   $this->user = paramload('SMSGUI','user');
	   $this->pwd = paramload('SMSGUI','pwd');
	   
	   $this->pxhost = paramload('SMSGUI','proxyname');
	   $this->pxport = paramload('SMSGUI','proxyport');
	   
	   $this->title = localize('SMSGUI_DPC',getlocal());
	   
	   $this->content = null;	
	}

    function event($evn=null) {
	}	

	function action($act=null) {

	    $this->sendsms("Test message",null,1);
			
	    return ($out);
	}
	
	
	
/*
This is an example PHP script, to be used to send SMS through smsgui.com
Tries to send through HTTP GET, the parameters you specify.
These parameters are required:
- $userid (your user ID, as defined on smsgui.com)
- $md5password (your MD5-hashed password)
- $to (destination groups and/or individual numbers)
- $message (the message you want to send)
Please note that SMS won't be sent, and you get back an error,
if any of the above parameters are missing, or invalid.
Other accepted parameters:
- $from (the sender of SMS; default is: smsgui.com)
- $lang (language, "en" - English, "gr" - Greek, "ucs2" or "utf8" - Unicode; default is: en)
(note that for Unicode, the maximum accepted message length, is 70)
- $validity (SMS validity period; default is: 12 hours)
- $flash (yes/no option; default is: no)
- $scheduled (date and time when SMS should be sent. Accepted format: YYYYMMDDhhmm)
- $rid (request ID - your internal code, which is sent back )
The returned result string contains no header, nor HTML code,
just simple text, from which the very first character can be:
0 - Error (some kind of critical error was encountered)
1 - Warning (request was accepted, but with warnings)
2 - OK (request was successfully accepted)
For more information, visit http://www.smsgui.com,
or send us e-mail to info@smsgui.com
*/	
	function sendsms($smsmessage,$to=null,$echoing=0) {
	
	  if ($to)
	    $sendto = substr_replace($to,'+',0,0); //add + at the beggining of the to
	  else 
	    $sendto = $this->tolist;//+ included	
		
      // your user ID on smsgui.com
      $userid = rawurlencode($this->user);
      // md5 is used to hide the clear-text password when transmitted over the Internet
      $md5password = md5($this->pwd);
      // comma-separated list of group names, and/or mobile numbers in international format
      $to = rawurlencode( $sendto );
      // message to send
      $message = rawurlencode($smsmessage);
      // if you want to send a message Unicode-encoded in UCS2, you first need to convert
      // the message from UTF-8 (assuming you have UTF-8 encoding in the HTML page, or whatever source)
      //$message = bin2hex( iconv("UTF-8","UTF-16BE","YOUR_MESSAGE") ); // UTF-16BE = UCS-2
      $lang = 'ucs2';
      // the smsgui.com URL you are trying to send the parameters to (no need to edit)
      $url = 'http://www.smsgui.com/send.php';
      $fullUrl = "$url?userid=$userid&md5password=$md5password&to=$to&message=$message";//&lang=$lang";
	  //echo $fullUrl;
      // try to send the parameters to smsgui.com
      // if the connection is successful, the result is received in the $contents array
      $this->contents = @file( $fullUrl );
      if( !$contents ) {
	    //echo "<br>Error accessing URL: $fullUrl";
		if ($echoing) echo "Error: sending SMS!";
	  }	
      else {
        $code = $contents[0][0]; // read just the very first character from the result
		if ($echoing) {
          switch( $code ){
			case '0': echo "Error: " . $this->contents[0]; break;
			case '1': echo "Warning: " . $this->contents[0]; break;
			case '2': echo "OK: " . $this->contents[0]; break;
			default : echo "$code - Read failed, or unrecognized code!";
          }
		}
      }
	  
	  return ($code . $this->contents[0]);
	}
	
	function getmessage() {
	
	  return $this->contents[0];
	}
	
	function socketsendsms($smsmessage,$to=null,$echoing=0) {
	
      // parse the url
	  $url = 'http://www.smsgui.com/send.php';
      $url_parsed=parse_url($url);        
	  
	  if ($to)
	    $sendto = $to;
	  else 
	    $sendto = $this->tolist;	  
	  
      $userid = rawurlencode($this->user);	
      $md5password = md5($this->pwd);
      $to = rawurlencode( $sendto );	  	
      $message = rawurlencode($smsmessage);	      
	  $get_file = "send.php?";
	  $get_string = "userid=$userid&md5password=$md5password&to=$to&message=$message";
	  $get_uri = $get_file . $get_string;
	  
	  $numberofseconds = 30;

      // open the connection to paypal
      $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,$numberofseconds); 
      if(!$fp) {
          
         // could not open the connection.  If loggin is on, the error message
         // will be in the log.
		 if ($echoing) echo "fsockopen error no. $errnum: $errstr";      
         return false;
         
      } else { 
 
         //fputs($fp, "GET $get_uri HTTP/1.1\r\n"); 
         //fputs($fp, "Host: $url_parsed[host]\r\n"); 
         //fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
         //fputs($fp, "Content-length: ".strlen($get_string)."\r\n"); 
         //fputs($fp, "Connection: close\r\n\r\n"); 
         //fputs($fp, $get_string . "\r\n\r\n"); 
		 
		 $resourcePath = $get_uri;
		 $domain = $url_parsed[host];
         fputs($socketConnection, "GET /$resourcePath HTTP/1.0\r\nHost: $domain\r\n\r\n");
		 

         // loop through the response from the server and append to variable
         while(!feof($fp)) { 
            $response .= fgets($fp, 128); 
         } 

         fclose($fp); // close connection

      }
      
      if (eregi("2",$response)) {
         if ($echoing) echo "ok " . $response;
         return true;       
         
      } else {
  
         if ($echoing) echo "error " . $response;
         return false;
         
      }	
	}
	
	function remote_sms($smsmessage,$to=null,$echoing=0) {
	
	  if ((defined("_HTTPCL_"))) {
	  
        // parse the url
	    $url = 'http://www.smsgui.com/send.php';
        $url_parsed=parse_url($url); 	  
	  
	    if ($to)
	      $sendto = $to;
	    else 
	      $sendto = $this->tolist;	  
	  
        $userid = rawurlencode($this->user);	
        $md5password = md5($this->pwd);
        $to = rawurlencode( $sendto );	  	
        $message = rawurlencode($smsmessage);	      
	    $get_file = "send.php?";
	    $get_string = "userid=$userid&md5password=$md5password&to=$to&message=$message";
	    $get_uri = $get_file . $get_string;
	 	  
	    //echo 'remote log:',$dpc;
        $server_http_connection = new http_class;	    
        $error = $server_http_connection->Open(array("HostName"=>$url_parsed[host],
	                                                 "HostPort"=>80,
													 "ProxyHostName"=>$this->pxhost,
													 "ProxyHostPort"=>$this->pxport));
        if (!$error) {
         $ret = $server_http_connection->SendRequest(array(
                                                          "RequestURI"=>$get_uri,
                                                          "Headers"=>array(
                                                          "Host"=>$url_parsed[host].":80",
                                                          "User-Agent"=>"phpdac",
                                                          "Pragma"=>"no-cache"
														 ))); 	
		}												 
        else {
		  if ($echoing) echo $error;														 
		}  
		$server_http_connection->Close();												 
														 		
	  }
	  else
	    echo 'HTTPCL not loaded!';													 
	}	

};
}
?>