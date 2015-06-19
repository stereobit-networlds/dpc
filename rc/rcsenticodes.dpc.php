<?php

$__DPCSEC['RCSENTICODES_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCSENTICODES_DPC")) && (seclevel('RCSENTICODES_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSENTICODES_DPC",true);

$__DPC['RCSENTICODES_DPC'] = 'rcsenticodes';


$d = GetGlobal('controller')->require_dpc('rc/rcicodes.dpc.php');
require_once($d); 

GetGlobal('controller')->get_parent('RCICODES_DPC','RCSENTICODES_DPC');

$__EVENTS['RCSENTICODES_DPC'][0]='cpsenticodes';
$__EVENTS['RCSENTICODES_DPC'][1]='approve';
$__EVENTS['RCSENTICODES_DPC'][2]='select';
$__EVENTS['RCSENTICODES_DPC'][3]='preview';
$__EVENTS['RCSENTICODES_DPC'][4]='createicodes';
$__EVENTS['RCSENTICODES_DPC'][5]='sendinow';
 
$__ACTIONS['RCSENTICODES_DPC'][0]='cpsenticodes'; 
$__ACTIONS['RCSENTICODES_DPC'][1]='approve';
$__ACTIONS['RCSENTICODES_DPC'][2]='select';
$__ACTIONS['RCSENTICODES_DPC'][3]='preview';
$__ACTIONS['RCSENTICODES_DPC'][4]='createicodes';
$__ACTIONS['RCSENTICODES_DPC'][5]='sendinow';


$__DPCATTR['RCSENTICODES_DPC']['cpsenticodes'] = 'cpsenticodes,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCSENTICODES_DPC'][0]='RCSENTICODES_DPC;Create invitation codes;Create invitation codes';

class rcsenticodes extends rcicodes {

   	var $product;
	var $type;
	var $approve;
	var $select;
	var $title;
	var $data,$meter,$ccodes,$mails;
	
	function rcsenticodes() {
	
	  $this->title = localize('RCSENTICODES_DPC',getlocal()); 	

      $login_already = GetSessionParam('LOGIN');
	  if ($login_already=='yes')    	  
	    $this->approve = true;
	  else	
	    $this->approve = false;	  
	  
	  $this->type = GetSessionParam('type');
	  $this->product = GetSessionParam('product');	  
	}
	
    function event($event=null) {
	
	  switch ($event) {
						  
		case 'approve'   :$this->approve(); 
		                  break;
		case 'preview'   :$this->data = $this->collect_data();
		                  break;
		case 'createicodes':$this->ccodes = $this->create_codes();
		                  //print_r($this->ccodes);
		                  break;							  
		case 'sendinow'  :$this->mails = $this->send_codes();
		                  //print_r($this->mails);
		                  break;				  							  
		case 'cpsenticodes':
		default            :
		                  break;				  					  
	  }
			
    }
  
    function action($action=null) {
	  
	  switch ($action) {
						  
		case 'approve'   :$out = setNavigator(seturl("t=cp","Control Panel"),$this->title);
		                  $out .= $this->bulkiform();
		                  break;				
						  
		case 'preview'   :$out = setNavigator(seturl("t=cp","Control Panel"),$this->title . " for " . 
		                                      $this->product . "(".$this->type.")");
		                  $out .= $this->preview();
		                  break;	

		case 'createicodes'  :$out = setNavigator(seturl("t=cp","Control Panel"),$this->title . " for " . 
		                                          $this->product . "(".$this->type.")");
		                      $out .= $this->create_result();
		                  break;							  
						  
		case 'sendinow'  :$out = setNavigator(seturl("t=cp","Control Panel"),$this->title . " for " . 
		                                      $this->product . "(".$this->type.")");
		                  $out .= $this->send_result();
		                  break;						  
						  			   
		case 'cpsenticodes':
		default          : $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);
		                  $out .= $this->bulkiform();				  						  					  
	  }	 
	 
