<?php
$__DPCSEC['LOCKMYV2_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("LOCKMYV2_DPC")) && (seclevel('LOCKMYV2_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("LOCKMYV2_DPC",true);

$__DPC['LOCKMYV2_DPC'] = 'lockmyv2';

$a = GetGlobal('controller')->require_dpc('networlds/lockmy.dpc.php');
require_once($a);

GetGlobal('controller')->get_parent('LOCKMY_DPC','LOCKMYV2_DPC');

//$__EVENTS['LOCKMYV2_DPC'][15]='demorenew';

//$__ACTIONS['LOCKMYV2_DPC'][15]='demorenew';

//$__LOCALE['LOCKMYV2_DPC'][0]='LOCKMYV2_DPC;Lockmy records;Lockmy records';

class lockmyv2 extends lockmy  {

	function lockmyv2() {
	  $UserSecID = GetGlobal('UserSecID'); 	
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	 
	  
      lockmy::lockmy();	 
	  
	  if (!$tmzid = GetSessionParam('tmzid')) {
	  
	    if (defined('SHUSERS_DPC')) {
	      $tmz_id = GetGlobal('controller')->calldpc_method('shuser.get_user_timezone');
		  $tmzid = $tmz_id?$tmz_id:'0'; //string 0 in case if GMT
          SetSessionParam('tmzid',$tmzid);		  
	    }
	    else
	      $tmzid = 0;//pre select GMT	  
	   }
	   else
	     $tmzid = 0;//pre select GMT	  
	   	
	   $this->tmz_id = $tmzid;	
	  					    	  
	}
	
	function event($event=null) { 			
	      
	    switch ($event) {

		  default                   : lockmy::event($event);									  
		}							  
	}		
	
	function action($action=null) {		
	  
	    switch ($action) {

		  default                   : $out = lockmy::action($action);									  
		}	  
	  
	    return ($out);
	}
	
	//override 
	function insert_form() {
		 $apptype = decode(GetGlobal('UserID'));
		 	
	     $today = date('Y-m-d');
	     $sd = date('mdY');
	     $yesterday  = mktime(0, 0, 0, date("m")-1  , date("d"), date("Y"));
	     $td = date('mdY',$yesterday);
		   
	     $tmz_today = $this->make_gmt_date(null,$this->tmzid);  		   		 
		 		 
		 $expgoto = 'http://lockmy.networlds.org/rexpired.html';
		 $rengoto = 'http://lockmy.networlds.org/rrenew.html';
		 $account = $apptype;
		 
         $out = $this->cmdline(80,null,20); 

         $myaction = seturl("t=saveapp");	


	     $form = new form(localize('LOCKMY_DPC',getlocal()), "LOCKMY", FORM_METHOD_POST, $myaction, false);
		 
         $form->addGroup  ("x",	"Please enter application details.");	
         $form->addGroup  ("y",	"Please enter configuration details.");
         $form->addGroup  ("p",	"Please enter renew details.");		 
         $form->addGroup  ("z",	"Please enter notification details.");		 		 
		 	 
         $form->addElement("x", new form_element_text("http://",  "name",		GetParam('name'),			"forminput",			50,				50,	0));		   		     
         $form->addElement("x", new form_element_checkbox("Enabled",  "enable",		1,			"forminput"));
		 
		 $dtmz = get_selected_option_fromfile($this->def_tmz,'timezones');
	     $form->addElement("x",	new form_element_combo_file (localize('TimeZone',getlocal()),     "zone",  $dtmz,				"forminput",	        5,				0,	'timezones'));			 
		 
         //$form->addElement("x", new form_element_date("Expire",  "LOCKMY",		"expire",         $today,			"forminput",			20,				10,	0));			 
		 $form->addElement("x", new form_element_text("Expire",  "expire",		$tmz_today,   		 "datechooser dc-dateformat='Y-n-j' dc-iconlink='images/datechooser.png' dc-weekstartday='1' dc-startdate='$sd' dc-latestdate='07312020' dc-earliestdate='$td'",			20,				10,	0));		 
         $form->addElement("x", new form_element_text("Gracedays",  "gdays",	GetParam('gdays')?GetParam('gdays'):"0",			"forminput",			3,				3,	0));		 		 
		 
	     //$form->addElement("y",	new form_element_combo_file ("Application to copy from",     "app2copy",	    '',				"forminput",	        5,				0,	'applist'));
         $form->addElement("y", new form_element_checkbox("Silent mode",  "silence",	GetParam('silence')?GetParam('silence'):0,			"forminput"));		 			 			 
         $form->addElement("y", new form_element_text("Promt",  "pass",	GetParam('pass')?GetParam('pass'):"",			"forminput",			50,				50,	0));		 			 
         $form->addElement("y", new form_element_text("When expires goto",  "expgoto",	GetParam('expgoto')?GetParam('expgoto'):$expgoto,			"forminput",			50,				50,	0));		 
         $form->addElement("y", new form_element_text("When renew goto",  "rengoto",	GetParam('rengoto')?GetParam('rengoto'):$rengoto,			"forminput",			50,				50,	0));			 		 
		 
         $form->addElement("p", new form_element_checkbox("Enable inhouse pay",  "usepay",		GetParam('usepay')?GetParam('usepay'):0,			"forminput"));		 			 			 		 
         $form->addElement("p", new form_element_text("Cost",  "payval",	GetParam('payval'),			"forminput",			50,				50,	0));			 			 		 		 
         $form->addElement("p", new form_element_text("Code",  "paycode",		GetParam('paycode'),			"forminput",			50,				50,	0));			 			 		 		 
		 $form->addElement("p", new form_element_text("Details",  "payitem",		GetParam('payitem'),			"forminput",			50,				50,	0));			 			 		 
		 $form->addElement("p", new form_element_text("Method",  "paytype",     'PAYPAL',			"forminput",			50,				50,	1));			 			 		 			 		 
         $form->addElement("p", new form_element_text("Account",  "payaccount",  GetParam('payaccount')?GetParam('payaccount'):$account,			"forminput",			50,				50,	0));		 
		 
         $form->addElement("z", new form_element_checkbox("Enable",  "usemail",		GetParam('usemail')?GetParam('usemail'):1,			"forminput"));		 			 			 		 
         $form->addElement("z", new form_element_text("Owner e-mail",  "ownermail",	GetParam('ownermail'),			"forminput",			50,				50,	0));			 			 		 		 
         $form->addElement("z", new form_element_text("CC #1",  "cc1",		GetParam('cc1'),			"forminput",			50,				50,	0));			 			 		 		 
		 $form->addElement("z", new form_element_text("CC #2",  "cc2",		GetParam('cc2'),			"forminput",			50,				50,	0));			 			 		 
		 $form->addElement("z", new form_element_text("Subject when in grace period",  "subject",GetParam('subject')?GetParam('subject'):$this->notify_subject,			"forminput",			50,				50,	0));			 			 		 			 		 
         $form->addElement("z", new form_element_text("Message when in grace period",  "message",GetParam('message')?GetParam('message'):$this->notify_message,			"forminput",			50,				50,	0));
		 $form->addElement("z", new form_element_text("Subject when expired",  "altsubject",GetParam('altsubject')?GetParam('altsubject'):$this->notify_altsubject,			"forminput",			50,				50,	0));			 			 		 			 		 
         $form->addElement("z", new form_element_text("Message when expired",  "altmessage",GetParam('altmessage')?GetParam('altmessage'):$this->notify_altmessage,			"forminput",			50,				50,	0));		 			 			 		 		 
         $form->addElement("z", new form_element_text("Send SMS to",  "sms",GetParam('sms'),			"forminput",			50,				50,	0));			 			 		 		 
		 
		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("type", $apptype));		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "installapp"));	   	   
	   
	     $ret .= $form->getform ();			

	   /*}
	   else	
	     $ret = "Invalid record!";*/
	   $msg = $this->msg ? "Add URL".$this->msg : "Add URL";		 
			
       $mywin = new window($msg,$ret);
       $out .= $mywin->render("center::100%::0::group_win_body::center::0::0::");		 
				
		
	   return ($out);
	}	
	
	//override
	function update_form() {  	
	   $sd = date('mdY');
	   $yesterday  = mktime(0, 0, 0, date("m")-1  , date("d"), date("Y"));
	   $td = date('mdY',$yesterday);
	   
       $out = $this->cmdline(80,null,20); 	 
	   
	   //if ($this->msg) $out .= $this->msg; 	     	   
	   	
	   //print_r($this->record);
	   if (is_array($this->record)) {
         $myaction = seturl("t=saveapp");	

	     $form = new form(localize('LOCKMY_DPC',getlocal()), "LOCKMY", FORM_METHOD_POST, $myaction, false);
		 
         $form->addGroup  ("x",	"Please enter application details.");		 
         $form->addGroup  ("y",	"Please enter configuration details.");
         $form->addGroup  ("p",	"Please enter renew details.");		 
         $form->addGroup  ("z",	"Please enter notification details.");
		 		 		 
         $form->addElement("x", new form_element_text("http://",  "name",	        GetParam('name')?GetParam('name'):$this->record[0],			"forminput",			50,				50,	1));		   		     
         $form->addElement("x", new form_element_checkbox("Enabled",  "enable",		GetParam('enable')?GetParam('enable'):$this->record[8],			"forminput"));			 			 
		 
		 $selectedtz = get_selected_option_fromfile($this->record[3],'timezones');
	     $form->addElement("x",	new form_element_combo_file (localize('TimeZone',getlocal()),     "zone",   $this->record[3],				"forminput",	        5,				0,	'timezones'));			 
		 
         //$form->addElement("x", new form_element_date("Expire",  "LOCKMY",      "expire",		$this->record[4],			"forminput",			20,				10,	0));			 
         $form->addElement("x", new form_element_text("Expire",  "expire",		GetParam('expire')?GetParam('expire'):$this->record[4],   		 "datechooser dc-dateformat='Y-n-j' dc-iconlink='images/datechooser.png' dc-weekstartday='1' dc-startdate='$sd' dc-latestdate='07312020' dc-earliestdate='$td'",			20,				10,	0));		 
         $form->addElement("x", new form_element_text("Gracedays",  "gdays",    GetParam('gdays')?GetParam('gdays'):$this->record[5],			"forminput",			3,				3,	0));			 
		 
         $form->addElement("y", new form_element_checkbox("Silent mode",  "silence",		GetParam('silence')?GetParam('silence'):$this->record[9],			"forminput"));		 
         $form->addElement("y", new form_element_text("Promt",  "pass",			GetParam('pass')?GetParam('pass'):$this->record[2],			"forminput",			50,				50,	0));		 
         $form->addElement("y", new form_element_text("When expires goto",  "expgoto",		GetParam('expgoto')?GetParam('expgoto'):$this->record[6],			"forminput",			50,				50,	0));		 
         $form->addElement("y", new form_element_text("When renew goto",  "rengoto",		GetParam('rengoto')?GetParam('rengoto'):$this->record[7],			"forminput",			50,				50,	0));		 		 
		 
         $form->addElement("p", new form_element_checkbox("Enable inhouse pay",  "usepay",		GetParam('usepay')?GetParam('usepay'):$this->record[19],			"forminput"));		 			 			 		 
         $form->addElement("p", new form_element_text("Cost",  "payval",	GetParam('payval')?	GetParam('payval'):$this->record[20],			"forminput",			50,				50,	0));			 			 		 		 
         $form->addElement("p", new form_element_text("Code",  "paycode",		GetParam('paycode')?GetParam('paycode'):$this->record[21],			"forminput",			50,				50,	0));			 			 		 		 
		 $form->addElement("p", new form_element_text("Details",  "payitem",		GetParam('payitem')?GetParam('payitem'):$this->record[22],			"forminput",			50,				50,	0));			 			 		 
		 $form->addElement("p", new form_element_text("Method",  "paytype",     $this->record[23],			"forminput",			50,				50,	1));			 			 		 			 		 
         $form->addElement("p", new form_element_text("Account",  "payaccount",  GetParam('payaccount')?GetParam('payaccount'):$this->record[24],			"forminput",			50,				50,	0));		 		 
		 
         $form->addElement("z", new form_element_checkbox("Enable",  "usemail",		GetParam('usemail')?GetParam('usemail'):$this->record[10],			"forminput"));			 			 		 
         $form->addElement("z", new form_element_text("Owner e-mail","ownermail", GetParam('ownermail')?GetParam('ownermail'):$this->record[11],			"forminput",			50,				50,	0));
         $form->addElement("z", new form_element_text("CC #1",  "cc1", GetParam('cc1')?GetParam('cc1'):$this->record[12],			"forminput",			50,				50,	0));			 			 		 
         $form->addElement("z", new form_element_text("CC #2",  "cc2", GetParam('cc2')?GetParam('cc2'):$this->record[13],			"forminput",			50,				50,	0));		 			 			 		 		 
		 $form->addElement("z", new form_element_text("Subject when in grace period",  "subject", GetParam('subject')?GetParam('subject'):($this->record[14]?$this->record[14]:$this->notify_subject),			"forminput",			50,				50,	0));			 			 		 			 		 
         $form->addElement("z", new form_element_text("Message when in grace period",  "message", GetParam('message')?GetParam('message'):($this->record[15]?$this->record[15]:$this->notify_message),			"forminput",			50,				50,	0));
		 $form->addElement("z", new form_element_text("Subject when expired",  "altsubject",      GetParam('altsubject')?GetParam('altsubject'):($this->record[16]?$this->record[16]:$this->notify_altsubject),			"forminput",			50,				50,	0));			 			 		 			 		 
         $form->addElement("z", new form_element_text("Message when expired",  "altmessage",      GetParam('altmessage')?GetParam('altmessage'):($this->record[17]?$this->record[17]:$this->notify_altmessage),			"forminput",			50,				50,	0));		 			 			 		 		 
         $form->addElement("z", new form_element_text("Send SMS to",  "sms", GetParam('sms')?GetParam('sms'):$this->record[18],			"forminput",			50,				50,	0));         		 

         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("id", GetReq('id')));		 		 		 		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("type", decode(GetGlobal('UserID'))));								 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "saveapp"));	   	   
	   
	     $ret .= $form->getform ();			

	   }
	   else	
	     $ret = "Invalid record!";
			
	   $msg = $this->msg ? "Modify URL".$this->msg : "Modify URL";
	   			
       $mywin = new window($msg,$ret);
       $out .= $mywin->render("center::100%::0::group_win_body::center::0::0::");		 
				
		
	   return ($out);
	}	

	//override
	function show_applications() {
	
	   $terms = $this->terms_and_contitions(); //agree terms from..once
	   if ($terms) return ($terms);
	
	   $out = $this->header_applications();		    
	
	   if ($this->carr) {	
	 
		$meter = 0;		
	
	    foreach ($this->carr as $n=>$rec) {
		
		   $meter += 1;
		   
           $idname = "enable_".$meter;
		   $check = $rec['enable']==1?'checked':null;
		   $value = $rec['enable']==1?1:0;		   
		   $viewdata[] = "<input type=\"checkbox\" name=\"$idname\" value=\"$value\" $check readonly>";
		   $viewattr[] = "left;2%";		   
		
		   $viewdata[] = $meter;//$n+1;
		   $viewattr[] = "right;5%";
		   
		   $symbol1 = $this->show_unpaid_record_mark($rec['appname'],$rec['isfree'],$rec['insdate'],$rec['paydate'],$rec['tmzid'],$rec['paydays']);
		   $viewdata[] = $symbol1;
		   $viewattr[] = "left;1%";
		   
		   /*$symbol2 = $this->show_extra_record_mark($meter,$rec['insdate'],$rec['paydate'],$rec['tmzid']);		   
		   $viewdata[] = $symbol2;
		   $viewattr[] = "left;1%";*/
		   
		   $symbol3 = $this->show_exp_app_mark($rec['appname'],$rec['expire'],$rec['gracedays'],$rec['tmzid']);
		   $viewdata[] = $symbol3;
		   $viewattr[] = "left;1%";		   		   		
		   
		   $viewdata[] = seturl("t=jsdelapp&id=".$this->encode($rec['appname']),"X");
		   $viewattr[] = "left;5%";		      
		   
		   $viewdata[] = $this->show_ccode_link($rec['appname']);//$rec['appname']? seturl("t=ccode&id=".$this->encode($rec['appname']),$rec['appname']) : "&nbsp;");
		   $viewattr[] = "left;25%";		   
		   
		   $viewdata[] = ($rec['timezone']?$rec['timezone']:"&nbsp;");
		   $viewattr[] = "left;50%";	
		   
		   $viewdata[] = $this->show_expire_link($rec['appname'],$rec['expire']);//($rec['expire']? seturl("t=editapp&id=".$this->encode($rec['appname']),$rec['expire']):"&nbsp;");
		   $viewattr[] = "left;10%";		
		   
		   $viewdata[] = ($rec['gracedays']?$rec['gracedays']:"&nbsp;0");
		   $viewattr[] = "right;5%";			   	   
		   	   	   	   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::content::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);			
		}
		///////////////////////////////////////////////////////////////
        $topay = $this->pay_notifications();
	   }
	   else {
	     //$toprint = "No url(s)...add a new !<br/>";
	     $toprint .= $this->insert_form();
	   }	 
		 		 
		 
       $mywin = new window(''/*$this->title*/,$toprint);
       $out .= $mywin->render("center::100%::0::content::center::0::0::");
	   
	   $out .= "<hr/>";	   
	   
       $mywin2 = new window('',$topay);
       $out .= $mywin2->render("center::100%::0::content::::0::0::");	   		 
	   
	   return ($out);			
	}
	
	function pay_notifications() {
		$apptype = decode(GetGlobal('UserID'));	
	
		////////////////////////////////////////////////////////////// records in inventory
		if (defined('LOCKMYTRANSACT_DPC')) {
		
		  $apptype = decode(GetGlobal('UserID'));
		  $paid = GetGlobal('controller')->calldpc_method("lockmytransact.get_map_status use " . $apptype);		
		  
 		  if ($paid>0) { 
		    $this->payfor = intval($paid) * -1; //to subtrack from to be paid
		    //echo $payfor,'>';
	        $topay .= writecl("- ". $paid . " record(s) already paid! Are stored in your inventory for future needs!",'#FFFFFF','#00FF00');
		  }
		  else
		    $this->payfor = 0;		  	
		}
		else
		  $this->payfor = 0;		
		
		///////////////////////////////////////////////////////////// expired records mechanism
		if ($this->expired_records) {
		  $this->payfor += $this->expired_records; //plus extra records
		  //echo $this->payfor;
		  $topay .= writecl("* ". $this->expired_records . " expired record(s) must be paid to continue work properly!",'#FFFFFF','#FF0000');
		}  
		
		/////////////////////////////////////////////////////////////  records in grace period mechanism
		if ($this->grace_records) {
		  $this->payfor += $this->grace_records; //plus extra records		
		  $topay .= writecl("+ ".$this->grace_records ." in grace period! Renew now to keep continue work properly!",'#FFFFFF','#0000FF');		  		
		}
		
		$payfor = intval($this->payfor);		
		$current_record_state = intval($this->expired_records + $this->grace_records);
		if ($current_record_state>0) {
		  if ($payfor<=0) {///////////////////////////////////////////////////// inventory renew link..goes to order
		    //if all recs are paid ok else only paid records
		    $paidfor = abs($paid)>=$current_record_state
		                         ? $current_record_state 
		                         : $current_record_state - $paid;  
	        $param = $this->encode(decode(GetParam('UserID')).';-'.$paidfor.';');//add - negative for paid records		  
		    $topay .= str_replace('@',urlencode($param),$this->inv_records_link);		
		  }
		  elseif ($payfor>0) {///////////////////////////////////////////////////////// pay link..goes to paypal
		    if ($paid>0) {//modulo of paid records
	          $param = $this->encode(decode(GetParam('UserID')).';-'.$paid.';');//add - negative for paid records		  
		      $topay .= str_replace('@',urlencode($param),$this->inv_records_link);			
			}
	        $param = $this->encode(decode(GetParam('UserID')).';'.$payfor.';');		
		    $topay .= str_replace('@',urlencode($param),$this->pay_records_link);					
		  }
		} 
		
		return ($topay);	
	}
	
	//override
	function insert_record() {
        $db = GetGlobal('db');
		$appname = GetParam('name');
        $apptype = GetParam('type');//user mail comes from insert form post
		$today = date('Y-m-d');
		
		$timezone = get_selected_option_fromfile(GetParam('zone'),'timezones');
		$tmzid = $this->create_timezone_id($timezone);
		  			   
	    $tmz_today = $this->make_gmt_date(null,$tmzid);  				

		/*if (!$appname) {
		  $this->msg = writecl("Not a valid Record!",'#FFFFFF','#FF0000');
		  return false; //NAME required    
		} 
		elseif ((stristr($appname,'http')) or (stristr($appname,'://'))) {
		  $this->msg = writecl("Not a valid url!($appname)",'#FFFFFF','#FF0000');
		  return false; //invalid form of url  		
		}
		else { //if ((stristr($myurl,'.htm')) or (stristr($myurl,'.php')) or (stristr($myurl,'.asp'))) {*/
		if ($this->check_record()) {		
		
	      //if (defined('LOCKMYTRANSACT_DPC'))  
	        $map = GetGlobal('controller')->calldpc_method('lockmytransact.get_map_status use '.$apptype);		
			if ($map) {
			  $pd = GetGlobal('controller')->calldpc_method('lockmytransact.get_days use +'.$apptype);
			  $paydays = $pd?intval($pd):0;
			}  
		  //else
	        //die('LOCKMYTRANSACT_DPC required!');
		  //echo $map,'>';
		  
		  $f = $this->isfree_record($apptype);
		  //echo $f,'>';
		  $free = $f?$f:0;		  			
		    
		  if ($map>0) {//already paid records
		    $sSQL2 = "insert into applications (enable,isfree,insdate,paydate,paydays,apptype,appname,apppass,timezone,tmzid,expire,gracedays,linkexpire,linkrenew,silence,usemail,ownermail,ccmail,subject,message,altsubject,altmessage";
			$sSQL2.= ",usepay,payval,paycode,payitem,paytype,payaccount) values (";
		  }	
		  else {		  
	        $sSQL2 = "insert into applications (enable,isfree,insdate,apptype,appname,apppass,timezone,tmzid,expire,gracedays,linkexpire,linkrenew,silence,usemail,ownermail,ccmail,subject,message,altsubject,altmessage";
			$sSQL2.= ",usepay,payval,paycode,payitem,paytype,payaccount) values (";			
		  }	
		  
			
		  $sSQL2 .= GetParam('enable') . ",";
		  $sSQL2 .= $free . ",";		  
		  $sSQL2 .= $db->qstr($tmz_today) .  ",";
		  
		  if ($map>0) {//already paid records		  
		    $sSQL2 .= $db->qstr($tmz_today) .  ",";		  
			$sSQL2 .= $paydays . ",";		 
		  }	
			
		  $sSQL2 .= $db->qstr($apptype) . ",";
		  $sSQL2 .= $db->qstr($appname) . ",";
		  $sSQL2 .= $db->qstr(GetParam('pass')) . ",";
		  $sSQL2 .= $db->qstr($timezone) . ",";
		  $sSQL2 .= $tmzid . ",";		
		  $sSQL2 .= $db->qstr(GetParam('expire')) . ",";		
		  $sSQL2 .= $db->qstr(GetParam('gdays')) . ",";
		  $sSQL2 .= $db->qstr(GetParam('expgoto')). ",";
		  $sSQL2 .= $db->qstr(GetParam('rengoto')). ",";		
		  $sSQL2 .= GetParam('silence')?GetParam('silence'):0;
		  $sSQL2 .=  ",";		  
		  $sSQL2 .= GetParam('usemail')? GetParam('usemail') : 0 ;
		  $sSQL2 .=  ",";		  
		  $sSQL2 .= $db->qstr(GetParam('ownermail')). ",";		  		  
		  $sSQL2 .= (GetParam('cc1') && GetParam('cc2')) ? //issset modmail ownmail
		            $db->qstr(GetParam('cc1') .';'. GetParam('cc2')) : //; list
					$db->qstr(GetParam('cc1') . GetParam('cc2')); //one or another
		  $sSQL2 .= "," . $db->qstr(GetParam('subject'));				   
		  $sSQL2 .= "," . $db->qstr(GetParam('message'));
		  $sSQL2 .= "," . $db->qstr(GetParam('altsubject'));		  
		  $sSQL2 .= "," . $db->qstr(GetParam('altmessage'));
		  
		  $sSQL2 .= "," . GetParam('usepay');				   
		  $sSQL2 .= "," . GetParam('payval');
		  $sSQL2 .= "," . $db->qstr(GetParam('paycode'));		  
		  $sSQL2 .= "," . $db->qstr(GetParam('payitem'));
		  $sSQL2 .= "," . $db->qstr(GetParam('paytype'));		  
		  $sSQL2 .= "," . $db->qstr(GetParam('payaccount'));		  		  		  							  
		  $sSQL2 .= ")";						

          $db->Execute($sSQL2,1);		
		  //echo $sSQL2;
		  if ($a = $db->Affected_Rows()) {
		    //echo 'z';
		    $this->msg = writecl("Record added successfully!",'#FFFFFF','#00FF00');
		   
		    //simulate add cart, submit and transaction
		    if ($map>0) {//only if already paid records			
              if (defined('LOCKMYCART_DPC')) {			
		        GetGlobal('controller')->calldpc_method("lockmycart.addtocart use 365;Lockmy url already paid records;path;template;group;page;url records for mylock;photo;0;-1;+-1+1");		  
			    $buffer = serialize(GetGlobal('controller')->calldpc_var('lockmycart.buffer'));			
			  }
	          else
			    $buffer = null;
			  			
	          if (defined('LOCKMYTRANSACT_DPC')) { 
			    $payway = 'Order';
	            $this->transaction_id = GetGlobal('controller')->calldpc_method('lockmytransact.saveTransaction use '.$buffer."+$apptype+$payway++-1+0+0+-1+".$paydays);		
			  }	
		      /*else
	            die('LOCKMYTRANSACT_DPC required!');*/		   
			}  
						  
            ////////////////////////////////////////////////// send notice mail
		    if ($this->host_mail_notifier)
		      $this->mailto($this->host_mail_notifier,'User insert a record',$this->makehtmlbody($apptype . ' add a new record'));
		  
		    return true;		  		   		  
		  }
		  else
		    $this->msg = writecl("Record NOT inserted!",'#FFFFFF','#FF0000');
		  
		}
		  
		return false;  
	}
	
	function show_ccode_link($appname) {
	
	    if ($this->appattr[$appname]['unpaid']<0)//expired
		  $link = $appname;
		else //paid or in grace period
	      $link = seturl("t=ccode&id=".$this->encode($appname),$appname);
		
		return ($link);
	}
	
	function show_expire_link($appname,$expire) {
	
	    if ($this->appattr[$appname]['unpaid']<0)//expired
		  $link = $expire;
		else //paid or in grace period	
	      $link = seturl("t=editapp&id=".$this->encode($appname),$expire);
		
		return ($link);
	}
	
	//override
	function update_record() {
        $db = GetGlobal('db'); 
		
		if ($this->check_record()) {
		
		$timezone = get_selected_option_fromfile(GetParam('zone'),'timezones');
		$tmzid = $this->create_timezone_id($timezone);		
		
	    $sSQL2 = "update applications set ";
        if (!$this->demo) {		
		  $sSQL2 .= "enable=";
		  $sSQL2 .= GetParam('enable')?GetParam('enable'):0;		
		  $sSQL2 .= ",";
		}
		
		$sSQL2 .= "silence=";
		$sSQL2 .= GetParam('silence')?GetParam('silence'):0; 		
		$sSQL2 .= ",apppass=" . $db->qstr(GetParam('pass'));
		$sSQL2 .= ",timezone=" . $db->qstr($timezone);
		$sSQL2 .= ",tmzid=" . $tmzid;		
		$sSQL2 .= ",expire=" . $db->qstr(GetParam('expire'));
		$sSQL2 .= ",gracedays=" . GetParam('gdays');
		
        if (!$this->demo) {		
	 	  $sSQL2 .= ",linkexpire=" . $db->qstr(GetParam('expgoto'));
		  $sSQL2 .= ",linkrenew=" . $db->qstr(GetParam('rengoto'));
		  $sSQL2 .=  ",usemail=";		  
  	      $sSQL2 .= GetParam('usemail')? GetParam('usemail') : 0 ;
		  $sSQL2 .= ",ownermail=" . $db->qstr(GetParam('ownermail'));						
		}  
		
		$sSQL2 .= ",ccmail=";				
        $sSQL2 .= (GetParam('cc1') && GetParam('cc2')) ? //issset modmail ownmail
		           $db->qstr(GetParam('cc1') .';'. GetParam('cc2')) : //; list
		 	       $db->qstr(GetParam('cc1') . GetParam('cc2')); //one or another
				   
        if (!$this->demo) {					 				   
	      $sSQL2 .= ",subject=" . $db->qstr(GetParam('subject'));				   
		  $sSQL2 .= ",message=" . $db->qstr(GetParam('message'));
		  $sSQL2 .= ",altsubject=" . $db->qstr(GetParam('altsubject'));		  
		  $sSQL2 .= ",altmessage=" . $db->qstr(GetParam('altmessage'));		  				 												
		  
		  $sSQL2 .= ",usepay=" . GetParam('usepay');				   
		  $sSQL2 .= ",payval=" . GetParam('payval');
		  $sSQL2 .= ",paycode=" . $db->qstr(GetParam('paycode'));		  
		  $sSQL2 .= ",payitem=" . $db->qstr(GetParam('payitem'));
		  $sSQL2 .= ",paytype=" . $db->qstr(GetParam('paytype'));		  
		  $sSQL2 .= ",payaccount=" . $db->qstr(GetParam('payaccount'));		  
		}
		
		$sSQL2 .= " where appname='" . GetParam('name') . "'";
		
        $db->Execute($sSQL2,1);		
		//echo $sSQL2;
		if ($a = $db->Affected_Rows()) {
		  //echo 'z';
		   $this->msg = writecl("Record modified successfully!",'#FFFFFF','#00FF00');
		   
		   ////////////////////////////////////////////////// send notice mail
		   if ($this->host_mail_notifier) {
		     if ($this->demo) 
		       $this->mailto($this->host_mail_notifier,'user in demo page',$this->makehtmlbody('demo page modified'),null,null,1);		  	 
		     else
		       $this->mailto($this->host_mail_notifier,'user update a record',$this->makehtmlbody($apptype . ' record modified.'));
		   }	 
			 
		   return true;	 		  
		}
		else
		  $this->msg .= writecl("Record NOT modified!",'#FFFFFF','#FF0000');
		}  
		return false;  
	}
	
	//override
	function get_record($name=null) {
        $db = GetGlobal('db');
		$id = GetParam('id')?GetParam('id'):GetReq('id');	
	    
		if (!$name)
		  $name = $this->decode($id);//called by the list  
	
	    $sSQL2 = "select appname,apptype,apppass,timezone,expire,gracedays,linkexpire,linkrenew,enable,silence,usemail,ownermail,ccmail,subject,message,altsubject,altmessage";
		$sSQL2 .= ",usepay,payval,paycode,payitem,paytype,payaccount from applications where active=1 and ";
		$sSQL2 .= "appname=" . $db->qstr($name);

		//echo $sSQL2;
	    $resultset = $db->Execute($sSQL2,2);  			 	
		//print_r($resultset);
		$res[] = $resultset->fields['appname'];
		$res[] = $resultset->fields['apptype'];
		$res[] = $resultset->fields['apppass'];
		$res[] = $resultset->fields['timezone'];
		$res[] = $resultset->fields['expire'];
		$res[] = $resultset->fields['gracedays'];
		$res[] = $resultset->fields['linkexpire'];
		$res[] = $resultset->fields['linkrenew'];
		$res[] = $resultset->fields['enable'];
		$res[] = $resultset->fields['silence'];
		$res[] = $resultset->fields['usemail'];
		$res[] = $resultset->fields['ownermail'];		
				
		if (stristr($resultset->fields['ccmail'],';')) {
		  $notemails = explode(';',$resultset->fields['ccmail']);
		  $res[] = $notemails[0];
		  $res[] = $notemails[1];										
		}
		else {
		  $res[] = $resultset->fields['ccmail'];  
		  $res[] = null;  
		}  
		
		$res[] = $resultset->fields['subject'];
		$res[] = $resultset->fields['message'];
		$res[] = $resultset->fields['altsubject'];		
		$res[] = $resultset->fields['altmessage'];
		$res[] = $resultset->fields['sms'];       //reserved for sms service		
		
		$res[] = $resultset->fields['usepay'];
		$res[] = $resultset->fields['payval'];
		$res[] = $resultset->fields['paycode'];		
		$res[] = $resultset->fields['payitem'];				
		$res[] = $resultset->fields['paytype'];		
		$res[] = $resultset->fields['payaccount'];
						
		//print_r($res);
		return ($res);								
	}
	
	function get_pay_details($name=null) {
        $db = GetGlobal('db');
	
	    $sSQL2 = "select ownermail,ccmail,usepay,payval,paycode,payitem,paytype,payaccount from applications where active=1 and ";
		$sSQL2 .= "appname=" . $db->qstr($name);
		
	    $resultset = $db->Execute($sSQL2,2); 
		
		$res[] = $resultset->fields['ownermail'];
		$res[] = $resultset->fields['ccmail'];
		$res[] = $resultset->fields['usepay'];
		$res[] = $resultset->fields['payval'];
		$res[] = $resultset->fields['paycode'];
		$res[] = $resultset->fields['payitem'];
		$res[] = $resultset->fields['paytype'];
		$res[] = $resultset->fields['payaccount'];
		
		$ret = implode('<@>',$res);
						
		return ($ret);
    }					
	
	function check_expired_records_mailservice() {
       $db = GetGlobal('db');	   
	   $today = date('Y-m-d');//handle timezone...	
	   $i = 0; 
	   $j = 0;	   

	   $sSQL2 = "select appname,apptype,enable,apppass,timezone,expire,gracedays,tmzid,linkexpire,linkrenew,silence,insdate,paydate,isfree,usemail,ownermail,ccmail,subject,message,altsubject,altmessage from applications where active=1 and enable=1";
	   //echo $sSQL2;
	   $resultset = $db->Execute($sSQL2,2); 	 
		 
       if ($resultset) {//&& (!empty($resultset->fields))) {
	   
	     foreach ($resultset as $i=>$rec) {
		 
		   $i+=1;
			
		   $appname = $rec['appname'];
		   $apptype = $rec['apptype'];
		   $enable = $rec['enable']; 		   		   
		   $apppass = $rec['apppass'];
		   $tz = $rec['timezone'];
		   $expire = $$rec['expire'];
		   $gracedays = $rec['gracedays'];		
		   $tmzid = $rec['tmzid'];
		   $expgoto = $rec['linkexpire'];
		   $rengoto = $rec['linkrenew'];
		   $silence = $rec['silence'];
		   $insdate = $rec['insdate'];
		   $paydate = $rec['paydate'];		   
		   $isfree = $rec['isfree'];		   		   		  
		   $usemail = $rec['usemail'];
		   $ownermail = $rec['ownermail'];
		   $ccmail = $rec['ccmail'];		   
		   $subject = $rec['subject'];
		   $message = $rec['message'];
		   $altsubject = $rec['altsubject'];		   
		   $altmessage = $rec['altmessage'];		   		   		    
		   
           $paid = $this->check_paid_record($insdate,$paydate,$tmzid);		   

           if (($isfree) || ($paid)) {//&& ($enable)) {	 ////sql query handle 
		   
		     $tokens = array(0=>$appname,1=>$rengoto);
		     $tmz_today = $this->make_gmt_date(null,$tmzid);		 
	         $expres = $this->date_diff($tmz_today,$expire);
		     //echo '>',$expres,'>';
			 
			 $j+=1;
			 
	         if ($expres<=0) {
	           if (($gracedays) && ($gracedays>abs($expres))) {
		         //into grace period..days left
		         $status = ($gracedays - abs($expres)) * 1;//mul 1... when 1-1=0;
				 //echo $status,'gin>';
				 ////////////////////////////////////////////////// send grace mail
				 $tokens[] = $status;
				 $this->notify_mail($tokens,$apptype,null,$subject,$message);
				 
				 if ($usemail)
				   $this->notify_mail($tokens,$ownermail,$ccmail,$subject,$message);				 				 	
		       }
			   /*elseif (($gracedays) && ($gracedays==abs($expres))) {
			     //into grace period..days left,,zero problem
			     $status = 1;
				 //echo $status,'giso>';
                 ////////////////////////////////////////////////// send grace mail
				 $tokens[] = '0'; //days ...$status;
				 $this->notify_mail($tokens,$apptype,null,$subject,$message);
				 
				 if ($usemail)
				   $this->notify_mail($tokens,$ownermail,$ccmail,$subject,$message);				 				 					 
			   }*/
		       else {
			     //out of grace period or no grace period
  		         $status = -1;
				 //echo $status,'gout>';
				 ////////////////////////////////////////////////// send expired mail
			     $this->notify_mail($tokens,$apptype,null,$altsubject,$altmessage);
				 
				 if ($usemail)
				   $this->notify_mail($tokens,$ownermail,$ccmail,$altsubject,$altmessage);					 
		       }  
             }//expres
		   }//free or paid
		 } //record read 		
       }//resultset
	   
	   $ret = $i . ' record(s) readed. '. $j .' records(s) expire.';
	   return ($ret);	   
	}	
	
	//inform user for unpaid app records thru mail
    function check_paid_records_mailservice() {
       $db = GetGlobal('db');		
	   $today = date('Y-m-d');
	   $i = 0; 
	   $j = 0;
	   $my_rengoto = 'http://lockmy.networlds.org/pricelist.php';
	   $paysubject = '%0 expired';
	   $paymessage = '%0 expired. Please renew to continue its functionality. %1';
	   
	   $sSQL2 = "select appname,apptype,enable,apppass,timezone,expire,gracedays,tmzid,linkexpire,linkrenew,silence,insdate,paydate,,paydays,isfree,usemail,ownermail,ccmail,subject,message,altsubject,altmessage from applications where active=1";// and enable=1";
	   //echo $sSQL2;
	   $resultset = $db->Execute($sSQL2,2); 	   	   
	   
	   if (!empty($resultset)) {
	   
	   foreach ($resultset as $i=>$rec) {
	   
	     $i+=1;
	   
		 $appname = $rec['appname'];
		 $apptype = $rec['apptype'];
		 $enable = $rec['enable']; 		   		   
		 $apppass = $rec['apppass'];
		 $tz = $rec['timezone'];
		 $expire = $$rec['expire'];
		 $gracedays = $rec['gracedays'];		
		 $tmzid = $rec['tmzid'];
		 $expgoto = $rec['linkexpire'];
		 $rengoto = $rec['linkrenew'];
		 $silence = $rec['silence'];
		 $insdate = $rec['insdate'];
		 $paydate = $rec['paydate'];
		 $paydays = $rec['paydays'];		 		   
		 $isfree = $rec['isfree'];		   		   		  
		 $usemail = $rec['usemail'];
		 $ownermail = $rec['ownermail'];
		 $ccmail = $rec['ccmail'];		   
		 $subject = $rec['subject'];
		 $message = $rec['message'];
		 $altsubject = $rec['altsubject'];		   
		 $altmessage = $rec['altmessage'];	   	   
	   
         if (!$isfree) {	   	
           $tmz = isset($tmzid) ? $tmzid : $this->tmz_id;	   
	       $tmz_today = $this->make_gmt_date(null,$tmz);  
	
	       if ($paydate)
             $expres = $this->date_diff($tmz_today,$paydate) + $this->freedays + $paydays;
	       elseif ($insdate) 	 
	         $expres = $this->date_diff($tmz_today,$insdate) + $this->freedays + $paydays;
	       //else
	         //return ;	 
			 
	       if ($expres<=0) {
		   
		     $j+=1; 
	   
		     $tokens = array(0=>$appname,1=>$my_rengoto);	   
	   
	         if (($this->gracedays) && ($this->gracedays>abs($expres))) {
		         //into grace period..days left
		         $status = ($this->gracedays - abs($expres)) * 1;//plus 1... when 1-1=0
 				 $tokens[] = $status;				 
                 ////////////////////////////////////////////////// send grace mail
				 $this->notify_mail($tokens,$apptype,null,$paysubject,$paymessage);	
		     }
	         /*elseif (($gracedays) && ($gracedays==abs($expres))) {
			     //into grace period..days left,,zero problem
			     $status = 1;
 				 $tokens[] = '0';//days... //$status;				 
                 ////////////////////////////////////////////////// send grace mail
				 $this->notify_mail($tokens,$apptype,null,$paysubject,$paymessage);				 				 					 
		     }*/		 
		     else {
			     //out of grace period or no grace period
  		         $status = -1;				 
				 ////////////////////////////////////////////////// send expired mail
				 $this->notify_mail($tokens,$apptype,null,$paysubject,$paymessage);					 
		     }  
		   }//free
         }//expres
	   }//foreach
	   }//empty
	   $ret = $i . ' record(s) readed. '. $j .' records(s) to be paid.';
	   return ($ret);
    }				

};
}		
?>