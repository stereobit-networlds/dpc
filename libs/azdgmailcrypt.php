<?php
//
///////////////////////////////////////////////////////
// Small AzDGMailCrypt class (you may reset this comments)
// Questions: (AzDG Support) <support@azdg.com>
///////////
// Purposes:
// Crypt mails for keep the spam bots
///////////
// Example:
///////////////////////////////////////////////////////
// include('AzDGMailCrypt.class.inc.php');
//	$crypt = new MC();
//	echo $crypt->cr("someone@somewhere.com");
//	echo "<br>";
//	echo $crypt->cr("someone-else@somewhere-else.com");
//	echo "<br>";
//      echo $crypt->cr("nospam@somewhere.com");
///////////////////////////////////////////////////////

class MC{
	function cr($m)
    {
        for ($i=0;$i<strlen($m);$i++) {
        $mc .= "&#".ord(substr($m,$i)).";"; 
        }
		return "<a href=\"mailto:$mc\">".$mc."</a>";
    }    
}
?>