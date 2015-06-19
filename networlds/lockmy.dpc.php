<?php
$__DPCSEC['LOCKMY_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("LOCKMY_DPC")) && (seclevel('LOCKMY_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("LOCKMY_DPC",true);

$__DPC['LOCKMY_DPC'] = 'lockmy';

$v = GetGlobal('controller')->require_dpc('crypt/ciphersaber.lib.php');
require_once($v); 

$a = GetGlobal('controller')->require_dpc('networlds/rcsapp.dpc.php');
require_once($a);

$b = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($b);

$c = GetGlobal('controller')->require_dpc('nitobi/nhandler.lib.php');
require_once($c);

GetGlobal('controller')->get_parent('RCSAPP_DPC','LOCKMY_DPC');

$__EVENTS['LOCKMY_DPC'][0]='lockmy';
$__EVENTS['LOCKMY_DPC'][9]='service';
$__EVENTS['LOCKMY_DPC'][10]='viewapp';
$__EVENTS['LOCKMY_DPC'][11]='ccode';
$__EVENTS['LOCKMY_DPC'][12]='demopage';
$__EVENTS['LOCKMY_DPC'][13]='demoexpired';
$__EVENTS['LOCKMY_DPC'][14]='demorenew';
$__EVENTS['LOCKMY_DPC'][15]='jsdelapp';
$__EVENTS['LOCKMY_DPC'][16]='terms';


$__ACTIONS['LOCKMY_DPC'][0]='lockmy';
$__ACTIONS['LOCKMY_DPC'][9]='service';
$__ACTIONS['LOCKMY_DPC'][10]='viewapp';
$__ACTIONS['LOCKMY_DPC'][11]='ccode';
$__ACTIONS['LOCKMY_DPC'][12]='demopage';
$__ACTIONS['LOCKMY_DPC'][13]='demoexpired';
$__ACTIONS['LOCKMY_DPC'][14]='demorenew';
$__ACTIONS['LOCKMY_DPC'][15]='jsdelapp';
$__ACTIONS['LOCKMY_DPC'][16]='terms';

$__LOCALE['LOCKMY_DPC'][0]='LOCKMY_DPC;Lockmy records;Lockmy records';
$__LOCALE['LOCKMY_DPC'][1]='_ID;Id;Id';
$__LOCALE['LOCKMY_DPC'][2]='_INSDATE;Register date;Register date';
$__LOCALE['LOCKMY_DPC'][3]='_delete;Deletr;Delete';
$__LOCALE['LOCKMY_DPC'][4]='_edit;Edit;Edit';
$__LOCALE['LOCKMY_DPC'][5]='_add;AAdd;Add';
$__LOCALE['LOCKMY_DPC'][6]='_GNAVAL;No Chart;No Chart';
$__LOCALE['LOCKMY_DPC'][7]='_APPNAME;Name/Cp User;Name/Cp User';
$__LOCALE['LOCKMY_DPC'][8]='_APPTYPE;Type;Type';
$__LOCALE['LOCKMY_DPC'][9]='_EXPIRE;Expires;Expires';
$__LOCALE['LOCKMY_DPC'][10]='_APPPASS;Cp Pass;Cp Pass';
$__LOCALE['LOCKMY_DPC'][11]='_TIMEZONE;Timezone;Timezone';
$__LOCALE['LOCKMY_DPC'][12]='_NEW;Add New Address;Πρόσθεσε νέο';

class lockmy extends rcsapp  {

    var $userlevelID;
	var $add_item, $del_item, $edit_item, $off_item, $sep;
	var $msg, $encoding;
	
	var $hasgraph, $hasgraph2;
	
	var $ckey, $key_expire;
	var $demo;	
	
	var $def_tmz;
	var $error, $free_records, $gracedays, $freedays, $expired_records, $grace_records;
	var $tmz_id;
	var $agree;
	
	var $pay_records_link, $inv_records_link;
	var $host_mail_notifier, $client_mail_notifier;
	
	var $appattr, $useterms;
	var $dst;
	var $post;
	var $mailsender;
	
	var $notify_subject, $notify_altsubject, $notify_body, $notify_altbody;
	var $annual, $bienual;

	function lockmy() {
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
	  
	  $this->ajaxLink = seturl('t=service&statsid='); //for use with...	      
	  
	  $this->hasgraph = $this->hasgraph2 = false;
	  
	  $this->ckey = null;
	  $this->key_expire = null;	  
	  $this->demo = null;	
	  
	  $this->tmz_id = 0;//pre select GMT
	  $this->def_tmz = 20;//pre select ..."GMT (Greenwich Mean Time) Dublin Edinburgh London";
	  $this->error = null;  
	  
	  $fr = remote_paramload('LOCKMY','freerecords',$this->path);						    	 
	  $this->free_records = $fr?$fr:5;//100; 
	  
	  $fd = remote_paramload('LOCKMY','freedays',$this->path);	  
	  $this->freedays = $fd?$fd:0;
	  $fdgrace = remote_paramload('LOCKMY','gracedays',$this->path);	  
	  $this->gracedays =  $fdgrace?$fdgrace:0;	  
	  	  
	  $this->expired_records = 0; //init 
	  $this->grace_records = 0; //init	  
	  
	  $this->agree = false;//every time login must agree..
	  
	  $pcmd = remote_paramload('LOCKMY','paycmd',$this->path);
	  $paycmd = $paycmd?$paycmd:'pricelist';
	  $this->pay_records_link = seturl("t=$paycmd&p=@",writecl('Renew Now','#FFFFFF','#FF0000'));
	  $this->inv_records_link = seturl("t=$paycmd&p=@",writecl('Renew Now','#FFFFFF','#00FF00'));	  
	  
	  $hm = remote_paramload('LOCKMY','hostmailnotice',$this->path);	  
	  $this->host_mail_notifier =  $hm?$hm:null;
	  $cm = remote_paramload('LOCKMY','clientmailnotice',$this->path);	  
	  $this->client_mail_notifier =  $cm?$cm:null;	 
	  
	  $this->appattr = array(); 
	  $this->dst = 1;	  
	  $this->post = false;
	  
	  $this->useterms = remote_paramload('LOCKMY','terms',$this->path);	 
	  
	  $this->notify_subject = '%0 expire in %2 day(s). Please renew!';
	  $this->notify_altsubject = '%0 expired. Please renew!';	  
	  $this->notify_message = '%0 expire in %2 days(s). Please renew! %1';
	  $this->notify_altmessage = '%0 expired. Please renew! %1';
	  
	  $this->mailsender = remote_paramload('LOCKMY','mailsender',$this->path);
	  
	  $this->annual = 365; //1 year
	  $this->bienual = 730;//2 years	  	  	   
	}
	
	function event($event=null) { 
	 	  
	   /////////////////////////////////////////////////////////////	
	   //if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////
	   $this->js_datechooser();			
	      
	    switch ($event) {

	     case 'terms'     :           if ($user = GetGlobal('UserID')) {
		                                $this->agree = $this->terms_agreement();
										$this->carr = $this->select_applications($user,'apptype',GetParam('filter'));
									  }
		                              break;  
	   
	     case 'ccode'     :           if ($user = GetGlobal('UserID')) {
		                                $this->ckey = $this->create_key();
		                                $this->carr = $this->select_applications($user,'apptype',GetParam('filter'));
									  }
		                              break;
									    
	     case 'installapp':           if ($user = GetGlobal('UserID')) {
		                                if (!$this->application_exists(GetParam('name'))) {
		                                  if ($this->post = $this->insert_record()) {
		                                    $this->ckey = $this->create_key(GetParam('name'));
										    $this->carr = $this->select_applications($user,'apptype',GetParam('filter')); 	
										  } 	
										}  
										
		                                  //$this->carr = $this->select_applications($user,'apptype',GetParam('filter'));
										
 	                                    /*$this->nitobi_javascript();
                                        $this->charts = new swfcharts;	
		                                $this->hasgraph = $this->charts->create_chart_data('applications');
		                                $this->hasgraph2 = $this->charts->create_chart_data('appexpires'); */									  									
									  }	
		                              break;
									  	   
	     case 'newapp'  :             //if ($user = GetGlobal('UserID')) 
		                                //$this->carr = $this->select_applications($user,'apptype',GetParam('filter'));
		                              break;
									  	   
	     case 'existapp':             if ($user = GetGlobal('UserID')) 
		                                $this->carr = $this->select_applications($user,'apptype',GetParam('filter'));
		                              break;
									  
	     case 'editapp' :             if ($user = GetGlobal('UserID')) {
		                                $this->record = $this->get_record(); 
		                                //$this->carr = $this->select_applications($user,'apptype',GetParam('filter'));
										
		                                /*$this->nitobi_javascript();
                                        $this->charts = new swfcharts;	
		                                $this->hasgraph = $this->charts->create_chart_data('applications');//,"where active=0");
		                                $this->hasgraph2 = $this->charts->create_chart_data('appexpires');*/									  		 
									  }	
		                              break;
									  
	     case 'saveapp'             : if ($user = GetGlobal('UserID')) {
		                                $this->post = $this->update_record();
									    if ($this->post) 
		                                  $this->carr = $this->select_applications($user,'apptype',GetParam('filter'));
										else
										  $this->record = $this->get_record(); 									  		 
										  
		                                /*$this->nitobi_javascript();
                                        $this->charts = new swfcharts;	
		                                $this->hasgraph = $this->charts->create_chart_data('applications');//,"where active=0");
		                                $this->hasgraph2 = $this->charts->create_chart_data('appexpires');*/										
									  }
									  elseif ($this->demo = GetParam('demo')) {//allow demo to update demo record
									    $this->update_record();	
									  }	
		                              break;
									  
		 case 'jsdelapp'            : if ($user = GetGlobal('UserID')) {
		                                $this->js_delete();
									    $this->carr = $this->select_applications($user,'apptype',GetParam('filter'));
									  }	
		                              break;
									  
	     case 'delapp'              : if ($user = GetGlobal('UserID')) {
		                                $this->delete_application();
										$this->carr = $this->select_applications($user,'apptype',GetParam('filter')); 
		                                /*$this->nitobi_javascript();
                                        $this->charts = new swfcharts;	
		                                $this->hasgraph = $this->charts->create_chart_data('applications');//,"where active=0");
		                                $this->hasgraph2 = $this->charts->create_chart_data('appexpires');*/
									  }										  		 
		                              break;									  		  		 
		
	      case 'cpngetapps'         : if ($user = GetGlobal('UserID')) $this->get_apps_list(); break;		 
	      case 'cpnsetapps'         : if ($user = GetGlobal('UserID')) $this->save_apps_list(); break;		  
		  
		  case 'demopage'           :
		  case 'demoexpired'        :
		  case 'demorenew'          : $this->demo = 1;
		                              break; 
		  case 'viewapp'            :
		  case 'service'            : 
		  default                   : if ($user = GetGlobal('UserID')) {		  
		                                /*$this->nitobi_javascript();
                                        $this->charts = new swfcharts;	
		                                $this->hasgraph = $this->charts->create_chart_data('applications');//,"where active=0");
		                                $this->hasgraph2 = $this->charts->create_chart_data('appexpires');*/
									  
									    $this->carr = $this->select_applications($user,'apptype',GetParam('filter'));									  
									  }	
									  break;									  
		}							  
	}		
	
	function action($action=null) {
	
	    /*if (GetSessionParam('REMOTELOGIN')) 
	      $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	  
	    else
          $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);
		*/		
	  
	    switch ($action) {
		
	      case 'ccode'              : if ($user = GetGlobal('UserID')) {
		                                $out .= $this->create_code();
		                                $out .= $this->show_applications();
									  }
									  else
									    $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');
		                              break;		
	      case 'newapp'             : if ($user = GetGlobal('UserID')) {
		                                $out .= $this->insert_form();
		                                //$out .= $this->show_applications();
									  }
									  else
									    $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');
		                              break;	  
	      case 'existapp'           : if ($user = GetGlobal('UserID')) {	
		                                $out .= $this->application_exists(); 
									  }
									  else
									    $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');	
	 					              break;	  
	      case 'editapp'            : if ($user = GetGlobal('UserID')) {
		                                $out .= $this->update_form(); 
		                                //$out .= $this->show_applications();
									  }
									  else
									    $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');	
		                              break;
	   	  case 'installapp'         : if ($user = GetGlobal('UserID')) {
		                                if (!$this->error) 
		                                  $out .= $this->create_code();
										else
										  $out .= $this->error;  
										
										if ($this->post)   
										  $out .= $this->show_applications();
										else
										  $out .= $this->insert_form();  
									  }
									  else
									    $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');
										
		  case 'demopage'           :
		  case 'demoexpired'        :
		  case 'demorenew'          : break; //dummy actions
		  											
	      case 'saveapp'            : if (!$this->demo) {
		                                if ($this->post)
		                                  $out .= $this->show_applications();
										else 
										  $out .= $this->update_form();  
		                              }
									  break; 
	      case 'delapp'             : 
		  case 'jsdelapp'           :
		  case 'viewapp'            : 
		  case 'terms'              : 		  
		  case 'service'            :
		  default                   : if ($user = GetGlobal('UserID')) {
		                                //$out .= $this->show_apps(); 
		                                $out .= $this->show_applications();
									  }
									  else
									    $out .= GetGlobal('controller')->calldpc_method('shlogin.html_form');	
		                             
		                       
        }	  
	  
	    return ($out);
	}
	
	function js_datechooser() {
	
      if (iniload('JAVASCRIPT')) {
	      
		   /*$code = "		   
		function FunctionEx6(objDate)
		{
			var ndExample5 = document.getElementById('expire');
			ndExample5.DateChooser.setEarliestDate(objDate);
			ndExample5.DateChooser.updateFields();

			return true;
		}		   
		   ";*/
	  
		   $js = new jscript;
           //$js->load_js($code,"",1);		   		   
           $js->load_js('datechooser.js');;			   
		   unset ($js);
	  }		  		
	}
	
	function js_delete() {
	  $recid = GetReq('id');
	  $recname = $this->decode($recid);	
	
      if (iniload('JAVASCRIPT')) {
		
	   $code = "
function delalert() {

  if (confirm('Delete [".$recname."] record. Are you sure?'))
    window.location = 'service.php?t=delapp&id=".$recid."';
  else  
    window.location = 'service.php';
}	
window.onload=function(){
  delalert();
}	
";	  
		   $js = new jscript;		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		  	
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

	    $bodyurl = seturl("t=service&id=");	
	
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
  
  //ifr = '<iframe src =\"'+bodyurl+'\" width=\"100%\" height=\"350px\"><p>Your browser does not support iframes ('+str+').</p>'+str+'</iframe>';  
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
	     	
       //$toprint .= $this->searchinbrowser();	   
	   
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
	
	function cmdline($bsearch=null,$badd=null,$bback=null) {
	
	   if ($bsearch) {
         $search = $this->searchinbrowser();
	     $viewdata[] = $search;
	     $viewattr[] = "left;".$bsearch."%";	   	 	   	
	   }
	   
	   if ($badd) {
	     $newlink = seturl('t=newapp',loadTheme('aitem',localize('_NEW',getlocal())));//'New');
	     $viewdata[] = $newlink;
	     $viewattr[] = "right;".$badd."%";	   
	   }
	   
	   if ($bback) {
	     $newlink = seturl('t=service',loadTheme('eitem',localize('_BACK',getlocal())));//'New');
	     $viewdata[] = $newlink;
	     $viewattr[] = "right;".$badd."%";	   
	   }	   
	   
	   if (!empty($viewdata)) {
	     $myrec = new window('',$viewdata,$viewattr);
	     $out .= $myrec->render("center::100%::0::content::left::0::0::");
	     unset ($viewdata);
	     unset ($viewattr);
	   
	     $out .= '<hr/>';	 
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
		 
	     if (defined('SHUSERS_DPC')) {
	       $tmzid = GetGlobal('controller')->calldpc_method('shuser.get_user_timezone');
	     }
	     else
	       $tmzid = 0;//pre select GMT	  
		   
	     $tmz_today = $this->make_gmt_date(null,$tmzid);  		   		 
		 		 
		 $expgoto = 'http://lockmy.networlds.org/rexpired.html';
		 $rengoto = 'http://lockmy.networlds.org/rrenew.html';
		 
         $out = $this->cmdline(80,null,20); 
		 
	     //if ($this->msg) $out .= $this->msg;		 		 		 
	
	   //print_r($this->record);
	   //if (is_array($this->record)) {
         $myaction = seturl("t=saveapp");	


	     $form = new form(localize('LOCKMY_DPC',getlocal()), "LOCKMY", FORM_METHOD_POST, $myaction, false);
		 
         $form->addGroup  ("x",	"Please enter application details.");	
         $form->addGroup  ("y",	"Please enter configuration details.");
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
	
	function demo_form($id=null,$goto=null) {
	   $today = date('Y-m-d');
	   $yesterday  = mktime(0, 0, 0, date("m")  , date("d")-3, date("Y"));
	   $dstart = date('mdY',$yesterday);
	   $tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"));
	   $dend = date('mdY',$tomorrow) ;	   
	   $myapp = $id?$id:'lockmy.networlds.org';
	   
	   $record = $this->get_record($myapp);
	   
	   //$m = $this->make_gmt_date(null,-12);	
	
	   //print_r($this->record);
	   if (is_array($record)) {
         $myaction = $goto?$goto:'demopage.php';//seturl("t=saveapp");	

	     $form = new form(localize('LOCKMY_DPC',getlocal()), "LOCKMY", FORM_METHOD_POST, $myaction, false);
		 
         $form->addGroup  ("x",	"Please enter application details.");		 
         $form->addGroup  ("y",	"Please enter configuration details.");
         $form->addGroup  ("z",	"Please enter notification details.");
		 		 		 
         $form->addElement("x", new form_element_text("http://",  "name",		'lockmy.networlds.org',			"forminput",			50,				50,	1));		   		     
         $form->addElement("x", new form_element_checkbox("Enabled",  "enable",		$record[8],			"forminput"));
		 		   		 			 			 
		 $selectedtz = get_selected_option_fromfile($record[3],'timezones');
	     $form->addElement("x",	new form_element_combo_file (localize('TimeZone',getlocal()),     "zone",	    $record[3],				"forminput",	        5,				0,	'timezones'));			 
		 
         //$form->addElement("x", new form_element_date("Expire",  "LOCKMY",   "expire",		$record[4],			"forminput",			20,				10,	0));			 
         $form->addElement("x", new form_element_text("Expire",  "expire",		$record[4],   		 "datechooser dc-dateformat='Y-n-j' dc-iconlink='images/datechooser.png' dc-weekstartday='1' dc-startdate='06241999' dc-latestdate='$dend' dc-earliestdate='$dstart'",			20,				10,	0));
         $form->addElement("x", new form_element_text("Gracedays",  "gdays",		$record[5],			"forminput",			3,				3,	0));			 
		 
         $form->addElement("y", new form_element_checkbox("Silent mode",  "silence",		$record[9],			"forminput"));		 
         $form->addElement("y", new form_element_text("Promt",  "pass",			$record[2],			"forminput",			50,				50,	0));		 
         $form->addElement("y", new form_element_text("When expires goto",  "expgoto",		$record[6],			"forminput",			50,				50,	1));		 
         $form->addElement("y", new form_element_text("When renew goto",  "rengoto",		$record[7],			"forminput",			50,				50,	1));		 		 
		 
         $form->addElement("z", new form_element_checkbox("Enable",  "usemail",		$record[10],		"forminput"));			 			 		 
         $form->addElement("z", new form_element_text("Owner e-mail",  "ownermail",	$record[11],			"forminput",			50,				50,	1));			 			 		 		 
         $form->addElement("z", new form_element_text("CC #1",  "cc1",		$record[12],			"forminput",			50,				50,	0));			 			 		 
         $form->addElement("z", new form_element_text("CC #2",  "cc2",		$record[13],			"forminput",			50,				50,	0));		 			 			 		 		          		 
		 $form->addElement("z", new form_element_text("Subject when in grace period",  "subject", GetParam('subject')?GetParam('subject'):($this->record[14]?$this->record[14]:$this->notify_subject),			"forminput",			50,				50,	1));			 			 		 			 		 
         $form->addElement("z", new form_element_text("Message when in grace period",  "message", GetParam('message')?GetParam('message'):($this->record[15]?$this->record[15]:$this->notify_message),			"forminput",			50,				50,	1));
		 $form->addElement("z", new form_element_text("Subject when expired",  "altsubject",      GetParam('altsubject')?GetParam('altsubject'):($this->record[16]?$this->record[16]:$this->notify_altsubject),			"forminput",			50,				50,	1));			 			 		 			 		 
         $form->addElement("z", new form_element_text("Message when expired",  "altmessage",      GetParam('altmessage')?GetParam('altmessage'):($this->record[17]?$this->record[17]:$this->notify_altmessage),			"forminput",			50,				50,	1));			 			 		 		 
         //$form->addElement("z", new form_element_text("Send SMS to",  "sms",			$record[18],			"forminput",			50,				50,	1));         		 
		 		 
		 		 		 		 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("type", 'balexiou@stereobit.com'));//<<<<<<<<<<<<<<<<<
		 								 
         $form->addElement		(FORM_GROUP_HIDDEN,		new form_element_hidden ("demo", "demo")); //hidden demo var
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
		
		  $f = $this->isfree_record($apptype);
		  $free = $f?$f:0;
		
	      $sSQL2 = "insert into applications (enable,isfree,insdate,apptype,appname,apppass,timezone,tmzid,expire,gracedays,linkexpire,linkrenew,silence,usemail,ownermail,ccmail,subject,message,altsubject,altmessage) values (";
		  $sSQL2 .= GetParam('enable') . ",";
		  $sSQL2 .= $free . ",";		  
		  $sSQL2 .= $db->qstr($tmz_today) .  ",";		  
		  $sSQL2 .= $db->qstr($apptype) . ",";
		  $sSQL2 .= $db->qstr($appname) . ",";
		  $sSQL2 .= $db->qstr(GetParam('pass')) . ",";
		  $sSQL2 .= $db->qstr($timezone) . ",";
		  $sSQL2 .= $tmzid . ",";		
		  $sSQL2 .= $db->qstr(GetParam('expire')) . ",";		
		  $sSQL2 .= $db->qstr(GetParam('gdays')) . ",";
		  $sSQL2 .= $db->qstr(GetParam('expgoto')). ",";
		  $sSQL2 .= $db->qstr(GetParam('rengoto')). ",";		
		  $sSQL2 .= GetParam('silence')? GetParam('silence') : 0 ;
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
		  $sSQL2 .= ")";						

          $db->Execute($sSQL2,1);		
		  //echo $sSQL2;
		  if ($a = $db->Affected_Rows()) {
		    //echo 'z';
		    $this->msg = writecl("Record added successfully!",'#FFFFFF','#00FF00');
			  			  
            ////////////////////////////////////////////////// send notice mail
		    if ($this->host_mail_notifier)
		      $this->mailto($this->host_mail_notifier,'User insert a record',$this->makehtmlbody($apptype . ' add a new record'));
		  
		    return true;		  		   		  
		  }
		  else
		    $this->msg .= writecl("Record NOT inserted!",'#FFFFFF','#FF0000');
		  
		}
		  
		return false;  
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
	function delete_application() {
        $db = GetGlobal('db'); 
		
		//$sSQL = "delete from applications where ";
		$sSQL = "update applications set active=0 where "; 
		$sSQL .= "appname=" . $db->qstr($this->decode(GetReq('id'))) . " and apptype=" . $db->qstr(decode(GetGlobal('UserID')));
		  
        $db->Execute($sSQL,1); 		  
	    //echo $sSQL;
		if ($a = $db->Affected_Rows()) {
		  //echo 'z';
		  $this->msg = writecl("Record removed successfully!",'#FFFFFF','#00FF00');
		  
          ////////////////////////////////////////////////// send notice mail
		  if ($this->host_mail_notifier)
		    $this->mailto($this->host_mail_notifier,'user delete a record',$this->makehtmlbody($apptype . 'remove a record'));
		  
		  return true;		  		   		  		  
		}						
		//else
		  //$this->msg = writecl("Record NOT deleted!",'#FFFFFF','#FF0000');
		  
		return false;  
	}
	
	function check_record() {
	    //echo 'z';
		if (!GetParam("name")) {
		  $this->msg .= writecl("Please add a valid name!",'#FFFFFF','#FF0000');
		  return false; //NAME required    
		} 
		if ((stristr(GetParam("name"),'http')) or (stristr(GetParam("name"),'://'))) {
		  $this->msg .= writecl("Not a valid url name!",'#FFFFFF','#FF0000');
		  return false; //invalid form of url  		
		}	
	    if ((GetParam("ownermail")) && (checkmail(GetParam("ownermail"))==false)) {
		   $this->msg .= writecl("Not a valid email!",'#FFFFFF','#FF0000');
		   return false;
		}  
	    if ((GetParam("cc1")) && (checkmail(GetParam("cc1"))==false)) {
		   $this->msg .= writecl("Not a valid email!",'#FFFFFF','#FF0000');
		   return false;
		} 
	    if ((GetParam("cc2")) && (checkmail(GetParam("cc2"))==false)) {
		   $this->msg .= writecl("Not a valid email!",'#FFFFFF','#FF0000');
		   return false;
		} 
		
		return true;				 	
	}
	
	//override
	function get_record($name=null) {
        $db = GetGlobal('db');
		$id = GetParam('id')?GetParam('id'):GetReq('id');	
	    
		if (!$name)
		  $name = $this->decode($id);//called by the list  
	
	    $sSQL2 = "select appname,apptype,apppass,timezone,expire,gracedays,linkexpire,linkrenew,enable,silence,usemail,ownermail,ccmail,subject,message,altsubject,altmessage from applications where active=1 and ";
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
						
		//print_r($res);
		return ($res);								
	}
	
	//override
	function select_applications($id,$key=null,$letter=null) {
        $db = GetGlobal('db'); 
		
		$sSQL = "select id,enable,insdate,paydate,paydays,appname,timezone,expire,gracedays,tmzid,isfree from applications where active=1";
		
		if ($key) 
		  $sSQL .= " and ". $key . "=" . $db->qstr(decode($id)); 
		  
		if ($letter) {
		  $sSQL .= "and (appname like '%" . strtolower($letter) . "%' or " .
		                "appname like '%" . strtoupper($letter) . "%')";
		}
		
		$sSQL .= " limit 100"; //outer limit //order by id 			
		  
		//echo $sSQL;
	    $resultset = $db->Execute($sSQL,2);
		//print_r($resultset);  			 
		foreach ($resultset as $i=>$rec)
		  $ret[] = $rec;
		//print_r($ret);  
		  	  
	    return ($ret);	 		
	}	
	
	function header_applications() {
	
	   if ($this->msg) $out = $this->msg; 
	   
	   if ($this->carr) {	   	   
         $out .= $this->cmdline(80,20);  		    
	   //else cmdline called at input record	 
		   
	   $symbol1 = 'Attributes';//writecl("___",'#FFFFFF','#FFFFFF');//white
	   $viewdata[] = $symbol1;
	   $viewattr[] = "left;15%";		   		
		   
	   //$viewdata[] = "&nbsp;";
	   //$viewattr[] = "left;5%";		      
		   
	   $viewdata[] = "Url";
	   $viewattr[] = "left;25%";		   
		   
	   $viewdata[] = "Timezone";;
	   $viewattr[] = "left;50%";	
		   
	   $viewdata[] = "Expires";
	   $viewattr[] = "left;10%";		
		   
	   //$viewdata[] = "&nbsp;";//"Gracedays";
	   //$viewattr[] = "right;5%";			   	   
		   	   	   	   
		   
       $myrec = new window('',$viewdata,$viewattr);
       $out .= $myrec->render("center::100%::0::content::left::0::0::");
       unset ($viewdata);
       unset ($viewattr);				  		 
	   
	   return ($out.'<hr/>');			
	   
	   }//no apps
	   
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
		   
		   $viewdata[] = ($rec['appname']? seturl("t=ccode&id=".$this->encode($rec['appname']),$rec['appname']) : "&nbsp;");
		   $viewattr[] = "left;25%";		   
		   
		   $viewdata[] = ($rec['timezone']?$rec['timezone']:"&nbsp;");
		   $viewattr[] = "left;50%";	
		   
		   $viewdata[] = ($rec['expire']? seturl("t=editapp&id=".$this->encode($rec['appname']),$rec['expire']):"&nbsp;");
		   $viewattr[] = "left;10%";		
		   
		   $viewdata[] = ($rec['gracedays']?$rec['gracedays']:"&nbsp;0");
		   $viewattr[] = "right;5%";			   	   
		   	   	   	   
		   
	       $myrec = new window('',$viewdata,$viewattr);
	       $toprint .= $myrec->render("center::100%::0::content::left::0::0::");
	       unset ($viewdata);
	       unset ($viewattr);			
		}
		
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
		  
		///////////////////////////////////////////////////////////// extra records pay mechanism
		//$max = count($ret);
		/*if ($meter > $this->free_records) {
		  $this->payfor = $meter - $this->free_records;
		  $topay .= writecl("^ " .$this->payfor . " extra record(s) must be paid to continue work properly!",'#FFFFFF','#FF0000');
		  //pay link
		  $param = encode(decode(GetParam('UserID')).';'.$this->payfor);
		  $topay .= str_replace('@',urlencode($param),$this->pay_records_link);//seturl('t=viewcart&p='.$param,writecl('Pay Now','#FFFFFF','#FF0000'));
		} */ 		
		
		///////////////////////////////////////////////////////////// expired records mechanism
		if ($this->expired_records) {
		  $this->payfor += $this->expired_records; //plus extra records
		  //echo $this->payfor;
		  $topay .= writecl("* ". $this->expired_records . " expired record(s) must be paid to continue work properly!",'#FFFFFF','#FF0000');
		  //second pay link
		  //$param = $this->encode(decode(GetParam('UserID')).';'.$this->expired_records);
		  //$topay .= str_replace('@',urlencode($param),$this->pay_records_link);
		}  
		
		/////////////////////////////////////////////////////////////  records in grace period mechanism
		if ($this->grace_records) {
		  $this->payfor += $this->grace_records; //plus extra records		
		  $topay .= writecl("+ ".$this->grace_records ." in grace period! Renew now to keep continue work properly!",'#FFFFFF','#0000FF');
		  //third pay link		  
		  //$param = $this->encode(decode(GetParam('UserID')).';'.$this->grace_records);		  
		  //$topay .= str_replace('@',urlencode($param),$this->pay_records_link);		  		
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
	   }
	   else
	     $toprint .= "No applications !<br/>";//$this->bulkiform();
		 		 
		 
       $mywin = new window(''/*$this->title*/,$toprint);
       $out .= $mywin->render("center::100%::0::content::center::0::0::");
	   
	   $out .= "<hr/>";	   
	   
       $mywin2 = new window('',$topay);
       $out .= $mywin2->render("center::100%::0::content::::0::0::");	   		 
	   
	   return ($out);			
	}
	
	function create_key($name=null) {
        $db = GetGlobal('db');	
	    
		if (!$name)//when no submit a new..
		  $name = $this->decode(GetReq('id'));  //get selected
	
	    $sSQL2 = "select appname,apptype,apppass,timezone,expire,gracedays from applications where active=1 and ";
		$sSQL2 .= "appname=" . $db->qstr($name);

		//echo $sSQL2;
	    $resultset = $db->Execute($sSQL2,2);  			 	
		
		$appname = $resultset->fields['appname'];
		$apptype = $resultset->fields['apptype'];
		$appass = $resultset->fields['apppass'];
		$tz = $resultset->fields['timezone'];
		$expire = $resultset->fields['expire'];
		$gracedays = $resultset->fields['gracedays'];
		
		$this->key_expire = $expire;
		//$a = encode($appname); $b = decode($a);
		//echo $a,' ',$b;
		
		$parts = $appname.'<DML>'.$apptype.'<DML>';
		//echo $parts,'<br>'; 	
		//echo $this->array2str($result->fields),'<br>'; 		
		
		//add pragma to set in a static length
		$k = str_pad($parts, 98, "1234567", STR_PAD_RIGHT);
		//echo $k;		
		$key = $this->my_encode($k);
		
		/*$m = strlen($parts);
		$mp = 88 - $m;
		if ($mp>0) {
		  //echo 'a';
		  $rr = str_repeat('x',$mp);
		  $k = $parts . $rr;
		  $key = $this->my_encode($k);
		}  
		else {
		  //echo 'b';
		  $k = $parts;
		  $key = $this->my_encode($k);//secure long strings
		} */
		 
		//echo $k,'<br>';
		//echo $this->encode($k),'<br>';
		//$a = $this->my_encode2($k);
		//echo '<br>',$a;
		//$b = $this->my_decode2($a);
		//echo '<br>',$b;
		//replace +
		//$mkey = str_replace('+','<SYN>',$key);
		//echo $mkey;
		//return (str_replace("/","\\",$key));	
		return ($key);
	}
	
	function create_code() {
	
	    if (!$this->ckey) return;  //DISABLED NEW WAY ??
	
	    $dkey = $this->my_decode($this->ckey);
		$p = explode('<DML>',$dkey);
		$appname = $p[0]; //domain or page
		$apptype = $p[1]; //user
		$pragma = $p[2];  //pragma
		$exp = date_parse($this->key_expire);
		$etm = mktime(12,0,0,$exp['month'],$exp['day'],$exp['year']);
		$expire_tag = date('D, d M Y h:m:s',$etm); //echo '>',$expire_tag;
		
		$app = $appname;//GetReq('id')?$this->decode(GetReq('id')):GetParam('name'); //id request or new app name
	
//<META HTTP-EQUIV=\"CACHE-CONTROL\" CONTENT=\"NO-CACHE\">
//<META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">	
        $data = "
              <p>Copy and paste this javascript code into HEAD section of your html page. <br/> ".writecl("WARNING: This code snippet and service functionality is only for <B>".$app."</B>",'#FFFFFF','#FF0000')." </p>
              <textarea name=\"jscode\" cols=\"120\" rows=\"8\" wrap=\"VIRTUAL\" readonly>
<META HTTP-EQUIV=\"Expires\" CONTENT=\"$expire_tag GMT\">			  
<script type=\"text/javascript\">
var myHost = ((\"https:\" == document.location.protocol) ? \"https://\" : \"http://\");
document.write(unescape(\"%3Cscript src='\" + myHost + \"lockmy.networlds.org/service.php?c=<@KEY@>' type='text/javascript'%3E%3C/script%3E\"));
</script>
              </textarea>
			  <br/>
			  <br/>
			  <br/>
";
	
	  $out = str_replace('<@KEY@>',$this->ckey,$data);
	  //$out = str_replace('<@KEY@>',$appname."&m=".$apptype,$data);	  //2nd way
	
	  return ($out);
	}
	
	function verify_code($code=null,$mail=null) {
       $db = GetGlobal('db');
	   $today = date('Y-m-d');//handle timezone...	
	   //echo $today;   
	   $v = array();	
	   $status = 0;
	   //echo $code;
	   //test self
	   //$code = "BmgKbw83U28AMAooVCkDMVA4CigAdwVqViUAaFIyA3FSfg9qDSQFNQBmXmcCMFBgUjpRfwduBmlXI1URBnEEcQZhCnIPMVNrAD8KOFRzA3FQPgozAG0%3D";
	
	   if ($code) {//2 = str_replace("\\","/",$key)) {
	     
		 if ($mail) {//2nd method without crypt
		 
           $sSQL2 = "select appname,enable,apptype,apppass,timezone,expire,gracedays,tmzid,linkexpire,linkrenew,silence,insdate,paydate,isfree,usemail,ownermail,ccmail,subject,message,altsubject,altmessage from applications where active=1 and enable=1 and";
		   $sSQL2 .= " appname=" . $db->qstr($code);
		   $sSQL2 .= " and apptype=" . $db->qstr($mail);		 
		 }
		 else {
		   //1st check
		   //$dcode = str_replace('<SYN>','+',$code);//rawurldecode($code);
		   $mycode = $this->my_decode($code);//,1);
           //echo $mycode,'<br>',$code;
		   $p = explode('<DML>',$mycode);	   
	       //print_r($p);
		   
	       $sSQL2 = "select appname,apptype,enable,apppass,timezone,expire,gracedays,tmzid,linkexpire,linkrenew,silence,insdate,paydate,paydays,isfree,usemail,ownermail,ccmail,subject,message,altsubject,altmessage from applications where active=1 and enable=1 and";
		   $sSQL2 .= " appname=" . $db->qstr($p[0]);
		   $sSQL2 .= " and apptype=" . $db->qstr($p[1]);
         }
		 //echo $sSQL2;
	     $resultset = $db->Execute($sSQL2,2); 	 
		 
		 if (($resultset) && (!empty($resultset->fields))) {
		   $appname = $resultset->fields['appname'];
		   $apptype = $resultset->fields['apptype'];
		   $enable = $resultset->fields['enable'];		   		   
		   $apppass = $resultset->fields['apppass'];
		   $tz = $resultset->fields['timezone'];
		   $expire = $resultset->fields['expire'];
		   $gracedays = $resultset->fields['gracedays'];		
		   $tmzid = $resultset->fields['tmzid'];
		   $expgoto = $resultset->fields['linkexpire'];
		   $rengoto = $resultset->fields['linkrenew']; 
		   $silence = $resultset->fields['silence'];
		   $insdate = $resultset->fields['insdate'];
		   $paydate = $resultset->fields['paydate'];
		   $paydays = $resultset->fields['paydays'];		   		   
		   $isfree = $resultset->fields['isfree'];		   		   		  
		   $usemail = $resultset->fields['usemail'];
		   $ownermail = $resultset->fields['ownermail'];
		   $ccmail = $resultset->fields['ccmail'];		   
		   $subject = $resultset->fields['subject'];
		   $message = $resultset->fields['message'];
		   $altsubject = $resultset->fields['altsubject'];		   
		   $altmessage = $resultset->fields['altmessage'];		   		   		    
		   
		   //if (!$enable)
		     //return false; //sql query handle 

           if (!$isfree) {
             $s = $this->check_paid_record($insdate,$paydate,$tmzid,$paydays);
			 //echo $s,'>';
		     if ($s<0) 
			   return false; //not paid record
		   }	 
		   
		   $tokens = array(0=>$appname,1=>$rengoto);
		   
		   //2nd check reverse...error not match ????
		   /*$k = encode($appname.';'.$apptype);
		   $key = rawurlencode($k);
           echo $key,'<br>',$code,'<br>',$k;
		   
		   if ($k===$code) {*/
		   
		     $tmz_today = $this->make_gmt_date(null,$tmzid);		 
		   
	         $expres = $this->date_diff($tmz_today,$expire);
			 //echo '>',$expres,'>';
			 
	         if ($expres>0) {
	           //not expired yes
			   $status = 0;
	         }
	         else {
	           if (($gracedays) && ($gracedays>abs($expres))) {
		         //into grace period..days left
		         $status = ($gracedays - abs($expres)) * 1;//mul 1... when 1-1=0;
				 //echo $status,'gin>';
				 ////////////////////////////////////////////////// send grace mail
				 //$tell = $this->makehtmlbody($appname.' will expire. Please renew!');
		         //$this->mailto($apptype,$appname.' expire in '. $status.' day(s).',$tell);
				 $tokens[] = $status?$status:'0';
				 $this->notify_mail($tokens,$apptype,null,$subject,$message);
				 
				 if ($usemail)
				   $this->notify_mail($tokens,$ownermail,$ccmail,$subject,$message);				 				 	
		       }
			   /*elseif (($gracedays) && ($gracedays==abs($expres))) {
			     //into grace period..days left,,zero problem
			     $status = 1;
				 //echo $status,'giso>';
                 ////////////////////////////////////////////////// send grace mail
				 //$tell = $this->makehtmlbody($appname.' will expire. Please renew!');
		         //$this->mailto($apptype,$appname.' expire in '. $status.' day(s).',$tell);
				 $tokens[] = '0';// days ...$status;				 
			     $this->notify_mail($tokens,$apptype,null,$subject,$message);				 
				 
				 if ($usemail)
				   $this->notify_mail($tokens,$ownermail,$ccmail,$subject,$message);				 				 					 
			   }*/
		       else {
			     //out of grace period or no grace period
  		         $status = -1;
				 //echo $status,'gout>';
				 ////////////////////////////////////////////////// send expired mail
				 //$tell = $this->makehtmlbody($appname.' expired!');
		         //$this->mailto($apptype,$appname.' expired',$tell);
			     $this->notify_mail($tokens,$apptype,null,$altsubject,$altmessage);				 
				 
				 if ($usemail)
				   $this->notify_mail($tokens,$ownermail,$ccmail,$altsubject,$altmessage);					 
		       }  
             }	
			 
			 //echo $status,'>';		 		   
			
		     $v[] = $appname;
		     $v[] = $apptype;
		     $v[] = $apppass;
		     $v[] = $tz;
		     $v[] = $expire;		   		   		   		   
		     $v[] = $gracedays;
			 $v[] = $status;
			 $v[] = $tmzid;
			 $v[] = $expgoto;
			 $v[] = $rengoto;
			 $v[] = $silence;
			 $v[] = $isfree;			 		   
		   
		     $ret = implode('<@>',$v);
		     return ($ret);
		     //return true;
		   //} //2nd check
		 }  
	   }
	   
	   return false;
	}	
	
	//override
	function appname_exists($param) {
	    $db = GetGlobal('db');
	
	    $sSQL = "select appname from applications where appname='" . $param . "' and apptype='" . decode(GetGlobal('UserID')) . "' and active=1";
	    $result = $db->Execute($sSQL,2);
		//echo $sSQL;
		//check reserverd words
		if ($file = @file_get_contents($this->syswordsfile)) {
		  $words = explode(",",$file);
		  //print_r($words);		

		  if (($result->fields['appname']==$param) || (in_array($param,$words)==true))
		    return true;
		  else
		    return false;     
	    }
		else {
		  if ($result->fields['appname']==$param) 
		    return true;
		  else
		    return false;    		
		}		
	}	
	
	//override
	function application_exists($appname=null) {
	
	  if (!$appname) return false;

	  if ($this->appname_exists($appname)) {
	      $this->error = writecl("The application [$appname] exist!",'#FFFFFF','#FF0000');
		  $ret = true;
	  }	  
	  else {
	      //$ret = writecl("The application [$appname] is available!",'#000000','#00FF00');
		  $ret = false;
	  }	  		
		
	  return ($ret);
	}
	
	function isfree_record($apptype=null) {
	    $db = GetGlobal('db');
	
	    $sSQL = "select count(isfree) from applications where apptype='" . $apptype . "' and active=1 and isfree=1";
	    $result = $db->Execute($sSQL,2);
		//echo $sSQL,'>';
		$recs = $result->fields[0];
		//echo $recs,'>';
		
		if ($recs < $this->free_records)
		  return true;
		  
		return false;  
	}	
	
	//inform user for page expirations
	function check_exp_app($expire=null,$gracedays=null,$tmz_id=null) {
	   $status = 0;	
	   $today = date('Y-m-d');	
       $tmz = isset($tmz_id) ? $tmz_id : $this->tmz_id;	   
	   $tmz_today = $this->make_gmt_date(null,$tmz);    
	
	   if ($expire)
         $expres = $this->date_diff($tmz_today,$expire) + $gracedays;
	   else
	     return ;	 
	   //echo '>',$expres,'>';
			 
	   if ($expres>0) {
	      //not expired yes
		  $status = 0;
	   }
	   else {
	     if (($gracedays) && ($gracedays>abs($expres))) {
		         //into grace period..days left
		         $status = ($gracedays - abs($expres)) * 1;//plus 1...
				 //echo $status,'gin>';	
		 }
	     /*elseif (($gracedays) && ($gracedays==abs($expres))) {
			     //into grace period..days left,,zero problem
			     $status = 1;
				 //echo $status,'giso>';				 					 
		 }*/		 
		 else {
			     //out of grace period or no grace period
  		         $status = -1;
				 //echo $status,'gout>';					 
		 }  
      }
	  //echo '+',$status,'+';
	  return ($status);	
	}
	
	//show apps to expire
	function show_exp_app_mark($appname,$expire=null,$gracedays=null,$tmz_id=null) {
	   $today = date('Y-m-d');
       $tmz = isset($tmz_id) ? $tmz_id : $this->tmz_id;	   
	   $tmz_today = $this->make_gmt_date(null,$tmz);  	   
	   	
	   $s = $this->check_exp_app($expire,$gracedays,$tmz);
	   $this->appattr[$appname]['expired'] = $s;
	   
	   if ($s>0) {//when expired in grace period ...deep red
	     $ret = writecl("$",'#FFFFFF','#CC0000');
	   }	 
	   elseif ($s<0) {//when expired out of grace period...red
	     $ret = writecl("$",'#FFFFFF','#FF0000');
	   }	 
	   else {//to be expired....
	   
         if ($expire) {
           $expres = $this->date_diff($tmz_today,$expire) + $gracedays;	   
		   $x = abs($expres);
	       if ($x<=10) //color depends on days..
		     $ret = writecl("$",'#FFFFFF','#FF6600');//orange
		   elseif ($x<=30)	 
		     $ret = writecl("$",'#FFFFFF','#FFFF00'); //yellow
		   else	 
	          $ret = writecl("$",'#FFFFFF','#00FF00'); //green   
		 }
		 else
		   $ret = writecl("$",'#FFFFFF','#FFFFFF');//white  
	   }	 
		 
	   return ($ret);	 
	}	
	
	//inform user for unpaid app records 
	function check_paid_record($insdate=null,$paydate=null,$tmz_id=null,$paydays=null) {
	   $status = 0;	
	   $today = date('Y-m-d');	
       $tmz = isset($tmz_id) ? $tmz_id : $this->tmz_id;	   
	   //echo $insdate,'>>',$paydate,'>>',$tmz_id,'>>';
	   $tmz_today = $this->make_gmt_date(null,$tmz);  
	   $paydays = $paydays?intval($paydays):0;
	
	   if ($paydate)
         $expres = $this->date_diff($tmz_today,$paydate) + $this->freedays + $paydays;
	   elseif ($insdate) 	 
	     $expres = $this->date_diff($tmz_today,$insdate) + $this->freedays + $paydays;
	   else
	     return ;	 
	   //echo '>',$expres,'>';
			 
	   if ($expres>0) {
	      //not expired yes
		  $status = 0;
	   }
	   else {
	     if (($this->gracedays) && ($this->gracedays>abs($expres))) {
		         //into grace period..days left
		         $status = ($this->gracedays - abs($expres)) * 1;//plus 1... when 1-1=0
				 //echo $status,'gin>';
				 $this->grace_records += 1;	
		 }
	     /*elseif (($gracedays) && ($gracedays==abs($expres))) {
			     //into grace period..days left,,zero problem
			     $status = 1;
				 //echo $status,'giso>';
				 $this->grace_records += 1;				 				 					 
		 }*/		 
		 else {
			     //out of grace period or no grace period
  		         $status = -1;
				 //echo $status,'gout>';
				 $this->expired_records += 1;					 
		 }  
      }
	  //echo '+',$status,'+';
	  return ($status);	
	}
	
	//show expired records to pay
	function show_unpaid_record_mark($appname,$isfree,$insdate=null,$paydate=null,$tmz_id=null,$paydays=null) {
	   
	   //free stuff
	   //if ($meter <= $this->free_records) 
	   //echo $isfree,':';	   
	   if ($isfree)
	     return (writecl("F",'#FFFFFF','#00FF00'));//green//white;	
	   
	   $s = $this->check_paid_record($insdate,$paydate,$tmz_id,$paydays);
	   $this->appattr[$appname]['unpaid'] = $s;	   
	   
	   if ($s>0)
	     $ret = writecl("+",'#FFFFFF','#0000FF');
	   elseif ($s<0)
	     $ret = writecl("*",'#FFFFFF','#FF0000');
	   else //$s=0..ok
	     $ret = writecl("_",'#FFFFFF','#00FF00');	 	 
		 
	   return ($ret);	 
	}	
	
	//show overloaded app records
	function show_extra_record_mark($meter,$insdate=null,$paydate=null) {
	
	
	   if (($meter > $this->free_records)) //&& ($s<>0)) 
	     $ret = writecl("^",'#FFFFFF','#FF0000');
	   else 	 
	     $ret = writecl("_",'#FFFFFF','#FFFFFF');	   
		 
	   return ($ret);	 
	}
	
	//paid extra records update
	function update_paid_extra_records($apptype=null,$paidrecords=null,$paydays=null) {
        $db = GetGlobal('db'); 
		$today = date('Y-m-d');
		$mem_apptype = decode(GetGlobal('UserID')); 
		$new_paydays = $paydays?intval($paydays):0;//intval($this->annual);		
		
		if ((!$apptype) || (!$paidrecords)) 
		  return false;
		  
		if (intval($paidrecords<0)) {
		  $paidrecords = abs($paidrecords);
		  $already_paid = true;
		}  
		else
		  $already_paid = false;		  
		
		//echo '>',$mem_apptype; //stored in session...when paypal????		
		
		//first fetch all active records  ...//////fetch pnly enabled apps???
	    $sSQL = "select id,appname,insdate,paydate,paydays,tmzid,isfree from applications where apptype='" . $apptype . "' and active=1 ";//and enable=1";
		$sSQL .= " limit 100"; //outer limit //order by id 					
	    $result = $db->Execute($sSQL,2);
		//echo $sSQL;
	    $resultset = $db->Execute($sSQL,2);
		//print_r($resultset);  			 
		foreach ($resultset as $i=>$rec)
		  $ret[] = $rec;
		  
		if (empty($ret)) { //means paid for records to be insterted in the future
		  $out = intval($paidrecords);				
		  return ($out);
        }
				
		$meter = 0;
		$affected = 0;
		foreach ($ret as $i=>$record) {
		
		  //$meter+=1;		
		
		  if (/*($meter > $this->free_records)*/(!$record['isfree']) && ($affected<$paidrecords)) {//($meter <= ($this->free_records+$paidrecords))) {
		    //check expiration based on already in record paydate,paydays
		    $ispaid = $this->check_paid_record($record['insdate'],$record['paydate'],$record['tmzid'],$record['paydays']);
			//echo $ispaid,'>';
		    if ($ispaid<>0) {
			
	          $tmz_today = $this->make_gmt_date(null,$record['tmzid']);
					
		      $sSQL = "update applications set paydate=" . $db->qstr($tmz_today) . ",paydays=" . $new_paydays . " where "; 
		      $sSQL .= "apptype='" . $apptype . "' and id=" . $record['id'];
              $db->Execute($sSQL,1); 
			
	          if ($a = $db->Affected_Rows()) 
			    $affected+=1;
	          //echo $affected,':',$meter,':',$sSQL,'<br>';			  
			}
		  }
		}
				  
	    //echo $sSQL;
		if ($affected) {
		  //return dif between paid record and inserted record
		  //if ins records < paid records will return ypoloipo to be used in future ins 
		  //else will be return 0 which is a perfect mapping = what you paid you get
		  $diff = (intval($paidrecords)-$affected); 						
		
		  //echo 'z';
		  if ($diff>0) {//diff 
		    $this->msg = writecl($affected . " record(s) successfuly updated! " . $diff .' record(s) into inventory!','#FFFFFF','#00FF00');
			$out = $diff;
		  }	
		  else {//0
		    if ($already_paid) {
			  $this->msg = writecl($affected . " record(s) successfuly updated! Inventory updated!",'#FFFFFF','#00FF00');
			  $out = ($paidrecords * -1);//-records as map variable
			}  
			else {
		      $this->msg = writecl($affected . " record(s) successfuly updated!",'#FFFFFF','#00FF00');
			  $out = $diff;//0 and paypal paid
			}  
		  }	
          ////////////////////////////////////////////////// send notice mail
		  if ($this->host_mail_notifier)
		    $this->mailto($this->host_mail_notifier,'Updated records',$this->makehtmlbody($this->msg . ' from user '.$apptype));
		  
		  //return true;
		  return ($out);			  
		}						
		else {
		  $this->msg = writecl("Records NOT updated! Please wait while we investigate the problem!",'#FFFFFF','#FF0000');
          ////////////////////////////////////////////////// send notice mail
		  if ($this->host_mail_notifier)
		    $this->mailto($this->host_mail_notifier,'ERROR:paid updated records',$this->makehtmlbody($this->msg . ' from user ' . $apptype));		  
		}  
		  
		return false;  
	}
	
	//override
    function searchinbrowser() {
	   $action = seturl('t=service');
	   $f = GetParam('filter');
	
       $ret = "Search<form name=\"searchinbrowser\" method=\"post\" action=\"$action\">
           <input name=\"filter\" type=\"Text\" value=\"$f\" size=\"56\" maxlength=\"64\">
           <input name=\"Image\" type=\"Image\" src=\"../images/b_go.gif\" alt=\"\"    align=\"absmiddle\" width=\"22\" height=\"28\" hspace=\"10\" border=\"0\">
           </form>";

       return ($ret);
    }
	 
	function  create_timezone_id($timezone=null) {
	
	   if (!$timezone) return 0;
	
	   $p = explode(' ',$timezone);
	   if (stristr($p[0],':')) {
	   
	     if (stristr($p[0],'+')) {
	       $t = explode('+',$p[0]);
		   $ret = floatval(str_replace(':','.',$t[1]));
		   //echo '+++',$ret;		   
		 }  
		 elseif (stristr($p[0],'-')) {  
		   $t = explode('-',$p[0]);
		   $ret = (floatval(str_replace(':','.',$t[1])) * -1);
		   //echo '---',$ret;
		 }  
		 else 
		   $ret = 0;//...  
	   }
	   else
	     $ret = 0; //gmt time
		 
	   return ($ret);	 
	}
	
	function make_gmt_date($date=null,$tmzid=null) {
	  $dst = -1;//defualt
      date_default_timezone_set('GMT'); //btpass server time	
      $today = date('Y-m-d');	
	  //echo $today,'#';
	  
	  if (!$date)
		$mkd = mktime();
	  else {
	    $d = explode('-',$date);	
	    $mkd = mktime(0,0,0,$d[1],$d[2],$d[0],$dst);
	  }
	  
	  if ($this->dst)
	    $dst_time = 60*60; //+1 hour	  	
	  //NOT WORK...
	  //$mkd_gmt = $mkd - date('Z');//auto server offset val = 0 when GMT
	  
	  if ($tmzid) {
        $user_tmz = $tmzid;
	    //echo $tmzid,'#';	  
	    $mkd_user_tmz = intval($user_tmz) * 60 * 60;//user tmz - hours * min * sec
        $user_local_time = $mkd/*_gmt*/ + $mkd_user_tmz; //return time in secs from 1970
	  
	    $gmtdate = date('Y-m-d',$user_local_time + $dst_time);
	  }
	  else
	    $gmtdate = date('Y-m-d',$mkd + $dst_time /*_gtm*/);
		 	
	  //echo $gmtdate,'<br>';
	  
	  return ($gmtdate);	  	 	  	  	  
	}	
	
	function terms_agreement() {
	    $db = GetGlobal('db');
	
	    //$sSQL = "select appname from applications where apptype='" . decode(GetGlobal('UserID'));
	    //$result = $db->Execute($sSQL,2);
		SetSessionParam('TERMS','AGREE');	
	
	    return true;
	}
	
	function terms_and_contitions() {
	
	    //if no agreement or no terms
	    if ((GetSessionParam('TERMS')) || (!$this->useterms)) return; //no view when agree
	
        $sFileName = seturl("t=terms",0,1);	
	    $terms = file_get_contents($this->path.'terms.txt');
		
		if ($terms) {
	
          $data = "
              <p>" . writecl("WARNING: Please read carefully the terms and contitions before use this service.",'#FFFFFF','#FF0000').
			  "</p><form method=\"POST\" action=\"" . $sFileName . "\" name=\"Terms\">
              <textarea name=\"terms\" cols=\"140\" rows=\"18\" wrap=\"VIRTUAL\" readonly>
              <@TERMS@>
              </textarea>
			  <br/>
              <input type=\"submit\" value=\"Agree\">			  
              <input type=\"hidden\" value=\"terms\" name=\"FormAction\"/>			  
              <input type=\"hidden\" name=\"FormName\" value=\"terms\">
			  </form>
			  <br/>
			  <br/>
			  <br/>
";

          //$out = str_replace('<@KEY@>',rawurlencode($this->ckey),$data);	
	      $out = str_replace('<@TERMS@>',$terms,$data);
	    }
	
	    return ($out);	
	}	
	
	
	function encode($s,$no_urlencode=null) {
	
       $cp = new ciphersaber;
	   if ($no_urlencode)	   
	     $outvar = $cp->encrypt($s,'1234567890abcdefdgklm#$%^&');	
	   else
	   	 $outvar = rawurlencode($cp->encrypt($s,'1234567890abcdefdgklm#$%^&'));
		 
	   return ($outvar);	 
	   
	   if ($no_urlencode)	
	     return (encode($s));
	   else
	     return rawurlencode(encode($s));//urlencode(encode($s));	 
	}
	
	function decode($s,$no_urldecode=null) {
	
       $cp = new ciphersaber;
	   if ($no_urldecode)
	     $outvar = $cp->decrypt($s,'1234567890abcdefdgklm#$%^&');	
	   else	   
	     $outvar = $cp->decrypt(rawurldecode($s),'1234567890abcdefdgklm#$%^&');
		 
	   return ($outvar);		 	
	
	   if ($no_urldecode)
	     return (decode($s));
	   else
	     return decode(rawurldecode($s));//decode(urldecode($s));
	}
	
	function my_encode2($s) {
	   //echo strlen($s),'<br>';
	   /*if (strlen($s)/strlen($s) != 1)
	     $s .= '0';
	   else
	     $s .= '00';	 */
	
	   //return convert_encode($s);
	   $s1 = str_replace('/','_',$s);	
	   $s2 = str_replace('.','*',$s1);	
	   $s3 = str_replace('@','$',$s2);
	   
	   $a = substr($s3,(strlen($s3)/2),(strlen($s3)/2)) .  substr($s3,0,(strlen($s3)/2));
	   $b = substr($a,(strlen($a)/4),(strlen($a)/4)) .  substr($a,0,(strlen($a)/4)) . 
	        substr($a,-(strlen($a)/4)) . substr($a,(strlen($a)/2),(strlen($a)/4));	 
	   $c = substr($a,(strlen($a)/8),(strlen($a)/8)) .  substr($a,0,(strlen($a)/8)) . 
	        substr($a,(strlen($a)/4)+(strlen($a)/8),(strlen($a)/8)) . substr($a,(strlen($a)/4),(strlen($a)/8)) .  
			substr($a,(strlen($a)/2),(strlen($a)/8)) . substr($a,(strlen($a)/2),(strlen($a)/8)) .
	        substr($a,-(strlen($a)/8)) . substr($a,(strlen($a)/2)+(strlen($a)/4),(strlen($a)/8)) ;	 
			
	   /*for ($i=0;$i<=strlen($s);$i++)
	     $aa[] = substr($s,$i,1);
	   //print_r($aa);
	   //echo count($aa);	 
	   for ($i=0;$i<=count($aa);$i++) {
	     $d .= chr(ord(array_pop($aa)));
	   }	*/				  
	     
	   	
	   return ($c);	
	}
	
	function my_decode2($s) {
	   //return convert_uudecode($s);
	   $s1 = str_replace('_','/',$s);	
	   $s2 = str_replace('*','.',$s1);	
	   $s3 = str_replace('$','@',$s2);	
	   
	   $a = substr($s3,0,(strlen($s3)/8)) . substr($s3,(strlen($s3)/8),(strlen($s3)/8)) .
	        substr($s3,(strlen($s3)/4),(strlen($s3)/8)) . substr($s3,(strlen($s3)/4)+(strlen($s3)/8),(strlen($s3)/8)) . 
			substr($s3,(strlen($s3)/2),(strlen($s3)/8)) . substr($s3,(strlen($s3)/2),(strlen($s3)/8)) . 
	        substr($s3,(strlen($s3)/2)+(strlen($s3)/4),(strlen($s3)/8)) . substr($s3,-(strlen($s3)/8)); 
	   $b = substr($a,0,(strlen($a)/4)) . substr($a,(strlen($a)/4),(strlen($a)/4)) .  
	        substr($a,(strlen($a)/2),(strlen($a)/4)) . substr($a,-(strlen($a)/4));					   
	   $c = substr($b,0,(strlen($b)/2)) . substr($b,(strlen($b)/2),(strlen($b)/2)); 	   
	      
	   return ($c);	
	}
	
	function my_encode($s) {
	
	   /*$s = str_replace('/','_',$s);	
	   $s = str_replace('.','*',$s1);	
	   $s = str_replace('@','$',$s2);*/

	   $a1 = substr($s,0,(strlen($s)/2));
	   for ($i=0;$i<strlen($a1);$i++)
	     $aa[] = substr($a1,$i,1);
		    
	   $a2 = substr($s,-(strlen($s)/2));
	   for ($i=0;$i<strlen($a2);$i++)
	     $bb[] = substr($a2,$i,1);	
		    
	   //print_r($aa); print_r($bb);
	   for ($i=strlen($s)/2;$i>=0;$i--) {
	     $ret .= $bb[$i] . $aa[$i];
	   }
	   //echo strlen($ret),'|||<br>';	   
	   
	   return ($ret);
	}
	
	function my_decode($s) {
	
	   for ($i=0;$i<=strlen($s);$i++) {
	     $aa[] = substr($s,$i,1);
	     $bb[] = substr($s,$i+1,1);		 
		 $i+=1;
	   }   	
	   //print_r($aa); print_r($bb);	
	   for ($i=(strlen($s)/2);$i>=0;$i--) {
	     $ret .= $bb[$i];
	   }  
	   for ($i=(strlen($s)/2);$i>=0;$i--) {
	     $ret .= $aa[$i];
	   }  	    

	   //echo strlen($ret),'|||<br>';
	   
	   /*$out2 = str_replace('_','/',$ret);	
	   $out1 = str_replace('*','.',$out2);	
	   $out = str_replace('$','@',$out1);*/
	   
	   return ($ret);
	}
	
	function notify_mail($tokens,$to=null,$cc=null,$subject=null,$body=null,$ishtml=null) {
	
	   $text = $this->combine_tokens($body,$tokens);
	   $body = $this->makehtmlbody($text);
	   $subj = $subject?$this->combine_tokens($subject,$tokens):'No subject';
	   
	   if ($to)
	     $this->mailto($to,$subj,$body,$cc,null,$ishtml);	   
	} 		
	
	function mailto($to=null,$sub=null,$body=null,$cc=null,$bcc=null,$ishtml=null) {
	
	   $from = $this->mailsender;//'support@lockmy.networlds.org';
	
	   GetGlobal('controller')->calldpc_method("rcssystem.sendit use $from+$to+$sub+$body+$cc+$ishtml");		  
	}
	
	function makehtmlbody($text=null) {
	  
	  if (stristr($text,'</html>')) //already html
	    $ret = $text;
	  else	
		$ret = '<html><body>' . $text . '</body></html>';
		
	  return ($ret);
	}
	
	function combine_tokens($text,$tokens) {
	
	    if (!is_array($tokens)) return;
		
		$ret = $text;
		  
		//echo $ret;
	    foreach ($tokens as $i=>$tok) {
            //echo $tok,'<br>';
		    $ret = str_replace("%".$i,$tok,$ret);
	    }
		//clean unused token marks
		for ($x=$i;$x<10;$x++)
		  $ret = str_replace("%".$x,'',$ret);
		//echo $ret;
		return ($ret);
	}	
		
};
}		
?>