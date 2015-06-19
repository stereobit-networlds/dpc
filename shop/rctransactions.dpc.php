<?php
$__DPCSEC['RCTRANSACTIONS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCTRANSACTIONS_DPC")) && (seclevel('RCTRANSACTIONS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCTRANSACTIONS_DPC",true);

$__DPC['RCTRANSACTIONS_DPC'] = 'rctransactions';

//$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
//require_once($a);

$d = GetGlobal('controller')->require_dpc('shop/shtransactions.dpc.php');
require_once($d);
 
$__EVENTS['RCTRANSACTIONS_DPC'][0]='cptransactions';
$__EVENTS['RCTRANSACTIONS_DPC'][1]='cptransshow';
$__EVENTS['RCTRANSACTIONS_DPC'][2]='cptranslink';
$__EVENTS['RCTRANSACTIONS_DPC'][3]='cptransview';
$__EVENTS['RCTRANSACTIONS_DPC'][4]='cptransviewhtml';
$__EVENTS['RCTRANSACTIONS_DPC'][5]='cploadframe';

$__ACTIONS['RCTRANSACTIONS_DPC'][0]='cptransactions';
$__ACTIONS['RCTRANSACTIONS_DPC'][1]='cptranssshow';
$__ACTIONS['RCTRANSACTIONS_DPC'][2]='cptranslink';
$__ACTIONS['RCTRANSACTIONS_DPC'][3]='cptransview';
$__ACTIONS['RCTRANSACTIONS_DPC'][4]='cptransviewhtml';
$__ACTIONS['RCTRANSACTIONS_DPC'][5]='cploadframe';

$__DPCATTR['RCTRANSACTIONS_DPC']['cptransactions'] = 'cptransactions,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCTRANSACTIONS_DPC'][0]='RCTRANSACTIONS_DPC;Transactions;Συναλλαγές';
$__LOCALE['RCTRANSACTIONS_DPC'][1]='_date;Date;Ημερ.';
$__LOCALE['RCTRANSACTIONS_DPC'][2]='_time;Time;Ώρα';
$__LOCALE['RCTRANSACTIONS_DPC'][3]='_status;Status;Φάση';
$__LOCALE['RCTRANSACTIONS_DPC'][4]='_payway;Pay method;Πληρωμή';
$__LOCALE['RCTRANSACTIONS_DPC'][5]='_roadway;Delivery;Διανομή';
$__LOCALE['RCTRANSACTIONS_DPC'][6]='_qty;Qty;Ποσοτ.';
$__LOCALE['RCTRANSACTIONS_DPC'][7]='_cost;Cost A;Κόστος A';
$__LOCALE['RCTRANSACTIONS_DPC'][8]='_costpt;Cost B;Κόστος B';
$__LOCALE['RCTRANSACTIONS_DPC'][9]='_xxx;Cost B;Κόστος Β';

class rctransactions extends shtransactions {

    var $reset_db, $title;
	var $_grids, $charts;
	var $ajaxLink;
	var $hasgraph, $hasgauge;
    var $status_sid, $status_sidexp;
		
	function rctransactions() {
	
       shtransactions::shtransactions();
	   //override if exist
	   if ($tpath = paramload('RCTRANSACTIONS','path'))
	     $this->path = paramload('SHELL','prpath') . $tpath;
     	 
	
	  $this->title = localize('RCTRANSACTIONS_DPC',getlocal());		
	  $this->reset_db = false;
	  
	  //$this->_grids[] = new nitobi("Transactions");	
      //$this->_grids[] = new nitobi("Customertrans");		
	  
	  $this->ajaxLink = seturl('t=cptransshow&statsid='); //for use with...	      
	  
	  $this->hasgraph = false;
	  $this->hasgauge = false;	  
	  $this->graphx = remote_paramload('RCTRANSACTIONS','graphx',$this->path);
	  $this->graphy = remote_paramload('RCTRANSACTIONS','graphy',$this->path);

      $this->status_sid = arrayload('RCTRANSACTIONS','sid');  

      $this->status_exp = arrayload('RCTRANSACTIONS','sidexp'); 
           
	}
	
