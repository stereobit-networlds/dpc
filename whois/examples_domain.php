<?

include("lib/domain.class.php");#

// Initializing class
$domain=new domain("test.com");

// Printing out whois data
echo $domain->info()."<br>";

// Printing out whois data in HTML format
echo $domain->html_info()."<br><br>";

// Checking if domain is available
if($domain->is_available()){
    echo "Domain is available<br>";
}else{
    echo "Domain is not Available<br>";
}

// Printing out whois host of domain
echo "Whois Server: ".$domain->get_whois_server()."<br>";

// Printing out name of domain without tld
echo "Domain: ".$domain->get_domain()."<br>";

// Printing out tld name of domain
echo "Tld: ".$domain->get_tld()."<br>";

// Checking if domain name is valid
if($domain->is_valid()){
    echo "Domain name is valid!<br>";
}else{
    echo "Domain name isn't valid!<br>";
}

// Getting all suppoerted TLD's
$tlds=$domain->get_tlds();
for($i=0;$i<count($tlds);$i++){
	echo $tlds[$i]."<br>";
}


?>