<?php
$__DPCSEC['HTTPCL_DPC']='2;1;1;1;1;1;1;1;2';

if ( (!defined("HTTPCL_DPC")) && (seclevel('HTTPCL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("HTTPCL_DPC",true);

$__DPC['HTTPCL_DPC'] = 'httpcl';

$__EVENTS['HTTPCL_DPC'][0]='http';
$__EVENTS['HTTPCL_DPC'][1]='get';
$__EVENTS['HTTPCL_DPC'][2]='post';
$__EVENTS['HTTPCL_DPC'][3]='http_options';

$__ACTIONS['HTTPCL_DPC'][0]='http';
$__ACTIONS['HTTPCL_DPC'][1]='get';
$__ACTIONS['HTTPCL_DPC'][2]='post';
$__ACTIONS['HTTPCL_DPC'][3]='http_options';

$__DPCATTR['HTTPCL_DPC']['get'] = 'get,0,0,1,0,0,0,0,0';
$__DPCATTR['HTTPCL_DPC']['post'] = 'post,0,0,1,0,0,0,0,0';

//require_once("httpcl.lib.php"); //included as extension!!!!


class httpcl {

   var $http_connection;
   var $error;
   var $url;
   var $client_type;
   
   var $req_url;
   var $req_uri;
   var $req_posts;
   var $useragent_name = 'General purpose http client by stereobit.com';
   var $reply;
   
   var $html_source = 0;

   function httpcl($url=null,$uri=null,$phost=null,$pport=null,$ctype=null) {
     $c = (isset($ctype) ? $ctype : GetReq('c'));
	 $uri = (isset($uri) ? $uri : GetReq('uri'));  

     //set_time_limit(0);
     $this->url = (isset($url) ? $url : paramload('HTTPCL','url')); //echo $this->url;
     $this->phostname = (isset($phost) ? $phost : paramload('HTTPCL','proxyname')); 
     $this->phostport = (isset($pport) ? $pport : paramload('HTTPCL','proxyport')); 	 	 
	 
     if ($c) $this->client_type = $c;
	    else $this->client_type = 'html';
		
	 $this->uri = $uri;	  
	 
	 $this->html_source = 0;
   }

   function event($evn=null) {
     $param1 = GetGlobal('param1');
	 $param2 = GetGlobal('param2');
	 $param3 = GetGlobal('param3');
								
	 switch ($evn) {
	   case 'get'   : $this->req_url = $param1; 
	                  $this->req_uri = $param2; 
					  $port = $param3;
					  $this->reply = $this->request_get($this->useragent_name,$this->req_url,$this->req_uri,0,$this->html_source,$port); 
	                  break;
	   case 'post'  : $this->req_url = $param1;
	                  $this->req_uri = $param2; //$this->geturi($this->getvars(explode(",",$param2))); echo $this->req_uri;
					  $this->req_posts = $this->getvars(explode(",",$param3)); //print_r($this->req_posts);
					  $this->reply = $this->request_post($this->useragent_name,$this->req_url,$this->req_uri,$this->req_posts,0,$this->html_source); 
	                  break;
	   //default      : $this->http_request_test();
	 }							 			 
   }   
   
   function action($act=null) {
      
	 switch ($act) {
	   case 'get'   : $out = $this->html_view($this->reply,$this->req_url.$this->req_uri); 
	                  break;
	   case 'post'  : $out = $this->html_view($this->reply,$this->req_url.$this->req_uri); 
	                  break;
	   //default      : exit; //end execution
	 }
	 
	 return ($out);			  
   }
   
   function html_view($data,$title='') {
   
     $out  = setNavigator("Html Viewer");
	 
	 $win = new window("Html View ".$title,$data);
	 $out .= $win->render();
	 unset($win);  
	 
	 return ($out);
   }
   
   function http_request_test() {
   
     //init
     $this->http_connection = new http_class;
	 
     $this->error = $this->http_connection->Open(array("HostName"=>$this->url));     
   
     //header
     echo $this->header();
   
    
     //data
     if($this->error=="") {
       $this->error = $this->http_connection->SendRequest(
	                                                      array(
                                                          "RequestURI"=>"/?t=$this->uri",
                                                          "Headers"=>array(
                                                          "Host"=>$this->url,
                                                          "User-Agent"=>$this->client_type,
                                                          "Pragma"=>"no-cache"
														 ))); 
														 
       if($this->error=="")  {
	   
         $headers=array();
         $this->error=$this->http_connection->ReadReplyHeaders($headers);
		 
         if($this->error=="")   {
            echo "<UL>\n<H2><LI>Headers:</LI</H2>\n<PRE>\n";
			
            for(Reset($headers),$header=0;$header<count($headers);Next($headers),$header++)    {
               $header_name=Key($headers);
               if(GetType($headers[$header_name])=="array")     {
			   
                 for($header_value=0;$header_value<count($headers[$header_name]);$header_value++)
                   echo $header_name.": ".$headers[$header_name][$header_value],"\r\n";
               }
               else
                 echo $header_name.": ".$headers[$header_name],"\r\n";
            }
            echo "</PRE>\n<H2><LI>Body:</LI</H2>\n<PRE>\n";
		 
            for(;;) {
              $this->error=$this->http_connection->ReadReplyBody($body,1000);
		   
              if($this->error!="" || strlen($body)==0) break;
              echo HtmlSpecialChars($body);
            }
            echo "</PRE>\n</UL>\n";
          }
        }														   
	 }
	 //close 
     $this->http_connection->Close();	
	 
	 
	 //footer
	 echo $this->footer();												 
   }     
   
   function header() {
     return "<HTML>
             <HEAD>
             <TITLE>HTTP Virtual Client</TITLE>
             </HEAD>
             <BODY>
             <H1><CENTER>HTTP virtual client self-test</CENTER></H1>
             <HR>";
   }
   
   function footer() {
     return "<HR>
             </BODY>
             </HTML>";
   }  
   
  
   //get expressions like xxx=yyy zzz=qqq from $vars and return array([xxx]=yyy,[zzz]=qqq)
   function getvars($vars,$startfromvarnum=0) {
     $i=0;
     foreach ($vars as $varname=>$varval) {
	   if ($i>=$startfromvarnum) { 
	     if (strstr($varval,'=')) {
	       $outvar = explode('=',$varval);
		   $retvar[$outvar[0]] = $outvar[1]; 
	     }
	   }
	   $i+=1;	 
	 }

	 //print_r($retvar);
	 return ($retvar); 
   }
   
   //makes a uri from previouw returned array
   function geturi($varsarray) {
   
     if (is_array($varsarray)) {
	   foreach ($varsarray as $name=>$val)
	     if ($val) $ret .= '&' . $name . '=' . $val;
	 }
	 //echo $ret;
	 return $ret;
   }       
   
   
   //PUBLIC GET FUNCTION
   function request_get($useragent,$url,$uri,$headerview=0,$source=0,$port=80,$noreply=null) {
   
     $this->http_connection = new http_class;
	   
     $this->error = $this->http_connection->Open(array("HostName"=>$url,
	                                                   "HostPort"=>$port,
													   "ProxyHostName"=>$this->phostname,
													   "ProxyHostPort"=>$this->phostport)); 
	   
     if($this->error=="") {
	 
       $this->error = $this->http_connection->SendRequest(
	                                                      array("RequestMethod"=>"GET",
                                                          "RequestURI"=>$uri,//"/?$uri",
                                                          "Headers"=>array(
                                                            "Host"=>$url,
                                                            "User-Agent"=>$useragent,
                                                            "Pragma"=>"no-cache"
														  )
														  )); 
														 												 
														 
       if (($this->error=="") || ($noreply)) {
		 
         $headers=array();
         $this->error=$this->http_connection->ReadReplyHeaders($headers);
		 
         if($this->error=="")   {
		    if ($headerview) {
            //echo "Headers -----------------------------------\n";
			
            for(Reset($headers),$header=0;$header<count($headers);Next($headers),$header++)    {
               $header_name=Key($headers);
               if(GetType($headers[$header_name])=="array")     {
			   
                 for($header_value=0;$header_value<count($headers[$header_name]);$header_value++)
                   $out .= $header_name.": ".$headers[$header_name][$header_value]."\r\n";
               }
               else
                 $out .= $header_name.": ".$headers[$header_name]."\r\n";
            }
            //echo "-------------------------------------------\n";
		    }//header view
			
            for(;;) {
              $this->error=$this->http_connection->ReadReplyBody($body,1000);
		   
              if($this->error!="" || strlen($body)==0) break;
			  
              //$out .= HtmlSpecialChars($body);
			  $out .= (($source>0) ? HtmlSpecialChars($body) : $body);			  
            }
          }
          else {
	        //$out = "Error .$this->error\n";
			setInfo("Error 2:".$this->error);
	      } 		  
        }	
        else {
	      //$out = "Error .$this->error\n";
		  setInfo("Error 1:".$this->error);		  
	    } 															   
	 }   
     else {
	    //$out = "Error .$this->error\n";
		setInfo("Error 0:".$this->error);		
	 } 	   
	        
     $this->http_connection->Close(); 	      
	   
	 return ($out);
   }
   
   //PUBLIC POST FUNCTION   
   function request_post($useragent,$url,$uri,$postvars,$headerview=0,$source=0,$port=80) {
   
      $this->http_connection = new http_class;
	   
      $this->error = $this->http_connection->Open(array("HostName"=>$url,
	                                                   "HostPort"=>$port,
													   "ProxyHostName"=>$this->phostname,
													   "ProxyHostPort"=>$this->phostport));
	   
      if($this->error=="") {//echo "b\n";
												   

      $this->error=$this->http_connection->SendRequest(array("RequestMethod"=>"POST",
   															 "RequestURI"=>$uri,//"/action.func", //very imporatnt
															 "PostValues"=>$postvars,//array("FormAction"=>"command","docomm"=>$action),
															 "Referer"=>$url, //"http://www.panikidis.gr/action.func",
															 "Headers"=>array(
															   "User-Agent"=>$useragent,
															   "Pragma"=>"no-cache"
															 )
															 ));												   									   
        if($this->error=="") {//echo "c\n";
            $headers=array();
            $this->error=$this->http_connection->ReadReplyHeaders($headers);
			
            if($this->error=="") {
		      if ($headerview) {
                //echo "<UL>\n<H2><LI>Headers:</LI</H2>\n<PRE>\n";
                for(Reset($headers),$header=0;$header<count($headers);Next($headers),$header++) {
			  
                  $header_name=Key($headers);
                  if(GetType($headers[$header_name])=="array") {
				
                    for($header_value=0;$header_value<count($headers[$header_name]);$header_value++) 
				      $out .= $header_name.": ".$headers[$header_name][$header_value]."\r\n";
                  }
                  else
                    $out .= $header_name.": ".$headers[$header_name]."\r\n";
                }
                //echo "</PRE>\n<H2><LI>Body:</LI</H2>\n<PRE>\n";
			    //echo "-------------------------------------------\n";
			  }//header view
			  
              for(;;) {
			 
                $this->error=$this->http_connection->ReadReplyBody($body,1000);
				
                if($this->error!="" || strlen($body)==0)  break;
				
				$out .= (($source>0) ? HtmlSpecialChars($body) : $body);
                //$out .= HtmlSpecialChars($body);
              }
              //echo "</PRE>\n</UL>\n";
           }
           else {
	         //$out = "Error .$this->error\n";
			 setInfo("Error :".$this->error);			 
	       } 		   
       }
	   else {
	    //$out = "Error .$this->error\n";
		setInfo("Error :".$this->error);		
	   } 
     }
     else {
	    //$out = "Error .$this->error\n";
		setInfo("Error :".$this->error);		
	 } 	 
	   
     $this->http_connection->Close(); 	
	 
	 return ($out);      
   }

};
}
?>