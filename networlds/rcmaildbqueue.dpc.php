<?php
$__DPCSEC['RCMAILDBQUEUE_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("RCMAILDBQUEUE_DPC")) && (seclevel('RCMAILDBQUEUE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCMAILDBQUEUE_DPC",true);

$__DPC['RCMAILDBQUEUE_DPC'] = 'rcmaildbqueue';

$a = GetGlobal('controller')->require_dpc('mail/maildbqueue.dpc.php');
require_once($a);

GetGlobal('controller')->get_parent('MAILDBQUEUE_DPC','RCMAILDBQUEUE_DPC');

$__EVENTS['RCMAILDBQUEUE_DPC'][3]='cpmaildbadd';
$__EVENTS['RCMAILDBQUEUE_DPC'][4]='cpmaildbedit';
$__EVENTS['RCMAILDBQUEUE_DPC'][5]='cpmaildbdel';
$__EVENTS['RCMAILDBQUEUE_DPC'][6]='cpmaildboff';
$__EVENTS['RCMAILDBQUEUE_DPC'][7]='cpmaildbsend';

$__ACTIONS['RCMAILDBQUEUE_DPC'][3]='cpmaildbadd';
$__ACTIONS['RCMAILDBQUEUE_DPC'][4]='cpmaildbedit';
$__ACTIONS['RCMAILDBQUEUE_DPC'][5]='cpmaildbdel';
$__ACTIONS['RCMAILDBQUEUE_DPC'][6]='cpmaildboff';
$__ACTIONS['RCMAILDBQUEUE_DPC'][7]='cpmaildbsend';

$__LOCALE['RCMAILDBQUEUE_DPC'][0]='RCMAILDBQUEUE_DPC;Mail queue;Mail queue';
$__LOCALE['RCMAILDBQUEUE_DPC'][1]='_send;Send Mail Now;Send Mail now';
$__LOCALE['RCMAILDBQUEUE_DPC'][2]='_delete;Reset queue;Reset queue';
$__LOCALE['RCMAILDBQUEUE_DPC'][3]='_edit;Abort sending mail;Abort sending mail';

class rcmaildbqueue extends maildbqueue  {

    var $userlevelID;
	var $active_app;
	var $add_item, $del_item, $edit_item, $off_item, $sep;
	var $msg;	

	function rcmaildbqueue() {
	  $UserSecID = GetGlobal('UserSecID'); 	
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	 
	  
      maildbqueue::maildbqueue();	 
	  
	  $this->active_app = GetReq('app')?GetReq('app'):GetSessionParam('activeapp'); 
	  $myapp = GetReq('app');
	  if ($myapp!='root')
	    SetSessionParam('activeapp',$myapp); 
	  else
	    SetSessionParam('activeapp','');//root app 	
	  
      $this->add_item = loadTheme('aitem',localize('_add',getlocal())); 		  
      $this->del_item = loadTheme('ditem',localize('_delete',getlocal())); 
      $this->edit_item = loadTheme('eitem',localize('_edit',getlocal()));			  
      $this->off_item = loadTheme('iitem',localize('_send',getlocal()));
      $this->mail_item = loadTheme('mailitem',localize('_send',getlocal()));	   
	  $this->sep ='&nbsp;';	  
	  
	  $this->msg = null;	  						    	  
	}
	
	function event($event=null) {
	   if ($this->active_app) //change db in case of app select for create chart data
	      GetGlobal('controller')->calldpc_method('database.switch_db use '.$this->active_app);			
	 	  
	   /////////////////////////////////////////////////////////////	
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////			
	      
	    switch ($event) {
		  case 'cpmaildbadd'        : break;		 
		  case 'cpmaildbedit'       : break;		 
		  case 'cpmaildbdel'        : $this->delete_mail(); 
		                              $this->nitobi_javascript();
                                      $this->charts = new swfcharts;	
		                              $this->hasgraph = $this->charts->create_chart_data('mailqueue',"where active=0");
		                              break;		 
		  case 'cpmaildboff'        : $this->reset_list(); 
		                              $this->nitobi_javascript();
                                      $this->charts = new swfcharts;	
		                              $this->hasgraph = $this->charts->create_chart_data('mailqueue',"where active=0");
		                              break;
		  case 'cpmaildbsend'       : $this->procced_mail(); 
		                              $this->nitobi_javascript();
                                      $this->charts = new swfcharts;	
		                              $this->hasgraph = $this->charts->create_chart_data('mailqueue',"where active=0");
		                              break;		  		 
		
	      case 'cpngetqueue'        : $this->get_mailqueue_list(); break;		 
	      case 'cpnsetqueue'        : $this->save_mailqueue_list();  break;
		  default                   : 
		                              $this->nitobi_javascript();
                                      $this->charts = new swfcharts;	
		                              $this->hasgraph = $this->charts->create_chart_data('mailqueue',"where active=0");
		}							  
	}		
	
	function action($action=null) {
	
	    if (GetSessionParam('REMOTELOGIN')) 
	      $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	  
	    else
          $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);
		
	    $out .= $this->select_app();		
	  
	    switch ($action) {
		
		  case 'cpmaildbadd'        : 		 
		  case 'cpmaildbedit'       : 		 
		  case 'cpmaildbdel'        : 		 
		  case 'cpmaildboff'        : 
		  case 'cpmaildbsend'       :		  
		   	  
		  case 'cpmaildbqueue'     : 
		  default                  : 
		                             $out .= $this->show_mailqueue();
		                       
        }	  
	  
	    return ($out);
	}
	
	//override
	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   $template = $this->set_template();   		      
		   
	       $code = $this->init_grids();			

		   $code .= $this->_grids[0]->OnClick(7,'QueueDetails',$template);//,'Customertrans','vid',2);
	   
		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.grid.js');		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}	
	
	//override
	function set_template() {
	
		   $send =  seturl("t=cpmaildbsend&app=".GetReq('app')."&rec=");	
		   //$add =  seturl("t=cpmaildbadd&app=".GetReq('app'));  
	       //$edit = seturl("t=cpmaildbedit&app=".GetReq('app')."&rec=");		   
		   $del =  seturl("t=cpmaildbdel&app=".GetReq('app')."&rec=");
		   $off =  seturl("t=cpmaildbreset&app=".GetReq('app'));	 		   	
		   	   		   
		   $template .= "<A href=\"$send'+i0+'\">".$this->off_item."</A>". $this->sep;					   
		   //$template .= "<A href=\"$add\">".$this->add_item."</A>". $this->sep;		   
		   //$template .= "<A href=\"$edit'+i0+'\">".$this->edit_item."</A>". $this->sep;		   
		   $template .= "<A href=\"$del'+i0+'\">".$this->edit_item."</A>". $this->sep;		   		   		   			   
		   //$template .= "<A href=\"$off\">".$this->off_item."</A>". $this->sep;			   			   
		   $template .= "<br>";	

		   $template .= "<h4>'+update_stats_id(i3,i1,i0)+'</h4>";	
		   $template .= "<table width=\"100%\" class=\"group_win_body\">";	   
		   $template .= "<tr><td>".localize('AA',getlocal()).":</td><td><b>'+i0+'</b></td></tr>";	
		   $template .= "<tr><td>".localize('_TIMEIN',getlocal()).":</td><td><b>'+i1+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('_TIMEOUT',getlocal()).":</td><td><b>'+i2+'</b></td></tr>";		   
		   $template .= "<tr><td>".localize('_SENDER',getlocal()).":</td><td><b>'+i3+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('_RECEIVER',getlocal()).":</td><td><b>'+i4+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('_SUBJECT',getlocal()).":</td><td><b>'+i5+'</b></td></tr>";
		   $template .= "<tr><td>".localize('_BODY',getlocal()).":</td><td><b>'+unescape(i6)+'</b></td></tr>";		   				   		   	
		   $template .= "</table>";	
		   
		   $template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td>";
		   //$template .= "'+i4+'";//"'+show_body(i4)+'";		   	
		   $template .= "</td></tr></table>";	 		        
		   
		   return ($template);	
	}
	
	function delete_mail() {
	   if ($this->active_app) //change db in case of app select
	      GetGlobal('controller')->calldpc_method('database.switch_db use '.$this->active_app);
	
       $db = GetGlobal('db');
	   
	   $marec = GetReq('rec');
	   //$sSQL = "delete from mailqueue where active=1 and id=" . $marec;	 
	   //echo $sSQL . '<br>';			
	   //$result = $db->Execute($sSQL,2);
	   
	   $sSQL = "update mailqueue set mailstatus='deactivated by user'".
			  		   ",active=0".
					   " where id=" . $marec;
	   //echo $sSQL . '<br>';			
	   $result = $db->Execute($sSQL,1);	   
	   
	   $this->msg = '1 message deleted from '.$this->active_app . ' application!';	   	   	
	}
	
	function reset_list() {
	   if ($this->active_app) //change db in case of app select
	      GetGlobal('controller')->calldpc_method('database.switch_db use '.$this->active_app);
	
       $db = GetGlobal('db');
	   
	   $sSQL = "delete from mailqueue where active=0" ;
			 
	   //echo $sSQL . '<br>';			
	   $result = $db->Execute($sSQL,2);
	   
	   $this->msg = 'queue messages deleted from '.$this->active_app . ' application!';	   	
	}
	
	function procced_mail() {
	   if ($this->active_app) //change db in case of app select
	      GetGlobal('controller')->calldpc_method('database.switch_db use '.$this->active_app);
	
       $db = GetGlobal('db');
	   
	   $marec = GetReq('rec');
	   $sSQL = "select id,timein,active,sender,receiver,subject,body,altbody,cc,bcc,ishtml,encoding,origin,user,pass,name,server from mailqueue where active=1 and id=" . $marec;
			 
	   //echo $sSQL . '<br>';			
	   $result = $db->Execute($sSQL,2);
	   if (!empty($result)) {		   
	       foreach ($result as $n=>$rec) {
		       $id = $rec['id'];	     
			   $from = $rec['sender'];//$user . '@' . $domain;
		       $to = $rec['receiver'];
		       $subject = $rec['subject'];
		       $body = $rec['body'];			 			 			 
		       $altbody = $rec['altbody'];				 
		       $cc = $rec['cc'];	
		       $bcc = $rec['bcc'];				 			 
		       $ishtml = $rec['ishtml'];				 
		       $encoding = $rec['encoding']?$rec['encoding']:$this->encoding;	
		       $origin = $rec['origin'];	 
			   $user = $rec['user']; 			 		 
			   $pass = $rec['pass']; 
			   $name = $rec['name']; 
			   $server = $rec['server']; 			 			 			 
			 
               $error = $this->sendmail($from,$to,$subject,$body,$altbody,$cc,$bcc,$ishtml,$encoding,$user,$pass,$name,$server);			 
			   //update db
		       $datetime = date('Y-m-d h:s:m');
		       $active = 0;
		       $sSQL = "update mailqueue set timeout=".$db->qstr($datetime).
			           ",mailstatus=".$db->qstr($error).
			  		   ",active=" . $active .
					   " where id=" . $marec;
	           //echo $sSQL . '<br>';			
	           $result = $db->Execute($sSQL,1);			 
		       //$meter += $result->Affected_Rows();				 
			 
			   $i+=1;
		   }

		   $this->msg = '1 message(s) send from '.$this->active_app . ' application!';
	   }
	   else
	      $this->msg = '0 message(s) send from '.$this->active_app . ' application!';	   	
	}			
	
	function select_app() {
      $db = GetGlobal('db');
		 
	  $sSQL = "select count(id) from mailqueue where active=1";	
	  // echo $sSQL;
      $result = $db->Execute($sSQL,2);
	  $meter = $result->fields[0];		 
	  $m = $meter?$meter:'0';
	  
	  $sSQL = "select count(id) from mailqueue";	
	  //echo $sSQL;
      $result = $db->Execute($sSQL,2);
	  $summeter = $result->fields[0];		 
	  $sm = $summeter?$summeter:'0';	  	

      $applinks[] = seturl('t=cpmaildbqueue&app=root','[Root Application'."($m-$sm)".']'); //empty app = root app

	  foreach ($this->app_pool as $i=>$ap) {
	  
	     GetGlobal('controller')->calldpc_method('database.switch_db use '.$ap);
		  	  
         $db = GetGlobal('db');
		 
	     $sSQL = "select count(id) from mailqueue where active=1";	
	     //echo $sSQL;
         $result = $db->Execute($sSQL,2);
		 $meter = $result->fields[0];		 
		 $m = $meter?$meter:'0';
		 
	     $sSQL = "select count(id) from mailqueue";	
	     //echo $sSQL;
         $result = $db->Execute($sSQL,2);
		 $summeter = $result->fields[0];		 
		 $sm = $summeter?$summeter:'0';		 
	   			  
	     $applinks[] = seturl('t=cpmaildbqueue&app='.$ap,'['.$ap."($m-$sm)".']');
	  }
	  
	  $ret = implode('&nbsp;',$applinks) . '<br>';
	  return ($ret);
	}
	
	//override
	function show_mailqueue() {
	
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
	   
	   
	   $grid0_get = seturl("t=cpngetqueue&alpha=$alpha&apo=$apo&eos=$eos&app=".$this->active_app);
	   $grid0_set = seturl("t=cpnsetqueue&app=".$this->active_app);
		
	   $this->_grids[0]->set_text_column("ID","id","50","true");		   
	   $this->_grids[0]->set_text_column(localize('_TIMEIN',getlocal()),"timein","100","true");		   
	   $this->_grids[0]->set_text_column(localize('_TIMEOUT',getlocal()),"timeout","100","true");		   
	   $this->_grids[0]->set_text_column(localize('_SENDER',getlocal()),"sender","150","true");		   
	   $this->_grids[0]->set_text_column(localize('_RECEIVER',getlocal()),"receiver","150","true");
	   $this->_grids[0]->set_text_column(localize('_SUBJECT',getlocal()),"subject","150","true");	   
	   $this->_grids[0]->set_text_column(localize('_BODY',getlocal()),"body","150","true");
	   
       $datattr[] = $this->_grids[0]->set_grid_remote($grid0_get,$grid0_set,"550","460","livescrolling",17,"true","true");
	   $viewattr[] = "left;50%";		   		        
	      	   
	   $off =  seturl("t=cpmaildbreset&app=".GetReq('app'));
	   $message  = "<A href=\"$off\">".$this->del_item."</A>". "<br>";
	   
	   if ($this->hasgraph)
		   $message .= $this->show_graph('mailqueue','Mail Queue',seturl('t=cpmaildbqueue'));
	   else
		   $message .= "<h3>".localize('_GNAVAL',0)."</h3>";	   		   	   
	   	   	    
	   $wd .= $this->_grids[0]->set_detail_div("QueueDetails",550,20,'F0F0FF',$message);
	   $wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");	   
			  
	   $datattr[] = $wd;
	   $viewattr[] = "left;50%";
	   
	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);		   	
	   	
	   return ($ret);	
	}		
	
	//override nitobi get	
	function get_mailqueue_list() {
	   if ($this->active_app) //change db in case of app select
	      GetGlobal('controller')->calldpc_method('database.switch_db use '.$this->active_app);
	
       $db = GetGlobal('db');	
	   //tranformed posts..
	   $apo = GetReq('apo'); //echo $apo;
	   $eos = GetReq('eos');	//echo $eos; 
       $filter = GetReq('filter');
	   
       $handler = new nhandler(17,'id','Desc');	   
       $handler->sortColumn = 'id';//timein		
	   $handler->sortDirection= 'Desc';		   

	   if ($filter) {
             
             $whereClause = " where (receiver like '%$filter%' or subject like '%$filter%' or body like '%$filter%') and active=1";
       }	
	   else
	     $whereClause = ' where active=1 ';

	   	
	   
		  if (isset($_GET['id'])) {		
            $whereClause .= ' and id=' . $_GET['id'];				     
	   	  }
		  				    
	   
	      if ($letter=GetReq('alpha')) {  
	        $whereClause .= " and ( receiver like '" . strtolower($letter) . "%' or " .
		                    " receiver like '" . strtoupper($letter) . "%')";	
			//marka is lookup table...???		 
		  }			 
  
		  if ($apo) {
		    $whereClause.= " and timein>='" . convert_date(trim($apo),"-DMY",1) . "'";
		  }  
		  
		  if ($eos) {
		    $whereClause .= "and timein<='" . convert_date(trim($eos),"-DMY",1) . "'";						
		  } 				   	
   
	   $sSQL .="select id,timein,timeout,sender,receiver,subject,body from mailqueue";	
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $handler->sortColumn . " " . $handler->sortDirection ." LIMIT ". $handler->ordinalStart .",". ($handler->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $db->Execute($sSQL,2);	
	   
	   $names = array('id','timein','timeout','sender','receiver','subject','body');			 			 
	   $handler->handle_output($db,$result,$names,'id',null,$this->encoding);	
	}
	
	//override nitobi set	
	function save_mailqueue_list() {
	
	   if ($this->active_app) //change db in case of app select
	      GetGlobal('controller')->calldpc_method('database.switch_db use '.$this->active_app);
		  	
       $db = GetGlobal('db');		
	
       $handler = new nhandler(17,'id','Desc');		
	   $names = array('id','timein','timeout','sender','receiver','subject','body');		 
	   $sql2run = $handler->handle_input(null,'mailqueue',$names,'id');		
	
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
	       $action = seturl('t=cpmaildbqueue');
	
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