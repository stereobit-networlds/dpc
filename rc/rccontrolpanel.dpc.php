<?php

$__DPCSEC['RCCONTROLPANEL_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCCONTROLPANEL_DPC")) && (seclevel('RCCONTROLPANEL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCCONTROLPANEL_DPC",true);

$__DPC['RCCONTROLPANEL_DPC'] = 'rccontrolpanel';

/*$ajx = GetGlobal('controller')->require_dpc('gui/ajax.dpc.php');
require_once($ajx);*/
 
$__EVENTS['RCCONTROLPANEL_DPC'][0]='cp';
$__EVENTS['RCCONTROLPANEL_DPC'][1]='cplogout';
$__EVENTS['RCCONTROLPANEL_DPC'][2]='cplogin';
$__EVENTS['RCCONTROLPANEL_DPC'][3]='cpnitobi';
$__EVENTS['RCCONTROLPANEL_DPC'][4]='cpchartshow';
$__EVENTS['RCCONTROLPANEL_DPC'][5]='cpmenushow';
$__EVENTS['RCCONTROLPANEL_DPC'][6]='cpgaugeshow';

$__ACTIONS['RCCONTROLPANEL_DPC'][0]='cp';
$__ACTIONS['RCCONTROLPANEL_DPC'][1]='cplogout';
$__ACTIONS['RCCONTROLPANEL_DPC'][2]='cplogin';
$__ACTIONS['RCCONTROLPANEL_DPC'][3]='cpnitobi';
$__ACTIONS['RCCONTROLPANEL_DPC'][4]='cpchartshow';
$__ACTIONS['RCCONTROLPANEL_DPC'][5]='cpmenushow';
$__ACTIONS['RCCONTROLPANEL_DPC'][6]='cpgaugeshow';

$__DPCATTR['RCCONTROLPANEL_DPC']['cp'] = 'cp,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCCONTROLPANEL_DPC'][0]='RCCONTROLPANEL_DPC;Control Panel;Control Panel';
$__LOCALE['RCCONTROLPANEL_DPC'][1]='_BACKCP;Back;Επιστροφή';
$__LOCALE['RCCONTROLPANEL_DPC'][2]='_DASHBOARD;CP Dashboard;Πινακας Ελεγχου';
$__LOCALE['RCCONTROLPANEL_DPC'][3]='_MENU;General info;Βασικές πληροφορίες';
$__LOCALE['RCCONTROLPANEL_DPC'][4]='_statisticscat;Category Viewed/Month;Επισκεψιμότητα κατηγοριών';
$__LOCALE['RCCONTROLPANEL_DPC'][5]='_statistics;Items Viewed/Month;Επισκεψιμότητα ειδών';
$__LOCALE['RCCONTROLPANEL_DPC'][6]='_transactions;Transaction/Month;Συναλλαγές ανα μήνα';
$__LOCALE['RCCONTROLPANEL_DPC'][7]='_applications;Applications Birth/Month;Νεές εφαρμογές ανα μήνα';
$__LOCALE['RCCONTROLPANEL_DPC'][8]='_appexpires;Applications Expires/Month;Ληξεις εφαρμογών ανα μήνα';
$__LOCALE['RCCONTROLPANEL_DPC'][9]='_mailqueue;Mail send/Month;Σταλθέντα e-mail ανα μήνα';
$__LOCALE['RCCONTROLPANEL_DPC'][10]='_mailsendok;Mail Received/Month;Παρεληφθέντα e-mail ανα μήνα';
$__LOCALE['RCCONTROLPANEL_DPC'][11]='_income;Income;Εισόδημα';

//cpmdbrec commands
$__LOCALE['RCCONTROLPANEL_DPC'][80]='_exit;Exit;Έξοδος';
$__LOCALE['RCCONTROLPANEL_DPC'][81]='_dashboard;Dashboard;Πίνακας ελέγχου';
$__LOCALE['RCCONTROLPANEL_DPC'][82]='_logout;Logout;Αποσύνδεση';
$__LOCALE['RCCONTROLPANEL_DPC'][83]='_rssfeeds;RSS Feeds;RSS Feeds';
$__LOCALE['RCCONTROLPANEL_DPC'][84]='_edititemtext;Edit Item Text;Μεταβολή κειμένου (text) αντικειμένου';
$__LOCALE['RCCONTROLPANEL_DPC'][85]='_deleteitemattachment;Delete Item Attachment;Διαγραφή συνημμένου είδους';
$__LOCALE['RCCONTROLPANEL_DPC'][90]='_editcat;Edit Category;Μεταβολή κατηγορίας';
$__LOCALE['RCCONTROLPANEL_DPC'][91]='_addcat;Add Category;Νέα Κατηγορία';
$__LOCALE['RCCONTROLPANEL_DPC'][92]='_additem;Add Item;Νέο Είδος';
$__LOCALE['RCCONTROLPANEL_DPC'][93]='_webstatistics;Statistics;Στατιστικά';
$__LOCALE['RCCONTROLPANEL_DPC'][94]='_addcathtml;Add Category Html;Προσθήκη κειμένου (html) κατηγορίας';
$__LOCALE['RCCONTROLPANEL_DPC'][95]='_editcathtml;Edit Category Html;Μεταβολή κειμένου (html) κατηγορίας';
$__LOCALE['RCCONTROLPANEL_DPC'][96]='_edititem;Edit Item;Μεταβολή αντικειμένου';
$__LOCALE['RCCONTROLPANEL_DPC'][97]='_edititemphoto;Edit Photo;Προσθήκη/Μεταβολή φωτογραφίας αντικειμένου';
$__LOCALE['RCCONTROLPANEL_DPC'][98]='_edititemdbhtm;Edit Item Htm(db);Μεταβολή κειμένου (htm) αντικειμένου (db)';
$__LOCALE['RCCONTROLPANEL_DPC'][99]='_edititemdbhtml;Edit Item Html(db);Μεταβολή κειμένου (html) αντικειμένου (db)';
$__LOCALE['RCCONTROLPANEL_DPC'][100]='_edititemdbtext;Edit Item Text(db);Μεταβολή κειμένου (text) αντικειμένου (db)';
$__LOCALE['RCCONTROLPANEL_DPC'][101]='_senditemmail;Send Text/Html e-mail;Αποστολή e-mail';
$__LOCALE['RCCONTROLPANEL_DPC'][102]='_delitemattachment;Delete Text/Html(Db);Διαγραφή κειμένου (db)';
$__LOCALE['RCCONTROLPANEL_DPC'][103]='_edititemtext;Edit Item Text;Μεταβολή κειμένου (text) αντικειμένου';
$__LOCALE['RCCONTROLPANEL_DPC'][104]='_edititemhtm;Edit Item Htm;Μεταβολή κειμένου (htm) αντικειμένου';
$__LOCALE['RCCONTROLPANEL_DPC'][105]='_edititemhtml;Edit Item Html;Μεταβολή κειμένου (html) αντικειμένου';
$__LOCALE['RCCONTROLPANEL_DPC'][106]='_additemhtml;Add Item Html;Εισαγωγή κειμένου στο αντικείμενο';
$__LOCALE['RCCONTROLPANEL_DPC'][107]='_transactions;Transactions;Συναλλαγές';
$__LOCALE['RCCONTROLPANEL_DPC'][108]='_users;Users;Χρήστες';
$__LOCALE['RCCONTROLPANEL_DPC'][109]='_itemattachments2db;Add Item(s) Txt/Html to Database;Μεταφορά κειμένων στην βάση δεδομένων';
$__LOCALE['RCCONTROLPANEL_DPC'][110]='_importdb;Import Database;Εισαγωγή βάσης δεδομένων';
$__LOCALE['RCCONTROLPANEL_DPC'][111]='_config;Configuration;Ρυθμίσεις';
$__LOCALE['RCCONTROLPANEL_DPC'][112]='_contactform;Contact Form;Φόρμα επικοινωνίας';
$__LOCALE['RCCONTROLPANEL_DPC'][113]='_subscribers;Subscribers;Συνδρομητές';
$__LOCALE['RCCONTROLPANEL_DPC'][114]='_sitemap;Sitemap;Χάρτης αντικειμένων';
$__LOCALE['RCCONTROLPANEL_DPC'][115]='_search;Search;Φόρμα Αναζήτησης';
$__LOCALE['RCCONTROLPANEL_DPC'][116]='_upload;Upload files;Ανέβασμα αρχείων';
$__LOCALE['RCCONTROLPANEL_DPC'][117]='_uploadid;Upload item files;Ανέβασμα αρχείων αντικειμένου';
$__LOCALE['RCCONTROLPANEL_DPC'][118]='_uploadcat;Upload category files;Ανέβασμα αρχείων κατηγορίας';
$__LOCALE['RCCONTROLPANEL_DPC'][119]='_syncphoto;Sync photos;Συγχρονισμός φωτογραφιών';
$__LOCALE['RCCONTROLPANEL_DPC'][120]='_syncsql;Sync data;Συγχρονισμός δεδομένων';
$__LOCALE['RCCONTROLPANEL_DPC'][121]='_dbphoto;Image in DB;Εικόνα στην βάση δεδομένων';
$__LOCALE['RCCONTROLPANEL_DPC'][122]='_edithtml;Edit html files;Επεξεργασία σελίδων';
$__LOCALE['RCCONTROLPANEL_DPC'][123]='_awstats;Web statistics;Στατιστικά';
$__LOCALE['RCCONTROLPANEL_DPC'][124]='_google_analytics;Google Analytics;Στατιστικά Google';
$__LOCALE['RCCONTROLPANEL_DPC'][125]='_siwapp;Siwapp;Siwapp τιμολόγηση';
$__LOCALE['RCCONTROLPANEL_DPC'][126]='_MENU1;Size;Μέγεθος';
$__LOCALE['RCCONTROLPANEL_DPC'][127]='_MENU2;People;Συναλλασόμενοι';
$__LOCALE['RCCONTROLPANEL_DPC'][128]='_MENU3;Photos & attachments;Φωτογραφίες και έγγραφα';
$__LOCALE['RCCONTROLPANEL_DPC'][129]='_MENU4;Inventory;Αποθήκη';
$__LOCALE['RCCONTROLPANEL_DPC'][130]='_MENU5;Synchronize;Συγχρονισμοί';
$__LOCALE['RCCONTROLPANEL_DPC'][131]='_MENU6;Campaigns;Καμπάνιες';
$__LOCALE['RCCONTROLPANEL_DPC'][132]='_MENU7;Transactions;Συναλλαγές';
$__LOCALE['RCCONTROLPANEL_DPC'][133]='_add_categories;Upload Categories;Εισαγωγή κατηγοριών';
$__LOCALE['RCCONTROLPANEL_DPC'][134]='_add_products;Upload Products;Εισαγωγή ειδών';
$__LOCALE['RCCONTROLPANEL_DPC'][135]='_google_addwords;Google Adwords;Google Adwords';
$__LOCALE['RCCONTROLPANEL_DPC'][136]='_upload_logo;Upload logo;Ανέβασμα Λογοτύπου';
$__LOCALE['RCCONTROLPANEL_DPC'][137]='_add_recaptcha;ReCaptcha;ReCaptcha';
$__LOCALE['RCCONTROLPANEL_DPC'][138]='_update;Update;Αναβάθμιση';

