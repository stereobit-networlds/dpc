<?php
$__DPCSEC['SELIDES_DPC']='1;1;1;1;1;1;1;1;2';

if (!defined("SELIDES_DPC")) {//&& (seclevel('TEST_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SELIDES_DPC",true);

$__DPC['SELIDES_DPC'] = 'selides';

class selides {


	function selides($env=null) {
	
	  $this->resources = new resources($env);//'localhost','19123');
	}
	
    function selidopoihsh($maxp) {

      $myarray = array();
  
      for ($i=1;$i<=$maxp;$i++)
        $myarray[] = $i;
	
      //print_r($myarray);
  
      //foreach ($myarray as $id=>$val) {
      $slide = 0;
      for ($j=1;$j<=$maxp;$j++) {
  
       if ($slide<=0) {
	     $array2ret[] = array_pop($myarray);
	     $slide+=1;
	   }
	   else {
	     $array2ret[] = array_shift($myarray);
	     if ($slide==2)
	       $slide=-1; 
	     else
	       $slide+=1;	
	   } 
     }
  
     //print_r($myarray);  
     //print_r($array2ret);
  
     $ret = implode(",",$array2ret);
	 
     //$na = MessageBox($ret,"Answer");  
	 
     printer_write($this->resources->get_resource('printer'),$ret."\n\r"); 
    
  
     return ($ret);	
  } 
	
};	
}
?>