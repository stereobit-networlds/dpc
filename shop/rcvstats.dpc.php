<?php

$__DPCSEC['RCVSTATS_DPC']='1;1;1;1;1;1;1;1;1';



if ((!defined("RCVSTATS_DPC")) && (seclevel('RCVSTATS_DPC',decode(GetSessionParam('UserSecID')))) ) {

define("RCVSTATS_DPC",true);



$__DPC['RCVSTATS_DPC'] = 'rcvstats';



$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($a);

 

$__EVENTS['RCVSTATS_DPC'][0]='cpvstats';

$__EVENTS['RCVSTATS_DPC'][1]='cpvstatsshow';



$__ACTIONS['RCVSTATS_DPC'][0]='cpvstats';

$__ACTIONS['RCVSTATS_DPC'][1]='cpvstatsshow';



$__DPCATTR['RCVSTATS_DPC']['cpvstats'] = 'cpvstats,1,0,0,0,0,0,0,0,0,0,0,1';



$__LOCALE['RCVSTATS_DPC'][0]='RCVSTATS_DPC;Statistics;Στατιστική';

$__LOCALE['RCVSTATS_DPC'][1]='_GNAVAL;Chart not available!;Στατιστική μή διαθέσιμη!';



class rcvstats  {



    var $reset_db, $title;

	var $_grids, $charts;

	var $ajaxLink;

	var $hasgraph;
	var $graphx, $graphy;
		

	function rcvstats() {

	

	  $this->title = localize('RCVSTATS_DPC',getlocal());		

	  $this->reset_db = false;

	  

	  $this->_grids[] = new nitobi("Items");	//must initialized althouth it handled by vehicles dpc  		  	  

      $this->_grids[] = new nitobi("ItemsStats");		

	  

	  $this->ajaxLink = seturl('t=cpvstatsshow&statsid='); //for use with...	      

	  //sndReqArg('index.php?t=existapp&application=meme2','existapp'

	  

	  $this->hasgraph = false;
	  $this->graphx = remote_paramload('RCVSTATS','graphx',$this->path);
	  $this->graphy = remote_paramload('RCVSTATS','graphy',$this->path);	
	}

	

    function event($event=null) {

	

	   //ALLOW EXPRIRED APPS

	   /////////////////////////////////////////////////////////////

	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	

	   /////////////////////////////////////////////////////////////		 

	

	   switch ($event) {

		 case 'cpvstatsshow': if (!$cvid = GetParam('statsid')) $cvid=-1; 

		                      $this->charts = new swfcharts;	

		                      $this->hasgraph = $this->charts->create_chart_data('statistics',"where tid='".$cvid . "' and year>=2000");

							  break; 	   

	     case 'cpvstats'    :

		 default            : $this->nitobi_javascript();

			                  $this->sidewin(); 		 

		                      if ($this->reset_db) $this->reset_db();

		                      $this->charts = new swfcharts;	

		                      $this->hasgraph = $this->charts->create_chart_data('statisticscat',"where year>=2000 and attr1='".urldecode(GetReq('cat'))."'");

	   }

			

    }   

	

    function action($action=null) {

	 
      if (!GetReq('editmode')) {
	    if (GetSessionParam('REMOTELOGIN')) 
	      $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	    else  
          $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 	 
      }
	  

	  switch ($action) {

	  

		 case 'cpvstatsshow': if ($this->hasgraph)

		                        $out = $this->show_graph('statistics','Product statistics',$this->ajaxLink,'stats');

							  else

							    $out = "<h3>".localize('_GNAVAL',0)."</h3>";	

							  die('stats|'.$out); //ajax return

							  break; 

	     case 'cpvstats'    :



		 default            : $out .= $this->show_statistics();

	  }	 



	  return ($out);

    }

	

	function nitobi_javascript() {

      if (iniload('JAVASCRIPT')) {



		   $template = $this->set_template();   		      

		   

	       $code = $this->init_grids();			

		   //$code .= $this->_grids[0]->OnClick(20,'StatisticDetails',$template,'VehicleStats','vid',19);

		   //REMOTE GRID ARRAY CALLED TO ENABLE onclick !!!!!

		   $vgrids = GetGlobal('controller')->calldpc_var('rcitems._grids');
		   $code .= $vgrids[0]->OnClick(17,'StatisticDetails',$template,'ItemsStats','tid',0);

	   

		   $js = new jscript;

		   $js->setloadparams("init()");

           $js->load_js('nitobi.grid.js');		   

           $js->load_js($code,"",1);			   

		   unset ($js);

	  }		

	}

	

	function set_template() {

	   		   

		   //$template .= "'+i19+'<br>";

		   //GetGlobal('controller')->calldpc_method('rcreport.execute_graph use Vehicle list');

		   //$template .= GetGlobal('controller')->calldpc_method('rcreport.show_graph use Vehicle list+400+200+1');		      	   

		   

		   //STATIC METHOD

		   /*$this->charts->create_chart_data('statistics','where vid=16');

		   $template = $this->charts->show_chart('statistics',400,200);

		   $template .= "'+update_stats_id(i19)+'<br>";

		   */

		   //self call

		   //$template .= "<phpdac> rcvstats.show_graph use statistics+400+200 </phpdac>";

		   

		   //DYNAMIC METHOD calling js script to update vid for ajax request

		   //$template = 'vid=';

		   $template .= "<h4>'+update_stats_id(i0,i0,i3)+'</h4>";

		   

		   return ($template);	

	}

	

	function show_graph($xmlfile,$title=null,$url=null,$ajaxid=null,$xmax=null,$ymax=null) {
	  $gx = $this->graphx?$this->graphx:$xmax?$xmax:550;
	  $gy = $this->graphy?$this->graphy:$ymax?$ymax:250;
	 
      $ret = $title;
	  $ret .= $this->charts->show_chart($xmlfile,$gx,$gy,$url,$ajaxid);

	  return ($ret);

	}
	
	

	function show_statistics() {

	

	   if ($this->msg) $out = $this->msg;



	   if (GetReq('cat')) {

		  if (defined("RCCATEGORIES_DPC"))//text based cats

		    $toprint .= GetGlobal('controller')->calldpc_method('rccategories.show_categories use cpvstats+1');		

          elseif (defined("RCKATEGORIES_DPC"))	   //ERROR!!!!

		    $toprint .= GetGlobal('controller')->calldpc_method('rckategories.show_menu use cpvstats');		  

	     //$toprint .= $this->show_categories('cpitems',1);

       }

	   

	   $toprint .= $this->show_grids();	

	   

       $mywin = new window($this->title,$toprint);

       $out .= $mywin->render();	

	   

	   //HIDDEN FIELD TO HOLD STATS ID FOR AJAX HANDLE

	   $out .= "<INPUT TYPE= \"hidden\" ID= \"statsid\" VALUE=\"0\" >";	   	    

	  

	   return ($out);		   

	}		

	

	function update_item_statistics($id) {

        $db = GetGlobal('db'); 

        $UserName = GetGlobal('UserName');	
		$name = $UserName?decode($UserName):session_id();

		

	    $currentdate = time();

	    $mydate = $db->qstr(date('Y-m-d',$currentdate));		

	    $myday  = date('d',$currentdate);	

	    $mymonth= date('m',$currentdate);	

	    $myyear = date('Y',$currentdate);	

								

		$sSQL = "insert into stats (date,day,month,year,tid,attr2) values (";

		$sSQL.= $mydate . ",";

		$sSQL.= $myday . ",";

		$sSQL.= $mymonth . ",";

		$sSQL.= $myyear . ",";						

		$sSQL.= $db->qstr($id) . ',';

        $sSQL.= $db->qstr($name) . ")";

		

		//echo $sSQL;



		$db->Execute($sSQL,1);	 		

		if ($db->Affected_Rows()) 

		  return true;

		else 

		  return false;		

	}

	

	function update_category_statistics($cat) {

        $db = GetGlobal('db'); 

        $UserName = GetGlobal('UserName');		
		$name = $UserName?decode($UserName):session_id();			

		

	    $currentdate = time();

	    $mydate = $db->qstr(date('Y-m-d',$currentdate));		

	    $myday  = date('d',$currentdate);	

	    $mymonth= date('m',$currentdate);	

	    $myyear = date('Y',$currentdate);	

								

		$sSQL = "insert into stats (date,day,month,year,attr1,attr2) values (";

		$sSQL.= $mydate . ",";

		$sSQL.= $myday . ",";

		$sSQL.= $mymonth . ",";

		$sSQL.= $myyear . ",";						

		$sSQL.= $db->qstr($cat) . ",";		

		$sSQL.= $db->qstr($name) . ")";

		

		//echo $sSQL;		



		$db->Execute($sSQL,1);	 		

		if ($db->Affected_Rows()) {

		  return true;

		}  

		else 

		  return false;		

	}	

	

	function reset_db() {

        $db = GetGlobal('db'); 

	 

	    $sSQL0 = "drop table stats";

	    $result0 = $db->Execute($sSQL0,1);	

	    if ($result0) $message = "Drop table ...\n";

		

	    //create table

	    $sSQL1 = 'CREATE TABLE `stats` ('

        . ' `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, '

        . ' `date` DATE NULL, '

        . ' `day` INT NULL, '

        . ' `month` INT NULL, '

        . ' `year` INT NULL, '

        . ' `vid` INT NULL, '

        . ' `tid` VARCHAR(64) NULL, '

        . ' `attr1` VARCHAR(254) NULL, '

        . ' `attr2` VARCHAR(254) NULL, '

        . ' `attr3` VARCHAR(254) NULL,'

        . ' INDEX (`vid`)'

        . ' )'

        . ' ENGINE = myisam'

        . ' CHARACTER SET greek COLLATE greek_general_ci'

        . ' COMMENT = \'item statistics\';';  

		  

	    $result1 = $db->Execute($sSQL1,1);

	    if ($result1) $message .= "Create table ...\n";

	  

	    setInfo($message);	  	

	}

	

	function init_grids() {

        //disable alert !!!!!!!!!!!!		

		$out = "

function alert() {}\r\n 



function update_stats_id() {

  var str = arguments[0];

  var str1 = arguments[1];

  var str2 = arguments[2];

  

  

  statsid.value = str;

  //alert(statsid.value);

  sndReqArg('$this->ajaxLink'+statsid.value,'stats');

  

  return str1+' '+str2;

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

	   $cat = GetReq('cat');	

           $filter = GetParam('filter');

	   

	   //grid 0 

	   $datattr[] = GetGlobal('controller')->calldpc_method("rcitems.show_grid use 500+440+1+$filter") . $this->searchinbrowser();							  

	   $viewattr[] = "left;50%";	   	   



	   $grid0_get = "shhandler.php?t=shngetstats";

	   $grid0_set = "";	   

	

	   //grid 1

	   $this->_grids[1]->set_text_column("AA","id","50","true");   	   	      	   	   	         	   	   	   	

	   $this->_grids[1]->set_text_column("Date","date","100","true");	

	   $this->_grids[1]->set_text_column("Day","day","60","true");	

	   $this->_grids[1]->set_text_column("Month","month","60","true");		   	   	   

	   $this->_grids[1]->set_text_column("Year","year","60","true");		   

	   $this->_grids[1]->set_text_column("ItemID","tid","50","true");		   

	   $this->_grids[1]->set_text_column("Attr1","attr1","100","true");		   	   	   

	   $this->_grids[1]->set_text_column("Attr2","attr2","100","true");		   

	   $this->_grids[1]->set_text_column("Attr3","attr3","100","true");		   

	   	   		   	   	  	   

	   $wd = $this->_grids[1]->set_grid_remote($grid0_get,$grid0_set,"550","220","livescrolling",10,"false");

	   

	   //businnes card	used to pass data from jscript

	   //$message .= $this->charts->render('usage',400,250);

	   $wd .= $this->_grids[1]->set_detail_div("StatisticDetails",550,20,'F0F0FF',$message);

	   $wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");


       if ($this->hasgraph) {
		   $wd .= $this->show_graph('statisticscat','Category statistics',seturl('t=cpvstats&cat='.$cat.'&p='.$p));
	   }	   
	   else
		   $wd .= "<h3>".localize('_GNAVAL',0)."</h3>";



	   $datattr[] = $wd;

	   $viewattr[] = "left;50%";

	   

	   $myw = new window('',$datattr,$viewattr);

	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");

	   unset ($datattr);

	   unset ($viewattr);		   	

	   	

	   return ($ret);	

	}	

	

	function sidewin() {



	    //$menu = $this->show_sub_tree('cpitems');

		//GetGlobal('controller')->calldpc_method('rcsidewin.set_show use '.$menu);	

		

		//if (!GetReq('cat')) { 

		

		  if (defined("RCCATEGORIES_DPC")) {//text based cats

		    if (!GetReq('cat'))//only when no cat sel else call other browser bellow

		      GetGlobal('controller')->calldpc_method('rcsidewin.set_show_calldpc use rccategories.show_tree PARAMS cpvstats');		

	      }		

          elseif (defined("RCKATEGORIES_DPC"))//sql based cats			

            GetGlobal('controller')->calldpc_method('rcsidewin.set_show_calldpc use rckategories.show_tree PARAMS cpvstats');					

	    //}	  

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

			

};

}

?>