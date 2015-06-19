<?php

$__DPCSEC['RCUSERS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCUSERS_DPC")) && (seclevel('RCUSERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCUSERS_DPC",true);

$__DPC['RCUSERS_DPC'] = 'rcusers';

$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($a);

$b = GetGlobal('controller')->require_dpc('shop/shusers.dpc.php');
require_once($b);


$__EVENTS['RCUSERS_DPC'][0]='cpusers';
$__EVENTS['RCUSERS_DPC'][1]='deluser';
$__EVENTS['RCUSERS_DPC'][2]='reguser';
$__EVENTS['RCUSERS_DPC'][3]='cpcusmail';
$__EVENTS['RCUSERS_DPC'][4]='cpcusmsend';
$__EVENTS['RCUSERS_DPC'][5]='insuser';
$__EVENTS['RCUSERS_DPC'][6]='upduser';
$__EVENTS['RCUSERS_DPC'][7]='saveupduser';
$__EVENTS['RCUSERS_DPC'][8]='cpupdate';
$__EVENTS['RCUSERS_DPC'][9]='cpupdateadv';
$__EVENTS['RCUSERS_DPC'][10]='cpusractiv';
$__EVENTS['RCUSERS_DPC'][11]='searchtopic';

$__ACTIONS['RCUSERS_DPC'][0]='cpusers';
$__ACTIONS['RCUSERS_DPC'][1]='deluser';
$__ACTIONS['RCUSERS_DPC'][2]='reguser';
$__ACTIONS['RCUSERS_DPC'][3]='cpcusmail';
$__ACTIONS['RCUSERS_DPC'][4]='cpcusmsend';
$__ACTIONS['RCUSERS_DPC'][5]='insuser';
$__ACTIONS['RCUSERS_DPC'][6]='upduser';
$__ACTIONS['RCUSERS_DPC'][7]='saveupduser';
$__ACTIONS['RCUSERS_DPC'][8]='cpupdate';
$__ACTIONS['RCUSERS_DPC'][9]='cpupdateadv';
$__ACTIONS['RCUSERS_DPC'][10]='cpusractiv';
$__ACTIONS['RCUSERS_DPC'][11]='searchtopic';

$__DPCATTR['RCUSERS_DPC']['cpusers'] = 'cpusers,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCUSERS_DPC'][0]='RCUSERS_DPC;Users;Users';
$__LOCALE['RCUSERS_DPC'][1]='_reason;Reason;Αιτία';
$__LOCALE['RCUSERS_DPC'][2]='_cdate;Date in;Ημ/νία εισοδου';
$__LOCALE['RCUSERS_DPC'][3]='_price;Price;Τιμή';
$__LOCALE['RCUSERS_DPC'][4]='_ftype;Pay;Πληρωμή';
$__LOCALE['RCUSERS_DPC'][5]='_name1;First Name;Ονομα';
$__LOCALE['RCUSERS_DPC'][6]='_name2;Last Name;Επώνυμο';
$__LOCALE['RCUSERS_DPC'][7]='_kybismos;Kyb.;Κυβικα';
$__LOCALE['RCUSERS_DPC'][8]='_color;Color;Χρώμα';
$__LOCALE['RCUSERS_DPC'][9]='_extras;Extras;Εχτρα';
$__LOCALE['RCUSERS_DPC'][10]='_address;Address;Διεύθυνση';
$__LOCALE['RCUSERS_DPC'][11]='_tel;Tel.;Τηλέφωνο';
$__LOCALE['RCUSERS_DPC'][12]='_mob;Mobile;Κινητό';
$__LOCALE['RCUSERS_DPC'][13]='_email;e-mail;e-mail';
$__LOCALE['RCUSERS_DPC'][14]='_fax;Fax;Fax';
$__LOCALE['RCUSERS_DPC'][15]='_TIMEZONE;Timezone;Ζωνη ωρας';
$__LOCALE['RCUSERS_DPC'][16]='_fname;Title;Επωνυμια';
$__LOCALE['RCUSERS_DPC'][17]='_lname;Job title;Επαγγελμα';
$__LOCALE['RCUSERS_DPC'][18]='_username;Username;Χρήστης';
$__LOCALE['RCUSERS_DPC'][19]='_password;Password;Κωδικός';
$__LOCALE['RCUSERS_DPC'][20]='_notes;Notes;Σημειωσεις';
$__LOCALE['RCUSERS_DPC'][21]='_subscribe;Subscriber;Συνδρομητης';
$__LOCALE['RCUSERS_DPC'][22]='_seclevid;seclevid;seclevid';
$__LOCALE['RCUSERS_DPC'][23]='_secparam;Param;Param';
$__LOCALE['RCUSERS_DPC'][24]='_active;Active;Ενεργός';