    function event($event=null) {
	
	   //ALLOW EXPRIRED APPS
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		 
	
	   switch ($event) {
	     case 'cptransviewhtml' : echo $this->viewTransactionHtml();
		                          die();
		                        break;	   
		 case 'cptransview': 
		                     break;		   
		 case 'cptranslink': echo $this->show_transaction_data();//'trans');
		                     die();
		                     break;	   
		 case 'cploadframe': echo $this->loadframe('trans');
		                     die();
		                     break;								 
		 case 'cptransshow': if (!$cvid = GetParam('statsid')) $cvid=-1; 
		                      $this->charts = new swfcharts;	
		                      $this->hasgraph = $this->charts->create_chart_data('transcust','where cid='.$cvid);
							  break; 	   
	     case 'cptransactions'    :
		 default            : $this->grid_javascript();
			                  //$this->sidewin(); 		 
		                      //if ($this->reset_db) $this->reset_db();
		                      $this->charts = new swfcharts;	
		                      $this->hasgraph = $this->charts->create_chart_data('transactions',"");
							  $this->hasgauge = $this->charts->create_gauge_data('income',"where cid=0",null,1,400,300,'meter');
	   }
			
    }   
	
    function action($action=null) {
	 
	  /*if (GetSessionParam('REMOTELOGIN')) 
	    $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	  else  
        $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 	 
	  */
	  switch ($action) {
	     case 'cptransviewhtml' : //$out = $this->viewTransactionHtml();
		                        break;		  
		 case 'cptransview': $out = $this->viewTransactions();
		                     break;
							 	  
		 case 'cptranssshow': if ($this->hasgraph)
		                        $out = $this->show_graph('transcust','Customer Transactions',$this->ajaxLink,'stats');
							  else
							    $out = "<h3>".localize('_GNAVAL',0)."</h3>";	
							  die('stats|'.$out); //ajax return
							  break; 
	     case 'cptransactions'    :

		 default            : $out .= $this->show_transactions();
	  }	 

	  return ($out);
    }
	
