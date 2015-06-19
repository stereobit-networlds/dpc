<?php
$__DPCSEC['RCBROWSER_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("RCBROWSER_DPC")) && (seclevel('RCBROWSER_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCBROWSER_DPC",true);

$__DPC['RCBROWSER_DPC'] = 'rcbrowser';

$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($a);

$b = GetGlobal('controller')->require_dpc('nitobi/nhandler.lib.php');
require_once($b);

$c = GetGlobal('controller')->require_dpc('gui/swfcharts.lib.php');
require_once($c);
 
$__EVENTS['RCBROWSER_DPC'][0]='cpbrowser';
$__EVENTS['RCBROWSER_DPC'][1]='cpbrattach';
$__EVENTS['RCBROWSER_DPC'][2]='cpbrsht';
//$__EVENTS['RCBROWSER_DPC'][7]='cpread';
//$__EVENTS['RCBROWSER_DPC'][8]='cpwrite';

$__ACTIONS['RCBROWSER_DPC'][0]='cpbrowser';
$__ACTIONS['RCBROWSER_DPC'][1]='cpbrattach';
$__ACTIONS['RCBROWSER_DPC'][2]='cpbrsht';
//$__ACTIONS['RCBROWSER_DPC'][7]='cpread';
//$__ACTIONS['RCBROWSER_DPC'][8]='cpwrite';

$__DPCATTR['RCBROWSER_DPC']['cpbrowser'] = 'cpbrowser,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCBROWSER_DPC'][0]='RCTRANSSQL_DPC;SQL Transactions;SQL Συναλλαγές';
$__LOCALE['RCBROWSER_DPC'][1]='_GNAVAL;Chart not available!;Στατιστική μή διαθέσιμη!';
$__LOCALE['RCBROWSER_DPC'][2]='_addrecs;Read/add records;Διαβασε/εισηγαγε εγγραφες';
$__LOCALE['RCBROWSER_DPC'][3]='_remrecs;Remove unexecuted records;Διαγραφη μη εκτελεσμάνων εγγραφων!';
$__LOCALE['RCBROWSER_DPC'][4]='_runrecs;Execute records!;Εκτελεση εγγραφων!';
$__LOCALE['RCBROWSER_DPC'][5]='_BACKDAYS; days unsynchronized!; μη ενημερωμενες ημέρες!';


class rcbrowser {

    var $path, $title;
	var $_grid, $charts;
	var $ajaxLink;
	var $hasgraph, $hasgauge;
    var $status_sid, $status_sidexp;
	var $msg;
	var $res;
	var $encoding;
	var $db;
	var $grid, $subgrid;
	var $editgrid, $tablefields;
	var $readcmd,$writecmd;
	var $primkey, $pivotkey, $foreignkey, $matchkey;
	var $hassubgrid;
	var $cmd;
	
	static $initjscode;
	static $jscode;
    static $zbuf; 
	static $zinstanse;
	//static $zdb;
	static $ztemplate;
	static $zttype;
		