class rcusers extends shusers {

    var $title;
	var $carr;
	var $msg;
	var $path;
	var $post;
	var $maillink;

	var $_grids;
	//var $urlpath, $inpath;
	
	var $tell_activate, $tell_deactivate;
	var $subj_activate, $subj_deactivate;
	var $body_activate, $body_deactivate;

	function rcusers() {
	
	  shusers::shusers();
	
	  $GRX = GetGlobal('GRX');
	  $this->title = localize('RCUSERS_DPC',getlocal());
	  $this->carr = null;
	  $this->msg = null;

	  $this->path = paramload('SHELL','prpath');
	  //$this->urlpath = paramload('SHELL','urlpath');
	  //$this->inpath = paramload('ID','hostinpath');		  

      $this->_grids[] = new nitobi("Users");
	  $this->_grids[] = new nitobi("Transactions");

	  $this->maillink = seturl('t=cpcusmail&<@>');

      if ($GRX) {
          $this->delete = loadTheme('ditem',localize('_delete',getlocal()));
          $this->edit = loadTheme('eitem',localize('_edit',getlocal()));
          //$this->import = loadTheme('ivehicle',localize('_import',getlocal()));
          //$this->recode = loadTheme('rvehicle',localize('_recode',getlocal()));
          $this->add = loadTheme('aitem',localize('_add',getlocal()));
          $this->mail = loadTheme('mailitem',localize('_mail',getlocal()));

		  $this->sep = "&nbsp;";//loadTheme('lsep');
      }
      else {
          $this->delete = localize('_delete',getlocal());
          $this->edit = localize('_edit',getlocal());
          //$this->import = localize('_import',getlocal());
          //$this->recode = loadTheme('rvehicle','show help');
          $this->add = localize('_add',getlocal());
          $this->mail = localize('_mail',getlocal());

		  $this->sep = "|";
      }
	  
	  $this->tell_activate = remote_paramload('RCUSERS','mail_on_activate',$this->path);
	  $this->tell_deactivate = remote_paramload('RCUSERS','mail_on_deactivate',$this->path);
	  $this->subj_activate = remote_paramload('RCUSERS','subject_on_activate',$this->path);
	  $this->subj_deactivate = remote_paramload('RCUSERS','subject_on_deactivate',$this->path);
	  $this->body_activate = remote_paramload('RCUSERS','text_on_activate',$this->path);
	  $this->body_deactivate = remote_paramload('RCUSERS','text_on_deactivate',$this->path);	  
	}

    function event($event=null) {

	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//
	   /////////////////////////////////////////////////////////////

	   switch ($event) {
	     case 'cpusractiv'    : $this->activate_deactivate();
	                            break;
	     case 'cpupdateadv'   :
		                        break;
	     case 'cpupdate'      : if (!$this->checkFields(null,$this->checkuseasterisk)) {
		 
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
		 
	     case 'cpcusmsend'  : $this->send_mail();
                              $this->nitobi_javascript();
		                      //$this->carr = $this->select_customers('all',null,GetReq('alpha'));//dummy param
		                      break;
	     case 'cpcusmail'   :
		                      break;

	     case 'reguser' :
		                      break;
		 case 'insuser' :     $this->insert();
		                      if (!GetReq('editmode'))
		                        $this->nitobi_javascript();
		                      break;
	     case 'upduser' :     if (!GetReq('editmode'))
		                        $this->updrec = $this->getuserdata(null,GetReq('rec'),$this->actcode);
							  //else
							    //$this->updrec = $this->getuser(GetReq('rec'),null,0,1);	
		                      break;
		 case 'saveupduser' : $this->update();
		                      if (!GetReq('editmode'))
		                        $this->nitobi_javascript();
		                      break;
							  
	     case 'deluser' :     if (!GetReq('editmode')) {
		                        $this->_delete(GetReq('rec'),'code2');
		                        $this->nitobi_javascript();
							  }
							  else	
							    $this->_delete(GetReq('rec'),'id');
							  break;
							 
		 case 'searchtopic' : 					 
	     case 'cpusers'     : 
		 default            : if (!GetReq('editmode'))
		                        $this->nitobi_javascript();
		                      //$this->carr = $this->select_customers('all',null,GetReq('alpha'));//dummy param
	   }

    }

    function action($action=null) {

	  if (GetSessionParam('REMOTELOGIN'))
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title);
	  elseif (!GetReq('editmode'))
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);

