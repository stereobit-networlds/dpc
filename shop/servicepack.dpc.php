<?php
$__DPCSEC['SERVICEPACK_DPC']='1;1;1;1;1;1;1;1;1';

if (!defined("SERVICEPACK_DPC"))  {
define("SERVICEPACK_DPC",true);

$__DPC['SERVICEPACK_DPC'] = 'servicepack';

 
$__EVENTS['SERVICEPACK_DPC'][0]='cpsp';
$__EVENTS['SERVICEPACK_DPC'][1]='cpservicepack';

$__ACTIONS['SERVICEPACK_DPC'][0]='cpsp';
$__ACTIONS['SERVICEPACK_DPC'][1]='cpservicepack';

$__DPCATTR['SERVICEPACK_DPC']['cpsp'] = 'cpsp,1,0,0,0,0,0,0,0,0,0,0,1';

$__LOCALE['SERVICEPACK_DPC'][0]='SERVICEPACK_DPC;Service Pack;Service Pack';
$__LOCALE['SERVICEPACK_DPC'][1]='_GNAVAL;Chart not available!;Στατιστική μή διαθέσιμη!';
$__LOCALE['SERVICEPACK_DPC'][2]='_addrecs;Read/add records;Διαβασε/εισηγαγε εγγραφες';
$__LOCALE['SERVICEPACK_DPC'][3]='_remrecs;Remove unexecuted records;Διαγραφη μη εκτελεσμάνων εγγραφων!';
$__LOCALE['SERVICEPACK_DPC'][4]='_runrecs;Execute records!;Εκτελεση εγγραφων!';
$__LOCALE['SERVICEPACK_DPC'][5]='_BACKDAYS; days unsynchronized!; μη ενημερωμενες ημέρες!';

class servicepack {

    var $reset_db, $title;
	var $_grids, $charts;
	var $ajaxLink;
	var $hasgraph, $hasgauge;
    var $status_sid, $status_sidexp;
	
	var $cycle, $datestyle, $initstart;
	var $res;
	var $encoding;
	
	var $islocalfile;
		
	function servicepack() {
	  $GRX = GetGlobal('GRX');	
	
	  //override if exist
	  if ($tpath = paramload('SP','path'))
	    $this->path = paramload('SHELL','prpath') . $tpath;	
		
      $char_set  = arrayload('SHELL','char_set');	  
      $charset  = paramload('SHELL','charset');	  		
	  if (($charset=='utf-8') || ($charset=='utf8'))
	    $this->encoding = 'utf-8';
	  else  
	    $this->encoding = $char_set[getlocal()]; 			
	
	  $this->title = localize('SERVICEPACK_DPC',getlocal());		
	  $this->reset_db = false;
	  
      $this->address = remote_paramload('SP','url',$this->path);
      $this->syncfiles = remote_arrayload('SP','files',$this->path);		  
      $this->cycle = remote_arrayload('SP','cycle',$this->path); //seconds of next run		  
      $this->datestyle = remote_paramload('SP','datestyle',$this->path);	  
      $this->initstart = remote_paramload('SP','initstart',$this->path);
      $this->islocalfile = remote_paramload('SP','islocalfile',$this->path);	  
	  $this->trimwords[] = array("\r\n", "no rows selected", "rows selected");		 
	  $this->res = null; 
	  
	  
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
	     case 'cpsp'        :
		 default            : $this->res = $this->sp(GetReq('sp'));	
	   } 
			
    }   
	
    function action($action=null) {
	 
	  if (GetSessionParam('REMOTELOGIN')) 
	    $out = setNavigator(seturl("t=cpremotepanel","Remote Panel"),$this->title); 	 
	  else  
        $out = setNavigator(seturl("t=cp","Control Panel"),$this->title);	 	 
	  
	  switch ($action) {
	  							
	     case 'cpsp'          : 
		 default              :  
		                        $out .= 'Done '.$this->res;
	  }	 

	  return ($out);
    }
	
    function sp($sp) {

       if ($sp!=null) {
	   
		 set_time_limit(0);
		   
         $ret = $this->$sp(); 
		 
		 set_time_limit(30);			 
	   }
	   
	   return ($ret);  
    }	
	

    function encode_user_passwords() {
      $db = GetGlobal('db');	
      $today = date("Y-m-d h:m:s");	  
	  
	  $sSQL = "select username,password from users where id>3";
	  $result = $db->Execute($sSQL,2);
	  $i=0;
	  foreach ($result as $n=>$rec) {
	    $sSQL2 = "update users set password=" . "'" . md5($rec['password']) . "'," .
	           " vpass=" . "'" . md5($rec['password']) . "'" . 
			   " where username=" . "'" . $rec['username'] . "'";
        $db->Execute($sSQL2);
        /*if($db->Affected_Rows()) return (true);
	                        else return (false);*/
		//echo $sSQL2;					
		$i+=1;					
	  }						
      return $i; 
    }
};
}
?>