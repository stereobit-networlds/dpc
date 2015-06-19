<?php

$__DPCSEC['RCPICKIT_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCPICKIT_DPC")) && (seclevel('RCPICKIT_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCPICKIT_DPC",true);

$__DPC['RCPICKIT_DPC'] = 'rcpickit';

$d = GetGlobal('controller')->require_dpc('rc/rcdownload.dpc.php');
require_once($d); 

//GetGlobal('controller')->get_parent('RCDOWNLOAD_DPC','RCPICKIT_DPC');//NOT INHERITANCE
//DUE TO CO-EXISTING IN SENTICODES DPC

$__EVENTS['RCPICKIT_DPC'][0]='pickit';
$__EVENTS['RCPICKIT_DPC'][1]='I agree';
$__EVENTS['RCPICKIT_DPC'][2]='I don\'t agree';
$__EVENTS['RCPICKIT_DPC'][3]='getthepro';//overwrite
//$__EVENTS['RCPICKIT_DPC'][4]='pickit';

$__ACTIONS['RCPICKIT_DPC'][0]='pickit';
$__ACTIONS['RCPICKIT_DPC'][1]='I agree';
$__ACTIONS['RCPICKIT_DPC'][2]='I don\'t agree';
$__ACTIONS['RCPICKIT_DPC'][3]='getthepro';//overwrite 
//$__ACTIONS['RCPICKIT_DPC'][4]='pickit';


$__DPCATTR['RCPICKIT_DPC']['pickit'] = 'pickit,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCPICKIT_DPC'][0]= 'RCPICKIT_DPC;Processing transaction;Συναλλαγή σε εξέλιξη';//'RCPICKIT_DPC;Download full version;Download full version';

class rcpickit extends rcdownload  {

    var $error;
	var $ftype;
	var $bypass_procced;
	var $tellit;
	var $download_path;
    var $urlpath;
	
