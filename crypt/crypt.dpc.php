<?php

if (!defined("CRYPT_DPC")) {
define("CRYPT_DPC",true);

$__DPC['CRYPT_DPC'] = 'crypt';

//$cm = arrayload('CRYPT','cryptcycle'); print_r($cm);
(($cm = arrayload('CRYPT','cryptcycle'))
  ?
  //require_once($cm[rand(0,count($cm)-1)].'.lib.php')
  //GetGlobal('controller')->include_dpc('crypt/'.$cm[rand(0,count($cm)-1)].'.lib.php')
  require_once(GetGlobal('controller')->require_dpc('crypt/'.$cm[rand(0,count($cm)-1)].'.lib.php'))
  :
  //require_once(paramload('CRYPT','method').'.lib.php')
  //GetGlobal('controller')->include_dpc('crypt/'.paramload('CRYPT','method').'.lib.php')
  require_once(GetGlobal('controller')->require_dpc('crypt/'.paramload('CRYPT','method').'.lib.php'))
);  
//echo ">>>",$cm[rand(0,count($cm)-1)].'.lib.php';//paramload('CRYPT','method');
//echo "\n>>>",paramload('CRYPT','method');

class crypt {

   var $cryptmethod;
   var $type;
   var $base64;
   
   var $key;
   var $ccycle;
   
   function crypt($method=null,$type=null,$base64=null,$key=null){
	  
	  if (defined("AZDGCRYPT_LIB")) $this->cryptmethod = 'azdgcrypt';
	    elseif (defined("CIPHERSABER_LIB")) $this->cryptmethod = 'ciphersaber';
		  else $this->cryptmethod = $method;
		  
	  //echo "+++",$this->cryptmethod;	  
		  
	  $this->type = paramload('CRYPT','type');
	  //if no param..
	  $this->type = ($this->type ? $this->type : $type); 	  
	  
	  $this->base64 = paramload('CRYPT','base64');
	  //if no param..
	  $this->base64 = ($this->base64 ? $this->base64 : $base64);
	  
	  //PROBLEM if key generated!!!!!!it must be stored for session
  	  $this->ckey = '1234567890abcdefdgklm#$%^&';//($key ? $key : $this->genkey());
	  //SOLUTION...
	  /*$this->ckey = GetSessionParam('cyeks');
	  if (!isset($this->ckey)) {
	    $this->ckey = ($key ? $key : $this->genkey());
	    SetPreSessionParam("cyeks", $this->ckey);	
	  }	*/
	  //PROBLEM WITH CIPHERSABER key

   }
   
   function encode($var) {
	
	 if ($var) {
	 
	   switch ($this->cryptmethod) {
	      case 'azdgcrypt'   : 
		                       $cp = new AzDGCrypto($this->ckey);
	                           $cvar = $cp->encrypt($var);
	                           unset($cp);
		                       break;
	      case 'ciphersaber' : 
		                       $cs = new cipherSaber($this->type);
	                           $cvar = ($this->base64 ?
							            $cs->encrypt($var,$this->ckey)
										:
										$cs->binCrypt($var,$this->ckey)
										);
	                           unset($cp);		                       
							   break;
	  	  default            : $cvar = $var;//$this->encrypt($var); 	 
	   }
     }
	
	 return ($cvar);
   }

   function decode($var) {

	 if ($var) {
	 
	   switch ($this->cryptmethod) {
	      case 'azdgcrypt'   : 
		                       $cp = new AzDGCrypto($this->ckey);
	                           $cvar = $cp->decrypt($var);
	                           unset($cp);
							   break;
	      case 'ciphersaber' : 
		                       $cs = new cipherSaber($this->type);
	                           $cvar = ($this->base64 ?
							            $cs->decrypt($var,$this->ckey)
										:
										$cs->binDecrypt($var,$this->ckey)
										);
	                           unset($cs);
							   break;
		  default            : $cvar = $var;//$this->decrypt($var);	 
	   }	  
	 }
	
	 return ($cvar);
   } 
   
   
   //generate key of 10 bytes
   function genkey() {
   
     srand((double)microtime()*1234567); 
     $key = substr(md5(rand(0,32000)),0,10);   
	 
	 return ($key);
   }
   
   //basic crypt
   function encrypt($var) {
   
	  if (is_number($var)) {
	  	//$res = (int)$var + 100;
        return ($var);
	  }
	  else {
        //print $var . "        ". ascii2ext($var,30) ."\n";
        return ascii2ext($var,30); 
	  }   
   } 
   //basic decrypt
   function decrypt($var) {
 
	  if (is_number($var)) {
		//$res = (int)$var - 100;
		return ($var);
	  }
	  else { 
		//print $var . "        ". ext2ascii($var,30) ."\n";
        return ext2ascii($var,30);
	  }   
   }
   
   function free() {
   }

};
}
?>