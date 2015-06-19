<?php
$__DPCSEC['RCPAYPAL_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCPAYPAL_DPC")) && (seclevel('RCPAYPAL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCPAYPAL_DPC",true);

$__DPC['RCPAYPAL_DPC'] = 'rcpaypal';

$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($a);
 
$__EVENTS['RCPAYPAL_DPC'][0]='cppaypal';
$__EVENTS['RCPAYPAL_DPC'][1]='cppaypalshow';

$__ACTIONS['RCPAYPAL_DPC'][0]='cppaypal';
$__ACTIONS['RCPAYPAL_DPC'][1]='cppaypalshow';

$__DPCATTR['RCPAYPAL_DPC']['cppaypal'] = 'cppaypal,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCPAYPAL_DPC'][0]='RCPAYPAL_DPC;Transactions;Συναλλαγές';
$__LOCALE['RCPAYPAL_DPC'][1]='_GNAVAL;Chart not available!;Στατιστική μή διαθέσιμη!';

class rcpaypal {

    var $reset_db, $title;
	var $_grids, $charts;
	var $ajaxLink;
	var $hasgraph;
		
	function rcpaypal() {
	
	  $this->title = localize('RCPAYPAL_DPC',getlocal());		
	  $this->reset_db = false;
	  
	  $this->_grids[] = new nitobi("Transactions");	
      //$this->_grids[] = new nitobi("Tpay");		
	  
	  $this->ajaxLink = seturl('t=cptransshow&statsid='); //for use with...	      
	  //sndReqArg('index.php?t=existapp&application=meme2','existapp'
	  
	  $this->hasgraph = false;
	}
	
    function event($event=null) {
	
	   //ALLOW EXPRIRED APPS
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		 
	
	   switch ($event) {
		 case 'cptransshow': if (!$cvid = GetParam('statsid')) $cvid=-1; 
		                      $this->charts = new swfcharts;	
		                      $this->hasgraph = $this->charts->create_chart_data('statistics','where tid='.$cvid);
							  break; 	   
	     case 'cptransactions'    :
		 default            : $this->nitobi_javascript();
			                  $this->sidewin(); 		 
		                      if ($this->reset_db) $this->reset_db();
		                      $this->charts = new swfcharts;	
		                      $this->hasgraph = $this->charts->create_chart_data('statisticscat',"where attr1='".urldecode(GetReq('cat'))."'");
	   }
			
    }   
	
    function action($action=null) {
	 
	  if (GetSessionParam('REMOTELOGIN')) 
	    $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	  else  
        $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 	 
	  
	  switch ($action) {
	  
		 case 'cptranssshow': if ($this->hasgraph)
		                        $out = $this->show_graph('statistics',400,200);
							  else
							    $out = "<h3>".localize('_GNAVAL',0)."</h3>";	
							  die('stats|'.$out); //ajax return
							  break; 
	     case 'cptransactions'    :

		 default            : $out .= $this->show_transactions();
	  }	 

	  return ($out);
    }
	
	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   $template = $this->set_template();   		      
		   
	       $code = $this->init_grids();			
		   //$code .= $this->_grids[0]->OnClick(20,'StatisticDetails',$template,'VehicleStats','vid',19);
		   //REMOTE GRID ARRAY CALLED TO ENABLE onclick !!!!!
		   //$vgrids = GetGlobal('controller')->calldpc_var('rcitems._grids');
		   $code .= $this->_grids[0]->OnClick(1,'StatisticDetails',$template);//,'ItemsStats','tid',0);
	   
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
		   //$template .= "<h4>'+update_stats_id(i0,i0,i3)+'</h4>";
		   