	function grid_javascript() {
      if (iniload('JAVASCRIPT')) {

		   //$template = $this->set_template();   		      
		   
	       $code = $this->init_grids();			

		   //$code .= $this->_grids[0]->OnClick(10,'TransactionDetails',$template);//,'Customertrans','vid',2);
	   
		   $js = new jscript;
		   //$js->setloadparams("init()");
           //$js->load_js('nitobi.grid.js');		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}
	/*
	function set_template() {

		   $template .= "<h4>'+update_stats_id(i2,i1,i0)+'</h4>";	
		   $template .= "<table width=\"100%\" class=\"group_win_body\">";	   
		   $template .= "<tr><td>".localize('AA',getlocal()).":</td><td><b>'+i0+'</b></td></tr>";	
		   $template .= "<tr><td>".localize('ID',getlocal()).":</td><td><b>'+i1+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('Customer ID',getlocal()).":</td><td><b>'+i2+'</b></td></tr>";		   
		   $template .= "<tr><td>".localize('Date',getlocal()).":</td><td><b>'+i3+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('Time',getlocal()).":</td><td><b>'+i4+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('Status',getlocal()).":</td><td><b>'+i5+'</b></td></tr>";				   		   
		   $template .= "<tr><td>".localize('Payment',getlocal()).":</td><td><b>'+i6+'</b></td></tr>";	
		   $template .= "<tr><td>".localize('Distribution',getlocal()).":</td><td><b>'+i7+'</b></td></tr>";				   		   
		   $template .= "<tr><td>".localize('Total',getlocal()).":</td><td><b>'+i8+'</b></td></tr>";
		   $template .= "<tr><td>".localize('Total2',getlocal()).":</td><td><b>'+i9+'</b></td></tr>";		
		   //$template .= "</td><td>&nbsp;";	
		   $template .= "</table>";	
		   
		   $template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td>";
		   $template .= "'+show_body(i1,i2,i0)+'";		   	
		   $template .= "</td></tr></table>";	 		        
		   
		   return ($template);	
	}*/
	
	function show_graph($xmlfile,$title,$url=null,$ajaxid=null,$xmax=null,$ymax=null) {
	  $gx = $this->graphx?$this->graphx:$xmax?$xmax:550;
	  $gy = $this->graphy?$this->graphy:$ymax?$ymax:250;	
	
	  $ret = $title; 	
	  $ret .= $this->charts->show_chart($xmlfile,$gx,$gy,$url,$ajaxid);
	  return ($ret);
	}
	
	function show_transactions() {
	
	   if ($this->msg) $out = $this->msg;
	   
	   //$toprint .= $this->show_grids();	   	
       //$mywin = new window($this->title,$toprint);
       //$out .= $mywin->render();	
	   
	   $out = $this->show_grids();	   	
	   
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
	    $sSQL1 = "CREATE TABLE `transactions` (
  `recid` int(11) NOT NULL auto_increment,
  `tid` varchar(64) NOT NULL default '',
  `cid` int(11) NOT NULL default '0',
  `tdate` date NOT NULL default '0000-00-00',
  `ttime` time NOT NULL default '00:00:00',
  `tdata` text NOT NULL,
  `tstatus` smallint(6) NOT NULL default '0',
  `type1` varchar(64) NOT NULL default '',
  `type2` varchar(64) NOT NULL default '',
  `payway` varchar(64) default NULL,
  `roadway` varchar(64) default NULL,
  `qty` int(11) default '0',
  `cost` double default NULL,
  `costpt` double default NULL,
  PRIMARY KEY  (`recid`),
  UNIQUE KEY `tid` (`tid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=greek COMMENT='transaction table';";
		  
	    $result1 = $db->Execute($sSQL1,1);
	    if ($result1) $message .= "Create table ...\n";
	  
	    setInfo($message);	  	
	}
	
	function init_grids() {

	    //$bodyurl = seturl("t=cptranslink&tid=");	
		$bodyurl = seturl("t=cploadframe&tid=");
	
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

function show_body() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];  
  var taskid;
  var custid;
  
  taskid = str;  
  custid = str1;
  /*bodyurl = '$bodyurl'+taskid+'&cid='+custid;
  
  ifr = '<iframe src =\"'+bodyurl+'\" width=\"100%\" height=\"350px\"><p>Your browser does not support iframes ('+str2+').</p>'+str1+'</iframe>';  
  return ifr;*/
  sndReqArg('$bodyurl'+taskid,'trans');
}
			
";
        $out .= "\r\n";
        return ($out);
	}
	
	function show_grid($x=null,$y=null,$filter=null,$bfilter=null) {
	    //$db = GetGlobal('db');
	    $selected_cus = urldecode(GetReq('cusmail'));
	
	    if (defined('MYGRID_DPC')) {
		
		    if ($selected_cus) {
				//$xsSQL2= "CREATE TEMPORARY TABLE temp1 ENGINE=MEMORY "; 
				//$xsSQL2.= "as (select * from users where username='$selected_cus');";
				$xsSQL2 = "SELECT * FROM (SELECT i.recid,i.tid,i.tdate,i.ttime,i.tstatus,i.payway,i.roadway,i.qty,i.cost,i.costpt,c.username FROM transactions i";
				$xsSQL2.= " INNER JOIN users c ON (c.code2 = i.cid AND c.code2='$selected_cus')) x";				
				//echo $xsSQL2;
			}
			else {
				$xsSQL2 = "SELECT * FROM (SELECT i.recid,i.tid,i.tdate,i.ttime,i.tstatus,i.payway,i.roadway,i.qty,i.cost,i.costpt,c.username FROM transactions i";
				$xsSQL2.= " INNER JOIN users c ON c.code2 = i.cid) x";
				//echo $xsSQL2;
			}
			//$out.= $xsSQL2;
			GetGlobal('controller')->calldpc_method("mygrid.column use grid2+recid|".localize('id',getlocal())."|5|0|||1|1");
			//GetGlobal('controller')->calldpc_method("mygrid.column use grid2+tid|".localize('id',getlocal())."|5|0|||0");
			//GetGlobal('controller')->calldpc_method("mygrid.column use grid2+tid|".localize('id',getlocal())."|5|0|||1|0");//"|link|5|".seturl('t=cptransviewhtml&editmode=1&tid={tid}').'||');
			GetGlobal('controller')->calldpc_method("mygrid.column use grid2+tid|".localize('id',getlocal())."|link|5|"."javascript:show_body({tid});".'||');
			GetGlobal('controller')->calldpc_method("mygrid.column use grid2+username|".localize('_user',getlocal())."|10|0|||0|1");
			//GetGlobal('controller')->calldpc_method("mygrid.column use grid2+tdate|".localize('_date',getlocal())."|boolean|1|ACTIVE:DELETED");			
			GetGlobal('controller')->calldpc_method("mygrid.column use grid2+tdate|".localize('_date',getlocal())."|date|0|");
		    GetGlobal('controller')->calldpc_method("mygrid.column use grid2+ttime|".localize('_time',getlocal())."|9|0|");	
			GetGlobal('controller')->calldpc_method("mygrid.column use grid2+tstatus|".localize('_status',getlocal())."|5|0|||||right");	
			//GetGlobal('controller')->calldpc_method("mygrid.column use grid2+tstatus|".localize('_status',getlocal())."|link|10|"."javascript:show_body({tid});".'||');
		    GetGlobal('controller')->calldpc_method("mygrid.column use grid2+payway|".localize('_payway',getlocal())."|20|1|");			
		    GetGlobal('controller')->calldpc_method("mygrid.column use grid2+roadway|".localize('_roadway',getlocal())."|20|1|");
	        GetGlobal('controller')->calldpc_method("mygrid.column use grid2+qty|".localize('_qty',getlocal())."|5|0|||||right");				
			GetGlobal('controller')->calldpc_method("mygrid.column use grid2+cost|".localize('_cost',getlocal())."|5|0|||||right");
			GetGlobal('controller')->calldpc_method("mygrid.column use grid2+costpt|".localize('_costpt',getlocal())."|5|0|||||right");
			$ret .= GetGlobal('controller')->calldpc_method("mygrid.grid use grid2+customers+$xsSQL2+r+".localize('RCTRANSACTIONS_DPC',getlocal())."+recid+1+1+20+400+$x+0+1+1");

	    }
		else 
		   $ret .= 'Initialize jqgrid.';
        
        return ($ret);

		
	/*
	   $x = $x?$x:400;
	   $y = $y?$y:100;
	
       if ($filter)   	   
	     $grid1_get = "shhandler.php?t=shngettrans&select=1";
       elseif ($bfilter)   	   
	     $grid1_get = "shhandler.php?t=shngettrans&filter=".$bfilter;
	   else
	     $grid1_get = "shhandler.php?t=shngettrans";

	   $this->_grids[0]->set_text_column("A/A","recid","70","true"); 		 
	   $this->_grids[0]->set_text_column("ID","tid","70","true");   	
	   $this->_grids[0]->set_text_column("CID","cid","70","true");		      	      	   	   	         	   	   	   	
	   $this->_grids[0]->set_text_column("Date","tdate","80","true");	
	   $this->_grids[0]->set_text_column("Time","ttime","80","true");	
	   $this->_grids[0]->set_text_column("Status","tstatus","70","true","LISTBOX","list_status","status","status_id");		   	   	   	   
	   $this->_grids[0]->set_text_column("Pay Method","payway","100","true");		   
	   $this->_grids[0]->set_text_column("Distribution","roadway","100","true");		   	   	   
	   $this->_grids[0]->set_text_column("Gross","cost","80","true");		   
	   $this->_grids[0]->set_text_column("Total","costpt","80","true");		   
	   	   		   	   	  	   
	   //$this->_grids[0]->set_datasource("check_active",array('101'=>'Active','0'=>'Inactive'),null,"value|display",true);		   //$stype = explode(",",file_get_contents($this->path . 'categories.opt'));	
	   if (is_array($this->status_sid)) {
           foreach ($this->status_sid as $i=>$s)
           $stype[$s] = $this->status_exp[$i];
           //print_r($stype); 	
           //$stype= array('-1'=>'Canceled','0'=>'Submited');
           $this->_grids[0]->set_datasource("list_status",$stype,"status_id","status_id|status",true);	   
	   }
	   else
	     echo 'status id in RCTRANSACTIONS_DPC not defined';
		 
	   //$ret = $this->_grids[0]->set_grid_remote($grid1_get,null,"$x","$y","livescrolling",null,"false");							  
	
	   return ($ret);*/	   	
	}
	
	function show_grids() {
	   //gets
	   //$cat = GetReq('cat');	
       //$filter= GetParam('filter');
		   
       $vd = $this->show_grid();//550,440,null,$filter);		   
		   
       //$vd .= $this->searchinbrowser();
	   
	   if ($this->hasgraph)
		   $wd = $this->show_graph('transactions',/*localize('RCTRANSACTIONS_DPC',getlocal())*/null,seturl('t=cptransactions'));
	   else
		   $wd = "<h3>".localize('_GNAVAL',0)."</h3>";	   
	   
	   if ($this->hasgauge)
		   $wd .= $this->charts->show_gauge('income',400,300,seturl('t=cptransactions'));
	   else
		   $wd .= "<h3>".localize('_GNAVAL',0)."</h3>";	   		   	   
	   		   		   		   	   
	   
	   //grid 0 
	   $datattr[] = $vd;
//GetGlobal('controller')->calldpc_method("rcitems.show_grid use 400+440+1");							  
	   $viewattr[] = "left;50%";	   	   

	   //$grid0_get = "shhandler.php?t=shngettrans";
	   //$grid0_set = "";	   
	
	   //grid 1
	   /*$this->_grids[1]->set_text_column("A/A","recid","70","true"); 	   
	   $this->_grids[1]->set_text_column("Id","tid","70","true");   
	   $this->_grids[0]->set_text_column("CID","cid","70","true");	   	   	      	   	   	         	   	   	   	
	   $this->_grids[1]->set_text_column("Date","tdate","60","true");	
	   $this->_grids[1]->set_text_column("Time","ttime","60","true");	
	   $this->_grids[1]->set_text_column("Status","status","30","true");		   	   	   
	   $this->_grids[1]->set_text_column("Data","data","10","true");		   
	   $this->_grids[1]->set_text_column("Pay Method","payway","60","true");		   
	   $this->_grids[1]->set_text_column("Distribution","roadway","60","true");		   	   	   
	   $this->_grids[1]->set_text_column("Gross","cost","60","true");		   
	   $this->_grids[1]->set_text_column("Total","costpt","60","true");		   
	   	   		   	   	  	   
	   $wd = $this->_grids[1]->set_grid_remote($grid0_get,$grid0_set,"550","220","livescrolling",10,"false");
	   */
	   
	   //businnes card	used to pass data from jscript
	   //$message .= $this->charts->render('usage',400,250);
	   //$wd .= $this->_grids[0]->set_detail_div("TransactionDetails",550,20,'F0F0FF',$message);
	   
	   //$wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");

       //goto below of trans
       /*if ($this->hasgraph)
		   $wd .= $this->show_graph('transactions','Customer transactions',$this->ajaxLink,'stats');
	   else
		   $wd .= "<h3>".localize('_GNAVAL',0)."</h3>";*/

	   $datattr[] = $wd;
	   $viewattr[] = "left;50%";
	   
	   $myw = new window('',$datattr,$viewattr);
	   $ret = $myw->render();//"center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);
	   
       /*$ret .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");	   
       if ($this->hasgraph)
		   $ret .= $this->show_graph('transactions','Customer transactions',$this->ajaxLink,'stats');
	   else
		   $ret .= "<h3>".localize('_GNAVAL',0)."</h3>";	   
	   */
       $ret .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use trans");	   
	   return ($ret);	
	}	
	
	function sidewin() { 
	}
	
	function show_transaction_data() {//$ajaxdiv=null) {
      $db = GetGlobal('db'); 	
	  $tid = GetReq('tid');
	  $cid = GetReq('cid');
	  
	  $customer_data = GetGlobal('controller')->calldpc_method('shcustomers.showcustomerdata use '.$cid.'+code2');	
	
	  $sSQL = "select tdata,cost,costpt from transactions where tid=".$this->initial_word.$tid;
	  $result = $db->Execute($sSQL);
	  
	  if (!$out = $this->loadTransactionHtml($this->initial_word.$tid)) {	
	  
	    $cart_data = unserialize($result->fields['tdata']);
	    $cartshow = GetGlobal('controller')->calldpc_method('shcart.head');	  
	    //print_r($cart_data);
	    foreach ($cart_data as $cart_id=>$cart_val) {
	      $vals = explode(';',$cart_val);
	      //$cartshow .= GetGlobal('controller')->calldpc_method('shcart.viewcart use '.$vals[0].'+'.$vals[1].'++++++++'.$vals[8].'+'.$vals[9]);
		  $pvals = implode('+',$vals);
		  $cartshow .= GetGlobal('controller')->calldpc_method('shcart.viewcart use '.$pvals);
	    }
	  
	    $cartshow .= "<hr>".localize('_SUBTOTAL',getlocal()).':'.$result->fields['cost'].
	                 "<hr>".localize('_TOTAL',getlocal()).':'.$result->fields['costpt'];
	
	    $ret = $customer_data . $cartshow;// . $result->fields['tdata'] .'123';
	  
	
	    $headtitle = paramload('SHELL','urltitle');			
   	    $printpage = new phtml('../themes/style.css',$ret,"<B><h1>$headtitle</h1></B>");
        $out = $printpage->render();	
	    unset($printpage);	
	  }  
	  
	  /*if ($ajaxdiv) {
		//$frame = '<iframe src =\"'+bodyurl+'\" width=\"100%\" height=\"350px\"><p>Your browser does not support iframes ('+str2+').</p>'+str1+'</iframe>';    
		$frame = "<iframe id=\"myTFrame\" src=\"about:blank\" width=\"100%\" height=\"350px\"></iframe>
<script type=\"text/javascript\">
var doc = document.getElementById('myTFrame').contentWindow.document;
doc.open();
doc.write('".$out."');
doc.close();
</script>";
	    return $ajaxdiv.'|'.$frame;//$out;
	  }
	  else*/
	     return ($out);
	}
	
	function loadframe($ajaxdiv=null) {
	    $bodyurl = seturl("t=cptranslink&tid=").GetReq('tid');
	
		$frame = "<iframe src =\"$bodyurl\" width=\"100%\" height=\"350px\"><p>Your browser does not support iframes</p></iframe>";    

		if ($ajaxdiv)
			return $ajaxdiv.'|'.$frame;//$out;	//'<p>'.$bodyurl.'</p>';
		else
			return ($frame);
	}
	
	function loadTransactionHtml($id) {
	
        $file = $this->path . $id . ".html"; 
	    //echo $file;
		if (is_readable($file)) {
          /*$fd = fopen($file, 'r');
          $ret = fread($fd, filesize($file));
          fclose($fd);   	*/
		  $ret = file_get_contents($file);
		
		  return ($ret);	
		}
		else
		  return false;
	} 		

    /*    function searchinbrowser() {
            $ret = "
           <form name=\"searchinbrowser\" method=\"post\" action=\"\">
           <input name=\"filter\" type=\"Text\" value=\"\" size=\"56\" maxlength=\"64\">
           <input name=\"Image\" type=\"Image\" src=\"../images/b_go.gif\" alt=\"\"    align=\"absmiddle\" width=\"22\" height=\"28\" hspace=\"10\" border=\"0\">
           </form>";

          $ret .= "<br>Last search: " . GetParam('filter')."<br>";

          return ($ret);
        }	
		
		
	*/	
		
		
	//override	
	function getTransactionsList($limit=null) {
       $db = GetGlobal('db');
       $UserName = GetGlobal('UserName');	
	   //$name = $UserName?decode($UserName):null;		   
	   
	   //if (!$name) return;
	   	
	   //if ($this->storetype=='DB') {  //db	
	   	   
	     $sSQL = "select tid,tdate,ttime,tstatus,payway,roadway,cost,costpt from transactions " .//where cid=" . $db->qstr($name) . 
		         "order by tid DESC";	
         if ($limit)
            $sSQL .= ' LIMIT '.$limit;
         //echo $sSQL;			
				 
				 
	     $res = $db->Execute($sSQL,2);
	     //print_r ($res->fields[5]);
		 $i=0;
	     if (!empty($res)) { 
	       foreach ($res as $n=>$rec) {
		    $i+=1;
				
			
            $transtbl[] = //$checkbox . $i . ";" . 
                         $rec[0] . ";" .
			             $rec[0] . ";" .
						 /*$rec[3] .*/ $rec[4] . "/" . $rec[5] . ";" .
			             $rec[1] . " / " . $rec[2] . ";" .	
			             number_format($rec[7],2,',','.');// . ";" .						 					 
			             //number_format($rec[7],2,',','.')/*str_replace(".",",",$rec[7])*/;		   
		   }
		   
           //browse
		   //print_r($transtbl); 
		   $ppager = GetReq('pl')?GetReq('pl'):100;
           $browser = new browse($transtbl,/*localize('_TRANSLIST',getlocal())*/null,$this->getpage($transtbl,$this->searchtext));
	       $out .= $browser->render("cptransview",$ppager,$this,1,0,0,0);
	       unset ($browser);	
		      
	     }
		 else {
           //empty message
	       $w = new window(/*localize('_CART',getlocal())*/null,localize('_EMPTY',getlocal()));
	       $out .= $w->render("center::40%::0::group_win_body::left::0::0::");//" ::100%::0::group_form_headtitle::center;100%;::");
	       unset($w);

		 }		 
	   //}	
	   
	   return ($out);
	} 	
			
	//override			
	function viewTransactions() {
       $db = GetGlobal('db');
	   $a = GetReq('a');
       $UserName = GetGlobal('UserName');	   
	   
	   $apo = GetParam('apo'); //echo $apo;
	   $eos = GetParam('eos');	//echo $eos;   

       $myaction = seturl("t=transview");	   
	   
       if (seclevel('TRANSADMIN_',$this->userLevelID)) {
	     $this->admint=1;
         $out .= "<form method=\"POST\" action=\"";
         $out .= "$myaction";
         $out .= "\" name=\"Transview\">";		 
	   }
	   elseif (seclevel('TRANSCANCEL_',$this->userLevelID)) { 
	     $this->admint=2;	   
         $out .= "<form method=\"POST\" action=\"";
         $out .= "$myaction";
         $out .= "\" name=\"Transview\">";		   
	   }
	   else {
	     //no form
         //$out .= "<form method=\"POST\" action=\"";
         //$out .= "$myaction";
         //$out .= "\" name=\"Transview\">";		   
	   }

	 
	   $out .= $this->getTransactionsList();	 
		 
	   if ($this->admint) {
		/*     if ($this->admint==1) {
	           $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status0\">&nbsp;";		 
	           $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status1\">&nbsp;";
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status2\">&nbsp;";			   
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status4\">";			   
			 }
			 elseif ($this->admint==2) {
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status2\">&nbsp;";
			   $out .= "<input type=\"submit\" name=\"FormAction\" value=\"$this->status4\">";			   
			 }*/
			 
             $out .= "<input type=\"hidden\" name=\"FormName\" value=\"Transview\">";
             $out .= "</FORM>";			 		   
			 	
	   }  
	   		 

       /*$out .= $this->searchform();	    
		 
	   $dater = new datepicker();	
	   $out .= $dater->renderspace(seturl("t=transview&a=$a"),"transview");		 
	   unset($dater);
       */
						
	   
	   return ($out);
	}

	//override
    function viewtrans($id,$fname,$lname,$status,$ddate,$dtime) {
	   $p = GetReq('p');
	   $a = GetReq('a');
	   
	   $link = seturl("t=loadcart&tid=$id" , $id);
	   
       /*if ($this->admint>0) {//==1) {
			   //print checkbox 
			   $data[] = "<input type=\"checkbox\" name=\"" . $fname . 
			                                  "\" value=\"" . $fname . "\">"; 
	           $attr[] = "left;1%";											  
	   }
	   elseif ($this->admint==2) {
			   //print checkbox only if status!=1
			   $data[] = "<input type=\"checkbox\" name=\"" . $fname . 
			                                  "\" value=\"" . $fname . "\">"; 
	           $attr[] = "left;1%";											  
	   }	*/											  	   
	   
							  	  
	   //$data[] = $id;//$link;   
	   //$attr[] = "left;10%";		 
	   
	   /*switch ($status) {
			  case 0 : $data[] = $this->status0; break;
			  case 1 : $data[] = $this->status1; break;	
			  case 2 : $data[] = $this->status2; break;				  		  
			  case 3 : $data[] = $this->status3; break;
			  case 4 : $data[] = $this->status4; break;
	   }	
	   $data[] = $fname;       
	   $attr[] = "left;10%";		   
	   */	   
	   
	   $d = ($this->details) ? /*$lnk . */$this->details($id) : '&nbsp;';//$lnk;
	   $data[] = $d;//$lnk;   
	   $attr[] = "left;50%";  

	   if (is_readable($this->path . $id . ".html")) {	
	     $lnk = seturl('t=cptransviewhtml&tid='.$id,$lname);
       }
	   else 
	     $lnk = $lname;		  
	   $data[] = $lnk;//$id;//$link;   
	   $attr[] = "left;10%";	   
	   
	   $data[] = $status;   
	   $attr[] = "left;20%";	      
	   
	   $data[] = '&nbsp;';//$ddate /*. '/' . $dtime*/;   
	   $attr[] = "right;1%";	
	   
	   $data[] = $ddate;//$dtime;   
	   $attr[] = "right;19%";		   
	   
	   
	   $myarticle = new window('',$data,$attr);
       $line = $myarticle->render("center::100%::0::group_dir_body::left::0::0::");
	   unset ($data);
	   unset ($attr);
	   
       //if ($this->details) {//disable cancel and delete form buttons due to form elements in details????
	     $mydata = $line;// . '<br/>' . $this->details($id);
	     $cartwin = new window2($id . '/' . $status,$mydata,null,1,null,'HIDE',null,1);
	     $out = $cartwin->render();//"center::100%::0::group_article_body::left::0::0::"
	     unset ($cartwin);		   
	   /*}	
	   else {   
		 $out .= $line . '<hr>';
	   }*/	   
	   

	   return ($out);
	}	
	
	function viewTransactionHtml($id=null) {
	    $id = $id?$id:GetReq('tid');
	
        $file = $this->path . $id . ".html"; 
	    //echo $file;
		if (is_readable($file)) {
		  $ret = file_get_contents($file);
		
		  return ($ret);	
		}
		else
		  return false;
	} 

	//override
	function headtitle() {
	   return null; 
    }	
};
}
?>