class rccontrolpanel {

	var $title,$cmd,$subpath,$path,$dbpath,$prpath;
	var $_ctree,$_ctabstrip, $dashboard, $cp0_tabtype, $cpn_tabtype;
	
    static $firstrun = 0;
	var $charts, $hasgraph, $goto, $ajaxgraph, $refresh, $objcall, $objgauge, $hasgauge;
	var $charset;
	var $editmode;
	var $application_path;	
	var $environment, $url;
		
	function rccontrolpanel() {
		
	    $this->title = localize('RCCONTROLPANEL_DPC',getlocal());		
		$this->subpath = paramload('ID','hostinpath') . "/cp";
		//$this->prpath = paramload('SHELL','urlpath') . $this->subpath;//??		
		$this->path = paramload('SHELL','urlpath') . $this->subpath;   		
		//echo $this->path; global $config; print_r($config);
		$this->dbpath = paramload('SHELL','dbgpath');
		
		$this->prpath = paramload('SHELL','prpath');	
        $this->application_path = paramload('SHELL','urlpath');			
		//echo $this->prpath;
		
		$murl = arrayload('SHELL','ip');
        $this->url = $murl[0]; 			
		$this->editmode = GetReq('editmode');
		
        //choose encoding
        $char_set  = arrayload('SHELL','char_set');	  
        $charset  = paramload('SHELL','charset');	  		
		if (($charset=='utf-8') || ($charset=='utf8'))
		  $this->charset = 'utf-8';
		else  
	      $this->charset = $char_set[getlocal()]; 		
		
		
		$au = remote_paramload('RCCONTROLPANEL','autoupdate',$this->prpath);
        $this->autoupdate = $au?$au:3600;
		$this->dashboard = null;
		
		$this->cp0_tabtype = 'dom';//'iframe'; //first tab
		$this->cpn_tabtype = 'iframe'; //all others
		
		$this->ajaxgraph=1;
		$this->refresh = GetReq('refresh')?GetReq('refresh'):60;//0
		$this->goto = seturl('t=cp&group='.GetReq('group'));//handle graph selections with no ajax
		
        //READ ENVIRONMENT ATTR
		$this->environment = $_SESSION['env'] ? $_SESSION['env'] : $this->read_env_file(true);		
		
        if (defined('RCKATEGORIES_DPC'))		  
          $this->objcall['statisticscat'] = seturl('t=cpchartshow&group='.GetReq('group').'&ai=1&report=statisticscat&statsid=');
        if (defined('RCITEMS_DPC'))		  
          $this->objcall['statistics'] = seturl('t=cpchartshow&group='.GetReq('group').'&ai=1&report=statistics&statsid=');
        if (defined('RCMAILDBQUEUE_DPC')) {		  
          $this->objcall['mailqueue'] = seturl('t=cpchartshow&group='.GetReq('group').'&ai=1&report=mailqueue&statsid=');
		  //$this->objcall['mailsendok'] = seturl('t=cpchartshow&group='.GetReq('group').'&ai=1&report=mailsendok&statsid=');
		}  
		if (defined('RCTRANSACTIONS_DPC'))
          $this->objcall['transactions'] = seturl('t=cpchartshow&group='.GetReq('group').'&ai=1&report=transactions&statsid=');  		  
        if (defined('RCSAPPVIEW_DPC')) {		  
          $this->objcall['applications'] = seturl('t=cpchartshow&group='.GetReq('group').'&ai=1&report=applications&statsid=');
          $this->objcall['appexpires'] = seturl('t=cpchartshow&group='.GetReq('group').'&ai=1&report=appexpires&statsid=');		  		  		  
		} 
		 
		if (defined('RCTRANSACTIONS_DPC'))
          $this->objgauge['income'] = seturl('t=cpgaugeshow&group='.GetReq('group').'&ai=1&report=income&statsid=');						    		
	}
	 	
	
    function event($sAction) {    	  
	   
	   //if a remote user is in do not allow /cp actions
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('REMOTELOGIN')!='') die("Not allowed!");//	
	   /////////////////////////////////////////////////////////////	
	   
	   $this->autoupdate();	   	  		  			      
  
