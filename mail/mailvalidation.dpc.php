<?php
/*
 * test_email_validation.html
 *
 * @(#) $Header: c:\php\webos\cvs/mail/mailvalidation.dpc.php,v 1.1.1.1 2006/05/02 09:07:18 administrator Exp $
 *
 */

$__DPCSEC['MAILVALIDATION_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("MAILVALIDATION_DPC")) && (seclevel('MAILVALIDATION_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("MAILVALIDATION_DPC",true);

$__DPC['MAILVALIDATION_DPC'] = 'mailvalidation';

$d = GetGlobal('controller')->require_dpc('mail/mailvalidation.lib.php');
require_once($d);

	/*
	 * If you are running under Windows or any other platform that does not
	 * have enabled the MX resolution function GetMXRR() , you need to
	 * include code that emulates that function so the class knows which
	 * SMTP server it should connect to verify if the specified address is
	 * valid.
	 */
     if (!function_exists("GetMXRR"))	{
		/*
		 * If possible specify in this array the address of at least on local
		 * DNS that may be queried from your network.
		 */
		$_NAMESERVERS=array();
		//include("getmxrr.php");
        $e = GetGlobal('controller')->require_dpc('tcp/getmxrr.lib.php');
        require_once($e);		
     }
	/*
	 * If GetMXRR function is available but it is not functional, you may
	 * use a replacement function.
	 */
	/*
	else
	{
		$_NAMESERVERS=array();
		if(count($_NAMESERVERS)==0)
			Unset($_NAMESERVERS);
		include("rrcompat.php");
		$validator->getmxrr="_getmxrr";
	}
	*/	 

class mailvalidation {

    var $validator;
    
	function mailvalidation() {
	
	  $this->validator = new email_validation_class;	
	  
	  /* how many seconds to wait before each attempt to connect to the
	     destination e-mail server */
	  $this->validator->timeout=10;	 
	
	  /* how many seconds to wait for data exchanged with the server.
	   set to a non zero value if the data timeout will be different
		 than the connection timeout. */
	  $this->validator->data_timeout=0;

	  /* user part of the e-mail address of the sending user
	   (info@phpclasses.org in this example) */
	  $this->validator->localuser="info";

	  /* domain part of the e-mail address of the sending user */
	  $this->validator->localhost="phpclasses.org";//paramload('SHELL','ip');//

	  /* Set to 1 if you want to output of the dialog with the
	    destination mail server */
	  $this->validator->debug=1;

	  /* Set to 1 if you want the debug output to be formatted to be
	  displayed properly in a HTML page. */
	  $this->validator->html_debug=1;


	  /* When it is not possible to resolve the e-mail address of
	   destination server (MX record) eventually because the domain is
	   invalid, this class tries to resolve the domain address (A
	   record). If it fails, usually the resolver library assumes that
	   could be because the specified domain is just the subdomain
	   part. So, it appends the local default domain and tries to
	   resolve the resulting domain. It may happen that the local DNS
	   has an * for the A record, so any sub-domain is resolved to some
	   local IP address. This  prevents the class from figuring if the
	   specified e-mail address domain is valid. To avoid this problem,
	   just specify in this variable the local address that the
	   resolver library would return with gethostbyname() function for
	   invalid global domains that would be confused with valid local
	   domains. Here it can be either the domain name or its IP address. */
	  $this->validator->exclude_address="";	 
	}
	
	function validate($mail) {
	
	  if (IsSet($mail)) $email=$mail;
		
	  if (IsSet($email)	&& strcmp($email,"")) {
	  
		if(($result = $this->validator->ValidateEmailBox($email))<0) {
			echo "<H2><CENTER>It was not possible to determine if <TT>$email</TT> is a valid deliverable e-mail box address.</CENTER></H2>\n";
			return ($false);
	    }		
		else {
			echo "<H2><CENTER><TT>$email</TT> is ".($result ? "" : "not ")."a valid deliverable e-mail box address.</CENTER></H2>\n";
			return ($result);
	    }		
	  }
	  
	  return false;
	}
};
}
?>