	 return ($out);
    }	
	 
	 
    function bulkiform() {	

      $filename = seturl("t=cpsenticodes",0,1);

      if (!$this->approve) {//ask for inv code 
        $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
	    $toprint .= "<STRONG>Input invitation code :</STRONG>"; 
	    $toprint .= "<input type=\"text\" name=\"icode\" value=\"\" size=\"64\" maxlength=\"128\">";
	    $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
        $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"approve\">";
        $toprint .= "</FORM>";
	  }
	  else {
        $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
	    $toprint .= "<STRONG>Product :</STRONG>"; 
	    //$toprint .= "<input type=\"text\" name=\"prd\" value=\"\" size=\"64\" maxlength=\"128\">";
		$toprint .= "<select name=\"prd\">";
		$toprint .= $this->get_product_list();
		$toprint .= "</select>";			
	    $toprint .= "<br><STRONG>Type :</STRONG>"; 
		$toprint .= "<select name=\"tpy\">";
		$toprint .= "<option value=\"shareware\">Shareware editions</option>";
		$toprint .= "<option value=\"fullversion\">Official editions</option>";		
		$toprint .= "</select>";		
	    //$toprint .= "<input type=\"text\" name=\"tpy\" value=\"\" size=\"64\" maxlength=\"128\">";		
	    $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
        $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"preview\">";
        $toprint .= "</FORM>";	  
	  }
	   
	  $data2[] = $toprint; 
  	  $attr2[] = "left";

	  $swin = new window($this->title,$data2,$attr2);
	  $out .= $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	  unset ($swin);

      return ($out);
	}	
	
	function approve() {
	
      $code = GetParam('icode');
		
	  if (($code) && ($this->verify_icode($code))) 
	    $this->approve = true;
	  else
	    $this->approve = false;	
	}
	
	function collect_data() {
	   
	      SetSessionParam('product',GetParam('prd'));
	      SetSessionParam('type',GetParam('tpy'));		  
	   
	      $this->product = GetParam('prd'); //echo $this->product,"++";
	      $this->type = GetParam('tpy');	//echo $this->type,"++";	   
		
	      if (($this->product) && ($this->type)) {
	        switch ($this->type) {
	          case 'shareware' : $data = $this->get_customers($this->product,$this->type,null);// no expire
		                         break;
					  
              case 'fullversion':$data = $this->get_customers($this->product,$this->type,null);//expire is on
		                         break;					  
	        }	
		  }
		  
		  return ($data);
	}
	
	function preview() {
	
       $filename = seturl("t=senticodes",0,1);	
	   $title = $this->title;
	
	   if ($this->data) {
		 
         $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";	
		 	 
		 foreach ($this->data as $n=>$rec) {

		   $viewdata[] = $n+1;
		   $viewattr[] = "left;5%";

           $name = "customer_".str_replace(".","#",$rec[1]); //echo $name."<br>";		 
		   $viewdata[] = "<input type=\"checkbox\" name=\"$name\" value=\"1\" checked>";
		   $viewattr[] = "left;5%";
		   
		   $viewdata[] = ($rec[0]?$rec[0]:"&nbsp;");
		   $viewattr[] = "left;30%";		   
		   
		   $viewdata[] = ($rec[1]?$rec[1]:"&nbsp;");
		   $viewattr[] = "left;30%";	
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);		   		   
		 }
		 
	     $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Create\">"; 
         $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"createicodes\">";
         $toprint .= "</FORM>";			 
	   }
	   else
	     $toprint .= $this->bulkiform();
		 
       $mywin = new window($title,$toprint);
       $out = $mywin->render();			 
	  
	   return ($out);	
	}
	
	function get_customers($product,$type,$expire=null) {
	
	  if ($product) $sel = $product;
	  if ($type) {
	    if ($product) $sel .= '+';
		$sel .= $type;
	  }
	  if ($expire) {
	    if (($product) || ($type)) $sel .= '+';
		$sel .= $expire;
	  }
	  
	  $ret = GetGlobal('controller')->calldpc_method("rccustomers.select use $sel");
	  return ($ret);		
	}	
	
	function create_codes() {

	  $this->meter = 0;
	  
	  foreach ($_POST as $name=>$val) {
	    //echo $name."<br>";
		
	    if (substr($name,0,strlen("customer"))=="customer") {
		    //echo $name."<br>";
			$p = explode("_",$name);
		    $this->meter +=1;
			$customer_mail = str_replace("#",".",$p[1]);
		    //echo $customer_mail,"++<br>";
			
			//10 days expiration
			$code = $this->insert_icode($this->product,$this->type,$customer_mail,10);
			
			$ccodes[] = $code."_".$customer_mail;
		}
	  }	

	  return ($ccodes);
	} 
	
	function create_result() {
	   
       $filename = seturl("t=senticodes",0,1);	   
	   
	   $title = $this->meter . " codes created successfull!";
	   
       $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";		   
	   //$toprint .= "Press send to send an email for each one.";
	   
	   foreach ($this->ccodes as $n=>$rec) {

	       $p = explode("_",$rec);

		   $code = $p[0];
		   $mail = $p[1];

		   $viewdata[] = $n+1;
		   $viewattr[] = "left;5%";
		   
		   $viewdata[] = $mail;
		   $viewattr[] = "left;30%";		   
		   
		   $viewdata[] = $code;
		   $viewattr[] = "left;40%";			   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);		   		   
		   
		   $email=str_replace('.',"#",$mail);
           $toprint .= "<input type=\"hidden\" name=\"customer_$email\" value=\"$code\">";		   
	   }		   
	      
	   $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Send\">"; 
       $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"sendinow\">";
       $toprint .= "</FORM>";	
	   
       $mywin = new window($title,$toprint);
       $out = $mywin->render();	   
	   
	   return ($out);
	}
	
	function send_codes() {
	
	  $this->meter = 0;

	  foreach ($_POST as $name=>$val) {
	  
	    $icode = $val;
	    //echo $name,":",$val,"<br>";
	    if (substr($name,0,strlen("customer"))=="customer") {
		    
			$p = explode("_",$name);

			//echo $p[1],"++<br>";
			$customer_mail = str_replace("#",".",$p[1]);
			
			switch ($this->type) {
			  case 'shareware'   :	$template = paramload('SHELL','prpath') . "update_shareware_mail.tpl";
			                        break;
			  case 'fullversion' :	$template = paramload('SHELL','prpath') . "update_fullversion_mail.tpl";
			                        break;
			}											
		    $body =  file_get_contents($template) . //"Dear customer,\n" .
		         "\nYour invitation number is:".$icode.
				 "\nBy pressing " . seturl('t=seticode',"here") .
				 " you can download your item.";			
			
		    $res = $this->tell_by_mail(paramload('RCSENTICODES','thema'),
		                               paramload('RCSENTICODES','from'),
						               $customer_mail,
							           $body);
								
			if (!$res) $this->meter +=1;									   					
			
			$mails[] = $customer_mail."_".$res;
		}	  
	  }	
	  
	  return ($mails);
	}
	
	function send_result() {
	
       $filename = seturl("t=");	   
	   
	   $title = $this->meter . " mails send successfull!";
	   
       $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";		   
	   //$toprint .= "Press send to send an email for each one.";
	   
	   foreach ($this->mails as $n=>$rec) {

	       $p = explode("_",$rec);

		   $mail = $p[0];
		   $result = trim($p[1]);

		   $viewdata[] = $n+1;
		   $viewattr[] = "left;5%";
		   
		   $viewdata[] = $mail;
		   $viewattr[] = "left;30%";		   
		   
		   $viewdata[] = ($result?$result:"Ok");
		   $viewattr[] = "left;40%";			   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);		   		   
		   		   
	   }	   
	   $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
       //$toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"sendinow\">";
       $toprint .= "</FORM>";	
	   	   
       $mywin = new window($title,$toprint);
       $out = $mywin->render();	
	   
	   return ($out);	
	} 	
	
   function tell_by_mail($subject,$from,$to,$body) {
         

         $smtpm = new smtpmail;
         $smtpm->to = $to; 
         $smtpm->from = $from; 
         $smtpm->subject = $subject;
         $smtpm->body = $body;
         $mailerror = $smtpm->smtpsend();
         unset($smtpm);	
		 
		 return ($mailerror);  
   } 
   
   function get_product_list() {
   
         $pfile = paramload('SHELL','prpath') . "product_details" . ".ini";
		 
		 if ($pdetails=@parse_ini_file($pfile,1)) {
		 
		   $toprint .= "<option value=\"\">--------- Product list ---------</option>";	
		 
		   foreach ($pdetails as $name=>$details)
		     $toprint .= "<option value=\"$name\">$name</option>";		
		 }
		 else
		   $toprint = "<input type=\"text\" name=\"tpy\" value=\"\" size=\"64\" maxlength=\"128\">";		
		 
		 return ($toprint);
   }	
  
};		
}
?>