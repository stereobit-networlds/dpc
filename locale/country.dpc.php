<?php

$__DPCSEC['COUNTRY_DPC']='1;1;1;1;1;1;1;1;1';

if ( (!defined("COUNTRY_DPC")) && (seclevel('COUNTRY_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("COUNTRY_DPC",true);

$__DPC['COUNTRY_DPC'] = 'ip2country';

$__EVENTS['COUNTRY_DPC'][0]='getcountry';
$__EVENTS['COUNTRY_DPC'][1]='txt2sql_ipcountry';
$__EVENTS['COUNTRY_DPC'][2]='auto_select_language';

$__ACTIONS['COUNTRY_DPC'][0]='getcountry';
$__ACTIONS['COUNTRY_DPC'][1]='txt2sql_ipcountry';
$__ACTIONS['COUNTRY_DPC'][2]='auto_select_language';


class ip2country {

    var $ret;
	var $rec_counter;
	var $datadir;
	var $source_file;
	
	var $country;
	var $webservice;

	function ip2country() {
	  $this->ret = "";
	  $this->country = "";	
	  $this->webservice = paramload('COUNTRY','webservice');  
	  
	  $this->rec_counter = 0;	
	  $this->datadir = paramload('SHELL','dbgpath');	  
	  $this->source_file = paramload('COUNTRY','ipfile');  
	}

    function event($evn=null) {
	   $param1 = GetGlobal('param1');
	
       switch ($evn) {		
          case "getcountry"          : $this->find_country($param1); break;					 	  
          case "txt2sql_ipcountry"   : $this->txt2sql_ipcountry(); break;		  
          case "auto_select_language": $this->auto_select_language(); break;		  
       }	
	}	

	function action($action=null) {
	
       $__USERAGENT = GetGlobal('__USERAGENT');
		
       switch ($action) {	
	    case 'auto_select_language' :
		    $out = null;
		break;			   
	    case 'txt2sql_ipcountry' :
		    $out = $this->rec_counter . ' records affected!';
		break;		
	    case 'getcountry' :
        switch ($__USERAGENT) {
	         case 'HTML' : $out = $this->country; break;
	         case 'GTK'  : $out = $this->country; break;
	         case 'TEXT' : $out = $this->country; break;
	         case 'CLI'  : $out = $this->country; break;			 
	         case 'WAP'  : $out = $this->country; break;			 
	    }
		break;
	   }	
			
	   return ($out);
	}
	
    //Here is a sample PHP function to convert a dotted IP Address to its corresponding IP Number:

    function IPAddress2IPNumber($dotted) {
        $dotted = preg_split( "/[.]+/", $dotted);
        $ip = (double) ($dotted[0] * 16777216) + ($dotted[1] * 65536) + ($dotted[2] * 256) + ($dotted[3]);
        return $ip;
    }

    //and the corresponding PHP function to convert IP Number to its corresponding dotted IP Address:

    function IPNumber2IPAddress($number) {
        $a = ($number / 16777216) % 256;
        $b = ($number / 65536) % 256;
        $c = ($number / 256) % 256;
        $d = ($number) % 256;
        $dotted = $a.".".$b.".".$c.".".$d;
        return $dotted;
    }
	
	function find_country($ip2find=null,$field2ret=null) {
	    $db = GetGlobal('controller')->calldpc_var('database.dbp');
        //echo $db;
        //echo $_SERVER['REMOTE_ADDR'];	
		if (!$field2ret) $field2ret = 'country_name'; //defaulr field
	
	    if ($ip2find)
          $ip = $this->IPAddress2IPNumber($ip2find);		
		else  
          $ip = $this->IPAddress2IPNumber($_SERVER['REMOTE_ADDR']);
		//echo $ip;
		//$ip = "3231973377"; //austria test
		$sSQL = "SELECT " . $field2ret . " FROM sysip WHERE " .
		        //$ip . ">= ip_from AND " . $ip . "<= ip_to";
				$ip . " BETWEEN ip_from AND ip_to";
		//$sSQL = "select country_name where ip_from=3275907072";		
				
		//echo $sSQL;		
		
        $ret = $db->Execute($sSQL,null,1);
		
		if ($ret) 
		  $this->country = $ret[0];//$ret->fields[0];
		else  
		  $this->country = 'Unknown country.';				
		
		return ($this->country);
	}	
	
	function find_country_as_webservice($ip2find=null) {

	   if ($ip2find)
         $ip = $this->IPAddress2IPNumber($ip2find);		
	   else  
         $ip = $this->IPAddress2IPNumber($_SERVER['REMOTE_ADDR']);	
	
       $url = "http://ip-to-country.com/get-country/?ip=" . $ip . "&user=guest&pass=guest";
       $fp = fsockopen ($url, 80, $errno, $errstr, 30);
       if (!$fp) {
          $ret .= "$errstr ($errno)<br>\n";
       } 
	   else {
          fputs ($fp, "GET / HTTP/1.0\r\nHost: " . $url. "\r\n\r\n");
          while (!feof($fp)) {
              $ret .= fgets ($fp,128);
          }
          fclose ($fp);
       }	
	   
	   return ($ret);
	}
	
	function txt2sql_ipcountry() {
	   $db = GetGlobal('db'); 
	   
       $ipfile = file ($this->datadir . $this->source_file);
	
       $this->rec_counter = 0;
	   
       if (is_array($ipfile)) {
	   
              //delete table if exist		
			  $sSQL = "drop table sysip";
              $db->Execute($sSQL);
              $sSQL = "create table sysip " .
			          "(" .
	                  "ip_id integer auto_increment primary key," .
	                  "ip_from double," .
	                  "ip_to double," .
	                  "country_code varchar(2)," .
	                  "country_code2 varchar(3)," .					  
                      "country_name varchar(50)" .			  
					  ")";			  
              $db->Execute($sSQL,1);			  
			  	   
              //while (list ($dline_num, $dline) = each ($aliasfile)) {
	          foreach ($ipfile as $dline_num => $dline) {	
                 $dsplit = explode (",", $dline);             

                 $sSQL = "insert into sysip (ip_from,ip_to,country_code,country_code2,country_name)" . 
				         " values (" .  
                         $dsplit[0] . "," . 
			             $dsplit[1] . "," . 
                         $dsplit[2] . "," .
                         $dsplit[3] . "," .						 
					     $dsplit[4] . ")";
                                  
                 $db->Execute($sSQL,1);
				 if ($db->Affected_Rows()) $this->rec_counter++;
              }
			  
			  setInfo($this->rec_counter . " Records affected");
       }	  
	   
	}	
	
	function auto_select_language() {
	
	   $ln = $this->load_cookie();
	   //echo '>>>>',$ln;
	   
	   if (!isset($ln)) {
         
		 if ($this->webservice) 
		   $remote_ip_country = $this->find_country_as_webservice($_SERVER['REMOTE_ADDR'],'country_code');
		 else 	
	       $remote_ip_country = $this->find_country($_SERVER['REMOTE_ADDR'],'country_code');
		   
	     //echo $remote_ip_country,'>>>>>>';
	     //2 LAN SELECTION
	     //if ($remote_ip_country=='GR') setlocal(1);
	       //                       else setlocal(0);
	     //MULTISELECTION
	     switch ($remote_ip_country) {
	       case 'EN' : $ln = 0; break;	   
	       case 'GR' : $ln = 1; break;
	       case 'DE' : $ln = 2; break;		 
		   default   : $ln = strval(paramload('SHELL','dlang'));
	     }
	   
	     $this->save_cookie($ln);
	   }
	   
	   setlocal($ln);	   
	}
	
	function save_cookie($value) {
	
	   if (paramload('SHELL','cookies')) {

		 $name = paramload('COUNTRY','cookie');
		 
	     if ($_COOKIE[$name]) {
		   echo "COOKIE saved";
		 } 
		 else {
		   //echo "NO COOKIE";		 
		   $exp = null;//$time() + paramload('COUNTRY','expire'); //null=end of session
		   //zero or not must be set else no cookie
           //if (isset($value)) 
		   setcookie($name, $value, $exp);
		 }
	   }
	}
	
	function load_cookie() {
	
	   if (paramload('SHELL','cookies')) {
	   
         $name = paramload('COUNTRY','cookie');
		 
	     if ($ret=$_COOKIE[$name]) {	   
		 
		    return ($ret);
		 }
	   }	
	   return (null);   
	}
};
}
?>