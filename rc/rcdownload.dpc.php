<?php

$__DPCSEC['RCDOWNLOAD_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCDOWNLOAD_DPC")) && (seclevel('RCDOWNLOAD_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCDOWNLOAD_DPC",true);

$__DPC['RCDOWNLOAD_DPC'] = 'rcdownload';


$__EVENTS['RCDOWNLOAD_DPC'][0]='download';
$__EVENTS['RCDOWNLOAD_DPC'][1]='agree';//'I agree';
//$__EVENTS['RCDOWNLOAD_DPC'][2]='I don\'t agree';
$__EVENTS['RCDOWNLOAD_DPC'][3]='instant';
 
$__ACTIONS['RCDOWNLOAD_DPC'][0]='download';
$__ACTIONS['RCDOWNLOAD_DPC'][1]='agree';//'I agree';
//$__ACTIONS['RCDOWNLOAD_DPC'][2]='I don\'t agree';
$__ACTIONS['RCDOWNLOAD_DPC'][3]='instant';

$__DPCATTR['RCDOWNLOAD_DPC']['download'] = 'download,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCDOWNLOAD_DPC'][0]='RCDOWNLOAD_DPC;Download;Download';

class rcdownload  {

    var $product_id,$title,$prpath,$downloadpath;
    var $message,$trymessage,$agreemessge,$notagreemessage;
	var $download_link,$download_path;
	var $ftype,$tellit;
	var $file_epithema;
	
	var $thanks_from_mail,$thanks_from_subject;
	var $urlpath, $ispublicdir, $wherethefileis;
	
	function rcdownload() {
	
	    $this->thanks_from_mail = paramload('RCDOWNLOAD','thanksfrom');
		$this->thanks_from_subject = paramload('RCDOWNLOAD','thanksubject');
	
        $this->ftype = paramload('RCDOWNLOAD','filetype');	//echo $this->ftype,'>';	
		$this->tellit = paramload('RCDOWNLOAD','tellit');

	    $this->download_link = null;
	    $this->prpath = paramload('SHELL','prpath');
	    $this->urlpath = paramload('SHELL','urlpath').'/';  
            $this->ispublicdir = paramload('RCDOWNLOAD','public');  

		$this->download_path = paramload('RCDOWNLOAD','dirsource');
		//echo $this->download_path;

	    $this->downloadpath = $this->prpath . paramload('RCDOWNLOAD','dir2copy');            

	    //echo $this->path;
		$this->file_epithema = paramload('RCDOWNLOAD','epithema'); //"_shareware";  

            if ($this->ispublicdir) {
               $this->wherethefileis = $this->urlpath . $this->download_path;
            }
            else
               $this->wherethefileis = $this->prpath . $this->download_path;
		
	    $this->title = localize('RCDOWNLOAD_DPC',getlocal());		
	    $this->product_id = GetReq('g');		

		$m = paramload('RCDOWNLOAD','message');	
		$ff = $this->prpath.$m;
		if (is_file($ff)) {
		  $this->message = file_get_contents($ff);
		}
		else
		  $this->message = $m; //plain text		
		  
		$m2 = paramload('RCDOWNLOAD','trymessage');	
		$ff2 = $this->prpath.$m2;
		if (is_file($ff2)) {
		  $this->trymessage = file_get_contents($ff2);
		}
		else
		  $this->trymessage = $m2; //plain text				  		  
		  
		$m3 = paramload('RCDOWNLOAD','agreemessage');	
		$ff3 = $this->prpath.$m3;
		if (is_file($ff3)) {
		  $this->agreemessage = file_get_contents($ff3);
		}
		else
		  $this->agreemessage = $m3; //plain text	
		  
		$m4 = paramload('RCDOWNLOAD','notagreemessage');	
		$ff4 = $this->prpath.$m4;
		if (is_file($ff4)) {
		  $this->notagreemessage = file_get_contents($ff4);
		}
		else
		  $this->notagreemessage = $m4; //plain text			  		  		  
	}
	
    function event($event=null) {
		
		//echo GetParam("FormAction"), ">>>>>";		
				
		switch ($event) {
		  case 'agree'          :
		  case 'I agree'        : $this->get_product($this->product_id,1); 
		                          //$this->send_downloaded_mail($this->product_id); moved to action
								  $this->send_thanks_mail($this->product_id);
								  break;
          case 'instant'        : break;
		  case 'I don\'t agree' : break;		  
		  default               :
		}		
    }
  
