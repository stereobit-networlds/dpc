<?php
if (!defined("CLIENT_DPC")) {
define("CLIENT_DPC",true);



class client {
   var $http_accept;
   var $http_user_agent;
   var $USER_AGENT,$NET_CLR,$OS,$Browser;

   function client($client='') {
      //global $HTTP_ACCEPT,$HTTP_USER_AGENT;

      $this->http_accept     = $_SERVER["HTTP_ACCEPT"];//$HTTP_ACCEPT;

      if ($client)
		 $this->http_user_agent = $client;
	  else
		 $this->http_user_agent = $_SERVER["HTTP_USER_AGENT"];//$HTTP_USER_AGENT;
		 
	  //echo $this->http_accept;
	  $this->USER_AGENT = $_SERVER["HTTP_USER_AGENT"]; //used by foreign methods	
	  $this->NET_CLR = false;
	  $this->OS = null;
	  $this->Browser = null; 
   }

   function action() {
   
      /*$cl = $this->getClient();

      $shell  = new shell($cl);
      $shell->render();
	  unset ($shell);*/ //EXECUTED AT STARTUP AND CLIENT AT SHELL!!!!
   }

   function event() {
   }

   function getClient() {
	  
	  $__USERAGENT = GetGlobal('__USERAGENT');
	  $argc = GetGlobal('argc');//command line (manual) arg client selection
	  $c = GetGlobal('c');	    //client on uri for self-test purposes
	 
      if (isset($argc)) $argclient = scanargs('-cl'); 
	  elseif ($c) $argclient = $c;
	  
      if (is_integer(strpos($this->http_user_agent,"wap"))) //self test
         $cl='WAP';
      elseif (is_integer(strpos($this->http_user_agent,"text"))) //self test
         $cl='TEXT';
      elseif (is_integer(strpos($this->http_user_agent,"cli"))) //self test
         $cl='CLI';	
      elseif (is_integer(strpos($this->http_user_agent,"soap"))) //self test
         $cl='SOAP';			 
      elseif (is_integer(strpos($this->http_user_agent,"gtk/xul"))) //self test
         $cl='XUL';		 	 		 
      elseif (is_integer(strpos($this->http_user_agent,"Elaine"))) //PALMTOPS CLIENT
         $cl='PDA';		 
	  elseif (is_integer(strpos($this->http_user_agent, "DoCoMo"))) //IMODE CLIENT
         $cl = 'IMODE';
      elseif (is_integer(strpos($this->http_accept, "text/vnd.wap.wml"))) //WAP CLIENT
         $cl = 'WAP';
      elseif (is_integer(strpos($this->http_accept, "text/x-hdml"))) //HANDHELD CLIENT
         $cl = 'HDML'; 
      /*elseif (is_integer(strpos($this->http_accept, "text/xml"))) //XML CLIENT is all html browsers
         $cl = 'XML';		*/ 
	  elseif (is_integer(strpos($this->http_user_agent, "Mozilla"))) //MOZILLA CLIENT
         $cl = 'HTML';		 
      elseif (isset($argclient))  //manual or self-test client	   
	     $cl = $argclient;	   
      else
         $cl='HTML';	//default
	  	 
	  //$__USERAGENT = $cl;
	  SetGlobal('__USERAGENT',$cl);

	  //echo $cl; 
      return $cl; 
   }
   
   function isdotnet() {
     
	 $this->_GetNET_CLR();
	 
	 return ($this->NET_CLR);
   }
   
   function getOS() {
   
     $this->_GetOperatingSysyem();
	 
	 return ($this->OS);
   }
   
   function getBrowser() {
   
     $this->_GetBrowser();
	 
	 return ($this->Browser);
   }   
   
		// PROTECTED - _GetNET_CLR()
		function _GetNET_CLR()
		{
			if (eregi("NET CLR",$this->USER_AGENT)) {$this->NET_CLR = true;}
		}
		
		
		// PROTECTED - _GetOperatingSystem()
		function _GetOperatingSystem()
		{
			if (eregi("win",$this->USER_AGENT))
			{
				$this->OS = "Windows";
				if ((eregi("Windows 95",$this->USER_AGENT)) || (eregi("Win95",$this->USER_AGENT))) {$this->OS_Version = "95";}
				elseif (eregi("Windows ME",$this->USER_AGENT) || (eregi("Win 9x 4.90",$this->USER_AGENT))) {$this->OS_Version = "ME";}
				elseif ((eregi("Windows 98",$this->USER_AGENT)) || (eregi("Win98",$this->USER_AGENT))) {$this->OS_Version = "98";}
				elseif ((eregi("Windows NT 5.0",$this->USER_AGENT)) || (eregi("WinNT5.0",$this->USER_AGENT)) || (eregi("Windows 2000",$this->USER_AGENT)) || (eregi("Win2000",$this->USER_AGENT))) {$this->OS_Version = "2000";}
				elseif ((eregi("Windows NT 5.1",$this->USER_AGENT)) || (eregi("WinNT5.1",$this->USER_AGENT)) || (eregi("Windows XP",$this->USER_AGENT))) {$this->OS_Version = "XP";}
				elseif ((eregi("Windows NT 5.2",$this->USER_AGENT)) || (eregi("WinNT5.2",$this->USER_AGENT))) {$this->OS_Version = ".NET 2003";}
				elseif ((eregi("Windows NT 6.0",$this->USER_AGENT)) || (eregi("WinNT6.0",$this->USER_AGENT))) {$this->OS_Version = "Codename: Longhorn";}
				elseif (eregi("Windows CE",$this->USER_AGENT)) {$this->OS_Version = "CE";}
				elseif (eregi("Win3.11",$this->USER_AGENT)) {$this->OS_Version = "3.11";}
				elseif (eregi("Win3.1",$this->USER_AGENT)) {$this->OS_Version = "3.1";}
				elseif ((eregi("Windows NT",$this->USER_AGENT)) || (eregi("WinNT",$this->USER_AGENT))) {$this->OS_Version = "NT";}
			}
			elseif (eregi("lindows",$this->USER_AGENT))
			{
				$this->OS = "LindowsOS";
			}
			elseif (eregi("mac",$this->USER_AGENT))
			{
				$this->OS = "MacIntosh";
				if ((eregi("Mac OS X",$this->USER_AGENT)) || (eregi("Mac 10",$this->USER_AGENT))) {$this->OS_Version = "OS X";}
				elseif ((eregi("PowerPC",$this->USER_AGENT)) || (eregi("PPC",$this->USER_AGENT))) {$this->OS_Version = "PPC";}
				elseif ((eregi("68000",$this->USER_AGENT)) || (eregi("68k",$this->USER_AGENT))) {$this->OS_Version = "68K";}
			}
			elseif (eregi("linux",$this->USER_AGENT))
			{
				$this->OS = "Linux";
				if (eregi("i686",$this->USER_AGENT)) {$this->OS_Version = "i686";}
				elseif (eregi("i586",$this->USER_AGENT)) {$this->OS_Version = "i586";}
				elseif (eregi("i486",$this->USER_AGENT)) {$this->OS_Version = "i486";}
				elseif (eregi("i386",$this->USER_AGENT)) {$this->OS_Version = "i386";}
			}
			elseif (eregi("sunos",$this->USER_AGENT))
			{
				$this->OS = "SunOS";
			}
			elseif (eregi("hp-ux",$this->USER_AGENT))
			{
				$this->OS = "HP-UX";
			}
			elseif (eregi("osf1",$this->USER_AGENT))
			{
				$this->OS = "OSF1";
			}
			elseif (eregi("freebsd",$this->USER_AGENT))
			{
				$this->OS = "FreeBSD";
				if (eregi("i686",$this->USER_AGENT)) {$this->OS_Version = "i686";}
				elseif (eregi("i586",$this->USER_AGENT)) {$this->OS_Version = "i586";}
				elseif (eregi("i486",$this->USER_AGENT)) {$this->OS_Version = "i486";}
				elseif (eregi("i386",$this->USER_AGENT)) {$this->OS_Version = "i386";}
			}
			elseif (eregi("netbsd",$this->USER_AGENT))
			{
				$this->OS = "NetBSD";
				if (eregi("i686",$this->USER_AGENT)) {$this->OS_Version = "i686";}
				elseif (eregi("i586",$this->USER_AGENT)) {$this->OS_Version = "i586";}
				elseif (eregi("i486",$this->USER_AGENT)) {$this->OS_Version = "i486";}
				elseif (eregi("i386",$this->USER_AGENT)) {$this->OS_Version = "i386";}
			}
			elseif (eregi("irix",$this->USER_AGENT))
			{
				$this->OS = "IRIX";
			}
			elseif (eregi("os/2",$this->USER_AGENT))
			{
				$this->OS = "OS/2";
				if (eregi("Warp 4.5",$this->USER_AGENT)) {$this->OS_Version = "Warp 4.5";}
				elseif (eregi("Warp 4",$this->USER_AGENT)) {$this->OS_Version = "Warp 4";}
			}
			elseif (eregi("amiga",$this->USER_AGENT))
			{
				$this->OS = "Amiga";
			}
			elseif (eregi("liberate",$this->USER_AGENT))
			{
				$this->OS = "Liberate";
			}
			elseif (eregi("qnx",$this->USER_AGENT))
			{
				$this->OS = "QNX";
				if (eregi("photon",$this->USER_AGENT)) {$this->OS_Version = "Photon";}
			}
			elseif (eregi("dreamcast",$this->USER_AGENT))
			{
				$this->OS = "Sega Dreamcast";
			}
			elseif (eregi("palm",$this->USER_AGENT))
			{
				$this->OS = "Palm";
			}
			elseif (eregi("powertv",$this->USER_AGENT))
			{
				$this->OS = "PowerTV";
			}
			elseif (eregi("prodigy",$this->USER_AGENT))
			{
				$this->OS = "Prodigy";
			}
			elseif (eregi("symbian",$this->USER_AGENT))
			{
				$this->OS = "Symbian";
			}
			elseif (eregi("unix",$this->USER_AGENT))
			{
				$this->OS = "Unix";
			}
			elseif (eregi("webtv",$this->USER_AGENT))
			{
				$this->OS = "WebTV";
			}
		}
		
		
		// PROTECTED - _GetBrowser()
		function _GetBrowser()
		{
			// boti
			if (eregi("msnbot",$this->USER_AGENT))
			{
				$this->Browser = "MSN Bot";
				if (eregi("msnbot/0.11",$this->USER_AGENT)) {$this->Browser_Version = "0.11";}
			}
			elseif (eregi("almaden",$this->USER_AGENT))
			{
				$this->Browser = "IBM Almaden Crawler";
			}
			elseif (eregi("ia_archiver",$this->USER_AGENT))
			{
				$this->Browser = "Alexa";
			}
			elseif ((eregi("googlebot",$this->USER_AGENT)) || (eregi("google",$this->USER_AGENT)))
			{
				$this->Browser = "Google Bot";
				if ((eregi("googlebot/2.1",$this->USER_AGENT)) || (eregi("google/2.1",$this->USER_AGENT))) {$this->Browser_Version = "2.1";}
			}
			elseif (eregi("surveybot",$this->USER_AGENT))
			{
				$this->Browser = "Survey Bot";
				if (eregi("surveybot/2.3",$this->USER_AGENT)) {$this->Browser_Version = "2.3";}
			}
			elseif (eregi("zyborg",$this->USER_AGENT))
			{
				$this->Browser = "ZyBorg";
				if (eregi("zyborg/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
			}
			elseif (eregi("w3c-checklink",$this->USER_AGENT))
			{
				$this->Browser = "W3C Checklink";
				if (eregi("checklink/3.6",$this->USER_AGENT)) {$this->Browser_Version = "3.6";}
			}
			elseif (eregi("linkwalker",$this->USER_AGENT))
			{
				$this->Browser = "LinkWalker";
			}
			elseif (eregi("fast-webcrawler",$this->USER_AGENT))
			{
				$this->Browser = "Fast WebCrawler";
				if (eregi("webcrawler/3.8",$this->USER_AGENT)) {$this->Browser_Version = "3.8";}
			}
			elseif ((eregi("yahoo",$this->USER_AGENT)) && (eregi("slurp",$this->USER_AGENT)))
			{
				$this->Browser = "Yahoo! Slurp";
			}
			elseif (eregi("naverbot",$this->USER_AGENT))
			{
				$this->Browser = "NaverBot";
				if (eregi("dloader/1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
			}
			// prehliadace
			elseif (eregi("amaya",$this->USER_AGENT))
			{
				$this->Browser = "amaya";
				if (eregi("amaya/5.0",$this->USER_AGENT)) {$this->Browser_Version = "5.0";}
				elseif (eregi("amaya/5.1",$this->USER_AGENT)) {$this->Browser_Version = "5.1";}
				elseif (eregi("amaya/5.2",$this->USER_AGENT)) {$this->Browser_Version = "5.2";}
				elseif (eregi("amaya/5.3",$this->USER_AGENT)) {$this->Browser_Version = "5.3";}
				elseif (eregi("amaya/6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
				elseif (eregi("amaya/6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
				elseif (eregi("amaya/6.2",$this->USER_AGENT)) {$this->Browser_Version = "6.2";}
				elseif (eregi("amaya/6.3",$this->USER_AGENT)) {$this->Browser_Version = "6.3";}
				elseif (eregi("amaya/6.4",$this->USER_AGENT)) {$this->Browser_Version = "6.4";}
				elseif (eregi("amaya/7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
				elseif (eregi("amaya/7.1",$this->USER_AGENT)) {$this->Browser_Version = "7.1";}
				elseif (eregi("amaya/7.2",$this->USER_AGENT)) {$this->Browser_Version = "7.2";}
				elseif (eregi("amaya/8.0",$this->USER_AGENT)) {$this->Browser_Version = "8.0";}
			}
			elseif ((eregi("aol",$this->USER_AGENT)) && !(eregi("msie",$this->USER_AGENT)))
			{
				$this->Browser = "AOL";
				if ((eregi("aol 7.0",$this->USER_AGENT)) || (eregi("aol/7.0",$this->USER_AGENT))) {$this->Browser_Version = "7.0";}
			}
			elseif ((eregi("aweb",$this->USER_AGENT)) || (eregi("amigavoyager",$this->USER_AGENT)))
			{
				$this->Browser = "AWeb";
				if (eregi("voyager/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
				elseif (eregi("voyager/2.95",$this->USER_AGENT)) {$this->Browser_Version = "2.95";}
				elseif ((eregi("voyager/3",$this->USER_AGENT)) || (eregi("aweb/3.0",$this->USER_AGENT))) {$this->Browser_Version = "3.0";}
				elseif (eregi("aweb/3.1",$this->USER_AGENT)) {$this->Browser_Version = "3.1";}
				elseif (eregi("aweb/3.2",$this->USER_AGENT)) {$this->Browser_Version = "3.2";}
				elseif (eregi("aweb/3.3",$this->USER_AGENT)) {$this->Browser_Version = "3.3";}
				elseif (eregi("aweb/3.4",$this->USER_AGENT)) {$this->Browser_Version = "3.4";}
				elseif (eregi("aweb/3.9",$this->USER_AGENT)) {$this->Browser_Version = "3.9";}
			}
			elseif (eregi("beonex",$this->USER_AGENT))
			{
				$this->Browser = "Beonex";
				if (eregi("beonex/0.8.2",$this->USER_AGENT)) {$this->Browser_Version = "0.8.2";}
				elseif (eregi("beonex/0.8.1",$this->USER_AGENT)) {$this->Browser_Version = "0.8.1";}
				elseif (eregi("beonex/0.8",$this->USER_AGENT)) {$this->Browser_Version = "0.8";}
			}
			elseif (eregi("camino",$this->USER_AGENT))
			{
				$this->Browser = "Camino";
				if (eregi("camino/0.7",$this->USER_AGENT)) {$this->Browser_Version = "0.7";}
			}
			elseif (eregi("cyberdog",$this->USER_AGENT))
			{
				$this->Browser = "Cyberdog";
				if (eregi("cybergog/1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
				elseif (eregi("cyberdog/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
				elseif (eregi("cyberdog/2.0b1",$this->USER_AGENT)) {$this->Browser_Version = "2.0b1";}
			}
			elseif (eregi("dillo",$this->USER_AGENT))
			{
				$this->Browser = "Dillo";
				if (eregi("dillo/0.6.6",$this->USER_AGENT)) {$this->Browser_Version = "0.6.6";}
				elseif (eregi("dillo/0.7.2",$this->USER_AGENT)) {$this->Browser_Version = "0.7.2";}
				elseif (eregi("dillo/0.7.3",$this->USER_AGENT)) {$this->Browser_Version = "0.7.3";}
			}
			elseif (eregi("doris",$this->USER_AGENT))
			{
				$this->Browser = "Doris";
				if (eregi("doris/1.10",$this->USER_AGENT)) {$this->Browser_Version = "1.10";}
			}
			elseif (eregi("emacs",$this->USER_AGENT))
			{
				$this->Browser = "Emacs";
				if (eregi("emacs/w3/2",$this->USER_AGENT)) {$this->Browser_Version = "2";}
				elseif (eregi("emacs/w3/3",$this->USER_AGENT)) {$this->Browser_Version = "3";}
				elseif (eregi("emacs/w3/4",$this->USER_AGENT)) {$this->Browser_Version = "4";}
			}
			elseif (eregi("firebird",$this->USER_AGENT))
			{
				$this->Browser = "Firebird";
				if ((eregi("firebird/0.6",$this->USER_AGENT)) || (eregi("browser/0.6",$this->USER_AGENT))) {$this->Browser_Version = "0.6";}
				elseif (eregi("firebird/0.7",$this->USER_AGENT)) {$this->Browser_Version = "0.7";}
			}
			elseif (eregi("frontpage",$this->USER_AGENT))
			{
				$this->Browser = "FrontPage";
				if ((eregi("express 2",$this->USER_AGENT)) || (eregi("frontpage 2",$this->USER_AGENT))) {$this->Browser_Version = "2";}
				elseif (eregi("frontpage 3",$this->USER_AGENT)) {$this->Browser_Version = "3";}
				elseif (eregi("frontpage 4",$this->USER_AGENT)) {$this->Browser_Version = "4";}
				elseif (eregi("frontpage 5",$this->USER_AGENT)) {$this->Browser_Version = "5";}
				elseif (eregi("frontpage 6",$this->USER_AGENT)) {$this->Browser_Version = "6";}
			}
			elseif (eregi("galeon",$this->USER_AGENT))
			{
				$this->Browser = "Galeon";
				if (eregi("galeon 0.1",$this->USER_AGENT)) {$this->Browser_Version = "0.1";}
				elseif (eregi("galeon/0.11.1",$this->USER_AGENT)) {$this->Browser_Version = "0.11.1";}
				elseif (eregi("galeon/0.11.2",$this->USER_AGENT)) {$this->Browser_Version = "0.11.2";}
				elseif (eregi("galeon/0.11.3",$this->USER_AGENT)) {$this->Browser_Version = "0.11.3";}
				elseif (eregi("galeon/0.11.5",$this->USER_AGENT)) {$this->Browser_Version = "0.11.5";}
				elseif (eregi("galeon/0.12.8",$this->USER_AGENT)) {$this->Browser_Version = "0.12.8";}
				elseif (eregi("galeon/0.12.7",$this->USER_AGENT)) {$this->Browser_Version = "0.12.7";}
				elseif (eregi("galeon/0.12.6",$this->USER_AGENT)) {$this->Browser_Version = "0.12.6";}
				elseif (eregi("galeon/0.12.5",$this->USER_AGENT)) {$this->Browser_Version = "0.12.5";}
				elseif (eregi("galeon/0.12.4",$this->USER_AGENT)) {$this->Browser_Version = "0.12.4";}
				elseif (eregi("galeon/0.12.3",$this->USER_AGENT)) {$this->Browser_Version = "0.12.3";}
				elseif (eregi("galeon/0.12.2",$this->USER_AGENT)) {$this->Browser_Version = "0.12.2";}
				elseif (eregi("galeon/0.12.1",$this->USER_AGENT)) {$this->Browser_Version = "0.12.1";}
				elseif (eregi("galeon/0.12",$this->USER_AGENT)) {$this->Browser_Version = "0.12";}
				elseif ((eregi("galeon/1",$this->USER_AGENT)) || (eregi("galeon 1.0",$this->USER_AGENT))) {$this->Browser_Version = "1.0";}
			}
			elseif (eregi("ibm web browser",$this->USER_AGENT))
			{
				$this->Browser = "IBM Web Browser";
				if (eregi("rv:1.0.1",$this->USER_AGENT)) {$this->Browser_Version = "1.0.1";}
			}
			elseif (eregi("chimera",$this->USER_AGENT))
			{
				$this->Browser = "Chimera";
				if (eregi("chimera/0.7",$this->USER_AGENT)) {$this->Browser_Version = "0.7";}
				elseif (eregi("chimera/0.6",$this->USER_AGENT)) {$this->Browser_Version = "0.6";}
				elseif (eregi("chimera/0.5",$this->USER_AGENT)) {$this->Browser_Version = "0.5";}
				elseif (eregi("chimera/0.4",$this->USER_AGENT)) {$this->Browser_Version = "0.4";}
			}
			elseif (eregi("icab",$this->USER_AGENT))
			{
				$this->Browser = "iCab";
				if (eregi("icab/2.7.1",$this->USER_AGENT)) {$this->Browser_Version = "2.7.1";}
				elseif (eregi("icab/2.8.1",$this->USER_AGENT)) {$this->Browser_Version = "2.8.1";}
				elseif (eregi("icab/2.8.2",$this->USER_AGENT)) {$this->Browser_Version = "2.8.2";}
				elseif (eregi("icab 2.9",$this->USER_AGENT)) {$this->Browser_Version = "2.9";}
				elseif (eregi("icab 2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
			}
			elseif (eregi("konqueror",$this->USER_AGENT))
			{
				$this->Browser = "Konqueror";
				if (eregi("konqueror/3.1",$this->USER_AGENT)) {$this->Browser_Version = "3.1";}
				elseif (eregi("konqueror/3.2",$this->USER_AGENT)) {$this->Browser_Version = "3.2";}
				elseif (eregi("konqueror/3",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
				elseif (eregi("konqueror/2.2",$this->USER_AGENT)) {$this->Browser_Version = "2.2";}
				elseif (eregi("konqueror/2.1",$this->USER_AGENT)) {$this->Browser_Version = "2.1";}
				elseif (eregi("konqueror/1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
			}
			elseif (eregi("liberate",$this->USER_AGENT))
			{
				$this->Browser = "Liberate";
				if (eregi("dtv 1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
				elseif (eregi("dtv 1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
			}
			elseif (eregi("desktop/lx",$this->USER_AGENT))
			{
				$this->Browser = "Lycoris Desktop/LX";
			}
			elseif (eregi("netcaptor",$this->USER_AGENT))
			{
				$this->Browser = "Netcaptor";
				if (eregi("netcaptor 7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
				elseif (eregi("netcaptor 7.1",$this->USER_AGENT)) {$this->Browser_Version = "7.1";}
				elseif (eregi("netcaptor 7.2",$this->USER_AGENT)) {$this->Browser_Version = "7.2";}
				elseif (eregi("netcaptor 7.5",$this->USER_AGENT)) {$this->Browser_Version = "7.5";}
			}
			elseif (eregi("netpliance",$this->USER_AGENT))
			{
				$this->Browser = "Netpliance";
			}
			elseif (eregi("netscape",$this->USER_AGENT)) // (1) netscape nie je prilis detekovatelny....
			{
				$this->Browser = "Netscape";
				if (eregi("netscape/7.1",$this->USER_AGENT)) {$this->Browser_Version = "7.1";}
				elseif (eregi("netscape/7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
				elseif (eregi("netscape6/6.2",$this->USER_AGENT)) {$this->Browser_Version = "6.2";}
				elseif (eregi("netscape6/6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
				elseif (eregi("netscape6/6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
			}
			elseif ((eregi("mozilla/5.0",$this->USER_AGENT)) && (eregi("rv:",$this->USER_AGENT)) && (eregi("gecko/",$this->USER_AGENT))) // mozilla je troschu zlozitejsia na detekciu
			{
				$this->Browser = "Mozilla";
				if (eregi("rv:1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
				elseif (eregi("rv:1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
				elseif (eregi("rv:1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
				elseif (eregi("rv:1.3",$this->USER_AGENT)) {$this->Browser_Version = "1.3";}
				elseif (eregi("rv:1.4",$this->USER_AGENT)) {$this->Browser_Version = "1.4";}
				elseif (eregi("rv:1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
				elseif (eregi("rv:1.6",$this->USER_AGENT)) {$this->Browser_Version = "1.6";}
				elseif (eregi("rv:1.7",$this->USER_AGENT)) {$this->Browser_Version = "1.7";}
			}
			elseif (eregi("offbyone",$this->USER_AGENT))
			{
				$this->Browser = "OffByOne";
				if (eregi("mozilla/4.7",$this->USER_AGENT)) {$this->Browser_Version = "3.4";}
			}
			elseif (eregi("omniweb",$this->USER_AGENT))
			{
				$this->Browser = "OmniWeb";
				if (eregi("omniweb/4.5",$this->USER_AGENT)) {$this->Browser_Version = "4.5";}
				elseif (eregi("omniweb/4.4",$this->USER_AGENT)) {$this->Browser_Version = "4.4";}
				elseif (eregi("omniweb/4.3",$this->USER_AGENT)) {$this->Browser_Version = "4.3";}
				elseif (eregi("omniweb/4.2",$this->USER_AGENT)) {$this->Browser_Version = "4.2";}
				elseif (eregi("omniweb/4.1",$this->USER_AGENT)) {$this->Browser_Version = "4.1";}
			}
			elseif (eregi("opera",$this->USER_AGENT))
			{
				$this->Browser = "Opera";
				if ((eregi("opera/7.21",$this->USER_AGENT)) || (eregi("opera 7.21",$this->USER_AGENT))) {$this->Browser_Version = "7.21";}
				elseif ((eregi("opera/7.50",$this->USER_AGENT)) || (eregi("opera 7.50",$this->USER_AGENT))) {$this->Browser_Version = "7.50";}
				elseif ((eregi("opera/7.23",$this->USER_AGENT)) || (eregi("opera 7.23",$this->USER_AGENT))) {$this->Browser_Version = "7.23";}
				elseif ((eregi("opera/7.22",$this->USER_AGENT)) || (eregi("opera 7.22",$this->USER_AGENT))) {$this->Browser_Version = "7.22";}
				elseif ((eregi("opera/7.20",$this->USER_AGENT)) || (eregi("opera 7.20",$this->USER_AGENT))) {$this->Browser_Version = "7.20";}
				elseif ((eregi("opera/7.11",$this->USER_AGENT)) || (eregi("opera 7.11",$this->USER_AGENT))) {$this->Browser_Version = "7.11";}
				elseif ((eregi("opera/7.10",$this->USER_AGENT)) || (eregi("opera 7.10",$this->USER_AGENT))) {$this->Browser_Version = "7.10";}
				elseif ((eregi("opera/7.03",$this->USER_AGENT)) || (eregi("opera 7.03",$this->USER_AGENT))) {$this->Browser_Version = "7.03";}
				elseif ((eregi("opera/7.02",$this->USER_AGENT)) || (eregi("opera 7.02",$this->USER_AGENT))) {$this->Browser_Version = "7.02";}
				elseif ((eregi("opera/7.01",$this->USER_AGENT)) || (eregi("opera 7.01",$this->USER_AGENT))) {$this->Browser_Version = "7.01";}
				elseif ((eregi("opera/7.0",$this->USER_AGENT)) || (eregi("opera 7.0",$this->USER_AGENT))) {$this->Browser_Version = "7.0";}
				elseif ((eregi("opera/6.12",$this->USER_AGENT)) || (eregi("opera 6.12",$this->USER_AGENT))) {$this->Browser_Version = "6.12";}
				elseif ((eregi("opera/6.11",$this->USER_AGENT)) || (eregi("opera 6.11",$this->USER_AGENT))) {$this->Browser_Version = "6.11";}
				elseif ((eregi("opera/6.1",$this->USER_AGENT)) || (eregi("opera 6.1",$this->USER_AGENT))) {$this->Browser_Version = "6.1";}
				elseif ((eregi("opera/6.	0",$this->USER_AGENT)) || (eregi("opera 6.0",$this->USER_AGENT))) {$this->Browser_Version = "6.0";}
				elseif ((eregi("opera/5.12",$this->USER_AGENT)) || (eregi("opera 5.12",$this->USER_AGENT))) {$this->Browser_Version = "5.12";}
				elseif ((eregi("opera/5.0",$this->USER_AGENT)) || (eregi("opera 5.0",$this->USER_AGENT))) {$this->Browser_Version = "5.0";}
				elseif ((eregi("opera/4",$this->USER_AGENT)) || (eregi("opera 4",$this->USER_AGENT))) {$this->Browser_Version = "4";}
			}
			elseif (eregi("oracle",$this->USER_AGENT))
			{
				$this->Browser = "Oracle PowerBrowser";
				if (eregi("(tm)/1.0a",$this->USER_AGENT)) {$this->Browser_Version = "1.0a";}
				elseif (eregi("oracle 1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
			}
			elseif (eregi("phoenix",$this->USER_AGENT))
			{
				$this->Browser = "Phoenix";
				if (eregi("phoenix/0.4",$this->USER_AGENT)) {$this->Browser_Version = "0.4";}
				elseif (eregi("phoenix/0.5",$this->USER_AGENT)) {$this->Browser_Version = "0.5";}
			}
			elseif (eregi("planetweb",$this->USER_AGENT))
			{
				$this->Browser = "PlanetWeb";
				if (eregi("planetweb/2.606",$this->USER_AGENT)) {$this->Browser_Version = "2.6";}
				elseif (eregi("planetweb/1.125",$this->USER_AGENT)) {$this->Browser_Version = "3";}
			}
			elseif (eregi("powertv",$this->USER_AGENT))
			{
				$this->Browser = "PowerTV";
				if (eregi("powertv/1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
			}
			elseif (eregi("prodigy",$this->USER_AGENT))
			{
				$this->Browser = "Prodigy";
				if (eregi("wb/3.2e",$this->USER_AGENT)) {$this->Browser_Version = "3.2e";}
				elseif (eregi("rv: 1.",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
			}
			elseif ((eregi("voyager",$this->USER_AGENT)) || ((eregi("qnx",$this->USER_AGENT))) && (eregi("rv: 1.",$this->USER_AGENT))) // aj voyager je trosku zlozitejsi na detekciu
			{
				$this->Browser = "Voyager";
				if (eregi("2.03b",$this->USER_AGENT)) {$this->Browser_Version = "2.03b";}
				elseif (eregi("wb/win32/3.4g",$this->USER_AGENT)) {$this->Browser_Version = "3.4g";}
			}
			elseif (eregi("quicktime",$this->USER_AGENT))
			{
				$this->Browser = "QuickTime";
				if (eregi("qtver=5",$this->USER_AGENT)) {$this->Browser_Version = "5.0";}
				elseif (eregi("qtver=6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
				elseif (eregi("qtver=6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
				elseif (eregi("qtver=6.2",$this->USER_AGENT)) {$this->Browser_Version = "6.2";}
				elseif (eregi("qtver=6.3",$this->USER_AGENT)) {$this->Browser_Version = "6.3";}
				elseif (eregi("qtver=6.4",$this->USER_AGENT)) {$this->Browser_Version = "6.4";}
				elseif (eregi("qtver=6.5",$this->USER_AGENT)) {$this->Browser_Version = "6.5";}
			}
			elseif (eregi("safari",$this->USER_AGENT))
			{
				$this->Browser = "Safari";
				if (eregi("safari/48",$this->USER_AGENT)) {$this->Browser_Version = "0.48";}
				elseif (eregi("safari/49",$this->USER_AGENT)) {$this->Browser_Version = "0.49";}
				elseif (eregi("safari/51",$this->USER_AGENT)) {$this->Browser_Version = "0.51";}
				elseif (eregi("safari/60",$this->USER_AGENT)) {$this->Browser_Version = "0.60";}
				elseif (eregi("safari/61",$this->USER_AGENT)) {$this->Browser_Version = "0.61";}
				elseif (eregi("safari/62",$this->USER_AGENT)) {$this->Browser_Version = "0.62";}
				elseif (eregi("safari/63",$this->USER_AGENT)) {$this->Browser_Version = "0.63";}
				elseif (eregi("safari/64",$this->USER_AGENT)) {$this->Browser_Version = "0.64";}
				elseif (eregi("safari/65",$this->USER_AGENT)) {$this->Browser_Version = "0.65";}
				elseif (eregi("safari/66",$this->USER_AGENT)) {$this->Browser_Version = "0.66";}
				elseif (eregi("safari/67",$this->USER_AGENT)) {$this->Browser_Version = "0.67";}
				elseif (eregi("safari/68",$this->USER_AGENT)) {$this->Browser_Version = "0.68";}
				elseif (eregi("safari/69",$this->USER_AGENT)) {$this->Browser_Version = "0.69";}
				elseif (eregi("safari/70",$this->USER_AGENT)) {$this->Browser_Version = "0.70";}
				elseif (eregi("safari/71",$this->USER_AGENT)) {$this->Browser_Version = "0.71";}
				elseif (eregi("safari/72",$this->USER_AGENT)) {$this->Browser_Version = "0.72";}
				elseif (eregi("safari/73",$this->USER_AGENT)) {$this->Browser_Version = "0.73";}
				elseif (eregi("safari/74",$this->USER_AGENT)) {$this->Browser_Version = "0.74";}
				elseif (eregi("safari/80",$this->USER_AGENT)) {$this->Browser_Version = "0.80";}
				elseif (eregi("safari/83",$this->USER_AGENT)) {$this->Browser_Version = "0.83";}
				elseif (eregi("safari/84",$this->USER_AGENT)) {$this->Browser_Version = "0.84";}
				elseif (eregi("safari/90",$this->USER_AGENT)) {$this->Browser_Version = "0.90";}
				elseif (eregi("safari/92",$this->USER_AGENT)) {$this->Browser_Version = "0.92";}
				elseif (eregi("safari/93",$this->USER_AGENT)) {$this->Browser_Version = "0.93";}
				elseif (eregi("safari/94",$this->USER_AGENT)) {$this->Browser_Version = "0.94";}
				elseif (eregi("safari/95",$this->USER_AGENT)) {$this->Browser_Version = "0.95";}
				elseif (eregi("safari/96",$this->USER_AGENT)) {$this->Browser_Version = "0.96";}
				elseif (eregi("safari/97",$this->USER_AGENT)) {$this->Browser_Version = "0.97";}
			}
			elseif (eregi("sextatnt",$this->USER_AGENT))
			{
				$this->Browser = "Tango";
				if (eregi("sextant v3.0",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
			}
			elseif (eregi("elinks",$this->USER_AGENT))
			{
				$this->Browser = "ELinks";
				if (eregi("0.3",$this->USER_AGENT)) {$this->Browser_Version = "0.3";}
				elseif (eregi("0.4",$this->USER_AGENT)) {$this->Browser_Version = "0.4";}
			}
			elseif (eregi("links",$this->USER_AGENT))
			{
				$this->Browser = "Links";
				if (eregi("0.9",$this->USER_AGENT)) {$this->Browser_Version = "0.9";}
				elseif (eregi("2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
				elseif (eregi("2.1",$this->USER_AGENT)) {$this->Browser_Version = "2.1";}
			}
			elseif (eregi("lynx",$this->USER_AGENT))
			{
				$this->Browser = "Lynx";
				if (eregi("lynx/2.3",$this->USER_AGENT)) {$this->Browser_Version = "2.3";}
				elseif (eregi("lynx/2.4",$this->USER_AGENT)) {$this->Browser_Version = "2.4";}
				elseif ((eregi("lynx/2.5",$this->USER_AGENT)) || (eregi("lynx 2.5",$this->USER_AGENT))) {$this->Browser_Version = "2.5";}
				elseif (eregi("lynx/2.6",$this->USER_AGENT)) {$this->Browser_Version = "2.6";}
				elseif (eregi("lynx/2.7",$this->USER_AGENT)) {$this->Browser_Version = "2.7";}
				elseif (eregi("lynx/2.8",$this->USER_AGENT)) {$this->Browser_Version = "2.8";}
			}
			elseif (eregi("webexplorer",$this->USER_AGENT))
			{
				$this->Browser = "WebExplorer";
				if (eregi("dll/v1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
			}
			elseif (eregi("wget",$this->USER_AGENT))
			{
				$this->Browser = "WGet";
				if (eregi("Wget/1.9",$this->USER_AGENT)) {$this->Browser_Version = "1.9";}
				if (eregi("Wget/1.8",$this->USER_AGENT)) {$this->Browser_Version = "1.8";}
			}
			elseif (eregi("webtv",$this->USER_AGENT))
			{
				$this->Browser = "WebTV";
				if (eregi("webtv/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
				elseif (eregi("webtv/1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
				elseif (eregi("webtv/1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
				elseif (eregi("webtv/2.2",$this->USER_AGENT)) {$this->Browser_Version = "2.2";}
				elseif (eregi("webtv/2.5",$this->USER_AGENT)) {$this->Browser_Version = "2.5";}
				elseif (eregi("webtv/2.6",$this->USER_AGENT)) {$this->Browser_Version = "2.6";}
				elseif (eregi("webtv/2.7",$this->USER_AGENT)) {$this->Browser_Version = "2.7";}
			}
			elseif (eregi("yandex",$this->USER_AGENT))
			{
				$this->Browser = "Yandex";
				if (eregi("/1.01",$this->USER_AGENT)) {$this->Browser_Version = "1.01";}
				elseif (eregi("/1.03",$this->USER_AGENT)) {$this->Browser_Version = "1.03";}
			}
			elseif ((eregi("mspie",$this->USER_AGENT)) || ((eregi("msie",$this->USER_AGENT))) && (eregi("windows ce",$this->USER_AGENT)))
			{
				$this->Browser = "Pocket Inetrnet Explorer";
				if (eregi("mspie 1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
				elseif (eregi("mspie 2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
				elseif (eregi("msie 3.02",$this->USER_AGENT)) {$this->Browser_Version = "3.02";}
			}
			elseif (eregi("msie",$this->USER_AGENT))
			{
				$this->Browser = "Internet Explorer";
				if (eregi("msie 6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
				elseif (eregi("msie 5.5",$this->USER_AGENT)) {$this->Browser_Version = "5.5";}
				elseif (eregi("msie 5.01",$this->USER_AGENT)) {$this->Browser_Version = "5.01";}
				elseif (eregi("msie 5.23",$this->USER_AGENT)) {$this->Browser_Version = "5.23";}
				elseif (eregi("msie 5.22",$this->USER_AGENT)) {$this->Browser_Version = "5.22";}
				elseif (eregi("msie 5.2.2",$this->USER_AGENT)) {$this->Browser_Version = "5.2.2";}
				elseif (eregi("msie 5.1b1",$this->USER_AGENT)) {$this->Browser_Version = "5.1b1";}
				elseif (eregi("msie 5.17",$this->USER_AGENT)) {$this->Browser_Version = "5.17";}
				elseif (eregi("msie 5.16",$this->USER_AGENT)) {$this->Browser_Version = "5.16";}
				elseif (eregi("msie 5.12",$this->USER_AGENT)) {$this->Browser_Version = "5.12";}
				elseif (eregi("msie 5.0b1",$this->USER_AGENT)) {$this->Browser_Version = "5.0b1";}
				elseif (eregi("msie 5.0",$this->USER_AGENT)) {$this->Browser_Version = "5.0";}
				elseif (eregi("msie 5.21",$this->USER_AGENT)) {$this->Browser_Version = "5.21";}
				elseif (eregi("msie 5.2",$this->USER_AGENT)) {$this->Browser_Version = "5.2";}
				elseif (eregi("msie 5.15",$this->USER_AGENT)) {$this->Browser_Version = "5.15";}
				elseif (eregi("msie 5.14",$this->USER_AGENT)) {$this->Browser_Version = "5.14";}
				elseif (eregi("msie 5.13",$this->USER_AGENT)) {$this->Browser_Version = "5.13";}
				elseif (eregi("msie 4.5",$this->USER_AGENT)) {$this->Browser_Version = "4.5";}
				elseif (eregi("msie 4.01",$this->USER_AGENT)) {$this->Browser_Version = "4.01";}
				elseif (eregi("msie 4.0b2",$this->USER_AGENT)) {$this->Browser_Version = "4.0b2";}
				elseif (eregi("msie 4.0b1",$this->USER_AGENT)) {$this->Browser_Version = "4.0b1";}
				elseif (eregi("msie 4",$this->USER_AGENT)) {$this->Browser_Version = "4.0";}
				elseif (eregi("msie 3",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
				elseif (eregi("msie 2",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
				elseif (eregi("msie 1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
			}
			elseif (eregi("iexplore",$this->USER_AGENT))
			{
				$this->Browser = "Internet Explorer";
			}
			elseif (eregi("mozilla",$this->USER_AGENT)) // (2) netscape nie je prilis detekovatelny....
			{
				$this->Browser = "Netscape";
				if (eregi("mozilla/4.8",$this->USER_AGENT)) {$this->Browser_Version = "4.8";}
				elseif (eregi("mozilla/4.7",$this->USER_AGENT)) {$this->Browser_Version = "4.7";}
				elseif (eregi("mozilla/4.6",$this->USER_AGENT)) {$this->Browser_Version = "4.6";}
				elseif (eregi("mozilla/4.5",$this->USER_AGENT)) {$this->Browser_Version = "4.5";}
				elseif (eregi("mozilla/4.0",$this->USER_AGENT)) {$this->Browser_Version = "4.0";}
				elseif (eregi("mozilla/3.0",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
				elseif (eregi("mozilla/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
			}
			// ine
			elseif (eregi("w3c_validator",$this->USER_AGENT))
			{
				$this->Browser = "W3C Validator";
			}
		}   

};
}
?>