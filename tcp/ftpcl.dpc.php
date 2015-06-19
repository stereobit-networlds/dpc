<?php
$__DPCSEC['FTPCL_DPC']='2;1;1;1;1;1;1;1;2';

if ( (!defined("FTPCL_DPC")) && (seclevel('FTPCL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("FTPCL_DPC",true);

$__DPC['FTPCL_DPC'] = 'ftpcl';

$__EVENTS['FTPCL_DPC'][0]='ftp';
$__EVENTS['FTPCL_DPC'][1]='get';
$__EVENTS['FTPCL_DPC'][2]='post';
$__EVENTS['FTPCL_DPC'][3]='ftp_options';

$__ACTIONS['FTPCL_DPC'][0]='ftp';
$__ACTIONS['FTPCL_DPC'][1]='get';
$__ACTIONS['FTPCL_DPC'][2]='post';
$__ACTIONS['FTPCL_DPC'][3]='ftp_options';


class ftpcl {	

	var $ftp_server = "localhost";
	var $ftp_user = "ek";
	var $ftp_passwd = "secret";
   
    function ftpcl() {
	}

    function event($event=null) {
	
	  switch ($event) {
	    case 'ftp' : 
	                 break;
	  }
    }
  
    function action($action=null) {
	
	  switch ($action) {
	    case 'ftp'     : $out .= $this->direct_method(); 
 		                 $out .= $this->api_method(); 
		                 break;
	  } 
	
	  return ($out); 
    }
	
	function direct_method() {
	/* direct object methods */
	require_once "ftpcl.lib.php";
	$ftp =& new FTP();
	if ($ftp->connect($ftp_server)) {
		if ($ftp->login($ftp_user,$ftp_passwd)) {
			$ret .= "\n".$ftp->sysType() . "\n";
			$ret .= $ftp->pwd() . "\n";
			$ret .= date("r",$ftp->mdtm("7juli.txt.gz")) . "\n";
			$ret .= $ftp->size("7juli.txt.gz")."\n";
			$ret .= $ftp->raw("SYST")."\n";
			$ftp->mkdir("ftp_test");
			$ftp->chmod(777,"ftp_test");
			$ftp->rename("ftp_test","ftp__test");
			$ftp->rename("ftp__test","ftp_test");
			$ftp->site("CHMOD 777 ftp_test");
			$ftp->exec("touch ftp_file.txt");
			$ftp->delete("ftp_file.txt");
			$ftp->chdir("ftp_test");
			$ftp->cdup();
			print_r($ftp->nlist());
			$ret .= "\n";
			print_r($ftp->rawlist());
			$ret .= "\n";
			$ftp->get("Week.exe","Week.exe");
			$ftp->put("logo.gif","logo.gif");
			$ftp->delete("logo.gif");
			$ftp->rmdir("ftp_test");
		} else {
			$ret .= "login failed: ";
			print_r($ftp->error_no);
			print_r($ftp->error_msg);
		}
		$ftp->disconnect();
		print_r($ftp->lastLines);
	} else {
		$ret .= "connection failed: ";
		print_r($ftp->error_no);
		print_r($ftp->error_msg);
	}
	
	return ($ret);
	}
	
	/* api methods */
	function api_method() {
	require_once "ftpapi.lib.php";
	if ($ftp = ftp_connect($ftp_server)) {
		if (ftp_login($ftp,$ftp_user,$ftp_passwd)) {
			$ret .= "\n".ftp_systype($ftp) . "\n";
			$ret .= ftp_pwd($ftp) . "\n";
			$ret .= date("r",ftp_mdtm($ftp,"7juli.txt.gz")) . "\n";
			$ret .= ftp_size($ftp,"7juli.txt.gz")."\n";
			if (function_exists("ftp_raw")) $ret .= ftp_raw($ftp,"SYST")."\n"; //PHP 5 CVS only
			ftp_mkdir($ftp,"ftp_test");
			if (function_exists("ftp_chmod")) ftp_chmod($ftp,777,"ftp_test"); //PHP 5 CVS only
			ftp_rename($ftp,"ftp_test","ftp__test");
			ftp_rename($ftp,"ftp__test","ftp_test");
			ftp_site($ftp,"CHMOD 777 ftp_test"); 
			ftp_exec($ftp,"touch ftp_file.txt");
			ftp_delete($ftp,"ftp_file.txt");
			ftp_chdir($ftp,"ftp_test");
			ftp_cdup($ftp);
			print_r(ftp_nlist($ftp,""));
			$ret .= "\n";
			print_r(ftp_rawlist($ftp,""));
			$ret .= "\n";
			ftp_get($ftp,"Week.exe","Week.exe",FTP_BINARY);
			ftp_put($ftp,"logo.gif","logo.gif",FTP_BINARY);
			ftp_delete($ftp,"logo.gif");
			ftp_rmdir($ftp,"ftp_test");
		} else {
			$ret .= "login failed";
		}
		ftp_close($ftp);
	} else {
		$ret .= "connection failed";
	}
	
	return ($ret);
	}
};
}	
?>