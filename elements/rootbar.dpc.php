<?php

$__DPCSEC['ROOTBAR_DPC']='1;1;1;1;1;1;1;1;9';

if ( (!defined("ROOTBAR_DPC")) && (seclevel('ROOTBAR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("ROOTBAR_DPC",true);

$__DPC['ROOTBAR_DPC'] = 'rootbar';

$__EVENTS['ROOTBAR_DPC'][0]='root';
$__EVENTS['ROOTBAR_DPC'][1]='rootmenu';
$__EVENTS['ROOTBAR_DPC'][2]='updateroots';
$__EVENTS['ROOTBAR_DPC'][3]='lang';//in case of lang change in root execute the file 

$__ACTIONS['ROOTBAR_DPC'][0]='root';
$__ACTIONS['ROOTBAR_DPC'][1]='rootmenu';
$__ACTIONS['ROOTBAR_DPC'][2]='updateroots';
$__ACTIONS['ROOTBAR_DPC'][3]='lang';//in case of lang change in root execute the file 

$__DPCATTR['ROOTBAR_DPC']['root'] = 'root,0,0,0,0,0,0,1,0';

$__PARSECOM['ROOTBAR_DPC']['render']='_ROOTBAR_';
$__PARSECOM['ROOTBAR_DPC']['iconize']='_ROOTICONS_';
$__PARSECOM['ROOTBAR_DPC']['rooturl']='_ROOTLNK_';

class rootbar {
	
	var $rootfiles;
	var $rootlang;
	var $rootext;
	var $icontype;
	var $iconpath;
	var $maxar;
	var $homeback;
	var $hiddens;
	var $filetype;
	
	var $userwinout;
	var $urlpath;

	function rootbar() {
	  $UserSecID = GetGlobal('UserSecID');
	  $GRX = GetGlobal('GRX'); 
	  $__LOCALE = GetGlobal('__LOCALE');
	  
	  decode_url();  //this class perform url encoding indepentendly from global url encoding

      $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	  
	  $this->rootfiles = arrayload('ROOTBAR','files'); //print_r($this->rootfiles);
	  $this->hiddens = arrayload('ROOTBAR','hidden');	  
	  $this->lang1 = arrayload('ROOTBAR','lan1');
	  $this->lang1 = arrayload('ROOTBAR','lan2');	  
	  $this->rootext = paramload('ROOTBAR','extension');	
	  $this->icontype = paramload('ROOTBAR','icontype');	  
	  $this->iconpath = paramload('ROOTBAR','iconpath');		  
	  $this->homeback = paramload('ROOTBAR','homeback');
	  $this->filetype = paramload('ROOTBAR','filetype');	  
	  
	  $this->maxar = count($this->rootfiles); //echo $this->maxar;
	  //print_r($__LOCALE);
	  for ($i=0;$i<$this->maxar;$i++){
	    $id = "_" . $this->rootfiles[$i];
		$lan0 = $this->rootfiles[$i];
        $lan1 = $this->lang1[$i];	
        $lan2 = $this->lang2[$i];		
	    $__LOCALE['ROOTBAR_DPC'][$i] = "$id;$lan0;$lan1;$lan2";    //??????
	  }	
	  SetGlobal('__LOCALE',$__LOCALE);
	  //print_r($__LOCALE['ROOTBAR_DPC']);
	  //print_r($this->rootfiles); 	
	  
      if ($GRX) {
			 $this->outpoint = "|"; //loadTheme('point');
			 $this->bullet = loadTheme('bullet');	  
	  }
      else {
	  	     $this->outpoint = "|";
			 $this->bullet = "&nbsp;";		 
	  }  
	  
	  $dispatcher = GetGlobal('dispatcher');
	  //warning in page cntrl(executed bu controller=dispatcher)
	  $this->usewinout = @$dispatcher->get_attribute(8);
	  $this->urlpath = paramload('SHELL','urlpath');//."/".paramload('ID','hostinpath')."/cp/rfiles/";	

      $this->prpath = paramload('SHELL','prpath');	  
	}
	

    function event($event=null) {
	
	  switch ($event) {
	    case "updateroots" : $this->updateroots(); break;
	  }        
    }
   
    function action($action=null) {
	   $param1 = GetGlobal('param1');
	   $g = GetReq('g'); //echo $g;

       //if ($page == $g) $param1 = $page;
	   if (isset($g)) $param1 = $g;

	   switch ($action) {
	     case 'rootmenu'    : $out = $this->render(); break;
	     case 'updateroots' : break;
		 case 'lang'        :
		 case 'root'        : $out = $this->loadRootFile($param1);
		 	                  //$cfp = new frontpage('rootbar',0);
	                          //$out = $cfp->render($preout);
	                          //unset($cfp);	
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
			               $xml->addtag('GTKMENU',null,null,null);
			               $xml->addtag('menubar','GTKMENU',null,"id=rootbar");			 		 
					       $xml->addtag('menu','menubar',null,"id=rootmenu|label=Root");
					       $xml->addtag('menupopup','menu',null,"id=popup1");				   					   
						   foreach ($this->rootfiles as $id=>$file)  
						     $xml->addtag('menuitem','menupopup','/',"label=".localize("_".$file,getlocal())."|cmd=root $file");
					       $xml->addtag('menuseparator','menupopup','/',null);
					       $xml->addtag('menuitem','menupopup','/',"label=Exit|cmd=_GTKEXIT");							   								 
					       $out = $xml->getxml();
					       unset($xml);
						   break;
		     case 'CLI'  :
	         case 'TEXT' : break;											   
	  } 	    
	  
	  return ($out);
    }
	
    function loadRootFile($file) {
       $__USERAGENT = GetGlobal('__USERAGENT');		

	   if ($file[0]=='_') {//that means title to be translated
	     $myf = localize($file,getlocal());
	   }	 
	   else
	     $myf = $file; //as is	 
	   
	   $lang_selected = (getlocal()?getlocal():0);
	   
	   $file_selected = $this->prpath . trim($myf) . $lang_selected . ".root";
	   
	   if (is_readable($file_selected))
	     $roortfile = $file_selected;
	   else	 //no lang option
	     $rootfile = $this->prpath . trim($myf) . ".root";
	   //echo '>>>',$rootfile;
	   
	   if (file_exists($rootfile)) {

         $datafile = html2txt($rootfile);	 
		 
         switch ($__USERAGENT) {
	         case 'XML'  : 
			 case 'XUL'  :		 
	         case 'GTK'  : $xml = new pxml('XUL');
			               $xml->addtag('GTKHTML',null,$xml->cdata($datafile),'cdata=1');		 		 						   					   
					       $out = $xml->getxml();
					       unset($xml);
						   break;
	         case 'TEXT' : 
		     case 'CLI'  : $out .= $datafile . "\n"; 
						   break;		
						   
	         case 'HTML' : 
			 default     : if ($this->usewinout) {//select appearance	   
                             //navigation status    	  
                             $out = setNavigator($myf);
	   
	                         $win = new window($myf,$datafile);
	                         $out .= $win->render();//"center::100%::0::group_win_body::left::0::0::");	
	                         unset ($win);	
		                   }
		                   else {
						     $out = setNavigator($myf);
		                     $out .= $datafile;
		                   } 
	                       break;						   									   
	     } 		    
		     
       }
	   return ($out);
    }	
	
	//called by the xgi file
	function iconize($vhtype="vertical",$icontype=1,$align="left") {
      $__USERAGENT = GetGlobal('__USERAGENT');;		  
	  
      switch ($__USERAGENT) {
	         case 'XML'  : 
			 case 'XUL'  :	
	         case 'GTK'  : $xml = new pxml('XUL');
			               $xml->addtag('GTKMENU',null,null,null);
			               $xml->addtag('menubar','GTKMENU',null,"id=rootbar");			 		 
					       $xml->addtag('menu','menubar',null,"id=rootmenu|label=File");
					       $xml->addtag('menupopup','menu',null,"id=popup1");				   					   
						   foreach ($this->rootfiles as $id=>$file)  
						     $xml->addtag('menuitem','menupopup','/',"label=".localize("_".$file,getlocal())."|cmd=root $file");
					       $xml->addtag('menuseparator','menupopup','/','id=sep1');
					       $xml->addtag('menuitem','menupopup','/',"label=Exit|cmd=_GTKEXIT");							   								 
					       $out = $xml->getxml();
					       unset($xml);
			               break;		 
		 	  
	         case 'HTML' : $out = $this->_iconize($vhtype,$icontype,$align);	  
   	                       break;
	  }
	  		  
	  return ($out);	
	}	
	
	
	function _iconize($vhtype="vertical",$icontype=1,$align="left") {
      $GRX = GetGlobal('GRX');
	  	
	  $root_icons = array();	
	  
      if ($GRX) {
	         for ($i=0;$i<=$this->maxar;$i++) { 
		
			   if (!$this->hiddens[$i]) {
			   
			     $iconame = $this->iconpath."/".$this->rootfiles[$i].$this->icontype;
			     $localrf = localize("_".$this->rootfiles[$i],getlocal());
				 $root_icons[] = icon($iconame,
				                      /*encode_url(*/"t=root&g=".urlencode($localrf)/*)*/,
									  $localrf,
									  $icontype);			   
			   }						  
			 } 
 
	         $mmax = count($root_icons);
	  			  
	         if ($this->homeback) { 
			   $home = icon($this->iconpath."/home".$this->icontype,"",
							localize("_HOME",getlocal()),$icontype);
			   array_unshift($root_icons, $home);			   
			 }				 
	  			  
	  }
      else {
	         for ($i=0;$i<=$this->maxar;$i++) { 
			  //if (!$this->hiddens[$i]) $root_icons[] = icon("/icons/".$this->rootfiles[$i].$this->icontype,encode_url("t=root&a=&g=".urlencode(localize("_".$this->rootfiles[$i],getlocal()))),localize("_".$this->rootfiles[$i],getlocal()),$icontype);			   
			   if (!$this->hiddens[$i]) {
			   
			     $iconame = $this->iconpath."/".$this->rootfiles[$i].$this->icontype;
			     $localrf = localize("_".$this->rootfiles[$i],getlocal());
				 
				 $root_icons[] = icon($iconame,
				                      /*encode_url(*/"t=root&g=".urlencode($localrf)/*)*/,
									  $localrf,
									  $icontype);			   
			   }	
			 } 
			  
	         $mmax = count($root_icons);
	  			  			   
	         if ($this->homeback) { 
			   $home = icon($this->iconpath."/home".$this->icontype,"",
							localize("_HOME",getlocal()),$icontype);
			   array_unshift($root_icons, $home);			   
			 }		  					  			  
	  } 
	  
	  switch ($vhtype)  {
		  default : 	  	  
		  case "vertical"  :  for ($i=0;$i<$mmax;$i++) 
		                       $out .= $root_icons[$i];
		                      break;
		  case "horizontal":  for ($i=0;$i<$mmax;$i++) { 
		                        if ($i==0) $icbar[] = $this->outpoint . $root_icons[$i] . $this->outpoint;
		                              else $icbar[] = $root_icons[$i] . $this->outpoint;
		                        $icatr[] = "right;";//. floor($this->maxar/100);							     
							  } 
		                      break;		
							  
		  case "line"     :   $out .= $this->outpoint;
		                      for ($i=0;$i<$mmax;$i++) 
		                       $out .= $root_icons[$i] . $this->outpoint;
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
	
	  $enc_url = urlencode(localize("_".$name,getlocal()));
	  $out = seturl(encode_url("t=root&a=&g=$enc_url"),localize("_".$name,getlocal())); 	  
	  
	  return ($out);
	}
	
	//update alias of root files in locales (not work !!!!!?????)
	function updateroots() {
	  $__LOCALE = GetGlobal('__LOCALE');	
	
	  $this->maxar = count($this->rootfiles); //echo $this->maxar;
	  //print_r($__LOCALE);
	  for ($i=0;$i<$this->maxar;$i++){
	    $id = "_" . $this->rootfiles[$i];
		$lan0 = $this->rootfiles[$i];
        $lan1 = $this->lang1[$i];	
	    $__LOCALE['ROOTBAR_DPC'][$i] = "$id;$lan0;$lan1";    //??????
	  }	
	  SetGlobal('__LOCALE',$__LOCALE);	 
	  
	  GetGlobal('controller')->calldpc_method_use_pointers('admindb.txt2sql_locales',array(0=>&$__LOCALE)); 
	  
	  //print_r($__LOCALE['ROOTBAR_DPC']);
	}
	
	//used bydfxslide65
	function getroot_array() {

	  if ($this->homeback) { 	
			   $ret_array[localize("_HOME",getlocal())] = seturl();		   
	  }	
	
	 for ($i=0;$i<=$this->maxar;$i++) { 
			  //if (!$this->hiddens[$i]) $root_icons[] = icon("/icons/".$this->rootfiles[$i].$this->icontype,encode_url("t=root&a=&g=".urlencode(localize("_".$this->rootfiles[$i],getlocal()))),localize("_".$this->rootfiles[$i],getlocal()),$icontype);			   
			   if (!$this->hiddens[$i]) {
			   
			     //$iconame = $this->iconpath."/".$this->rootfiles[$i].$this->icontype;
			     $localrf = localize("_".$this->rootfiles[$i],getlocal());
				 
				 $ret_array[$localrf] = seturl(/*encode_url(*/"t=root&g=".urlencode($localrf)/*)*/);			   
			   }	
	  }		  
	  
	  return ($ret_array);	
	}	

};
}
?>