<?php
if (defined("DATABASE_DPC")) {

$__DPCSEC['VIEWEVENT_DPC']='1;1;1;1;1;1;1;1;2';

if ( (!defined("VIEWEVENT_DPC")) && (seclevel('VIEWEVENT_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("VIEWEVENT_DPC",true);

$__DPC['VIEWEVENT_DPC'] = 'event_view';

$__EVENTS['VIEWEVENT_DPC'][0]='viewevent';
$__EVENTS['VIEWEVENT_DPC'][1]='vieweventset';
$__EVENTS['VIEWEVENT_DPC'][2]='vieweventgroups';

$__ACTIONS['VIEWEVENT_DPC'][0]='viewevent';
$__ACTIONS['VIEWEVENT_DPC'][1]='vieweventset';
$__ACTIONS['VIEWEVENT_DPC'][2]='vieweventgroups';

$__DPCATTR['VIEWEVENT_DPC']['viewevent']='viewevent,0,0,0,0,0,0,0,0';

$__LOCALE['VIEWEVENT_DPC'][0] = '_EMSG_OK;Ok !;Ok !';
$__LOCALE['VIEWEVENT_DPC'][1] = '_EMSG_ERROR;Error !;Error !';
$__LOCALE['VIEWEVENT_DPC'][2] = '_VIEWEVENT;View Event;Προβολή Γεγονότος';

class event_view {

	var $message;	
    var $res;
	var $inwin;		

	function event_view() { 
	 
	   $this->message = null;	    
	   $this->res = null;
	   
	   $this->inwin = GetGlobal('dispatcher')->get_attribute(8);
	}

    function event($evn) {
	   $g = GetReq('g');
	
       switch ($evn) {		
	     case "viewevent"       : $this->selectevent($g); break;	   
	     case "vieweventset"    : break;		  		 
	     case "vieweventgroups" : $this->read_categories($g); break;		 
       }	
	}	

	function action($act) {
	   $g = GetReq('g');
	  
      switch ($act) {		  	
	     case "viewevent"       : $out = $this->viewevent(); break;	  
	     case "vieweventset"    : $out = $this->vieweventset($g); break;
	     case "vieweventgroups" : $out = $this->view_categories(); break;		 
      }		
	    
	  return ($out);
	}
	
	
	function read_categories($parent=null) {
	   $db = GetGlobal('db');
	  
       $sSQL = "SELECT e_categories FROM events";
	   if ($parent) $sSQL .= " WHERE e_categories LIKE %" . $parent ."%";		   										         
    
	   //print $sSQL;
       $results = $db->Execute($sSQL);
	   
       while(!$results->EOF) {
          $this->res[] = explode(',',$results->fields[0]);
	      $results->MoveNext();		  
	   }	   
	   //print_r($this->res);
	}
	
	function view_categories() {
	
       $out = setNavigator(seturl("t=events",localize('EVENTSRV_DPC',getlocal())),
	                        localize('_VIEWEVENT',getlocal())); 	
   
       //result is a multi-array 
       if (is_array($this->res)) {
	   
	      foreach ($this->res as $id=>$catroot) {
		    foreach ($catroot as $cid=>$subcat) {
			   
			   if ($subcat) $data[$cid] .= seturl('t=vieweventset&g='.$subcat,$subcat) ."<br/>";
			           else $data[$cid] .= "&nbsp;"; 
			   $attr[$cid] = "left;25%";
			}
		  }
	   }
	   
	   $win = new window('categories',$data,$attr);
	   $out .= $win->render();
	   unset ($win);
	   
	   return ($out);
	}		
	

	function selectevent($id) {
	   $db = GetGlobal('db');
	
	  
       $sSQL = "select " . 
	           "e_organizer,e_date,e_startat,e_endat,e_location,e_transparency,e_categories," .
			   "e_title,e_descr,e_class,e_attendees,e_priority,e_frequency,e_recurrency,e_freqstep," .
			   "e_daysofweek,e_startday,e_exceptiondays,e_alarmin,e_status,e_url,e_lang,e_durationmin," .  		   
			   "e_freebusy,e_group FROM events ";
	   $sSQL .= "WHERE e_id=" . $id;		   										         
    
	   //print $sSQL;
       $results = $db->Execute($sSQL);
	   
       $this->res = $results->fields;
	}
	
    function viewevent() {
	    
	   $category = str_replace(","," > ",$this->res['e_categories']);
	   		
       $out = setNavigator(seturl("t=events",localize('EVENTSRV_DPC',getlocal())),
	                       //localize('_VIEWEVENT',getlocal())); 
						   $category);
						    
	   
	   
	   $body = "<H1>" . $this->res['e_title'] . "</H1>";
	   $body .= "<br/>" . $this->res['e_date'];
	   $body .= "<br/>" . $this->res['e_descr'];
	   
	   if ($this->inwin) $title = localize('_VIEWEVENT',getlocal()); //window appear if title is set
	   
	   $win = new window($title,$body);
	   $out .= $win->render();
	   unset($win);
	   
	   return ($out); 						  	 
	 	 
    }
	
    function vieweventset($category=null,$fromdt='',$todt='') {
	   $db = GetGlobal('db');    	
	
       $out = setNavigator(seturl("t=vieweventgroups",localize('EVENTSRV_DPC',getlocal())),$category);
	                                         //localize('_VIEWEVENT',getlocal())); 
	
	   $sSQL = "select e_id,e_date,e_categories,e_title,e_descr from events";
	   if ($category) $sSQL .= " WHERE e_categories LIKE " . $db->qstr('%'.$category.'%');	   
       //if ($fromdt) $sSQL .= " where (e_startat>=" . $db->qstr(trim(reverse_datetime($fromdt))) .")";
 	   //if ($todt) $sSQL .= " AND (e_startat<=" . $db->qstr(trim(reverse_datetime($todt))) .")";		   
       $sSQL .= " ORDER BY e_date DESC";
   
	   //print $sSQL;

       $browser = new browseSQL();
	   $out .= $browser->render($db,$sSQL,"events","viewmore",20,$this,1,1,0,0);
	   unset ($browser);    
	   
	   return ($out); 
	 	 
    }
	
    function browse($packdata,$view) {
	
	   $data = explode("||",$packdata);
	   
	   switch ($view) {
	     case 'eventvlist' :  $out = $this->viewelist($data[0],$data[1],$data[2],$data[3],$data[4]); break;
	     case 'viewtitles' :  $out = $this->viewtitles($data[0],$data[1],$data[2],$data[3],$data[4]); break;		 
	     case 'viewmore'   :  $out = $this->viewmore($data[0],$data[1],$data[2],$data[3],$data[4]); break;			 
	    
              default      :  $out = $this->viewelist($data[0],$data[1],$data[2],$data[3],$data[4]);
       }
	   return ($out);
	}	
	
    function viewelist($id,$date,$cats,$title,$descr) {
	   $a = GetReq('a');
	   
	   $link = seturl("t=viewevent&a=&g=$id&p=" , $title);   
							  
	   $data[] = reverse_datetime($date);
	   $attr[] = "left;20%";
	   $data[] = $cats;
	   $attr[] = "left;20%";	   
	   $data[] = $link;   
	   $attr[] = "left;20%";
	   $data[] = $descr;   
	   $attr[] = "left;39%";
	   
	   if ( (defined("ICALENDAR_DPC")) && (seclevel('REMINDER_',decode($this->userLevelID))) ) {
	     $data[] = seturl("t=reminder&a=&g=$id","R");   
	     $attr[] = "left;1%";	   
	   }
	   
	   $myarticle = new window('',$data,$attr);
	   
	   //$out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
       if (($a) && ((stristr($organizer,$a)) ||
	                (stristr($date,$a)) ||
					(stristr($title,$a)) ||
					(stristr($descr,$a))) ) $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
	                                   else $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");	   
	   
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}			
	
    function viewtitles($id,$date,$cats,$title,$descr) {
	   $a = GetReq('a');
	   
	   $link = seturl("t=viewevent&a=&g=$id&p=" , $title);   
							  
	   $data[] = reverse_datetime($date);
	   $attr[] = "left;20%";   
	   $data[] = $link;   
	   $attr[] = "left;80%";
	   //$data[] = $descr;   
	   //$attr[] = "left;39%";
	   
	   $myarticle = new window('',$data,$attr);
	   
       if (($a) && ((stristr($organizer,$a)) ||
	                (stristr($date,$a)) ||
					(stristr($title,$a)) ||
					(stristr($descr,$a))) )
	     $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");
	   else 
	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");	   
	   
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}		
	
    function viewmore($id,$date,$cats,$title,$descr) {
	   $a = GetReq('a');
	   
	   $link = seturl("t=viewevent&a=&g=$id&p=" , localize('_MORE',getlocal())); 
	   
	   $body = "<H2>" . $title . "</H2>";
	   $body .= "[" . reverse_datetime($date) . "]" . substr($descr,0,200) . " ". $link . "<hr>";  
							  
	   $data[] = $body;
	   $attr[] = "left;100%";   
	   
	   $myarticle = new window('',$data,$attr);
	   
       if (($a) && ((stristr($organizer,$a)) ||
	                (stristr($date,$a)) ||
					(stristr($title,$a)) ||
					(stristr($descr,$a))) )
	     $out = $myarticle->render("center::100%::0::group_article_selected::left::0::0::");
	   else 
	     $out = $myarticle->render("center::100%::0::group_article_body::left::0::0::");	   
	   
	   unset ($data);
	   unset ($attr);

	   return ($out);
	}		
	
};
}
}
else die("DATABASE DPC REQUIRED!");
?>