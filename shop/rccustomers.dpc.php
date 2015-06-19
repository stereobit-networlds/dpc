<?php

$__DPCSEC['RCCUSTOMERS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCCUSTOMERS_DPC")) && (seclevel('RCCUSTOMERS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCCUSTOMERS_DPC",true);

$__DPC['RCCUSTOMERS_DPC'] = 'rccustomers';

$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($a);
$b = GetGlobal('controller')->require_dpc('shop/shcustomers.dpc.php');
require_once($b);

$__EVENTS['RCCUSTOMERS_DPC'][0]='cpcustomers';
$__EVENTS['RCCUSTOMERS_DPC'][1]='delcustomer';
$__EVENTS['RCCUSTOMERS_DPC'][2]='regcustomer';
$__EVENTS['RCCUSTOMERS_DPC'][3]='cpcusmail';
$__EVENTS['RCCUSTOMERS_DPC'][4]='cpcusmsend';
$__EVENTS['RCCUSTOMERS_DPC'][5]='cpctype';
$__EVENTS['RCCUSTOMERS_DPC'][6]='insert2';
$__EVENTS['RCCUSTOMERS_DPC'][7]='signup3';
$__EVENTS['RCCUSTOMERS_DPC'][8]='updcustomer';
$__EVENTS['RCCUSTOMERS_DPC'][9]='saveupdcus';

$__ACTIONS['RCCUSTOMERS_DPC'][0]='cpcustomers';
$__ACTIONS['RCCUSTOMERS_DPC'][1]='delcustomer';
$__ACTIONS['RCCUSTOMERS_DPC'][2]='regcustomer';
$__ACTIONS['RCCUSTOMERS_DPC'][3]='cpcusmail';
$__ACTIONS['RCCUSTOMERS_DPC'][4]='cpcusmsend';
$__ACTIONS['RCCUSTOMERS_DPC'][5]='cpctype';
$__ACTIONS['RCCUSTOMERS_DPC'][6]='insert2';
$__ACTIONS['RCCUSTOMERS_DPC'][7]='signup3';
$__ACTIONS['RCCUSTOMERS_DPC'][8]='updcustomer';
$__ACTIONS['RCCUSTOMERS_DPC'][9]='saveupdcus';

$__DPCATTR['RCCUSTOMERS_DPC']['cpcustomers'] = 'cpcustomers,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCCUSTOMERS_DPC'][0]='RCCUSTOMERS_DPC;Customers;Customers';
$__LOCALE['RCCUSTOMERS_DPC'][1]='_reason;Reason;Αιτία';
$__LOCALE['RCCUSTOMERS_DPC'][2]='_cdate;Date in;Ημ/νία εισοδου';
$__LOCALE['RCCUSTOMERS_DPC'][3]='_price;Price;Τιμή';
$__LOCALE['RCCUSTOMERS_DPC'][4]='_ftype;Pay;Πληρωμή';
$__LOCALE['RCCUSTOMERS_DPC'][5]='_name1;First Name;Ονομα';
$__LOCALE['RCCUSTOMERS_DPC'][6]='_name2;Last Name;Επώνυμο';
$__LOCALE['RCCUSTOMERS_DPC'][7]='_kybismos;Kyb.;Κυβικα';
$__LOCALE['RCCUSTOMERS_DPC'][8]='_color;Color;Χρώμα';
$__LOCALE['RCCUSTOMERS_DPC'][9]='_extras;Extras;Εχτρα';
$__LOCALE['RCCUSTOMERS_DPC'][10]='_address;Address;Διεύθυνση';
$__LOCALE['RCCUSTOMERS_DPC'][11]='_tel;Tel.;Τηλέφωνο';
$__LOCALE['RCCUSTOMERS_DPC'][12]='_mob;Mobile;Κινητό';
$__LOCALE['RCCUSTOMERS_DPC'][13]='_mail;e-mail;e-mail';
$__LOCALE['RCCUSTOMERS_DPC'][14]='_fax;Fax;Fax';
$__LOCALE['RCCUSTOMERS_DPC'][15]='_ptype;Price type;Τύπος Τιμών';
$__LOCALE['RCCUSTOMERS_DPC'][16]='_name;Name;Όνομα';
$__LOCALE['RCCUSTOMERS_DPC'][17]='_afm;Vat ID;ΑΦΜ';
$__LOCALE['RCCUSTOMERS_DPC'][18]='_area;Area;Περιοχή';
$__LOCALE['RCCUSTOMERS_DPC'][19]='_prfdescr;Occupation;Επάγγελμα';

