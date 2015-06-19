<?php

$__DPCSEC['SHDOWNLOAD_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("SHDOWNLOAD_DPC")) && (seclevel('SHDOWNLOAD_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHDOWNLOAD_DPC",true);

$__DPC['SHDOWNLOAD_DPC'] = 'shdownload';

$__EVENTS['SHDOWNLOAD_DPC'][0]='download';
$__EVENTS['SHDOWNLOAD_DPC'][1]='I agree';
$__EVENTS['SHDOWNLOAD_DPC'][2]='I don\'t agree';
$__EVENTS['SHDOWNLOAD_DPC'][3]='instant';
$__EVENTS['SHDOWNLOAD_DPC'][4]=' OK ';
$__EVENTS['SHDOWNLOAD_DPC'][5]='Cancel';
 
$__ACTIONS['SHDOWNLOAD_DPC'][0]='download';
$__ACTIONS['SHDOWNLOAD_DPC'][1]='I agree';
$__ACTIONS['SHDOWNLOAD_DPC'][2]='I don\'t agree';
$__ACTIONS['SHDOWNLOAD_DPC'][3]='instant';
$__ACTIONS['SHDOWNLOAD_DPC'][4]=' OK ';
$__ACTIONS['SHDOWNLOAD_DPC'][5]='Cancel';

$__DPCATTR['SHDOWNLOAD_DPC']['download'] = 'download,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['SHDOWNLOAD_DPC'][0]='SHDOWNLOAD_DPC;Download;Download';
$__LOCALE['SHDOWNLOAD_DPC'][1]='AGREE_; OK ; OK ';
$__LOCALE['SHDOWNLOAD_DPC'][2]='CANCEL_;Cancel;Ακυρο';

class shdownload  {

    var $product_id,$title,$prpath,$downloadpath;
    var $message,$trymessage,$agreemessge,$notagreemessage;
	var $download_link,$download_path;
	var $ftype,$tellit;
	var $file_epithema;
	
	var $thanks_from_mail,$thanks_from_subject;
	var $urlpath, $ispublicdir, $wherethefileis;
	var $dir2copy;
	
	var $goback, $instant;
	
	function shdownload() {

	    $this->download_link = null;
	    $this->prpath = paramload('SHELL','prpath');
	    $this->inpath = paramload('ID','hostinpath');		
	    $this->urlpath = paramload('SHELL','urlpath').'/'.$this->inpath .'/';  		
	    $this->title = localize('SHDOWNLOAD_DPC',getlocal());		
	    $this->product_id = GetReq('id');		
			
	    $this->thanks_from_mail = remote_paramload('SHDOWNLOAD','thanksfrom',$this->prpath);
		$this->thanks_from_subject = remote_paramload('SHDOWNLOAD','thanksubject',$this->prpath);
	
        $this->ftype = remote_arrayload('SHDOWNLOAD','filetype',$this->prpath);	//echo $this->ftype,'>';	
		$this->tellit = remote_paramload('SHDOWNLOAD','tellit',$this->prpath);
        $this->ispublicdir = remote_paramload('SHDOWNLOAD','public',$this->prpath);  
		$this->download_path = remote_paramload('SHDOWNLOAD','dirsource',$this->prpath);
		//echo $this->download_path;
        $this->dir2copy = remote_paramload('SHDOWNLOAD','dir2copy',$this->prpath);
	    $this->downloadpath = $this->prpath . $this->dir2copy;            

	    //echo $this->path;
		$this->file_epithema = remote_paramload('SHDOWNLOAD','epithema',$this->prpath); //"_shareware";  

        if ($this->ispublicdir) {
           $this->wherethefileis = $this->urlpath . $this->download_path;
        }
        else
           $this->wherethefileis = $this->prpath . $this->download_path;
		   
		$back = remote_paramload('SHDOWNLOAD','backcmd',$this->prpath);   
        $this->goback = $back?$back:'kshow';	
		$this->instant = remote_paramload('SHDOWNLOAD','instant',$this->prpath);			   					  		  		  
	}
	
    function event($event=null) {
		
		//echo GetParam("FormAction"), ">>>>>";		
				
		switch ($event) {
	      case localize('AGREE_',getlocal()) :		
		  case 'I agree'        : $this->get_product($this->product_id,1); 
								  //$this->send_thanks_mail($this->product_id);
								  break;
          case 'instant'        : break;
	      case localize('CANCEL_',getlocal()) :		  
		  case 'I don\'t agree' : break;
		  		  
		  default               : if ($tx = @file_get_contents($this->prpath.$this->product_id."_terms.txt")) {//terms form
	                               //nothing
								 }  
								 else {//bypass terms
								   if ($this->instant) {
								   }
								   else
								     $this->get_product($this->product_id,1); 
								 }  
		}		
    }
  
    function action($action=null) {
	  $cat=getReq('cat');
	  $id= getReq('id');
	
	 //$form_submited = GetSessionParam('FORMSUBMITED') ;	 /////////////////////////////////
	 //$code_submited = GetSessionParam('CODESUBMITED') ;	 /////////////////////////////////

     $pid = substr($this->product_id,0,-1); //echo $pid; //extract last letter = attachment letter	 
	 $gobacktoproduct = seturl("t=$this->goback&cat=$cat&id=$pid",$this->get_product_info($this->product_id,6));
	 $out = setNavigator($gobacktoproduct,$this->title);//,$this->product_id) ;	  	 
     //$out = setNavigator($this->title,$this->product_id) ;	  	 
	 
	 //if (($form_submited) || ($code_submited)) {
	 
	   switch ($action) {
	   
	     case localize('AGREE_',getlocal()) :	   
		 case 'I agree'        : 
                                 $template = $this->prpath . "download_generic_thanks.tpl";
		                         $out .= file_get_contents($template); 
   								 $out .= $this->httpdownload($this->product_id);
								 break;
	     case localize('CANCEL_',getlocal()) :							 
		 case 'I don\'t agree' : $out .= $this->set_message('notagreemessage'); 
		                         break;
								 
		 case 'instant'        : $out .= $this->instant_download($this->product_id);
 		                         //$out .= shdownload::instant_download($this->product_id);
		                         //SetSessionParam("TRYPROCCESS",null);   ////////////////////////////////////
		                         break;						 		   
	 
	     default :  
								 if ($tx = @file_get_contents($this->urlpath.'cp/html/'.$this->product_id."_terms.txt")) {//terms form
	                               $out .= $this->termsform($tx);  
								 }  
								 else {//bypass terms
								   if ($this->instant)
								     $out .= $this->instant_download($this->product_id);
								   else
								     $out .= $this->httpdownload($this->product_id); 
								 }     
	   }
	 /*}///////////////////////////////////////////////
	 else {
	   $out = $this->message;
	   $out .= GetGlobal('controller')->calldpc_method('rcform.action');
	 }*/
	 
	 
	 return ($out);
    }
	
	//used by other dpcs
	function get_download_link($id,$params=null) {
	
	  $ret = seturl('t=download&id='.$id.'&'.$params);
	  return ($ret);
	}
	
	function set_message($message) {
	
		$m = remote_paramload('SHDOWNLOAD',$message,$this->prpath);	
		$ff = $this->prpath.$m;
		if (is_file($ff)) {
		  $mymessage = file_get_contents($ff);
		}
		else
		  $mymessage = $m; //plain text
		  
		return ($mymessage);  		
	
	}
	
  function termsform($termstext=null) {
     $t = GetReq('t');
	 $cat = GetReq('cat');
	 $id = GetReq('id');
     $sFormErr = GetGlobal('sFormErr');
	 
     $myaction = seturl("t=$t&cat=$cat&id=$id");//.$this->product_id);   	
	 
	 $ret .= "<table width='70%'  border='1' align='center' cellpadding='5' cellspacing='0' bordercolor='#666666'>";
     $ret .= "<tr><td align='center' valign='middle' bgcolor='#F9F9FF'>";	
	 
     $ret .= "<form method=\""."POST"."\" name=\""."Download"."\" action=\"".$myaction."\" style=\"margin: 0px;\">";	     
     $ret .= "<textarea cols=65"." rows=14"." name=\""."terms"."\"readonly>".
	         //file_get_contents($this->prpath."terms_trial.txt") .
	         "</textarea><BR>";
			 
	 //$ret .= "<input type=\"hidden\" value=\"download\" name=\"FormAction\"/>";
	 $ret .= "<input type=\"hidden\" name=\"FormName\" value=\"Download\">"; 
	  
     //$ret .= "<input type=submit name=\"FormAction\" value=\""."I agree"."\"" . ">";
	 //$ret .= "&nbsp;";
	 //$ret .= "<input type=submit name=\"FormAction\" value=\""."I don't agree"."\"" . ">"; 
	 
     $ret .= "<input type=submit name=\"FormAction\" value=\"".localize('AGREE_',getlocal())."\"" . ">";
	 //$ret .= "&nbsp;";
	 //$ret .= "<input type=submit name=\"FormAction\" value=\"".localize('CANCEL_',getlocal())."\"" . ">"; 	 
	 
     $ret .= "</form></tr></table>";

 
     return ($ret);
  }
  
  function get_product($product_id,$instant=0) {
    
     if ($instant) {//echo instant;
	   
       foreach ($this->ftype as $filetype) {
         $file = $this->wherethefileis. $product_id . $this->file_epithema . '.' . $filetype; 
	     echo 'A',$file;	   
	 
	     if (file_exists($file)) { //echo $file;
           //SetSessionParam("TRY_".$product_id,1);	 ////////////////////////////////////////////////
           $this->download_link = true;//bypass copy for intant download
		   //for httpdownload
		   GetGlobal('controller')->calldpc_method('httpdownload.set_filename use '.$file);		 
		   return true;
	     }	 		 
	   }
	   return false;
	 }  
	 
     foreach ($this->ftype as $filetype) {
       $file = $this->wherethefileis. $product_id . $this->file_epithema . '.' . $filetype;       
	   //echo $file,"<br>";
	   if (file_exists($file)) {
	 
	     //copy file to a new path
	     $targetdir = $this->downloadpath . session_id();
	     //echo $targetdir;
         @mkdir($targetdir,0700);	//make a dir named as session
	   
	     $target = $targetdir . "/" . $product_id . "." . $filetype; 
	     if (@copy($file,$target)) {
	       //echo "Ok";
		 
           $ip = paramload('SHELL','ip');//$_SERVER['HTTP_HOST'];
           $pr = paramload('SHELL','protocol');		   			 
		   $this->download_link = $pr . $ip . "/" . $this->dir2copy .  session_id() . "/" . $product_id . $filetype;
								
           //SetSessionParam("TRY_".$product_id,1);	///////////////////////////////////////////////
		   return $this->download_link;								
	     }
	     else {
	       echo "Copy error!";  
		   return false;
		 }  
	   }//file exist
    }//for each
	//if not any
    echo "File not exist!";			 
	return false;
  }
  
  function get_product_info($product_id,$fid=null) {
       $myfid = $fid?$fid:6;
       //echo $myfid,'<br>';
       $pid = substr($product_id,0,-1);
	   
	   if (defined('SHKATALOGMEDIA_DPC'))
         $dataset = GetGlobal('controller')->calldpc_method('shkatalogmedia.getiteminfo use '.$pid);
		 
	   foreach ($dataset as $if=>$record) {
         return ($record[$myfid]); 	   
	   }	 
	  
      return ($pid);//default is the code	  
  }  
  
  function show_product_link($product_id) {
  
     if ($this->download_link) {
	 
       //$file = $this->wherethefileis . $product_id . $this->ftype;	 
	   //echo $file," ",filesize($file);
	   //$link = "<a href=\"$this->download_link\">Download re-coding::$product_id</a>";
	   $link = seturl("t=instant&g=$product_id",$product_id); 
	   
	   $a[] = $link;
	   $b[] = "left;20%;";
	   
	   $a[] = $this->get_product_info($product_id,6);
	   $b[] = "left;80%;";		   
	   
	   $w = new window($this->title . $product_id,$a,$b);//$link);
	   $ret = $w->render();
	   unset($w);
	   

	 }
	 else {
		  $ret = $this->set_message('error');
	 }
	 
	 return $ret;
  }
  
  //no need the file to be public
  function instant_download($product_id) {
  
     foreach ($this->ftype as $filetype) {  
   	   //extra security set form must be filled as session param
	   //to prevent cmd instant download without it.
	   //if (GetSessionParam('FORMSUBMITED')) {	  ////////////////////////////////////////////
  
       $file = $this->wherethefileis . $product_id . $this->file_epithema . '.' . $filetype;	   
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
         $this->set_message('error');		 
	   }
	   //else
	     // $ret = "OK";	
	   //}
	   //else
	     //$ret = "Prohibited area!"; /////////////////////////////////////////////////////
		 	   
	   return ($ret);
	   
	   
	   //use download2.lib
	   //$dFile = new Download_file($this->prpath . paramload('SHDOWNLOAD','dirsource') , $product_id . $this->ftype);
	 }  
  }
  
  function httpdownload($product_id) {
  
     foreach ($this->ftype as $filetype) { 
  
   	   //extra security set form must be filled as session param
	   //to prevent cmd instant download without it.
	   //if (GetSessionParam('FORMSUBMITED')) {//&&///////////////////////////////////////////////////
	      //(GetGlobal('controller')->calldpc_method('httpdownload.get_filename'))) {	  
       
	   
       $file = $this->wherethefileis . $product_id . $this->file_epithema . '.' . $filetype;	  
	   $title = $this->get_product_info($product_id,6);		   
       //echo $file,'-';
  
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
	   
	   $w = new window($title. $product_id,"<h2>".$ret."</h2>");//$link);
	   $out = $w->render();
	   unset($w);	
	   //}
	   //else
	     //$out = "Prohibited area!";   ///////////////////////////////////////
	   
	   return ($out);
	 }  
  }
  
  function send_downloaded_mail($product,$user=null) {
  
       if ($this->tellit) {
  
		$thema = $product . " downloaded";//"Re-coding technologies ";
		
		//$template = paramload('SHELL','prpath') . "buy_mail_thanks.tpl";
		$body = $product . " downloaded from " . (isset($user)?$user:GetSessionParam('FORMMAIL')); //file_get_contents($template);
				
		$this->tell_by_mail($thema,
		                    (isset($user)?$user:GetSessionParam('FORMMAIL')),
						    $this->tellit,
							$body);	
							
        $this->tell_by_sms($thema);								
	  }							  
  }
  
  function send_thanks_mail($product) {
  
    //if (GetSessionParam('FORMMAIL')) {////////////////////////////////////////////
  
	    $thema = $this->thanks_from_subject ." ". $product;
		
	    $template = paramload('SHELL','prpath') . "download_mail_thanks.tpl";
	    $body = file_get_contents($template);
				
	    $this->tell_by_mail($thema,
		                  $this->thanks_from_mail,
						  GetSessionParam('FORMMAIL'),
						  $body);	
    //}////////////////////////////////////////////////						    
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