		   return ($template);	
	}
	
	function show_graph($xmlfile,$x,$y) {
	
	  $ret = $this->charts->show_chart($xmlfile,$x,$y);
	  return ($ret);
	}
	
	function show_transactions() {
	
	   if ($this->msg) $out = $this->msg;
	   
	   $toprint .= $this->show_grids();	
	   
       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render();	
	   
	   //HIDDEN FIELD TO HOLD STATS ID FOR AJAX HANDLE
	   $out .= "<INPUT TYPE= \"hidden\" ID= \"statsid\" VALUE=\"0\" >";	   	    
	  
	   return ($out);		   
	}		
	
	function reset_db() {
        $db = GetGlobal('db'); 
	 
	    $sSQL0 = "drop table transactions";
	    $result0 = $db->Execute($sSQL0,1);	
	    if ($result0) $message = "Drop table ...\n";
		
	    //create table
	    $sSQL1 = 'CREATE TABLE `transactions` ('
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
	
	function show_grid($x=null,$y=null,$filter=null) {
	
	   $x = $x?$x:400;
	   $y = $y?$y:100;
	
       if ($filter)   	   
	     $grid1_get = "shhandler.php?t=shngettrans&select=1";
	   else
	     $grid1_get = "shhandler.php?t=shngettrans";

	   $this->_grids[0]->set_text_column("A/A","recid","70","true"); 		 
	   $this->_grids[0]->set_text_column("ID","tid","70","true");   	
	   $this->_grids[0]->set_text_column("CID","cid","70","true");		      	      	   	   	         	   	   	   	
	   $this->_grids[0]->set_text_column("Date","tdate","80","true");	
	   $this->_grids[0]->set_text_column("Time","ttime","80","true");	
	   $this->_grids[0]->set_text_column("Status","tstatus","50","true");		   	   	   	   
	   $this->_grids[0]->set_text_column("Pay Method","payway","100","true");		   
	   $this->_grids[0]->set_text_column("Distribution","roadway","100","true");		   	   	   
	   $this->_grids[0]->set_text_column("Gross","cost","80","true");		   
	   $this->_grids[0]->set_text_column("Total","costpt","80","true");		   
	   	   		   	   	  	   
	   //$this->_grids[0]->set_datasource("check_active",array('101'=>'Active','0'=>'Inactive'),null,"value|display",true);	   
	   	   
	   $ret = $this->_grids[0]->set_grid_remote($grid1_get,null,"$x","$y","livescrolling",null,"false");							  
	
	   return ($ret);	   	
	}
	
	function show_grids() {
	   //gets
	   $cat = GetReq('cat');	
	   
	   //grid 0 
	   $datattr[] = $this->show_grid(400,440);//GetGlobal('controller')->calldpc_method("rcitems.show_grid use 400+440+1");							  
	   $viewattr[] = "left;50%";	   	   

	 /*  $grid0_get = "shhandler.php?t=shngettrans";
	   $grid0_set = "";	   
	
	   //grid 1
	   $this->_grids[1]->set_text_column("Id","tid","30","true");   	   	      	   	   	         	   	   	   	
	   $this->_grids[1]->set_text_column("Date","tdate","60","true");	
	   $this->_grids[1]->set_text_column("Time","ttime","60","true");	
	   $this->_grids[1]->set_text_column("Status","status","30","true");		   	   	   
	   $this->_grids[1]->set_text_column("Data","data","10","true");		   
	   $this->_grids[1]->set_text_column("Pay Method","payway","60","true");		   
	   $this->_grids[1]->set_text_column("Distribution","roadway","60","true");		   	   	   
	   $this->_grids[1]->set_text_column("Gross","cost","60","true");		   
	   $this->_grids[1]->set_text_column("Total","costpt","60","true");		   
	   	   		   	   	  	   
	   $wd = $this->_grids[1]->set_grid_remote($grid0_get,$grid0_set,"400","220","livescrolling",10,"false");
	   */
	   //businnes card	used to pass data from jscript
	   //$message .= $this->charts->render('usage',400,250);
	   $wd .= $this->_grids[0]->set_detail_div("StatisticDetails",400,20,'F0F0FF',$message);
	   $wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");

      /* if ($this->hasgraph)
		   $wd .= $this->show_graph('statisticscat',400,200);
	   else
		   $wd .= "<h3>".localize('_GNAVAL',0)."</h3>";
*/
	   $datattr[] = $wd;
	   $viewattr[] = "left;50%";
	   
	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);		   	
	   	
	   return ($ret);	
	}	
	
	function sidewin() { 
	}		
			
};
}
?>