	   switch ($sAction) {
		 case 'cpmenushow': //if ($menu = GetReq('group')) {//ajax call
		                       //header("Content-Type", "text/html; charset=$this->charset");
		                       $this->hasmenu = $this->read_directory($this->path);
							   //$this->gotomenu = seturl('t=cpmenushow&group='.GetReq('group').'&ai=1&&statsid=');
							 //}
							 break; 
							 
		 case 'cpchartshow': if ($report = GetReq('report')) {//ajax call
		                       $this->hasgraph = GetGlobal('controller')->calldpc_method("swfcharts.create_chart_data use $report");
							   $this->goto = seturl('t=cpchartshow&group='.GetReq('group').'&ai=1&report='.$report.'&statsid=');
							 }
							 break;
							 							 	   
		 case 'cpgaugeshow': if ($report = GetReq('report')) {//ajax call
		                       $this->hasgauge = GetGlobal('controller')->calldpc_method("swfcharts.create_gauge_data use $report+where cid=0++1+400+300+meter");
							   $this->goto = seturl('t=cpgaugeshow&group='.GetReq('group').'&ai=1&report='.$report.'&statsid=');
							 }
							 break;	   
	   	
        case "cplogout"    :$this->logout();
		                    break;
		case "cplogin"     :$valid = $this->verify_login();
		                    $this->javascript();							  
							
		case "cpnitobi"    :if (GetSessionParam('LOGIN')=='yes')
		                      $this->read_directory($this->path);//echo $this->path;
		                    break;					
		case "cp"          :if (GetSessionParam('LOGIN')=='yes') { 
		                      $this->javascript();	
		                      $this->read_directory($this->path);//echo $this->path;
							}
		                    break;
       } 
    }
  
    function action($sAction) {
	  
      $tmpl = 'cpdashboard.htm';  
	  $t = $this->path . '/html/'. str_replace('.',getlocal().'.',$tmpl) ; 
	  //echo $t;
	  if (is_readable($t)) 
	    $this->dashboard = file_get_contents($t);	  		 
	   
	  //echo GetSessionParam('LOGIN'),"...";
	  if (GetSessionParam('LOGIN')=='yes') {
	      switch ($sAction) {
		    case 'cpmenushow': if ($this->hasmenu) //ajax call
		                          //$out = $this->show_directory_iconstable(4,3);
								  $out = $this->ajax_menu(4,3);//,null,1); //xmlout
							    else
							      $out = "<h3>".localize('_GNAVAL',0)."</h3>";	

							    die('menu|'.$out); //ajax return
								break;		  
		  
		    case 'cpchartshow': if ($this->hasgraph) {//ajax call
		                          $out = GetGlobal('controller')->calldpc_method("swfcharts.show_chart use " . GetReq('report') ."+500+240+$this->goto");								  
								}  
							    else
							      $out = "<h3>".localize('_GNAVAL',0)."</h3>";	

							    die(GetReq('report').'|'.$out); //ajax return
								break;
								
		    case 'cpgaugeshow': if ($this->hasgauge) {//ajax call
		                          $out = GetGlobal('controller')->calldpc_method("swfcharts.show_gauge use ". GetReq('report') ."+400+300");								  
								}  
							    else
							      $out = "<h3>".localize('_GNAVAL',0)."</h3>";	

							    die(GetReq('report').'|'.$out); //ajax return
								break;										  
		  	    
			default            : $out .= $this->controlpanel(4,3,$this->editmode); 
			  
		 } 		 		  
	  }  
	  else {  
	     //login....
		 
		 //if (!$out = GetGlobal('controller')->calldpc_method("fronthtmlpage.subpage use cplogin.htm+rccontrolpanel.logincp_form->".$this->nitobi.">1"))	  
	       // $out = $this->logincp_form($this->nitobi);  	
	  }	  
	
	 
	  return ($out);
    } 
	
	function javascript() {
      if (iniload('JAVASCRIPT')) {
	  
		   $js = new jscript;	  
	        
		   //auto refresh
	       if ($refresh = $this->refresh)
             $code = $this->javascript_refresh(seturl('t=cp&refresh='.$refresh),$refresh*1000);  

           if ($this->ajaxgraph) {		   
	         $code .= $this->ajaxinitjscall();
		     $js->setloadparams("init();");//call menu ajax		   		   		   		   
		   }
		   
           $js->load_js($code,"",1);			   
		   unset ($js);		   
	  }	
	}
	
	function ajaxinitjscall() {
        $gotomenu = seturl('t=cpmenushow&group='.GetReq('group').'&ai=1&&statsid=');				
	
		$out = "
function init()
{		 
  sndReqArg('$gotomenu'+statsid.value,'menu'); 		
}		
";					
        return ($out);
	}
	
   function javascript_refresh($page,$timeout=null) {	 
	  
     $mytimeout=$timeout?$timeout:5000;//5 sec
     $mytimeout2=$timeout?$timeout+2000:7000;//5 sec
     
     if ($this->ajaxgraph) {
	   
	   if (!empty($this->objcall)) {
	     $i = 0;
	     foreach ($this->objcall as $report=>$goto) {
	       $timeout = $mytimeout + (++$i*1000); //set delay 
           $ret .= "window.setInterval(\"sndReqArg('$goto'+statsid.value,'$report')\",$timeout);
";	 
         }
	   }
	   if (!empty($this->objgauge)) {
	     $j = 0;
	     foreach ($this->objgauge as $report=>$goto) {
	       $timeout = $mytimeout + (++$j*1500); //set delay 
           $ret .= "window.setInterval(\"sndReqArg('$goto'+statsid.value,'$report')\",$timeout);
";	 
         }	 
	   }  
	 }
	 else {
       $ret = "
function neu()
{	
	top.frames.location.href = \"$page\"
}
window.setTimeout(\"neu()\",$mytimeout);
";
	 }
	 return ($ret);
   }			
	
	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   //$template = $this->set_template();   		      
		   
	       //$code = $this->init_tabstrip();
	       $code = $this->_ctabstrip->tabstrip_init($this->cpn_tabtype);//'ajax');//null=iframe		   			
	   
		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.toolkit.js');		   
           $js->load_js('nitobi.tabstrip.js');
           $js->load_js('nitobi.tree.js');		   		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}	
	
   function tabstrip() {
	 $mytitle = (isset($this->dashboard))?localize('_DASHBOARD',getlocal()):'Control Panel';
  
     if ($this->cp0_tabtype=='dom')
	   $ret = $this->_ctabstrip->set_tabstrip($mytitle,'cp0',$this->cp0_tabtype,'nitobi','100%','700px','left','fade');
	 else
       $ret = $this->_ctabstrip->set_tabstrip($mytitle,'cp.php?t=cpnitobi',$this->cp0_tabtype,'nitobi','100%','700px','left','fade');
  
     return ($ret);
  }				
	  
  
  function controlpanel($type=4,$linemax=3,$nav_off=null,$win_off=null) {
     //echo '>',$this->editmode;
	 
	 if (!$nav_off) {
	     if ($gr=GetReq('group'))
	       $out = setNavigator(seturl("t=cp",$this->title),substr($gr,2));	 
	     else	 
           $out = setNavigator($this->title);//,$t);	   
	 }
	 
	 if (isset($this->dashboard)) {
			//all icons one token
			if ($this->editmode) 
			  $tokens[] = $this->site_stats();
		    else
			  $tokens[] = $this->show_directory_iconstable($type,$linemax);  	
			  
		    $out .= $this->combine_tokens($this->dashboard,$tokens);
	 }
	 else { 	  

	   if ($this->ajaxgraph) { //ajax
	     if ($this->editmode) {
		   //$panel = ....;
		 }  
		 else
		   $panel = GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use menu");
	   }	 
	   else	
	     $panel = $this->show_directory_iconstable($type,$linemax);
	   
	   if ($win_off) {
	     $out .= $panel;
	   }
	   else {	 		 		 
	     if ($this->editmode) {
		   $site_panel = $this->site_stats();
		   
		   $win1 = new window2(localize("_MENU",getlocal()),$site_panel,null,1,null,'SHOW',null,1);
	       $panel = $win1->render();
		   unset ($win1);		   
		 }
		 else {
		   $win1 = new window2(localize("_MENU",getlocal()),$panel,null,1,null,'SHOW',null,1);
	       $panel = $win1->render();
		   unset ($win1);
	     }

		 //multicolumn view	
		 
         $c = $this->_show_addon_tools() . $this->_show_charts();		 
		 $g = $this->_show_gauges();
		 
	     $data1[] = $this->_show_update_tools() . $panel . $this->_show_addons();
         $attr1[] = isset($c) ? (isset($g) ? "left;33%" : "left;50%") : "left;100%";	    
		
		 if (isset($g)) { 
			$data1[] = $g;
			$attr1[] = isset($c) ? "left;33%" : "left;50%";
		 }
		
		 if (isset($c)) { 
			$data1[] = $c;//$stats;//ts			
			$attr1[] = isset($d) ? "left;33%" : "left;50%";
		 }
		 
	     $swin = new window(localize("_DASHBOARD",getlocal()),$data1,$attr1);
	     $out .= $swin->render("center::100%::0::::center::0::2::");		 
	     //$swin = new window("Control Panel",$panel);
	     //$out .= $swin->render("center::50%::0::group_win_body::center::0::0::");	
	     unset ($swin);
		 
	     //HIDDEN FIELD TO HOLD STATS ID FOR AJAX HANDLE
	     $out .= "<INPUT TYPE= \"hidden\" ID= \"statsid\" VALUE=\"0\" >";	  		 			   		 
       }
	 }  
	 
     return ($out);	 
  } 
  
  function _show_charts() {
  
    //$stats = $this->_show_addon_tools(); //tools to enable
  
	if (!empty($this->objcall)) {  
		 
 		    foreach ($this->objcall as $report=>$goto) {//goto not used in this case
                $title = localize("_$report",getlocal()); //title
		        if ($this->ajaxgraph)  {//ajax
			        $ts = GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use $report");
			    }
		        elseif ($transtats = GetGlobal('controller')->calldpc_method("swfcharts.create_chart_data use transactions")) {
			        $ts = GetGlobal('controller')->calldpc_method("swfcharts.show_chart use $report+500+240+$this->goto");
			    }						
			    $win1 = new window2($title,$ts,null,1,null,'SHOW',null,1);
	            $stats .= $win1->render();
		        unset ($win1);								 	   
			}
	}

    return ($stats);		 
  }
  
  function _show_gauges() {
  
    if (!empty($this->objgauge)) {  
	
	    foreach ($this->objgauge as $report=>$goto) {
           $title = localize("_$report",getlocal()); //title

		   if ($this->ajaxgraph)  {//ajax
			 $ts = GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use $report");
		   }
		   elseif ($transtats = calldpc_method("swfcharts.create_gauge_data use $report+where cid=0++1+400+300+meter")) {
		     $ts = GetGlobal('controller')->calldpc_method("swfcharts.show_gauge use $report+400+300");
		   }
		      
		   $win1 = new window2($title,$ts,null,1,null,'SHOW',null,1);
           $chart .= $win1->render();
           unset ($win1);		 
	   }
    }
    return ($chart);	
  }
  
  function _show_addons() {  
  
    if (!empty($this->environment)) {    
      foreach ($this->environment as $mod=>$val) {
	    
		if ($val) {//enabled
		   $module = strtolower($mod);
		   switch ($module) {
		       case 'dashboard' : $text=null; break; //bypass
			   case 'ckfinder'  : $text=null; break; //bypass
			   
			   case 'edithtml'  : $text = $this->edit_html_files(); 
			                      break; 
			   
			   case 'menu'      : $text=null; break; //bypass

		       case 'awstats'   : //$text = "<a href='cgi-bin/awstats.php'>Awstats</a>";
                               $url = "cgi-bin/awstats.pl?config=".str_replace('www.','',$_ENV["HTTP_HOST"])."&framename=mainright#top";			   
					           $text .= "<IFRAME SRC=\"$url\" TITLE=\"awstats\" WIDTH=100% HEIGHT=400>
										<!-- Alternate content for non-supporting browsers -->
										<H2>Awstats</H2>
										<H3>iframe is not suported in your browser!</H3>
										</IFRAME>";			   
			                   break;			   
		       case 'siwapp' : $text = "<a href='../siwapp/'>Siwapp</a>"; 
			                   /*$url = "http://".str_replace('www.','',$_ENV["HTTP_HOST"])."/siwapp/";			   
					           $text .= "<IFRAME SRC=\"$url\" TITLE=\"siwapp\" WIDTH=100% HEIGHT=400>
										<!-- Alternate content for non-supporting browsers -->
										<H2>Siwapp</H2>
										<H3>iframe is not suported in your browser!</H3>
										</IFRAME>";	*/
			                   break;
		       default       : $text = null;//$val;
		   }
		  
		   if ($text) {
		     $mtitle = localize('_'.$module, getlocal());
		     $win1 = new window2($mtitle,$text,null,1,null,'SHOW',null,1);
             $addons .= $win1->render();
             unset ($win1);
		   }
		}  
      }		
	}

    return ($addons);	
  }
  
   function _show_addon_tools() {  
    //print_r($this->environment);
    $this->tools = array('google_analytics'=>1,'add_recaptcha'=>1,/*'add_addwords'=>1,*/
	                     'upload_logo'=>1,'add_categories'=>1,'add_products'=>1); 
  
    if (!empty($this->tools)) {    
      foreach ($this->tools as $tool=>$ison) {
        $text = null;
		$mytool = strtolower($tool);
		//echo $tool;		   
		if (($ison>0) && (isset($this->environment[strtoupper($tool)]))) {//enabled
		   switch ($mytool) {
		       case 'google_addwords'  : $text = "<a href='../analyr/'>Go to addwords</a>"; 
			                             break;		   
										 
		       case 'google_analytics' : $url = "http://analytics.google.com";	   
					                     $text .= "<IFRAME SRC=\"$url\" TITLE=\"analytics\" WIDTH=100% HEIGHT=400>
										<!-- Alternate content for non-supporting browsers -->
										<H2>Google analytics</H2>
							   			<H3>iframe is not suported in your browser!</H3>
										</IFRAME>";			   
			                             break;	
			   case 'add_categories':   if (defined('RCIMPORTDB_DPC'))
			                                $text = GetGlobal('controller')->calldpc_method('rcimportdb.upload_database_form use +++1');
			                            else
											$text = "<a href='cpimportdb.php?editmode=1&encoding=".$this->charset."'>Upload categories</a>"; 
			                            break;
               case 'add_products'  :	if (defined('RCIMPORTDB_DPC'))
			                                $text = GetGlobal('controller')->calldpc_method('rcimportdb.upload_database_form use +++1');
			                            else
											$text = "<a href='cpimportdb.php?editmode=1&encoding=".$this->charset."'>Upload products</a>"; 
			                            break;	
			   case 'upload_logo'   :	if (defined('RCUPLOAD_DPC')) {
			                                $text = GetGlobal('controller')->calldpc_method('rcupload.advanced_uploadform use +logo.png++images+');
											$text .= GetGlobal('controller')->calldpc_method('rcupload.advanced_uploadform use +pointer.png++images+');
										}	
			                            else
											$text = "<a href='cpupload.php?editmode=1&encoding=utf-8'><img src='../images/logo.png'/></a>"; 
			                            break;	
               case 'add_recaptcha' :  	//$text = "<a href='cpupload.php?editmode=1&encoding=utf-8'>reCAPTCHA ON!</a>";
			                            $text = "Recaptcha feature installed";
                                        break;			   
		       default              : //nothing
			                             $text = null;
		   }
		}
        elseif ($ison>0) {//disabled tool..enable it
		   switch ($mytool) {
		       case 'google_addwords'  : if ($e1 = $this->call_wizard_url('google_addwords'))
											$text = "<a href='$e1'>Enable addwords</a>"; 
										 else
 										    $text = "Unknown tool."; 
			                             break;		   
										 
		       case 'google_analytics' : if ($e1 = $this->call_wizard_url('google_analytics'))
											$text = "<a href='$e1'>Enable analytics</a>"; 
										 else
 										    $text = "Unknown tool.";
			                             break;
			   							 
			   case 'add_categories':    if ($e1 = $this->call_wizard_url('add_categories'))
											$text = "<a href='$e1'>Upload categories</a>"; 
										 else
 										    $text = "Unknown tool.";
			                             break;
               case 'add_products'  :    if ($e1 = $this->call_wizard_url('add_products'))
											$text = "<a href='$e1'>Upload products</a>"; 
										 else
 										    $text = "Unknown tool.";
			                             break;
               case 'upload_logo'   :    if ($e1 = $this->call_wizard_url('upload_logo')) {
											//$text = "<a href='$e1'>Change logo</a>"; 
											$text = "<a href='$e1'><img src='../images/logo.png'/></a>"; 												
										 }	
										 else
 										    $text = "Unknown tool.";
			                             break;									
               case 'add_recaptcha'  :	 if ($e1 = $this->call_wizard_url('add_recaptcha')) 
											$text = "<a href='$e1'>Add recaptcha entry feature</a>"; 	
										 else
 										    $text = "Unknown tool.";									 
										 break;
		       default                 : //nothing
			                             $text = null;
		   }		
        }
        //else disabled tool...		
		
		if ($text) {
		    $mtitle = localize('_'.$mytool, getlocal());
		    $win1 = new window2($mtitle,$text,null,1,null,'SHOW',null,1);
            $wtools .= $win1->render();
            unset ($win1);
		}		
      }	//foreach	
	}

    return ($wtools);	
  } 
  
   function _show_update_tools() {   
   
       $text = 'update';
	   
		if ($text) {
		    $mtitle = localize('_update', getlocal());
		    $win1 = new window2($mtitle,$text,null,1,null,'SHOW',null,1);
            $utools .= $win1->render();
            unset ($win1);
		}		   
	   return ($utools);
   }
  
  //as call_upgrade_ini into rcuwizard dpc
  protected function call_wizard_url($addon=null,$isupdate=false) {
        if (!$addon) return false;
		
		$upgrade_root_path = $this->prpath . '/../../cp/upgrade-app/';	
		$update_root_path = $this->prpath . '/../../cp/update-app/';
		
		$r_path = $isupdate ? $update_root_path : $upgrade_root_path;
		
	    $inifile = $r_path . "/cpwizard-".$addon.".ini";
		$target_inifile = $this->prpath . "/cpwizard-".$addon.".ini";
		$installed_inifile = str_replace('.ini','._ni',$target_inifile);
		//echo $inifile;
		
		if ((is_readable($target_inifile)) || ((is_readable($inifile)))){//already copied or fetch from root app

            $url = $isupdate ? seturl('t=cpwupdate&wf='.$addon) : 
			                   seturl('t=cpupgrade&wf='.$addon);		
		}
        else
            $url = false; 		
			
        return ($url);		
  }
  
  function read_directory($dirname) {
  
        if ($gr=GetReq('group'))
		  $dirname .= "/" . $gr;
  
        //echo $dirname;
	    if (is_dir($dirname)) {
          $mydir = dir($dirname);
		 
          while ($fileread = $mydir->read ()) {
	        
		   if (($fileread!='.') && ($fileread!='..'))  {
		   
                 //echo "<br>",$fileread;
				 		   
		         //read cp dirs
		         if ((is_dir($dirname."/".$fileread)) &&
		   		     ($fileread{0}==='c') &&
					 ($fileread{1}==='p')) {
					 
			         $ddir[] = $fileread;		 
		         } 		   
	             //read cp files
  	             if ((stristr ($fileread,".php")) &&
				     ($fileread{0}==='c') &&
					 ($fileread{1}==='p') &&
					 ($fileread != "cp.php")) {		   

		              $ddir[] = $fileread;						
					}
		   } 
	      }
	      $mydir->close ();
        }

		$this->cmds = $ddir;
		return ($ddir);
  }
  
  function show_directory()  {
  
	//if (defined('SMSGUI_DPC')) {
	  //$ret = GetGlobal('controller')->calldpc_method('smsgui.sendsms use aekara2+306936550848+1');
	  //echo $ret;
	//}	  

    if (is_array($this->cmds)) {
	
	  $actions = GetGlobal('__ACTIONS');	//print_r($actions); 
	
      foreach ($this->cmds as $id=>$name) {
	
	    $parts = explode(".",$name);
		
		if (isset($parts[1])) {//it means it is a file '.php'
		  $obj = strtoupper(str_replace("cp","rc",$parts[0])."_DPC");
		
		  if (defined($obj)) {
		    $cmd = $actions[$obj][0];
		    $title = localize($obj,getlocal());
	        
	        $ret .= $id.". " .seturl("t=".$cmd,$title) ."<br>"; 
		  }
	    }
		else {//it is a dir with other .php files
		   $t = GetReq('t');	
		   $group = $parts[0];
		   $title = substr($group,2); //exclude cp chars
		   $ret .= $id.". " .seturl("t=$t&group=".$group,$title) ."<br>";
		}
	  }	
	}
	
	//$ret .= seturl("t=cplogout","Logout");		
	if (GetSessionParam('REMOTELOGIN')) {
	  //$ret .= seturl("t=cpremotelogout","Logout");	
	  if ($gr=GetReq('group')) {
	    $t = GetReq('t'); 
        $ret .= seturl("t=$t","Back");		  
	  }	
	  else {
        $ret .= seturl("t=cpremotelogout","Logout");  
	  }			  
	}  
	else {
	
	  $ret .= "<hr>";
	
	  $ret .= seturl("t=senticodes","Send invitation codes");
	  $ret .= "<br>";
	  $ret .= seturl("t=exportdb","Export database schema");
	  $ret .= "<br>";
	  $ret .= seturl("t=crackermail","Web mail");	
	  $ret .= "<br>";	
	  	
	  //$ret .= seturl("t=cplogout","Logout"); 
	  if ($gr=GetReq('group')) {
	    $t = GetReq('t'); 
        $ret .= seturl("t=$t","Back");		  
	  }	
	  else {
        $ret .= seturl("t=cpremotelogout","Logout");  
	  }			   	
	}  
	
	return ($ret);
  }
  
  
  function show_directory_icons($type=0) {
	$t = GetReq('t');  
    $mygroup = GetReq('group');  
    $cpfile = @file_get_contents($this->prpath.'cpmenu.txt');
    $menu_direct_file = explode(',',$cpfile);  
  
    if (is_array($this->cmds)) {
		
	
	  $actions = GetGlobal('__ACTIONS');	//print_r($actions); 
	
      foreach ($this->cmds as $id=>$name) {
	
	    $parts = explode(".",$name);

		if (isset($parts[1])) {//it means it is a file '.php'		
		  $obj = strtoupper(str_replace("cp","rc",$parts[0])."_DPC");
		  $obj2 = strtolower($obj);
		  
		  if (defined($obj)) {
		    $cmd = $actions[$obj][0];
			$url = ($mygroup ? $mygroup.'/'.$name:$name); //as php file			
		    $title = localize($obj,getlocal());
	
	        if (in_array($parts[0],$menu_direct_file)) {

   		       $ret .= icon2("/icons/$obj2.gif",$url,$title,$type);
			}   
			else {
			
               $ret .= icon("/icons/$obj2.gif","t=".$cmd,$title,$type); 
			}  
		  }
	    }
		else {//it is a dir with other .php files
		   $group = $parts[0];
		   $title = substr($group,2); //exclude cp chars
		   if ($this->ajaxgraph) {
		     $goto = seturl('t=cpmenushow&group='.$group.'&ai=1&statsid=');
		     $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		     $ret .= "<a href=\"#\">". loadicon('/icons/'.$group.'.gif',$title,$js)."<br>$title</a>"; 			  
		   }			   
		   else 		   
		     $ret .= icon("/icons/$group.gif","t=$t&group=".$group,$title,$type);
		}		  
	  }
	}
	
	//$ret .= icon("/icons/cplogout.gif","t=cplogout","Logout",$type);	
	if (GetSessionParam('REMOTELOGIN')) {
	  //$ret .= icon("/icons/cplogout.gif","t=cpremotelogout","Logout",$type);	
	  if ($gr=GetReq('group')) {
		if ($this->ajaxgraph) {
		  $goto = seturl('t=cpmenushow&group=&ai=1&&statsid=');
   	      $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		  $items[] = "<a href=\"#\">". loadicon('/icons/cplogout.gif',localize('_BACKCP',getlocal()),$js)."<br>".localize('_BACKCP',getlocal())."</a>"; 			  
		}			   
		else
          $ret .= icon("/icons/cplogout.gif","t=$t",localize('_BACKCP',getlocal()),$type);		  
	  }	
	  else {
        $ret .= icon("/icons/cplogout.gif","t=cpremotelogout","Logout",$type);	  
	  }		
	}  
	else {
	
	  $ret .= "<hr>";
	
	  $ret .= icon("/icons/rcsenticodes_dpc.gif","t=senticodes","Send invitation codes",$type);
	  $ret .= icon("/icons/rcexportdb_dpc.gif","t=exportdb","Export database schema",$type);
	  //$ret .= icon("/icons/rcmailcracker_dpc.gif","t=crackermail","Web mail",$type);	    
	
		
	  //$ret .= icon("/icons/cplogout.gif","t=cplogout","Logout",$type);		
	  if ($gr=GetReq('group')) { 
		if ($this->ajaxgraph) {
		  $goto = seturl('t=cpmenushow&group=&ai=1&&statsid=');
   	      $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		  $items[] = "<a href=\"#\">". loadicon('/icons/cplogout.gif',localize('_BACKCP',getlocal()),$js)."<br>".localize('_BACKCP',getlocal())."</a>"; 			  
		}			   
		else		
          $ret .= icon("/icons/cplogout.gif","t=$t",localize('_BACKCP',getlocal()),$type);		  
	  }	
	  else {
        $ret .= icon("/icons/cplogout.gif","t=cplogout","Logout",$type);	  
	  }			  
	}  
	
	return ($ret);	
  }  
  
  function show_directory_iconstable($type=0,$linemax=4,$cmds=null) {
    $t = GetReq('t');     
    $mygroup = GetReq('group');
    $cpfile = @file_get_contents($this->prpath.'cpmenu.txt');
    $menu_direct_file = explode(',',$cpfile);
    //print_r($menu_direct_file);

    if ($cmds)
	  $this->cmds = unserialize($cmds);     
  
    if (is_array($this->cmds)) {
	
	  $actions = GetGlobal('__ACTIONS');	//print_r($actions); 
	
      foreach ($this->cmds as $id=>$name) {	
	    $parts = explode(".",$name);
		
		if (isset($parts[1])) {//it means it is a file '.php'				
		  $obj = strtoupper(str_replace("cp","rc",$parts[0])."_DPC");
		  $obj2 = strtolower($obj);
		  //echo $obj,"<br>";
		  if (defined($obj)) {
		    $cmd = $actions[$obj][0];//from cp as t=xxx
			$url = ($mygroup ? $mygroup.'/'.$name:$name); //as php file
		    $title = localize($obj,getlocal());// . "|<a href=\"#\" onclick=\"remove()\">X</a>";
	
	        if (in_array($parts[0],$menu_direct_file)) {

			  if ($this->ajaxgraph) {		  			  
				$items[] = icon2("/icons/$obj2.gif",$url,$title,$type); 
			  } 
			  else 			
			    $items[] = icon2("/icons/$obj2.gif",$url,$title,$type); 
			}  
			else {
			
			  if ($this->ajaxgraph) {
				$items[] = icon("/icons/$obj2.gif","t=".$cmd,$title,$type); 			  
			  }			   
			  else 			
			    $items[] = icon("/icons/$obj2.gif","t=".$cmd,$title,$type); 	
			}  
		  }
	    }
		else {//it is a dir with other .php files
		   $group = $parts[0];
		   $title = substr($group,2); //exclude cp chars
		   
		   if ($this->ajaxgraph) {
		     $goto = seturl('t=cpmenushow&group='.$group.'&ai=1&statsid=');
		     $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		     $items[] = "<a href=\"#\">". loadicon('/icons/'.$group.'.gif',$title,$js)."<br>$title</a>"; 			  
		   }			   
		   else 		   
		     $items[] = icon("/icons/$group.gif","t=$t&group=".$group,$title,$type);
		}		  
	  }
	}
	
	
	
	if (GetSessionParam('REMOTELOGIN')) {
	  //$items[] = icon("/icons/cplogout.gif","t=cpremotelogout","Logout",$type);		
	  if ($gr=GetReq('group')) {
		if ($this->ajaxgraph) {
		  $goto = seturl('t=cpmenushow&group=&ai=1&statsid=');
   	      $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		  $items[] = "<a href=\"#\">". loadicon('/icons/cplogout.gif',localize('_BACKCP',getlocal()),$js)."<br>".localize('_BACKCP',getlocal())."</a>"; 			  
		}			   
		else
          $items[] = icon("/icons/cplogout.gif","t=$t",localize('_BACKCP',getlocal()),$type);		  
	  }	
	  else {
        $items[] = icon("/icons/cplogout.gif","t=cpremotelogout","Logout",$type);	  
	  }			  
	}  
	else {
	
	  //$items[] = icon("/icons/rcsenticodes_dpc.gif","t=senticodes","Send invitation codes",$type);
	  //$items[] = icon("/icons/rcexportdb_dpc.gif","t=exportdb","Export database schema",$type);
	  //$items[] = icon("/icons/rcmailcracker_dpc.gif","t=crackermail","Web mail",$type);	    	
	
	  //$items[] = icon("/icons/cplogout.gif","t=cplogout","Logout",$type);		
	  if ($gr=GetReq('group')) {
		if ($this->ajaxgraph) {
		  $goto = seturl('t=cpmenushow&group=&ai=1&statsid=');
   	      $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		  $items[] = "<a href=\"#\">". loadicon('/icons/cplogout.gif',localize('_BACKCP',getlocal()),$js)."<br>".localize('_BACKCP',getlocal())."</a>"; 			  
		}			   
		else 
          $items[] = icon("/icons/cplogout.gif","t=$t",localize('_BACKCP',getlocal()),$type);		  
	  }	
	  else {
        $items[] = icon("/icons/cplogout.gif","t=cplogout","Logout",$type);	  
	  }			  
	}
	
	$itemscount = count($items);
	$timestoloop = floor($itemscount/$linemax)+1;
	$meter = 0;
	for ($i=0;$i<$timestoloop;$i++) {
	  //echo $i,"---<br>";
	  for ($j=0;$j<$linemax;$j++) {
	     //echo $i*$j,"<br>";
		 $viewdata[] = (isset($items[$meter])? $items[$meter] : "&nbsp");
		 $viewattr[] = "center;10%";	
		 $meter+=1;	 
	  }
	  
	  $myrec = new window('',$viewdata,$viewattr);
	  $ret .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	  unset ($viewdata);
	  unset ($viewattr);		  
	}
	
	
	return ($ret);	
  } 
  
  function ajax_menu($type=0,$linemax=4,$cmds=null,$xmlout=null) {
    $t = GetReq('t');     
    $mygroup = GetReq('group');
    $cpfile = @file_get_contents($this->prpath.'cpmenu.txt');
    $menu_direct_file = explode(',',$cpfile);
    //print_r($menu_direct_file);

    if ($cmds)
	  $this->cmds = unserialize($cmds);     
  
    if (is_array($this->cmds)) {
	
	  $actions = GetGlobal('__ACTIONS');	//print_r($actions); 
	
      foreach ($this->cmds as $id=>$name) {	
	    $parts = explode(".",$name);
		
		if (isset($parts[1])) {//it means it is a file '.php'				
		  $obj = strtoupper(str_replace("cp","rc",$parts[0])."_DPC");
		  $obj2 = strtolower($obj);
		  //echo $obj,"<br>";
		  if (defined($obj)) {
		    $cmd = $actions[$obj][0];//from cp as t=xxx
			$url = ($mygroup ? $mygroup.'/'.$name:$name); //as php file
		    $title = localize($obj,getlocal());// . "|<a href=\"#\" onclick=\"remove()\">X</a>";
	
	        //if (in_array($parts[0],$menu_direct_file)) {	//disable CPMENU.TXT	  			  
			if (is_readable($this->path.'/'.$url)) {
  			    //echo $this->path . '/'. $url,'<br>';			
				$items[] = icon2("/icons/$obj2.gif",$url,$title,$type); 
			}  
			else {
			    //echo $cmd,'<br>';
				$items[] = icon("/icons/$obj2.gif","t=".$cmd,$title,$type); 			  
			}  
		  }
	    }
		else {//it is a dir with other .php files
		   $group = $parts[0];
		   $title = substr($group,2); //exclude cp chars
		   $goto = seturl('t=cpmenushow&group='.$group.'&ai=1&statsid=');
		   $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		   $items[] = "<a href=\"#\">". loadicon('/icons/'.$group.'.gif',$title,$js)."<br>$title</a>"; 			  
		}		  
	  }
	}
	
	
	
	if (GetSessionParam('REMOTELOGIN')) {
	  //$items[] = icon("/icons/cplogout.gif","t=cpremotelogout","Logout",$type);		
	  if ($gr=GetReq('group')) {
		$goto = seturl('t=cpmenushow&group=&ai=1&statsid=');
   	    $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		$items[] = "<a href=\"#\">". loadicon('/icons/cplogout.gif',localize('_BACKCP',getlocal()),$js)."<br>".localize('_BACKCP',getlocal())."</a>"; 			  
	  }	
	  else {
        $items[] = icon("/icons/cplogout.gif","t=cpremotelogout","Logout",$type);	  
	  }			  
	}  
	else {	
	  if ($gr=GetReq('group')) {
        $goto = seturl('t=cpmenushow&group=&ai=1&statsid=');
   	    $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		$items[] = "<a href=\"#\">". loadicon('/icons/cplogout.gif',localize('_BACKCP',getlocal()),$js)."<br>".localize('_BACKCP',getlocal())."</a>"; 			  		  
	  }	
	  else {
        $items[] = icon("/icons/cplogout.gif","t=cplogout","Logout",$type);	  
	  }			  
	}
	
	if ($xmlout) {
	  $xml = new pxml(null,$this->charset);//'test');
      $xml->encoding = $this->charset;  
	  $xml->addtag('menu',null,null,"version=2.0");
	  
	  foreach ($items as $i=>$item)
	    $xml->addtag($i,'menu',$item,null);
		
	  $ret = $xml->getxml(1);	
	}
	else {
	  $itemscount = count($items);
	  $timestoloop = floor($itemscount/$linemax)+1;
	  $meter = 0;
	  for ($i=0;$i<$timestoloop;$i++) {
	  //echo $i,"---<br>";
	    for ($j=0;$j<$linemax;$j++) {
	     //echo $i*$j,"<br>";
		 $viewdata[] = (isset($items[$meter])? $items[$meter] : "&nbsp");
		 $viewattr[] = "center;10%";	
		 $meter+=1;	 
	    }
	  
	    $myrec = new window('',$viewdata,$viewattr);
	    $ret .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	    unset ($viewdata);
	    unset ($viewattr);		  
	  }
	}
	
	
	return ($ret);	
  }   
  
  
 function nitobi_menu($cp=0,$cs=0,$linemax=null,$cmds=null) {
    $linemax = $linemax?$linemax:4;
    $mygroup = GetReq('group');
    $cpfile = str_replace('\r\n','',@file_get_contents($this->path.'/cpmenu.txt'));
    $cdetail = @parse_ini_file($this->path.'/control_details.ini',1);
    $menu_group_file = explode(',',trim($cpfile));
	
	foreach ($menu_group_file as $i=>$args) {
	  //echo $args,'<br>';
	  $x = explode(':',trim(str_replace('\r\n','',$args)));
	  //[dpc]=group
	  //echo $x[0],'>',$x[1],'<br>';
	  $g[strtoupper($x[0]).'_DPC'] = $x[1];
	}  
	$a = count(g);
    //print_r($g);
	//echo $a;
	
	//VIEW BY LOCALES (DPC LOADED)
	$l = GetGlobal('__LOCALE'); //print_r($l);
	foreach ($l as $dpc=>$dpcdata) {
	  //echo $dpcdata[0],'<br>';
	  if ((substr($dpc,0,2)=='RC') && (substr($dpc,-4)=='_DPC')) {
	    //echo $dpc,'<br>';
		if ($group = $g[$dpc])
		  $menu[$group][] = $dpc;
		else    
          $menu[] = $dpc; 
	  } 	
	}  
	//echo '<pre>';
    //print_r($menu);
	//echo '</pre>';
	$actions = GetGlobal('__ACTIONS');
    //$details = $cdetail[$titles[$id]]['detail'];//???			
	
	if (is_array($menu)) {
      foreach ($menu as $id=>$dpcname) {
  	     if (is_array($dpcname)) {
		   foreach ($dpcname as $i=>$dpcn) {
		     $items[$id][] = $this->make_icon($dpcn,$actions,$details);
		   }	 
		   $items[$id][] = $this->make_icon('back');	 		   
		 }
		 else {
		   $items[] = $this->make_icon($dpcname,$actions,$details);
		 }
      } 
	  $items[] = $this->make_icon('logout');				
	}
	
	//print_r($items);
	
	foreach ($items as $it=>$item) {
  	     if (is_array($item)) {
		   $mgroup = null; 
		   foreach ($item as $i=>$groupicon) {		 
		     $mgroup .= $groupicon; 
		   }
	       //$win = new window2($it,$mgroup,null,1,null,'SHOW',null,1);
	       //$ret .= $win->render("center::100%::0::group_article_selected::left::0::0::");
	       //unset ($win);
		   $ret .= '<br>'.$it.'<hr>'.$mgroup;		   
		 }
		 else {
           $mroot.= $item;		 
		 }		 		  	
	}
	//$win = new window2('Menu',$mroot,null,1,null,'SHOW',null,1);
	//$ret .= $win->render("center::100%::0::group_article_selected::left::0::0::");
	//unset ($win);
	$ret .= '<br>'.'<hr>'.$mroot;		
	
	return ($ret);
  }
  
  function make_icon($dpcname,$actions=null,$details=null) {
  
	    if ($dpcname=='back') { 
		  $js = "onclick=\"add('".$title."','cp.php?t=".$cmd."')\""; 
		  $ret = "<a href=\"#\">".loadicon('/icons/cplogout.gif',localize('_BACKCP',getlocal()),$js)."</img>".localize('_BACKCP',getlocal())."</a>";		  		  
	    }	
	    elseif ($dpcname=='logout') { 
		  $js = "onclick=\"add('".$title."','cp.php?t=cplogout')\""; 
		  $ret = "<a href=\"cp.php?t=cplogout\">".loadicon('/icons/cplogout.gif','Logout')."</img>Logout</a>";		  	  
	    }  
		elseif (defined($dpcname)) {

		  $title = localize($dpcname,getlocal());			
	      $parts = explode("_",$dpcname);
		  $obj = strtolower($dpcname);		  
						
		  $file = strtolower(str_replace("RC","CP",$parts[0]).".php");
		  $cmdfile = $this->path .'/'. $file; //echo $cmdfile,'<br>';
		  
		  if (is_readable($cmdfile)) {//.php exist
		      $url = $file;

		      $js = "onclick=\"add('".$title."','".$url."')\""; 
			  $ret = "<a href=\"#\" alt=\"$title\">".loadicon('/icons/'.$obj.'.gif',$title,$js)."</img>$title</a>";
			  //echo $js."<br>";

		  }  
		  else {//.php doesn.t exist
			    $cmd = $actions[$dpcname][0];
			    //$nurl = seturl("t=".$cmd);
			    $js = "onclick=\"add('".$title."','cp.php?t=".$cmd."')\""; 
			    $ret = "<a href=\"#\">".loadicon('/icons/'.$obj.'.gif',$title,$js)."</img>$title</a>";
			    //echo $js."<br>";	
		  } 
		}  

		return ($ret);  
  }
  	    
  function show_directory_details($type=2,$cmds=null) {
	$t = GetReq('t');   
    $mygroup = GetReq('group');  
    $cpfile = @file_get_contents($this->prpath.'cpmenu.txt');
    $menu_direct_file = explode(',',$cpfile);
	  
    if ($cmds)
	  $this->cmds = unserialize($cmds);
  
    $type=2;
    $cdetail = @parse_ini_file($this->prpath.'control_details.ini',1);	
	//echo $this->prpath;
	//print_r($cdetail);  
  
    if (is_array($this->cmds)) {
	  
	  $actions = GetGlobal('__ACTIONS');	//print_r($actions); 
	
      foreach ($this->cmds as $id=>$name) {
	
	    $parts = explode(".",$name);
		
		if (isset($parts[1])) {//it means it is a file '.php'
		  $obj = strtoupper(str_replace("cp","rc",$parts[0])."_DPC");
		  $obj2 = strtolower($obj);
		  //echo $obj,"<br>";
		  if (defined($obj)) {
		    $cmd = $actions[$obj][0]; //from cp as t=xxx
			$url = $url = ($mygroup ? $mygroup.'/'.$name:$name);  //as php file			
		    $title = localize($obj,getlocal());
		    $titles[] = localize($obj,getlocal());
		    $cmds[] = seturl("t=".$cmd,$title);
	
	        if (in_array($parts[0],$menu_direct_file)) {	
		
			    $items[] = icon2("/icons/$obj2.gif",$url,$title,$type); 						
			}  
			else {  			
	            $items[] = icon("/icons/$obj2.gif","t=".$cmd,$title,$type); 
			}  
		  }
	    }
		else {//it is a dir with other .php files
		   $group = $parts[0];
		   $title = substr($group,2); //exclude cp chars
		   $titles[] = $title;
		   $cmds[] = seturl("t=$t&group=".$group,$title);		
		   if ($this->ajaxgraph) {
		     $goto = seturl('t=cpmenushow&group='.$group.'&ai=1&statsid=');
		     $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		     $items[] = "<a href=\"#\">". loadicon('/icons/'.$group.'.gif',$title,$js)."<br>$title</a>"; 			  
		   }			   
		   else		   
		     $items[] = icon("/icons/$group.gif","t=cp&group=".$group,$title,$type);
		}		  
	  }
	}
	
	if (GetSessionParam('REMOTELOGIN')) {
	  $items[] = icon("/icons/cplogout.gif","t=cpremotelogout","Logout",$type);		
	  
	  if ($gr=GetReq('group')) {
	    $title = localize('_BACKCP',getlocal());
	    $titles[] = localize('_BACKCP',getlocal());
		if ($this->ajaxgraph) {
		  $goto = seturl('t=cpmenushow&group=&ai=1&statsid=');
   	      $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		  $cmds[] = "<a href=\"#\">". loadicon('/icons/cplogout.gif',localize('_BACKCP',getlocal()),$js)."<br>".localize('_BACKCP',getlocal())."</a>"; 			  
		}			   
		else				
	      $cmds[] = seturl("t=$t",$title);	  
	  }	
	  else {
	    $title = "Exit";
	    $titles[] = "Exit";	  
	    $cmds[] = seturl("t=cpremotelogout",$title);	  
	  }	
	}  
	else {
	
	  $items[] = icon("/icons/rcsenticodes_dpc.gif","t=senticodes","Send invitation codes",$type);
	  $items[] = icon("/icons/rcexportdb_dpc.gif","t=exportdb","Export database schema",$type);
	  //$items[] = icon("/icons/rcmailcracker_dpc.gif","t=crackermail","Web mail",$type);	  //...IMPORETD AS REGULAR RC component
	
	  //$items[] = icon("/icons/cplogout.gif","t=cplogout","Logout",$type);		
	  if ($gr=GetReq('group')) {
		if ($this->ajaxgraph) {
		  $goto = seturl('t=cpmenushow&group=&ai=1&statsid=');
   	      $js = "onclick=\"sndReqArg('".$goto."'+statsid.value,'menu')\""; 
		  $items[] = "<a href=\"#\">". loadicon('/icons/cplogout.gif',localize('_BACKCP',getlocal()),$js)."<br>".localize('_BACKCP',getlocal())."</a>"; 			  
		}			   
		else 
          $items[] = icon("/icons/cplogout.gif","t=$t",localize('_BACKCP',getlocal()),$type);		  
	  }	
	  else {
        $items[] = icon("/icons/cplogout.gif","t=cplogout","Logout",$type);	  
	  }			
	}    
	//print_r($items);
    foreach ($items as $id=>$myitem) {
	
	  $details = $cdetail[$titles[$id]]['detail'];
	
	  $viewdata[] = $myitem;
	  $viewattr[] = "center;10%";
	  
	  $viewdata[] = "<B>" . $cmds[$id] . "</B><br>" . $details;
	  $viewattr[] = "left;90%";	  
	
	  $myrec = new window('',$viewdata,$viewattr);
	  $ret .= $myrec->render("center::100%::0::group_article_selected::left::0::0::");
	  unset ($viewdata);
	  unset ($viewattr);		  
	}	
	
	return ($ret);
  }     
  
  function logincp_form($nav_off=null,$tokens=null) {
	
	    if (!$nav_off) 
		  $out = setNavigator($this->title);
	 
	    if ($this->editmode)
		  $filename = seturl("t=cp&editmode=1",0,1);
		else
          $filename = seturl("t=cp",0,1);
	  	
		if ($tokens) {
		  $token[] = "<FORM action=". $filename . " method=post class=\"thin\">" . 
                      "<input type=\"text\" name=\"cpuser\" value=\"\" size=\"32\" maxlength=\"128\">";		  
		}  
		else {
          $toprint  = "<FORM action=". $filename . " method=post class=\"thin\">";
	      $toprint .= "<STRONG>Username:</STRONG>"; 
          $toprint .= "<input type=\"text\" name=\"cpuser\" value=\"\" size=\"32\" maxlength=\"128\"><br>";  		
		}
		
		if ($tokens) {
          $token[] = "<input type=\"password\" name=\"cppass\" value=\"\" size=\"32\" maxlength=\"128\">";		
		}
		else {
          $toprint .= "<STRONG>Password:</STRONG>"; 
	      $toprint .= "<input type=\"password\" name=\"cppass\" value=\"\" size=\"32\" maxlength=\"128\"><br>";
        }
		
		if ($tokens) {
	      $token[] = "<input type=\"submit\" name=\"Submit\" value=\"Ok\">" .
                     "<input type=\"hidden\" name=\"FormAction\" value=\"cplogin\">" .
		             "<input type=\"hidden\" name=\"AUTHENTICATE\" value=\"Login\">" .	
                     "</FORM>";	
				   
		  return ($token);		   		
		}
		else {
	      $toprint .= "<input type=\"submit\" name=\"Submit\" value=\"Ok\">"; 
          $toprint .= "<input type=\"hidden\" name=\"FormAction\" value=\"cplogin\">";
		
		  //enable AUTH
		  $toprint .= "<input type=\"hidden\" name=\"AUTHENTICATE\" value=\"Login\">";
				
          $toprint .= "</FORM>";	   
		
		
	      $swin = new window("Login",$toprint);
	      $out .= $swin->render("center::50%::0::group_dir_body::left::0::0::");	
	      unset ($swin);

          return ($out);
		}
  } 
  
  function verify_login() {
    //in case of instance app login goto root db
    $mydb = & GetGlobal('controller')->calldpc_method('database.switch_db use +1+1');  
	
    $db = GetGlobal('db');  
  
    if (($user=GetParam('cpuser')) && ($pwd=GetParam('cppass'))) {
	
	  //get running application info
	  $is_instance_app = paramload('ID','isinstance');
	  //echo $is_instance_app; 
	  $appname = paramload('ID','instancename');
	  //echo '>',$appname;
	  
	  //INSERT ROOT USER
	  //$sins = "insert into dpcmodules (user,pwd,appname) values ('root','rootvk7dp','root')";
      //$result = $db->Execute($sins,1);	  
	   
	  $sSQL .= "select user,pwd,appname from dpcmodules where user='$user' and pwd='$pwd' and active=1";
      $result = $mydb->Execute($sSQL,2);	
	  //echo $sSQL;
	  //print_r($result);
	  
	  //if username & password exists
	  if (($result->fields[0]==$user) && ($result->fields[1]==$pwd)) {
	  
	    //restore app db
        GetGlobal('controller')->calldpc_method('database.switch_db');//null = this app// use '.$appname); 	  

	    //must be instance and appname be correct
	    if (($is_instance_app) && ($result->fields[2]==$appname)) {
	      SetSessionParam('LOGIN','yes');	
		  SetSessionParam('USER',$user);	
		  return true;
		}//else is no instance (root app) appname=root  
		elseif ((!$is_instance_app) && ($result->fields[2]=='root')) {
		  SetSessionParam('LOGIN','yes');	
		  SetSessionParam('USER',$user);	
		  SetSessionParam('ADMIN','yes');
		  return true;
		}  
		else
		  return false;
	  }	
	}
	return false;
  } 
  
	function get_user_name($nopro=0) {
	
	  //return (GetSessionParam('USER'));
	  if (!$nopro)
	    $pro = "User:";
	  
	  if (GetSessionParam('LOGIN')) {
	  
	    if ($user=GetSessionParam('USER'))
	      $ret = $pro . $user;
	    else
	      $ret = null;
		
	    //echo $ret;		
	  }
	  
	  return $ret;	  
	}

  function show_tree($param=null) {
  
          ////////////////////////////////////////////////////////////////// tree test			
	      $out .= $this->_ctree->set_tree('sthandler.php?key='.GetReq('key'),'folders',300,100);
		  
		  return ('a'.$out);  
  }	  
	
  function sidewin() {
	  if (GetSessionParam('LOGIN')=='yes') {          

          $ret = GetGlobal('controller')->calldpc_method('rcsidewin.set_show_calldpc use rccontrolpanel.show_tree PARAMS cpitems');		  
		  
		  return ($ret);
	  }	  
  }
  
  function combine_tokens($template_contents,$tokens=null) {
	
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
  
  function autoupdate() {
     //echo $_SERVER['PHP_SELF'];
	 /*$rf = file_get_contents('http://www.stereobit.com/cp/cp.php');
	 $hf = file_get_contents($this->path .'/cp.php');
	 if (strlen($rf)!=strlen($hf)) {
	   echo 'must update...';
	 }*/
  }
  
	
  function getencoding() {

	  return ($this->charset);
  }
  
  function logout() {
  
    SetSessionParam('LOGIN',null);
	SetSessionParam('USER',null);
	//SetSessionParam('ADMIN',null); //to not propagated!?just close navigator window
  }
  
  protected function bytesToSize1024($bytes, $precision = 2) {
        $unit = array('B','KB','MB','GB');
        return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
  }  
 
 
  function filesize_r($path){
		if(!file_exists($path)) return 0;
	    if(is_file($path)) return filesize($path);
		$ret = 0;
		
		$glob = glob($path."/*");
		
		if (is_array($glob)) {
			foreach(glob($path."/*") as $fn)
				$ret += $this->filesize_r($fn);
		}	
		return $ret;

  } 
  
  function cached_disk_size($year=null, $month=null) {
  	   $path = $this->application_path; 
       $name = strval(date('Ymd'));
       $tsize = $this->prpath . $name . '-tsize.size';
	   $size = 0;
	   
	   $selected_name = sprintf('%04d',$year) . sprintf('%02d',$month);
	   for ($d=1;$d<31;$d++) {
	       $search_selected_name = $selected_name . sprintf('%02d',$d);
		   if (is_readable($this->prpath . $search_selected_name . '-tsize.size'))
		      $tsize = $this->prpath . $search_selected_name . '-tsize.size';
	   }
	   //else tsize of today...


       if (is_readable($tsize)) {
	        //echo $tsize;
			$size = file_get_contents($tsize);

	   }
	   else {
            $size = $this->filesize_r($path);
			@file_put_contents($tsize, $size);
	   }
	   
	   return ($size);
  }
  
  function cached_database_filesize($year=null, $month=null) {
    $db = GetGlobal('db'); 
    $name = strval(date('Ymd'));
    $dsize = $this->prpath . $name . '-dsize.size';	
	
	$selected_name = sprintf('%04d',$year) . sprintf('%02d',$month);
	for ($d=1;$d<31;$d++) {
	       $search_selected_name = $selected_name . sprintf('%02d',$d);
		   if (is_readable($this->prpath . $search_selected_name . '-dsize.size'))
		      $tsize = $this->prpath . $search_selected_name . '-dsize.size';
	}	
    
    if (is_readable($dsize)) {
	    //echo $dsize;
		$size = file_get_contents($dsize);

	}
	else {
		//$result = mysql_query( “SHOW TABLE STATUS” );
		$sSQL = "SHOW TABLE STATUS";
		$res = $db->Execute($sSQL,2);		
		//print_r($res);
		$size = 0;
		/*while( $row = mysql_fetch_array( $result ) ) {  
        $dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];
		} */
		if (!empty($res)) { 
			foreach ($res as $n=>$rec) {
				$size += $rec[ "Data_length" ] + $rec[ "Index_length" ];					
			}
		}
		@file_put_contents($dsize, $size);
	}	
		
	return ($size);
  }
  
  protected function select_timeline($year=null,$month=null,$nodropdown=false) {
	
	if ($nodropdown) {
        $ret = "<p><b>Year ($year):|";
		
		for ($y=2010;$y<=intval(date('Y'));$y++) {
		    $ret .= seturl('t=cp&month='.$month.'&year='.$y.'&editmode=1',$y) .'|';
		}
		$ret .= "<hr>Month ($month):|";	
		
		for ($m=1;$m<=12;$m++) {
		    $mm = sprintf('%02d',$m);
		    $ret .= seturl('t=cp&month='.$mm.'&year='.$year.'&editmode=1',$mm) .'|';
		}
		$ret .= "</b>";
		$ret .= '</p>';
        return ($ret);		
	}
	
	for ($mo=1;$mo<=12;$mo++) {
	  $id = sprintf('%02d',$mo);
      $s{$id} = ($id==$month) ? "selected=\"true\"" : null;
	  //echo "<br>s$id",'-',$mo,'-',$month,'>',$id,'>',$s{$id};
	}
  
    $form = "Selected period:
<form method=POST action=\"cp.php?t=cp&editmode=1\">
<select name=\"month\">
<option $s01 value=\"01\">Jan</option>
<option $s02 value=\"02\">Feb</option>
<option $s03 value=\"03\">Mar</option>
<option $s04 value=\"04\">Apr</option>
<option $s05 value=\"05\">May</option>
<option $s06 value=\"06\">Jun</option>
<option $s07 value=\"07\">Jul</option>
<option $s08 value=\"08\">Aug</option>
<option $s09 value=\"09\">Sep</option>
<option $s10 value=\"10\">Oct</option>
<option $s11 value=\"11\">Nov</option>
<option $s12 value=\"12\">Dec</option>
</select>
<select name=\"year\">
";

    for ($y=2010;$y<=intval(date('Y'));$y++) {

	   if ($year==$y)
	      $form .= "<option selected=\"true\" value=\"$y\">$y</option>";
	   else
		  $form .= "<option value=\"$y\">$y</option>";
    }
    $form .= "
</select>	
<!--input type=\"hidden\" name=\"FormAction\" value=\"cp\" /-->
<input type=\"submit\" value=\" OK \" />
</form>
";	

    return ($form);
  }
  
  function site_stats() {
		$db = GetGlobal('db'); 
        $year = GetParam('year') ? GetParam('year') : date('Y');
	    $month = GetParam('month') ? GetParam('month') : date('m');		
		if (GetReq('id')) //item selected..bypass ????
		    return null; 
			
		$ret .= $this->select_timeline($year,$month,1);//,true);//???? 
		//$ret .= "<hr>";
		
        $path = $this->application_path;//$this->path;// .'dpc'; //echo $path;
		$tsize = $this->cached_disk_size($year, $month);//$this->filesize_r($path);
		$tsize2 = $this->bytesToSize1024($tsize,1);
        $ret_a .= "<br>Folder size ". /*$tsize . '|' .*/ $tsize2;//." bytes";	

        $dsize = $this->cached_database_filesize($year, $month);	
		$dsize2 = $this->bytesToSize1024($dsize,1);	
        $ret_a .= "<br>Database size ". /*$dsize . '|' .*/ $dsize2;//." bytes";

		$total_size = $tsize + $dsize;
		$stotal = $this->bytesToSize1024($total_size,1);
		$ret_a .= "<br>Total used size ". $stotal;
		//$ret .= '<hr>';
		$win1 = new window2(localize("_MENU1",getlocal()),$ret_a,null,1,null,'HIDE',null,1);
	    $ret .= $win1->render();
		unset ($win1);	
		
		//if $total_size > ..50MB ...goto upgrade.....
		
        if (defined('RCKATEGORIES_DPC')) { //???	
		
            $sSQL = "select count(id) from users";
			$res = $db->Execute($sSQL,2);	         
			$ret_b .= '<br>Total users:'.$res->fields[0];	
			
            $sSQL = "select count(id) from users where subscribe=1";
			$res = $db->Execute($sSQL,2);	         
			$ret_b .= '<br>Total subscribers:'.$res->fields[0];				
			
            $sSQL = "select count(id) from customers";
			$res = $db->Execute($sSQL,2);	         
			$ret_b .= '<br>Total customers:'.$res->fields[0];						

            $sSQL = "select count(id) from ulists";
			$res = $db->Execute($sSQL,2);	         
			$ret_b .= '<br>Total mails in lists:'.$res->fields[0];			
            //$ret .= '<hr>';
		    $win1 = new window2(localize("_MENU2",getlocal()),$ret_b,null,1,null,'HIDE',null,1);
	        $ret .= $win1->render();
		    unset ($win1);				
			
            $sSQL = "select count(id) from pphotos where stype='SMALL'";
			$res = $db->Execute($sSQL,2);	         
			$ret_c .= '<br>Total photos in database (small):'.$res->fields[0];	
            $sSQL = "select count(id) from pphotos where stype='MEDIUM'";
			$res = $db->Execute($sSQL,2);	         
			$ret_c .= '<br>Total photos in database (medium):'.$res->fields[0];
            $sSQL = "select count(id) from pphotos where stype='LARGE'";
			$res = $db->Execute($sSQL,2);	         
			$ret_c .= '<br>Total photos in database (large):'.$res->fields[0];
			
            $sSQL = "select count(id) from pattachments";
			$res = $db->Execute($sSQL,2);	         
			$ret_c .= '<br>Total item attachments:'.$res->fields[0];			
            //$ret .= '<hr>';				
		    $win1 = new window2(localize("_MENU3",getlocal()),$ret_c,null,1,null,'HIDE',null,1);
	        $ret .= $win1->render();
		    unset ($win1);				
		}  
        if (defined('RCITEMS_DPC')) {		  
		    $sSQL = "select id,substr(sysins,1,4) as year,substr(sysins,6,2) as month from products where substr(sysins,1,4)='$year' and substr(sysins,6,2)='$month'";
			//$sSQL = " and ";
			$res = $db->Execute($sSQL,2);
			//echo $sSQL;
			//print_r ($res);
			$i=0;
			if (!empty($res)) { 
				foreach ($res as $n=>$rec) {
				    $i+=1;					
				}
			}
			$ret_d .= '<br>Items inserted this month:'.$i;
			
		    $sSQL = "select id,substr(sysupd,1,4) as year,substr(sysupd,6,2) as month from products where substr(sysupd,1,4)='$year' and substr(sysupd,6,2)='$month'";
			//$sSQL = " and ";
			$res = $db->Execute($sSQL,2);
			//echo $sSQL;
			//print_r ($res);
			$i=0;
			if (!empty($res)) { 
				foreach ($res as $n=>$rec) {
				    $i+=1;				
				}
			}
			$ret_d .= '<br>Items updated this month:'.$i;

            $sSQL = "select count(id) from products where itmactive=1 or active=101";
			$res = $db->Execute($sSQL,2);			
			$ret_d .= '<br>Total active items:'.$res->fields[0];	
            $sSQL = "select count(id) from products where itmactive=0 or active=0";//or...
			$res = $db->Execute($sSQL,2);			
			$ret_d .= '<br>Total inactive items:'.$res->fields[0];			
            $sSQL = "select count(id) from products";
			$res = $db->Execute($sSQL,2);			
			$ret_d .= '<br>Total items:'.$res->fields[0];
            //$ret .= '<hr>';	
		    $win1 = new window2(localize("_MENU4",getlocal()),$ret_d,null,1,null,'HIDE',null,1);
	        $ret .= $win1->render();
		    unset ($win1);				
		} 
        if (defined('RCITEMS_DPC')) {//???????SYNC DPC
		    $sSQL = "select id,status,sqlres,sqlquery,substr(date,1,4) as year,substr(date,6,2) as month from syncsql where substr(date,1,4)='$year' and substr(date,6,2)='$month'";
			//$sSQL = " and ";
			$res = $db->Execute($sSQL,2);
			//echo $sSQL;
			//print_r ($res);
			$i=0;
			$chars_send = 0;
			$noexec_syncs = 0;
			if (!empty($res)) { 
				foreach ($res as $n=>$rec) {
				    $i+=1;
                    $chars_send += strlen($rec['sqlquery']);
                    if (!$rec['status']) 
                        $noexec_syncs+=1;					
				}
			}
			$ret_e .= '<br>Syncs send this month:'.$i;
			$ret_e .= '<br>Syncs not executed this month:'.$noexec_syncs;			
			$ret_e .= '<br>Syncs data send this month:'.$this->bytesToSize1024($chars_send,1);

		    $sSQL = "select count(id) from syncsql where substr(date,1,4)='$year'";
			//echo $sSQL;
			$res = $db->Execute($sSQL,2);
			$ret_e .= '<br>Total syncs :'.$res->fields[0]; 	          				
            //$ret .= '<hr>';				
		    $win1 = new window2(localize("_MENU5",getlocal()),$ret_e,null,1,null,'HIDE',null,1);
	        $ret .= $win1->render();
		    unset ($win1);				
		}  		
        if (defined('RCMAILDBQUEUE_DPC')) {
		    $sSQL = "select id,body,active,status,mailstatus,sender,receiver,substr(timeout,1,4) as year,substr(timeout,6,2) as month from mailqueue where substr(timeout,1,4)='$year' and substr(timeout,6,2)='$month'";
			$sSQL .= " and active=0";
			$res = $db->Execute($sSQL,2);
			//echo $sSQL;
			//print_r ($res);
			$i=0;
			$chars_send = 0;
			if (!empty($res)) { 
				foreach ($res as $n=>$rec) {
				    $i+=1;
                    $chars_send += strlen($rec['body']);				
				}
			}
			$ret_f .= '<br>Mails send this month:'.$i;
			$ret_f .= '<br>Mails data send this month:'.$this->bytesToSize1024($chars_send,1);
			
		    $sSQL = "select count(id) from mailqueue where active=1";
			//echo $sSQL;
			$res = $db->Execute($sSQL,2);
			$ret_f .= '<br>Total mails in queue:'.$res->fields[0]; 			
			
		    $sSQL = "select count(id) from mailqueue where substr(timeout,1,4)='$year' and active=0";
			//echo $sSQL;
			$res = $db->Execute($sSQL,2);
			$ret_f .= '<br>Total mails send:'.$res->fields[0]; 	          				
            //$ret .= '<hr>';			
		    $win1 = new window2(localize("_MENU6",getlocal()),$ret_f,null,1,null,'HIDE',null,1);
	        $ret .= $win1->render();
		    unset ($win1);				
		}  
		if (defined('RCTRANSACTIONS_DPC')) {
		
		    $sSQL = "select tid,cid,tstatus,cost,costpt,payway,roadway,substr(tdate,1,4) as year,substr(tdate,6,2) as month from transactions where substr(tdate,1,4)='$year' and substr(tdate,6,2)='$month'";
			//$sSQL = " and ";
			$res = $db->Execute($sSQL,2);
			//echo $sSQL;
			//print_r ($res);
			$i=0;
			$pay_send = 0;
			$paynet_send = 0;
			if (!empty($res)) { 
				foreach ($res as $n=>$rec) {
				    $i+=1;
                    $paynet_send += floatval($rec['cost']);
                    $pay_send += floatval($rec['costpt']);					
				}
			}
			
			$ret_g .= '<br>Transactions this month:'.$i;
			$ret_g .= '<br>Monthly revenue (net):'.sprintf("%01.2f", $paynet_send); 	          
			$ret_g .= '<br>Monthly revenue:'.sprintf("%01.2f", $pay_send);	
			
		    $sSQL = "select sum(cost),sum(costpt) from transactions where substr(tdate,1,4)='$year'";
			//echo $sSQL;
			$res = $db->Execute($sSQL,2);
			$ret_g .= '<br>Total revenue (net):'.sprintf("%01.2f", $res->fields[0]); 	          
			$ret_g .= '<br>Total revenue (taxes included):'.sprintf("%01.2f", $res->fields[1]);			
			//$ret .= '<hr>';
		    $win1 = new window2(localize("_MENU7",getlocal()),$ret_g,null,1,null,'HIDE',null,1);
	        $ret .= $win1->render();
		    unset ($win1);				
		}  
        if (defined('RCSAPPVIEW_DPC')) {		  
         
		}

        return ($ret);     	
  }

	//read environment cp file
	protected function read_env_file($save_session=false) {
		$myenvfile = $this->prpath . 'cp.ini';
		$ret = @parse_ini_file($myenvfile ,false, INI_SCANNER_RAW);	

		if ($save_session)
		    SetSessionParam('env', $ret); 
		
		return ($ret);
    } 

    protected function edit_html_files() {
		$sourcedir = $this->prpath . '/html/';
	    $encoding = $this->charset;	
		
	    if (!is_dir($sourcedir)) 
		   return (false);		 

		  
        $mydir = dir($sourcedir);
        while ($fileread = $mydir->read ()) { 
	
           if (($fileread!='.') && ($fileread!='..') && (!is_dir($sourcedir.'/'.$fileread)) ) { 
			  if ((stristr($fileread,'.htm')) && (substr($fileread,0,2)!='cp'))  {//<<cpfilename restiction

				$weditfiles[] = $fileread;				
			  }
           }
        }
        $mydir->close ();
		
	    if (!empty($weditfiles)) {
		
		  foreach ($weditfiles as $i=>$file) {

            $plainfile	= str_replace('.html','', $file);	
		    $phpfile = str_replace('.html','.php', $file);
		    $htmlfile = urlencode(base64_encode($file));
		
		    $htmleditlink = "<a href='cpmhtmleditor.php?cke4=1&encoding=$encoding&editmode=1&htmlfile=$htmlfile' target='mainFrame'>".
			                $plainfile .
							"</a>"; 
		   	$ret .= '<br/>edit:' . $htmleditlink;
			
		  }
        }
        //else
          //$ret = 'no files to edit...';
		  
        return ($ret);  		
    }
};
}
?>