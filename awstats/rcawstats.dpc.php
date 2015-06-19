<?php

$__DPCSEC['RCAWSTATS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCAWSTATS_DPC")) && (seclevel('RCAWSTATS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCAWSTATS_DPC",true);

$__DPC['RCAWSTATS_DPC'] = 'rcawstats';
 
$d = GetGlobal('controller')->require_dpc('awstats/awstats.dpc.php');
require_once($d); 

//GetGlobal('controller')->get_parent('AWSTATS_DPC','RCAWSTATS_DPC');
$__EVENTS['RCAWSTATS_DPC'][0]='cpawstats';
$__EVENTS['RCAWSTATS_DPC'][1]='cpawstatselect';

$__ACTIONS['RCAWSTATS_DPC'][0]='cpawstats';
$__ACTIONS['RCAWSTATS_DPC'][1]='cpawstatselect';

$__LOCALE['RCAWSTATS_DPC'][0]='RCAWSTATS_DPC;Awstats log;Awstats log';
$__LOCALE['RCAWSTATS_DPC'][1]='_MONTHS;Month;Month';
$__LOCALE['RCAWSTATS_DPC'][2]='_YEARS;Year;Year';

class rcawstats extends awstats {

	var $title,$domain;
	var $awpath,$path, $url, $urlpath, $inpath;
	var $awurlpath;
	var $encoding;
		
	function rcawstats() {
	
	    //awstats::awstats();
		
	    $this->title = localize('RCAWSTATS_DPC',getlocal());	
		$this->domain = paramload('RCAWSTATS','domain');
		
		if ($remoteuser=GetSessionParam('REMOTELOGIN')) 
		  $this->path = paramload('SHELL','prpath')."instances/$remoteuser/";		  
		else 
		  $this->path = paramload('SHELL','prpath');	
		  
		$this->url = paramload('SHELL','url'); 
	    $this->urlpath = paramload('SHELL','urlpath');
	    $this->inpath = paramload('ID','hostinpath');				 
		  		
		$this->awpath = $this->path . paramload('RCAWSTATS','awpath');	 
		$this->awfile = null;
        $this->aw = new awfile($this->awfile); 
		
		$appname = paramload('ID','instancename');
		$app = $appname?$appname.'.':null;//null is the root app
		
        $char_set  = arrayload('SHELL','char_set');	  
        $charset  = paramload('SHELL','charset');	  		
	    if (($charset=='utf-8') || ($charset=='utf8'))
	      $this->encoding = 'utf-8';
	    else  
	      $this->encoding = $char_set[getlocal()]; 	
				
		//public dir
		//$this->awurlpath = "http://www.networlds.org/cgi-bin/awstats.pl?config=".$app."networlds.org&ssl=&lang=gr";		
		//personal protected dir
		$this->awurlpath = $this->url . $this->inpath . "/cp/cgi-bin/awstats.pl?config=".$app."networlds.org&ssl=&lang=gr";		
		//echo '>',$this->awurlpath;
	}
	 	
	
    function event($sAction) {
	
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		
	
        //awstats::event($sAction); 
		switch ($sAction) {
		
		  case 'cpawstatselect' : $this->select_awfile();
		                          break;
		}
    }
  
    function action($action) {

	 if (GetSessionParam('REMOTELOGIN')) 
	     $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	 else  
         $out = setNavigator(seturl("t=cp","Control Panel"),$this->title); 	 
		  
	 //$out .= $this->changeform();
	 
	 //$out .= awstats::action($action);
	 
	 $out .= $this->show_awstats_online();
	 
	 return ($out);
    } 
	
	function show_awstats_online() {
	
      $bodyurl = $this->awurlpath;
  
      $ret = '<br><iframe src ="'.$bodyurl.'" width="100%" height="450px"><p>Your browser does not support iframes .</p></iframe>';  
      return ($ret);	
	}
	
	function changeform() {
	
       $myaction = seturl("t=cpawstatselect");	
	
	   $form = new form(localize('RCAWSTATS_DPC',getlocal()), "rcawstats", FORM_METHOD_POST, $myaction, true);
	
	   $form->addGroup			("a",			"Select period.");
	   $form->addElement		("a",			new form_element_combo_file (localize('_MONTHS',getlocal()),     "month",	    GetParam("month"),				"forminput",	        1,				0,	'months'));		   	
       $form->addElement		("a",			new form_element_combo_file (localize('_YEARS',getlocal()),     "year",	    GetParam("year"),				"forminput",	        1,				0,	'years'));		   		   
       $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("FormAction", "cpawstatselect"));	   
	   
	   
	   $ret = $form->getform ();	
	   
	   return ($ret);
	}
	
	function select_awfile() {
	   
	   $month = sprintf("%02d",GetParam('month')+1); //echo '>>>',$month;
	   //$year = get_selected_option_fromfile(GetParam("year"),'years'); 
	   $year = 2000 + GetParam('year')+1;
	   //echo '>>>>',$year,' ',GetParam("year");
	   
	   $this->awfile = $this->awpath . 'awstats' . $month . $year . '.' . $this->domain .'.txt';
	   $this->aw = new awfile($this->awfile); 
	}
	
	//overwrite
	function statistics() {
	
	  if ($this->awfile) {
	
	  $ret =  "<strong>[".$this->awfile."]</strong><br />";
	
	  if (!$this->aw->Error()) {

	   $ret .= "The site first visit in the month: ".$this->aw->GetFirstVisit()."<br /><br />";
	   $ret .=  "Total visits this month: ".$this->aw->GetVisits()."<br /><br />";
	   $ret .=  "Total unique visits this month: ".$this->aw->GetUniqueVisits()."<br /><br />";
	   $ret .= "<br />";
	   
	   $title =  "Pages viewed / hours:";
	   foreach ($this->aw->GetHours() as $hour=>$pages)
		 $data .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".str_pad($hour, 2, "0", STR_PAD_LEFT).": ".$pages." pages viewed.</em><br />";
	   $swin = new window($title,$data);
	   $ret .= $swin->render("center::100%::0::group_win_body::center::0::0::");	
	   unset ($swin);		
	   $ret .= "<br />";		
		
	   $title =  "Pages viewed / days:";
	   foreach ($this->aw->GetDays() as $day=>$pages)
		 $data1 .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$day.": ".$pages." pages viewed.</em><br />";
	   $swin = new window($title,$data1);
	   $ret .= $swin->render("center::100%::0::group_win_body::center::0::0::");	
	   unset ($swin);		
	   $ret .= "<br />";	

	   $betterDay = $this->aw->GetBetterDay();
	   $ret .=  "The day with more visitors(".$betterDay[1].") was the ".$betterDay[0].".<br /><br />";
       $ret .= "<br />";
	   
	   $title =  "hits / os:";
	   foreach ($this->aw->GetOs() as $os=>$hits)
		 $data2 .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$os.": ".$hits." hits.</em><br />";
	   $swin = new window($title,$data2);
	   $ret .= $swin->render("center::100%::0::group_win_body::center::0::0::");	
	   unset ($swin);		
	   $ret .= "<br />";	
	
	   $title =  "hits / browser:";
	   foreach ($this->aw->GetBrowser() as $browser=>$hits)
		$data3 .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$browser.": ".$hits." hits.</em><br />";
	   $swin = new window($title,$data3);
	   $ret .= $swin->render("center::100%::0::group_win_body::center::0::0::");	
	   unset ($swin);		
	   $ret .= "<br />";	
		
	   $title =  "Distinct Referers:";
	   foreach ($this->aw->GetReferers() as $referer=>$hits)
		 $data4 .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$referer.": ".$hits." hits.</em><br />";
	   $swin = new window($title,$data4);
	   $ret .= $swin->render("center::100%::0::group_win_body::center::0::0::");	
	   unset ($swin);		
	   $ret .= "<br />";	
		
	   $title =  "Visits / Session Ranges:";
	   foreach ($this->aw->GetRanges() as $range=>$visits)
		 $data5 .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$range.": ".$visits." visits.</em><br />";
	   $swin = new window($title,$data5);
	   $ret .= $swin->render("center::100%::0::group_win_body::center::0::0::");	
	   unset ($swin);		
	   $ret .= "<br />";	
	
	  }
	  else
	    $ret .= "error!";
		
	  return ($ret);	
	  }
	} 	  
  
};
}
?>