class rccustomers extends shcustomers {

    var $title;
	var $carr;
	var $msg;
	var $path;
	var $post;
	var $maillink;

	var $_grids;
	var $actcode;
	var $updrec;

	function rccustomers() {
	  $GRX = GetGlobal('GRX');
	  $this->title = localize('RCCUSTOMERS_DPC',getlocal());
	  $this->carr = null;
	  $this->msg = null;

	  $this->path = paramload('SHELL','prpath');

      $this->_grids[] = new nitobi("Customers");
	  $this->_grids[] = new nitobi("Transactions");	//must initialized althouth it handled by vehicles dpc

	  $this->maillink = seturl('t=cpcusmail&<@>');

      if ($GRX) {

          $this->delete = loadTheme('ditem',localize('_delete',getlocal()));
          $this->edit = loadTheme('eitem',localize('_edit',getlocal()));
          //$this->import = loadTheme('iitem',localize('_import',getlocal()));
          //$this->recode = loadTheme('ritem',localize('_recode',getlocal()));
          $this->add = loadTheme('aitem',localize('_add',getlocal()));
          $this->mail = loadTheme('mailitem',localize('_mail',getlocal()));
		  $this->type = loadTheme('iitem',localize('_ptype',getlocal()));

		  $this->sep = "&nbsp;";//loadTheme('lsep');
      }
      else {
          $this->delete = localize('_delete',getlocal());
          $this->edit = localize('_edit',getlocal());
          //$this->import = localize('_import',getlocal());
          //$this->recode = loadTheme('rvehicle','show help');
          $this->add = localize('_add',getlocal());
          $this->mail = localize('_mail',getlocal());
          $this->type = localize('_ptype',getlocal());

		  $this->sep = "|";
      }

	  $acode = remote_paramload('RCCUSTOMERS','activecode',$this->path);

	  $this->actcode = $acode?$acode:'code2';
	}

    function event($event=null) {

	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//
	   /////////////////////////////////////////////////////////////

	   switch ($event) {

         case "signup3"    :  if (!$this->checkFields())
		                        $this->insert();
							  $this->nitobi_javascript();
 			                  break;

		 case 'cpctype'    :  $this->make_cus_type();
			                  $this->nitobi_javascript();
			                       //$this->read_list();
			                       break;
	     case 'cpcusmsend'  : $this->send_mail();
                              $this->nitobi_javascript();
		                      //$this->carr = $this->select_customers('all',null,GetReq('alpha'));//dummy param
		                      break;
	     case 'cpcusmail'   :
		                      break;

	     case 'regcustomer' :
		                      break;
         case 'updcustomer' : $this->updrec = $this->getcustomer(GetReq('rec'),$this->actcode);
		                      break;
	     case 'saveupdcus'  : $this->update(GetReq('rec'),$this->actcode);
		                      $this->nitobi_javascript();
		                      break;
	     case 'delcustomer' : $this->delete_customer(GetReq('rec'),$this->actcode);
		                      $this->nitobi_javascript();
		                      //$this->carr = $this->select_customers('all',null,GetReq('alpha'));
							  break;
	     case 'cpcustomers' :
		 default            : $this->nitobi_javascript();
		                      //$this->carr = $this->select_customers('all',null,GetReq('alpha'));//dummy param
	   }

    }