    function action($action=null) {
	
	 $form_submited = GetSessionParam('FORMSUBMITED') ;	 
	 $code_submited = GetSessionParam('CODESUBMITED') ;
     $try_proccess = GetSessionParam('TRYPROCCESS') ;
     //echo 'TRY:',$try_proccess ;	 
	 
     $out = setNavigator($this->title,"<B>" . $this->product_id . "</B>") ;	  	 
	 
	 if (($form_submited) || ($code_submited) || ($try_proccess)) {
	 
	   switch ($action) {
	     case 'agree'          :
		 case 'I agree'        : //if ($this->download_link) {moved into downloads funcs
		                           //$out .= $this->agreemessage; 
                                 $template = paramload('SHELL','prpath') . "download_generic_thanks.tpl";
		                         $out .= file_get_contents($template); 
								   
		                         $out .= $this->show_product_link($this->product_id);
								   
   								 //$out .= $this->httpdownload($this->product_id);
								 
	                             //INSTANT DOWNLOAD	
                                 //$out .= $this->instant_download($this->product_id);
	                             //SetSessionParam("TRYPROCCESS",null);								 
								   
								 /*$template = paramload('SHELL','prpath') . "requirments.tpl";
		                         $out .= file_get_contents($template); 
								 $template = paramload('SHELL','prpath') . "trial_support.tpl";
		                         $out .= file_get_contents($template); */								   
								 //}
								 break;
		 case 'I don\'t agree' : $out .= $this->notagreemessage; 
		                         break;
								 
		 case 'instant'        : $out .= $this->instant_download($this->product_id);
 		                         //$out .= rcdownload::instant_download($this->product_id);
		                         SetSessionParam("TRYPROCCESS",null);
		                         break;						 		   
	 
	     default :  
                    $out .= $this->trymessage;
	                $out .= $this->termsform();  
	   }
	 }
	 else {
	   $out = $this->message;
	   $out .= GetGlobal('controller')->calldpc_method('rcform.advmailform use 0');
	 }
	 
	 
	 return ($out);
    } 

  //dpc call from rcform	
  function down_load($product=null) {
  
    $this->product_id = $product ? $product : GetReq('g'); 

	//INSTANT DOWNLOAD	
    //$out = $this->instant_download($this->product_id);
	//SetSessionParam("TRYPROCCESS",null);
	
	$out = $this->trymessage;
	$out .= $this->termsform(); 

    return ($out);	
  
  }  
	
  function termsform() {

     $sFormErr = GetGlobal('sFormErr');
	 
     $myaction = seturl("g=".$this->product_id);   	
	 
	 $ret .= "<table width='70%'  border='1' align='center' cellpadding='5' cellspacing='0' bordercolor='#666666'>";
     $ret .= "<tr><td align='center' valign='middle' bgcolor='#F9F9FF'>";	
	 
     $ret .= "<form method=\""."POST"."\" name=\""."Download"."\" action=\"".$myaction."\" style=\"margin: 0px;\">";	     
     $ret .= "<textarea cols=65"." rows=14"." name=\""."terms"."\"readonly>".
	         file_get_contents($this->prpath."terms_trial.txt") .
	         "</textarea><BR>";
			 
	 //$ret .= "<input type=\"hidden\" value=\"download\" name=\"FormAction\"/>";
	 $ret .= "<input type=\"hidden\" name=\"FormName\" value=\"Download\">"; 
	 $ret .= "<input type=\"hidden\" name=\"FormAction\" value=\""."agree"."\"" . ">";
	 $ret .= "<input type=submit name=\"submit\" value=\""."I agree"."\"" . ">";
	  
     //$ret .= "<input type=submit name=\"FormAction\" value=\""."I agree"."\"" . ">";
	 //$ret .= "&nbsp;";
	 //$ret .= "<input type=submit name=\"FormAction\" value=\""."I don't agree"."\"" . ">"; 
	 
     $ret .= "</form></tr></table>";

 
     return ($ret);
  }
  
