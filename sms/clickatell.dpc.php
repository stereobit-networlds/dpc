<?php

$__DPCSEC['CLICKATELL_DPC']='1;1;1;1;1;1;1;1;2';

if ( (!defined("CLICKATELL_DPC")) && (seclevel('CLICKATELL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CLICKATELL_DPC",true);

$__DPC['CLICKATELL_DPC'] = 'clickatell';

$__EVENTS['CLICKATELL_DPC'][0]='clickatell';

$__ACTIONS['CLICKATELL_DPC'][0]='clickatell';

$__LOCALE['CLICKATELL_DPC'][0]='CLICKATELL_DPC;ClickaTell;ClickaTell';


class clickatell {

    var $userLevelID;
	var $tolist,$smsgui_url;
	var $user,$pwd;
	var $pxhost,$pxport;
	
	var $content;
	var $httpapi;

	function clickatell() {
	   $UserSecID = GetGlobal('UserSecID');
       
       $this->tolist = paramload('CLICKATELL','to');
	   $this->smsgui_url = paramload('CLICKATELL','url');
	   
	   $this->user = paramload('CLICKATELL','user');
	   $this->pwd = paramload('CLICKATELL','pwd');
	   
	   $this->pxhost = paramload('CLICKATELL','proxyname');
	   $this->pxport = paramload('CLICKATELL','proxyport');
	   
	   $this->title = localize('CLICKATELL_DPC',getlocal());
	   
	   $this->httpapi = paramload('CLICKATELL','httpapi');	   
	   
	   $this->content = null;	
	}

    function event($evn=null) {
	}	

	function action($act=null) {

	    $this->sendsms("Test message",null,1);
			
	    return ($out);
	}
	
	
	/*
	user=balexiou
    password=<Clickatell Account Password>
    api_id=3239691
    to=<Mobile Number(s)> (comma separated)
    text=<SMS Message>	 
	*/
	function sendsms($smsmessage,$to=null,$echoing=0) {
	
	  if ($to)
	    $sendto = substr_replace($to,'+',0,0); //add + at the beggining of the to
	  else 
	    $sendto = $this->tolist;//+ included	
		
      // your user ID on clickatell
      $userid = $this->user;
      $password = $this->pwd;
	  $api = $this->httpapi;
      // comma-separated list of group names, and/or mobile numbers in international format
      $to = $sendto;
      // message to send
      $message = rawurlencode($smsmessage);
      // if you want to send a message Unicode-encoded in UCS2, you first need to convert
      // the message from UTF-8 (assuming you have UTF-8 encoding in the HTML page, or whatever source)
      //$message = bin2hex( iconv("UTF-8","UTF-16BE","YOUR_MESSAGE") ); // UTF-16BE = UCS-2
      $lang = 'ucs2';
 
      $url = 'http://api.clickatell.com/http/sendmsg';//?user=balexiou&password=PASSWORD&api_id=3239691&to=306936550848&text=Message';
      $fullUrl = "$url?user=$userid&password=password&api=$api&to=$to&text=$message";//&lang=$lang";
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
	  $url = 'http://api.clickatell.com/http/sendmsg';
      $url_parsed=parse_url($url);        
	  
	  if ($to)
	    $sendto = $to;
	  else 
	    $sendto = $this->tolist;	  
	  
      $userid = $this->user;	
      $password = $this->pwd;
      $to = $sendto;	  	
      $message = rawurlencode($smsmessage);	      
	  $get_file = "sendmsg?";
	  $get_string = "user=$userid&password=password&api=$api&to=$to&text=$message";
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
	    $url = 'http://api.clickatell.com/http/sendmsg';
        $url_parsed=parse_url($url); 	  
	  
	    if ($to)
	      $sendto = $to;
	    else 
	      $sendto = $this->tolist;	  
	  
        $userid = $this->user;	
        $password = $this->pwd;
        $to = $sendto;	  	
        $message = rawurlencode($smsmessage);	      
	    $get_file = "sendmsg?";
	    $get_string = "user=$userid&password=password&api=$api&to=$to&text=$message";
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