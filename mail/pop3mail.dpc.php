<?php
$__DPCSEC['POP3MAIL_DPC']='1;1;1;2;2;2;2;2;9';
$__DPCSEC['_DEBUGPOP3MAIL']='1;0;0;0;0;0;0;0;1';

if ((!defined("POP3MAIL_DPC")) && (seclevel('POP3MAIL_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("POP3MAIL_DPC",true);

$__DPC['POP3MAIL_DPC'] = 'pop3mail';

//require_once("pop3class.lib.php");
//GetGlobal('controller')->include_dpc('mail/pop3class.lib.php');
$d = GetGlobal('controller')->require_dpc('mail/pop3class.lib.php');
require_once($d);

class pop3mail {

    var $pop3;

    function pop3mail() {
	  $UserSecID = GetGlobal('UserSecID');  
			
      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);	
	  
	  $this->pop3=new pop3_class;
	  $this->pop3->hostname=paramload('POP3MAIL','pop3server');  /* POP 3 server host name              */
	  $this->pop3->port=110;                        /* POP 3 server host port              */
	  $this->user=paramload('POP3MAIL','user');     /* Authentication user name            */
	  $this->password=paramload('POP3MAIL','password');/* Authentication password             */
	  $this->pop3->realm=paramload('POP3MAIL','realm');/* Authentication realm or domain      */
	  $this->pop3->workstation="";                  /* Workstation for NTLM authentication */
	  $this->apop=0;                                /* Use APOP authentication             */
	  $this->pop3->authentication_mechanism="USER"; /* SASL authentication mechanism       */
	  $debugpcl = arrayload('POP3MAIL','debug');	  
	  $this->pop3->debug=$debugpcl[$this->userLevelID]; /* Output debug information            */
	  $this->pop3->html_debug=1;                    /* Debug information is in HTML        */	  
	
	}
	
    function pop3receive() {
	
	  if(($error=$pop3->Open())=="")
	  {
		echo "<PRE>Connected to the POP3 server &quot;".$this->pop3->hostname."&quot;.</PRE>\n";
		if(($error=$this->pop3->Login($user,$password,$apop))=="")
		{
			echo "<PRE>User &quot;$user&quot; logged in.</PRE>\n";
			if(($error=$this->pop3->Statistics($messages,$size))=="")
			{
				echo "<PRE>There are $messages messages in the mail box with a total of $size bytes.</PRE>\n";
				$result=$this->pop3->ListMessages("",0);
				if(GetType($result)=="array")
				{
					for(Reset($result),$message=0;$message<count($result);Next($result),$message++)
						echo "<PRE>Message ",Key($result)," - ",$result[Key($result)]," bytes.</PRE>\n";
					$result=$this->pop3->ListMessages("",1);
					if(GetType($result)=="array")
					{
						for(Reset($result),$message=0;$message<count($result);Next($result),$message++)
							echo "<PRE>Message ",Key($result),", Unique ID - \"",$result[Key($result)],"\"</PRE>\n";
						if($messages>0)
						{
							if(($error=$pop3->RetrieveMessage(1,$headers,$body,2))=="")
							{
								echo "<PRE>Message 1:\n---Message headers starts below---</PRE>\n";
								for($line=0;$line<count($headers);$line++)
									echo "<PRE>",HtmlSpecialChars($headers[$line]),"</PRE>\n";
								echo "<PRE>---Message headers ends above---\n---Message body starts below---</PRE>\n";
								for($line=0;$line<count($body);$line++)
									echo "<PRE>",HtmlSpecialChars($body[$line]),"</PRE>\n";
								echo "<PRE>---Message body ends above---</PRE>\n";
								if(($error=$this->pop3->DeleteMessage(1))=="")
								{
									echo "<PRE>Marked message 1 for deletion.</PRE>\n";
									if(($error=$this->pop3->ResetDeletedMessages())=="")
									{
										echo "<PRE>Resetted the list of messages to be deleted.</PRE>\n";
									}
								}
							}
						}
						if($error=="" && ($error=$this->pop3->Close())=="")
							echo "<PRE>Disconnected from the POP3 server &quot;".$this->pop3->hostname."&quot;.</PRE>\n";
						
					}
					else
						$error=$result;
				}
				else
					$error=$result;
			}
		}
	  }
	  if($error!="")
		echo "<H2>Error: ",HtmlSpecialChars($error),"</H2>";	
		
	  return ($result);	
	}

};
}
?>