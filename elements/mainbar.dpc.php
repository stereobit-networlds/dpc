<?php

$__DPCSEC['MAINBAR_DPC']='1;1;1;1;1;1;1;1;9';

if ( (!defined("MAINBAR_DPC")) && (seclevel('MAINBAR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("MAINBAR_DPC",true);

$__DPC['MAINBAR_DPC'] = 'mainbar';

$__EVENTS['MAINBAR_DPC'][0]='root';
$__EVENTS['MAINBAR_DPC'][1]='mainmenu';

$__ACTIONS['MAINBAR_DPC'][0]='root';
$__ACTIONS['MAINBAR_DPC'][1]='mainmenu';

$__DPCATTR['MAINBAR_DPC']['root'] = 'root,0,0,0,0,0,0,1'; 
$__DPCATTR['MAINBAR_DPC']['mainmenu'] = 'mainmenu,0,0,0,0,0,0,1'; 

$__LOCALE['MAINBAR_DPC'][0]='_MCOMM1;Home;Αρχική';
$__LOCALE['MAINBAR_DPC'][1]='_MCOMM2;Search;Αναζητηση';
$__LOCALE['MAINBAR_DPC'][2]='_MCOMM3;Help;Βοήθεια';
$__LOCALE['MAINBAR_DPC'][3]='_MCOMM4;Contact us;Επικοινωνια';
$__LOCALE['MAINBAR_DPC'][4]='_MCOMM5;News;Νεα';
$__LOCALE['MAINBAR_DPC'][5]='_MCOMM6;Company;Εταιρία';
$__LOCALE['MAINBAR_DPC'][6]='_MCOMM7;Terms of Use;Οροι Χρήσης';
$__LOCALE['MAINBAR_DPC'][7]='_MCOMM8;Login;ΠΙΣΤΟΠΟΙΗΣΗ';
$__LOCALE['MAINBAR_DPC'][8]='_MCOMM9;Product Catalog;ΚΑΤΑΛΟΓΟΣ ΠΡΟΙΟΝΤΩΝ';

$__PARSECOM['MAINBAR_DPC']['render']='_MAINBAR_';
$__PARSECOM['MAINBAR_DPC']['iconize']='_MAINICONS_';
$__PARSECOM['MAINBAR_DPC']['rooturl']='_ROOTURL_';

class mainbar {

	var $pic1;
	var $pic2;
	var $pic3;
	var $pic4;

	function mainbar() {
	  $UserSecID = GetGlobal('UserSecID');
	  $GRX = GetGlobal('GRX');  

      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);

      $this->home =   localize('_MCOMM1',getlocal());	  
	  $this->search = localize('_MCOMM2',getlocal());
	  $this->help =   localize('_MCOMM3',getlocal());
	  $this->contact =localize('_MCOMM4',getlocal());
	  $this->news =   localize('_MCOMM5',getlocal());
  	  $this->company =localize('_MCOMM6',getlocal());	  
  	  $this->terms =  localize('_MCOMM7',getlocal());	  
  	  $this->logn =   localize('_MCOMM8',getlocal());	  
  	  $this->catalog =localize('_MCOMM9',getlocal());  	
	  
      if ($GRX) {
			 $this->outpoint = "|"; //loadTheme('point');
			 $this->bullet = loadTheme('bullet');	  
	  }
      else {
	  	     $this->outpoint = "|";
			 $this->bullet = "&nbsp;";		 
	  }  
	}
	

    function event($event=null) {        
    }
   
    function action($action=null) {
	   $param1 = GetGlobal('param1');
	   $g = GetReq('g');

       if ($g) $param1 = $g;		   

	   switch ($action) {
	     case 'mainmenu' : $out = $this->render(); break;
         default         : $out = $this->loadRootFile($g);
	   }
	   
	   return ($out);
    }
	
    function loadRootFile($file) {	
       $__USERAGENT = GetGlobal('__USERAGENT');			

	   $rootfile = paramload('SHELL','prpath') . trim($file) . ".root";
	   
       switch ($__USERAGENT) {
	         case 'HTML' :        //navigation status    	  
                           $out = setNavigator($file);
                           $datafile = html2txt($rootfile);
	   
	                       $win = new window($file,$datafile);
	                       $out .= $win->render();//"center::100%::0::group_win_body::left::0::0::");	
	                       unset ($win);	
	                       break;
	         case 'XML'  : 
			 case 'XUL'  :		 
	         case 'GTK'  : $xml = new pxml('XUL');
			               $xml->addtag('GTKHTML',null,html2txt($rootfile),null);		 		 						   					   
					       $out = $xml->getxml();
					       unset($xml);
						   break;
		     case 'CLI'  :
	         case 'TEXT' : break;											       
		     
       }	      
 
	   return ($out);
    } 	
	
    function render($pw='100%',$pal='right') {	
      $__USERAGENT = GetGlobal('__USERAGENT');	
		
      switch ($__USERAGENT) {
	         case 'HTML' : $mbwin = new window('',$this->iconize("horizontal",3));
                           $out = $mbwin->render("$pal::$pw::0::groupcomm::left::0::0::");
                           unset ($mbwin); 
	                       break;
	         case 'XML'  : 
			 case 'XUL'  :		 
	         case 'GTK'  : $xml = new pxml('XUL');
			               $xml->addtag('menubar','GTKMENU',null,null);			 
			               $xml->addtag('menubar',null,null,"id=mainbar");			 		 
					       $xml->addtag('menu','menubar',null,"id=mainmenu|label=Main");
					       $xml->addtag('menupopup','menu',null,"id=popup1");
					       $xml->addtag('menuitem','menupopup','/',"label=Aaaa");
					       $xml->addtag('menuitem','menupopup','/',"label=Bbbb");
					       $xml->addtag('menuitem','menupopup','/',"label=Cccc");
					       $xml->addtag('menuseparator','menupopup','/',null);	
					       $xml->addtag('menuitem','menupopup','/',"label=Dddd");						   					   
					       $out = $xml->getxml();
					       unset($xml);
						   break;
		     case 'CLI'  :
	         case 'TEXT' : break;											   
	  } 	   

	  return ($out);
    }
	
	function iconize($vhtype="vertical",$icontype=1,$align="left") {
      $GRX = GetGlobal('GRX');
	  
	  $news_url = urlencode('Νέα');
	  $company_url = urlencode('Εταιρία');
	  $help_url = urlencode('Βοήθεια');	
	  	  
      if ($GRX) {
			 $this->home_icon = icon("/icons/home.gif","t=&a=&g=",$this->home,$icontype);			 
			 $this->news_icon = icon("/icons/news.gif","t=root&a=&g=$news_url",$this->news,$icontype);
			 $this->company_icon = icon("/icons/company.gif","t=root&a=&g=$company_url",$this->company,$icontype);			 			 
			 $this->help_icon = icon("/icons/help.gif","t=root&a=&g=$help_url",$this->help,$icontype);  
	  }
      else {
			 $this->home_icon = icon("","t=&a=&g=",$this->home,1);			 
			 $this->news_icon = icon("","t=root&a=&g=$news_url",$this->news,1);
			 $this->company_icon = icon("","t=root&a=&g=$company_url",$this->company,1);			 
			 $help_icon = icon("","t=root&a=&g=$help_url",$this->help);			 
	  } 
	  
	  switch ($vhtype)  {
		  default : 	  	  
		  case "vertical"  :  $out .= $this->home_icon . 
	                                  $this->news_icon .
			                          $this->company_icon .
			                          $this->help_icon;
		                      break;
		  case "horizontal":  $icbar[] = $this->home_icon . $this->outpoint;
		                      $icatr[] = "right;49%";
                              $icbar[] = $this->news_icon . $this->outpoint;
		                      $icatr[] = "left;1%";							  
							  $icbar[] = $this->company_icon . $this->outpoint;
		                      $icatr[] = "left;1%";
							  $icbar[] = $this->help_icon;
		                      $icatr[] = "left;49%";
		                      break;		
							  
		  case "line"     :  $out .= $this->home_icon . $this->outpoint .
	                                  $this->news_icon . $this->outpoint .
			                          $this->company_icon . $this->outpoint .
			                          $this->help_icon;
		                      break;							  		                
	  }	   	  
	  
	  switch ($vhtype) {
			    case "horizontal" :
                                     $icobar = new window('',$icbar,$icatr);
                                     $out = $icobar->render("center::100%::0::group_icons::$align::0::0::");
                                     unset ($icobar);	
									 break;
			    case "line" :
                                     $icobar = new window('',$winout);
                                     $out = $icobar->render("center::100%::0::group_icons::$align::0::0::");
                                     unset ($icobar);	
									 break;									 
	  }
	  		  
	  return ($out);	
	}	
	
	function rooturl($name) {
	
	  $enc_url = urlencode($name);
	  $out = seturl("t=root&a=&g=$enc_url",$name); 	  
	  
	  return ($out);
	}

};
}
?>