  function get_product($product_id,$instant=0) {

     $product_type = $this->get_product_info($product_id,'type');
  
     switch ($product_type) {
		//view
		case 'template': $this->download_link = true;
		                 return true;
			             break;		 
		//code
		case 'dpccall':  $this->download_link = true;
		                 return true;
			             break;
		//file	
	    case '.pdf'  :		
	    case '.apk'  :
	             $file = $this->wherethefileis. $product_id . $this->file_epithema . $product_type;
                 break; 				 
	    case '.zip'  :
	    default :
                 $file = $this->wherethefileis. $product_id . $this->file_epithema . $this->ftype; 
	 }
	 //echo $file;  
	  
	 
     if ($instant) {
	 
	   if (file_exists($file)) { 
         SetSessionParam("TRY_".$product_id,1);	 
         $this->download_link = true;//bypass copy for intant download
		 //for httpdownload
		 GetGlobal('controller')->calldpc_method('httpdownload.set_filename use '.$file);		 
	   }	 
	   else
	     $this->download_link = false;
		 
	   return $this->download_link;		 
	 }
      
	 //echo $file,"<br>";
	 if (file_exists($file)) {
	 
	   //copy file to a new path
	   $targetdir = $this->downloadpath . session_id();
	   //echo $targetdir;
       @mkdir($targetdir,0700);	//make a dir named as session
	   
	   $target = $targetdir . "/" . $product_id . "." . $this->ftype; 
	   if (@copy($file,$target)) {
	     //echo "Ok";
		 
         $ip = paramload('SHELL','ip');//$_SERVER['HTTP_HOST'];
         $pr = paramload('SHELL','protocol');		   			 
		 $this->download_link = $pr . $ip . "/" . paramload('RCDOWNLOAD','dir2copy') .
		                        session_id() . "/" .$product_id . $this->ftype;
								
         SetSessionParam("TRY_".$product_id,1);									
	   }
	   else
	     echo "Copy error!";  
	 }
	 else
	   echo "File not exist!";
  }
  
  function get_product_info($product_id, $attr=null) {
   
      $selected_product = $product_id;
	  
	  //read the attributes
      $actfile = paramload('SHELL','prpath') . "product_details" . ".ini";							
	  //echo $actfile;
	 
      if ($pdetails=@parse_ini_file($actfile,1)) {
         
		 //print_r($pdetails);
		 
		 $myproduct = $pdetails[$selected_product];
		 
		 if (is_array($myproduct)) {
		   		
		   if (is_array($attr)) {
		   
		     foreach ($attr as $at) {
		       $ret[$at] = $myproduct[$at];
			 }  
			 return ($ret);
           }   		   
		   elseif ($attr)
		     return $myproduct[$attr];
		   else
		     return $myproduct['shareware_details'];
		 }
      }
	  
      return null;	  
  }  
  
  function show_product_link($product_id) {
  
     if ($this->download_link) {
	 
	   $product_info = $this->get_product_info($product_id, array('shareware_details','template','type','type_param'));
	 
       //$file = $this->wherethefileis . $product_id . $this->ftype;	 
	   //echo $file," ",filesize($file);
	   
	   if ($type=$product_info['type']) {
	     switch ($type) {
		 
		    //view
		    case 'template': $template = paramload('SHELL','prpath') . $product_info['type_param'];
	                         if (is_readable($template)) {
 	                           $ret .= file_get_contents($template); 
	                         }
			                 break;		 
		    //code
		    case 'dpccall':  $ret .= GetGlobal('controller')->calldpc_method($product_info['type_param']);
			                 break;
		    //file
		    case '.pdf':$link = seturl("t=instant&g=$product_id&filetype=.pdf",$product_id. ' - '.$product_info['shareware_details']); 
	                    $ret = "<h2>". $link . "</h2>";
						break;
			case '.apk':$link = seturl("t=instant&g=$product_id&filetype=.apk",$product_id. ' - '.$product_info['shareware_details']); 
	                    $ret = "<h2>". $link . "</h2>";
						break;
		    case '.zip':
		    default    :
	                    $link = seturl("t=instant&g=$product_id",$product_id. ' - '.$product_info['shareware_details']); 
	                    $ret = "<h2>". $link . "</h2>";			
		 }
	   } 
	   else {
	     //$link = "<a href=\"$this->download_link\">Download re-coding::$product_id</a>";	   
	     $link = seturl("t=instant&g=$product_id",$product_id. ' - '.$product_info['shareware_details']); 
	     $ret = "<h2>". $link . "</h2>";
	   }
	   
	   $template = paramload('SHELL','prpath') . $product_info['template'];
	   if (is_readable($template)) {
 	     $ret .= file_get_contents($template); 
	   }	 
		 
	   /*
	   $a[] = $link;
	   $b[] = "left;20%;";
	   
	   $a[] = $this->get_product_info($product_id);
	   $b[] = "left;80%;";		   
	   
	   $w = new window($this->title. " demonstration",$a,$b);//$link);
	   $ret = $w->render();
	   unset($w);
	   */

	 }
	 else {
		$m = paramload('RCDOWNLOAD','error');	
		$ff = $this->prpath.$m;
		if (is_file($ff)) {
		  $ret = file_get_contents($ff);
		}
		else
		  $ret = $m; //plain text
	 }
	 
	 return $ret;
  }
  