	function rcbrowser($vtype='horizontal',$db=null,$title=null,$cmd=null,$grid=null,$primkey=null,$tablefields=null,$editgrid=null,$subgrid=null,$fk=null,$mk=null) {
	  $GRX = GetGlobal('GRX');	
      $char_set  = arrayload('SHELL','char_set');	  
      $charset  = paramload('SHELL','charset');	 
      $this->path = paramload('SHELL','prpath');
	  
	  if (($charset=='utf-8') || ($charset=='utf8'))
	    $this->encoding = 'utf-8';
	  else  
	    $this->encoding = $char_set[getlocal()]; 			
	
	  $this->db = &$db;
	  
	  $this->msg = null;
	  $this->title = localize($title,getlocal());
	  
	  $this->_grid = new nitobi($grid);	  
	  $this->grid = $grid; //name of table of grid	  		
	  $this->primkey = $primkey?$primkey:'id';
	  //$this->foreignkey = $fk?$fk:'id';	  
	  $this->tablefields = $tablefields ? explode(';',$tablefields) : array();	  
	  $this->pivotkey = count($this->tablefields);	  
   	  $this->matchkey = $mk?$mk:$this->getkey();	  
	  
	  if (is_object($subgrid)) {	
	    $this->hassubgrid = true;
	    $this->subgrid = & $subgrid;	  
        $this->foreignkey = $fk?$fk:$subgrid->primkey;				
	  }
	  else {
	    $this->hassubgrid = false; 
		$this->foreignkey = null;		
	  }
	  
	  //echo $this->matchkey,'>';	
			
	  $this->editgrid = $editgrid?"true":"false";				  
	  $this->cmd = $cmd?$cmd:'cpbrowser';
	  $this->readcmd = 'cpread';
	  $this->writecmd = 'cpwrite';	 
	  $this->zbuf = 0; 
	  
	  $this->ajaxLink = seturl('t='.$this->cmd.'&statsid=');	  	      
	  
	  $this->hasgraph = false;
	  $this->hasgauge = false;	  
	  $this->graphx = remote_paramload('RCTRANSSQL','graphx',$this->path);
	  $this->graphy = remote_paramload('RCTRANSSQL','graphy',$this->path);

      $this->status_sid = remote_arrayload('RCTRANSSQL','sid',$this->path);  

      $this->status_exp = remote_arrayload('RCTRANSSQL','sidexp',$this->path); 
	  
      if ($GRX) {    	
          $this->add_recs = loadTheme('aitem',localize('_addrec',getlocal())); 
          $this->rem_recs = loadTheme('ditem',localize('_remrec',getlocal())); 
          $this->run_recs = loadTheme('iitem',localize('_edirec',getlocal())); 		  		  
          $this->mail_recs = loadTheme('mailitem',localize('_mailrecs',getlocal())); 		  
		  
		  $this->sep ='&nbsp;';		  		  
      } 
      else { 	
          $this->add_recs = localize('_addrec',getlocal());
          $this->rem_recs = localize('_remrec',getlocal());
          $this->run_recs = localize('_edirec',getlocal());		  		   
          $this->mail_recs = localize('_mailrecs',getlocal());		  
		  
		  $this->sep = "|";	
      }	  
	  
	  //self::$zdb = GetGloabl('db');
	  self::$zinstanse[$grid] = (object) $this;
	  
 	  self::$initjscode = "$title = nitobi.initGrid('$grid');" . self::$initjscode;
	  //self::$initjscode = "$title = nitobi.loadComponent('$grid');". self::$initjscode; //new ver
	  
	  //when enable ...view type 2,3
	  self::$zttype = $vtype?strtolower($vtype):"horizontal";	  //"vertical";//
	  
	  if (self::$zttype=="vertical")	  
	    self::$ztemplate .= $this->endpoint($cmd) . $this->_grid->set_detail_div($this->grid.'_details',550,20,'F0F0FF',null);// . self::$ztemplate;	  
	  elseif (self::$zttype=="horizontal") 	
	    self::$ztemplate = "<td>" . $this->endpoint($cmd) . $this->_grid->set_detail_div($this->grid.'_details',550,20,'F0F0FF',null) . "</td>" . self::$ztemplate;;

	       
      $template = $this->set_template();
	  
      if ($this->hassubgrid) 
	    self::$jscode = $this->_grid->OnClick($this->pivotkey,$this->grid.'_details',$template,$this->subgrid->grid,$this->foreignkey,$this->matchkey,'onClick_'./*(self::$zbuf++)*/$this->grid) . self::$jscode;	  		   
	  else
		self::$jscode = $this->_grid->OnClick($this->pivotkey,$this->grid.'_details',$template,null,null,null,'onClick_'./*(self::$zbuf++)*/$this->grid) . self::$jscode;		
		
      $this->charts = new swfcharts;	
	  $this->hasgraph = $this->charts->create_chart_data($this->grid,"");
	  //$this->hasgauge = $this->charts->create_gauge_data($this->grid,"where cid=0",null,1,400,300,'meter');			
	}
	