	  switch ($action) {
	     case 'cpupdateadv' : $out .= $this->user_form();
		                      break;
	     case 'cpcusmsend'  : $out .= $this->show_users();
		                      break;
	     case 'cpcusmail'   : $out .= $this->show_mail();
		                      break;
	     case 'deluser'     : if (GetReq('editmode'))
		                        $out .= $this->viewUsers();
							  else	
		                        $out .= $this->show_users();
		                      break;
		 case 'reguser'     : //$out .= $this->form();
		                      //$out .= $this->show_users();
							  $out .= $this->regform(null,'insuser');
							  break;
		 case 'cpupdate'  :	  //$out .= $this->register();
		                      if (!GetReq('editmode'))
		                        $out .= $this->show_users();
							  else
							    $out .= $this->viewUsers();	
		                      break;	
							  			  
	     case 'upduser' :     //$out .= $this->updrec.'>';
		 
		                      if (!GetReq('editmode'))
		                        $out .= $this->regform($this->updrec,'saveupduser',1);
							  else
							    $out .= $this->register(GetReq('rec'),'id','rec','cpupdate');	
							  
		                      break;
		 case 'saveupduser' :
         case 'insuser'     :
	     case 'cpusers'     :
		 case 'cpusractiv'  :
		 case 'searchtopic' :			 
		 default            : if (!GetReq('editmode'))
		                        $out .= $this->show_users();
							  else
							    $out .= $this->viewUsers();	
	 }

	 return ($out);
    }

	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   $template = $this->set_template();

	       $code = $this->init_grids();
		   $code .= $this->_grids[0]->OnClick(12,'Userdetails',$template,'Transactions','cid',1);

		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.grid.js');
           $js->load_js($code,"",1);
		   unset ($js);
	  }
	}

	function set_template() {

	       $edit = seturl("t=upduser"."&rec=");
		   $add =  seturl("t=reguser");
		   $del =  seturl("t=deluser&rec=");
		   $mail = seturl("t=cpusmail&rec=");

		   $template .= "<A href=\"$edit'+i1+'\">".$this->edit."</A>". $this->sep;
		   $template .= "<A href=\"$add\">".$this->add."</A>". $this->sep;
		   $template .= "<A href=\"$del'+i1+'\">".$this->delete."</A>". $this->sep;
		   $template .= "<A href=\"$mail'+i1+'\">".$this->mail."</A>". $this->sep;
		   $template .= "<br>";

		   //customer
		   $template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td width=\"30%\">";
		   $template .= localize('a/a',getlocal()).":</br>";
		   $template .= localize('_code',getlocal()).":</br>";
		   $template .= localize('_fname',getlocal()).":</br>";
		   $template .= localize('_lname',getlocal()).":</br>";
		   $template .= localize('_notes',getlocal()).":</br>";
		   $template .= localize('_seclevid',getlocal()).":</br>";
		   $template .= localize('_secparam',getlocal()).":</br>";
		   $template .= localize('_subscribe',getlocal()).":</br>";
		   $template .= localize('_email',getlocal()).":</br>";
		   $template .= localize('_username',getlocal()).":</br>";
		   $template .= localize('_password',getlocal()).":</br>";
		   $template .= "</td><td width=\"70%\">";
		   $template .= "'+i0+'<br>" . "'+i1+'<br>" . "'+i2+'<br>" . "'+i3+'<br>" .
		                "'+i4+'<br>" . "'+i5+'<br>" ."'+i6+'<br>" . "'+i7+'<br>" .
						"'+mailto(i8,5)+'<br>" . "'+i9+'" . "<br>'+i10+'<br>";
		   $template .= "</td></tr></table>";

		   //wish
		   /*$template .= "<br>";
		   $template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td width=\"30%\">";
		   $template .= localize('_reason',getlocal()).":</br>";
		   $template .= localize('_type',getlocal()).":</br>";
		   $template .= localize('_marka',getlocal()).":</br>";
		   $template .= localize('_model',getlocal()).":</br>";
		   $template .= localize('_kybismos',getlocal()).":</br>";
		   $template .= localize('_etosk',getlocal()).":</br>";
		   $template .= localize('_km',getlocal()).":</br>";
		   $template .= localize('_color',getlocal()).":</br>";
		   $template .= localize('_price',getlocal()).":</br>";
		   $template .= localize('_ftype',getlocal()).":</br>";
		   $template .= "</td><td width=\"70%\">";
           $template .= "'+wish_list(i10)+'";
		   $template .= "</td></tr></table>";	*/

		   return ($template);
	}

	function show_users() {

	   if ($this->msg) $out = $this->msg;
	   /*
	   $myadd = new window('',seturl("t=regcustomer","Register a new customer!"));
	   $toprint .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");
	   unset ($myadd);
	   */

	   $toprint .= $this->show_grids();

	   $toprint .= $this->alphabetical();

	   $dater = new datepicker("/MDYT");
	   $toprint .= $dater->renderspace(seturl("t=cpusers"),"cpusers");
	   unset($dater);

       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render();

	   return ($out);
	}

	function alphabetical($command='cpusers') {

	  $preparam = GetReq('alpha');

	  $ret .= seturl("t=$command","Home") . "&nbsp;|";

	  for ($c=$preparam.'a';$c<$preparam.'z';$c++) {
	    $ret .= seturl("t=$command&alpha=$c",$c) . "&nbsp;|";
	  }
	  //the last z !!!!!
	  $ret .= seturl("t=$command&alpha=".$preparam."z",$preparam."z");

      //$mywin = new window('',$ret);
      //$out = $mywin->render();

	  return ($ret);
	}

	function form($action=null) {

     $myaction = seturl("t=reguser");

     if ($this->post==true) {

	   SetSessionParam('REGISTERED_CUSTOMER',1);
	 }
	 else { //show the form plus error if any

       //if (!$action) $out = setNavigator($this->title);

       $out .= setError($sFormErr . $this->msg);


	   $form = new form(localize('_ADDEVENT',getlocal()), "reguser", FORM_METHOD_POST, $myaction, true);

	   $form->addGroup			("personal",			"Tell us about your self.");
	   //$form->addGroup			("technical",			"Tell us about your technology.");
	   $form->addGroup			("subscribe",			"Subscribe.");

	   $form->addElement		("personal",			new form_element_text		(localize('_COMP',getlocal())."*",     "company",		GetParam("company"),				"forminput",	        50,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_CPER',getlocal()),     "cperson",		GetParam("cperson"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_ACTV',getlocal()),     "activities",	GetParam("activities"),				"forminput",	        30,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_ADDR',getlocal()),     "address",	    GetParam("address"),				"forminput",	        30,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_TOWN',getlocal()),     "town",	        GetParam("town"),				"forminput",	        20,				255,	0));
