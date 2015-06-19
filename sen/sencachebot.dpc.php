<?php
//PHP5
if (defined('SENPTREE_DPC')) {

$__DPCSEC['SENCACHEBOT_DPC']='2;1;1;1;1;1;1;2;9';

if ((!defined("SENCACHEBOT_DPC")) && (seclevel('SENCACHEBOT_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SENCACHEBOT_DPC",true);

$__DPC['SENCACHEBOT_DPC'] = 'sencachebot';

//GetGlobal('controller')->get_parent('HTTPCL_DPC','SENCACHEBOT_DPC');
GetGlobal('controller')->set_command('senbotstart',null,'SENCACHEBOT_DPC');
GetGlobal('controller')->set_command('senbotstop',null,'SENCACHEBOT_DPC');

class sencachebot extends senptree {


  private $meter = 0;
  private $lm = 7; //userlevel
   
  function __construct() {
  
    senptree::senptree();
	
  }
  
  public function event($event=null) {
  
    $param1 = GetGlobal('param1');  
    //httpcl::event($event);

	switch ($event) {	
	  case 'senbotstart': $this->botevent01($param1); break;
	}
  }
  
  public function action($action=null) { 
    //$out = httpcl::action($action);
	
	switch ($action) {
	  case 'senbotstart' : $out .= $this->botaction01(); break;
	} 
	
	return ($out); 
  }
  
 /* private function select_categories($cat=null) {
  
    //select categories
	$sSQL = 'select distinct CTGLEVEL2 from ' . $this->T_category;
	$sSQL .= " WHERE ";
    if (seclevel('SENPTREEADMIN_',$this->userLevelID)) {
	  $sSQL .= "CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ'";
	}
	else {
	  $sSQL .= "(CTGLEVEL1='ÊÁÔÇÃÏÑÉÅÓ ÅÉÄÙÍ' and CTGLEVEL2 not like 'LOST+FOUND')"; 
	}	
	$result = $this->sen_db->Execute($sSQL);
	
	//echo $sSQL;
    //print_r($result);	
	
	
	if ($result) { 

      while(!$result->EOF) {	
	
         $array[] = $result->fields[0];	  
		 $result->MoveNext();	  
	  }
	}
	return ($array);     
  }
  
  private function level_test() {
  
	if ($array=$this->select_categories()) { 
	
	  for ($level=0;$level<7;$level++) { 
	  
	     echo "LEVEL:",$level,"\n"; 
         reset($array);
		 foreach ($array as $id=>$f) {
		   
		   $this->read_sentree($f,"$level");
	     }
	  }	
    }  
	
	return ($array);
  } */ 


  private function level_00($maxlevel=7) {

	if ($array=$this->read_sentree(null,'0')) {
	
	  for ($level=0;$level<$maxlevel;$level++) { 
	  
	     echo "LEVEL:",$level,"\n"; 
         $this->read_sentree(null,$level);
	  }	
    }  
    //print_r($array);
	return ($array);
  }

  private function level_01($array,$maxlevel=7) {
  
	if (is_array($array)) { 
	
	  for ($level=0;$level<$maxlevel;$level++) { 
	  
	     echo "LEVEL:",$level,"\n"; 
         reset($array);
		 foreach ($array as $id=>$f) {
		   
		   $newarray[$f] = $this->read_sentree($f,"$level");
	     }
	  }	
    }  
	//print_r($newarray);
	return ($newarray);
  }
  
  private function level_02($array,$maxlevel=7) {
  
	if (is_array($array)) { 
	
		 foreach ($array as $family=>$subfamily) {
            if (is_array($subfamily)) {       
			foreach ($subfamily as $id=>$f) {
			
			  $group = $family."^".$id;
			  //echo $group,"\n";
			  for ($level=0;$level<$maxlevel;$level++) { 
    	        echo "LEVEL:",$level,"\n";
			    $newarray[$family][$id] = $this->read_sentree($group,"$level"); 
			  }	
			}
			}
		 }    
	}	 
	
	return ($newarray);
  }  
  
  
  private function level_03($array,$maxlevel=7) {
  
    $lm = $this->lm;
  
	if (is_array($array)) { 
		 
		 foreach ($array as $family=>$subfamily) {
		   if (is_array($subfamily)) {
		   foreach ($subfamily as $subf=>$omada) {            
		     if (is_array($omada)) {
			 foreach ($omada as $id=>$f) {
			 
			   $group = $family."^".$subf."^".$id;
			   //echo $group,"\n";
			   for ($level=0;$level<$maxlevel;$level++) { 
			   
	             echo "LEVEL:",$level,"\n";			   
			     $newarray[$family][$subf][$id] = $this->read_sentree($group,"$level"); 
			   
			     if (defined('SENPRODUCTS_DPC')) {
			       //$l = "$level";echo "++++",$level,"+++++";
			       GetGlobal('controller')->calldpc_method("senproducts.read_products use $group+$level");
			     }
			   }	 			   
			 }
			 }
		   }	
		   }
		 }	    
	}	 
	
	return ($newarray);  
  }
  
  private function level_04($array,$maxlevel=7) {
  
	if (is_array($array)) { 
		 
		 foreach ($array as $family=>$subfamily) {
		   if (is_array($subfamily)) {
		   foreach ($subfamily as $subf=>$omada) {            
		     if (is_array($omada)) {
			 foreach ($omada as $omd=>$kategory) {
		       if (is_array($kategory)) {
			   foreach ($kategory as $id=>$f) {			 
			   
				 $group = $family."^".$subf."^".$omd."^".$id;
				 //echo $group,"\n";
				 
				 for ($level=0;$level<$maxlevel;$level++) { 
				 
	               echo "LEVEL:",$level,"\n";				 
			       $newarray[$family][$subf][$omd][$id] = $this->read_sentree($group,"$level"); 
				 
			       if (defined('SENPRODUCTS_DPC')) {
				 
			         GetGlobal('controller')->calldpc_method("senproducts.read_products use $group+$level");				 
			       }				 
				 }  
			   }
			   }	 
			 }
			 }
		   }	
		   }
		 }    
	}	 
	
	return ($newarray);  
  } 
  
  private function make_super($action,$maxlevel=7) {
  
	if ($array=$this->read_sentree(null,'0')) {
	
	  foreach ($array as $id=>$family) {
	    for ($level=0;$level<$maxlevel;$level++) { 
	  
	      echo "LEVEL:",$level,$family,"\n";
		  SetReq('g',$family);
		  $this->make_supercache2($action,$level); 
	    }	 
      }
	}  
  }
  
  private function make_supercache2($action,$level) {

    if (defined('FRONTPAGE_DPC')) {  
      $controller = GetGlobal('controller');  
	  
	  if (defined('SUPERCACHE_DPC')) {    			   
	   
	     $supercache = & new supercache($action,$level,'HTML');
	     //simulate dispatcher
         $controller->event($action);
         $contents = $controller->action($action);
		 //supercached 
         $supercache->getcache_method_use_pointers('frontpage.render',
		                                           array(0=>&$contents),1);
		 unset($supercache);										   
	  }  
    }	  
  }	  
  
  private function make_supercache($action=null,$contents=null,$maxlevel=7) {

    if (defined('FRONTPAGE_DPC')) {  
     $controller = GetGlobal('controller');  
	
     if ($action) { 
	
	  for ($level=0;$level<$maxlevel;$level++) { 
	  
	   if (defined('SUPERCACHE_DPC')) {    			   
	   
	   $supercache = & new supercache($action,$level,'HTML');
	
	   if (!$contents) { 
	     //simulate dispatcher
         $controller->event($action);
         $contents = $controller->action($action);
		 //supercached 
         $supercache->getcache_method_use_pointers('frontpage.render',
		                                           array(0=>&$contents),1);									   
	   }	 
	   else { //supercached directly	   
         $supercache->getcache_method_use_pointers('frontpage.render',
		                                           array(0=>&$contents),1);	   					   
	   }
	   unset($supercache);
	   }
	   else 
	     echo "WARNING: supercache dpc not exist...\nSupercache failed!\n";
	  }  
	 }
     else { //serial dispatch (under construction)
      $actions = GetGlobal('__ACTIONS');
	
	  //echo "<PRE>";
	  //print_r($actions);
	  //echo "</PRE>";
	
	  foreach ($actions as $dpc=>$actionsarray) {
	    foreach ($actionsarray as $id=>$action) {
	
	      if ($controller->get_attribute($dpc,$action,7)) {//if supercached
	   
	        echo $action,"\n";
	      }
	    }	
	  }
	 }
	}
    else 
	  echo "WARNING: frontpage dpc not exist...\nSupercache failed!\n";	
  }
  
  
  private function botevent01($levm=null) {
  
    (isset($levm) ? $lm = ($levm+1) : $lm = ($this->lm+1));
	
	//$this->make_supercache('senvp',null,$lm);
	//$this->make_super('senvp',$lm);

    $level1 = $this->level_00($lm);
	if (is_array($level1))
	  $level2 = $this->level_01($level1,$lm); 	
	if (is_array($level2))  
	  $level3 = $this->level_02($level2,$lm);  
	if (is_array($level3))  
	  $level4 = $this->level_03($level3,$lm);	
	if (is_array($level4))  
	  $level5 = $this->level_04($level4,$lm);
  }
  
  private function botaction01() {
	
	$__USERAGENT = GetGlobal('__USERAGENT');
		
    switch ($__USERAGENT) {	
	
	  case 'HTML'  : 
	  case 'TEXT'  :
	  case 'CLI'   :
	  default      :  $out = "ending......\n";  
	}
	
	return ($out);
  }  
  
  function __destruct() {
  }
};
}
} else die("SENPTREE DPC REQUIRED! (" . __FILE__ . ")");
?>