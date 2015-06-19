<?php

$__DPCSEC['AMAIL_DPC']='1;1;1;1;1;1;1;1;9';

if ((!defined("AMAIL_DPC")) && (seclevel('AMAIL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("AMAIL_DPC",true);

$__DPC['AMAIL_DPC'] = 'advcontactmail';
 
$__EVENTS['AMAIL_DPC'][0]="sendamail";

$__ACTIONS['AMAIL_DPC'][0]='amail';
$__ACTIONS['AMAIL_DPC'][1]="sendamail";
//$__ACTIONS['AMAIL_DPC'][2]='contact';

$__DPCATTR['AMAIL_DPC']['amail'] = 'amail,0,0,0,0,0,0,0,0,0,0,0,1';
$__DPCATTR['AMAIL_DPC']['sendamail'] = 'sendamail,0,0,0,0,0,0,0,0,0,0,0,0';

$__LOCALE['AMAIL_DPC'][0]='AMAIL_DPC;Contact Us;Επικοινωνία';
$__LOCALE['AMAIL_DPC'][1]='_OXI;No;Οχι';
$__LOCALE['AMAIL_DPC'][2]='_NAI;Yes;Ναι';
$__LOCALE['AMAIL_DPC'][3]='_TOWN;Town;Πόλη';
$__LOCALE['AMAIL_DPC'][4]='_ZIP;Zip;Ταχ. κωδικός';
$__LOCALE['AMAIL_DPC'][5]='_CNTR;Country;Χώρα';
$__LOCALE['AMAIL_DPC'][6]='_COMP;Name;Επωνυμία';
$__LOCALE['AMAIL_DPC'][7]='_CPER;Contact Person;Υπεύθυνος επικοινωνίας';
$__LOCALE['AMAIL_DPC'][8]='_ACTV;Activities;Δραστηριότητα';
$__LOCALE['AMAIL_DPC'][9]='_ADDR;Address;Διευθυνση';
$__LOCALE['AMAIL_DPC'][10]='_WEB;Web;Ιστοσελίδα';
$__LOCALE['AMAIL_DPC'][11]='_MAIL;e-mail;Ηλεκτρονικό ταχυδρομείο';
$__LOCALE['AMAIL_DPC'][12]='_SUBSE;Please send me mail informations about new products;Θέλω να λαμβάνω πληροφορίες για νέα προϊόντα μέσω ηλεκτρονικού ταχυδρομείου';
$__LOCALE['AMAIL_DPC'][13]='_AMTRUE;Succsessfull transmition!;Επιτυχής επικοινωνία!';
$__LOCALE['AMAIL_DPC'][14]='_AMFALSE;Unknown data!;Ακατάληλα δεδομένα!';
$__LOCALE['AMAIL_DPC'][15]='_FORMWARN;Fields with (*) required.;Τα πεδία με αστερίσκο (*) ειναι απαραίτητα.';
$__LOCALE['AMAIL_DPC'][16]='_WARNING;Warning;Προειδοποίηση';
$__LOCALE['AMAIL_DPC'][17]='_NOMOS;State;Νομός';


class advcontactmail {

    var $sendaddress;
	var $sendtype;
	
	var $msg;
	var $verify_address;	
	var $verify_message;
	var $verify_subject;	
	var $verify;
	var $info_message;
	var $title;
	var $post;
	
	function advcontactmail() {
		
		$this->sendaddress = paramload('AMAIL','mail');  	   	   	   
		$this->sendtype = paramload('AMAIL','type'); 		
		
		$this->msg=null;	
		$this->verify = paramload('AMAIL','verify');			
		$this->verify_address = paramload('AMAIL','verifymailer');			
		$this->verify_subject = paramload('AMAIL','vsubject');		
		$this->verify_message = paramload('AMAIL','vmsg');
		
	    $this->title = localize('AMAIL_DPC',getlocal());			
		
		$this->info_message = paramload('AMAIL','vbody');		
		
	    $this->post = false; //hold successfull posting		
	}
	

	function text2send() {
	
	   $head_text =    localize('_COMP',getlocal()) . ":" . GetParam("company") ."\r\n".
					   localize('_CPER',getlocal()) . ":" . GetParam("cperson") ."\r\n".
					   localize('_ACTV',getlocal()) . ":" . GetParam("activities") ."\r\n".
					   localize('_ADDR',getlocal()) . ":" . GetParam("address") ."\r\n".
					   localize('_TOWN',getlocal()) . ":" . GetParam("town") ."\r\n".
					   localize('_ZIP',getlocal()) . ":" . GetParam("zip") ."\r\n". 
					   localize('_CNTR',getlocal()) . ":" . GetParam("country") ."\r\n". 
					   localize('_TEL',getlocal()) . ":" . GetParam("tel") ."\r\n". 
					   localize('_FAX',getlocal()) . ":" . GetParam("fax") ."\r\n". 					   					   					   
					   localize('_MAIL',getlocal()) . ":" . GetParam("email") ."\r\n".
					   localize('_WEB',getlocal()) . ":" . GetParam("web") ."\r\n".					   
					   localize('_SUBSCR',getlocal()) . ":" . GetParam("subscribe") ."\r\n".					   					   
					   localize('_SUBJECT',getlocal()) . ":" . GetParam("subject") ."\r\n";		
					   
       return $head_text;					   	
	}
	
    function event($sAction) {
       $sFormErr = GetGlobal('sFormErr');	  	    		  
	   
	   switch ($this->sendtype) {
	   
	     case 'html' : $submiteddata = str_replace("\r\n","<br/>",$this->text2send()) . "<br/>" .
		                               GetParam("mail_text");
		               break;
	   
	     case 'text' : 
	     default     :
	                   $submiteddata = "-----------------------------------------------<br/>".
	                                   str_replace("\r\n","<br/>",$this->text2send()) .					   
	                                   "------------------------------------------------<br/>".
					                   GetParam("mail_text") . "<br/>";
	   }				    
  
       if (!$sFormErr) {   
  
	   switch ($sAction) {	

		case "sendamail"  : if (GetParam("company")) { //check company name
		
		                      //$this->post = $this->sendit(GetParam("email"),$this->sendaddress,GetParam("subject"),$submiteddata);
                              $smtpm = new smtpmail;
		                      $smtpm->to = $this->sendaddress; 
		                      $smtpm->from = GetParam("email"); 
		                      $smtpm->subject = GetParam("subject");
		                      $smtpm->body = $submiteddata;
		                      $error = $smtpm->smtpsend();
		                      unset($smtpm);							  
							  
							  //if ($this->post==true) {
							  if (!$error) {
							    $this->post = true;
	                            //verify
	                            if ($this->verify) { 
								  //$this->send_smtpmail($this->verify_address,GetParam("email"),$this->verify_subject,$this->verify_message);	 	
                                  $smtpm = new smtpmail;
		                          $smtpm->to = GetParam("email"); 
		                          $smtpm->from = $this->verify_address; 
		                          $smtpm->subject = $this->verify_subject;
		                          $smtpm->body = $this->verify_message;
		                          $err = $smtpm->smtpsend();
		                          unset($smtpm);									  
								}
							    //subscribe		
						        if (trim(GetParam('subscribe'))) 
								  $this->subscribe(GetParam("email"));	 
							  
							    $this->msg = localize('_AMTRUE',getlocal());
							  }  
							  else {
  							    $this->msg = localize('_AMFALSE',getlocal())."<br>".$error;
								SetInfo($error);
							  }	
							}
							else
							  $this->msg = localize('_AMFALSE',getlocal());  
	                        break; 																											  						
       }
      }
  }
  
  function action() {
     $__USERAGENT = GetGlobal('__USERAGENT');
     
	 switch ($__USERAGENT) {
	   case 'HTML' : $out = $this->advmailform(); break;
	   case 'GTK'  : $out = "amail"; break;
	 }
	 
	 return ($out);
  }    
  
  /////////////////////////////////////////////////////////////////////
  // adv contact mail form
  /////////////////////////////////////////////////////////////////////
  function advmailform() {

     $sFormErr = GetGlobal('sFormErr');
	 
     $myaction = seturl("t=amail");   	
	 	 
     //error message
	 //if ($sFormErr==localize('_MLS2',getlocal())) { //send mail succesfully
	 //if ($this->msg==localize('_AMTRUE',getlocal())) {	
	 if ($this->post==true) {
	   
       $out = setNavigator($this->title);	   
	   
	   $msg = localize('_AMTRUE',getlocal());
	   
	   $swin = new window(localize('AMAIL_DPC',getlocal()),$msg);
	   $out .= $swin->render("center::70%::0::group_win_body::center::0::0::");	
	   unset ($swin);		     
	 }
	 else { //show the form plus error if any

       $out = setNavigator($this->title);
	 	 
       $out .= setError($sFormErr . $this->msg);
	   
	   
	   $form = new form(localize('_ADDEVENT',getlocal()), "amail", FORM_METHOD_POST, $myaction, true);
	
	   $form->addGroup			("personal",			"1.");
	   $form->addGroup			("subscribe",			"2.");
	   $form->addGroup			("thema",				"3.");	   
	   $form->addGroup			("warning",				"4.");	   
	   if ($this->info_message) 
	     $form->addGroup			("info",			"5.");		   

	   $form->addElement		("personal",			new form_element_text		(localize('_COMP',getlocal())."*",     "company",		GetParam("company"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_CPER',getlocal()),     "cperson",		GetParam("cperson"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_ACTV',getlocal()),     "activities",	GetParam("activities"),				"forminput",	        20,				255,	0));	   	   	   
	   $form->addElement		("personal",			new form_element_text		(localize('_ADDR',getlocal()),     "address",	    GetParam("address"),				"forminput",	        20,				255,	0));	
	   $form->addElement		("personal",			new form_element_text		(localize('_TOWN',getlocal()),     "town",	        GetParam("town"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_greekmap	(localize('_NOMOS',getlocal()),     "amail","nomos",GetParam("nomos"),"forminput",20,20,1));	   	
	   $form->addElement		("personal",			new form_element_text		(localize('_ZIP',getlocal()),      "zip",	        GetParam("zip"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_CNTR',getlocal()),     "country",	    GetParam("country"),				"forminput",	        20,				255,	0));	
	   $form->addElement		("personal",			new form_element_text		(localize('_TEL',getlocal()),      "tel",	        GetParam("tel"),				"forminput",	        20,				255,	0));	
	   $form->addElement		("personal",			new form_element_text		(localize('_FAX',getlocal()),      "fax",	        GetParam("fax"),				"forminput",	        20,				255,	0));		   	   	   		   	   	   
	   $form->addElement		("personal",			new form_element_text		(localize('_MAIL',getlocal())."*",     "email",			GetParam("email"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_WEB',getlocal()),      "web",			"http://",		"forminput",		    20,				255,	0));
	   //$form->addElement		("subscribe",			new form_element_text		(localize('_SYBSCR',getlocal()),   "subscribe",		"",				"forminput",	        20,				255,	0));
	   $form->addElement		("subscribe",			new form_element_radio		(localize('_SUBSE',getlocal()),   "subscribe",      1,             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	   
	   $form->addElement		("thema",			    new form_element_text		(localize('_SUBJECT',getlocal())."*",  "subject",		GetParam("subject"),				"forminput",			60,				255,	0));	
	   $form->addElement		("thema",			    new form_element_textarea   (localize('_MESSAGE',getlocal()),  "mail_text",		GetParam("mail_text"),				"formtextarea",			60,				9));		      
	   
	   $form->addElement		("warning",			    new form_element_onlytext	(localize('_WARNING',getlocal()),  localize('_FORMWARN',getlocal()),"formtextonly"));	   
	   
	   if ($this->info_message)	   
	     $form->addElement		("info",			    new form_element_onlytext	("",  $this->info_message,"formtextonly"));	   	   

	   // Adding a hidden field
	   $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "sendamail"));
 
	   // Showing the form
	   $fout = $form->getform ();		
	   
	   $fwin = new window(localize('AMAIL_DPC',getlocal()),$fout);
	   $out .= $fwin->render();	
	   unset ($fwin);	

	   //$form->checkform();	   
	 }
     
	 if (defined(FRONTPAGE_DPC)) {
	   $cfp = new frontpage('amail',0);
	   $cpout = $cfp->render($out);
	   unset($cfp);		 
	 
	   //$cpout = $out; 
       return ($cpout);
	 }
	 else 
	   return ($out);
  }  
  
  function subscribe($mailaddr) {

     if ( (defined('SUBSCRIBE_DPC')) && (seclevel('SUBSCRIBE_DPC',$this->userLevelID)) ) {
	    //echo Getparam("subscribe"),">>>>>";
		GetGlobal('controller')->calldpc_method('subscribe.dosubscribe use '.$mailaddr); 
	 }
     elseif ( (defined('SENSUBSCRIBE_DPC')) && (seclevel('SENSUBSCRIBE_DPC',$this->userLevelID)) ) {
        //echo "SENSUB\n";
		GetGlobal('controller')->calldpc_method('sensubscribe.dosubscribe use '.$mailaddr); 
	 }
     elseif ( (defined('GFILIST_DPC')) && (seclevel('GFILIST_DPC',$this->userLevelID)) ) {
        //echo "GFILIST\n";
		GetGlobal('controller')->calldpc_method('gfilist.dosubscribe use '.$mailaddr); 
	 }		 		 	 
  }
  
};
}
?>