<?php

$__DPCSEC['SHFORM_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("SHFORM_DPC")) && (seclevel('SHFORM_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHFORM_DPC",true);

$__DPC['SHFORM_DPC'] = 'shform';

//require_once("amail.dpc.php");
$d = GetGlobal('controller')->require_dpc('mail/rcamail.dpc.php');
require_once($d); 

GetGlobal('controller')->get_parent('RCAMAIL_DPC','SHFORM_DPC');
 

$__EVENTS['SHFORM_DPC'][0]='contact';
$__EVENTS['SHFORM_DPC'][1]="sendamail";

$__ACTIONS['SHFORM_DPC'][0]='contact';
$__ACTIONS['SHFORM_DPC'][1]="sendamail";

$__DPCATTR['SHFORM_DPC']['contact'] = 'contact,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['SHFORM_DPC'][0]='SHFORM_DPC;Form;Επικοινωνία';
$__LOCALE['SHFORM_DPC'][1]='_SHSUBSCRIBEMESG;Subscribe;Θέλω να λαμβάνω πληροφορίες μεσω ηλεκτρονικού ταχυδρομείου';
$__LOCALE['SHFORM_DPC'][2]='_SEC1;Section1;Προσωπικά στοιχεία';
$__LOCALE['SHFORM_DPC'][3]='_SEC2;Section2;Συνδρομή';
$__LOCALE['SHFORM_DPC'][4]='_SEC3;Section3;Θέμα';
$__LOCALE['SHFORM_DPC'][5]='_POST;Submit;Καταχώρηση';
$__LOCALE['SHFORM_DPC'][6]='_RCAMFALSE;Email, Fields required!;Ακατάληλα δεδομένα!';
$__LOCALE['SHFORM_DPC'][7]='_COMPANY;Company;Εταιρεία';
$__LOCALE['SHFORM_DPC'][8]='_CPERSON;Contact person;Ονοματεπωνυμο';
$__LOCALE['SHFORM_DPC'][9]='_EMAIL;e-mail;e-mail';
$__LOCALE['SHFORM_DPC'][10]='_SUBJECT;Subject;Θέμα';
$__LOCALE['SHFORM_DPC'][11]='_BODY;Message;Κείμενο';
$__LOCALE['SHFORM_DPC'][12]='_MSG11;is required;είναι απαραίτητο';
$__LOCALE['SHFORM_DPC'][13]='_MSG12;The value in field;Η τιμή στο πεδίο';
$__LOCALE['SHFORM_DPC'][14]='_INVALIDMAIL;Invalid mail address;Άκυρο e-mail';
$__LOCALE['SHFORM_DPC'][15]='_NAME;Name;Ονοματεπωνυμο';

class shform extends rcamail {

    var $path, $urlpath, $inpath;
	var $recaptcha_public_key, $recaptcha_private_key;
	
	var $cntform, $cntformtitles, $checkuserasterisk, $asterisk;
	var $country_id;
	
	function shform() {
		
		rcamail::rcamail();
		
	    $this->title = localize('SHFORM_DPC',getlocal());	
		$this->path = paramload('SHELL','prpath');
	    $this->urlpath = paramload('SHELL','urlpath');
	    $this->inpath = paramload('ID','hostinpath');					
		
		  
		//overwrite  
		$this->sendaddress = remote_paramload('SHFORM','mail',$this->path);  	   	   	   
		$this->sendtype = remote_paramload('SHFORM','type',$this->path); 
		$this->verify = remote_paramload('SHFORM','verify',$this->path);			
		$this->verify_address = remote_paramload('SHFORM','verifymailer',$this->path);			
		$this->verify_subject = remote_paramload('SHFORM','vsubject',$this->path);		
		$this->verify_message = remote_paramload('SHFORM','vmsg',$this->path);
		$this->info_message = remote_paramload('SHFORM','vbody',$this->path);		
		
	    $this->cntform = remote_arrayload('SHFORM','cntform',$this->path);		   
	    $this->cntformtitles = remote_arrayload('SHFORM','cntformtitles',$this->path);		
	    $this->checkuseasterisk = remote_paramload('SHFORM','checkasterisk',$this->path);	 
	    $this->asterisk = $this->checkuseasterisk?'&nbsp;':'*'; //echo $this->asterisk,'>';		
		
		$this->recaptcha_public_key = remote_paramload('RECAPTCHA','pubkey',$this->path);							  
		$this->recaptcha_private_key = remote_paramload('RECAPTCHA','privkey',$this->path);		
		
		$this->country_id = remote_paramload('SHFORM','countryid',$this->path);	
	}
	
	function onok_message() {
	
	    /*$template = "contactformok.htm";
	    $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
		//echo $t;
	    if (is_readable($t)) {		   
		  $mytemplate = file_get_contents($t);*/
			
		  $tokens[] = GetParam("company");		  
		  $tokens[] = GetParam("cperson");	
		  $tokens[] = GetParam("activities");	
		  $tokens[] = GetParam("address");	
		  $tokens[] = GetParam("town");	
		  $tokens[] = GetParam("zip");	
		  $tokens[] =  get_selected_option_fromfile(GetParam("country"),'country');
		  $tokens[] = GetParam("tel");	
		  $tokens[] = GetParam("fax");	
		  $tokens[] = GetParam("email");	
		  $tokens[] = GetParam("web");			  		  		  		  		  		  		  		  		  	
		  $tokens[] = GetParam("subscribe");			  
		  $tokens[] = GetParam("subject");	
		  $tokens[] = GetParam("mail_text");			  
			
		/*  $ret = $this->combine_tokens($mytemplate,$tokens);		
	    }*/
		
        $sd = str_replace('+','<@>',implode('<TOKENS>',$tokens));
		if (!$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use contactformok.htm+".$sd."+1")) {
		
		//else {
		  $m = paramload('SHFORM','message');
			
		  $ff = $this->path . $m;
		  if (is_file($ff)) {
		    $ret = file_get_contents($ff);
		  }
		  else
		    $ret = $m; //plain text	
		} 
		//echo '>',$ret;
		return ($ret); 
	}
	
	//overwrite
	function text2send() {
	
	    /*$template = "contactformmail.htm";
	    $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
		//echo $t;
	    if (is_readable($t)) {	
		  $mytemplate = file_get_contents($t);*/
			
		  $tokens[] = GetParam("company");		  
		  $tokens[] = GetParam("cperson");	
		  $tokens[] = GetParam("activities");	
		  $tokens[] = GetParam("address");	
		  $tokens[] = GetParam("town");	
		  $tokens[] = GetParam("zip");	
		  $tokens[] =  get_selected_option_fromfile(GetParam("country"),'country');
		  $tokens[] = GetParam("tel");	
		  $tokens[] = GetParam("fax");	
		  $tokens[] = GetParam("email");	
		  $tokens[] = GetParam("web");			  		  		  		  		  		  		  		  		  
		  $tokens[] = GetParam("subscribe");			  
		  $tokens[] = GetParam("subject");	
		  $tokens[] = GetParam("mail_text");			  
			
		 /* $ret = $this->combine_tokens($mytemplate,$tokens);			   
				  
	    }
		else {	*/
		
        $sd = str_replace('+','<@>',implode('<TOKENS>',$tokens));
		if (!$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use contactformmail.htm+".$sd."+1")) {
				
	
	     $data =  localize('_COMP',getlocal()) . ":" . GetParam("company") ."\r\n".
				  localize('_CPER',getlocal()) . ":" . GetParam("cperson") ."\r\n".
				  localize('_ACTV',getlocal()) . ":" . GetParam("activities") ."\r\n".
				  localize('_ADDR',getlocal()) . ":" . GetParam("address") ."\r\n".
				  localize('_TOWN',getlocal()) . ":" . GetParam("town") ."\r\n".
				  localize('_ZIP',getlocal()) . ":" . GetParam("zip") ."\r\n". 
				  localize('_CNTR',getlocal()) . ":" . get_selected_option_fromfile(GetParam("country"),'country') ."\r\n". 
				  localize('_TEL',getlocal()) . ":" . GetParam("tel") ."\r\n". 
				  localize('_FAX',getlocal()) . ":" . GetParam("fax") ."\r\n". 					   					   					   
				  localize('_MAIL',getlocal()) . ":" . GetParam("email") ."\r\n".
				  localize('_WEB',getlocal()) . ":" . GetParam("web") ."\r\n".							   
				  //"--------------------------------------------------------------------\r\n".					   
				  //localize('_PLAN',getlocal()) . ":" . get_selected_option_fromfile(GetParam("proglan"),'proglan') ."\r\n".
				  //localize('_OSYS',getlocal()) . ":" . get_selected_option_fromfile(GetParam("opersys"),'opersys') ."\r\n".
				  //localize('_USERI',getlocal()) . ":" . get_selected_option_fromfile(GetParam("userint"),'userint') ."\r\n".
				  //localize('_DBENV',getlocal()) . ":" . get_selected_option_fromfile(GetParam("dbenv"),'dbenv') ."\r\n".
                  //"--------------------------------------------------------------------\r\n".						   
				  localize('_SUBSCR',getlocal()) . ":" . GetParam("subscribe") ."\r\n".			   					   
				  localize('_SUBJECT',getlocal()) . ":" . GetParam("subject") ."\r\n".	
				  localize('_BODY',getlocal()) . ":" . GetParam("mail_text")."\r\n";	
				  
		  $ret = str_replace("\r\n","<br/>",$data);
	   }	
	   
	   /*switch ($this->sendtype) {
	   
	     case 'html' : $submiteddata = str_replace("\r\n","<br/>",$this->text2send()) . "<br/>" .
		                               GetParam("mail_text");
		               break;
	   
	     case 'text' : 
	     default     :
	                   $submiteddata = "-----------------------------------------------<br/>".
	                                   str_replace("\r\n","<br/>",$this->text2send()) .					   
	                                   "------------------------------------------------<br/>".
					                   GetParam("mail_text") . "<br/>";
	   }	*/	   	
	   		   
       return ($ret);					   	
	}	 	
	
    //overwriten
    function event($sAction) {
	   $db = GetGlobal('db');
	   
       //rcamail::event($sAction);  
	   
       $sFormErr = GetGlobal('sFormErr');	  	    		  
	  			    
  
       if (!$sFormErr) {   
  
	   switch ($sAction) {	

		case "sendamail"  : //if ($this->valid_recaptcha()) {
		                    //if ((GetParam("company")) &&
		                      //  (checkmail(GetParam("email"))) /*&&
								//(GetParam("subject"))*/) {
						    if (!$err=$this->checkFields()) {		
								
							  //save to db
							  //$this->register_customer();	 
							
							  if (defined('DATABASE_DPC')) {
								
	                            $sSQL = "insert into cform (email,postform) values (" . $db->qstr(addslashes(GetParam("email"))) . ",\"";
								$sSQL.= addslashes($this->text2send()); 
								$sSQL.= "\")";
                                //echo $sSQL;
                                $ret = $db->Execute($sSQL);	 
								//print_r($ret);

                                //if ($ret = $db->Affected_Rows()) 								
							  }
							  else {
							    //save to a text flat file	
							    $this->write2file(GetParam("company").";".
							                    GetParam("cperson").";".
												GetParam("activities").";".
												GetParam("address").";".
												GetParam("town").";".
												GetParam("zip").";".
												get_selected_option_fromfile(GetParam("country"),'country').";".
												GetParam("tel").";".
												GetParam("fax").";".
												GetParam("email").";".
												GetParam("web").";".
												GetParam("subscribe").";".
												GetParam("subject").";\r\n"
							                   );	
		                      }
							  
                              /*$smtpm = new smtpmail;
		                      $smtpm->to($this->sendaddress); 
		                      $smtpm->from($this->sendaddress);//GetParam("email"); 
		                      $smtpm->subject(GetParam("subject"));
 		                      $smtpm->body($this->text2send());//$submiteddata;
		                      $error = $smtpm->smtpsend();
		                      unset($smtpm);*/
							  
							  $subject = GetParam("subject");
							  $body = $this->text2send();
							  $this->mailto($this->sendaddress,$this->sendaddress,$subject,$body,1);							  
							  
							  //if ($this->post==true) {
							  if (!$error) {
							    $this->post = true;
	                            //verify
	                            if ($this->verify) { 	 
								  
							      $this->mailto($this->verify_address,GetParam("email"),$this->verify_subject,$this->verify_message,1);								  									  
								}
							    //subscribe		
						        if (trim(GetParam('subscribe'))) 
								  $this->subscribe(GetParam("email"));	 
							  
							    $this->msg = localize('_AMTRUE',getlocal());
								
								//set session param that has contact
								SetSessionParam("FORMSUBMITED",1);
								//save user mail
								SetSessionParam("FORMMAIL",GetParam("email"));
							  }  
							  else {
  							    $this->msg = localize('_RCAMFALSE',getlocal())."<br>".$error;
								SetInfo($error);
							  }	
							}
							/*else 
							  $this->msg = localize('_RCAMFALSE',getlocal());  
							}//recapthca check  */
	                        break; 																											  						
       }
      }  	   
    }
  
    function action($action) {

	 switch ($action) {
	  
	   case 'contact' :
	   default :
	            $out = $this->advmailform();
	 }
	 
	 return ($out);
    } 
	  
  
  //ovewrwrite
  function advmailform() {
  
     $formtitles = remote_arrayload('SHFORM','formtitles',$this->path);
	 
	 $title1 = $formtitles[0]?localize($formtitles[0],getlocal()):"Στοιχεία ενδιαφερομένου.";
	 $title2 = $formtitles[1]?localize($formtitles[1],getlocal()):"Εγγραφή στην λίστα.";
	 $title3 = $formtitles[2]?localize($formtitles[2],getlocal()):"Σχόλια.";
	 $title4 = $formtitles[3]?localize($formtitles[3],getlocal()):"Στοιχεία ενδιαφερομένου.";	 	 	 

     $sFormErr = GetGlobal('sFormErr');
	 
     $myaction = seturl("t=");//"g=".GetReq('g'));//g=product id in case of try process   	
	 //echo $myaction;	 
     //error message
	 //if ($sFormErr==localize('_MLS2',getlocal())) { //send mail succesfully
	 //if ($this->msg==localize('_AMTRUE',getlocal())) {	
	 if ($this->post==true) {
	 
         //$out = setNavigator($this->title);	   
	     $out .= $this->onok_message();//$this->message;
	 }
	 else { //show the form plus error if any
	 
	   $formtemplate='cform.htm';	   
	   $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$formtemplate) ; 
	   //echo $t,'>';
	   if (is_readable($t)) {
		 $myform = file_get_contents($t);
	   
	     if (defined('RECAPTCHA_DPC')) {
	       $recaptcha = recaptcha_get_html($this->recaptcha_public_key, $this->recaptcha_private_key);	
           $myform = str_replace('<@RECAPTCHA@>',$recaptcha,$myform);		   
	     }
	 
	     $out .= $sFormErr . $this->msg;
		 $out .= $myform; 
	   }	 
	   else {
	     $tokens[] = $sFormErr . $this->msg . 
	                 "<form method=\"POST\" action=\"" .$myaction. "\" name=\"amail\">";
	     $tokens[] = "<input type=\"text\" name=\"company\" maxlength=\"50\" value=\"". ToHTML(GetParam("company")) . "\" size=\"30\" >";		  
	     $tokens[] = "<input type=\"text\" name=\"cperson\" maxlength=\"50\" value=\"". ToHTML(GetParam("cperson")) . "\" size=\"30\" >";		  	
	     $tokens[] = "<input type=\"text\" name=\"activities\" maxlength=\"50\" value=\"". ToHTML(GetParam("activities")) . "\" size=\"30\" >";		  
	     $tokens[] = "<input type=\"text\" name=\"address\" maxlength=\"50\" value=\"". ToHTML(GetParam("address")) . "\" size=\"30\" >";		  	
	     $tokens[] = "<input type=\"text\" name=\"town\" maxlength=\"50\" value=\"". ToHTML(GetParam("town")) . "\" size=\"30\" >";		  
	     $tokens[] = "<input type=\"text\" name=\"zip\" maxlength=\"50\" value=\"". ToHTML(GetParam("zip")) . "\" size=\"30\" >";		  	
	   
	     $cntr = GetParam("country")?GetParam("country"):$this->country_id; 
	     $tokens[] =  "<select name=\"country\">".get_options_file('country',false,true,$cntr)."</select>";
	   
	     $tokens[] = "<input type=\"text\" name=\"tel\" maxlength=\"50\" value=\"". ToHTML(GetParam("tel")) . "\" size=\"30\" >";		  	
	     $tokens[] = "<input type=\"text\" name=\"fax\" maxlength=\"50\" value=\"". ToHTML(GetParam("fax")) . "\" size=\"30\" >";		  	
	     $tokens[] = "<input type=\"text\" name=\"email\" maxlength=\"50\" value=\"". ToHTML(GetParam("email")) . "\" size=\"30\" >";		  	
	     $tokens[] = "<input type=\"text\" name=\"web\" maxlength=\"50\" value=\"". ToHTML(GetParam("web")) . "\" size=\"30\" >";		  			  		  		  		  		  		  		  		  		  	
	   
	     $statin = 'checked';
	     $tokens[] = "<input type=\"checkbox\" name=\"subscribe\"". $statin . ">";			  
	   
	     $tokens[] = "<input type=\"text\" name=\"subject\" maxlength=\"50\" value=\"". ToHTML(GetParam("subject")) . "\" size=\"30\" >";		  	
	     $tokens[] = "<TEXTAREA name='mail_text' cols='50' rows='8' wrap='VIRTUAL'>" . GetParam("mail_text") . "</TEXTAREA>";	  
	   
	     if (defined('RECAPTCHA_DPC')) {
	       $tokens[] = recaptcha_get_html($this->recaptcha_public_key, $this->recaptcha_private_key);	   
	     }
	   
         $tokens[] = "<input type=\"submit\" value=\"" . trim(localize('_POST',getlocal())) . "\">" .
                     "<input type=\"hidden\" name=\"FormAction\" value=\"sendamail\">" .
                     "</form>";	   
	   
         $sd = str_replace('+','<@>',implode('<TOKENS>',$tokens));
	     if ($tout = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use contactform.htm+".$sd."+1")) {  
	   
           //$out = setNavigator($this->title);	
		   $out .= $tout; 
	     }
	     else {//classik form
	   
           $out = setNavigator($this->title);	   
	 	 
           $out .= setError($sFormErr . $this->msg);
	   
	   
	       $form = new form(localize('SHFORM_DPC',getlocal()), "amail", FORM_METHOD_POST, $myaction, true);
	
	       $form->addGroup			("personal",			$title1);
	       //$form->addGroup			("technical",			"Tell us about your technology.");	   
	       $form->addGroup			("subscribe",			$title2);
	       $form->addGroup			("thema",				$title3);	   
	       $form->addGroup			("warning",				"&nbsp;");	   
	       if ($this->info_message) 
	         $form->addGroup			("info",			"6.");		   

	       $form->addElement		("personal",			new form_element_text		(localize('_COMP',getlocal())."*",     "company",		GetParam("company"),				"forminput",	        50,				255,	0));
	       $form->addElement		("personal",			new form_element_text		(localize('_CPER',getlocal()),     "cperson",		GetParam("cperson"),				"forminput",	        20,				255,	0));
	       $form->addElement		("personal",			new form_element_text		(localize('_ACTV',getlocal()),     "activities",	GetParam("activities"),				"forminput",	        30,				255,	0));	   	   	   
	       $form->addElement		("personal",			new form_element_text		(localize('_ADDR',getlocal()),     "address",	    GetParam("address"),				"forminput",	        30,				255,	0));	
	       $form->addElement		("personal",			new form_element_text		(localize('_TOWN',getlocal()),     "town",	        GetParam("town"),				"forminput",	        20,				255,	0));
           //$form->addElement		("personal",			new form_element_greekmap	(localize('_NOMOS',getlocal()),     "amail","nomos",GetParam("nomos"),"forminput",20,20,1));	   	
	       $form->addElement		("personal",			new form_element_text		(localize('_ZIP',getlocal()),      "zip",	        GetParam("zip"),				"forminput",	        20,				255,	0));
	       //$form->addElement		("personal",			new form_element_text		(localize('_CNTR',getlocal()),     "country",	    GetParam("country"),				"forminput",	        20,				255,	0));	
		   $cntr = GetParam("country")?GetParam("country"):$this->country_id;
	       $form->addElement		("personal",			new form_element_combo_file (localize('_CNTR',getlocal()),     "country",	    /*$this->get_country_from_ip()*/$cntr,				"forminput",	        1,				0,	'country'));	
	       $form->addElement		("personal",			new form_element_text		(localize('_TEL',getlocal()),      "tel",	        GetParam("tel"),				"forminput",	        20,				255,	0));	
	       $form->addElement		("personal",			new form_element_text		(localize('_FAX',getlocal()),      "fax",	        GetParam("fax"),				"forminput",	        20,				255,	0));		   	   	   		   	   	   
	       $form->addElement		("personal",			new form_element_text		(localize('_MAIL',getlocal())."*",     "email",			GetParam("email"),				"forminput",	        30,				255,	0));
	       $form->addElement		("personal",			new form_element_text		(localize('_WEB',getlocal()),      "web",			"http://",		"forminput",		    20,				255,	0));

	       $form->addElement		("thema",			    new form_element_text		(localize('_SUBJECT',getlocal())."*",  "subject",		GetParam("subject"),				"forminput",			60,				255,	0));	
	       $form->addElement		("thema",			    new form_element_textarea   (localize('_MESSAGE',getlocal()),  "mail_text",		GetParam("mail_text"),				"formtextarea",			60,				9));		      
	   
	   
	       //$form->addElement		("subscribe",			new form_element_text		(localize('_SYBSCR',getlocal()),   "subscribe",		"",				"forminput",	        20,				255,	0));
	       $form->addElement		("subscribe",			new form_element_radio		(localize('_SHSUBSCRIBEMESG',getlocal()),   "subscribe",      1,             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	   

	       $form->addElement		("warning",			    new form_element_onlytext	(localize('_WARNING',getlocal()),  localize('_FORMWARN',getlocal()),""));	   
	   
	       if ($this->info_message)	   
	         $form->addElement		("info",			    new form_element_onlytext	("",  $this->info_message,""));	   	   

	       // Adding a hidden field
	       $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "sendamail"));
		 
	       if (defined('RECAPTCHA_DPC')) {
		 
	         $form->addGroup		    ("recaptcha",		"Recaptcha.");		   
	         $form->addElement		("recaptcha",   	new form_element_recaptcha	($this->recaptcha_public_key));
	       }			 
 
	       // Showing the form
	       $fout = $form->getform ();		
		 
	       $fwin = new window(localize('SHFORM_DPC',getlocal()),$fout);
	       $out .= $fwin->render();	
	       unset ($fwin);	
	   
	       //$out .= $fout;

	       //$form->checkform();		
		 	 
		 }//classik form 	 
	   }//cform
   }//no post
	   
   return ($out);
 }
	 
     function subscribe($mail=null) {
       //echo 'a>>>>>';
       if (defined('SHSUBSCRIBE_DPC')) {
	  
	     GetGlobal('controller')->calldpc_method('shsubscribe.dosubscribe use '.$mail.'++0');
	   }
     } 	
	 
    function checkFields($bypass=null,$checkasterisk=null) {
	   $sFormErr = GetGlobal('sFormErr');
	   SetGlobal('sFormErr',"");	   

       if ($this->valid_recaptcha()) {	   
	   
	   if ($bypass) 
	     return null;		  
	   
	   $recfields = (array) $this->cntform;//custom fields
	   $titlefields = (array) $this->cntformtitles;
	   
       if (!$recfields) {

	      $recfields = array('company','cperson','email','subject','mail_text');
		  $titlefields = array('_COMPANY','_CPERSON','_EMAIL','_SUBJECT','_BODY'); 
       }	   
	   
	   //print_r($recfields);
	   
	   if ($checkasterisk) {
	     foreach ($recfields as $field_num => $fieldname) {
    		//$title = localize($titlefields[$field_num],getlocal());
			$titles = explode('/',remote_paramload('SHFORM',$fieldname,$this->path));
			$title = $titles[getlocal()];
	     	if (strstr($title,'*')) { //check by titile using *

              if(!strlen(GetParam(_with($fieldname)))) {
                $this->msg .= localize('_MSG12',getlocal()) . " <font color=\"red\">" .
		                     $title . "</font> " .
		                     localize('_MSG11',getlocal()) . "<br>";		  			
			  }
			}
		 }		   
	   }	
	   else { 
         foreach ($recfields as $field_num => $fieldname) {
           //echo $fieldname,'<br>';
           if(!strlen(GetParam(_with($fieldname)))) {
             $this->msg .= localize('_MSG12',getlocal()) . " <font color=\"red\">" .
		                  localize($titlefields[$field_num],getlocal()) . "</font> " .
		                  localize('_MSG11',getlocal()) . "<br>";
             //echo $fieldname;
           }
		 }	     
       }
	   
	   //extra checks	
		
	   //mail chek	 
	   if ((GetParam("email")) && (checkmail(GetParam("email"))==false))
		   $this->msg .= localize('_INVALIDMAIL',getlocal()) . "<br>";	
	   
	   }//recaptcha
	   
       return ($this->msg);
    }	   
			  
	 
	 function valid_recaptcha() {
	 
	      if (!defined('RECAPTCHA_DPC')) return true;
		  
          if ($_POST["recaptcha_response_field"]) {
            $resp = recaptcha_check_answer ($this->recaptcha_private_key,
                                            $_SERVER["REMOTE_ADDR"],
                                            $_POST["recaptcha_challenge_field"],
                                            $_POST["recaptcha_response_field"]);
											
            //print_r($resp);
            if ($resp->is_valid) {
                $error = null;//echo "You got it!";
				$ret = true;
            } 
			else {
                # set the error code so that we can display it
                $error = $resp->error;
				$ret = false;
		        $this->msg .= '<br>' . "Incorrect recaptcha entry!";				
            }
		  }
		  else {
		    $ret = false;
		    $this->msg .= '<br>' . "Recaptcha entry required!";			  
		  }
		  //$sFormErr = $error;
		  //echo '>',$error,'-',$ret;
		  return ($ret);												
									 
     }  
	 
	function mailto($from,$to,$subject=null,$body=null,$ishtml=false,$instant=false) {
	
	    if ((defined('RCSSYSTEM_DPC')) && (!$instant)) { //no queue when no instant
		  //echo 'z',$to,'>',$from,'<br>';
		  $ret = GetGlobal('controller')->calldpc_method("rcssystem.sendit use $from+$to+$subject+$body++$ishtml");
        }
		else {
		  //echo 'AAAA',$to,'>',$from,'<br>';
		     if ((defined('SMTPMAIL_DPC')) &&
				 (seclevel('SMTPMAIL_DPC',$this->UserLevelID)) ) {
				 
		       $smtpm = new smtpmail;
			   
		       $smtpm->to($to); 
		       $smtpm->from($from); 
		       $smtpm->subject($subject);
		       $smtpm->body($body);			   

			   $mailerror = $smtpm->smtpsend();

			   unset($smtpm);
			 }
		}
			 
	}	 
  
	/*function combine_tokens($template_contents,$tokens) {
	
	    if (!is_array($tokens)) return;
		
		if (defined('FRONTHTMLPAGE_DPC')) {
		  $fp = new fronthtmlpage(null);
		  $ret = $fp->process_commands($template_contents);
		  unset ($fp);
          //$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.process_commands use ".$template_contents);		  		
		}		  		
		else
		  $ret = $template_contents;
		  
		//echo $ret;
	    foreach ($tokens as $i=>$tok) {
            //echo $tok,'<br>';
		    $ret = str_replace("$".$i."$",$tok,$ret);
	    }
		//clean unused token marks
		for ($x=$i;$x<20;$x++)
		  $ret = str_replace("$".$x."$",'',$ret);
		//echo $ret;
		return ($ret);
	}	*/  
  
};
}
?>