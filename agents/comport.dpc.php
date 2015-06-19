<?php
$__DPCSEC['COMPORT_DPC']='1;1;1;1;1;1;1;1;2';

if (!defined("COMPORT_DPC")) {
define("COMPORT_DPC",true);

$__DPC['COMPORT_DPC'] = 'comport';

class comport {


   function comport($env = null) {
   
     $this->env = $env;   

	 $set_mode = "MODE COM1: BAUD=38400 PARITY=N DATA=8 STOP=1 TO=OFF XON=OFF ODSR=OFF OCTS=OFF DTR=OFF RTS=OFF IDSR=OFF";
     exec($set_mode, $output, $result);
   }
   
   public function readcom() {
   
      $com = fopen ("COM1:","wb+");
	  if (!$com) {
	    echo 'false';
	  }
	  else {
	  
	    $buffer = fgets($com,1024);	  
	  
        while (trim($buffer)!="") {
          echo "BUFFER=$buffer<br>";
          $res .= ";$buffer";
          $buffer = fgets($serial_port, 1024);
        }	  
	  
		echo $res;
		fclose($com);
	  }
   }
   
};	
}
?>