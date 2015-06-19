<?php
/**
* Copyright (c) 2005 - Javier Infante
* All rights reserved.   This program and the accompanying materials
* are made available under the terms of the 
* GNU General Public License (GPL) Version 2, June 1991, 
* which accompanies this distribution, and is available at: 
* http://www.opensource.org/licenses/gpl-license.php 
*
* Description: Example of usage of class.awfile.php
*
* Author: Javier Infante (original author)
* Email: jabi (at) irontec (dot) com
**/	

$d = GetGlobal('controller')->require_dpc('awstats/awstats.lib.php');
require_once($d); 
	
	
$__DPCSEC['AWSTATS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("AWSTATS_DPC")) && (seclevel('AWSTATS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("AWSTATS_DPC",true);

$__DPC['AWSTATS_DPC'] = 'AWSTATS';
 
$__EVENTS['AWSTATS_DPC'][0]='awstats';

$__ACTIONS['AWSTATS_DPC'][0]='awstats';

$__DPCATTR['AWSTATS_DPC']['awstats'] = 'awstats,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['AWSTATS_DPC'][0]='AWSTATS_DPC;Awstats;Awstats';

class awstats {	
 
    var $aw,$awfile;

    function awstats() {
  
	  // Path to the AWSTATS DATA FILE	
	  $this->awfile = paramload('SHELL','prpath') . "awstats012005.example.com.txt"; 
	  $this->aw = new awfile($this->awfile); 
    }
  
    function event($sAction) {
	
    }
  
    function action($action) {

	 $out = $this->statistics();
	 
	 return ($out);
    }  
	
	function statistics() {
	
	  if (!$this->aw->Error()) {

	   $ret =  "<strong>Showing contents [".$this->awfile."]</strong><br />";

	   $ret .= "The site first visit in the month: ".$this->aw->GetFirstVisit()."<br /><br />";
	   $ret .=  "Total visits this month: ".$this->aw->GetVisits()."<br /><br />";
	   $ret .=  "Total unique visits this month: ".$this->aw->GetUniqueVisits()."<br /><br />";
	   $ret .=  "Pages viewed / hours:<br />";
	   foreach ($this->aw->GetHours() as $hour=>$pages)
		 $ret .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".str_pad($hour, 2, "0", STR_PAD_LEFT).": ".$pages." pages viewed.</em><br />";
		
	   $ret .=  "Pages viewed / days:<br />";
	   foreach ($this->aw->GetDays() as $day=>$pages)
		 $ret .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$day.": ".$pages." pages viewed.</em><br />";
	   $ret .=  "<br />";

	   $betterDay = $this->aw->GetBetterDay();
	   $ret .=  "The day with more visitors(".$betterDay[1].") was the ".$betterDay[0].".<br /><br />";

	   $ret .=  "hits / os:<br />";
	   foreach ($this->aw->GetOs() as $os=>$hits)
		 $ret .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$os.": ".$hits." hits.</em><br />";
	   $ret .=  "<br />";	
	
	   $ret .=  "hits / browser:<br />";
	   foreach ($this->aw->GetBrowser() as $browser=>$hits)
		$ret .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$browser.": ".$hits." hits.</em><br />";
	   $ret .=  "<br />";
		
	   $ret .=  "Distinct Referers:<br />";
	   foreach ($this->aw->GetReferers() as $referer=>$hits)
		 $ret .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$referer.": ".$hits." hits.</em><br />";
	   $ret .=  "<br />";
		
	   $ret .=  "Visits / Session Ranges:<br />";
	   foreach ($this->aw->GetRanges() as $range=>$visits)
		 $ret .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$range.": ".$visits." visits.</em><br />";
	   $ret .=  "<br />";	
	
	  }
	  else
	    $ret = "error!";
		
	  return ($ret);	
	} 
}
};

?>