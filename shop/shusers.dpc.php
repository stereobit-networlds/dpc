<?php
$__DPCSEC['SHUSERS_DPC']='1;1;1;1;1;1;1;1;1';
$__DPCSEC['SIGNUP_']='2;2;1;1;1;2;2;2;9';
$__DPCSEC['USERSMNG_']='7;1;1;1;1;1;1;7;9';
$__DPCSEC['DELETEUSR_']='2;1;1;1;1;1;1;1;9';
$__DPCSEC['UPDATEUSR_']='1;1;2;2;2;2;2;2;9';
$__DPCSEC['ACCOUNTMNG_']='2;1;2;2;2;2;2;2;9';

if ((!defined("SHUSERS_DPC")) && (seclevel('SHUSERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHUSERS_DPC",true);

$__DPC['SHUSERS_DPC'] = 'shusers';


$__EVENTS['SHUSERS_DPC'][0]='shusers';
$__EVENTS['SHUSERS_DPC'][1]='insert';
$__EVENTS['SHUSERS_DPC'][2]='update';
$__EVENTS['SHUSERS_DPC'][3]='delete';
$__EVENTS['SHUSERS_DPC'][4]='searchuser';
$__EVENTS['SHUSERS_DPC'][5]= "reset_sessions";
$__EVENTS['SHUSERS_DPC'][999]='signup';

$__ACTIONS['SHUSERS_DPC'][0]='shusers';
$__ACTIONS['SHUSERS_DPC'][1]='signup';
$__ACTIONS['SHUSERS_DPC'][2]='insert';
$__ACTIONS['SHUSERS_DPC'][3]='update';
$__ACTIONS['SHUSERS_DPC'][4]='delete';
$__ACTIONS['SHUSERS_DPC'][5]='searchuser';
$__ACTIONS['SHUSERS_DPC'][6]= "reset_sessions";

$__DPCATTR['SHUSERS_DPC']['signup'] = 'signup,1,0,1';

$__LOCALE['SHUSERS_DPC'][0]='SHUSERS_DPC;Users;Users';
$__LOCALE['SHUSERS_DPC'][1]='_USERNAME;Username;Χρήστης';
$__LOCALE['SHUSERS_DPC'][2]='_PASSWORD;Password;Κωδικός';
$__LOCALE['SHUSERS_DPC'][3]='_MSG9;The following fields are optional.;Τα παρακάτω πεδία εξυπηρετούν στατιστικούς λόγους και δεν είναι απαραίτητα.';
$__LOCALE['SHUSERS_DPC'][4]='_MSG10;Successfull registration!;Επιτυχής καταχώρηση!';
$__LOCALE['SHUSERS_DPC'][5]='_MSG11;is required;είναι απαραίτητο';
$__LOCALE['SHUSERS_DPC'][6]='_MSG12;The value in field;Η τιμή στο πεδίο';
$__LOCALE['SHUSERS_DPC'][7]='_MSG13;No valid Password !;Ο κωδικός δεν συμφωνεί με την επιβεβαιωσή του';
$__LOCALE['SHUSERS_DPC'][8]='_MSG17;Invalid data. Your username used by someone else!;Μη αποδεκτά δεδομένα. Το όνομα χρήστη είναι δεσμευμένο';
$__LOCALE['SHUSERS_DPC'][9]='_MSG18;Invalid update or the data has no diference!;Μη αποδεκτά δεδομένα ή μη διαφορά νέων δεδομένων';
$__LOCALE['SHUSERS_DPC'][10]="_MSG19;Can't delete this record!;Αδύνατη η διαγραφή";
$__LOCALE['SHUSERS_DPC'][11]='_FNAME;First name;Ονομα';
$__LOCALE['SHUSERS_DPC'][12]='_LNAME;Last name;Επωνυμο';
$__LOCALE['SHUSERS_DPC'][13]='_VPASS;Verify Password;Επιβεβαίωση Κωδικού';
$__LOCALE['SHUSERS_DPC'][14]='_EMAIL;e-mail;Ηλ. Ταχυδρομείο';
$__LOCALE['SHUSERS_DPC'][15]='_COUNTRY;Country;Χώρα';
$__LOCALE['SHUSERS_DPC'][16]='_LANGUAGE;Language;Γλώσσα';
$__LOCALE['SHUSERS_DPC'][17]='_AGE;Age;Ηλικία';
$__LOCALE['SHUSERS_DPC'][18]='_GENDER;Gender;Φύλλο';
$__LOCALE['SHUSERS_DPC'][19]='_SEARCHUSR;Search User;Αναζήτηση Χρήστη';
$__LOCALE['SHUSERS_DPC'][20]='_SIGNUP;SignUp;Εγγραφή';
$__LOCALE['SHUSERS_DPC'][21]='_UPDATE;Update;Αλλαγή';
$__LOCALE['SHUSERS_DPC'][22]='_DELETE;Delete;Διαγραφή';
$__LOCALE['SHUSERS_DPC'][23]='_USERS;Users;Χρήστες';
$__LOCALE['SHUSERS_DPC'][24]='_FORMWARN;Fields with (*) required.;Τα πεδία με αστερίσκο (*) ειναι απαραίτητα.';
$__LOCALE['SHUSERS_DPC'][25]='_PDATA;Personal Data;Προσωπικά Στοιχεία';
$__LOCALE['SHUSERS_DPC'][26]='SHUSERS_CNF;Manage my Account;Διαχείρηση Λογαριασμού';
$__LOCALE['SHUSERS_DPC'][27]='_NOPRIV;Denied! No privileges.;Αρνηση! Δεν έχετε δικαίωμα.';
$__LOCALE['SHUSERS_DPC'][28]="_MSG20;Can't procced your request please try later!;Λαθος καταχώρησης. Παρακαλουμε δοκιμάστε αργότερα.";
$__LOCALE['SHUSERS_DPC'][29]="_MSG21;Password and verify password doesn't match!;Η επιβαιβαιωση κωδικου δεν συμφωνει με τον κωδικο σας.";
$__LOCALE['SHUSERS_DPC'][30]='_UNMSG;Username will be send to you at the end of this proccess!;Το ονομα χρήστη θα σας αποσταλει μετα το τελος της διαδικασίας!';
$__LOCALE['SHUSERS_DPC'][31]='_UMAILSUBH;New Registration ;Νεα εγγραφη';
$__LOCALE['SHUSERS_DPC'][32]='_UMAILSUBC;New user;Νέος χρήστης';
$__LOCALE['SHUSERS_DPC'][33]='_UNKNOWNENTRY;WARNING:You are not an official registrated user, you can not use all of our company services until to physicaly register by phone!;ΠΡΟΣΟΧΗ: Δεν ειστε έγκυρος πελάτης της εταιρείας μας και δεν θα έχετε δικαίωμα παραγγελίας, επικοιωνήστε τηλεφωνικώς για την πρώτη σας εγγραφή και επαναλάβετε την διαδικασία εγγραφής!';
$__LOCALE['SHUSERS_DPC'][34]='_TIMEZONE;Timezone;Ζωνη ωρας';
$__LOCALE['SHUSERS_DPC'][35]='_PASS;Password;Κωδικός';
$__LOCALE['SHUSERS_DPC'][36]='_USEREXISTS;Username exists. Already registered!;Το ονομάσ σας υπάρχει. Είστε ήδη καταχωρημένος!';
$__LOCALE['SHUSERS_DPC'][37]='_CUSTEXISTS;Customer exists. Already registered!;Είστε ήδη καταχωρημενος!';
$__LOCALE['SHUSERS_DPC'][38]='_SUCCESSREG;An mail send to you. Follow the instruction in order to complete the registartion process!;Ένα email σταλθηκε στον λογαριασμό ηλ. ταχυδρομείου που δηλώσατε. Ακολουθήστε τις οδηγίες για την ολοκληρωση της διαδικασίας εγγραφής.';


class shusers  {

	var $userLevelID;
	var $msg;
	var $pagenum;
	var $searchtext;
	var $country_id;
	var $language_id;
	var $age_id;
	var $gender_id;
	var $job_id;

	var $dbase;
	var $tell_it;
	var $atok;
	var $leeid;
	var $predef_customer;
	var $customer_sec;
	var $unknown_sec;
	var $security;
	var $c_message;
	var $it_sendfrom;
	var $username;
	var $userid;
	var $new_user_id;
	var $usemail2send,$usemailasusername;
	var $urlpath, $inpath;	
	var $includecusform;
	var $tokensout,$mytemplate;
	var $usrform,$usrformtitles,$checkuseasterisk,$asterisk;
	var $continue_register_customer, $deny_multiple_users;
	var $check_existing_customer, $map_customer, $customer_exist_id;
    var $inactive_on_register;	

	function shusers() {

	   $UserSecID = GetGlobal('UserSecID');
	   $sFormErr = GetGlobal('sFormErr');
	   $UserName = GetGlobal('UserName');
	   $UserID = GetGlobal('UserID');

       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	   $this->username = decode($UserName);
	   $this->userid = decode($UserID);
	   $this->msg = $sFormErr;
	   $this->pagenum = 30;
	   $this->searchtext = trim(GetParam("usernum"));
	   $this->path = paramload('SHELL','prpath');
	   
	   $this->urlpath = paramload('SHELL','urlpath');
	   $this->inpath = paramload('ID','hostinpath');		   

	   //startup select vals
	   $this->country_id = remote_paramload('SHUSERS','countryid',$this->path);
	   $this->language_id = remote_paramload('SHUSERS','lanid',$this->path);
	   $this->age_id = remote_paramload('SHUSERS','ageid',$this->path);
	   $this->gender_id = remote_paramload('SHUSERS','genderid',$this->path);
	   $this->tmz_id = remote_paramload('SHUSERS','tmzid',$this->path);	   
	   $this->job_id = 0;

	   //DATABSE_DPC lookup (no need)
	   //$this->dbase = new database; //db methods

	   $this->atok = remote_paramload('SHUSERS','atok',$this->path);
	   $this->leeid = remote_paramload('SHUSERS','leeid',$this->path);
	   $this->customer_sec = remote_paramload('SHUSERS','ifcustomer',$this->path);
	   $this->unknown_sec = remote_paramload('SHUSERS','else',$this->path);
	   $this->c_message = remote_paramload('SHUSERS','mailmsg',$this->path);
	   $this->it_sendfrom = remote_paramload('SHUSERS','sendusernamefrom',$this->path);

	   //init security
	   $this->security = $this->unknown_sec?$this->unknown_sec:0; //default

	   $this->predef_customer = null;
	   $this->usemailasusername =  remote_paramload('SHUSERS','usemailasusername',$this->path);
	   $this->usemail2send =  remote_paramload('SHUSERS','usemail2send',$this->path);
	   $this->tell_it = remote_paramload('SHUSERS','tellregisterto',$this->path);
	   
	   $cusform = remote_paramload('SHUSERS','includecusform',$this->path); 
	   //echo '>',$cusform;
	   $this->includecusform = $cusform?true:false;	 
	   //echo 'z',$this->includecusform,$this->path;
	   $usrt = GetSessionParam('usrtokens');
	   $this->tokensout = $usrt?true:false;
	   
	   $this->usrform = remote_arrayload('SHUSERS','usrform',$this->path);		   
	   $this->usrformtitles = remote_arrayload('SHUSERS','usrformtitles',$this->path);		
	   $this->checkuseasterisk = remote_paramload('SHUSERS','checkasterisk',$this->path);	 
	   $this->asterisk = $this->checkuseasterisk?'&nbsp;':'*'; //echo $this->asterisk,'>'; 
	   
	   $this->continue_register_customer = remote_paramload('SHUSERS','continueregcus',$this->path);
	   $this->deny_multiple_users = remote_paramload('SHUSERS','denymultuser',$this->path);	   

       //NOT LOADED YET //GetGlobal('controller')->calldpc_var('shcustomers.check_exist');		   	   	   
       $this->check_existing_customer = remote_paramload('SHCUSTOMERS','checkexist',$this->path);
	   $this->map_customer = null;
	   $this->customer_exist_id = null;
       $this->inactive_on_register = remote_paramload('SHUSERS','inactive_on_register',$this->path);	   
	}

    function event($sAction) {

	   $a = GetReq('a');

       if (!$this->msg) {

         switch($sAction)   {

	        case "searchuser" : $a = $this->search($this->searchtext); /*print $a; */break;

            case "insert": if ($this->includecusform) {
			
			                 if ( (defined('SHCUSTOMERS_DPC')) && (seclevel('SHCUSTOMERS_DPC',$this->userLevelID)) ) {
							   //echo 'a>';
			                   if ($this->check_existing_customer) {
							     //echo 'b>';
                                 if ($cid = GetGlobal('controller')->calldpc_method('shcustomers.customer_exist use 1')) {
		                           if ($cid<>-1) {//not mapped customer	
								     //echo 'c1>';
								     $checkcuserr = null;
									 $this->map_customer = true;						 
									 $this->customer_exist_id = $cid;
								   }
								   else {//already maped customer
								     //echo 'c2>';
								     $checkcuserr = localize('_CUSTEXISTS',getlocal());//'Customer exist!';
									 $this->map_customer = false;	
									 $this->customer_exist_id = null;
									 SetGlobal('sFormErr',$checkcuserr);
								   }
								 }
								 else  {//new customer
								   //echo 'c>';
								   $checkcuserr = GetGlobal('controller')->calldpc_method('shcustomers.checkFields use +'.$this->checkuseasterisk);   
								   $this->map_customer = null; //new customer	
								 } 
							   }
							   else {//new customer
			                     $checkcuserr = GetGlobal('controller')->calldpc_method('shcustomers.checkFields use +'.$this->checkuseasterisk);
								 //SetGlobal('sFormErr',$checkcuserr);
							   }
							 }
							   
							 //user check  
							 $checkusrerr = $this->checkFields(null,$this->checkuseasterisk);
							 //echo 'errors:',$checkusrerr,'|',$checkcuserr;
							 
			                 if ((!$checkusrerr) && (!$checkcuserr))  {		
							  //echo 'e>';
				              $this->insert_with_customer();							  					 
							 } 
							 
			               }//not include cus form
						   else {
				             if (!$this->checkFields(null,$this->checkuseasterisk)) {	
				              $this->insert();
                             }
			               }
				           break;
            case "update":
			//case trim(localize('_UPDATE',getlocal())) :
				           if (!$this->checkFields(null,$this->checkuseasterisk)) {
							  //auto subscribe
                              if ( (defined('SHSUBSCRIBE_DPC')) && (seclevel('SHSUBSCRIBE_DPC',$this->userLevelID)) ) {
								if (trim(GetParam('autosub'))=='on')
								  GetGlobal('controller')->calldpc_method('shsubscribe.dosubscribe use '.GetParam("eml"));//.'++-1');
								else
							      GetGlobal('controller')->calldpc_method('shsubscribe.dounsubscribe use '.GetParam("eml"));//.'+-1');
							  }
				              $this->update();
			               }
				           break;
            case "delete":
			//case trim(localize('_DELETE',getlocal())) :
						   //auto unsubscribe
                           if ( (defined('SHSUBSCRIBE_DPC')) && (seclevel('SHSUBSCRIBE_DPC',$this->userLevelID)) ) {
							  GetGlobal('controller')->calldpc_method('shsubscribe.dounsubscribe use '.GetParam("eml").'+-1');
						   }
				           $this->_delete();
				           break;

		    case "reset_sessions"        : $this->reset_sessions(); break;
          }
       }
	}

	function action($action) {
	   $__USERAGENT = GetGlobal('__USERAGENT');
	   $GRX = GetGlobal('GRX');

       switch ($sAction) {
		     case "reset_sessions"  : switch ($__USERAGENT) {
	                                       case 'HTML' : break;
	                                       case 'GTK'  : $out = $this->msg; break;
										   case 'CLI'  :
	                                       case 'TEXT' : $out = $this->msg; break;
	                                     }
		                                 break;
		     case 'signup' :
			 default          :	$out = $this->register();
       }

       return ($out);
	}


    function get_seclevels() {

      $levels = explode(",",paramload('SHUSERS','groups'));
      return ($levels);
    }

    function regform($fields='',$cmd=null,$isupdate=null,$isadmin=null,$nodelivery=null,$noinvtype=null,$noincludecusform=null) {
       //echo $fields;
	   $UserName = GetGlobal('UserName');
       $sFormErr = GetGlobal('sFormErr');
	   //readonly username field when update
	   $is_update = $UserName?true:false; 
	   if ($is_update)
         $readonly = 'READONLY';	   
	   
	   if (isset($noinvtype))//no for update
	     $invtype = '0';
	   else
	     $invtype = GetGlobal('controller')->calldpc_method('shcustomers.get_invoice_type');
		 
	   if (isset($nodelivery))//no for update
	     $delivery = '0';
	   else	 	   
	     $delivery = GetGlobal('controller')->calldpc_method('shcustomers.get_delivery_address');	 
		 
  	   $myinvtype = GetReq('invtype');  //ger req when error
	   //echo '>',$invtype,'>',$delivery;
       $dpccmd = $cmd?$cmd:'insert';
	   $usernamemsg = localize('_UNMSG',getlocal());
	   $a = GetReq('a'); //id
	   $g = GetReq('g'); //username 
	   
       $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',$delivery . $invtype . getlocal().'.','usrregister.htm') ; 
	   //echo $t;
	   
	   if (is_readable($t)) {//echo 'z';
		 $this->mytemplate = file_get_contents($t);
		 $this->tokensout = $tokensout = 1;
		 SetSessionParam('usrtokens',1);
       }		   
	   
	   if ($fields) {
		   $myfields = explode(";",$fields); //print_r($myfields);
           //print_r($fields);
		   $fname = $myfields[0];
		   $lname = $myfields[1];
		   $uname = $myfields[2];
		   $pwd = $myfields[3];
		   $pwd2 = $myfields[4];
		   $eml = $myfields[5];
		   $country_id = $myfields[6];
		   $language_id = $myfields[7];
		   $age_id = $myfields[8];
		   $gender_id = $myfields[9];   

           if (seclevel('USERSMNG_',$this->userLevelID)) {
		       $notes = $myfields[10];
		       $dcreate = $myfields[11];
		       $ipins = $myfields[12];
		       $ipupd = $myfields[13];
		       $llogin = $myfields[14];
		       $sparam = $myfields[15];
		       $sesid = $myfields[16];
		       $seclevid = $myfields[17];
		       $tmz_id = $myfields[18];					   
		   }
		   else
		   	   $tmz_id = $myfields[10];		
	   }
	   else {//get post data on error
		   $fname = GetParam('fname');
		   $lname = GetParam('lname');
		   $uname = GetParam('uname');
		   //$pwd = $myfields[3];
		   //$pwd2 = $myfields[4];
		   $eml = GetParam('eml');  
	   }

       $aligntitle = "right;40%;";
	   $alignfield = "left;60%;";

       $sFileName = seturl("t=signup&a=$a&g=$g&invtype=".$myinvtype."&editmode=".GetReq('editmode'),0,1);

	   //echo $this->tokensout,'>';
       if ($tokensout) {
	     //message at top
         $tokens[] = localize('_FORMWARN',getlocal()) . '<br>' . $sFormErr .
		             "<form method=\"POST\" action=\"" .$sFileName. "\" name=\"Registration\">";	   
	   }	   
	   else {	   
	     $warning = new window('',localize('_FORMWARN',getlocal()));
	     $out .= $warning->render(" ::100%::0::group_article_selected::center;100%;::");
	     unset($warning);

         $out .= "<form method=\"POST\" action=\"";
         $out .= "$sFileName";
         $out .= "\" name=\"Registration\">";
	   }
	   
	   
       if ($tokensout) {
	      $tokens[] = "<input type=\"text\" name=\"fname\" maxlength=\"50\" value=\"" .
                      ToHTML($fname) . "\" size=\"30\" >";
	   }
	   else {
       $field[] = localize('_FNAME',getlocal()) . $this->asterisk;
	   $attr[] = $aligntitle;
       $f  = "<input type=\"text\" name=\"fname\" maxlength=\"50\" value=\"";
       $f .= ToHTML($fname);
       $f .= "\" size=\"30\" >";
	   $field[] = $f;
	   $attr[] = $alignfield;
	   $w = new window('',$field,$attr);  $out .= $w->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field);  unset ($attr);
       }
	   
       if ($tokensout) {
	      $tokens[] = "<input type=\"text\" name=\"lname\" maxlength=\"50\" value=\"".
                      ToHTML($lname) . "\" size=\"30\" >";
	   }
	   else {	   
       $field0[] = localize('_LNAME',getlocal()) . $this->asterisk;
	   $attr0[] = $aligntitle;
       $f0  = "<input type=\"text\" name=\"lname\" maxlength=\"50\" value=\"";
       $f0 .= ToHTML($lname);
       $f0 .= "\" size=\"30\" >";
	   }
	   
	   
	   //DISABLE SELECTIOn LIST OF PRF DESCRIPTIONS
       /*$f0  = "<select name=\"lname\">";
	   $jobid = isset($lname) ? $lname : $this->job_id;
       $f0 .= $this->get_options('select pinid, pindescr from prf order by pindescr',false,true,$jobid);
       $f0 .= "</select>";	   */
	   	   
	   $field0[] = $f0;
	   $attr0[] = $alignfield;
	   $w0 = new window('',$field0,$attr0);  $out .= $w0->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field0);  unset ($attr0);
	   
		 
       if ($this->usemailasusername) {
	   
         if ($tokensout) {
	 
	       $tokens[] = "<input type=\"text\" name=\"uname\" maxlength=\"55\" value=\"".
                      ToHTML($uname) . "\" size=\"25\" $readonly>";
	     }
	     else {	   
         $field1[] = localize('_EMAIL',getlocal()) . $this->asterisk;
	     $attr1[] = $aligntitle;
         $f1  = "<input type=\"text\" name=\"uname\" maxlength=\"55\" value=\"";
         $f1 .= ToHTML($uname);
         $f1 .= "\" size=\"25\" $readonly>";
	     $field1[] = $f1;
	     $attr1[] = $alignfield;
		 }
	   }
       else {
         if ($tokensout) {
	       /*if ($fields) 
		     $tokens[] = ToHTML($uname);
		   else
		     $tokens[] = ToHTML($usernamemsg);*/
			 
	       $tokens[] = "<input type=\"text\" name=\"uname\" maxlength=\"50\" value=\"".
                      ToHTML($uname) . "\" size=\"15\" $readonly>";
	     }
	     else {	   
         $field1[] = '<br>' . localize('_USERNAME',getlocal()) . ":&nbsp;<br>";
	     $attr1[] = $aligntitle;
         $f1  = "<input type=\"text\" name=\"uname\" maxlength=\"50\" value=\"";
         $f1 .= ToHTML($uname);
         $f1 .= "\" size=\"15\" $readonly>";
	     if ($fields) {
	       $field1[] = "<br><label>" . ToHTML($uname) . "</label><br>";//$f1; NOT EDITABLE!!!!
	     }
	     else
	   	  $field1[] = "<br><label>" . ToHTML($usernamemsg) . "</label><br>";//message
	     $attr1[] = $alignfield;
		 }
	   }
	   $w1 = new window('',$field1,$attr1);  $out .= $w1->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field1);  unset ($attr1);

       if ($tokensout) {
	      $tokens[] = "<input type=\"password\" name=\"pwd\" maxlength=\"50\" value=\"".
                      ToHTML($pwd) . "\" size=\"15\" >";
	   }
	   else {		   
       $field2[] = localize('_PASSWORD',getlocal()) . $this->asterisk;
	   $attr2[] = $aligntitle;
       $f2  = "<input type=\"password\" name=\"pwd\" maxlength=\"50\" value=\"";
       $f2 .= ToHTML($pwd);
       $f2 .= "\" size=\"15\" >";
	   $field2[] = $f2;
	   $attr2[] = $alignfield;
	   $w2 = new window('',$field2,$attr2);  $out .= $w2->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field2);  unset ($attr2);
       }
	   
       if ($tokensout) {
	      $tokens[] = "<input type=\"password\" name=\"pwd2\" maxlength=\"50\" value=\"".
                      ToHTML($pwd2) . "\" size=\"15\" >";
	   }
	   else {		   
       $field3[] = localize('_VPASS',getlocal()) . $this->asterisk;
	   $attr3[] = $aligntitle;
       $f3  = "<input type=\"password\" name=\"pwd2\" maxlength=\"50\" value=\"";
       $f3 .= ToHTML($pwd2);
       $f3 .= "\" size=\"15\" >";
	   $field3[] = $f3;
	   $attr3[] = $alignfield;
	   $w3 = new window('',$field3,$attr3);  $out .= $w3->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field3);  unset ($attr3);
       }
	   
       if ($this->usemailasusername) {
	     //do nothing
	   }
	   else {
         if ($tokensout) {
	        $tokens[] = "<input type=\"text\" name=\"eml\" maxlength=\"55\" value=\"".
                        ToHTML($eml) . "\" size=\"25\" >";
	     }
	     else {		   
         $field4[] = localize('_EMAIL',getlocal()) . $this->asterisk;
	     $attr4[] = $aligntitle;
         $f4  = "<input type=\"text\" name=\"eml\" maxlength=\"55\" value=\"";
         $f4 .= ToHTML($eml);
         $f4 .= "\" size=\"25\" >";
	     $field4[] = $f4;
	     $attr4[] = $alignfield;
	     $w4 = new window('',$field4,$attr4);  $out .= $w4->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field4);  unset ($attr4);
		 }
       }
	   
	   
	   //INCLUDE CUSTOMER DATA TO MIX IN ONE FORM..SQL EXECUTE USER AND CUS QUERY... 
       if (($this->includecusform) && (!$noincludecusform)) {
	   
         if ($tokensout) {
		   $custokens = GetGlobal('controller')->calldpc_method('shcustomers.makesubform use 1');	
		   foreach ($custokens as $t)	 
	         $tokens[] = $t;
	     }
	     else {		
		     $out .= GetGlobal('controller')->calldpc_method('shcustomers.makesubform');	     
		 }
	   }	   
	   //////////////////////////////////////////////////////////////////////////
	   
	   
	   
       if ($tokensout) {
	      $tokens[] = localize('_MSG9',getlocal());
	   }
	   else {		   
	   $out .= "<br>";
	   $warning1 = new window('',localize('_MSG9',getlocal()));
	   $out .= $warning1->render(" ::100%::0::group_article_selected::center;100%;::");
	   unset($warning1);
	   $out .= "<br>";
	   }

       if ($tokensout) {
	      $cntr = isset($country_id) ? $country_id : $this->country_id;	   
	      $tokens[] = "<select name=\"country_id\">".get_options_file('country',false,true,$cntr)."</select>";
	   }
	   else {	   
       $field5[] = localize('_COUNTRY',getlocal()) . "&nbsp;";
	   $attr5[] = $aligntitle;
       $f5  = "<select name=\"country_id\">";
	   $cntr = isset($country_id) ? $country_id : $this->country_id;
       //$f5 .= $this->dbase->get_options('select country_id, country_desc from lookup_countries order by 2',false,true,$cntr);
       $f5 .= get_options_file('country',false,true,$cntr);
       $f5 .= "</select>";
	   $field5[] = $f5;
	   $attr5[] = $alignfield;
	   $w5 = new window('',$field5,$attr5);  $out .= $w5->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field5);  unset ($attr5);
       }
	   
       if ($tokensout) {
	      $lan = isset($language_id) ? $language_id : $this->language_id;   
	      $tokens[] = "<select name=\"language_id\">".get_options_file('languages',false,true,$lan)."</select>";
	   }
	   else {	   
       $field6[] = localize('_LANGUAGE',getlocal()) . "&nbsp;";
	   $attr6[] = $aligntitle;
       $f6  = "<select name=\"language_id\">";
	   $lan = isset($language_id) ? $language_id : $this->language_id;
       //$f6 .= $this->dbase->get_options('select lang_id, name from lookup_languages order by 2',false,true,$lan);
       $f6 .= get_options_file('languages',false,true,$lan);
       $f6 .= "</select>";
	   $field6[] = $f6;
	   $attr6[] = $alignfield;
	   $w6 = new window('',$field6,$attr6);  $out .= $w6->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field6);  unset ($attr6);
       }
	   
       if ($tokensout) {
	      $age = isset($age_id) ? $age_id : $this->age_id;  
	      $tokens[] = "<select name=\"age\">".get_options_file('age',false,true,$age)."</select>";
	   }
	   else {		   
       $field7[] = localize('_AGE',getlocal()) . "&nbsp;";
	   $attr7[] = $aligntitle;
       $f7  = "<select name=\"age\">";
	   $age = isset($age_id) ? $age_id : $this->age_id;
       //$f7 .= $this->dbase->get_options('select age_id, age_desc from lookup_ages order by 2',false,true,$age);
       $f7 .= get_options_file('age',false,true,$age);
       $f7 .= "</select>";
	   $field7[] = $f7;
	   $attr7[] = $alignfield;
	   $w7 = new window('',$field7,$attr7);  $out .= $w7->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field7);  unset ($attr7);
       }
	   
       if ($tokensout) {
	      $gender = isset($gender_id) ? $gender_id : $this->gender_id;   
	      $tokens[] = "<select name=\"gender\">".get_options_file('gender',false,true,$gender)."</select>";
	   }
	   else {		   
       $field8[] = localize('_GENDER',getlocal()) . "&nbsp;";
	   $attr8[] = $aligntitle;
       $f8  = "<select name=\"gender\">";
	   $gender = isset($gender_id) ? $gender_id : $this->gender_id;
       //$f8 .= $this->dbase->get_options('select gender_id, gender_desc from lookup_genders order by 2',false,true,$gender);
       $f8 .= get_options_file('gender',false,true,$gender);
       $f8 .= "</select>";
	   $field8[] = $f8;
	   $attr8[] = $alignfield;
	   $w8 = new window('',$field8,$attr8);  $out .= $w8->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field8);  unset ($attr8);
       }
	   
       if ($tokensout) {
	      $tmz = isset($tmz_id) ? $tmz_id : $this->tmz_id;   
	      $tokens[] = "<select name=\"timezone\">".get_options_file('timezones',false,true,$tmz)."</select>";
	   }
	   else {		   
	   //timezone field
       $field8a[] = localize('_TIMEZONE',getlocal()) . "&nbsp;";
	   $attr8a[] = $aligntitle;
       $f8a  = "<select name=\"timezone\">";
	   $tmz = isset($tmz_id) ? $tmz_id : $this->tmz_id;
       //$f8 .= $this->dbase->get_options('select gender_id, gender_desc from lookup_genders order by 2',false,true,$gender);
       $f8a .= get_options_file('timezones',false,true,$tmz);
       $f8a .= "</select>";
	   $field8a[] = $f8a;
	   $attr8a[] = $alignfield;
	   $w8a = new window('',$field8a,$attr8a);  $out .= $w8a->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field8);  unset ($attr8);
	   }
	   
       if ( (defined('SHSUBSCRIBE_DPC')) && (seclevel('SHSUBSCRIBE_DPC',$this->userLevelID)) ) {
	     $out .= "<br>";
	     $subs = new window('',localize('_SUBSCRWARN',getlocal()));
	     $out .= $subs->render(" ::100%::0::group_article_selected::center;100%;::");
	     unset($subs);
	     $out .= "<br>";

		 //check if user is in sub list
		 if (GetGlobal('controller')->calldpc_method('shsubscribe.isin use '.$eml))  
		   $statin = 'checked';

         if ($tokensout) {
	       $tokens[] = "<input type=\"checkbox\" name=\"autosub\"". $statin . ">";
	     }
	     else {				 
	     $subwin[] = localize('_SUBSCRTEXT',getlocal());
	     $subattr[] = $aligntitle;
	     $subwin[] = "<input type=\"checkbox\" name=\"autosub\"". $statin . ">";
	     $subattr[] = $alignfield;
	     $ss = new window('',$subwin,$subattr);  $out .= $ss->render("center::100%::0::group_article_selected::left::0::0::");   unset ($subwin);  unset ($subattr);
		 }
		          
		 $out .= "<br>";
	   }

	   //admin section  
       if ((seclevel('USERSMNG_',$this->userLevelID)) || ($isadmin) || (GetReq('editmode'>0))) {

	   $adminout = "<br>";

       $field9[] = "Notes&nbsp;";
	   $attr9[] = $aligntitle;
       $f9  = "<textarea name=\"notes\" cols=\"50\" rows=\"8\">";
       $f9 .= ToHTML($notes);
       $f9 .= "</textarea>";
	   $field9[] = $f9;
	   $attr9[] = $alignfield;
	   $w9 = new window('',$field9,$attr9);  
	   $adminout .= $w9->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field9);  unset ($attr9);

	   $adminout .= "<br>";

       $field10[] = "Date Created&nbsp;";
	   $attr10[] = $aligntitle;
       $f10  = "<input type=\"text\" name=\"dcreate\" maxlength=\"15\" value=\"";
       $f10 .= ToHTML($dcreate);
       $f10 .= "\" size=\"15\" >";
	   $field10[] = $f10;
	   $attr10[] = $alignfield;
	   $w10 = new window('',$field10,$attr10);  
	   $adminout .= $w10->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field10);  unset ($attr10);

       $field11[] = "IP insert&nbsp;";
	   $attr11[] = $aligntitle;
       $f11  = "<input type=\"text\" name=\"ipins\" maxlength=\"15\" value=\"";
       $f11 .= ToHTML($ipins);
       $f11 .= "\" size=\"15\" >";
	   $field11[] = $f11;
	   $attr11[] = $alignfield;
	   $w11 = new window('',$field11,$attr11);  
	   $adminout .= $w11->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field11);  unset ($attr11);

       $field12[] = "IP update&nbsp;";
	   $attr12[] = $aligntitle;
       $f12  = "<input type=\"text\" name=\"ipupd\" maxlength=\"15\" value=\"";
       $f12 .= ToHTML($ipupd);
       $f12 .= "\" size=\"15\" >";
	   $field12[] = $f12;
	   $attr12[] = $alignfield;
	   $w12 = new window('',$field12,$attr12);  
	   $adminout .= $w12->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field12);  unset ($attr12);

       $field13[] = "last Login&nbsp;";
	   $attr13[] = $aligntitle;
       $f13  = "<input type=\"text\" name=\"llogin\" maxlength=\"15\" value=\"";
       $f13 .= ToHTML($llogin);
       $f13 .= "\" size=\"15\" >";
	   $field13[] = $f13;
	   $attr13[] = $alignfield;
	   $w13 = new window('',$field13,$attr13);  
	   $adminout .= $w13->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field13);  unset ($attr13);

       $field14[] = "Security Param&nbsp;";
	   $attr14[] = $aligntitle;
       $f14  = "<input type=\"text\" name=\"sparam\" maxlength=\"15\" value=\"";
       $f14 .= ToHTML($sparam);
       $f14 .= "\" size=\"15\" >";
	   $field14[] = $f14;
	   $attr14[] = $alignfield;
	   $w14 = new window('',$field14,$attr14);  
	   $adminout .= $w14->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field14);  unset ($attr14);

       $field15[] = "Session ID&nbsp;";
	   $attr15[] = $aligntitle;
       $f15  = "<input type=\"text\" name=\"sesid\" maxlength=\"50\" value=\"";
       $f15 .= ToHTML($sesid);
       $f15 .= "\" size=\"15\" >";
	   $field15[] = $f15;
	   $attr15[] = $alignfield;
	   $w15 = new window('',$field15,$attr15);  
	   $adminout .= $w15->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field15);  unset ($attr15);

       $field16[] = "SecLevel ID&nbsp;";
	   $attr16[] = $aligntitle;
       $f16  = "<input type=\"text\" name=\"seclevid\" maxlength=\"15\" value=\"";
       $f16 .= ToHTML($seclevid);
       $f16 .= "\" size=\"15\" >";
	   $field16[] = $f16;
	   $attr16[] = $alignfield;
	   $w16 = new window('',$field16,$attr16);  
	   $adminout .= $w16->render("center::100%::0::group_article_selected::left::0::0::");   unset ($field16);  unset ($attr16);
	   
       if ($tokensout) {   
	      $tokens[] = $adminout;
	   }
	   else
	     $out .= $adminout;
	   }//admin section	 
		 
       //submit section
	   
	   if (GetReq('editmode')>0) {
           if ((seclevel('UPDATEUSR_',$this->userLevelID)) || ($isupdate) || (GetReq('editmode'))) {
              $updcmd = $cmd?$cmd:'update';
              $submitout .= "<input type=\"submit\" value=\"" . trim(localize('_UPDATE',getlocal())) . "\">";// onclick=\"document.forms('Registration').FormAction.value = '$updcmd';\">";
              $submitout .= "<input type=\"hidden\" value=\"$updcmd\" name=\"FormAction\"/>";			  
		   }

           /*if (seclevel('DELETEUSR_',$this->userLevelID)) {
		      $submitout .= "&nbsp;";
              $submitout .= "<input type=\"submit\" value=\"" . trim(localize('_DELETE',getlocal())) . "\">";// onclick=\"document.forms('Registration').FormAction.value = 'delete';\">";
              $submitout .= "<input type=\"hidden\" value=\"delete\" name=\"FormAction\"/>";			  
		   }*/	   
		   
		   $submitout .= "<input type=\"hidden\" value=".GetReq('rec')." name=\"rec\"/>";		   
	   }
	   else {
	     $un = decode($UserName);
         if (!$un) {
           $submitout .= "<input type=\"submit\" value=\"" . trim(localize('_SIGNUP',getlocal())) . "\" onclick=\"document.forms('Registration').FormAction.value = '$dpccmd';\">";
           $submitout .= "<input type=\"hidden\" value=\"insert\" name=\"FormAction\"/>";
         }
         else {
           //$submitout .= "<input type=\"hidden\" value=\"update\" name=\"FormAction\"/>";

           if ((seclevel('UPDATEUSR_',$this->userLevelID)) || ($isupdate) || (GetReq('editmode'))) {
              $updcmd = $cmd?$cmd:'update';
              $submitout .= "<input type=\"submit\" value=\"" . trim(localize('_UPDATE',getlocal())) . "\">";// onclick=\"document.forms('Registration').FormAction.value = '$updcmd';\">";
              $submitout .= "<input type=\"hidden\" value=\"$updcmd\" name=\"FormAction\"/>";			  
		   }

           if (seclevel('DELETEUSR_',$this->userLevelID)) {
              $submitout .= "<input type=\"submit\" value=\"" . trim(localize('_DELETE',getlocal())) . "\">";// onclick=\"document.forms('Registration').FormAction.value = 'delete';\">";
              $submitout .= "<input type=\"hidden\" value=\"delete\" name=\"FormAction\"/>";			  
		   }
         }
       }
       $submitout .= "<input type=\"hidden\" name=\"FormName\" value=\"Registration\">";
       $submitout .= "</form>";

       if ($tokensout) {
	      //print_r($tokens);   
	      $tokens[] = $submitout;
		  
		  $ret = $this->combine_tokens($this->mytemplate,$tokens);
		  return ($ret);			  
	   }
	   else {
	     $out .= $submitout;	   
		 
	     $uwin = new window(localize('_PDATA',getlocal()),$out);
	     $winout = $uwin->render();
	     unset($uwin);

	     return ($winout);
	   }

	}

    function checkFields($bypass=null,$checkasterisk=null) {
	   $sFormErr = GetGlobal('sFormErr');
	   SetGlobal('sFormErr',"");	   
	   
	   if ($bypass) 
	     return null;		   
	   
	   $recfields = (array) $this->usrform;//custom fields
	   $titlefields = (array) $this->usrformtitles;
	   
       if (!$recfields) {
         if ($this->usemailasusername) { 
	         $recfields = array('uname','pwd','pwd2','fname','lname');
			 $titlefields = array('_EMAIL','_PASS','_VPASS','_FNAME','_LNAME');
	     }
	     else {
	       $recfields = array('eml','pwd','pwd2','fname','lname');
		   $titlefields = array('_EMAIL','_PASS','_VPASS','_FNAME','_LNAME');
		 }  
       }	   
	   
	   
	   if ($checkasterisk) {
	     foreach ($recfields as $field_num => $fieldname) {
    		//$title = localize($titlefields[$field_num],getlocal());
			$titles = explode('/',remote_paramload('SHUSERS',$fieldname,$this->path));
			$title = $titles[getlocal()];
	     	if (strstr($title,'*')) { //check by titile using *

              if(!strlen(GetParam(_with($fieldname)))) {
                $sFormErr .= localize('_MSG12',getlocal()) . " <font color=\"red\">" .
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
             $sFormErr .= localize('_MSG12',getlocal()) . " <font color=\"red\">" .
		                  localize($titlefields[$field_num],getlocal()) . "</font> " .
		                  localize('_MSG11',getlocal()) . "<br>";
             //echo $fieldname;
           }
		 }	     
       }
	   
	   //extra checks
	   //...password verification
       if(GetParam("pwd")!=GetParam("pwd2"))
         $sFormErr .= localize('_MSG13',getlocal()) . "<br>";	
		
	   //mail chek	 
       if ($this->usemailasusername) { 
	     if ((GetParam("uname")) && (checkmail(GetParam("uname"))==false))
		   $sFormErr .= localize('_INVALIDMAIL',getlocal()) . "<br>";	
	   }
	   else {
	     if ((GetParam("eml")) && (checkmail(GetParam("eml"))==false))
		   $sFormErr .= localize('_INVALIDMAIL',getlocal()) . "<br>";		   
	   }
	   
	   if (GetGlobal('FormAction')=='insert') {//only at insert
	     if (($this->deny_multiple_users) && ($this->user_exists(GetParam("uname")))) {
		   $sFormErr .= localize('_USEREXISTS',getlocal()) . "<br>";		 
	     }
	   }
		 	   
	   SetGlobal('sFormErr',$sFormErr);
       return $sFormErr;
    }

    function getuser($id="",$fkey=null,$isadmin=null,$isupdate=null) {
       $db = GetGlobal('db');
	   $UserName = GetGlobal('UserName');
	   $myfkey = $fkey?$fkey:'username';
	   $a = GetReq('a');
	   $g = GetReq('g');
	   $un = decode($UserName); //echo $un;
	   $myrec = $id?$id:$un;
	   

	   if ($isupdate) {    
	     $recfields = array('fname','lname','username','password','vpass','email');//,'lname');
	     $basicfields = implode(',',$recfields);	   
		 
	     $sSQL = "select " . $basicfields . ",CNTRYID,LANID,AGEID,GENID,TIMEZONE,SUBSCRIBE from users";//,NOTES,STARTDATE,IPINS,IPUPD,LASTLOGON,SECPARAM,SESID,SECLEVID,TIMEZONE FROM users" .
		 if (strstr($myfkey,'id'))
	    	$sSQL.= " WHERE " . $myfkey . "=" . $myrec;// ."'";	
		 else
		 	$sSQL.= " WHERE " . $myfkey . "='" . $myrec . "'";	   
		 $sSQL.= " and lname<>'SUBSCRIBER'"; //compatibility with extra rec as subscriber		  
       }
	   else {//????
         if ($isadmin) {//admin selection
	         $sSQL = "SELECT FNAME,LNAME,USERNAME,PASSWORD,VPASSWRD,EMAIL,CNTRYID,LANID,AGEID,GENID,NOTES,STARTDATE,IPINS,IPUPD,LASTLOGON,SECPARAM,SESID,SECLEVID,TIMEZONE FROM users" .
			 " WHERE ".$myfkey."='" . $myrec . "'";// . " AND USERNAME='" . $g . "'";
	     }
         elseif ((!$a) || (!seclevel('USERSMNG_',$this->userLevelID))) { //unique selection
	         $sSQL = "SELECT FNAME,LNAME,USERNAME,PASSWORD,VPASS,EMAIL,CNTRYID,LANID,AGEID,GENID,NOTES,STARTDATE,IPINS,IPUPD,LASTLOGON,SECPARAM,SESID,SECLEVID,TIMEZONE FROM users" .
			 " WHERE " . $myfkey . "='" . $myrec . "'";// ."'";
	     }
	     else {//admin selection
	         $sSQL = "SELECT FNAME,LNAME,USERNAME,PASSWORD,VPASSWRD,EMAIL,CNTRYID,LANID,AGEID,GENID,NOTES,STARTDATE,IPINS,IPUPD,LASTLOGON,SECPARAM,SESID,SECLEVID,TIMEZONE FROM users" .
			 " WHERE ".$myfkey."='" . $a . "' AND USERNAME='" . $g . "'";
	     }
		 	 
       }//elseif
	   
       $result = $db->Execute($sSQL,2);
	   
	   //echo $sSQL;	   

	   if (count($result->fields)>1) {//check result...
	    
		foreach ($result->fields as $i=>$rec) {
	      if (is_numeric($i)) {
		    $record[] = $rec;
		  }
		}  
        $ret = implode(";",$record); //echo $record;		   
	   }	
		 
       //echo $ret;  
	   return ($ret);
	}

	//return array of record
    function getuserdata($what=null) {

       //read data
	   $fields = $this->getuser();

       //in case of no customer data this must return null
	   if (strlen($fields)>3) { //if empty returns ';;;'

		   $myfields = explode(";",$fields);

		   $data = $myfields;
       }

       //print_r($data);
	   if (isset($what)) {
	     //echo $data[$what];
	     return ($data[$what]);
	   }
	   else {
	     return ($data);
	   }
	}

    function readusers() {
      $db = GetGlobal('db');
	  $g = GetReq('g');

      if (seclevel('USERSMNG_',$this->userLevelID)) {

         $sSQL = "SELECT CODE2,FNAME,LNAME,USERNAME FROM users ";
		 //echo $sSQL;

         $browser = new browseSQL(localize('_USERS',getlocal()));
	     $out .= $browser->render($this->sen_db,$sSQL,$this->T_users,"signup",$this->pagenum,$this,1,1,1,0);
	     unset ($browser);

		 $out .= $this->searchform();

		 return ($out);
	  }
	}

	function register($myuser=null,$myfkey=null,$selectid=null,$cmd=null) {
       $user = decode(GetGlobal('UserID'));
	   $sFormErr = GetGlobal('sFormErr');
	   $a = GetReq($selectid)?GetReq($selectid):GetReq('a');
	   $mycmd_update = $cmd?$cmd:'update';	   
	   
	   
       //echo $sFormErr,'<br>';
       if ($sFormErr=="ok") {

   		   SetGlobal('sFormErr',"");

	       $myaction = GetGlobal('dispatcher')->getqueue(); //echo $myaction,"<><><><";
		   switch ($myaction) {

            case "insert": 
			               $out .= localize('_SUCCESSREG',getlocal());//setError(localize('_MSG10',getlocal()));
						   $out .= $this->after_registration_goto();
						   break;
            case "update": 
						   $out = setError(localize('_MSG10',getlocal()));
						   $out .= $this->after_update_goto();
						   break;
            case "delete": $out = setError(localize('_MSG10',getlocal()));
			               $out .= $this->after_delete_goto();
			               break;
		   }

	   }
	   else {

		   if ((!$user) && (seclevel('SIGNUP_',$this->userLevelID))) {
		   
	         //if (!GetReq('editmode'))
		       //$out = setNavigator(localize('_SIGNUP',getlocal()));
			 
 			 if (!$this->tokensout)			 
			   $out .= setError($sFormErr);
			  
	         $out .= $this->regform(); //insert action
		   }	   
   	       elseif (seclevel('ACCOUNTMNG_',$this->userLevelID)) {

   	          if ((seclevel('USERSMNG_',$this->userLevelID)) && (!$a)) {
			  
			     if (!GetReq('editmode'))
		           $out = setNavigator(localize('_USERS',getlocal()));
				 
                 $out .= $this->readusers(); //browse users action
		      }
			  else {
		         //$out = setNavigator(localize('_UPDATE',getlocal()));
				 if ($myuser)
                   $record = $this->getuser($myuser,$myfkey,null,1);
				 else				 
                   $record = $this->getuser(null,null,null,1);
				 
 				 if (!$this->tokensout)
	               $out .= setError($sFormErr);
				   
	             $out .= $this->regform($record,$mycmd_update,1,null,1,1,1); //update action
				 
		 	     //VIEW TRANSACTIONS
	             /*if (defined('SHTRANSACTIONS_DPC')) {
	               $out .= seturl('t=transview&pl=20',localize('_TRANSLIST',getlocal()));
		           $out .= GetGlobal('controller')->calldpc_method('shtransactions.viewTransactions');
                 }*/
				 //VIEW CUSTOMER LISTS
				 if (defined('SHCUSTOMERS_DPC')) {
                   //$out .= GetGlobal('controller')->calldpc_method('shcustomers.addcustomerform');	  
	               //$out .= GetGlobal('controller')->calldpc_method('shcustomers.show_customer_delivery');  				 
				   
				   $out .= GetGlobal('controller')->calldpc_method('shcustomers.show_customers_list');	  
		         }
			  }
		   }
		   else {
		     //$out = setNavigator(localize('_SIGNUP',getlocal()));
			 //$out = setError(localize('_NOPRIV',getlocal()));

             $b = new msgBox(localize('_NOPRIV',getlocal()),"OKOnly","Error");
             //use the makeLinks() for the buttons to work
             $links = array(seturl(''));//,seturl('t=slogout'));
             $b->makeLinks($links);
             $out .= $b->render();

			 /*$a = new tabmenu(array(0=>"First",1=>"Second",2=>"Third"),
			                  array(0=>"First content",1=>"Second content",2=>"Third content"),
							  array(),
							  0,
							  '10','210','500','200'
							 );
			 $out .= $a->render();	*/
		   }
	   }

	   return ($out);
	}
	
	function after_registration_goto() {
	   $sFormErr = GetGlobal('sFormErr');	
	
       if ($this->predef_customer) {//repdefined customer
						     $content = $this->predef_customer . "<H4>".$this->atok."</H4>";
							 $mx = "100%";
			                 $win = new window(localize('_MSG10',getlocal()),$content);
			                 $out .= $win->render("center::$mx::0::group_win_body::left::0::0::");
			                 unset($win);									 
	   }
	   elseif ($this->includecusform) {//customer has submited with user form
						     if ( (defined('SHCART_DPC')) && (seclevel('SHCART_DPC',$this->userLevelID)) ) {
							   $out .= GetGlobal('controller')->calldpc_method('shcustomers.after_registration_goto');
							 }
							 elseif ( (defined('SHLOGIN_DPC')) && (seclevel('SHLOGIN_DPC',$this->userLevelID)) ) {
							   $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');
							 }
	   }
	   else {//goto customer registration
       
		   if (($this->continue_register_customer) && ( (defined('SHCUSTOMERS_DPC')) && (seclevel('SHCUSTOMERS_DPC',$this->userLevelID)) )) {
							   //find id......
							   $this->new_user_id = GetGlobal('controller')->calldpc_method('shcustomers.getmaxid')+1;
                               $out .= GetGlobal('controller')->calldpc_method('shcustomers.register use '.$this->new_user_id);
		   }	  
		   elseif ( (defined('SHLOGIN_DPC')) && (seclevel('SHLOGIN_DPC',$this->userLevelID)) ) {
							   $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');
		   }
		   else //continue rendering
		     $out .= '';

						/*     if ( (defined('SHCUSTOMERS_DPC')) && (seclevel('INSERTCUSTOMER_',$this->userLevelID)) ) {
							   //find id......
							   $this->new_user_id = GetGlobal('controller')->calldpc_method('shcustomers.getmaxid')+1;
                               $out .= GetGlobal('controller')->calldpc_method('shcustomers.register use '.$this->new_user_id);
							   //$mx = "100%";
							 }
							 elseif ( (defined('SHLOGIN_DPC')) && (seclevel('SHLOGIN_DPC',$this->userLevelID)) ) {
							   $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');
							 }
						     //else
							   //$mx = "50%";*/
							 
	   }	
						   				   
						   
	   return ($out);
	}	
	
	function after_update_goto() {
	   $myaction = GetParam('FormAction');
	   //echo '>',$myaction;
	   //print_r($_POST);
	   if ((GetGlobal('UserID')) && (stristr($myaction,'update'))) {//already in..modify account
	       //update1 or update2 (user or customer)
	      
		   if ($myaction=='update') {//user
		     $out .= $this->register();
		   }
		   elseif ((($myaction=='update2') && 
		           (defined('SHCUSTOMERS_DPC')) && (seclevel('SHCUSTOMERS_DPC',$this->userLevelID)))) {
				   
                $out .= GetGlobal('controller')->calldpc_method('shcustomers.register');		   
		   }
	   }	
	   
	   return ($out);
	}
	
	function after_delete_goto() {
	
	  return ($out);
	}
	
	//check if the registered user is a valid sen user and if it is return his leeid
	//preset is used to pass lanme+fname as default username	
	function find_predefined_customer() {
	   $a = GetParam('fname');
	   $b = GetParam('lname');	
	
       //SEN SUPPORT : get customer data or register new customer
       if ( (defined('SHCUSTOMERS_DPC')) && (seclevel('SHCUSTOMERS_DPC',$this->UserLevelID)) ) {

		  $WSQL = "NAME='$a' AND PRFDESCR='$b'";//PRFDESCR='$b'";

		  $leeid = GetGlobal('controller')->calldpc_method('shcustomers.search_customer_id use '.$WSQL);
		  //echo "LEEID:",$leeid;
		  if ($leeid) {
		    $this->predef_customer = GetGlobal('controller')->calldpc_method('shcustomers.showcustomerdata use '.$leeid);

			//overwrite default user leeid
			$this->leeid = $leeid;
			//set security param
			$this->security = $this->customer_sec; //customer sec id
			//return leeid as username
			return ($leeid);
		  }

	   }
	   
	   return null;	
	}


	function pre_insert_task($preset=null) {
	   $a = GetParam('fname');
	   $b = GetParam('lname');	
	   $c = GetParam('uname');		
	   
	   //echo $a,$b,$c,'<br>';   
	          
       if ($this->usemailasusername) {
	     if (checkmail(GetParam("uname"))==true)
	       $genun = strtolower(trim($c)); //string = code of cus
		 else
		   return null;  
	   }	 
	   else	{//find predef customer
         $genun = $this->find_predefined_customer(); //number=code2 of cus	 
       
	     //CHECK
	     //default username = the combination of fname (as inserted by user) plus lname=job title
	     //else if is customer this function return leeid of customer where is the username
	     if (!$genun)	{
	       if ($preset)
		     $genun = $preset;
		   else  
	         $genun = $a.' '.$b; //combine fisrt last name
	     }	
	   } 
		 
	   //echo '>'.$genun;	 
	   return ($genun);
	}

	//parameter is the result of input = username
	function after_insert_task($username=null,$password=null,$fname=null,$lname=null) {

      //mail registration info to the company
	  if ($this->tell_it) {
	    $template= "userinserttell.htm";
	    $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
		//echo $t;
	    if (is_readable($t)) {
		  $mytemplate = file_get_contents($t);
				
		  $tokens[] = $username;	
		  $tokens[] = $password;	
		  $tokens[] = $fname;	
		  $tokens[] = $lname;			  					
			
		  $mailbody = $this->combine_tokens($mytemplate,$tokens);
	    }	
	   	else {	
	      $cmailb = GetParam('fname') . "<br/>" . GetParam('lname');

		  $template = paramload('SHELL','prpath') . "insertusrcom.tpl";
		  $mailbody = str_replace("##_LINK_##",$cmailb,file_get_contents($template));
        }
	    //$this->mailto(GetParam('eml'),$this->tell_it,localize('_UMAILSUBH',getlocal()),$mailbody);
		$subject = remote_paramload('SHUSERS','tellsubject',$this->path);
		$mysubject = $subject?$subject:localize('_UMAILSUBC',getlocal());
	    $this->mailto($this->usemail2send,$this->tell_it,$mysubject,$mailbody);//,1,1);
		//echo $this->usemail2send,':',$this->tell_it,'<br>';
	  }
	  
      //send username/password to user
	  if ($this->it_sendfrom) {
	    $template= "userinsert.htm";
	    $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
		//echo $t;
	    if (is_readable($t)) {
		  $mytemplate = file_get_contents($t);
			
		  $tokens[] = $username;	
		  $tokens[] = $password;			  
			
		  $mailbody = $this->combine_tokens($mytemplate,$tokens);
		  //echo $mailbody;
	    }	
	   	else {		
	      if ($password)
	        $pass = "/Password:" . $password;		  
			
	      $mailcontent = "Username:" . $username . $pass . $this->c_message;

		  $template = paramload('SHELL','prpath') . "insertusrusr.tpl";
		  $mailbody = str_replace("##_LINK_##",$mailcontent,file_get_contents($template));
        }
		
		$subject = remote_paramload('SHUSERS','tellsubject',$this->path);
		$mysubject = $subject?$subject:localize('_UMAILSUBC',getlocal());

        if ($this->usemailasusername) {
	      $this->mailto($this->it_sendfrom,$username,$mysubject,$mailbody);//,1,1);
		  //echo $this->it_sendfrom,':',$username,'A<br>';		  
	    }	 
	    else {
	      $this->mailto($this->it_sendfrom,GetParam('eml'),$mysubject,$mailbody);//,1,1);
		  //echo $this->it_sendfrom,':',GetParam('eml'),'B<br>';		 
		} 
 
	  }
	  
	  $this->auto_subscribe();

	}
	
	function auto_subscribe() {

       if ($this->usemailasusername) 
	     $submail = GetParam("uname");
       else
	     $submail = GetParam("eml");	
		 
	   if ($submail) {
        if ( (defined('SHSUBSCRIBE_DPC')) && (seclevel('SHSUBSCRIBE_DPC',$this->userLevelID)) ) {
	     if (trim(GetParam('autosub'))=='on') {
		     
			GetGlobal('controller')->calldpc_method('shsubscribe.dosubscribe use '.$submail.'+1+-1');
	     }  
	    }	
	   }
	}	

	function insert() {
       $db = GetGlobal('db');
	   $sFormErr = GetGlobal('sFormErr');
       $seclevid = $this->security?$this->security:'0';  	   

       $user_code = $this->pre_insert_task();	
	   //echo '+',$user_code;
	   
	   if (!$user_code) {
		   SetGlobal('sFormErr',localize('_MSG20',getlocal()));
		   return null;	   
	   }
	   
	   //save it to restore if 2nd step exist to insert custime and to connect
	   SetSessionParam('new_user_code',$user_code); 

	   if ($un = $this->username_exist()) {

	     SetGlobal('sFormErr', localize('_MSG17',getlocal()) . ' ' . $un);
	   }
	   else {
		 
		 //$code2 = $this->leeid;//?$this->leeid:$code; echo $code;
         $sSQL = "insert into users" . " (" . "code2,fname,lname,username,password,vpass,email,CNTRYID,LANID,AGEID,GENID,timezone,notes";

         if (seclevel('USERSMNG_',$this->userLevelID)) {
 	  	    $sSQL .= ",STARTDATE,IPINS,IPUPD,LASTLOGON,SECPARAM,SESID,SECLEVID";
         }
		 else {
		    $sSQL .= ",SECLEVID"; //only security
		 }

         $sSQL .= ")" .  " values (" .
				"'" . addslashes($user_code) . "'," . //username as usercode
                "'" . addslashes(GetParam("fname")) . "'," .
			    "'" . addslashes(GetParam("lname")) . "'," .
                "'" . addslashes($user_code) . "'," . //username=usercode
                "'" . addslashes(GetParam("pwd")) . "'," .
                "'" . addslashes(GetParam("pwd2")) . "',";

          if ($this->usemailasusername)
                $sSQL .= "'" . addslashes($user_code) . "',";//email = usercode
		  else
                $sSQL .= "'" . addslashes(GetParam("eml")) . "',";

          $country = GetParam("country_id")?GetParam("country_id"):0;
		  $language = GetParam("language_id")?GetParam("language_id"):0;
		  $age = GetParam("age")?GetParam("age"):0;
		  $gender = GetParam("gender")?GetParam("gender"):0;
		  $tmz = GetParam("timezone")?GetParam("timezone"):0;
		  
		  $active = $this->inactive_on_register?'DELETED':'ACTIVE';
		  
          $sSQL .= $country . "," . $language . "," . $age . "," . $gender . "," . $db->qstr($tmz) . "," .	$db->qstr($active); 

           if (seclevel('USERSMNG_',$this->userLevelID)) {
 	  	      $sSQL .= "," .
           	    //"'" . GetParam("notes") . "'," . //became default active
                GetParam("dcreate") . "," .
                "'" . GetParam("ipins")  . "'," .
                "'" . GetParam("ipupd")  . "'," .
                "'" . GetParam("llogin")  . "'," .
                "'" . GetParam("sparam")  . "'," .
                "'" . GetParam("sesid")  . "'," .
                "'" . GetParam("seclevid") ;
		   }
		   else {
		       $sSQL .= "," . $seclevid;//only security automated (predefined customer)
		   }

	     $sSQL .= ")";
         //echo $sSQL;
         $ret = $db->Execute($sSQL);	 //print_r($ret);

         if ($ret = $db->Affected_Rows()) {
		   SetGlobal('sFormErr',"ok");

		   $this->after_insert_task($user_code,GetParam("pwd"),GetParam("fname"),GetParam("lname"));//send code to customer
		   
	       //INCLUDE CUSTOMER DATA TO MIX IN ONE FORM..SQL EXECUTE USER AND CUS QUERY... 
           if ($this->includecusform) {		
		     $sFormErr = GetGlobal('controller')->calldpc_method('shcustomers.subinsert use '.$user_code);
			 SetGlobal('sFormErr',$sFormErr);	   
		   }
		   //////////////////////////////////////////////////////////////////////////		   
		 }
	     else {
		   $ret = $db->ErrorMsg();
		   echo $ret;
		   SetGlobal('sFormErr',localize('_MSG20',getlocal()));
		 }
	   }
	}
	
	function insert_with_customer() {
       $db = GetGlobal('db');
	   $sFormErr = GetGlobal('sFormErr');
       $seclevid = $this->security?$this->security:'0';   

       $user_code = $this->pre_insert_task();	
	   //echo '+',$user_code;
	   
	   if (!$user_code) {
		   SetGlobal('sFormErr',localize('_MSG20',getlocal()));
		   return null;	   
	   }	
	   
	   //save it to restore if 2nd step exist to insert custime and to connect
	   SetSessionParam('new_user_code',$user_code); 

	   if ($un = $this->username_exist()) {

	     SetGlobal('sFormErr', localize('_MSG17',getlocal()) . ' ' . $un);
	   }
	   else {	 
	     //start map procedure
	     $map_customer = null;
	     
		 //echo '>',$this->check_existing_customer;
	     if ($this->check_existing_customer) {
		   if ($this->map_customer===true)//map a customer
             $sFormErr = 'ok';
		   elseif ($this->map_customer===false)//already mapped error	 
		     $sFormErr = 'Customer is already mapped!';//will not be shown just err...
		   else //is null = new customer	 
		     $sFormErr = GetGlobal('controller')->calldpc_method('shcustomers.subinsert use '.$user_code.'+1');
		 }
		 else //register new customer
		   $sFormErr = GetGlobal('controller')->calldpc_method('shcustomers.subinsert use '.$user_code.'+1');
		 
		 if ($sFormErr=='ok') {//start user registartion
		
		 
		 //$code2 = $this->leeid;//?$this->leeid:$code; echo $code;
         $sSQL = "insert into users" . " (" . "code2,fname,lname,username,password,vpass,email,CNTRYID,LANID,AGEID,GENID,timezone,notes";

         if (seclevel('USERSMNG_',$this->userLevelID)) {
 	  	    $sSQL .= ",STARTDATE,IPINS,IPUPD,LASTLOGON,SECPARAM,SESID,SECLEVID";
         }
		 else {
		    $sSQL .= ",SECLEVID"; //only security
		 }

         $sSQL .= ")" .  " values (" .
				"'" . addslashes($user_code) . "'," . //username as usercode
                "'" . addslashes(GetParam("fname")) . "'," .
			    "'" . addslashes(GetParam("lname")) . "'," .
                "'" . addslashes($user_code) . "'," . //username=usercode
                "'" . addslashes(GetParam("pwd")) . "'," .
                "'" . addslashes(GetParam("pwd2")) . "',";

          if ($this->usemailasusername)
                $sSQL .= "'" . addslashes($user_code) . "',";//email = usercode
		  else
                $sSQL .= "'" . addslashes(GetParam("eml")) . "',";
				
		  $active = $this->inactive_on_register?'DELETED':'ACTIVE';	

          $sSQL .= GetParam("country_id") . "," .
                GetParam("language_id") . "," .
            	GetParam("age") . "," .
            	GetParam("gender") . "," .
            	"'" . GetParam("timezone") . "'," .				
				"'$active'"; //default active

           if (seclevel('USERSMNG_',$this->userLevelID)) {
 	  	      $sSQL .= "," .
           	    //"'" . GetParam("notes") . "'," . //became default active
                GetParam("dcreate") . "," .
                "'" . GetParam("ipins")  . "'," .
                "'" . GetParam("ipupd")  . "'," .
                "'" . GetParam("llogin")  . "'," .
                "'" . GetParam("sparam")  . "'," .
                "'" . GetParam("sesid")  . "'," .
                "'" . GetParam("seclevid") ;
		   }
		   else {
		       $sSQL .= "," . $seclevid ;//only security automated (predefined customer)
		   }

	     $sSQL .= ")";
         //echo $sSQL;
         $ret = $db->Execute($sSQL);	 //print_r($ret);

         if ($ret = $db->Affected_Rows()) {
		   //map procedure cntinue after user registration
		   if (($this->check_existing_customer) && ($this->map_customer===true)) {
		     //echo 'user code:',$user_code;
		     $map = GetGlobal('controller')->calldpc_method('shcustomers.map_customer use '.$user_code.'+'.$this->customer_exist_id);
		   }
		 
		   SetGlobal('sFormErr',"ok");
		   $this->after_insert_task($user_code,GetParam("pwd"),GetParam("fname"),GetParam("lname"));//send code to customer	   
		 }
	     else {
		   //rollback
		   //delete inserted customer.....
		   if ((!$this->check_existing_customer) && ($this->map_customer===null)) //if NOT map procedure
		     $rollback = GetGlobal('controller')->calldpc_method('shcustomers.subdelete use '.$user_code);
		 
		   $ret = $db->ErrorMsg();
		   //echo $ret;
		   SetGlobal('sFormErr',localize('_MSG20',getlocal()));
		 }
	   }	
	   }//if customer inserted 
	       
	}
	
	function update_user_code($c,$codef=null) {
	   $db = GetGlobal('db');	
	   $currentuser = decode($UserName);	   
	
	   $code = $codef?$codef:$this->leeid;
       $sSQL = "UPDATE users set $code=" . $c;
       $sSQL .= " WHERE USERNAME ='" . $currentuser . "'";
	   
       $db->Execute($sSQL);
       if($db->Affected_Rows()) return (true);
	                       else return (false);	   	    	
	}

	function update($id=null) {
	   $db = GetGlobal('db');
	   $UserName = GetGlobal('UserName');
	   $sFormErr = GetGlobal('sFormErr');
	   //print_r($_POST);
       $rec = $id?$id:GetParam('rec');
	   $a = GetReq('a');
	   $g = GetReq('g');
	   $currentuser = decode($UserName);


       $sSQL = "UPDATE users set " .
                "FNAME='" . GetParam("fname")  . "'," .
			    "LNAME='" . GetParam("lname")  . "'," .			
                "PASSWORD='" . GetParam("pwd")  . "'," .
                "VPASS='" . GetParam("pwd2")  . "',";
				
       if ($this->usemailasusername) {
                //$sSQL .= "USERNAME='" . GetParam("uname")  . "',";//foreign key to customers
                $sSQL .= "EMAIL='" . GetParam("uname")  . "'"; 				
	   }		
	   else {
                //$sSQL .= "USERNAME='" . GetParam("uname")  . "',";//foreign key to customers	   
                $sSQL .= "EMAIL='" . GetParam("eml")  . "'"; 
	   }			
		
	   $subscribe = GetParam('autosub')?1:0;				
	   
	   if (!GetReq('editmode')) { 
	   $sSQL .= "," ;						
       $sSQL .= "CNTRYID=" . GetParam("country_id")  . "," .
                "LANID=" . GetParam("language_id")  . "," .
            	"AGEID=" . GetParam("age")  . "," .
            	"GENID=" . GetParam("gender") . "," .
            	"TIMEZONE=" . $db->qstr(GetParam("timezone")) . "," .	
				"SUBSCRIBE=" . $subscribe . "," .	 			
				"CLOGON=0";
        }
        if ((seclevel('USERSMNG_',$this->userLevelID)) || (!GetReq('editmode'))) {
		    //not these data....
 	  	    /*$sSQL .= "," .
           	    "NOTES='" . GetParam("notes")  . "'," .
                //"STARTDATE=" . GetParam("dcreate")  . "," .
                "IPINS='" . GetParam("ipins")  . "'," .
                "IPUPD='" . GetParam("ipupd")  . "'," .
                //"LASTLOGON=" . GetParam("llogin")  . "," .
                //"SECPARAM=" . GetParam("sparam")  . "," .
                "SESID='" . GetParam("sesid")  . "'," .
                //"SECLEVID=" . GetParam("seclevid") ;
				*/
         }
         
		 if ($rec) {
		   $sSQL .= " WHERE ID =" . $rec;		 
		 }
		 elseif ($a) {
			$sSQL .= " WHERE CODE2 ='" . $a . "' AND USERNAME='" . $g . "'";		 
		 }  
	     else 
		   $sSQL .= " WHERE USERNAME ='" . $currentuser . "'";


	     //echo $sSQL;

         $db->Execute($sSQL,1);
         if($db->Affected_Rows()) SetGlobal('sFormErr',"ok");
	                         else SetGlobal('sFormErr',localize('_MSG18',getlocal()));
	}

	function _delete($id=null,$fkey=null) {
	   $db = GetGlobal('db');
	   $UserID = GetGlobal('UserID');
	   $sFormErr = GetGlobal('sFormErr');	;
	   $myfkey = $fkey?$fkey:'code2';

	   $a = GetReq('a');
	   $g = GetReq('g');

	   if (!$a) $currentuserID = decode($UserID);
	       else $currentuserID = $a;

	   $myrec = $id?$id:$currentuserID;

       if (seclevel('USERSMNG_',$this->userLevelID)) {

         //exclude record 1=admin
		 if (GetReq('editmode')) {
		 	$sSQL = "delete from users where id=" . GetReq('rec');
		 }
         elseif ($g!='admin') {
		   //$sSQL = "DELETE from users WHERE user_id =" . $db->qstr($currentuserID) ;

		   //virtual delete
           $sSQL = "UPDATE users set " .
                   "NOTES='DELETED'";//  .
				   //"WHERE LEEID='" . $currentuserID . "'" ;
     
	       if (!$a)
		      $sSQL .= " WHERE $myfkey =" . $myrec . "'";
		    else  		   
			  $sSQL .= " WHERE $myfkey =" . $myrec . " AND USERNAME='" . $g . "'";

		 }
		 //echo $sSQL;		 
         $db->Execute($sSQL);
         if($db->Affected_Rows()) SetGlobal('sFormErr',"ok");
	                         else SetGlobal('sFormErr',localize('_MSG18',getlocal()));		 
	   }
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
		       //$smtpm->to = $to;
			   //$smtpm->from = $from;
			   //$smtpm->subject = $subject;
			   //$smtpm->body = $body ;
			   
		       $smtpm->to($to); 
		       $smtpm->from($from); 
		       $smtpm->subject($subject);
		       $smtpm->body($body);			   

			   $mailerror = $smtpm->smtpsend();

			   unset($smtpm);
			 }
		}
			 
	}

    function reset_sessions() {
	  $db = GetGlobal('db');

	  $this->msg = null;

	  //delete table
	  $sSQL0 = "drop table if exists sessions";
	  $result0 = $db->Execute($sSQL0);

	  if ($result0) $this->msg = "Drop table ...\n";
	  //create table
	  $sSQL1 = "create table sessions
                (
	              username varchar(60) primary key,
	              session text,

	              UNIQUE (username)
                )";
	  $result1 = $db->Execute($sSQL1);
	  if ($result1) $this->msg .= "Create table ...\n";

	  setInfo($this->msg);

	  return ($this->msg);
	}

   /////////////////////////////////////////////////////////////////
   // generate user selection list
   /////////////////////////////////////////////////////////////////
   function selectUser($select=0) {

     $levels = explode(",",paramload('SHUSERS','groups'));

     if ($levels) {
       reset ($levels);
       //asort ($levels);

       $toprint .= "<select name=\"userlevel\">\n";//<OPTION value=\"1\">ALL</OPTION>\n";

       foreach ($levels as $lan_num => $lan_descr) {

	     //not display users above this user
	     if ($lan_num<=$this->userLevelID) {

	       //is selected ?
		   if ($lan_num==$select) $issel = 'selected';
		                     else $issel = '';
		   //have description
           if ($lan_descr!='')
		     $toprint .= "<OPTION value=\"$lan_num\" $issel>$lan_descr</OPTION>\n";
		 }
       }

	   $toprint .= "\n</select>";
     }

     return ($toprint);
   }

    //- function returns options for HMTL control "<select>" as one string
    function get_options($sql,$is_search,$is_required,$selected_value) {
	    $db = GetGlobal('db');

        $options_str="";

        if ($is_search)
          $options_str.="<option value=\"\">All</option>";
        else  {
          if (!$is_required) {
            $options_str.="<option value=\"\"></option>";
          }
         }

         $result = $db->Execute($sql,3);

         if ($result) {
           while (!$result->EOF)  {

             $id=$result->fields[0];
             $value=$result->fields[1];
             $selected="";
             if ($id == $selected_value) {
               $selected = "SELECTED";
             }
             $options_str.= "<option value='".$id."' ".$selected.">".$value."</option>";

	         $result->MoveNext();
           }
         }

         return $options_str;
    }
	
	function get_cus_type($id,$field='username',$istext=1) {
        $db = GetGlobal('db');
		$mycode = $field;

	    $sSQL = "select attr1,username from customers,users where $mycode=";
		
		switch ($istext) {
		  case 1 : $sSQL .= $db->qstr($id); break;
		  case 0 :
		  default: $sSQL .= $id;
		}
		
		$sSQL .= " and customers.code2=users.code2";
		//echo $sSQL;
		$ret = $db->Execute($sSQL,2);
		
		return ($ret->fields[0]);		
	}	
	
	function get_cus_name() {
        $db = GetGlobal('db');
		$user = decode(GetGlobal('UserID'));

	    $sSQL = "select name,username from customers,users where users.code2=" . $db->qstr($user);
		$sSQL .= " and active=1 and customers.code2=users.code2";
		//echo $sSQL;
		$res = $db->Execute($sSQL,2);
		
		//incase of no mapped customer get username
		$name = $res->fields['name']?$res->fields['name']:$user;
		
		$nk = seturl('t=signup');//addnewcus&select=1');
		$ret = "<a href='$nk'>" . $name . "</a>";
		//echo '>',$ret;
		return ($ret);		
	}
	
	function get_user_name($prefix=null,$edituser=null) {
        $db = GetGlobal('db');
		$user = decode(GetGlobal('UserID'));
		
		if (!$user) return;

	    /*$sSQL = "select name,username from customers,users where users.code2=" . $db->qstr($user);
		$sSQL .= " and active=1 and customers.code2=users.code2";
		//echo $sSQL;
		$res = $db->Execute($sSQL,2);*/
		
		//incase of no mapped customer get username
		$name = $res->fields['name']?$res->fields['name']:$user;
		
		
		if ($prefix) {
		  if ($edituser) {
		    $nk = seturl('t=signup');
		    $ret = $prefix . "<a href='$nk'>" . '&nbsp;'. $name . "</a>";
	      }
		  else
		    $ret = $prefix . $name;
		}  
		else {
		  if ($edituser) {
		    $nk = seturl('t=signup');		
		    $ret = "<a href='$nk'>" . $name . "</a>";
	      }
		  else
		    $ret = $name;			
		}  
		//echo '>',$ret;
		return ($ret);		
	}					
	
	function get_user_timezone($c=null, $codef=null) {
	   $db = GetGlobal('db');	
	   $currentuser = $this->username;	   
	
	   $code = $codef?$codef:$this->leeid;
       $sSQL = "select timezone from users";
	   if ($c)
	    $sSQL .= " WHERE " . $code."=" . $c;
	   else	
        $sSQL .= " WHERE USERNAME ='" . $currentuser . "'";
	   
       $result = $db->Execute($sSQL);
       //if($db->Affected_Rows()) return (true);
	     //                  else return (false);	 	
	   $timezone_descr = $result->fields['timezone'];
	   $tmzid = $this->create_timezone_id($timezone_descr);
	   
	   return ($tmzid);	//+- hours 
	}
	
	function set_user_timezone($tmz, $c=null, $codef=null) {
	   $db = GetGlobal('db');	
	   $currentuser = $this->username;	   
	
	   $code = $codef?$codef:$this->leeid;
       $sSQL = "update users set timezone='$tmz'";
	   if ($c)
	    $sSQL .= " WHERE " . $code."=" . $c;
	   else	
        $sSQL .= " WHERE USERNAME ='" . $currentuser . "'";
	   
       $db->Execute($sSQL);
       if($db->Affected_Rows()) return (true);
	                       else return (false);	 		 
	}
	
	function  create_timezone_id($timezone=null) {
	
	   if (!$timezone) return 0;
	
	   $p = explode(' ',$timezone);
	   if (stristr($p[0],':')) {
	   
	     if (stristr($p[0],'+')) {
	       $t = explode('+',$p[0]);
		   $ret = floatval(str_replace(':','.',$t[1]));
		   //echo '+++',$ret;		   
		 }  
		 elseif (stristr($p[0],'-')) {  
		   $t = explode('-',$p[0]);
		   $ret = (floatval(str_replace(':','.',$t[1])) * -1);
		   //echo '---',$ret;
		 }  
		 else 
		   $ret = 0;//...  
	   }
	   else
	     $ret = 0; //gmt time
		 
	   return ($ret);	 
	}	
	
	function get_user_country($c=null, $codef=null) {
	   $db = GetGlobal('db');	
	   $currentuser = $this->username;	   
	
	   $code = $codef?$codef:$this->leeid;
       $sSQL = "select cntryid from users";
	   if ($c)
	    $sSQL .= " WHERE " . $code."=" . $c;
	   else	
        $sSQL .= " WHERE USERNAME ='" . $currentuser . "'";
	   //echo $sSQL;
       $result = $db->Execute($sSQL);	 	
	   $country_id = $result->fields['cntryid'];
	   
	   return ($country_id);	 
	}
	
	function set_user_country($cntryid, $c=null, $codef=null) {
	   $db = GetGlobal('db');	
	   $currentuser = $this->username;	   
	
	   $code = $codef?$codef:$this->leeid;
       $sSQL = "update users set cntryid='$cntryid'";
	   if ($c)
	    $sSQL .= " WHERE " . $code."=" . $c;
	   else	
        $sSQL .= " WHERE USERNAME ='" . $currentuser . "'";
	   
       $db->Execute($sSQL);
       if($db->Affected_Rows()) return (true);
	                       else return (false);	 		 
	}	
	
	function username_exist($myusername=null) {
       $db = GetGlobal('db');	
	
	   //ask if username exist
	   $sSQL1 = "select USERNAME from users where USERNAME='" . $myusername/*trim(GetParam("uname"))*/ . "'";
       //echo $sSQL1;

       $res1 = $db->Execute($sSQL1,3);
	   //print_r($res1->fields);
	   $existed_username = trim($res1->fields[0]);	
	   
	   return ($existed_username);
	}	
	
	function user_exists($username=null) {
	   $db = GetGlobal('db');	
	   if (!$username) return false;
	  
       $sSQL = "select username from users";
	   $sSQL .= " WHERE username=" . $db->qstr($username);
	   
       $result = $db->Execute($sSQL,2);
       //if($db->Affected_Rows()) return (true);
	     //                  else return (false);
	   //echo $sSQL;	 	 
	   //print_r($result->fields);	   	
	   $res = $result->fields['username'];	  
	   
	   $ret = $res?true:false;
	   return ($ret); 
	}
	
	
	function combine_tokens($template_contents,$tokens) {
	
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
	}		

	function free() {
	}
};
}
?>