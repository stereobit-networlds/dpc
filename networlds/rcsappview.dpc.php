<?php
$__DPCSEC['RCSAPPVIEW_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCSAPPVIEW_DPC")) && (seclevel('RCSAPPVIEW_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCSAPPVIEW_DPC",true);

$__DPC['RCSAPPVIEW_DPC'] = 'rcsappview';

$a = GetGlobal('controller')->require_dpc('networlds/rcsapp.dpc.php');
require_once($a);

$b = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($b);

$c = GetGlobal('controller')->require_dpc('nitobi/nhandler.lib.php');
require_once($c);

GetGlobal('controller')->get_parent('RCSAPP_DPC','RCSAPPVIEW_DPC');

$__EVENTS['RCSAPPVIEW_DPC'][9]='cpngetapps';
$__EVENTS['RCSAPPVIEW_DPC'][10]='cpnsetapps';
$__EVENTS['RCSAPPVIEW_DPC'][11]='cpsavedpc';
//$__EVENTS['RCSAPPVIEW_DPC'][12]='cpmaildboff';
//$__EVENTS['RCSAPPVIEW_DPC'][13]='cpmaildbsend';

$__ACTIONS['RCSAPPVIEW_DPC'][9]='cpngetapps';
$__ACTIONS['RCSAPPVIEW_DPC'][10]='cpnsetapps';
$__ACTIONS['RCSAPPVIEW_DPC'][11]='cpsavedpc';
//$__ACTIONS['RCSAPPVIEW_DPC'][12]='cpmaildboff';
//$__ACTIONS['RCSAPPVIEW_DPC'][13]='cpmaildbsend';

$__LOCALE['RCSAPPVIEW_DPC'][0]='RCSAPPVIEW_DPC;Applications;Applications';
$__LOCALE['RCSAPPVIEW_DPC'][1]='_ID;Id;Id';
$__LOCALE['RCSAPPVIEW_DPC'][2]='_INSDATE;Register date;Register date';
$__LOCALE['RCSAPPVIEW_DPC'][3]='_delete;Deletr;Delete';
$__LOCALE['RCSAPPVIEW_DPC'][4]='_edit;Edit;Edit';
$__LOCALE['RCSAPPVIEW_DPC'][5]='_add;AAdd;Add';
$__LOCALE['RCSAPPVIEW_DPC'][6]='_GNAVAL;No Chart;No Chart';
$__LOCALE['RCSAPPVIEW_DPC'][7]='_APPNAME;Name/Cp User;Name/Cp User';
$__LOCALE['RCSAPPVIEW_DPC'][8]='_APPTYPE;Type;Type';
$__LOCALE['RCSAPPVIEW_DPC'][9]='_EXPIRE;Expires;Expires';
$__LOCALE['RCSAPPVIEW_DPC'][10]='_APPPASS;Cp Pass;Cp Pass';
$__LOCALE['RCSAPPVIEW_DPC'][11]='_TIMEZONE;Timezone;Timezone';

class rcsappview extends rcsapp  {

    var $userlevelID;
	var $add_item, $del_item, $edit_item, $off_item, $sep;
	var $msg, $encoding;
	
	var $hasgraph, $hasgraph2;	

	function rcsappview() {
	  $UserSecID = GetGlobal('UserSecID'); 	
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	 
	  
      rcsapp::rcsapp();	 
	  
      $char_set  = arrayload('SHELL','char_set');	  
      $charset  = paramload('SHELL','charset');	  		
	  if (($charset=='utf-8') || ($charset=='utf8'))
	    $this->encoding = 'utf-8';
	  else  
	    $this->encoding = $char_set[getlocal()]; 	  
	  
      $this->add_item = loadTheme('aitem',localize('_add',getlocal())); 		  
      $this->del_item = loadTheme('ditem',localize('_delete',getlocal())); 
      $this->edit_item = loadTheme('eitem',localize('_edit',getlocal()));			  
      $this->off_item = loadTheme('iitem',localize('_off',getlocal()));
      $this->mail_item = loadTheme('mailitem',localize('_send',getlocal()));	   
	  $this->sep ='&nbsp;';	  
	  
	  $this->msg = null; 
	  
	  $this->_grids[] = new nitobi("Applications");	
      //$this->_grids[] = new nitobi("Customertrans");		
	  
	  $this->ajaxLink = seturl('t=cpsapp&statsid='); //for use with...	      
	  
	  $this->hasgraph = $this->hasgraph2 = false;	  						    	  
	}
	
	function event($event=null) { 
	 	  
	   /////////////////////////////////////////////////////////////	
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////			
	      
	    switch ($event) {
		  case 'cpmaildbactives'    :     
		                              break;		 
		  case 'cpmaildbdactives'   :                    
		                              break;		 
		  case 'cpmaildbnoerror'    : 
		                              break;		 
		  case 'cpmaildboff'        :     
		                              break;
		  case 'cpmaildbsend'       :   
		                              break;

		 case 'cpsavedpc' :           GetGlobal('controller')->calldpc_method('rcsdpc.save_dpc use '.GetReq('id'));
		                              GetGlobal('controller')->calldpc_method('rcsdpc.read_dpc2show use '.GetReq('id'));
							          break;
									  									  
		 case 'cpdpcshow' :           GetGlobal('controller')->calldpc_method('rcsdpc.read_dpc2show use '.GetReq('id'));
							          break;	   
	     case 'installapp':           $this->insert_record(); 
		                              $this->error = $this->create_application_files($_POST['type'],$_POST['name'],$this->read_applications($_POST['app2copy']),$_POST['subdir']); 
		                              $this->nitobi_javascript();
                                      $this->charts = new swfcharts;	
		                              $this->hasgraph = $this->charts->create_chart_data('applications');
		                              $this->hasgraph2 = $this->charts->create_chart_data('appexpires');									  									
		                              break;	   
	     case 'newapp'  :             break;	   
	     case 'existapp':             break;
	     case 'editapp' :             $this->record = $this->get_record(GetReq('id')); 
		                              $this->nitobi_javascript();
                                      $this->charts = new swfcharts;	
		                              $this->hasgraph = $this->charts->create_chart_data('applications');//,"where active=0");
		                              $this->hasgraph2 = $this->charts->create_chart_data('appexpires');									  		 
		                              break;
	     case 'saveapp'             : $this->update_record(); 
		                              $this->nitobi_javascript();
                                      $this->charts = new swfcharts;	
		                              $this->hasgraph = $this->charts->create_chart_data('applications');//,"where active=0");
		                              $this->hasgraph2 = $this->charts->create_chart_data('appexpires');									  		 
		                              break;
		 
	     case 'delapp'              : $this->delete_application(GetReq('id'),'appname'); //NOOOOOOO...security
		                              $this->nitobi_javascript();
                                      $this->charts = new swfcharts;	
		                              $this->hasgraph = $this->charts->create_chart_data('applications');//,"where active=0");
		                              $this->hasgraph2 = $this->charts->create_chart_data('appexpires');									  		 
		                              break;									  		  		 
		
	      case 'cpngetapps'         : $this->get_apps_list(); break;		 
	      case 'cpnsetapps'         : $this->save_apps_list();  break;
		  case 'cpsapps'            : 
		  default                   : 		  
		                              $this->nitobi_javascript();
                                      $this->charts = new swfcharts;	
		                              $this->hasgraph = $this->charts->create_chart_data('applications');//,"where active=0");
		                              $this->hasgraph2 = $this->charts->create_chart_data('appexpires');									  
									  break;									  
		}							  
	}		
	
	function action($action=null) {
	
	    if (GetSessionParam('REMOTELOGIN')) 
	      $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	  
	    else
          $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);
				
	  
	    switch ($action) {
		
		  case 'cpmaildbadd'        : 		 
		  case 'cpmaildbedit'       : 		 
		  case 'cpmaildbdel'        : 		 
		  case 'cpmaildboff'        : 
		  case 'cpmaildbsend'       :
		  
	      case 'cpsavedpc'          : 
	      case 'cpdpcshow'          : $out = GetGlobal('controller')->calldpc_method('rcsdpc.show_dpc'); 
		                              die($out);
						              break; 	  
	      case 'newapp'             : $out .= $this->insert_form();
		                              break;	  
	      case 'existapp'           :	
		                              $out .= $this->application_exists(); 
	 					              break;	  
	      case 'editapp'            : $out .= $this->update_form(); 
		                              break;
	   	  case 'installapp':
	      case 'saveapp':
	      case 'delapp' :
		  case 'cpsapps'            : 
		  default                   : $out .= $this->show_apps(); 
		                             
		                       
        }	  
	  
	    return ($out);
	}
	
	//override
	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   $template = $this->set_template();   		      
		   
	       $code = $this->init_grids();			

		   $code .= $this->_grids[0]->OnClick(7,'AppDetails',$template);//,'Customertrans','vid',2);
	   
		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.grid.js');		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}	
	
	//override
	function set_template() {
	
           $edit =  seturl("t=editapp&id=");			   
	       $add =  seturl("t=newapp");  
	       $delete =  seturl("t=delapp&id=");  		   	   
	       $template = "<A href=\"$add\">".$this->add_item."</A>";
	       $template .= "<A href=\"$edit'+i2+'\">".$this->edit_item."</A>";
	       $template .= "<A href=\"$delete'+i2+'\">".$this->del_item."</A>";	

		   $template .= "<h4>'+update_stats_id(i0,i2,i4)+'</h4>";	
		   $template .= "<table width=\"100%\" class=\"group_win_body\">";	   
		   $template .= "<tr><td>".localize('AA',getlocal()).":</td><td><b>'+i0+'</b></td></tr>";	
		   $template .= "<tr><td>".localize('_INSDATE',getlocal()).":</td><td><b>'+i1+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('_APPNAME',getlocal()).":</td><td><b>'+i2+'</b></td></tr>";		   
		   $template .= "<tr><td>".localize('_APPTYPE',getlocal()).":</td><td><b>'+i3+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('_EXPIRE',getlocal()).":</td><td><b>'+i4+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('_TIMEZONE',getlocal()).":</td><td><b>'+i5+'</b></td></tr>";
		   $template .= "<tr><td>".localize('_APPPASS',getlocal()).":</td><td><b>'+i6+'</b></td></tr>";		   				   		   	
		   $template .= "</table>";	
		   
		   $template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td>";
		   $template .= "'+show_body(i2)+'";		   	
		   $template .= "</td></tr></table>";
		   
	       //$template .= $this->insert_form();		   	 		        
		   
		   return ($template);	
	}
	
	function init_grids() {

	    $bodyurl = seturl("t=cpdpcshow&id=");	
	
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
  
  return str1+' expires at '+str2;
}

function show_body() {
  var str = arguments[0];
  var appid;
  
  appid = str;  
  bodyurl = '$bodyurl'+appid;
  
  ifr = '<iframe src =\"'+bodyurl+'\" width=\"100%\" height=\"350px\"><p>Your browser does not support iframes ('+str+').</p>'+str+'</iframe>';  
  return ifr;
}
			
function init()
{
";
        foreach ($this->_grids as $n=>$g)
		  $out .= $g->init_grid($n);
	
        $out .= "\r\n}";
        return ($out);
	}	
	
	function show_apps() {
	
	   if ($this->msg) 
	     $out = $this->msg;
	   
	   $toprint .= $this->show_grids();	 
	     	
       $toprint .= $this->searchinbrowser();	   
	   
       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render();	
	   
	   //HIDDEN FIELD TO HOLD STATS ID FOR AJAX HANDLE
	   $out .= "<INPUT TYPE= \"hidden\" ID= \"statsid\" VALUE=\"0\" >";	   	    
	  
	   return ($out);		   
	}
	
	//override
	function show_grids() {
       $sFormErr = GetGlobal('sFormErr');
	
	   //gets
	   $alpha = GetReq('alpha');
	   //transformed posts !!!!
	   $apo = GetParam('apo');
	   $eos = GetParam('eos');	 
	   
	   
	   $grid0_get = seturl("t=cpngetapps&alpha=$alpha&apo=$apo&eos=$eos");
	   $grid0_set = seturl("t=cpnsetapps");
		
	   $this->_grids[0]->set_text_column("ID","id","50","true");		   
	   $this->_grids[0]->set_text_column(localize('_INSDATE',getlocal()),"insdate","150","true");		   
	   $this->_grids[0]->set_text_column(localize('_APPNAME',getlocal()),"appname","150","true");		   
	   $this->_grids[0]->set_text_column(localize('_APPTYPE',getlocal()),"apptype","150","true");		   
	   $this->_grids[0]->set_text_column(localize('_EXPIRE',getlocal()),"expire","150","true");
	   $this->_grids[0]->set_text_column(localize('_TIMEZONE',getlocal()),"timezone","150","true");	   
	   $this->_grids[0]->set_text_column(localize('_APPPASS',getlocal()),"apppass","150","true");   	   
	   
       $datattr[] = $this->_grids[0]->set_grid_remote($grid0_get,$grid0_set,"550","460","livescrolling",17,"true","true");
	   $viewattr[] = "left;50%";		   		        
	      	   			   
	   $add =  seturl("t=newapp");  	   
	   $message  = "<A href=\"$add\">".$this->add_item."</A>" . "<br>";		      	   
	   
	   if ($this->hasgraph)
		   $message .= $this->show_graph('applications','Applications Birth:',$this->ajaxLink,'stats');
	   else
		   $message .= "<h3>".localize('_GNAVAL',0)."</h3>";
		   
	   if ($this->hasgraph2)
		   $message .= $this->show_graph('appexpires','Applications Expires:',$this->ajaxLink,'stats');
	   else
		   $message .= "<h3>".localize('_GNAVAL',0)."</h3>";		   	   		   	   
	   	   	    
	   $wd .= $this->_grids[0]->set_detail_div("AppDetails",550,20,'F0F0FF',$message);
	   $wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");	   
			  
	   $datattr[] = $wd;
	   $viewattr[] = "left;50%";
	   
	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);		   	
	   	
	   return ($ret);	
	}		
		
	function get_apps_list() {
       $db = GetGlobal('db');	
	   //tranformed posts..
	   $apo = GetReq('apo'); //echo $apo;
	   $eos = GetReq('eos');	//echo $eos; 
       $filter = GetReq('filter');
	   
       $handler = new nhandler(17,'id','Asc');	   
       $handler->sortColumn = 'id';//timein		
	   $handler->sortDirection= 'Asc';
	   
	   if ($filter)      
         $whereClause = " where (appname like '%$filter%' or apptype like '%$filter%' or expire like '%$filter%')";	
	   //else
	     //$whereClause = ' where ';

	   	
	   
		  if (isset($_GET['id'])) {		
            $whereClause .= ' and id=' . $_GET['id'];				     
	   	  }
		  				    
	   
	      if ($letter=GetReq('alpha')) {  
	        $whereClause .= " and ( appname like '" . strtolower($letter) . "%' or " .
		                    " apptype like '" . strtoupper($letter) . "%')";	
			//marka is lookup table...???		 
		  }			 
  
		  if ($apo) {
		    $whereClause.= " and expire>='" . convert_date(trim($apo),"-DMY",1) . "'";
		  }  
		  
		  if ($eos) {
		    $whereClause .= "and expire<='" . convert_date(trim($eos),"-DMY",1) . "'";						
		  } 				   	
   
	   $sSQL = "select id,insdate,appname,apptype,expire,timezone,apppass from applications ";	
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $handler->sortColumn . " " . $handler->sortDirection ." LIMIT ". $handler->ordinalStart .",". ($handler->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $db->Execute($sSQL,2);	
	   
	   $names = array('id','insdate','appname','apptype','expire','timezone','apppass');			 			 
	   $handler->handle_output($db,$result,$names,'id',null,$this->encoding);	
	}
		
	function save_apps_list() {	
       $db = GetGlobal('db');		
	
       $handler = new nhandler(17,'id','Asc');		
	   $names = array('id','insdate','appname','apptype','expire','timezone','apppass');
	   $sql2run = $handler->handle_input(null,'applications',$names,'id');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($handler->debug_sql) && ($f = fopen($this->prpath . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }		
	}
	
	function show_graph($xmlfile,$title,$url=null,$ajaxid=null,$xmax=null,$ymax=null) {
	  $gx = $this->graphx?$this->graphx:$xmax?$xmax:550;
	  $gy = $this->graphy?$this->graphy:$ymax?$ymax:250;	
	
	  $ret = $title; 	
	  $ret .= $this->charts->show_chart($xmlfile,$gx,$gy,$url,$ajaxid);
	  return ($ret);
	}	
	
    function searchinbrowser() {
	       $action = seturl('t=cpsapps');
	
            $ret = "
           <form name=\"searchinbrowser\" method=\"post\" action=\"$action\">
           <input name=\"filter\" type=\"Text\" value=\"\" size=\"56\" maxlength=\"64\">
           <input name=\"Image\" type=\"Image\" src=\"../images/b_go.gif\" alt=\"\"    align=\"absmiddle\" width=\"22\" height=\"28\" hspace=\"10\" border=\"0\">
           </form>";

          $ret .= "<br>Last search: " . GetParam('filter')."<br>";

          return ($ret);
     }				

};
}		
?>