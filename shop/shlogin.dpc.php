<?php
$__DPCSEC['SHLOGIN_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("SHLOGIN_DPC")) && (seclevel('SHLOGIN_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHLOGIN_DPC",true);

$__DPC['SHLOGIN_DPC'] = 'shlogin';

$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($a);

$__EVENTS['SHLOGIN_DPC'][0]='shlogin';
$__EVENTS['SHLOGIN_DPC'][1]='dologin';
$__EVENTS['SHLOGIN_DPC'][2]='dologout';
$__EVENTS['SHLOGIN_DPC'][3]='rempwd';
$__EVENTS['SHLOGIN_DPC'][4]='shremember';
$__EVENTS['SHLOGIN_DPC'][5]='shcaptcha';

$__ACTIONS['SHLOGIN_DPC'][0]='shlogin';
$__ACTIONS['SHLOGIN_DPC'][1]='dologin';
$__ACTIONS['SHLOGIN_DPC'][2]='dologout';
$__ACTIONS['SHLOGIN_DPC'][3]='rempwd';
$__ACTIONS['SHLOGIN_DPC'][4]='shremember';
$__ACTIONS['SHLOGIN_DPC'][5]='shcaptcha';

$__DPCATTR['SHLOGIN_DPC']['shlogin'] = 'shlogin,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['SHLOGIN_DPC'][0]='SHLOGIN_DPC;Login;Εισαγωγή';
$__LOCALE['SHLOGIN_DPC'][1]='_SHLOGOUT;Logout;Αποσύνδεση';
$__LOCALE['SHLOGIN_DPC'][2]='SHLOGIN_CNF;Logout;Αποσύνδεση';
$__LOCALE['SHLOGIN_DPC'][3]='SHLOGIN_UNK;Login;Εισαγωγή';
$__LOCALE['SHLOGIN_DPC'][4]='_USERNAME;Username;Χρήστης';
$__LOCALE['SHLOGIN_DPC'][5]='_PASSWORD;Password;Κωδικός';
$__LOCALE['SHLOGIN_DPC'][6]='_WELLCOME;Wellcome;Καλωσήρθες';
$__LOCALE['SHLOGIN_DPC'][7]='_SEEYOU;See you next time;Τα λέμε';
$__LOCALE['SHLOGIN_DPC'][8]='_MSG1;Incorect data!;Λάθος στοιχεία!';
$__LOCALE['SHLOGIN_DPC'][9]='_VERPASS;Verify password;Επαληθευση κωδικου';
$__LOCALE['SHLOGIN_DPC'][10]='_PASSREMINDER;Please change your password!;Παρακαλω αλλαξτε τον κωδικό σας!';
$__LOCALE['SHLOGIN_DPC'][11]='_VERPASSUCCESS;Password changed!;Επιτυχης αλλαγη κωδικου';
$__LOCALE['SHLOGIN_DPC'][12]='_HERE;here;εδώ';
$__LOCALE['SHLOGIN_DPC'][13]="_IFORGET;If you dont remember your password click ;Αν δεν θυμαστε τον κωδικο σας πατηστε";
$__LOCALE['SHLOGIN_DPC'][14]='_PRESSHERE;Click here;Πατήστε εδώ';
$__LOCALE['SHLOGIN_DPC'][15]='_MSG2;Username and Password send at your mail account!;Το όνομα χρήστη και ο κωδικός στάλθηκαν στο ηλεκτρονικό σας ταχυδρομείο!';
$__LOCALE['SHLOGIN_DPC'][16]='_SENDCRE;Username and Password send at your mail account!;Το όνομα χρήστη και ο κωδικός στάλθηκαν στο ηλεκτρονικό σας ταχυδρομείο!';
$__LOCALE['SHLOGIN_DPC'][17]='_UMAILREMSUBC;Account reminder;Υπενθύμιση στοιχειων χρήστη';
$__LOCALE['SHLOGIN_DPC'][18]='_OK;Success;Επιτυχης εργασια';
$__LOCALE['SHLOGIN_DPC'][19]='_OKREMINDER;Your account details has been send by e-mail.;Τα στοιχεία του λογαριασμού σας σταλθηκσν με e-mail.';

//cpmdbrec commands
$__LOCALE['SHLOGIN_DPC'][80]='_exit;Exit;Έξοδος';
$__LOCALE['SHLOGIN_DPC'][81]='_dashboard;Dashboard;Πίνακας ελέγχου';
$__LOCALE['SHLOGIN_DPC'][82]='_logout;Logout;Αποσύνδεση';
$__LOCALE['SHLOGIN_DPC'][83]='_rssfeeds;RSS Feeds;RSS Feeds';
$__LOCALE['SHLOGIN_DPC'][84]='_edititemtext;Edit Item Text;Μεταβολή κειμένου';// (text) αντικειμένου';
$__LOCALE['SHLOGIN_DPC'][85]='_deleteitemattachment;Delete Item Attachment;Διαγραφή συνημμένου';// είδους';
$__LOCALE['SHLOGIN_DPC'][90]='_editcat;Edit Category;Μεταβολή κατηγορίας';
$__LOCALE['SHLOGIN_DPC'][91]='_addcat;Add Category;Νέα Κατηγορία';
$__LOCALE['SHLOGIN_DPC'][92]='_additem;Add Item;Νέο Είδος';
$__LOCALE['SHLOGIN_DPC'][93]='_webstatistics;Statistics;Στατιστικά';
$__LOCALE['SHLOGIN_DPC'][94]='_addcathtml;Add Category Text;Προσθήκη κειμένου';// (html) κατηγορίας';
$__LOCALE['SHLOGIN_DPC'][95]='_editcathtml;Edit Category Text;Μεταβολή κειμένου';// (html) κατηγορίας';
$__LOCALE['SHLOGIN_DPC'][96]='_edititem;Edit Item;Μεταβολή αντικειμένου';
$__LOCALE['SHLOGIN_DPC'][97]='_edititemphoto;Edit Photo;Μεταβολή φωτογραφίας';// αντικειμένου';
$__LOCALE['SHLOGIN_DPC'][98]='_edititemdbhtm;Edit Item;Μεταβολή κειμένου';// (htm) αντικειμένου (db)';
$__LOCALE['SHLOGIN_DPC'][99]='_edititemdbhtml;Edit Item;Μεταβολή κειμένου';// (html) αντικειμένου (db)';
$__LOCALE['SHLOGIN_DPC'][100]='_edititemdbtext;Edit Item;Μεταβολή κειμένου';// (text) αντικειμένου (db)';
$__LOCALE['SHLOGIN_DPC'][101]='_senditemmail;Send e-mail;Αποστολή e-mail';
$__LOCALE['SHLOGIN_DPC'][102]='_delitemattachment;Delete Text;Διαγραφή κειμένου';// (db)';
$__LOCALE['SHLOGIN_DPC'][103]='_edititemtext;Edit Item Text;Μεταβολή κειμένου';// (text) αντικειμένου';
$__LOCALE['SHLOGIN_DPC'][104]='_edititemhtm;Edit Item Htm;Μεταβολή κειμένου';// (htm) αντικειμένου';
$__LOCALE['SHLOGIN_DPC'][105]='_edititemhtml;Edit Item Html;Μεταβολή κειμένου';// (html) αντικειμένου';
$__LOCALE['SHLOGIN_DPC'][106]='_additemhtml;Add Item Html;Εισαγωγή κειμένου';// στο αντικείμενο';
$__LOCALE['SHLOGIN_DPC'][107]='_transactions;Transactions;Συναλλαγές';
$__LOCALE['SHLOGIN_DPC'][108]='_users;Users;Χρήστες';
$__LOCALE['SHLOGIN_DPC'][109]='_itemattachments2db;Add Item(s) text to database;Μεταφορά κειμένων στην Β.Δ.';//βάση δεδομένων';
$__LOCALE['SHLOGIN_DPC'][110]='_importdb;Import Database;Εισαγωγή βάσης δεδομένων';
$__LOCALE['SHLOGIN_DPC'][111]='_config;Configuration;Ρυθμίσεις';
$__LOCALE['SHLOGIN_DPC'][112]='_contactform;Contact Form;Φόρμα επικοινωνίας';
$__LOCALE['SHLOGIN_DPC'][113]='_subscribers;Subscribers;Συνδρομητές';
$__LOCALE['SHLOGIN_DPC'][114]='_sitemap;Sitemap;Χάρτης πλοήγησης';// αντικειμένων';
$__LOCALE['SHLOGIN_DPC'][115]='_search;Search;Φόρμα Αναζήτησης';
$__LOCALE['SHLOGIN_DPC'][116]='_upload;Upload files;Ανέβασμα αρχείων';
$__LOCALE['SHLOGIN_DPC'][117]='_uploadid;Upload item files;Ανέβασμα αρχείων';// αντικειμένου';
$__LOCALE['SHLOGIN_DPC'][118]='_uploadcat;Upload category files;Ανέβασμα αρχείων';// κατηγορίας';
$__LOCALE['SHLOGIN_DPC'][119]='_syncphoto;Sync photos;Συγχρονισμός φωτογραφιών';
$__LOCALE['SHLOGIN_DPC'][120]='_syncsql;Sync data;Συγχρονισμός δεδομένων';
$__LOCALE['SHLOGIN_DPC'][121]='_dbphoto;Image in DB;Εικόνα στην βάση δεδομένων';
$__LOCALE['SHLOGIN_DPC'][122]='_editctag;Category Tags;Tags κατηγορίας';
$__LOCALE['SHLOGIN_DPC'][123]='_edititag;Item Tags;Tags είδους';
$__LOCALE['SHLOGIN_DPC'][124]='_menu;Menu;Επιλογές Menu';
$__LOCALE['SHLOGIN_DPC'][125]='_slideshow;Slideshow;Επιλογές Slideshow';

class shlogin {

	var $userLevelID;
	var $msg;
	var $outpoint;
	var $sslscript;
	var $ses_prothema;
	var $ses_path;
	var $ssl;
    var $time_of_session;
	var $reseller_attr, $path;
	var $username, $userid;
    var $actcode;
    var $iname, $load_sesssion;
    var $after_goto, $dpc_after_goto,$login_successfull;
	var $login_goto,$logout_goto;  
	var $urlpath, $inpath;
	var $recaptcha_public_key, $recaptcha_private_key;	

	function shlogin() {
	   $sFormErr = GetGlobal('sFormErr');
	   $UserName = GetGlobal('UserName');
	   $UserSecID = GetGlobal('UserSecID');
	   $GRX = GetGlobal('GRX');
	   $this->username = decode($UserName);
	   $this->userid = decode($UserID);

       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	   $this->msg = $sFormErr;
	   $this->ssl = paramload('SHELL','ssl');
	   $this->outpoint = "|";

	   if ($remoteuser=GetSessionParam('REMOTELOGIN'))
	     $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";
	   else
	     $this->path = paramload('SHELL','prpath');
		 
	   $this->urlpath = paramload('SHELL','urlpath');
	   $this->inpath = paramload('ID','hostinpath');			 

	   $this->sslscript = "<script src=\"".paramload('SHLOGIN','sslscript')."\"></script>";
	   $this->ses_prothema = paramload('SHLOGIN','sespro');
	   $this->ses_path = paramload('SHELL','sespath');

       $this->must_reenter_password	= null;

	   $this->link = seturl("t=rempwd",localize("_HERE",getlocal()));
	   $this->message = localize("_IFORGET",getlocal());
	   $this->formerror = null;

	   $this->title = localize('SHLOGIN_DPC',getlocal());
       $logintime = remote_paramload('SHLOGIN','logintime',$this->path);
	   $this->time_of_session = $logintime?$logintime:3600;//1 hour is default
	   
	   $this->reseller_attr = remote_paramload('SHLOGIN','reseller',$this->path); //DISABLED
	   
	   $this->accountmailfrom = remote_paramload('SHLOGIN','accountmailfrom',$this->path);

	   $acode = remote_paramload('RCCUSTOMERS','activecode',$this->path);
	   $this->actcode = $acode?$acode:'code2';

	   $this->iname = paramload('ID','instancename');
	   $this->load_session = remote_paramload('SHLOGIN','loadsession',$this->path);

	   $this->after_goto = remote_paramload('SHLOGIN','aftergoto',$this->path);
	   $this->dpc_after_goto = GetSessionParam('afterlogingoto')?GetSessionParam('afterlogingoto'):$this->after_goto;
	   $this->login_successfull = false;
	   
	   $this->logout_goto = remote_paramload('SHLOGIN','logout_goto',$this->path);
	   $this->login_goto = remote_paramload('SHLOGIN','login_goto',$this->path);	
	   
	   $this->recaptcha_public_key = remote_paramload('RECAPTCHA','pubkey',$this->path);							  
	   $this->recaptcha_private_key = remote_paramload('RECAPTCHA','privkey',$this->path);	      
	}

    function event($sAction) {
	   $__USERAGENT = GetGlobal('__USERAGENT');
	   $UserName = GetGlobal('UserName');
	   $param1 = GetGlobal('param1');
	   $param2 = GetGlobal('param2');
	   

       if (!$this->msg) {

         switch($sAction)   {
		    case 'rempwd'       : break;
		    case 'shremember'  : $this->do_the_job(); break;
		    case 'shcaptcha'   : $this->do_the_captcha(); break;
            case 'shlogin'     : break;

            case "dologin":  
			               switch ($__USERAGENT) {
	                          case 'HTML' :  $this->login_successfull = $this->do_login();
                                             if (($this->login_goto)&& ($this->login_successfull)) {
							                    if (!$this->dpc_after_goto)// inside code command
			                                      $this->refresh_page_js($this->login_goto);	
											 }	
																		  
							                 if (defined('UONLINE_DPC'))
											   GetGlobal('controller')->calldpc_method('uonline.isOnline');
											 break;
	                          case 'XML'  :
	                          case 'GTK'  :
							  case 'XUL'  :
		                      case 'CLI'  :
	                          case 'TEXT' :  $this->do_login($param1,$param2);
							                 break;
						   }
						   //reset database connection
						   $db = null;
                           break;

            case "dologout":  
			                switch ($__USERAGENT) {
	                          case 'HTML' : if (defined('UONLINE_DPC'))
    	                                      GetGlobal('controller')->calldpc_method('uonline.isOffline');
											  
                                            $this->do_logout();
											
                                            if ($this->logout_goto)
			                                  $this->refresh_page_js($this->logout_goto); 											
                                            break;
	                          case 'XML'  :
	                          case 'GTK'  :
							  case 'XUL'  :
		                      case 'CLI'  :
	                          case 'TEXT' : $this->do_logout();
							                break;
						   }
						   //reset database connection
						   //$db = null;
                           break;

			 case "chpass" : $this->do_reenter_pasword(); break;
          }
       }
	}

    function action($action=null)  {
	    $__USERAGENT = GetGlobal('__USERAGENT');
	    $sFormErr = GetGlobal('sFormErr');

        switch($action)   {

		    case 'rempwd'       :
		    case 'shremember'  : $out = $this->title();
			                     if ($this->formerror!="ok") {//when send NOT ok goto rem form
								   //$tmpl = 'remlogin.htm';
								   /*$toks = $this->remform(1);
								   $mydata = str_replace('+','<@>',implode('<TOKENS>',$toks));
								   if ($form = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use remlogin.htm+".$mydata)) 
								     $out .= $form;
								   else	 
								     $out .= $this->remform();*/
									
								   $out .= $this->html_remform();	 
								 }	 
								 else {//login
								   //$tmpl = 'login.htm'; 
								   $out = $this->html_form();
								 }  
								    
								   
			                     /*$t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$tmpl) ; 
						         //echo $t;
	                             if (is_readable($t)) {
		                            $mytemplate = file_get_contents($t);
							        $tokens = $this->remform(1);
							        //print_r($tokens);
		                            $out .= $this->combine_tokens($mytemplate,$tokens);
	                             }
						         else
			                       $out .= $this->remform();*/ 
								 break;
		    case 'shcaptcha'   : $out .= $this->show_the_captcha(); break;
            case "shlogin": $out = $this->form(); break;

            case "dologin":
               //goto after login with ses param
               if (($this->dpc_after_goto) && ($this->login_successfull)) {
                 //echo $this->dpc_after_goto,'>';
			     $mydpc = explode('.',$this->dpc_after_goto);//check security
			     $mydpcname = strtoupper($mydpc[0]).'_DPC';				 
				 if (seclevel($mydpcname,$this->userLevelID)) 
                   $out .= GetGlobal('controller')->calldpc_method($this->dpc_after_goto);
				 else
				   $out .= $this->form();//default action  
                 $this->dpc_after_goto = null;
                 SetSessionParam('afterlogingoto','');
               }
               else {
               switch ($__USERAGENT) {
	             case 'HTML' : $out = $this->form(); break;
	             case 'XML'  : $out = $this->form('XML'); break;
			     case 'XUL'  :
	             case 'GTK'  : $out = $this->form('GTK'); break;
		         case 'CLI'  :
	             case 'TEXT' : $out = (IsSet($sFormErr) ? $sFormErr : "Ok"); break;
	           }
	           }
		       break;

            case "dologout": $out = $this->form(); break;
		    case "chpass" :
               switch ($__USERAGENT) {
	             case 'HTML' : $out = $this->form2(); break;
	             /*case 'XML'  : $out = $this->form('XML'); break;
			     case 'XUL'  :
	             case 'GTK'  : $out = $this->form('GTK'); break;
		         case 'CLI'  :
	             case 'TEXT' : $out = (IsSet($sFormErr) ? $sFormErr : "Ok"); break;	*/
	           }
		       break;
		}

		return ($out);
	}
	
   function refresh_page_js($goto) {
   
      if (iniload('JAVASCRIPT')) {

	       $code = $this->javascript($goto);
	   
		   $js = new jscript;
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }   
   } 
   
   //after login/logout goto...
   function javascript($goto) {
   
     $ret = "
function neu()
{	
	top.frames.location.href = \"$goto\"
}
window.setTimeout(\"neu()\",10);
";
	 
	 return ($ret);
   }   	

	function title() {
	   $UserID = GetGlobal('UserID');

       $uid = decode($UserID);

       //navigation status
       if ($uid == "")
  	     $out = setNavigator(localize('SHLOGIN_DPC',getlocal()));
	   else
         $out = setNavigator(localize('_SHLOGOUT',getlocal()));

	   return ($out);
	}

	function html_form($tokensout=null) {
	     $sFormErr = GetGlobal('sFormErr');
	     $UserID = GetGlobal('UserID');
         $logonurl = seturl("t=dologin",0,1);	
		 
		 if ($UserID) {
		 
           $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.','logout.htm') ; 
		   //echo $t;
	       if (is_readable($t)) {
		     $mytemplate = file_get_contents($t);
		     $tokensout = 1;
           }	
		   
           if ($tokensout) {
		     $tokens[] = seturl("t=dologout"); //link
			 $tokens[] = localize('_SHLOGOUT',getlocal());
			 $ret = $this->combine_tokens($mytemplate,$tokens);

		   }
		   else		   		 
		     $ret = '<h2><p align=center>' . seturl("t=dologout",localize('_SHLOGOUT',getlocal())) . '</p></h2>';
			 	
		   return ($ret);
		 }
		 
		 //NOT FOR PASSWORDS FORM
	     /*if (defined('RECAPTCHA_DPC')) {
	         $recaptcha = recaptcha_get_html($this->recaptcha_public_key, $this->recaptcha_private_key);	   
			 //echo $recaptcha;
	     }	*/		 
		 
         $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.','login.htm') ; 
		 //echo $t;
	     if (is_readable($t)) {
		   $mytemplate = file_get_contents($t);
		   $tokensout = 1;
         }		 	 

	     //SSL tm
	     if ($this->ssl) {
		                      //$loginform .= $this->sslscript;
	                          $sslwin = new window("",$this->sslscript);
	                          $loginform .= $sslwin->render("center::100%::0::group_article_selected::right::0::0::");
	                          unset ($sslwin);
	     }

         if ($tokensout) {
		    if (($sFormErr=='ok')||($sFormErr=='Ok')||($sFormErr=='OK')||($sFormErr=='OKREMINDER')) {//fix ok global msg
			  if (stristr($sFormErr,'ok'))
			    $tokens[] = localize('_OK', getlocal());
			  else
			    $tokens[] = localize('_OKREMINDER', getlocal());
			}  
			else
		      $tokens[] = $sFormErr;
		 }
		 else {	 
		 
		    $loginform .= $toprint = $this->title();
						   		 
		    if (($sFormErr=='ok')||($sFormErr=='Ok')||($sFormErr=='OK')||($sFormErr=='OKREMINDER')) {//fix ok global msg
			  if (stristr($sFormErr,'ok'))
			    $loginform .= localize('_OK', getlocal());
			  else
			    $loginform .= localize('_OKREMINDER', getlocal());			
			}
			else  //error message
	          $loginform .= setError($sFormErr);
		 }				   
						   //$loginform .= new captcha;
        
         if ($tokensout) {
		 
		   $tokens[] = "<form action=\"$logonurl\" method=\"POST\">" . 
		               "<input type=\"text\" name=\"Username\" maxlenght=\"20\">" ;
		 }
		 else { 
                           //login form
                           $loginform .= "<form action=\"$logonurl\" method=\"POST\">";
						   		 
                           $lf1[] = localize('_USERNAME',getlocal());
		                   $lfat1[] = "right;20%;";
		                   $lf1[] = "&nbsp;<input type=\"text\" name=\"Username\" maxlenght=\"20\">";
		                   $lfat1[] = "left;80%";
		                   $w1 = new window('',$lf1,$lfat1);  $loginform .= $w1->render("center::100%::0::group_article_selected::left::0::0::");   unset ($w1);
         } 		 
		 
         if ($tokensout) {
		 		 
		 
		   $tokens[] = "<input type=\"password\" name=\"Password\" maxlenght=\"20\">"; 
		               			   
					   
		   $tokens[] = //$recaptcha . NOT FOR PASSWORDS FORM 
		               "<input type=\"submit\" value=\"" . localize('SHLOGIN_DPC',getlocal()) . "\">" .
		               "<input type=\"hidden\" name=\"FormName\" value=\"Login\">" .					   
		               "<input type=\"hidden\" name=\"FormAction\" value=\"dologin\">" .
		               "</form>";
		 }
		 else { 		 
                           $lf2[] = localize('_PASSWORD',getlocal());
		                   $lfat2[] = "right;20%";
		                   $lf2[] = "&nbsp;<input type=\"password\" name=\"Password\" maxlenght=\"20\">";
		                   $lfat2[] = "left;80%";
		                   $w2 = new window('',$lf2,$lfat2); $loginform .= $w2->render("center::100%::0::group_article_selected::left::0::0::"); unset ($w2);
						   
						   $loginform .= $recaptcha;
                           $loginform .= "<input type=\"hidden\" name=\"FormName\" value=\"Login\">";         
                           $loginform .= "<input type=\"hidden\" name=\"FormAction\" value=\"dologin\">";
                           $loginform .= "<input type=\"submit\" value=\"" . localize('SHLOGIN_DPC',getlocal()) . "\">";
                           $loginform .= "</form>";
		 } 				   

         if ($tokensout) {
		 
		   //i  forget my password....extension
		   $tokens[] = $this->iforgotmypassword();
		   
		   //return ($tokens);
		   $ret = $this->combine_tokens($mytemplate,$tokens);
		   return ($ret);
		 }
		 else {  		 
						   //i  forget my password....extension
						   $loginform .= $this->iforgotmypassword();

                           $data1[] = $loginform;
                           $attr1[] = "center";

	                       $swin = new window(localize('_SENLOGIN',getlocal()),$data1,$attr1);
	                       $toprint .= $swin->render("center::40%::0::group_win_body::left::0::0::");
	                       unset ($swin);

						   return ($toprint);
		 }				   
	}

    function form($agent='HTML') {
	   $sFormErr = GetGlobal('sFormErr');

       $uid = decode($UserID);

       $sFileName = seturl("t=dologin");
       $logonurl = seturl("",0,1); //seturl("t=&a=&g=",0,1);
       $logoffurl = seturl();
	   
	   //in case of preint form after login
       if ($this->login_successfull) {
	   
         $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.','welcome.htm') ; 
	     //echo $t;
	     if (is_readable($t)) {
		   $mytemplate = file_get_contents($t);
           return ($mytemplate);
         }
		 else		   
	       return (localize('_WELLCOME',getlocal()) .' '. decode(GetSessionParam('UserName')));
	   }	 

       //if ((!$this->userid) && (!$this->username)) {//!!!!!!!!!!!!!!!!!!???
	   if ($uid=="") {

	      switch ($agent) {
	         case 'HTML' : //template form
			               //$toprint = $this->title();
						   
			               $toprint .= $this->html_form();
		                   break;

		     case 'XML'  : $xml = new pxml();
			               $xml->addtag('TEXT',null,'/',"value=$sFormErr");
					       $xml->addtag('FORM',null,null,"action=".$xml->xstr($logonurl)."|method=POST|class=thin");
					       $xml->addtag('LABEL','FORM','/',"for=cmd|accesskey=C");
					       $xml->addtag('INPUT','FORM','/',"type=text|name=docomm|maxlength=255|value=|size=15|id=cmd");
					       $xml->addtag('INPUT','FORM','/',"type=submit|value=Ok");
					       $xml->addtag('HIDDEN','FORM','/',"name=FormAction|value=command");
					       $toprint = $xml->getxml();
					       unset($xml);
			               break;
			 case 'XUL'  :
		     case 'GTK'  : $xml = new pxml('XUL');
			               $xml->addtag('GTKDIALOG',null,null,'id=dialog1|label=Login|modal=true|winx=400|winy=150|border=10');
						   $xml->addtag('GTKLABEL','GTKDIALOG','/',"value=".$sFormErr);
						   $xml->addtag('GTKFORM','GTKDIALOG',null,null);
						   //$xml->addtag('description','GTKFORM','/',"value=".$sFormErr);
			               $xml->addtag('label','GTKFORM','/',"control=usr|value=".localize('_USERNAME',getlocal()));
					       $xml->addtag('textbox','GTKFORM','/',"id=Username|maxlength=64");
			               $xml->addtag('label','GTKFORM','/',"control=pwd|value=".localize('_PASSWORD',getlocal()));
					       $xml->addtag('textbox','GTKFORM','/',"id=Password|type=password|maxlength=64");
					       $xml->addtag('hidden','GTKFORM','/',"id=action|value=login");
					       $toprint = $xml->getxml();
					       unset($xml);
						   //die($toprint)
			               break;
		   }
        }
		else {

          //never in here ???????
		  if ($goto = GetPreSessionParam('afterlogingoto')) {
			 $mydpc = explode('.',$goto);//check security
			 $mydpcname = strtoupper($mydpc[0]).'_DPC';				 
			 if (seclevel($mydpcname,$this->userLevelID)) 	  
		       $toprint .= GetGlobal('controller')->calldpc_method($goto);
		  }
		}


        return ($toprint);
    }

    function quickform($attr=null,$after_goto=null,$dpc_after_goto=null,$param_name=null,$param=null) {
       $winattr = $attr?$attr:"center::100%::0::group_win_body::left::0::0::";
	   $UserID = GetGlobal('UserID');
       $uid = decode($UserID);
	   
       $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.','qlogin.htm') ; 
	   //echo $t;
	   if (is_readable($t)) {
		 $mytemplate = file_get_contents($t);
		 $tokensout = 1;
       }	   

       $this->after_goto = $after_goto?$after_goto:null;

       //$this->dpc_after_goto = $dpc_after_goto?str_replace('>','.',$dpc_after_goto):null;
       if ($dpc_after_goto) {
       	  SetSessionParam('afterlogingoto',str_replace('>','.',$dpc_after_goto));
       }
        //echo $dpc_after_goto;

        if ($this->after_goto) {
               	  $logonurl = seturl("t=".$this->after_goto."&$param_name=".$param,0,1);
               	  $this->after_goto = null;
        }
        else
                  $logonurl = seturl("",0,1); //seturl("t=$a=&g=",0,1);
				  
       $logoffurl = seturl('t=dologout',localize('_SHLOGOUT',getlocal()));

	   if (isset($UserID)) {
	     if ($tokensout) {
		   return null;
		 }
		 else {
	       $myw = new window($logoffurl,null);
	       $ret = $myw->render("center::100%::0::group_win_body::left::0::0::");
	       unset ($myw);
		   return($ret);
	       //return($logoffurl);
		 }
	   }

       if ($tokensout) {
	   
	     $tokens[] = "<form action=\"$logonurl\" method=\"POST\">" .
		             "<input type=\"text\" name=\"Username\" maxlenght=\"50\" size=\"20\" >";
					 
		 $tokens[] = "<input type=\"password\" name=\"Password\" maxlenght=\"50\" size=\"20\" >" .
                     "<input type=\"hidden\" name=\"FormAction\" value=\"dologin\">" .
					 "<input type=\"hidden\" name=\"FormName\" value=\"Login\">";
					 
		 $tokens[] = "<input type=\"submit\" value=\"" . localize('SHLOGIN_DPC',getlocal()) . "\">" .
		             "</form>";
					 
		 $ret = $this->combine_tokens($mytemplate,$tokens);
		 return ($ret);					 
		             
	   }
	   else {
         $loginform = "<table>".
                    "<form action=\"$logonurl\" method=\"POST\">" .
                    "<tr><td>" .
                    localize('_USERNAME',getlocal()) . " :<br>" .
                    localize('_PASSWORD',getlocal()) . " :";

         if ( (defined('SHUSERS_DPC')) && (seclevel('SHUSERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
           $loginform .= '<br>'.seturl('t=signup',localize('_SIGNUP',getlocal()));//GetGlobal('controller')->calldpc_method('shcustomers.register');
	     }
         $loginform .= "</td><td>" .
                       "<input type=\"hidden\" name=\"FormName\" value=\"Login\"><br>" .
                       "<input type=\"text\" name=\"Username\" maxlenght=\"50\" size=\"20\" ><br>".
                       "<input type=\"password\" name=\"Password\" maxlenght=\"50\" size=\"20\" ><br>" .
                       "<input type=\"hidden\" name=\"FormAction\" value=\"dologin\">" .
                       "<input type=\"submit\" value=\"" . localize('SHLOGIN_DPC',getlocal()) . "\">" .
                       "</td></tr></form></table>";
					   
         $toprint .= $loginform;

	     $myw = new window($this->title,$toprint);
	     $out = $myw->render("$winattr");
	     unset ($myw);

         return ($out);
	   }	 
    }

    function do_login($user='',$pwd='',$editmode=null) {
	   $db = GetGlobal('db');
	   $sFormErr = GetGlobal('sFormErr');
	   $UserName = GetGlobal('UserName');
	   
	   //recaptcha NOT FOR PASSWORDS FORM 
       //if ($this->valid_recaptcha()) {		   
	   //if ($db) {   

       if (!$user) $sUsername = GetParam("Username", adText);
	          else $sUsername = $user;
       if (!$pwd) $sPassword = GetParam("Password", adText);
	         else $sPassword = $pwd;

	   if (($sUsername) && ($sPassword)) {

	      $sSQL = "SELECT ".$this->actcode.", sesid, notes, seclevid, clogon FROM users" . " WHERE username ='" .
		          addslashes($sUsername) . "' AND password='" . addslashes($sPassword) . "'";
				  
		  if ($editmode)
            $sSQL .= " and seclevid>=9";		  

		  //echo "login:",$sSQL;

          $result = $db->Execute($sSQL,2);
		  //print_r($result->fields);

          if (($result->fields[$this->actcode]) && (strcmp(trim($result->fields['notes']),"DELETED")!=0)) {
		  
		                     if ($editmode) {
		                       SetSessionParam('LOGIN','yes');	
		                       //SetSessionParam('USER',encode($sUsername));
		                       SetSessionParam('ADMIN','yes');	

                               return true;							   
							 }  

                             if ($this->load_session)
							  $this->loadSession($sUsername);

                              SetSessionParam("UserName", encode($sUsername));
							  SetSessionParam("Password", encode($sPassword));//!!!!!
                              SetSessionParam("UserID", encode($result->fields[$this->actcode]));
							  $GLOBALS['UserID']=encode($result->fields[$this->actcode]);
                              SetSessionParam("UserSecID", encode($result->fields['seclevid']));
							  
							  //$this->is_reseller(); //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
							  if ((defined('SHCUSTOMERS_DPC'))) {	  
	                              //GetGlobal('controller')->calldpc_var('shcustomers.reseller');							  
	                              GetGlobal('controller')->calldpc_method('shcustomers.is_reseller');	
	                          }	

			                  //re-enter password flag
			                  if ($result->fields['clogon']==1) {
							    $this->must_reenter_password=1;
								$chpass = seturl("t=chpass",localize('_PASSREMINDER',getlocal()),1,'',1);
								setInfo($chpass);
							  }
							  else
  		                        setInfo(localize('_WELLCOME',getlocal()) . " " . $sUsername);

							  //set cookie
							  if (paramload('SHELL','cookies')) {
							    setcookie("cuser",$UserName,time()+$this->time_of_session);//,time()+3600,"","panikidis.gr",0);
								setcookie("csess",session_id(),time()+$this->time_of_session);
							  }
							  //echo "login:",$sSQL;
							  return true;
           }
           else {
		                      //setInfo(localize('_MSG1',getlocal()));
                              //$sFormErr = localize('_MSG1',getlocal());
							  SetGlobal('sFormErr',localize('_MSG1',getlocal()));
							  return false;
           }
	   }
	   //}//db	   
	   //}//recaptcha
	   
	   return false;
	}

    function do_logout() {
      $UserName = GetGlobal('UserName');

      $un  = decode($UserName);
      $this->saveSession();
      //print "save session";

      setInfo(localize('_SEEYOU',getlocal()) . " $un ...");

	  //zero cookie
	  if (paramload('SHELL','cookies')) {
	    setcookie("cuser","");//,time()+3600,"",$this->iname,0);
		setcookie("csess","");
	  }
	}
	
	function getUserName() {
	  $UserName = GetGlobal('UserName');	
	  
	  $ret = decode($UserName);
	  
	  return ($ret);
	}

    function do_reenter_pasword() {
	   $db = GetGlobal('db');
	   $sFormErr = GetGlobal('sFormErr');
	   $UserName = GetGlobal('UserName');

	   $currentuser = decode($UserName);
	   $pwd = GetParam("Password");
	   $pwd2 = GetParam("vPassword");

	   if (($pwd!=null) && ($pwd2!=null)) {

	     if  ((strcmp($pwd,$pwd2)==0)) {

         $sSQL = "UPDATE users set " .
                  "password='" . $pwd  . "'," .
                  "vpass='" . $pwd2  . "'," .
				  "clogon=0";

	     if (!$a) $sSQL .= " WHERE username ='" . $currentuser . "'";
	         else $sSQL .= " WHERE ".$this->actcode." =" . $a;

	     //echo $sSQL;

         $db->Execute($sSQL,1);
         if($db->Affected_Rows()) $sFormErr = "ok";
	                         else $sFormErr = localize('_MSG1',getlocal());

         }
		 else
		   //$sFormErr = localize('_VERPASS',getlocal());
		   SetGlobal('sFormErr',localize('_VERPASS',getlocal()));
	   }
	}

	function saveSession() {
	   $db = GetGlobal('db');
	   $UserName = GetGlobal('UserName');
	   //$db = GetGlobal('db');
	   
	   if ($db) {

		$currentses = session_id();
		$currentuser = decode($UserName);
		$session_data = str_replace("\"","<@>",session_encode());
		//echo '>',$session_data;
		//save ses id
		$sSQL = "UPDATE users set sesid='" . $currentses .
                    "',sesdata='" . $session_data .
		       "' WHERE username ='" . $currentuser . "'" ;

		//echo $sSQL;

		$db->Execute($sSQL,1);
       }
	   
	   //unregister all selected session params
	   ResetSessionParams();


       //session_write_close();

       session_unset();

	   //session_destroy();
	}

	function loadSession($uname) {
	 $db = GetGlobal('db');


	   $sSQL = "select sesdata from users where username='" . $uname ."'" ;
       $res = $db->Execute($sSQL,2);//null,1);
	   //print_r($res->fields);
	   //echo $sSQL;
	   //echo '<',$db->model,'>';

	  /* if ($db->model=='ADODB')
	     $ret = $res->fields[0];
	   else
	     $ret = $res[0];	*/

	 //if ($ret) {
	   //echo '>',$res->fields[0];
	   session_decode(str_replace("<@>","\"",$res->fields[0]));
	 //}
     //echo '>';
     //print_r(GetSessionParam('storebuffer'));
    }

	function is_reseller($leeid=null) {
	   $db = GetGlobal('db');

	   if ($leeid!=null)
	     $id = $leeid;
	   else
	     $id = decode(GetSessionParam('UserID'));

	   //if is in cuatomers table then....
	   if ($id) {

	     $sSQL = "select attr1 from customers where ".$this->actcode."=" . $id;
		 //echo $sSQL;
         $result = $db->Execute($sSQL,2);
		 //echo $result->fields[0];

		 if ($result->fields[0]==$this->reseller_attr) {//'ΧΟΝΔΡΙΚΗ') {
		   //echo 'yes';
		   SetSessionParam('RESELLER','true');
		   return true;
		 }
	   }

	   return false;
	}

	function iforgotmypassword() {

	    $ret = $this->message . "&nbsp;" . $this->link;

		return ($ret);
	}

    function remform($tokensout=null) {

       $url = seturl("t=shremember",0,1);

  	   if ($this->formerror!="ok") {
	   
	     if (defined('RECAPTCHA_DPC')) {
	         $recaptcha = recaptcha_get_html($this->recaptcha_public_key, $this->recaptcha_private_key);	   
	     }		   

		 if ($this->ssl) {
		    //$loginform .= $this->sslscript;
	        $sslwin = new window("",$this->sslscript);
	        $loginform .= $sslwin->render("center::100%::0::group_article_selected::right::0::0::");
	        unset ($sslwin);
		 }
         
		 if ($tokensout) {
		   $tokens[] = $this->formerror;
		 }
		 else
           $loginform .= $this->formerror;

		 if ($tokensout) {
		   $tokens[] = "<form action=\"$url\" method=\"POST\">".
		               "<input type=\"hidden\" name=\"FormName\" value=\"RemLogin\">".
					   "<input type=\"text\" name=\"myemail\" maxlenght=\"50\" size=\"20\" >".
					   $recaptcha . "<input type=\"hidden\" name=\"FormAction\" value=\"shremember\">";
		   $tokens[] = "<input type=\"submit\" value=\"" . "Ok" . "\">".
                       "</form>";
		 }
		 else {		   
         $loginform .= "<form action=\"$url\" method=\"POST\">";
         $loginform .= "<input type=\"hidden\" name=\"FormName\" value=\"RemLogin\">";

         //$loginform .= localize('_USERNAME',getlocal()) . " :<br><input type=\"text\" name=\"myusername\" maxlenght=\"50\" size=\"12\" ><br>";
        /* $lf0[] = localize('_USERNAME',getlocal());
		 $lfat0[] = "right;40%;";
		 $lf0[] = "&nbsp;<input type=\"text\" name=\"myusername\" maxlenght=\"20\" size=\"20\" >";
		 $lfat0[] = "left;60%";
		 $w0 = new window('',$lf0,$lfat0);  $loginform .= $w0->render("center::100%::0::group_article_selected::left::0::0::");   unset ($w0);
		*/
         //$loginform .= localize('_MAIL',getlocal()) . " :<br><input type=\"text\" name=\"myemail\" maxlenght=\"50\" size=\"12\" ><br>";
         $lf1[] = localize('_MAIL',getlocal());
	     $lfat1[] = "right;40%;";
		 $lf1[] = "&nbsp;<input type=\"text\" name=\"myemail\" maxlenght=\"50\" size=\"20\" >";
		 $lfat1[] = "left;60%";
		 $w1 = new window('',$lf1,$lfat1);  $loginform .= $w1->render("center::100%::0::group_article_selected::left::0::0::");   unset ($w1);

		 $loginform .= $recaptcha;
		  
         $loginform .= "<input type=\"hidden\" name=\"FormAction\" value=\"shremember\">";
         $loginform .= "<input type=\"submit\" value=\"" . "Ok" . "\">";
         $loginform .= "</form>";
      
         $toprint .= $loginform;

         $swin = new window(localize('SHLOGIN_DPC',getlocal()),$toprint);
         $out .= $swin->render("center::40%::0::group_win_body::left::0::0::");
	     unset ($swin);
		 }//tokens
		 
	     if ($tokensout) 
	       return ($tokens); 
	     else	 
           return ($out);		 
	   }
	   else {
	     //echo 'OK';
		 if (defined(_CAPTCHA_)) {

           /*if (iniload('JAVASCRIPT')) {
  	            $plink = "<A href=\"" . seturl("t=slogin",'',1) . "\"";
	            //call javascript for opening a new browser win for the img
	            $params = seturl("t=sencaptcha&c=".encode($this->result),0,1) . ";captcha;scrollbars=yes,width=320,height=340;";
				$js = new jscript;
	            $plink .= calldpc_method('javascript.JS_function use js_openwin+'.$params);
                unset ($js);

	            $plink .= ">";
	       }
	       else
              $plink = "<A href=\"" . seturl("t=sencaptcha&c=".encode($this->result)) . ">";

		   $winout = $plink . localize('_PRESSHERE',getlocal()) . "</A>";
		   */
		   //no press here...just showit in the page.
		   //call the image
		   $uc = seturl("t=sencaptcha&c=".encode($this->result));
           $winout .= "<img src=\"$uc\">";

           $swin = new window(localize('SHLOGIN_DPC',getlocal()),$winout);
           $out .= $swin->render("center::40%::0::group_win_body::left::0::0::");
	       unset ($swin);
		 }
		 elseif (defined('SMTPMAIL_DPC')) {
		   //echo 'mailOK';		 
		   $msg = localize('_SENDCRE',getlocal());		   
		   
		   if ($tokensout) {
		     $tokens[] = $msg;
			 
			 //login tokens
		     $tokens[] = "<form action=\"$logonurl\" method=\"POST\">".
		               "<input type=\"text\" name=\"Username\" maxlenght=\"20\">".
					   "<input type=\"hidden\" name=\"FormName\" value=\"Login\">";	
		     $tokens[] = "<input type=\"password\" name=\"Password\" maxlenght=\"20\">".
		                 "<input type=\"hidden\" name=\"FormAction\" value=\"dologin\">";
					   
		     $tokens[] = "<input type=\"submit\" value=\"" . localize('SHLOGIN_DPC',getlocal()) . "\">".
					      "</form>";;					   		 
		   }
		   else {
             $swin = new window(localize('SHLOGIN_DPC',getlocal()),$msg);
             $out .= $swin->render("center::40%::0::group_win_body::left::0::0::");
	         unset ($swin);
		   }	 
		 }
	     else {
		   //echo 'textOK';
		   //text ver
		   $r = $this->result;
		   if ($tokensout) {
		     $tokens[] = $r;
		   }
		   else {		   
             $swin = new window(localize('SHLOGIN_DPC',getlocal()),$r);
             $out .= $swin->render("center::40%::0::group_win_body::left::0::0::");
	         unset ($swin);
		   }	 
		 }
		 
	     if ($tokensout) 
	       return ($tokens); 
	     else	 
           return ($out);			 
	   }

       //return ($out);
    }
	
	function html_remform() {
	
   		$toks = $this->remform(1);
		
		$mydata = str_replace('+','<@>',implode('<TOKENS>',$toks));
		
		if (!$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use remlogin.htm+".$mydata)) 
		  $ret = $this->remform();
		  
		return ($ret);  
	}

	function do_the_job() {
	   $db = GetGlobal('db');
	   $u = GetParam("myusername");
	   $m = GetParam("myemail");
		 
       if ($this->valid_recaptcha()) {		 

		/* if (($m) && ($u)) {//username and mail -> show the password

	       $sSQL = "SELECT ".$this->actcode.", notes, password FROM users WHERE username='" .
		           addslashes($u) . "' AND email='" . addslashes($m) . "'";

	       //echo "remember:",$sSQL;

           $result = $db->Execute($sSQL,2);
		   //print_r($result->fields);

           if (($result->fields[$this->actcode]>0) &&
		       (strcmp(trim($result->fields['notes']),"DELETED")!=0)) {

			   $this->result = 	$result->fields['password'];
			   SetGlobal('captcha',$result->fields['password']);
               //echo $this->result,'>>>>';
			   $this->formerror = "ok";
		   }
		   else $this->formerror = localize('_MSG1',getlocal());
		 }
		 else*/if (($m)) {// && (!$u)) {//mail only -> send username and password
	       $sSQL = "SELECT username, password, notes FROM users WHERE " .
		           "email='" . addslashes($m) . "' and username is not null";//not for subscribers// and seclevid>0";

	       //echo "remember:",$sSQL;

           $result = $db->Execute($sSQL,2);
		   //print_r($result->fields);
           if (($u=$result->fields['username']) && ($p=$result->fields['password'])
		   /* &&
		       (strcmp(trim($result->fields['notes']),"DELETED")!=0)*/) {

	           //if ($this->tellit) {
			   $tokens[] = $u;
			   $tokens[] = $p;
				 
		       $sd = str_replace('+','<@>',implode('<TOKENS>',$tokens));
		       if (!$mailbody = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use userremind.htm+".$sd."+1")) {
		
			   
	             $mailcontent = "Account info:<br>" . $u . '/' . $p;

		         $template = paramload('SHELL','prpath') . "insertusrusr.tpl";
		         $mailbody = str_replace("##_LINK_##",$mailcontent,file_get_contents($template));
				 $this->result = $mailbody;
               }
			   
		       $from = $this->accountmailfrom;
	           $this->mailto($from,$m,localize('_UMAILREMSUBC',getlocal()),$mailbody);

			     /*if ($error)
			       $this->formerror = $error;
			     else*/
			   $this->formerror = "ok";//localize('_MSG2',getlocal()); //"ok";
			   //} 
		   }
		   else $this->formerror = localize('_MSG1',getlocal());
		 }
		 else
		   $this->formerror = localize('_MSG1',getlocal());
		   
		   
		 if ($this->formerror!='ok') {
		   SetGlobal('sFormErr',$this->formerror);		   
		 }
		 else {
		   //$msg = localize('_SENDCRE',getlocal());	
		   SetGlobal('sFormErr',"OKREMINDER");////$msg);
		 }
		 
	   }//recaptcha	    
		   
	}

	function do_the_captcha() {

		//$this->captcha = new captcha();//strlen($this->result),'jpeg',$this->result);
		//$captcha->sent_header();
		//echo $captcha;
	}

	function show_the_captcha() {

	       $result = decode(GetReq('c'));
		   $captcha = new captcha(strlen($result),'jpeg',$result);

		   die();
	}

	function is_logged_in() {

	      if (GetSessionParam('UserID'))
		    return true;

		  return false;
	}

	function mailto($from,$to,$subject=null,$body=null) {

		     if ((defined('SMTPMAIL_DPC')) &&
				 (seclevel('SMTPMAIL_DPC',$this->UserLevelID)) ) {
		       $smtpm = new smtpmail;
		       $smtpm->to = $to;
			   $smtpm->from = $from;
			   $smtpm->subject = $subject;
			   $smtpm->body = $body ;

			   $mailerror = $smtpm->smtpsend();
			   //echo '>',$mailerror;

			   unset($smtpm);

			   return ($mailerror);
			 }
	}
	
	function login_with_key($key=null,$code=null,$ischar=null) {
	  $db = GetGlobal('db');
		 	
	  if ($key) {
	  
	      $pk = explode("~",$key);

	      $sSQL = "SELECT ".$this->actcode.", sesid, notes, seclevid, clogon, username, password FROM users WHERE ";
		  $sSQL.= $code?$code:$this->actcode;
		  $sSQL.= "=";
		  $sSQL.= $ischar?"'".$pk[0]."'":$pk[0];
		  //echo $sSQL;

		  //echo "login:",$sSQL;

          $result = $db->Execute($sSQL,2);
		  //print_r($result->fields);
		  $hash = $pk[1];
		  $data = trim($result->fields['username']).":".trim($result->fields['password']);
		  $hash2cmp = md5($data);
		  
		  if (strcmp($hash,$hash2cmp)) {//echo 'zzzz2';
		  
          
             if (/*($result->fields[$this->actcode]>0) &&*////PROBLEM
		         (strcmp(trim($result->fields['notes']),"DELETED")!=0)) {//echo 'zzzz';

                             if ($this->load_session)
							  $this->loadSession($sUsername);

							  $_POST['UserName'] = encode($sUsername);
							  
                              SetSessionParam("UserName", encode($sUsername));
							  SetSessionParam("Password", encode($sPassword));//!!!!!
                              SetSessionParam("UserID", encode($result->fields[$this->actcode]));
							  $GLOBALS['UserID']=encode($result->fields[$this->actcode]);
                              SetSessionParam("UserSecID", encode($result->fields['seclevid']));
							  $this->is_reseller(); //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

			                  //re-enter password flag
			                  if ($result->fields['clogon']==1) {
							    $this->must_reenter_password=1;
								$chpass = seturl("t=chpass",localize('_PASSREMINDER',getlocal()),1,'',1);
								setInfo($chpass);
							  }
							  else
  		                        setInfo(localize('_WELLCOME',getlocal()) . " " . $sUsername);

							  //set cookie
							  if (paramload('SHELL','cookies')) {
							    setcookie("cuser",$UserName,time()+$this->time_of_session);//,time()+3600,"","panikidis.gr",0);
								setcookie("csess",session_id(),time()+$this->time_of_session);
							  }
							  //echo "login:",$sSQL;
							  return (encode($sUsername));
             }
             else {
		                      //setInfo(localize('_MSG1',getlocal()));
                              //$sFormErr = localize('_MSG1',getlocal());
							  SetGlobal('sFormErr',localize('_MSG1',getlocal()));
							  return false;
             }  
		  }//hash validation	 
	  
	  }//is key
	  else
	    return false;
   }
   
	 function valid_recaptcha() {
	 
	      if (!defined('RECAPTCHA_DPC')) return true;
		  
		  //print_r($_POST);
		  
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
		        $msg = '<br>' . "Incorrect recaptcha entry!";				
            }
		  }
		  else {
		    $ret = false;
		    $msg = '<br>' . "Recaptcha entry required!";			  
		  }
		  //$sFormErr = $error;
		  //echo '>',$error,'-',$ret;
		  //SetGlobal('sFormErr',$this->msg);
		  
		  $this->formerror = $msg;//'recaptcha error';
		  SetGlobal('sFormErr',$msg);
		  
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
		    $ret = str_replace("$".$i,$tok,$ret);
	    }
		//clean unused token marks
		for ($x=$i;$x<10;$x++)
		  $ret = str_replace("$".$x,'',$ret);
		//echo $ret;
		return ($ret);
	}   		
};
}
?>