    function event($event=null) {		 
	
	   switch ($event) {
		 case 'cpbrsht'  : echo $this->search();	
		                      die(); 	   
		 case 'cpbrattach'  : echo $this->show_attachments();	
		                      die(); 
		                      break;	   
		 case 'cpwrite'     : $this->set_records();	
		                      break;
		 case 'cpread'      : $this->get_records();	
		                      break;
		 case 'cpbrowse'    : 					 
		 default            : $this->nitobi_javascript();
			                  $this->sidewin(); 		 
		                      //$this->charts = new swfcharts;	
		                      //$this->hasgraph = $this->charts->create_chart_data($this->grid,"");
							  //$this->hasgauge = $this->charts->create_gauge_data($this->grid,"where cid=0",null,1,400,300,'meter');
							  
	   } 
			
    }   
	
    function action($action=null) {
	 
	  if (GetSessionParam('REMOTELOGIN')) 
	    $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	  else  
        $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 	 
	  
	  switch ($action) {
		 case 'cpbrowse'      :	   
		 default              :  
		                        $out .= $this->render();
	  }	 

	  return ($out);
    }
	
	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   //$template = $onclickdata?$onclickdata:$this->set_template();   		      
		   
	       $code = $this->init_grids(self::$initjscode);			
           
		   $code .= self::$jscode;	 
	   
		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.grid.js');		   
           $js->load_js($code,"",1);		   			   
		   unset ($js);
	  }		  	
	}
	
	function set_template() {
	       $ii = $this->matchkey?$this->matchkey:'0';
	       $jsid = 'i'. $ii;
		   
	       $template = "<A href=\"#$this->grid\">".$this->mail_recs."</A>";//startpoint		   
	
           if ($this->editgrid=="true") {
		   
	         /*$add =  seturl("t=cpbradd&grid=".$this->grid);		   	   		   
	         //$template .= "<A href=\"$add\">".$this->add_recs."</A>". $this->sep;
		     $js = "onclick=\"sndReqArg(\'".$add."\',\'".$this->grid.'_details'."\')\""; 
		     $template .= "<a href=\"#\" $js>". $this->add_recs ."</a>". $this->sep; 			 
			 
	         $del =  seturl("t=cpbrdel&grid=".$this->grid."&id=");			 
	         //$template .= "<A href=\"$del'+$jsid+'\">".$this->rem_recs."</A>". $this->sep;
		     $js = "onclick=\"sndReqArg(\'".$del."'+$jsid+'\',\'".$this->grid.'_details'."\')\""; 
		     $template .= "<a href=\"#\" $js>". $this->rem_recs ."</a>". $this->sep; 
			 			 
	         $edi =  seturl("t=cpbredit&grid=".$this->grid."&id=");						 
	         //$template .= "<A href=\"$edi'+$jsid+'\">".$this->run_recs."</A>". $this->sep;
		     $js = "onclick=\"sndReqArg(\'".$edi."'+$jsid+'\',\'".$this->grid.'_details'."\')\""; 
		     $template .= "<a href=\"#\" $js>". $this->run_recs ."</a>". $this->sep;*/
			 
			 $template .= $this->show_buttons(); 			 
		   		   
		   }  		   	

		   //$template .= "<h4>'+update_stats_id(i0,i1,i2)+'</h4>";	
		   $template .= "<table width=\"100%\" class=\"group_win_body\">";
		   
           foreach ($this->tablefields as $i=>$f) 		   	   
		     $template .= "<tr><td>".localize($f,getlocal()).":</td><td><b>'+i$i+'</b></td></tr>";	
			
		   $template .= "</table>";	
		   
		   /*$template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td>";
		   $template .= "'+show_body(i0,$jsid,i1)+'";		   	
		   $template .= "</td></tr></table>";*/	 		        
		   return ($template);	
	}
	
	function show_graph($xmlfile,$title,$url=null,$ajaxid=null,$xmax=null,$ymax=null) {
	  $gx = $this->graphx?$this->graphx:$xmax?$xmax:550;
	  $gy = $this->graphy?$this->graphy:$ymax?$ymax:250;	
	
	  $ret = $title; 	
	  $ret .= $this->charts->show_chart($xmlfile,$gx,$gy,$url,$ajaxid);
	  return ($ret);
	}
	
	function render($initdata=null) {
	
	   if ($this->msg) $out = $this->msg;
	   
       if (isset(self::$ztemplate))	 {//view method 2  'vertical'
	     
		 if (self::$zttype=="vertical")	 {
		   $data[] = self::$ztemplate;
		   $dttr[] = 'left;1%';
		 }
		 elseif (self::$zttype=="horizontal") { //view method 3 'horizontal'
	       $templatetitle = "<table><tr>" . self::$ztemplate . "</tr></table>";	 		 
		 }
		 
		 $data[] = $this->show_grids($initdata);
		 $dttr[] = 'left;99%';
		 
         $mywin = new window($templatetitle,$data,$dttr);
         $out .= $mywin->render();		 	   
	   }
	   else {
	     $toprint .= $this->show_grids($initdata);	   	
	   
         $mywin = new window('',$toprint);
         $out .= $mywin->render();	
	   }
	   
	   //HIDDEN FIELD TO HOLD STATS ID FOR AJAX HANDLE
	   $out .= "<INPUT TYPE= \"hidden\" ID= \"statsid\" VALUE=\"0\" >";	   	    
	  
	   return ($out);		   
	}		
	
	
	function init_grids($initjscode) {

	    $bodyurl = seturl("t=".$this->cmd."&tid=");	
	
        //disable alert !!!!!!!!!!!!		
		$out = "
function alert() {}\r\n 

function update_stats_id() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];
  
  
  statsid.value = str;
  //alert(statsid.value);
  //sndReqArg('$this->ajaxLink'+statsid.value,'stats');
  
  return str1+' '+str2;
}