	function rcpickit() {
	
        rcdownload::rcdownload();	
		
	    $this->title = localize('RCPICKIT_DPC',getlocal());		
		$this->ftype = paramload('RCPICKIT','filetype');	
		$this->tellit = paramload('RCPICKIT','tellit');
		$this->download_path = paramload('RCPICKIT','dirsource');
        $this->urlpath = paramload('SHELL','urlpath');
		
		$this->bypass_procced = true;
		
		$m = paramload('RCPICKIT','error');	
		$ff = $this->prpath.$m;
		if (is_file($ff)) {
		  $this->error = file_get_contents($ff);
		}
		else
		  $this->error = $m; //plain text
		  
		$m3 = paramload('RCPICKIT','agreemessage');	
		$ff3 = $this->prpath.$m3;
		if (is_file($ff3)) {
		  $this->agreemessage = file_get_contents($ff3);
		}
		else
		  $this->agreemessage = $m3; //plain text	
		  
		$m4 = paramload('RCPICKIT','notagreemessage');	
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
	      case 'pickit'         : $this->get_product($this->product_id);//echo 'ZZZZ';
		                          break;
								  
		  case 'I agree'        : $this->get_product($this->product_id);
		                          //$this->send_downloaded_mail($this->product_id); 
								  //moved into get_product due to get_product call from paypal
								  break;
          case 'getthepro'      : break;
		  case 'I don\'t agree' : break;		  
		  default               :
		}	 	
    }
  
    //overwrite
    function action($action=null) {
	 
	 $form_submited = GetSessionParam('FORMSUBMITED') ;	  
	 
     $out = setNavigator($this->title,"<B>" . $this->product_id . "</B>") ;	  	 
	 
	 if ($form_submited) {//NO NEED AT SELL!!!!!
	 
	   switch ($action) {
	   
	     //case 'pickit'         : $out .= $this->show_product_link($this->product_id);
		   //                      break;
	   
		 case 'I agree'        : if ($this->download_link) {
		                           //$out .= $this->agreemessage; 
								   
								   if (defined('PAYPAL_DPC'))	 
								     $template = paramload('SHELL','prpath') . "paypal_details.tpl";
								   elseif (defined('CLICKBANK_DPC'))
								     $template = paramload('SHELL','prpath') . "clickbank_details.tpl";
								   else //default paypal
								     $template = paramload('SHELL','prpath') . "paypal_details.tpl";
								   
								   //echo $template;
								   	 	 	 
 	                               $out .= file_get_contents($template);  
								   $out .= $this->procced_button();
								   //$out .= $this->show_product_link($this->product_id);
								 }
								 else 
								   $out .= $this->error; 
								 break;
		 case 'I don\'t agree' : $out .= $this->notagreemessage; 
		                         break;
								 
		 case 'getthepro'      : $out .= $this->instant_download($this->product_id);
		                         //$out .= rcpickit::instant_download($this->product_id);
		                         break;						 		   
	 
	     default               : if ($this->download_link) { 
                                   //$out .= $this->trymessage;								   
								   $template = paramload('SHELL','prpath') . "contract_details.tpl";

 	                               $out .= file_get_contents($template);  
								   //echo $template;
								   if ($this->bypass_procced)
								     $out .= $this->termsform_bypassed_procced();  
								   else  
	                                 $out .= $this->termsform();  
								 }
								 else  
								   $out .= $this->error;
	   }
	 }
	 else {
	   $out = $this->message;
	   $out .= GetGlobal('controller')->calldpc_method('rcform.advmailform use 1');
	 }
	 
	 
	 return ($out);
    }
	
	//dpc call
	function sell($product=null) {
	
      $this->product_id = $product ? $product : GetReq('g'); 	
	  $this->get_product($this->product_id);
	  
      if ($this->download_link) { 
                                   //$out .= $this->trymessage;								   
								   $template = paramload('SHELL','prpath') . "contract_details.tpl";

 	                               $out .= file_get_contents($template);  
								   //echo $template;
								   if ($this->bypass_procced)
								     $out .= $this->termsform_bypassed_procced();  
								   else  
	                                 $out .= $this->termsform();  
	  }
	  else  
	    $out .= $this->error;	  
		
	  return ($out);	
	}
	
    function termsform_bypassed_procced() {

     $sFormErr = GetGlobal('sFormErr');
	 
	 $pay = strtolower($this->get_product_pay_method($this->product_id));	 
	 
     $myaction = seturl("t=$pay&g=".$this->product_id); 
	 //echo $myaction,'>';  	
	 
	 $ret .= "<table width='70%'  border='1' align='center' cellpadding='5' cellspacing='0' bordercolor='#666666'>";
     $ret .= "<tr><td align='center' valign='middle' bgcolor='#F9F9FF'>";	
	 
     $ret .= "<form method=\""."POST"."\" name=\""."Download"."\" action=\"".$myaction."\" style=\"margin: 0px;\">";	     
     $ret .= "<textarea cols=65"." rows=14"." name=\""."terms"."\"readonly>".
	         file_get_contents($this->prpath."terms_register.txt") .
	         "</textarea><BR>";
			 
	 //$ret .= "<input type=\"hidden\" value=\"download\" name=\"FormAction\"/>";
	 $ret .= "<input type=\"hidden\" name=\"FormName\" value=\"Pickit\">"; 
	
     $ret .= "<input type=submit name=\"Submit\" value=\""."I agree"."\"" . ">";
	 
	 switch ($pay) {
	     case 'clickbank' : $ret .= "<input type=\"hidden\" value=\"clickbank\" name=\"FormAction\">";
		                    break;
		 case 'paypal'    :
		 default          : $ret .= "<input type=\"hidden\" value=\"paypal\" name=\"FormAction\">";					
	 }	 
	 
	 $ret .= "&nbsp;";
	 //$ret .= "<input type=submit name=\"FormAction\" value=\""."I don't agree"."\"" . ">"; 
	 //$ret .= seturl("t=I don't agree","Cancel");
     $ret .= "</form></tr></table>";

 
     return ($ret);
    }	 
	
	//overwrite
    function get_product($product_id) {
	
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
	                     $file = $this->prpath . paramload('RCPICKIT','dirsource') . $product_id . $product_type;
                         break; 				 
	    case '.zip'  :
	    default      :
                         $file = $this->prpath . paramload('RCPICKIT','dirsource') . $product_id . $this->ftype; 
	   }	
  
       //$file = $this->prpath . paramload('RCPICKIT','dirsource') . $product_id . $this->ftype; 
	   //echo $file,'>';
	   if (file_exists($file)) { 
	   
         SetSessionParam("GET_".$product_id,1);	 
         $this->download_link = true;//bypass copy for instant download
		 
		 //send an email
		 //$this->send_downloaded_mail($product_id); //no form email!!!!moved to paypal
	   }	 
	   else
	     $this->download_link = false;
		 
	   return $this->download_link;		 
    }
	
	//overwrite
    function get_product_info($product_id,$attr=null) {
   
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
		     return $myproduct['product_details'];
		 }
      }
	  
      return null;	  
    } 	
	
	//overwrite
    function show_product_link($product_id,$getbycode=null) {
  
     if ($this->download_link) {
	 
	   if ($getbycode)  
	     $template = paramload('SHELL','prpath') . "code_generic_thanks.tpl";
	   else	 
         $template = paramload('SHELL','prpath') . "buy_generic_thanks.tpl";
	   $ret = @file_get_contents($template); 	 
	   
	   $product_info = $this->get_product_info($product_id, array('product_details','template','type','type_param'));
	 
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
			case '.pdf':$link = seturl("t=getthepro&g=$product_id&filetype=.pdf",$product_id. ' - '.$product_info['product_details']); 
	                    $ret = "<h2>". $link . "</h2>";
						break;				
			case '.apk':$link = seturl("t=getthepro&g=$product_id&filetype=.apk",$product_id. ' - '.$product_info['product_details']); 
	                    $ret = "<h2>". $link . "</h2>";
						break;			
		    case '.zip':
		    default    :
	                    $link = seturl("t=getthepro&g=$product_id",$product_id. ' - '.$product_info['product_details']); 
	                    $ret = "<h2>". $link . "</h2>";			
		 }
	   } 
	   else {	 
	     //$link = "<a href=\"$this->download_link\">Download re-coding::$product_id</a>";
	     $link = seturl("t=getthepro&g=$product_id",$product_id . ' - '.$product_info['product_details']); 
	     $ret .= "<h2>". $link . "</h2>";
	   }
	   
	   $template = paramload('SHELL','prpath') . $product_info['template'];
	   if (is_readable($template)) {
 	     $ret .= file_get_contents($template); 
	   }		   
	   
	   /*
	   $a[] = $link;
	   $b[] = "left;20%;";
	   
	   $a[] = "<h2>" . $product_info . "</h2>";
	   $b[] = "left;80%;";	   
	   
	   $w = new window($this->title." ",$a,$b);//$link);
	   $ret .= $w->render();
	   unset($w);
	   
	   $template = paramload('SHELL','prpath') . "requirments.tpl";
	   $ret .= file_get_contents($template); 
	   $template = paramload('SHELL','prpath') . "full_support.tpl";
	   $ret .= file_get_contents($template); 		
	   */
	   
	   //$this->download_link = false;//reset to prevent re-loading	   

	 }
	 else {
		$m = paramload('RCPICKIT','error');	
		$ff = $this->prpath.$m;
		if (is_file($ff)) {
		  $ret = file_get_contents($ff);
		}
		else
		  $ret = $m; //plain text
	 }
	 
	 return $ret;
    }	
    
    //overwrite 
    function instant_download($product_id, $ftype=null) {
	
       if (!$filetype = GetReq('filetype'))	
	     $filetype = $ftype ? $ftype : $this->ftype;
	
   	   //extra security set payment = true as session param
	   //to prevent cmd instant download from pickit!!!
	   //add security from invitation code (without session payment=true)
	   if ((GetSessionParam('PAYMENTSUBMITED')==='secretpaymentnumber') ||
	       (GetSessionParam('CODESUBMITED')===1)) {	
  
       $file = $this->prpath . $this->download_path . $product_id . $filetype;	   
	   //echo "PICKIT:",$file;
	   //die();
       $downloadfile = new DOWNLOADFILE($file);
	   
       /*$this->tell_by_mail("fullversion file downloaded",
	                      'support@re-coding.com',
		                  'billy@re-coding.com',
						  $file);	
						  
       $this->tell_by_sms("$product_id fullversion file downloaded.");*/
	   //inform bt mail and sms
	   $this->send_downloaded_mail($product_id);								   	   
	   
       if (!$downloadfile->df_download()) {
	     //echo "Sorry, we are experiencing technical difficulties downloading this file. Please report this error to Technical Support.";	   	   
         $ret = $this->error;	 
	   }
	   //else
	     // $ret = "OK";	
	   }
	   else
	     $ret = "Prohibited area!";
		 
	   return ($ret);
    }
	
	function procced_button() {
	
	   $pay = strtolower($this->get_product_pay_method($this->product_id));
	   
	   switch ($pay) {
	     case 'clickbank' : $url = seturl("t=clickbank&g=".$this->product_id,"Procced");
		                    break;
		 case 'paypal'    :
		 default          : $url = seturl("t=paypal&g=".$this->product_id,"Procced");	 					
	   }
	
       $ret = "<table width='70%'  border='1' align='center' cellpadding='5' cellspacing='0' bordercolor='#666666'>";
       $ret .= "<tr><td align='center' valign='middle' bgcolor='#F9F9FF'>";
       $ret .= "<p><b>$url</b></p></tr></table>";	
	   
	   return ($ret);
	}
	
	//overwrite
    function send_downloaded_mail($product,$user=null) {
	
	   $from_user = GetSessionParam('FORMMAIL')?GetSessionParam('FORMMAIL'):'anonymous';
  
       if ($this->tellit) {
  
		$thema = $product . " transaction completed";
		
		//$template = paramload('SHELL','prpath') . "buy_mail_thanks.tpl";
		$body = $product . " transcation completed by " . $from_user; //file_get_contents($template);
				
		$this->tell_by_mail($thema,
		                    (isset($user)?$user:$this->tellit),
						    $this->tellit,
							$body);	
							
	    $this->tell_by_sms($thema);							
	  }							  
    }	
	
    function get_product_pay_method($product_id) {
	
      $selected_product = $product_id;
	  
	  //read the attributes
      $actfile = paramload('SHELL','prpath') . "product_details" . ".ini";							
	  //echo $actfile;
	 
      if ($pdetails=@parse_ini_file($actfile,1)) {
         
		 //print_r($pdetails);
		 
		 $myproduct = $pdetails[$selected_product];
		 
		 if (is_array($myproduct)) {
		   //echo $myproduct['paymethod'];	 
		   return $myproduct['paymethod'];
		 }
      }
	  
      return null;		
	}
  
};
}
?>