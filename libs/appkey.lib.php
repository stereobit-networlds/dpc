<?php
if (defined("AZDGCRYPT_DPC")) {
$__DPCSEC['APPKEY_DPC']='1;1;1;1;1;1;2;2;9';

if (!defined("APPKEY_DPC")) {

define("APPKEY_DPC",true);

$__DPC['APPKEY_DPC'] = 'appkey';

//$a = GetGlobal('controller')->require_dpc('system/azdgcrypt.lib.php');
//require_once($a);

class appkey {

    function __construct() {
	}
	
	public function create_key($division=null, $param=null, $date=null, $time=null) {
	
		if (($division) && ($param) && ($date) && ($time)) {
		
			//standart encoding-decoding (may other methods preloaded)
			$crypt = new AzDGCrypt('1234567890abcdefdgklm#$%^&');
		
		    $d = $crypt->crypt($division);
			$p = $crypt->crypt($param);
			$m = $crypt->crypt($date);
			$t = $crypt->crypt($time);
			$key = $this->from_base256($d).'-'.
			       $this->from_base256($p).'-'.
				   $this->from_base256($m).'-'.
				   $this->from_base256($t);
			//return ($key);
			$keyenc = $this->sp_char($key, true);
			return ($keyenc);
		}
		
		return false;
	}	
   
	//check defined section and section expiration
	public function isdefined($dpc_section=null) {
	    if (!$dpc_section) return false;
		if (GetSessionParam($dpc_section)=='true')
		    return true; //session saved
		
		if (defined($dpc_section)) {
		
			//standart encoding-decoding (may other methods preloaded)
			$crypt = new AzDGCrypt('1234567890abcdefdgklm#$%^&');	
		    
			$section = str_replace('_DPC','',$dpc_section);
			$key = remote_paramload($section, 'expires', $this->path);
			if (!$key)//not installed key
				return false;//true;
			
			$keymap = explode('-',$this->sp_char($key));
			if ((empty($keymap)) || (count($keymap)!=4)) 
				return false;	

			$division = $crypt->decrypt($this->to_base256($keymap[0])); 
			//echo '<br>d:'.$division;
			$param = $crypt->decrypt($this->to_base256($keymap[1])); 
			//echo '<br>p:'.$param;
			$value = (($keymap[2]) && ($keymap[3])) ?
					  $crypt->decrypt($this->to_base256($keymap[2])).' '.$crypt->decrypt($this->to_base256($keymap[3])) :
				      null;
				  
			if ((strtoupper($division) == $section) && ($param == 'expires') && ($value)) { 

				$value_date = strtotime($value);
				$now = time();
				//echo $now,'-',$value,'-',$value_date;
				if ($now <= $value_date) { //date must be in the future
					//$ret = $timeleft ? ($value_date-$now) : $value;
					SetSessionParam($dpc_section,'true');//session save, faster
					return true;
				}
			}//valid key	
		}//defined
		return false;
	}	
	
	public function decode_key($key=null,$section=null,$timeleft=false) {
	    if ((!$key)||(!$section)) return false;
		
		//standart encoding-decoding (may other methods preloaded)
        $crypt = new AzDGCrypt('1234567890abcdefdgklm#$%^&');	
		

		$keymap = explode('-',$this->sp_char($key));
		if ((empty($keymap)) || (count($keymap)!=4)) 
			return false;	

		$division = $crypt->decrypt($this->to_base256($keymap[0])); 

		$param = $crypt->decrypt($this->to_base256($keymap[1])); 

		$value = (($keymap[2]) && ($keymap[3])) ?
		          $crypt->decrypt($this->to_base256($keymap[2])).' '.$crypt->decrypt($this->to_base256($keymap[3])) :
				  null;
				  
		if ((strtoupper($division) == $section) && 
		    ($param == 'expires') && ($value)) { 

			$value_date = strtotime($value);//_decoded);
			$now = time();
			//echo $now,'-',$value,'-',$value_date;
			if ($now < $value_date) { //date must be in the future
			    $ret = $timeleft ? ($value_date-$now) : $value;
				return ($ret);
			}
			
        }//valid key	
		
		return false;
	}	
	
	public function sp_char($data=null,$enc=null) {
	    if (!$data) return null;
		
		$ret = ($enc) ? str_replace(array('+','/','='),array('SSyN','SlSh','IIsOn'),$data) :	
		                str_replace(array('SSyN','SlSh','IIsOn'),array('+','/','='),$data);
				
        return ($ret);			
	}
	
	public function to_base256($number, $from_base = 10) {
        return ($number);//not active
		
		$binary_number = base_convert($number, $from_base, 2); 
		$final_string = ""; 
		$new_length = (ceil(strlen($binary_number)/8)*8); 
		$binary_number = str_pad($binary_number, $new_length, "0", STR_PAD_LEFT); 
		for($i=($new_length-8); $i>=0; $i-=8) { 
			$final_string = chr(base_convert(substr($binary_number, $i, 8), 2, 10)).$final_string; 
		} 
		return $final_string; 
	}	
	
    public function from_base256($string, $to_base = 10) { 
	    return ($string); //not active
		
		$number = ""; 
		for($i=0; $i<strlen($string); $i++) { 
			$number .= str_pad(base_convert(ord($string{$i}), 10, 2), 8, "0", STR_PAD_LEFT); 
		} 
		return base_convert($number, 2, $to_base); 
	}  

	//use on expirations....
	//$date = "2009-03-04 17:45";
	//$result = nicetime($date); // 2 days ago		
	public function nicetime($date, $tenseago=null, $tensefnow=null) {
		if(empty($date)) {
			return "No date provided";
		}
    
		$periods         = getlocal() ? array("δευτερόλεπτα", "λεπτά", "ώρα(ες)", "ημέρα(ες)", "εβδομάδα(ες)", "μήνας(ες)", "χρόνος(ια)", "δεκαετία(ες)"):
		                                array("second(s)", "minute(s)", "hour(s)", "day(s)", "week(s)", "month(s)", "year(s)", "decade(s)");
		$lengths         = array("60","60","24","7","4.35","12","10");
    
		$now             = time();
		$unix_date         = strtotime($date);
    
		// check validity of date
		if(empty($unix_date)) {    
			return "Bad date";
		}

		// is it future date or past date
		if($now > $unix_date) {    
			$difference     = $now - $unix_date;
			$tense         = $tenseago ? $tenseago : localize('_ago',getlocal());//"ago";
        
		} 
		else {
			$difference     = $unix_date - $now;
			$tense         = $tensefnow ? $tensefnow : localize('_fromnow',getlocal());//"from now";
		}
    
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
    
		$difference = round($difference);
    
	    //added inside parentheses month(s)
		/*if($difference != 1) {
			$periods[$j].= "s";
		}*/
    
		return "$difference $periods[$j] {$tense}";
	}		
};
}  
} else die('AZDGCRYPT DPC REQUIRED');
?>