function show_body() {
  var str = arguments[0];
  var str1 = arguments[1];
  var str2 = arguments[2];  
  
  bodyurl = str;
  
  ifr = '<iframe src =\"'+bodyurl+'str1\" width=\"100%\" height=\"350px\"><p>Your browser does not support iframes ('+str2+').</p>'+str1+'</iframe>';  
  return ifr;
}
			
function init()
{
";
        //foreach ($this->_grids as $n=>$g)
		//  $out .= $g->init_grid($n);
		$out .=  $initjscode;
	
        $out .= "\r\n}";
        return ($out);
	}
	
	function show_grid($x=null,$y=null,$filter=null,$bfilter=null) {
	
	   $x = $x?$x:400;
	   $y = $y?$y:100;
	
       if ($filter)   	   
	     $grid_get = seturl("t=".$this->readcmd."&grid=".$this->grid."&select=1");
       elseif ($bfilter)   	   
	     $grid_get = seturl('t='.$this->readcmd."&grid=".$this->grid.'&filter='.$bfilter);
	   else
	     $grid_get = seturl('t='.$this->readcmd."&grid=".$this->grid);
		 
	   $grid_set = seturl('t='.$this->writecmd."&grid=".$this->grid);		 

       foreach ($this->tablefields as $f) 
	     $this->_grid->set_text_column(localize($f,getlocal()),$f,"70","true"); 		 
	   	   		   	   	  	   
	   //$this->_grids[0]->set_datasource("check_active",array('101'=>'Active','0'=>'Inactive'),null,"value|display",true);		   //$stype = explode(",",file_get_contents($this->path . 'categories.opt'));	
	   if (is_array($this->status_sid)) {
           foreach ($this->status_sid as $i=>$s)
           $stype[$s] = $this->status_exp[$i];
           //print_r($stype); 	
           //$stype= array('-1'=>'Canceled','0'=>'Submited');
           $this->_grid->set_datasource("list_status",$stype,"status_id","status_id|status",true);	   
	   }
	   else
	     echo 'status id not defined';
		 
	   $ret = $this->_grid->set_grid_remote($grid_get,$grid_set,"$x","$y","livescrolling",17,$this->editgrid); 							  
	
	   return ($ret);	   	
	}
	
	function show_grids($initdata=null) {
	   //gets
	   $cat = GetReq('cat');	
       $filter= GetParam('filter');
		   
       $vd = $this->show_grid(550,440,null,$filter);		   
		   
       $vd .= $this->searchinbrowser();
	   
	   if ($this->hasgraph)
		   $vd .= $this->show_graph($this->grid,$this->title,seturl('t='.$this->cmd));
	   else
		   $vd .= "<h3>".localize('_GNAVAL',0)."</h3>";	   
	   
	   /*if ($this->hasgauge)
		   $vd .= $this->charts->show_gauge('income',400,300);
	   else
		   $vd .= "<h3>".localize('_GNAVAL',0)."</h3>";	   		   	   */
	   		   		   		   	   
	   
	   //grid 0 
	   $datattr[] = $vd;							  
	   $viewattr[] = "left;50%";
	   
	   if ($this->hassubgrid) 
	     $wd = $this->subgrid->show_grids();//(550,100);
	   
	   if ($initdata) {
	      $wd .= $this->_grid->set_detail_div($this->grid.'_details',550,20,'F0F0FF',$initdata);
	   }
	   else {	   	    		      		   	  

         if (!isset(self::$ztemplate))
	       $wd .= $this->_grid->set_detail_div($this->grid.'_details',550,20,'F0F0FF',null);
	   
	     $wd .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use stats");

         /*if ($this->hasgraph)
		   $wd .= $this->show_graph('transactions','Customer transactions',$this->ajaxLink,'stats');
	     else
		   $wd .= "<h3>".localize('_GNAVAL',0)."</h3>";*/
       }
	   
	   $datattr[] = $wd;
	   $viewattr[] = "left;50%"; 
		   
	   $title = $this->startpoint($this->cmd);
	   if ($this->hassubgrid)
	     $title .= '&nbsp;>&nbsp;' . $this->startpoint($this->subgrid->grid,$this->subgrid->title);
	   	   
	   $myw = new window($title,$datattr,$viewattr);
	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);		   	
	   	
	   return ($ret);	
	}	
	
	function sidewin() { 
	}
	
	function show_attachment() {
	   
	   $ret = 'attachment';
	   
	   return ($ret);
	}
	
	function endpoint($point) {
	
      $ret = "<a name=\"$point\"></a>";
	  return ($ret);
	}
	
	function startpoint($point=null,$title=null) {
	  $tt = $title?$title:$this->title;
	  $pp = $point?$point:$this->grid;
	  
      $ret = "<a href=\"#$pp\">".$tt."</a>";
	  return ($ret);
	}
	
	function getmyfather_interscope() {
	
	
	}	
	

    function searchinbrowser() {
	   //$mygrid = GetReq('grid');	
	   //$sgrid = (object) self::$zinstanse[$mygrid];
	   	
	   $act = seturl("t=cpbrsearch&grid=".$this->grid."filter=ADAGIO");
			
	   $js = "onclick=\"sndReqArg('".$act."','".$this->grid."')\""; 			
	
       $ret = "
           <form name=\"searchinbrowser\" method=\"post\" action=\"$act\">
           <input name=\"filter\" type=\"Text\" value=\"\" size=\"56\" maxlength=\"64\">
           <input name=\"Image\" type=\"Image\" src=\"../images/b_go.gif\" alt=\"\"    align=\"absmiddle\" width=\"22\" height=\"28\" hspace=\"10\" border=\"0\">
	       <A href=\"#\" $js>".$this->mail_recs."</A>		   
	       <A href=\"#$this->cmd\">".$this->mail_recs."</A>		   
           </form>";

       $ret .= "<br>Last search: " . GetParam('filter')."<br>";

       return ($ret);
    }	
	
	//nitobi get	
	/*function get_records() {	
       $filter = GetReq('filter');	
	   $whereClause='';
	   	   
       $handler = new nhandler(17,'id','Desc');
	   //$handler->debug_sql = true;	   	   		   
	   
	   if (isset($_GET['select'])) {
	     if (isset($_GET['cid'])) {
		   $whereClause=" WHERE id=".$_GET["cid"]." ";
	     } 
	     else
	       $whereClause=" WHERE id=-1";//fetch nothing	   
	   }
	   elseif ($filter) {
           $whereClause = " where (";//status like '%$filter%' or sqlquery like '%$filter%' or sqlres like '%$filter%' or date like '%$filter%' or execdate like '%$filter%' or reference like '%$filter%')";
		   foreach ($this->tablefields as $f) 
		     $whereClause .= $f . " like '%$filter%' or ";  
		   $whereClause .= $this->primkey . "=$filter )";
       }	
       else	   
	     $whereClause = null;	   
	
	   $sSQL .= "select ";
	   $sSQL .= implode(',',$this->tablefields);
	   $sSQL .= " from " . $this->grid;
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $handler->sortColumn . " " . $handler->sortDirection ." LIMIT ". $handler->ordinalStart .",". ($handler->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $this->db->Execute($sSQL,2);	
	   
	   if (($handler->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sSQL,strlen($sSQL));
		 fclose($f);
	   }	
   	   		 			 
	   $handler->handle_output($this->db,$result,$this->tablefields,'id',null,$this->encoding);	
	}		
	
	//nitobi set
	function set_records() {		   	
	
	   
       $handler = new nhandler(17,'id','Asc');	 	   
	   $sql2run = $handler->handle_input(null,$this->grid,$this->tablefields,'id');		
	
       $this->db->Execute($sql2run,3,null,1);
	   
	   if (($handler->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }	
	}*/
	
	//static nitobi get 
	function getgrid_records() {	
       $filter = GetReq('filter');
	   $mygrid = GetReq('grid');	
	   $whereClause='';
	   
	   $sgrid = (object) self::$zinstanse[$mygrid];
	   	   
       $handler = new nhandler(17,$sgrid->primkey,'Desc');
	   //$handler->debug_sql = true;	   	   		   
	   
	   //if (isset($_GET['select'])) {
	   if (isset($_GET[$sgrid->primkey])) {
	       $asksubgrid = is_numeric($_GET[$sgrid->primkey]) ? $_GET[$sgrid->primkey] : "'".$_GET[$sgrid->primkey]."'";	   
		   $whereClause = " WHERE ".$sgrid->primkey."=".$asksubgrid." ";
	   } 
	   else {	   
		 //if ($sgrid->hassubgrid) {
	       if ($filter) {
		   
		     $askfilter = is_numeric($filter) ? $filter : "'".$filter."'";
			 
             $whereClause = " where (";
		     foreach ($sgrid->tablefields as $f) 
		       $whereClause .= $f . " like '%$filter%' or ";  
		     $whereClause .= $sgrid->primkey . "= $askfilter )";
           }		 
		   else
	         $whereClause = null;	 
		 /*}  		 
		 else	 
	       $whereClause =" WHERE ".$sgrid->primkey."=-1";//fetch nothing ok..when no numeric	     
		   */
       }  
	
	   $sSQL .= "select ";
	   $sSQL .= implode(',',$sgrid->tablefields);
	   $sSQL .= " from " . $sgrid->grid;
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $handler->sortColumn . " " . $handler->sortDirection ." LIMIT ". $handler->ordinalStart .",". ($handler->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $sgrid->db->Execute($sSQL,2);	
	   
	   if (($handler->debug_sql) && ($f = fopen($sgrid->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sSQL,strlen($sSQL));
		 fclose($f);
	   }	
   	   		 			 
	   $handler->handle_output($sgrid->db,$result,$sgrid->tablefields,'id',null,$sgrid->encoding);	
	}
	
	//static nitobi set
	function setgrid_records() {		   	
	   $mygrid = GetReq('grid');	
	   $sgrid = (object) self::$zinstanse[$mygrid];
	   	   
       $handler = new nhandler(17,$sgrid->primkey,'Asc');	 	   
	   $sql2run = $handler->handle_input(null,$sgrid->grid,$sgrid->tablefields,'id');		
	
       $this->db->Execute($sql2run,3,null,1);
	   
	   if (($handler->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }	
	}
	
	function search() {
       $filter = GetReq('filter');
	   $mygrid = GetReq('grid');	
	   $whereClause='';
	   
	   $sgrid = (object) self::$zinstanse[$mygrid];
	   	   
       $handler = new nhandler(17,$sgrid->primkey,'Desc');
	   //$handler->debug_sql = true;	   	   		   
	   
	   //if (isset($_GET['select'])) {
	   if (isset($_GET[$sgrid->primkey])) {
	       $asksubgrid = is_numeric($_GET[$sgrid->primkey]) ? $_GET[$sgrid->primkey] : "'".$_GET[$sgrid->primkey]."'";	   
		   $whereClause = " WHERE ".$sgrid->primkey."=".$asksubgrid." ";
	   } 
	   else {	   
		 //if ($sgrid->hassubgrid) {
	       if ($filter) {
		   
		     $askfilter = is_numeric($filter) ? $filter : "'".$filter."'";
			 
             $whereClause = " where (";
		     foreach ($sgrid->tablefields as $f) 
		       $whereClause .= $f . " like '%$filter%' or ";  
		     $whereClause .= $sgrid->primkey . "= $askfilter )";
           }		 
		   else
	         $whereClause = null;	 
		 /*}  		 
		 else	 
	       $whereClause =" WHERE ".$sgrid->primkey."=-1";//fetch nothing ok..when no numeric	     
		   */
       }  
	
	   $sSQL .= "select ";
	   $sSQL .= implode(',',$sgrid->tablefields);
	   $sSQL .= " from " . $sgrid->grid;
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $handler->sortColumn . " " . $handler->sortDirection ." LIMIT ". $handler->ordinalStart .",". ($handler->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $sgrid->db->Execute($sSQL,2);	
	   
	   if (($handler->debug_sql) && ($f = fopen($sgrid->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sSQL,strlen($sSQL));
		 fclose($f);
	   }	
   	   		 			 
	   $handler->handle_output($sgrid->db,$result,$sgrid->tablefields,'id',null,$sgrid->encoding);	
	}
	
	//return primkey num in array...default
	function getkey() {
	
	    reset($this->tablefields);
		
		foreach ($this->tablefields as $k=>$f) {
		  if ($f === $this->primkey)
		    return ($k);
		}	
	}
	
	function show_buttons($staticobj=null) {
	
	       if (is_object($staticobj))
		     $sgrid = $staticobj;
		   else
		     $sgrid = & $this;	 
	
	       $ii = $sgrid->matchkey?$sgrid->matchkey:'0';
	       $jsid = 'i'. $ii;	
	
	         $add =  seturl("t=cpbradd&grid=".$sgrid->grid);		   	   		   
	         //$template .= "<A href=\"$add\">".$this->add_recs."</A>". $this->sep;
		     $js = "onclick=\"sndReqArg(\'".$add."\',\'".$sgrid->grid.'_details'."\')\""; 
		     $template .= "<a href=\"#\" $js>". $sgrid->add_recs ."</a>". $sgrid->sep; 			 
			 
	         $del =  seturl("t=cpbrdel&grid=".$sgrid->grid."&id=");			 
	         //$template .= "<A href=\"$del'+$jsid+'\">".$this->rem_recs."</A>". $this->sep;
		     $js = "onclick=\"sndReqArg(\'".$del."'+$jsid+'\',\'".$sgrid->grid.'_details'."\')\""; 
		     $template .= "<a href=\"#\" $js>". $sgrid->rem_recs ."</a>". $sgrid->sep; 
			 			 
	         $edi =  seturl("t=cpbredit&grid=".$sgrid->grid."&id=");						 
	         //$template .= "<A href=\"$edi'+$jsid+'\">".$this->run_recs."</A>". $this->sep;
		     $js = "onclick=\"sndReqArg(\'".$edi."'+$jsid+'\',\'".$sgrid->grid.'_details'."\')\""; 
		     $template .= "<a href=\"#\" $js>". $sgrid->run_recs ."</a>". $sgrid->sep;
			 
			 return ($template);	
	}
	
	function add_record($callback=null) {
	   $mygrid = GetReq('grid');	
	   $sgrid = (object) self::$zinstanse[$mygrid];				   	
	   
	   if ($callback) {
	     $out = GetGlobal('controller')->calldpc_method("$callback");
	   }
	   else	{ 	   
       //<phpdac> dataforms.setform use myform+myform+5+5+20+100+0+0 </phpdac>
	   GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+20+100+0+0');
       //<phpdac> dataforms.setformadv use 0+0+30+20 </phpdac>
	   GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+30+20+id');	  
       //<phpdac> dataforms.setformgoto use _LIST </phpdac>
	   
       //GetGlobal('controller')->calldpc_method('dataforms.set_id use id');	   
	   GetGlobal('controller')->calldpc_method('dataforms.setformgoto use DPCLINK:cpvphoto:OK');	  
       //<phpdac> dataforms.getform use update.rccustomers+dataformsinsert,dataformsupdate,unsubscribe+Post+Clear++A,*B++id=39+dummy </phpdac>
	   
	   GetGlobal('controller')->calldpc_method('dataforms.setformtemplate use cpitemsadd');		   
	   
       $fields = implode(',',$sgrid->tablefields);

	   foreach ($sgrid->tablefields as $t)
	     $title[] = localize($t,getlocal());
	   $titles = implode(',',$title);					 
 
	   $out .= GetGlobal('controller')->calldpc_method("dataforms.getform use insert.".$mygrid."+dataformsinsert+Post+Clear+$fields+$titles++dummy+dummy");	  
	   }
	   
	   $menu = self::show_buttons($sgrid);
	   	   
       //return ($out);	
	   $s = $sgrid->grid.'_details';	
	   die("$s|".$menu . $out); //ajax return
	}
	
	function edit_record($callback=null) {
	   $id = GetParam('id');
	   $mygrid = GetReq('grid');	
	   $sgrid = (object) self::$zinstanse[$mygrid];	 
	   
	   if ($callback) {
	     $out = GetGlobal('controller')->calldpc_method("$callback");
	   }
	   else	{     	   
	   
       //<phpdac> dataforms.setform use myform+myform+5+5+20+100+0+0 </phpdac>
	   GetGlobal('controller')->calldpc_method('dataforms.setform use myform+myform+5+5+20+100+0+0');
       //<phpdac> dataforms.setformadv use 0+0+30+20 </phpdac>
	   GetGlobal('controller')->calldpc_method('dataforms.setformadv use 0+0+30+20');	  
       //<phpdac> dataforms.setformgoto use _LIST </phpdac>
	   GetGlobal('controller')->calldpc_method('dataforms.setformgoto use DPCLINK:cpitems:OK');	  
       //<phpdac> dataforms.getform use update.rccustomers+dataformsinsert,dataformsupdate,unsubscribe+Post+Clear++A,*B++id=39+dummy </phpdac>
	   GetGlobal('controller')->calldpc_method('dataforms.setformtemplate use cpitemsmod');	   
	   
       $fields =  implode(',',$sgrid->tablefields);

	   foreach ($sgrid->tablefields as $t)
	     $title[] = localize($t,getlocal());
	   $titles = implode(',',$title);	 
		 
				 	                                                                                                   																			//kybismos_opt
	   $out .= GetGlobal('controller')->calldpc_method("dataforms.getform use update.".$mygrid."+dataformsupdate+Post+Clear+$fields+$titles++".$sgrid->primkey."=$id+dummy");	  	
	   }
	   
	   $menu = self::show_buttons($sgrid);	
	   
       //return ($out);
	   $s = $sgrid->grid.'_details';	
	   die("$s|".$menu . $out); //ajax return
	} 
	
	function del_record($callback=null) {
	   $mygrid = GetReq('grid');	
	   $sgrid = (object) self::$zinstanse[$mygrid];	
	
	   if ($callback) {
	     $out = GetGlobal('controller')->calldpc_method("$callback");
	   }
	   else
	     $out = 'Delete Record?';
	   
	   $menu = self::show_buttons($sgrid);
	   	   
       //return ($out);
	   
	   $s = $sgrid->grid.'_details';	
	   die("$s|".$menu . $out); //ajax return	
	}								
			
};
}
?>