  //no need the file to be public
  function instant_download($product_id, $ftype=null) {
  
       if (!$filetype = GetReq('filetype'))
         $filetype = $ftype ? $ftype : $this->ftype;
  
   	   //extra security set form must be filled as session param
	   //to prevent cmd instant download without it.
	   if ((GetSessionParam('FORMSUBMITED')) || GetSessionParam("CODESUBMITED")) {	  
  
       $file = $this->wherethefileis . $product_id . $this->file_epithema . $filetype;	   
	   //$file = "c:\\php\\webos2\\projects\\re-coding-official\\demo\\delphi2java_shareware.zip";
	   //echo "DOWNLOAD:",$file;
	   //die();
       $downloadfile = new DOWNLOADFILE($file);
	   
       /*$this->tell_by_mail("demo file downloaded",
	                      'support@re-coding.com',
		                  'billy@re-coding.com',
						  $file);	
						  
         $this->tell_by_sms("$product_id demo file downloaded.");*/	
		 
	   //inform bt mail and sms
	   $this->send_downloaded_mail($product_id);					     
	   
       if (!$downloadfile->df_download()) {
	     //echo "Sorry, we are experiencing technical difficulties downloading this file. Please report this error to Technical Support.";	   	   
		$m = paramload('RCDOWNLOAD','error');	
		$ff = $this->prpath.$m;
		if (is_file($ff)) {
		  $ret = file_get_contents($ff);
		}
		else
		  $ret = $m; //plain text		 
	   }
	   //else
	     // $ret = "OK";	
	   }
	   else
	     $ret = "Prohibited area!"; 
		 	   
	   return ($ret);
	   
	   
	   //use download2.lib
	   //$dFile = new Download_file($this->prpath . paramload('RCDOWNLOAD','dirsource') , $product_id . $this->ftype);
  }
  
  //test new download method
  function httpdownload($product_id) {
  
   	   //extra security set form must be filled as session param
	   //to prevent cmd instant download without it.
	   if (GetSessionParam('FORMSUBMITED')) {//&&
	      //(GetGlobal('controller')->calldpc_method('httpdownload.get_filename'))) {	  

         $file = $this->wherethefileis . $product_id . $this->file_epithema . $this->ftype;	  
	   $title = $this->get_product_info($product_id);		   
  
  
       if ($this->download_link) {  
         //$d = new httpdownload($file);
	     //$ret = $d->select_download_type();	   
		 //$ret = GetGlobal('controller')->calldpc_method('httpdownload.select_download_type');
		 $ret = GetGlobal('controller')->calldpc_method('httpdownload.set_download use NRSPEED');
		 //infoem by mail and sms
		 $this->send_downloaded_mail($product_id);
	   }
	   else
	     $ret = "ERROR:file not exist!";	 
	   
	   $w = new window($title. " SHAREWARE EDITION","<h2>".$ret."</h2>");//$link);
	   $out = $w->render();
	   unset($w);	
	   }
	   else
	     $out = "Prohibited area!";   
	   
	   return ($out);
  }
  
  function send_downloaded_mail($product,$user=null) {
  
       if ($this->tellit) {
  
		$thema = $product . " shareware downloaded";//"Re-coding technologies ";
		
		//$template = paramload('SHELL','prpath') . "buy_mail_thanks.tpl";
		$body = $product . " shareware downloaded!"; //file_get_contents($template);
				
		$this->tell_by_mail($thema,
		                    (isset($user)?$user:$this->tellit),//GetSessionParam('FORMMAIL')),
						    $this->tellit,
							$body);	
							
        $this->tell_by_sms($thema);								
	  }							  
  }
  
  function send_thanks_mail($product) {
  
    if (GetSessionParam('FORMMAIL')) {
  
	    $thema = $this->thanks_from_subject ." ". $product;
		
	    $template = paramload('SHELL','prpath') . "download_mail_thanks.tpl";
	    $body = file_get_contents($template);
				
	    $this->tell_by_mail($thema,
		                  $this->thanks_from_mail,
						  GetSessionParam('FORMMAIL'),
						  $body);	
    }						    
  }
  
  function tell_by_mail($subject,$from,$to,$body) {
         

         $smtpm = new smtpmail;
         $smtpm->to = $to; 
         $smtpm->from = $from; 
         $smtpm->subject = $subject;
         $smtpm->body = $body;
         $mailerror = $smtpm->smtpsend();
         unset($smtpm);	
		 
		 if ($mailerror) echo "Error sending mail! ($mailerror)";
		 return ($mailerror);   
  } 
  
  function tell_by_sms($message) {
	
	    if (defined('SMSGUI_DPC'))
	      $ret = GetGlobal('controller')->calldpc_method('smsgui.sendsms use '.$message);		
  }      
  
};
}
?>