//	   $form->addElement		("personal",			new form_element_greekmap	(localize('_NOMOS',getlocal()),     "amail","nomos",GetParam("nomos"),"forminput",20,20,1));
	   $form->addElement		("personal",			new form_element_text		(localize('_ZIP',getlocal()),      "zip",	        GetParam("zip"),				"forminput",	        20,				255,	0));
	   //$form->addElement		("personal",			new form_element_text		(localize('_CNTR',getlocal()),     "country",	    GetParam("country"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_combo_file (localize('_CNTR',getlocal()),     "country",	    $this->get_country_from_ip(),				"forminput",	        1,				0,	'country'));
	   $form->addElement		("personal",			new form_element_text		(localize('_TEL',getlocal()),      "tel",	        GetParam("tel"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_FAX',getlocal()),      "fax",	        GetParam("fax"),				"forminput",	        20,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_MAIL',getlocal())."*",     "email",			GetParam("email"),				"forminput",	        30,				255,	0));
	   $form->addElement		("personal",			new form_element_text		(localize('_WEB',getlocal()),      "web",			"http://",		"forminput",		    20,				255,	0));

	   //$form->addElement		("technical",			new form_element_combo_file (localize('_PLAN',getlocal()),     "proglan",	    GetParam("proglan"),				"forminput",	        5,				0,	'proglan'));
	   //$form->addElement		("technical",			new form_element_combo_file (localize('_OSYS',getlocal()),     "opersys",	    GetParam("opersys"),				"forminput",	        5,				0,	'opersys'));
	   //$form->addElement		("technical",			new form_element_combo_file (localize('_USERI',getlocal()),     "userint",	    GetParam("userint"),				"forminput",	        5,				0,	'userint'));
	   //$form->addElement		("technical",			new form_element_combo_file (localize('_DBENV',getlocal()),     "dbenv",	    GetParam("dbenv"),				"forminput",	        5,				0,	'dbenv'));

	   //$form->addElement		("subscribe",			new form_element_text		(localize('_SYBSCR',getlocal()),   "subscribe",		"",				"forminput",	        20,				255,	0));
	   $form->addElement		("subscribe",			new form_element_radio		(localize('_RCSUBSE',getlocal()),   "subscribe",      1,             "",   2, array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));
	   //$form->addElement		("thema",			    new form_element_text		(localize('_SUBJECT',getlocal())."*",  "subject",		GetParam("subject"),				"forminput",			60,				255,	0));
	   //$form->addElement		("thema",			    new form_element_textarea   (localize('_MESSAGE',getlocal()),  "mail_text",		GetParam("mail_text"),				"formtextarea",			60,				9));

	   //$form->addElement		("warning",			    new form_element_onlytext	(localize('_WARNING',getlocal()),  localize('_FORMWARN',getlocal()),""));

	   //if ($this->info_message)
	     //$form->addElement		("info",			    new form_element_onlytext	("",  $this->info_message,""));

	   // Adding a hidden field
	   if ($action)
	     $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", $action));
	   else
	     $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "reguser"));

	   // Showing the form
	   $fout = $form->getform ();

	   //$fwin = new window(localize('AMAIL_DPC',getlocal()),$fout);
	   //$out .= $fwin->render();
	   //unset ($fwin);

	   $out .= $fout;

	   //$form->checkform();
	 }

     return ($out);
	}


    function get_country_from_ip() {

     $mycountry = GetGlobal('controller')->calldpc_method("country.find_country");
	 //return "Greece";
	 return ($mycountry);
    }

	function init_grids() {
        //disable alert !!!!!!!!!!!!
		$out = "
function alert() {}\r\n

function wish_list() {
  var str = arguments[0];

  if (str.substr(0,1)>0) x='ΠΩΛΗΣΗ'; else x='ΑΓΟΡΑ';

  var data = str.substr(1);
  databr = data.replace(/<@>/g, '<br>');

  ret = x+databr;

  return ret;
}

function mailto() {
  var mail = arguments[0];
  var veh = arguments[1];

  var data = '$this->maillink';
  link = data.replace(/<@>/, 'm='+mail+'&id='+veh);
  ret = '<A href=\''+link+'\'>'+mail+'</A>';

  return ret;
}

function init()
{
";
        foreach ($this->_grids as $n=>$g)
		  $out .= $g->init_grid($n);

        $out .= "\r\n}";
        return ($out);
	}

	function show_grids() {
	   //gets
	   $alpha = GetReq('alpha');
	   //transformed posts !!!!
	   $apo = GetParam('apo');
	   $eos = GetParam('eos');
           $filter = GetParam('filter');


	   $grid0_get = "shhandler.php?t=shngetuserslist&alpha=$alpha&apo=$apo&eos=$eos&filter=$filter";
	   $grid0_set = "shhandler.php?t=shnsetusers";

	   $this->_grids[0]->set_text_column("ID","id","50","true");
	   $this->_grids[0]->set_text_column(localize('_code',getlocal()),"code2","70","true");
	   $this->_grids[0]->set_text_column(localize('_fname',getlocal()),"fname","150","true");
	   $this->_grids[0]->set_text_column(localize('_lname',getlocal()),"lname","150","true");
	   $this->_grids[0]->set_text_column(localize('_active',getlocal()),"notes","100","true","CHECKBOX","check_active","display","value",'ACTIVE','');
	   $this->_grids[0]->set_text_column(localize('_seclevid',getlocal()),"seclevid","100","true");
	   $this->_grids[0]->set_text_column(localize('_secparam',getlocal()),"secparam","100","true");
	   $this->_grids[0]->set_text_column(localize('_subscribe',getlocal()),"subscribe","100","true","CHECKBOX","check_subscriber","display","value",'1','0');
	   $this->_grids[0]->set_text_column(localize('_email',getlocal()),"email","150","true");
	   $this->_grids[0]->set_text_column(localize('_username',getlocal()),"username","150","true");
	   $this->_grids[0]->set_text_column(localize('_password',getlocal()),"password","150","true");
	   $this->_grids[0]->set_text_column(localize('_TIMEZONE',getlocal()),"timezone","150","true");	   

	   $this->_grids[0]->set_datasource("check_active",array('ACTIVE'=>'Active','0'=>'Inactive'),null,"value|display",true);
	   $this->_grids[0]->set_datasource("check_subscriber",array('1'=>'Active','0'=>'Inactive'),null,"value|display",true);


       $datattr[] = $this->_grids[0]->set_grid_remote($grid0_get,$grid0_set,"400","460","livescrolling",17) . $this->searchinbrowser();
	   $viewattr[] = "left;50%";

	   //details
	   $add =  seturl("t=reguser");
	   $message = "<A href=\"$add\">".$this->add."</A>";//. $this->sep;

	   $wd .= $this->_grids[0]->set_detail_div("UserDetails",400,360,'F0F0FF',$message);

	   //grid 1
	   $wd .= GetGlobal('controller')->calldpc_method("rctransactions.show_grid use 400+150+1");

	   $datattr[] = $wd;
	   $viewattr[] = "left;50%";

	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);

	   return ($ret);
	}

	//show the customers grid as vehicles lookup
	//called from cpvehicles
	function show_grid() {
	   $grid1_get = 'shhandler.php?t=shgetvcustomers';

	   $this->_grids[0]->set_text_column("ID","id","70","true");
	   $this->_grids[0]->set_text_column(localize('_cdate',getlocal()),"insdate","100","true");
	   $this->_grids[0]->set_text_column(localize('_name1',getlocal()),"fname","150","true");
	   $this->_grids[0]->set_text_column(localize('_name2',getlocal()),"lname","150","true");
	   $this->_grids[0]->set_text_column(localize('_address',getlocal()),"address","100","true");
	   $this->_grids[0]->set_text_column(localize('_tel',getlocal()),"tel","100","true");
	   $this->_grids[0]->set_text_column(localize('_fax',getlocal()),"fax","100","true");
	   $this->_grids[0]->set_text_column(localize('_mob',getlocal()),"mob","100","true");
	   $this->_grids[0]->set_text_column(localize('_mail',getlocal()),"mail","100","true");
	   $this->_grids[0]->set_text_column(localize('_TIMEZONE',getlocal()),"timezone","150","true");	   
       $ret = $this->_grids[0]->set_grid_remote($grid1_get,"","400","100","livescrolling",null,"false");

	   return ($ret);
	}
	
	function getUsersList() {
       $db = GetGlobal('db');
       $UserName = GetGlobal('UserName');	
	   //$name = $UserName?decode($UserName):null;		   
       //echo GetReq('col');
	   	   
	     $sSQL = "select id,notes,fname,lname,email,username,code1,code2 from users ";// .//where cid=" . $db->qstr($name) . 
		 
		 if ($s = GetParam('searcht')) {//SEARCH TOPIC   
		   $sSQL .= "where fname like '%$s%' or lname like '%$s%' or email like '%$s%' or username like '%$s%' ";
		 }  		 
		 
		 if ($col = GetReq('col'))
		   $sSQL .= "order by " . $col;
		 else
		   $sSQL .= "order by id"; 
		   
		 if (GetReq('sort')<0)
		   $sSQL .= ' DESC';
		   
		 //echo $sSQL;		 
				 
				 
	     $res = $db->Execute($sSQL,2);
	     //print_r ($res);
		 $i=0;
	     if (!empty($res)) { 
	       foreach ($res as $n=>$rec) {
		    $i+=1;
				
			
            $transtbl[] = $i . ";" . 
                         $rec[0] . ";" . $rec[1] . ";" . $rec[2] . ";" . $rec[3] . ";" .
						 $rec[4] . ";" . $rec[5] . ";" . $rec[6] . ";" . $rec[7];						 					 	   
		   }
		   
           //browse
		   //print_r($transtbl); 
		   $ppager = GetReq('pl')?GetReq('pl'):100;
           $browser = new browse($transtbl,null,$this->getpage($transtbl,$this->searchtext));
	       $out .= $browser->render("cpusers",$ppager,$this,1,1,0,0,1,1,1,0);
	       unset ($browser);	
		      
	     }
		 else {
           //empty message
	       $w = new window(null,localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);

		 }		 	
	   
	   return ($out);
	} 
	
	function viewUsers() {
       $db = GetGlobal('db');
	   $a = GetReq('a');
       $UserName = GetGlobal('UserName');	   
	   
	  /* $apo = GetParam('apo'); //echo $apo;
	   $eos = GetParam('eos');	//echo $eos;   

       $myaction = seturl("t=cpusers&editmode=".GetReq('editmode'));	   
	   
       $out .= "<form method=\"POST\" action=\"";
       $out .= "$myaction";
       $out .= "\" name=\"Transview\">";		   
      */ 
	 
	   $out .= 	$this->getUsersList();	 
		 
	  /*		 
       $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Userview\">";
       $out .= "</FORM>";			 		   
		*/	 	 					
	   return ($out);	
	
	}		

	function show_mail() {
       $sFormErr = GetGlobal('sFormErr');
	   $sendto = GetReq('m');

	   if (defined('ABCMAIL_DPC')) {
	     $ret = $sFormErr;
	     $ret .= GetGlobal('controller')->calldpc_method('abcmail.create_mail use cpcusmsend+'.$sendto);
	   }

	   return ($ret);
	}

	function send_mail($from=null, $to=null, $subject=null, $body=null) {

	   if (!defined('RCSSYSTEM_DPC')) return;

	   $from = $from ? $from : GetParam('from');
	   $to = $to ? $to : GetParam('to');
	   $subject = $subject ? $subject : GetParam('subject');
	   $body = $body ? $body : GetParam('mail_text');

	   if ($res = GetGlobal('controller')->calldpc_method('rcssystem.sendit use '.$from.'+'.$to.'+'.$subject.'+'.$body)) {
	     $this->mailmsg = "Send successfull";
		 return true;
	   }	 
	   else {
	     $this->mailmsg = "Send failed";
		 return false;
	   }	 
	}

    function searchinbrowser() {
            $ret = "
           <form name=\"searchinbrowser\" method=\"post\" action=\"\">
           <input name=\"filter\" type=\"Text\" value=\"\" size=\"56\" maxlength=\"64\">
           <input name=\"Image\" type=\"Image\" src=\"../images/b_go.gif\" alt=\"\"    align=\"absmiddle\" width=\"22\" height=\"28\" hspace=\"10\" border=\"0\">
           </form>";

          $ret .= "<br>Last search: " . GetParam('filter');

          return ($ret);
    }
		
	function getpage($array,$id){
	
	   if (count($array)>0) {
         //while(list ($num, $data) = each ($array)) {
         foreach ($array as $num => $data) {
		    $msplit = explode(";",$data);
			if ($msplit[1]==$id) return floor(($num+1) / $this->pagenum)+1;
		 }	  
		 
		 return 1;
	   }	 
	}		
		
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata); //print_r($data);
	
       $out = $this->viewusrs($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);

	   return ($out);
	}				
		
    function viewusrs($i,$id,$notes,$fname,$lname,$email,$username,$code1,$code2) {
	   $p = GetReq('p');
	   $a = GetReq('a');
	   
	   $del_link = seturl("t=deluser&rec=$id&p=$p&editmode=1" , $i);
	   $name_link = seturl("t=upduser&rec=$id&p=$p&editmode=1" , $fname. '&nbsp;'.$lname);								  	   
	   $email_link = seturl("t=cpupdateadv&rec=$id&p=$p&editmode=1" , $email);	
	   $activ_link = seturl("t=cpusractiv&rec=$id&p=$p&editmode=1" , $notes);	   			  
	   	   
	   $data[] = $del_link?$del_link:'&nbsp;';   
	   $attr[] = "left;10%";	   
	   
	   $data[] = $activ_link?$activ_link:'&nbsp;';   
	   $attr[] = "left;15%";   
	   
	   $data[] = $name_link?$name_link:'&nbsp;';   
	   $attr[] = "left;25%";	      
	   
	   $data[] = $email_link?$email_link:'&nbsp;'; /*. '/' . $dtime*/;   
	   $attr[] = "left;25%";	
	   
	   $data[] = $username?$username:'&nbsp;';   
	   $attr[] = "left;25%";		   
	   
	   
	   $myarticle = new window('',$data,$attr);
       $line = $myarticle->render();//"center::100%::0::group_dir_body::left::0::0::");
	   unset ($data);
	   unset ($attr);
	   
       if ($this->details) {//disable cancel and delete form buttons due to form elements in details????
	     $mydata = $line . '<br/>' . $this->details($id);
	     $cartwin = new window2($id . '/' . $status,$mydata,null,1,null,'HIDE',null,1);
	     $out = $cartwin->render();//"center::100%::0::group_article_body::left::0::0::"
	     unset ($cartwin);		   
	   }	
	   else {   
		 $out .= $line ;//. '<hr>';
	   }	   
	   

	   return ($out);
	}			
		
	function headtitle() {
	   $p = GetReq('p');
	   $t = GetReq('t')?GetReq('t'):'cpusers';
	   $sort = GetReq('sort')>0?-1:1; 
	   
	   if (GetReq('editmode'))
	     $edmode = '&editmode=1';
	   else
	     $edmode = null; 
	
       $data[] = seturl("t=$t&a=&g=1&p=$p&sort=$sort&col=id".$edmode ,  "A/A" );
	   $attr[] = "left;10%";							  
	   $data[] = seturl("t=$t&a=&g=2&p=$p&sort=$sort&col=notes".$edmode , localize('_active',getlocal()) );
	   $attr[] = "left;15%";
	   $data[] = seturl("t=$t&a=&g=3&p=$p&sort=$sort&col=fname".$edmode , localize('_fname',getlocal()) );
	   $attr[] = "left;25%";
	   $data[] = seturl("t=$t&a=&g=4&p=$p&sort=$sort&col=email".$edmode , localize('_email',getlocal()) );
	   $attr[] = "left;25%";
	   $data[] = seturl("t=$t&a=&g=5&p=$p&sort=$sort&col=username".$edmode , localize('_username',getlocal()) );
	   $attr[] = "left;25%";	   

  	   $mytitle = new window('',$data,$attr);
	   $out = $mytitle->render(" ::100%::0::group_form_headtitle::center;100%;::");
	   unset ($data);
	   unset ($attr);	
	   
	   return ($out);
	}
	
	function user_form() {
	  global $config;	
      $db = GetGlobal('db');	
	
	   if (GetReq('editmode')) {//default form colors	

	     $config['FORM']['element_bgcolor1'] = 'EEEEEE';
	     $config['FORM']['element_bgcolor2'] = 'DDDDDD';	
		   
         $sSQL = "select id from users ";
	     $sSQL .= " WHERE id='" . GetReq('rec') . "'";	
	     //echo $sSQL;
	  
	     $resultset = $db->Execute($sSQL,2);	
		 //print_r($resultset->fields);
		 $id = $resultset->fields['id']	;  
		 
	     GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+50+100+0+0');
	     GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+50+10');
		 GetGlobal('controller')->calldpc_method('dataforms.setformgoto use DPCLINK:cpusers:OK');
	     GetGlobal('controller')->calldpc_method('dataforms.setformtemplate use cpupdateadvok');	   
	   
         $fields = "code1,code2,ageid,clogon,cntryid,email,fname,genid,lanid,lastlogon,lname,notes,seclevid,sesdata" .
                   ",startdate,subscribe,username,password,vpass,timezone";		   
				 
	     $farr = explode(',',$fields);
	     foreach ($farr as $t)
	       $title[] = localize($t,getlocal());
	       $titles = implode(',',$title);			 	 					
	     }	 	
		 
	     $out .= GetGlobal('controller')->calldpc_method("dataforms.getform use update.users+dataformsupdate+Post+Clear+$fields+$titles++id=$id+dummy");	  
	   
         return ($out);		 
	}
	
	function activate_user() {
	     $db = GetGlobal('db');	
		 $id = GetReq('rec');
		 
		 $sSQL = "update users set notes='ACTIVE' where id = " . $id;
		 //echo $sSQL;		 
         $db->Execute($sSQL);
         if($db->Affected_Rows()) {
		   SetGlobal('sFormErr',"ok");
		   return ($id);
		 }  
	     else {
		   SetGlobal('sFormErr',localize('_MSG18',getlocal()));			 
		   return false;
		 }  
	}
	
	function deactivate_user() {
	     $db = GetGlobal('db');	
		 $id = GetReq('rec');
		 
		 $sSQL = "update users set notes='DELETED' where id = " . $id;
		 //echo $sSQL;		 
         $db->Execute($sSQL);
         if($db->Affected_Rows()) {
		   SetGlobal('sFormErr',"ok");
		   return ($id);
		 }		 
	     else {
		   SetGlobal('sFormErr',localize('_MSG18',getlocal()));			 
		   return false;
		 }  
	}		
	
	function is_activated_user() {
	     $db = GetGlobal('db');	
		 $id = GetReq('rec');
		 
		 $sSQL = "select notes from users where id = " . $id;
		 //echo $sSQL;		 
         $result = $db->Execute($sSQL,2);
		 
		 $notes = $result->fields['notes'];
		 if (substr($notes,0,7)=='DELETED')
		   return false;
		 else
		   return true;  
	
	}
	
	function fetch_user_data($id, $fields=null) {
	     $db = GetGlobal('db');	
		 if ((!$id) || (!$fields)) return false;
		 
		 if (stristr($fields,'::')) {
		   $mfa = explode('::',$fields);//array of fields
		   $mf = str_replace('::',',',$fields);
		 }  
		 else {
           $mfa = $fields; //one element		 
		   $mf = $fields;
		 }
		 
		 
		 $sSQL = "select $mf from users where id = " . $id;
		 //echo $sSQL;		 
         $result = $db->Execute($sSQL,2);
		 
		 if (is_array($mfa)) {
		   foreach ($mfa as $i=>$f)
		     $ret[$f] = $result->fields[$f];
		 }
		 else
		   $ret = $result->fields[$mfa];
		 
         //echo $ret;
         //echo print_r($ret);
		 
		 return ($ret);  
	
	}	
	
	function activate_deactivate() {
	
	   if ($this->is_activated_user()) {
	   
	     $uid = $this->deactivate_user();
		 
		 if (($uid) && ($this->tell_deactivate)) {	 
		    $user_email = $this->fetch_user_data($uid,'email');
			
			$template= "userdeactivatetell.htm";
	        $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
		    //echo $t;
	        if (is_readable($t)) {
		      $mytemplate = file_get_contents($t);
			  $tokens[] = $user_email;
			  $mailbody = $this->combine_tokens($mytemplate,$tokens);
			  $this->mailto($this->tell_it, $user_email,$this->subj_activate,$mailbody);
			}
            else			
			  $this->send_mail($this->tell_it, $user_email,$this->subj_deactivate,$this->body_deactivate);
		 }		 
	   }	 
	   else {
	   
	     $uid = $this->activate_user();	 
		 
		 if (($uid) && ($this->tell_activate)) {
		    $user_email = $this->fetch_user_data($uid,'email');
			
			$template= "useractivatetell.htm";
	        $t = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template) ;
		    //echo $t;
	        if (is_readable($t)) {
		      $mytemplate = file_get_contents($t);
			  $tokens[] = $user_email;
			  $mailbody = $this->combine_tokens($mytemplate,$tokens);
			  $this->mailto($this->tell_it, $user_email,$this->subj_activate,$mailbody);
			}
            else
			  $this->send_mail($this->tell_it, $user_email,$this->subj_activate,$this->body_activate);		 
		 }
	   }	 
	}
	
};
}
?>