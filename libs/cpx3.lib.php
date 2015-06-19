<?
require_once('cpanel.lib.php');

class cpx3{

    var $cPanel, $domain;

    function __construct($domain=null,$cpuser=null,$cppass=null,$cpport=2082,$cptheme=null,$ssl=false){
	   
	    $cptheme = $cptheme ? $cptheme : 'x3';
		$cpport = $cpport ? $cpport : 2082;
		
		$this->domain = $domain;
		
        $this->cPanel = new cPanel($this->domain, $cpuser, $cpapass, $cpport, $cptheme, $ssl);	
	 
    }
	
	function _get_contact_mail() {
	
	   return $this->cPanel->getContactEmail();
	}   
		
	function _get_email_usedspace($email) {
	
	   return $this->cPanel->email()->getUsedSpace($email, $this->domain);
	}   
	
	function _get_email_quota($email) {
	
	   return $this->cPanel->email()->getQuota($email, $this->domain);
	}  	
	function _get_email_forwarders($email) {
		
      return (array) $this->cPanel->email()->getForwarders($email, $this->domain);	
    }

}
?>