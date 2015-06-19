<?php
$__DPCSEC['RCTRANSSQL2_DPC']='1;1;1;1;1;1;1;1;1';

if (!defined("RCTRANSSQL2_DPC"))  {
define("RCTRANSSQL2_DPC",true);

$__DPC['RCTRANSSQL2_DPC'] = 'rctranssql2';
/*
$a = GetGlobal('controller')->require_dpc('nitobi/nitobi.lib.php');
require_once($a);

$b = GetGlobal('controller')->require_dpc('nitobi/nhandler.lib.php');
require_once($b);
*/
$d = GetGlobal('controller')->require_dpc('shop/rcsyncsql.dpc.php');
require_once($d);
 
$__EVENTS['RCTRANSSQL2_DPC'][0]='cptranssql';
$__EVENTS['RCTRANSSQL2_DPC'][1]='cptsqlshow';
$__EVENTS['RCTRANSSQL2_DPC'][2]='cptsqllink';
$__EVENTS['RCTRANSSQL2_DPC'][3]='cptsqlexe';
$__EVENTS['RCTRANSSQL2_DPC'][4]='cptsqldel';
$__EVENTS['RCTRANSSQL2_DPC'][5]='cptsqlrun';
$__EVENTS['RCTRANSSQL2_DPC'][6]='cptsqlrerun';
$__EVENTS['RCTRANSSQL2_DPC'][7]='cptsqlnread';
$__EVENTS['RCTRANSSQL2_DPC'][8]='cptsqlnwrite';

$__ACTIONS['RCTRANSSQL2_DPC'][0]='cptranssql';
$__ACTIONS['RCTRANSSQL2_DPC'][1]='cptsqlshow';
$__ACTIONS['RCTRANSSQL2_DPC'][2]='cptsqllink';
$__ACTIONS['RCTRANSSQL2_DPC'][3]='cptsqlexe';
$__ACTIONS['RCTRANSSQL2_DPC'][4]='cptsqldel';
$__ACTIONS['RCTRANSSQL2_DPC'][5]='cptsqlrun';
$__ACTIONS['RCTRANSSQL2_DPC'][6]='cptsqlrerun';
$__ACTIONS['RCTRANSSQL2_DPC'][7]='cptsqlnread';
$__ACTIONS['RCTRANSSQL2_DPC'][8]='cptsqlnwrite';

$__DPCATTR['RCTRANSSQL2_DPC']['cptranssql'] = 'cptranssql,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['RCTRANSSQL2_DPC'][0]='RCTRANSSQL2_DPC;SQL Transactions;SQL Συναλλαγές';
$__LOCALE['RCTRANSSQL2_DPC'][1]='_GNAVAL;Chart not available!;Στατιστική μή διαθέσιμη!';
$__LOCALE['RCTRANSSQL2_DPC'][2]='_addrecs;Read/add records;Διαβασε/εισηγαγε εγγραφες';
$__LOCALE['RCTRANSSQL2_DPC'][3]='_remrecs;Remove unexecuted records;Διαγραφη μη εκτελεσμάνων εγγραφων!';
$__LOCALE['RCTRANSSQL2_DPC'][4]='_runrecs;Execute records!;Εκτελεση εγγραφων!';
$__LOCALE['RCTRANSSQL2_DPC'][5]='_BACKDAYS; days unsynchronized!; μη ενημερωμενες ημέρες!';

class rctranssql2 extends rcsyncsql {

    var $reset_db, $title;
	var $_grids, $charts;
	var $ajaxLink;
	var $hasgraph, $hasgauge;
    var $status_sid, $status_sidexp;
	
	var $cycle, $datestyle, $initstart;
	var $res;
	var $encoding;
	
	var $islocalfile;
		
	function rctranssql2() {
	  $GRX = GetGlobal('GRX');	
	
      rcsyncsql::rcsyncsql();
	  //override if exist
	  if ($tpath = paramload('RCTRANSSQL','path'))
	    $this->path = paramload('SHELL','prpath') . $tpath;	
		
      $char_set  = arrayload('SHELL','char_set');	  
      $charset  = paramload('SHELL','charset');	  		
	  if (($charset=='utf-8') || ($charset=='utf8'))
	    $this->encoding = 'utf-8';
	  else  
	    $this->encoding = $char_set[getlocal()]; 			
	
	  $this->title = localize('RCTRANSSQL_DPC',getlocal());		
	  $this->reset_db = false;
	  
      $this->address = remote_paramload('RCTRANSSQL','url',$this->path);
      $this->syncfiles = remote_arrayload('RCTRANSSQL','files',$this->path);		  
      $this->cycle = remote_arrayload('RCTRANSSQL','cycle',$this->path); //seconds of next run		  
      $this->datestyle = remote_paramload('RCTRANSSQL','datestyle',$this->path);	  
      $this->initstart = remote_paramload('RCTRANSSQL','initstart',$this->path);
      $this->islocalfile = remote_paramload('RCTRANSSQL','islocalfile',$this->path);	  
	  $this->trimwords[] = array("\r\n", "no rows selected", "rows selected");		 
	  $this->res = null; 
	  
	  //$this->_grids[] = new nitobi("Transactions");	
	  
	  $this->ajaxLink = seturl('t=cptsqlshow&statsid='); //for use with...	      
	  
	  $this->hasgraph = false;
	  $this->hasgauge = false;	  
	  $this->graphx = remote_paramload('RCTRANSSQL','graphx',$this->path);
	  $this->graphy = remote_paramload('RCTRANSSQL','graphy',$this->path);

      $this->status_sid = remote_arrayload('RCTRANSSQL','sid',$this->path);  

      $this->status_exp = remote_arrayload('RCTRANSSQL','sidexp',$this->path); 
	  
      if ($GRX) {    	
          $this->add_recs = loadTheme('aitem',localize('_addrecs',getlocal())); 
          $this->rem_recs = loadTheme('ditem',localize('_remrecs',getlocal())); 
          $this->run_recs = loadTheme('iitem',localize('_runrecs',getlocal())); 		  		  
          $this->mail_recs = loadTheme('mailitem',localize('_mailrecs',getlocal())); 		  
		  
		  $this->sep ='&nbsp;';// loadTheme('lsep');		  		  
      } 
      else { 	
          $this->add_recs = localize('_addrecs',getlocal());
          $this->rem_recs = localize('_remrecs',getlocal());
          $this->run_recs = localize('_runrecs',getlocal());		  		   
          $this->mail_recs = localize('_mailrecs',getlocal());		  
		  
		  $this->sep = "|";	
      }	  
	}
	
    function event($event=null) {

	   //ALLOW EXPRIRED APPS
	   /////////////////////////////////////////////////////////////
	   if (GetSessionParam('LOGIN')!='yes') die("Not logged in!");//	
	   /////////////////////////////////////////////////////////////		 
	
	   switch ($event) {
		 case 'cptsqlnwrite':$this->save_transsql_list();	
		                     break;
		 case 'cptsqlnread': $this->get_transsql_list();	
		                     break;
							 							 	   
		 case 'cptsqlrerun': $this->res = $this->rerun_sql();
                             //$this->nitobi_javascript();
			                 $this->sidewin(); 
		                     break;	 
							 	   
		 case 'cptsqllink':  echo $this->show_transaction_data();
		                     die();
		                     break;	   
		 case 'cptsqlshow': if (!$cvid = GetParam('statsid')) $cvid=-1; 
		                      $this->charts = new swfcharts;	
		                      $this->hasgraph = $this->charts->create_chart_data('transcust','where cid='.$cvid);
							  break; 	 

		 case 'cptsqlrun'  : $this->res = $this->run_sql();
                              //$this->nitobi_javascript();
			                  $this->sidewin(); 
							  break;							  
		 case 'cptsqldel'  : //$this->res = $this->unsync_sql();//replace with...
		                      $this->res = $this->delete_error_sql();
                              //$this->nitobi_javascript();
			                  $this->sidewin(); 
							  break;		 							  
		 case 'cptsqlexe'   : $this->res = $this->sync_sql();					    
	     case 'cptranssql'  :
		 default            : //$this->nitobi_javascript();
			                  $this->sidewin(); 		 
		                      if ($this->reset_db) $this->reset_db();
		                      /*$this->charts = new swfcharts;	
		                      $this->hasgraph = $this->charts->create_chart_data('transactions',"");
							  $this->hasgauge = $this->charts->create_gauge_data('income',"where cid=0",null,1,400,300,'meter');
							  */
	   } 
			
    }   
	
    function action($action=null) {
	 
	  if (GetSessionParam('REMOTELOGIN')) 
	    $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	  else  
        $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 	 
	  
	  switch ($action) {
	  
		 case 'cptsqlrerun'     : 	  
                             	$out .= $this->show_transactions();								
								break;	  
	  
		 case 'cptsqlshow'    : if ($this->hasgraph)
		                          $out = $this->show_graph('transcust','Customer Transactions',$this->ajaxLink,'stats');
							    else
							      $out = "<h3>".localize('_GNAVAL',0)."</h3>";	
							    die('stats|'.$out); //ajax return
							    break; 
		 case 'cptsqlrun'      : 	  
                             	$out .= $this->show_transactions();								
								break;								
		 case 'cptsqldel'      : 	   
                             	$out .= $this->show_transactions();								
								break;
		 case 'cptsqlexe'     :   
		                        $out .= $this->show_transactions();
								break;								
	     case 'cptranssql'    : 
		 default              :  
		                        $out .= $this->show_transactions();
	  }	 

	  return ($out);
    }
	
	//override
    function sync_sql($localexec=null, $encfrom=null, $encto=null) {
       $link = GetGlobal('db');
	   $dstyle = $this->datestyle?$this->datestyle:'dmY';
	   
	   if (($encfrom!=null) && ($encto!=null)) 
	     echo "Encoding:" . $encfrom . "->" . $encto;
	 
	   if ($localexec!=null) {//bin file get in
	     //html out to mail
	     $html_start = null;//'<html><body>';
		 $html_end = null;//'</body></html>';	   
	     $execbin = remote_paramload('RCTRANSSQL','binsyncfile',$this->path);
	     if (!$execbin) 
	       return($html_start.'Sevice Deactivated!!'.$html_end);
       }
	   else { 
	     //no html web exec
	     $html_start = null;
		 $html_end = null;
	   }	
	   
	   if (!$lasttime = GetReq('startform')) //get req for manual set
	     $lasttime = $this->lastexecsql(1);
	   
	   $timenow = time(); //timestamp	
                     
	   $from = (int) $prevrun;
	   $to = (int) $today;
	   
	   //paging import
	   /*if ($lasttime>10)
	     $steplimit = 10;
	   else*/
	   	 $steplimit = GetReq('endto')?GetReq('endto'):0;//get req for manual set
		 
	   echo 'LAST_TIME>',$lasttime;	 
	   //print_r($this->syncfiles);
	   
       if (is_array($this->syncfiles)) {
	   
		 set_time_limit(0);
		   
	     foreach ($this->syncfiles as $f=>$sfile) { 
		 
	       for ($i=$lasttime;$i>=$steplimit;$i--) {		 
             $nowrun  = date($dstyle, mktime(0, 0, 0, date("m")  , (date("d")-$i), date("Y")));
			 $sourcedate = date("Y-m-d", mktime(0, 0, 0, date("m")  , (date("d")-$i), date("Y")));		   
		   
		     //$ret .=  '<br><h2>'.$sfile.'</h2><br>';	
			 if ($this->islocalfile)//get it from local file system
			   $rfile = $this->urlpath . $this->address.$nowrun.'/'.$sfile;
			 else //get it from remote address	 
			   $rfile = $this->address.$nowrun.'/'.$sfile;
			 //echo 'RFILE>',$rfile,'<br>';
			 
			 if ($localexec) {
			   $ret .=  $this->remote_execute_sql($rfile,$sourcedate,$encfrom,$encto);
			 }
			 else {
			   $ret .= '<br><h3>'. $i.'/'.$rfile.'</h3><br>';
               $ret .=  $this->remote_execute_sql($rfile,$sourcedate,$encfrom,$encto);
               $ret .=  '<br>---------------------------<br>';		 
			 }  
		   }	 
		 }
		 
		 set_time_limit(30);			 
	   }
	   
	   return ($html_start.$ret.$html_end);  
    }	
	
	//override
    function remote_execute_sql($file,$sourcedate=null,$encfrom=null,$encto=null) {
      $db = GetGlobal('db');	
      $myfile = @file_get_contents($file);
      $today = date("Y-m-d h:m:s");	  
	  $sqldate = $sourcedate?$sourcedate:$today;
	  
      if ($myfile) {
        //$ret = '<br>----------------------------------------------------------------------------'.$file."<br>";
		
		foreach ($this->trimwords as $x=>$w) {
		   if ($x==0)
		     $tfile = str_replace($w,'',$myfile);
		   else
		     $tfile = str_replace($w,';',$tfile);//record bypass
		}
		//echo $tfile,'------<br>';
        $sql = explode(";",$tfile);	
	    $current_batch = (array) $sql;	//one batch
		
        $i=0;
        $ix=0;	 
        foreach ($current_batch as $s=>$stm) {
		  if ($this->isvalidsql($stm)) {
           $i+=1;
		   //$qry = iconv('ISO-8859-7', 'UTF-8//IGNORE', str_replace('"','\"',trim($stm)));
		   $qry = mb_convert_encoding(str_replace('"','\"',trim($stm)), 'UTF-8', 'ISO-8859-7');
		   $sSQL = "insert into syncsql (fid,date,status,reference,sqlquery) values ($i,\"$sqldate\",0,\"$file\",\"" . $qry . "\")";
		   //$ret .= $sSQL;
		   if ($result = $db->Execute($sSQL,1) ) {
		     //echo '>',$link->Affected_Rows(),'<br>';
		     if ($x = $db->Affected_Rows()) {
		       $ix+=1;
			 }  
		     else
		       $error_sql .= "<br>" . "Error:" . $db->error;
		   }	  
           else
             $ret .= "<br>" . "Error:" . $db->error; 									  		   
		  }//if		   
		}//foreach   
		
	    $ret .= '<br>'. $i.' sql records readed!';
        $ret .= '<br>'.$ix. ' queries imported!';
		if ($error_sql) {	 
	      $ret .= '<br>-----------------------Errors';
	      $ret .= '<br>' . $error_sql;		  		  
		}		
      }
	  else {
        $ret = "File not exist ($file)!";	
	  }	
	
      return $ret; 
    }
	
	function lastexecsql($returndiff=null,$type=null) {
      $db = GetGlobal('db');	
	  $timenow = time(); //timestamp  
	  $dstyle = $this->datestyle?$this->datestyle:'dmY';	  	  
	  
	  $sSQL = "select date from syncsql";// where status=1"; no matter if executed
	  //$sSQL .= $whereClause;
	  $sSQL .= " ORDER BY time desc limit 1";
	  //echo $sSQL;
	   
      $result = $db->Execute($sSQL,2);
	  $ret = $result->fields[0];//$na[0]?$na[0]:0;
	  echo 'LAST_EXEC>',$ret;
      if ($ret>0) {	  
	  
	   if ($returndiff) {
	    switch ($type) {	  		  
		
		  default :
	                $today = date('d-m-Y');		  
	                $lastrun = convert_date($ret,'-YMD');
	                $expres = $this->date_diff($lastrun,$today);	 			
	                echo 'DIFF>',$lastrun,'.',$today,'.',$expres,'<br>';			
			
			        return ($expres-1);
		}//switch
	   }//return diff
	   else {
	     return ($ret?$ret:0);		  
       }
	  }//if
	  else {
	    return ($this->initstart?$this->initstart:0); 	  		
	  }	
	}
	
	function unsync_sql() {
      $db = GetGlobal('db');	
	
	  $sSQL = "delete from syncsql where status<=0";
	  if ($recid = GetReq('id'))
	    $sSQL .= " and id=" . $recid;	  
	  $ret .= $sSQL;
	  if ($result = $db->Execute($sSQL,1) ) {
        return null; 
	  }  
	  else
	    $ret .= "<br>" . "Error:". $db->error;   
			 
	  return ($ret);	 	
	}
	
	function delete_error_sql() {
      $db = GetGlobal('db');	
	
	  $sSQL = "delete from syncsql where status<=-1";
	  if ($recid = GetReq('id'))
	    $sSQL .= " and id=" . $recid;	  
	  $ret .= $sSQL;
	  if ($result = $db->Execute($sSQL,1) ) {
        return null; 
	  }  
	  else
	    $ret .= "<br>" . "Error:". $db->error;   
			 
	  return ($ret);	 	
	}	
	
	function run_sql($recid=null,$localexec=null) {
      $db = GetGlobal('db');
      $now = date("Y-m-d");	
	  
	   if ($localexec!=null) {//bin file get in
	     //html out to mail
	     $html_start = null;//'<html><body>';
		 $html_end = null;//'</body></html>';	   
	     $execbin = remote_paramload('RCTRANSSQL','binsyncfile',$this->path);
	     if (!$execbin) 
	       return($html_start.'Sevice Deactivated!!'.$html_end);
       }
	   else { 
	     //no html web exec
	     $html_start = null;
		 $html_end = null;
	   }	    	
	
	  $sSQL = "select id,sqlquery from syncsql where status<=0";
	  if ($recid = GetReq('id'))
	    $sSQL .= " and id=" . $recid;
	  $sSQL .= " order by id ASC";	
		
	  $ret .= '<br>'.$sSQL; 
	  //echo $sSQL;
	  $ix = 0;
	  if ($result = $db->Execute($sSQL,2) ) {
	    //print_r($result); 
		//echo '>',$db->Affected_Rows();
		
        set_time_limit(0);		
		
        foreach ($result as $i=>$record) {		
		
		  $runSQL = $record[1];//.'xyz'; 
		  //echo $runSQL; 
		  $ret .= '<br>'.$runSQL; 
		  //$res = $db->Execute($runSQL,1);
		  //if ($x = $db->Affected_Rows()) {
		  if ($res = $db->Execute($runSQL,1)) {
		    //echo $runSQL;
		    $ix+=1;
			$postSQL = 'update syncsql set status=1,execdate="' . $now . '",sqlres=""'.' where id='. $record[0]; 
		    $ps = $db->Execute($postSQL,1);			
		  }
		  else {
		    $error_sql .= "<br>" . "Error:" . $db->error;
			$postSQL = 'update syncsql set status=-1,execdate="' . $now . '",sqlres="Error:"'.$db->error.' where id='. $record[0]; 
		    $ps = $db->Execute($postSQL,1);			 		  
		  }
		  $ret .= '<br>'.$postSQL; //echo $postSQL;	
		} 
	    $ret .= '<br>'. ($i+1) .' sql records run!';
        $ret .= '<br>'.$ix. ' queries executed!';
		if ($error_sql) {	 
	      $ret .= '<br>-----------------------Errors';
	      $ret .= '<br>' . $error_sql;		  		  
		}	

        set_time_limit(30);		
	  }  
	  else
	    $ret .= "<br>" . "Error:". $db->error;   
			 
	  return ($ret);	 	
	}	
	
	function rerun_sql() {
      $db = GetGlobal('db');
      $now = date("Y-m-d");	  	
	  
	  if ($recid = GetReq('id')) {
	    $sSQL = "select id,sqlquery from syncsql where status<=0";
	    $sSQL .= " and id=" . $recid;
		
	    $ret .= '<br>'.$sSQL; 
	    //echo $sSQL;
	    $ix = 0;
	    if ($result = $db->Execute($sSQL,2) ) {
	      //print_r($result); 
	  	  //echo '>',$db->Affected_Rows();
          foreach ($result as $i=>$record) {
		    $runSQL = GetParam('rerunsql')?GetParam('rerunsql'):$record[1];//.'xyz'; 
		    //echo $runSQL; 
		    $ret .= '<br>'.$runSQL; 
		    //$res = $db->Execute($runSQL,1);
		    //if ($x = $db->Affected_Rows()) {
		    if ($res = $db->Execute($runSQL,1)) {
		      //echo $runSQL;
		      $ix+=1;
			  $postSQL = 'update syncsql set sqlquery="'.str_replace('"','\"',trim($runSQL)).'",status=1,execdate="' . $now . '",sqlres=""'.' where id='. $record[0]; 
		      $ps = $db->Execute($postSQL,1);			
		    }
		    else {
		      $error_sql .= "<br>" . "Error:" . $db->error;
			  $postSQL = 'update syncsql set status=-1,execdate="' . $now . '",sqlres="Error:"'.$db->error.' where id='. $record[0]; 
		      $ps = $db->Execute($postSQL,1);			 		  
		    }
		    $ret .= '<br>'.$postSQL; //echo $postSQL;	
		  } 
	      $ret .= '<br>'. ($i+1) .' sql records run!';
          $ret .= '<br>'.$ix. ' queries executed!';
		  if ($error_sql) {	 
	        $ret .= '<br>-----------------------Errors';
	        $ret .= '<br>' . $error_sql;		  		  
		  }		
	    }  
	    else
	      $ret .= "<br>" . "Error:". $db->error;   
	  }//id recid	  
			 
	  return ($ret);	 	
	}		
	
	function isvalidsql($sql) {
	
	  if ((stristr($sql,'insert')) ||
	      (stristr($sql,'update')) ||
		  (stristr($sql,'delete ')) ||
		  (stristr($sql,'select')))
		  return true;
	  else
	      return false;	  
		  
	}
		
  function sqlform($sql=null,$msg=null) {
       $myaction = seturl("t=cptsqlrerun&id=");  
	   $mycmd = "cptsqlrerun";
	   $mymsg = $msg?$msg:"Re-run SQL query.";
	   
       $ret = "<form name=\"rerunsql\" method=\"post\" action=\"$myaction'+i0+'\">";
       $ret .= "<textarea name=\"rerunsql\" cols=\"70\" rows=\"8\">";
       $ret .= "'+i6+'";//$sql;
       $ret .= "</textarea><br>";
	   //last search hidden
       $ret .= "<input name=\"filter\" type=\"hidden\" value=\"".GetParam('filter')."\" size=\"56\" maxlength=\"64\">";
       $ret .= "<input type=\"submit\" value=\"" . "Run SQL" . "\">";
       $ret .= "<input type=\"hidden\" name=\"FormName\" value=\"$mycmd\">";	   
       $ret .= "</form>";	   
	   
	   return ($ret);  
  }	
/*	
	function nitobi_javascript() {
      if (iniload('JAVASCRIPT')) {

		   $template = $this->set_template();   		      
		   
	       $code = $this->init_grids();			

		   $code .= $this->_grids[0]->OnClick(10,'TransactionDetails',$template);//,'Customertrans','vid',2);
	   
		   $js = new jscript;
		   $js->setloadparams("init()");
           $js->load_js('nitobi.grid.js');		   
           $js->load_js($code,"",1);			   
		   unset ($js);
	  }		
	}
	
	function set_template() {
	
	       $exe =  seturl("t=cptsqlexe");
	       $del =  seturl("t=cptsqldel&id=");
	       $run =  seturl("t=cptsqlrun&id=");		   	   		   
	       $template = "<A href=\"$exe\">".$this->add_recs."</A>". $this->sep;
	       $template .= "<A href=\"$del'+i0+'\">".$this->rem_recs."</A>". $this->sep;
	       $template .= "<A href=\"$run'+i0+'\">".$this->run_recs."</A>";//. $this->sep;		   	

		   $template .= "<h4>'+update_stats_id(i0,i1,i8)+'</h4>";	
		   $template .= "<table width=\"100%\" class=\"group_win_body\">";	   
		   $template .= "<tr><td>".localize('AA',getlocal()).":</td><td><b>'+i0+'</b></td></tr>";	
		   $template .= "<tr><td>".localize('FID',getlocal()).":</td><td><b>'+i1+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('Timestamp',getlocal()).":</td><td><b>'+i2+'</b></td></tr>";		   
		   $template .= "<tr><td>".localize('Date',getlocal()).":</td><td><b>'+i3+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('ExecDate',getlocal()).":</td><td><b>'+i4+'</b></td></tr>";		
		   $template .= "<tr><td>".localize('Status',getlocal()).":</td><td><b>'+i5+'</b></td></tr>";				   		   
		   $template .= "<tr><td>".localize('Query',getlocal()).":</td><td><b>'+i6+'</b></td></tr>";	
		   $template .= "<tr><td>".localize('Result',getlocal()).":</td><td><b>'+i7+'</b></td></tr>";				   		   
		   $template .= "<tr><td>".localize('Reference',getlocal()).":</td><td><b>'+i8+'</b></td></tr>";
		   $template .= "<tr><td>".localize('ID',getlocal()).":</td><td><b>'+i0+'</b></td></tr>";		
		   //$template .= "</td><td>&nbsp;";	
		   $template .= "</table>";	
		   
		   $template .= "<table width=\"100%\" class=\"group_win_body\"><tr><td>";
		   $template .= "'+show_body(i8,i1,i0)+'";		   	
		   $template .= "</td></tr></table>";
		   
		   $template .= $this->sqlform();	 		        
		   
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
	   
	   $toprint .= $this->show_grids();	   	
	   
       $mywin = new window($this->title,$toprint);
       $out .= $mywin->render();	
	   
	   //HIDDEN FIELD TO HOLD STATS ID FOR AJAX HANDLE
	   $out .= "<INPUT TYPE= \"hidden\" ID= \"statsid\" VALUE=\"0\" >";	   	    
	  
	   return ($out);		   
	}		
	
	function reset_db() {
        $db = GetGlobal('db'); 
	 
	    $sSQL0 = "delete from transsql";
	    $result0 = $db->Execute($sSQL0,1);	
	    if ($result0) 
		  $message = "Empty table ...\n";
		
	    //create table
	    /*$sSQL1 = "";
		  
	    $result1 = $db->Execute($sSQL1,1);
	    if ($result1) $message .= "Create table ...\n";*/
	  
	    setInfo($message);	  	
	}
/*	
	function init_grids() {

	    $bodyurl = seturl("t=cptranslink&tid=");//not used	
	
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
  ifr = '<iframe src =\"'+bodyurl+'\" width=\"100%\" height=\"250px\"><p>Your browser does not support iframes ('+str2+').</p>'+str1+'</iframe>';  
  return ifr;
}
			
function init() {
";
        foreach ($this->_grids as $n=>$g)
		  $out .= $g->init_grid($n);
	
        $out .= "\r\n}";
        return ($out);
	}
	
	function show_grid($x=null,$y=null,$filter=null,$bfilter=null) {
	
	   $x = $x?$x:400;
	   $y = $y?$y:100;
	
       if ($filter)   	   
	     $grid1_get = seturl('t=cptsqlnread&select=1');//"shhandler.php?t=shngettranssql&select=1";
       elseif ($bfilter)   	   
	     $grid1_get = seturl('t=cptsqlnread&filter='.$bfilter);//"shhandler.php?t=shngettranssql&filter=".$bfilter;
	   else
	     $grid1_get = seturl('t=cptsqlnread');//"shhandler.php?t=shngettranssql";
		 
	   $grid1_set = seturl('t=cptsqlnwrite');//"shhandler.php?t=shnsettranssql";		 

	   $this->_grids[0]->set_text_column("A/A","id","70","true"); 		 
	   $this->_grids[0]->set_text_column("FID","fid","70","true");   	
	   $this->_grids[0]->set_text_column("time","time","70","true");		      	      	   	   	         	   	   	   	
	   $this->_grids[0]->set_text_column("Date","date","80","true");	
	   $this->_grids[0]->set_text_column("ExecDate","execdate","80","true");	
	   $this->_grids[0]->set_text_column("Status","status","70","true","LISTBOX","list_status","status","status_id");		   	   	   	   
	   $this->_grids[0]->set_text_column("Query","sqlquery","200","true");		   
	   $this->_grids[0]->set_text_column("Result","sqlres","100","true");		   	   	   
	   $this->_grids[0]->set_text_column("reference","reference","100","true");		   
	   $this->_grids[0]->set_text_column("ID","id","80","true");		   
	   	   		   	   	  	   
	   //$this->_grids[0]->set_datasource("check_active",array('101'=>'Active','0'=>'Inactive'),null,"value|display",true);		   //$stype = explode(",",file_get_contents($this->path . 'categories.opt'));	
	   if (is_array($this->status_sid)) {
           foreach ($this->status_sid as $i=>$s)
           $stype[$s] = $this->status_exp[$i];
           //print_r($stype); 	
           //$stype= array('-1'=>'Canceled','0'=>'Submited');
           $this->_grids[0]->set_datasource("list_status",$stype,"status_id","status_id|status",true);	   
	   }
	   else
	     echo 'status id in RCTRANSSQL_DPC not defined';
		 
	   $ret = $this->_grids[0]->set_grid_remote($grid1_get,$grid1_set,"$x","$y","livescrolling",17);//null,"false"); 							  
	
	   return ($ret);	   	
	}*/
	
	function show_grids() {
	   //gets
	   $cat = GetReq('cat');	
       $filter= GetParam('filter');
		   
       //$vd = $this->show_grid(550,540,null,$filter);		   
		   
       $vd .= $this->searchinbrowser();
	   
	   if ($this->hasgraph)
		   $vd .= $this->show_graph('transactions','Transactions',seturl('t=cptranssql'));
	   else
		   $vd .= "<h3>".localize('_GNAVAL',0)."</h3>";	   
	   
	   if ($this->hasgauge)
		   $vd .= $this->charts->show_gauge('income',400,300);
	   else
		   $vd .= "<h3>".localize('_GNAVAL',0)."</h3>";	   		   	   
	   		   		   		   	   
	   
	   //grid 0 
	   $datattr[] = $vd;							  
	   $viewattr[] = "left;50%";	   	   

	   //$grid0_get = "shhandler.php?t=shngettranssql";
	   //$grid0_set = "";	   
	
	   $exe =  seturl("t=cptsqlexe");
	   $del =  seturl("t=cptsqldel");	   		   
	   $run =  seturl("t=cptsqlrun");		   	   		   
	   $message = "<A href=\"$exe\">".$this->add_recs."</A>". $this->sep;
	   $message .= "<A href=\"$del\">".$this->rem_recs."</A>". $this->sep;
	   $message .= "<A href=\"$run\">".$this->run_recs."</A>";//. $this->sep;
	   
	   if ($backdays = $this->lastexecsql(1))	
	     $message .= "<br><h3>" . $backdays . localize('_BACKDAYS',getlocal()) . "</h3>";
	   $message .= "<br><hr>" . $this->res;		      		   	  

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
	   $ret = $myw->render("center::100%::0::group_article_selected::left::3::3::");
	   unset ($datattr);
	   unset ($viewattr);		   	
	   	
	   return ($ret);	
	}	

    function searchinbrowser() {
	        $act = seturl('t=cptranssql');
	
            $ret = "
           <form name=\"searchinbrowser\" method=\"post\" action=\"$act\">
           <input name=\"filter\" type=\"Text\" value=\"\" size=\"56\" maxlength=\"64\">
           <input name=\"Image\" type=\"Image\" src=\"../images/b_go.gif\" alt=\"\"    align=\"absmiddle\" width=\"22\" height=\"28\" hspace=\"10\" border=\"0\">
           </form>";

          $ret .= "<br>Last search: " . GetParam('filter')."<br>";

          return ($ret);
    }	
	
	//nitobi get	
	function get_transsql_list() {
       $db = GetGlobal('db');	
       $filter = GetReq('filter');	
	   
       $handler = new nhandler(17,'id','Desc');	   
       $handler->sortColumn = 'id';		
	   $handler->sortDirection= 'Desc';		   
	   
	   $whereClause='';
	   if (isset($_GET['select'])) {
	     if (isset($_GET['cid'])) {
		   $whereClause=" WHERE id=".$_GET["cid"]." ";
	     } 
	     else
	       $whereClause=" WHERE id=-1";//fetch nothing	   
	   }
	   elseif ($filter) {
             
           $whereClause = " where (status like '%$filter%' or sqlquery like '%$filter%' or sqlres like '%$filter%' or date like '%$filter%' or execdate like '%$filter%' or reference like '%$filter%')";
       }	
       else	   
	     $whereClause = null;	   
	
	   $sSQL .= "select id,fid,time,date,execdate,status,sqlquery,sqlres,reference from syncsql ";
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $handler->sortColumn . " " . $handler->sortDirection ." LIMIT ". $handler->ordinalStart .",". ($handler->pageSize) .";";
	   //echo $sSQL;	die();
	   
       $result = $db->Execute($sSQL,2);	
	   

	   $names = array('id','fid','time','date','execdate','status','sqlquery','sqlres','reference');	
   	   		 			 
	   $handler->handle_output($db,$result,$names,'id',null,$this->encoding);	
	}		
	
	//nitobi set
	function save_transsql_list() {
       $db = GetGlobal('db');		   	
	
	   $names = array('id','fid','time','date','execdate','status','sqlquery','sqlres','reference');
	   
       $handler = new nhandler(17,'id','Asc');	 	   
	   $sql2run = $handler->handle_input(null,'syncsql',$names,'id');		
	
       $db->Execute($sql2run,3,null,1);
	   
	   if (($handler->debug_sql) && ($f = fopen($this->path . "nitobi.sql",'w+'))) {
	     fwrite($f,$sql2run,strlen($sql2run));
		 fclose($f);
	   }	
	}
	
	function sidewin() {
	
	    if (defined('RCCONTROLPANEL_DPC')) {
	
	      if (!GetReq('t')) {//when first load of page
		    $ret = GetGlobal('controller')->calldpc_method("rccontrolpanel.show_directory_icons use 0");	  
	      }
		  $ret .= GetGlobal('controller')->calldpc_method("ajax.setajaxdiv use menu");
		  
		  GetGlobal('controller')->calldpc_method("rcsidewin.set_show use ".$ret);
		  //return ($ret);	 
		}
	}	

// Get date difference between two given dates
// $returntype: s = seconds, m = minutes, h = hours, d = days
// int date_diff(int start_date, int end_date[, string return_type])
function date_diff($start_date, $end_date, $returntype="d") {
   if ($returntype == "s")
       $calc = 1;
   if ($returntype == "m")
       $calc = 60;
   if ($returntype == "h")
       $calc = (60*60);
   if ($returntype == "d")
       $calc = (60*60*24);   
       
   $_d1 = explode(" ", $start_date);
   $_d11 = explode('-',$_d1[0]); //print_r($_d11);
   $_d12 = explode(':',$_d1[1]); //print_r($_d12);  
   $d1 = $_d11[0]; //echo 'A:',$d1;
   $m1 = $_d11[1]; //echo '-',$m1;
   $y1 = $_d11[2]; //echo '-',$y1;
   $h1 = $_d12[0]?$_d12[0]:0;
   $n1 = $_d12[1]?$_d12[1]:0;
   $s1 = $_d12[2]?$_d12[2]:0; 
   //echo $h1,':',$n1,':',$s1,'<br>'; 
   
   $_d2 = explode(" ", $end_date);
   $_d21 = explode('-',$_d2[0]);
   $_d22 = explode(':',$_d2[1]);   
   $d2 = $_d21[0]; //echo 'B:',$d2;
   $m2 = $_d21[1]; //echo '-',$m2;
   $y2 = $_d21[2]; //echo '-',$y2;
   $h2 = $_d22[0]?$_d22[0]:0;
   $n2 = $_d22[1]?$_d22[1]:0;
   $s2 = $_d22[2]?$_d22[2]:0;
   //echo $h2,':',$n2,':',$s2,'<br>'; 
   
  
   if (($y1 < 1970 || $y1 > 2037) || ($y2 < 1970 || $y2 > 2037)) {
       return 0;
   } 
   else {
       $start_date_stamp    = mktime($h1,$n1,$s1,$m1,$d1,$y1); 
//echo $start_date_stamp,'<br>';
       $end_date_stamp    = mktime($h2,$n2,$s2,$m2,$d2,$y2);
//echo $end_date_stamp,'<br>';
	   
	   
       $difference = round(($end_date_stamp-$start_date_stamp)/$calc);
	   echo 'DIFF-FUNC>',$difference;
		  
	   return $difference;  
   }
}	
			
};
}
?>