    function action($action=null) {

	  if (GetSessionParam('REMOTELOGIN'))
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title);
	  else
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);

	  switch ($action) {
	     case 'cpcusmsend'  : $out .= $this->show_customers();
		                      break;
	     case 'cpcusmail'   : $out .= $this->show_mail();
		                      break;
	     case 'delcustomer' : $out .= $this->show_customers();
		                      break;
		 case 'regcustomer' : //$out .= $this->form();
		                      //$out .= $this->show_customers();
							  $out .= $this->makeform(null,1,'signup3');
							  break;
		 case 'updcustomer' :
							  $out .= $this->makeform($this->updrec,1,'saveupdcus',1);
							  break;
		 case 'saveupdcus'  :
		 case 'signup3'     :
		 case 'cpctype'     :
	     case 'cpcustomers' :
		 default            :
		                      $out .= $this->show_customers();
	 }

	 return ($out);
    }

	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   $template = $this->set_template();

	       $code = $this->init_grids();
		   $code .= $this->_grids[0]->OnClick(11,'Customerdetails',$template,'Transactions','cid',1);

		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.grid.js');
           $js->load_js($code,"",1);
		   unset ($js);
	  }
	}

	function set_template() {

	       //$edit = seturl("t=cpcmodify&cat=".GetReq('cat')."&rec=");
		   $add =  seturl("t=regcustomer");
		   $imp =  seturl("t=cpcimport");
		   $edit =  seturl("t=updcustomer&rec=");
		   $del =  seturl("t=delcustomer&rec=");
		   $mail = seturl("t=cpusmail&rec=");
		   $off =  seturl("t=cpctype&rec=");

		   //$template .= "<A href=\"$edit'+i1+'\">".$this->edit."</A>". $this->sep;
		   $template .= "<A href=\"$add\">".$this->add."</A>". $this->sep;
		   $template .= "<A href=\"$edit'+i1+'\">".$this->edit."</A>". $this->sep;
		   $template .= "<A href=\"$del'+i1+'\">".$this->delete."</A>". $this->sep;
		   $template .= "<A href=\"$mail'+i1+'\">".$this->mail."</A>". $this->sep;
		   $template .= "<A href=\"$off'+i1+'\">".$this->type."</A>". $this->sep;
		   $template .= "<br>";


		   //customer
		   $template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td width=\"30%\">";
		   $template .= localize('a/a',getlocal()).":</br>";
		   $template .= localize('_code',getlocal()).":</br>";
		   $template .= localize('_name',getlocal()).":</br>";
		   $template .= localize('_afm',getlocal()).":</br>";
		   $template .= localize('_address',getlocal()).":</br>";
		   $template .= localize('_area',getlocal()).":</br>";
		   $template .= localize('_tel',getlocal()).":</br>";
		   $template .= localize('_tel',getlocal()).":</br>";
		   $template .= localize('_fax',getlocal()).":</br>";
		   $template .= localize('_mail',getlocal()).":</br>";
		   $template .= localize('_prfdescr',getlocal()).":</br>";
		   $template .= "</td><td width=\"70%\">";
		   $template .= "'+i0+'<br>" . "'+i1+'<br>" . "'+i2+'<br>" . "'+i3+'<br>" .
		                "'+i4+'<br>" . "'+i5+'<br>" ."'+i6+'<br>" . "'+i7+'<br>" .
						"'+i8+'<br>" . "'+mailto(i9,5)+'" . "<br>'+i10+'<br>";
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


	function delete_customer($id,$key=null) {
        $db = GetGlobal('db');

		$sSQL = "delete from customers where ";
		if ($key)
		  $sSQL .= $key . "=" . $id;//'' must added to param
		else
		  $sSQL .= "email = " . $db->qstr($id);

        $db->Execute($sSQL,1);
	    //echo $sSQL;

		$this->msg = "Customer with $key=$id deleted!";
	}


	function show_customers() {
     	   $sFormErr = GetGlobal('sFormErr');

	   if ($this->msg) $out = $this->msg;
	   /*
	   $myadd = new window('',seturl("t=regcustomer","Register a new customer!"));
	   $toprint .= $myadd->render("center::100%::0::group_article_selected::right::0::0::");
	   unset ($myadd);
	   */
         $toprint .= $sFormErr;


	   $toprint .= $this->show_grids();

	   $toprint .= $this->alphabetical();

	   $dater = new datepicker("/MDYT");
	   $toprint .= $dater->renderspace(seturl("t=cpcustomers"),"cpcustomers");
	   unset($dater);

       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render();

	   return ($out);
	}

	function alphabetical($command='cpcustomers') {

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

     $myaction = seturl("t=regcustomer");

     if ($this->post==true) {

	   SetSessionParam('REGISTERED_CUSTOMER',1);
	 }
	 else { //show the form plus error if any

       //if (!$action) $out = setNavigator($this->title);

       $out .= setError($sFormErr . $this->msg);


	   $form = new form(localize('_ADDEVENT',getlocal()), "regcustomer", FORM_METHOD_POST, $myaction, true);

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
	     $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "regcustomer"));

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


	   $grid0_get = "shhandler.php?t=shngetcustomerslist&alpha=$alpha&apo=$apo&eos=$eos&filter=$filter";
	   $grid0_set = "shhandler.php?t=shnsetcustomers";

	   $this->_grids[0]->set_text_column("ID","id","50","true");
	   $this->_grids[0]->set_text_column(localize('_code',getlocal()),"code2","70","true");
	   $this->_grids[0]->set_text_column(localize('_name',getlocal()),"name","200","true");
	   $this->_grids[0]->set_text_column(localize('_afm',getlocal()),"afm","150","true");
	   $this->_grids[0]->set_text_column(localize('_address',getlocal()),"address","100","true");
	   $this->_grids[0]->set_text_column(localize('_area',getlocal()),"area","100","true");
	   $this->_grids[0]->set_text_column(localize('_tel',getlocal()),"voice1","100","true");
	   $this->_grids[0]->set_text_column(localize('_tel',getlocal()),"voice2","100","true");
	   $this->_grids[0]->set_text_column(localize('_fax',getlocal()),"fax","100","true");
	   $this->_grids[0]->set_text_column(localize('_mail',getlocal()),"mail","150","true");
	   $this->_grids[0]->set_text_column(localize('_prfdescr',getlocal()),"prfdescr","150","true");
	   $this->_grids[0]->set_text_column(localize('_attr1',getlocal()),"attr1","150","true");


       $datattr[] = $this->_grids[0]->set_grid_remote($grid0_get,$grid0_set,"400","460","livescrolling",17) . $this->searchinbrowser();
	   $viewattr[] = "left;50%";

	   //details
	   //$message = "...";
	   $add =  seturl("t=regcustomer");
       $message = "<A href=\"$add\">".$this->add."</A>";//. $this->sep;

	   $wd .= $this->_grids[0]->set_detail_div("CustomerDetails",400,360,'F0F0FF',$message);
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
       $ret = $this->_grids[0]->set_grid_remote($grid1_get,"","400","100","livescrolling",null,"false");

	   return ($ret);
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

	function send_mail() {

	   if (!defined('ABCMAIL_DPC')) return;

	   $from = GetParam('from');
	   $to = GetParam('to');
	   $subject = GetParam('subject');
	   $body = GetParam('mail_text');

	   if ($res = GetGlobal('controller')->calldpc_method('abcmail.sendit use '.$from.'+'.$to.'+'.$subject.'+'.$body))
	     $this->mailmsg = "Send successfull";
	   else
	     $this->mailmsg = "Send failed";
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

	function make_cus_type() {
        $db = GetGlobal('db');
		$mycode = $this->actcode;

	    $sSQL = "select attr1 from customers where $mycode=".GetReq('rec');
		$ret = $db->Execute($sSQL,2);

		switch ($ret->fields[0]) {
		  case $this->reseller_attr  : $sw = ''; break;
		  default                    : $sw = $this->reseller_attr ;
		}
		//echo $sSQL,$sw,'>',$ret->fields[0];

	    $sSQL = "update customers set attr1="."'$sw' where $mycode=".GetReq('rec');
		$db->Execute($sSQL,1);
		//reset session of user
		$sSQL = "update users set sesdata='' where $mycode=".GetReq('rec');
		$db->Execute($sSQL,1);
        //echo $sSQL;
		$this->msg = "Job completed!(Customer type: $sw)";
	}

};
}
?>