<?php

class network {

    function network() {
    }
	
	


/////////////////////// LOW LEVEL NET FUNCTIONS

function send($message,$ip,$port='19125') {

     if (ereg ("([0-9]{1,2,3}).([0-9]{1,2,3}).([0-9]{1,2,3}).([0-9]{1,2,3})", $ip, $regs)) {

       $fp = fsockopen("tcp://$ip", $port, $errno, $errstr, 30);
       if (!$fp) {
         echo "ERROR: $errno - $errstr\n";
       } else {
         fwrite($fp, "$message\n");
         $ret = fread($fp, 26);
         fclose($fp);
       }  
	   
	   return ($ret);      
	 }
	 else return ("Invalid IP!\n");  
} 

function stat() {
		$output = Array();
		exec( 'netstat -na', $output );
        
		$ret = implode("\n",$output);
	   
	    return ($ret);
}

function find() {
		$output = Array();
		exec( 'netstat -na', $output );
        
		$ret = implode("\n",$output);
	   
	    return ($ret);
}



function macaddress() {
		$output = Array();
		exec( 'netstat -r', $output );
		for( $a = 0, $b = &count( $output ); $a < $b; $a++ ) {
			if( preg_match( "/(?i)([a-z0-9]{2} ){6}/", $output[$a] ) == true ) {
				$macaddress = &$output[$a];
				$uniquekey = &md5( $macaddress );
				$output[$a] = &preg_replace( "/(?i)([^a-z0-9]*?)([a-z0-9]{2} ){6}/i", "\\1 {$uniquekey} ", $output[$a] );
				$output[$a] = &explode( " {$uniquekey} ", $output[$a] );
				$uniquekey = Array( trim( $output[$a][0] ), trim( $output[$a][1] ) );
				$macaddress = &str_replace( $uniquekey, "", $macaddress );
				return trim( $macaddress );
			}
		}
		return 'not found';
}

//////////////////////////   DNS  ///////////////////////////////////////
     var $dns_socket = NULL;
     var $QNAME = "";
     var $dns_packet= NULL;
     var $Total_Accounts = 0;
     var $cIx = 0;
     var $dns_repl_domain;
     var $arrMX = array();
	 
function dns($domain,$dns) {

        $this->QNAME($domain);
        $this->pack_dns_packet();
		$dns_socket = fsockopen("udp://$dns", 53);
        fwrite($dns_socket,$this->dns_packet,strlen($this->dns_packet));
        $this->dns_reply  = fread($dns_socket,1);
        $bytes = stream_get_meta_data($dns_socket);
        $this->dns_reply .= fread($dns_socket,$bytes['unread_bytes']);
        fclose($dns_socket);
		
		 $ret = $this->dns_reply;		
		
        $this->cIx=6;
        $this->Total_Accounts   = $this->gord(2);
        $this->cIx+=4;
        $this->parse_data($this->dns_repl_domain);
        $this->cIx+=7;

        for($ic=1;$ic<=$this->Total_Accounts;$ic++) {
          $QTYPE = ord($this->gdi($this->cIx));
          if($QTYPE!==15){ 
			  return("No MX Records returned\n"); 
		  }
          $this->cIx+=9;
          $mxPref = ord($this->gdi($this->cIx));
          $this->parse_data($curmx);
          $this->arrMX[] = array("MX_Pref" => $mxPref, "MX" => $curmx);
          $this->cIx+=3;
        }	
		
		$ret .= implode($this->arrMX);
		return ($ret);			
}

	/**
	 * Fucntion to set the value of QNAME
	 *
	 * @param $domain - string
	 */
     public function QNAME($domain) {
       $dot_pos = 0; $temp = "";
       while($dot_pos=strpos($domain,".")) {
         $temp   = substr($domain,0,$dot_pos);
         $domain = substr($domain,$dot_pos+1);
         $this->QNAME .= chr(strlen($temp)).$temp;
       }
       $this->QNAME .= chr(strlen($domain)).$domain.chr(0);
     }
	 
	 /**
	  * Fucntion to parse MX data
	  *
	  * @param $retval string
	  */
	 public function parse_data(&$retval) {
       $arName = array();
       $byte = ord($this->gdi($this->cIx));
       while($byte!==0) {
         if($byte==192) { //compressed   
           $tmpIx = $this->cIx;
           $this->cIx = ord($this->gdi($cIx));
           $tmpName = $retval;
           $this->parse_data($tmpName);
           $retval = $retval.".".$tmpName;
           $this->cIx = $tmpIx+1;
           return;
         }
         $retval="";
         $bCount = $byte;
         for($b=0;$b<$bCount;$b++) {
           $retval .= $this->gdi($this->cIx);
         }
         $arName[]=$retval;
        $byte = ord($this->gdi($this->cIx));
      }
      $retval=join(".",$arName);
	 }
	 
	/**
	 * Initialise the value of gdi
	 *
	 * @param $cIx - string
	 * @param $bytes - int
	 *
	 * @return string
	 */	 
     public function gdi(&$cIx,$bytes=1) {
      $this->cIx++;
      return(substr($this->dns_reply, $this->cIx-1, $bytes));
     }	 	 
	 
	/**
	 * fucntion to return the formated dns reply
	 *
	 * @param $in - int
	 */
     public function gord($ln=1) {
       $reply="";
       for($i=0;$i<$ln;$i++){
        $reply.=ord(substr($this->dns_reply,$this->cIx,1));
        $this->cIx++;
        }
       return $reply;
     }	 
	 
	/**
	 * Fucntion to return dns packet(s)
	 *
	 */
     public function pack_dns_packet() {
       $this->dns_packet =  chr(0).chr(1).
                       chr(1).chr(0).
                       chr(0).chr(1).
                       chr(0).chr(0).
                       chr(0).chr(0).
                       chr(0).chr(0).
                       $this->QNAME.
                           chr(0).chr(15).
                           chr(0).chr(1);
						   				   
     }	 
	 
	 
	 

//ie 192.168.0.*
//or even 192.168.*.1
function valid_ipv4($ip_addr) {

       $num="(\*|[0-9]{1,3}|^1?\d\d$|2[0-4]\d|25[0-5])";

       if(preg_match("/$num\.$num\.$num\.$num/",$ip_addr,$matches))
       {
           print_r ($matches);
               return $matches[0];
       } else {
               return false;
       }
}

}
?>