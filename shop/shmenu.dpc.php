<?php

$__DPCSEC['SHMENU_DPC']='1;1;1;2;2;2;2;2;9';

if ((!defined("SHMENU_DPC")) && (seclevel('SHMENU_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHMENU_DPC",true);

$__DPC['SHMENU_DPC'] = 'shmenu';


$__EVENTS['SHMENU_DPC'][1]='menu';
$__EVENTS['SHMENU_DPC'][2]='menu1';
$__EVENTS['SHMENU_DPC'][3]='menu2';

$__ACTIONS['SHMENU_DPC'][1]='menu';
$__ACTIONS['SHMENU_DPC'][2]='menu1';
$__ACTIONS['SHMENU_DPC'][3]='menu2';


$__LOCALE['SHMENU_DPC'][0]='SHMENU_CNF;Menu;Menu';	   

	   
class shmenu {

   var $path, $urlpath, $inpath, $menufile;
   var $delimiter;
	
   function __construct() {
	   $UserName = GetGlobal('UserName');	
	   $UserSecID = GetGlobal('UserSecID');
	   $UserID = GetGlobal('UserID');		
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	   $this->username = decode($UserName);
	   $this->userid = decode($UserID);
	   
       $this->path = paramload('SHELL','prpath');	   
	   
	   $this->urlpath = paramload('SHELL','urlpath');
	   $this->inpath = paramload('ID','hostinpath');	
	  
       $this->menufile = $this->path . 'menu.ini';
	   $this->delimiter = ',';
   }
   

   function event($event=null) {
   
       switch ($event) {

		 case 'menu'          :	
		 default              : 						
	   }
   }
   

   function action($action=null) {

       switch ($action) {

		 case 'menu'          :						
		 default              : 
	   }
	   
	   return ($out);
   }   
   			

   function render($menu_template=null,$glue_tag=null,$glue_end_tag=null) {
        $lan = getlocal() ? getlocal() : '0';
   
        //echo $this->menufile;
		if (is_readable($this->menufile))
          $m = parse_ini_file($this->menufile,1);
		  
		//print_r($m);
		foreach ($m as $menu_item) {
		
          //menu items		
		  if (isset($menu_item['title'])) { 		
		  
		    $title = explode($this->delimiter ,$menu_item['title']);
		    $link = explode($this->delimiter ,$menu_item['link']); 		  
		  
		    //spaces before and after title
		    $spaces = explode($this->delimiter ,$menu_item['spaces']); 
		    if ($sp = $spaces[$lan]) 
		      $sps[$title[$lan].'-spaces'] = $sp;
		    else	
			  $sps[$title[$lan].'-spaces'] = 0;

		    //submenu
		    $submenu = explode($this->delimiter ,$menu_item['submenu']); 
		    if ($smenu = $submenu[$lan]) 
		      $smu[$title[$lan].'-submenu'] = $smenu;
		    else	
			  $smu[$title[$lan].'-submenu'] = null;
		
		    
			//set title / link
		    $menu[$title[$lan]] = $link[$lan];
		  }
		}
		
		//print_r($smu);
		//print_r($sps);
		//print_r($menu);
		if (!empty($menu)) {
		   $ret = null;
	       $mytemplate = $menu_template?$menu_template:'menu.html';
		   //echo '>',$mytemplate;	   
	       $tfile = $this->urlpath .'/' . $this->inpath . '/cp/html/'. $mytemplate ; 	

           if (is_readable($tfile)) {
                $tt = file_get_contents($tfile);
                foreach ($menu as $name=>$url) {
				    $murl = $url ? str_replace('@','?t=',$url) : '#';
					
					if ($space_count = $sps[$name.'-spaces']) {
					  $name_space = str_repeat('&nbsp;', $space_count) . $name . str_repeat('&nbsp;', $space_count);
					  //echo $name.'-spaces>',$name_space,'>',$space_count,'<br>';
					}  
					else  
					  $name_space = $name;  	
                    
                    if ($sub_menu = $smu[$name.'-submenu']) {
					   //echo 'a',$sub_menu;
					   $_smenu = (array) $m[$sub_menu];
					   $ret2 = $this->render_submenu($_smenu,$menu_template, $glue_tag, $glue_end_tag);
					   //echo $ret2;
					   $ret .= str_replace('@SHMENU-SUBMENU@',$ret2,str_replace('@SHMENU-MENUTITLE@',$name_space,str_replace('@SHMENU-LINK@',$murl,$tt)));
                    } 					
					else
					   $ret .= str_replace('@SHMENU-SUBMENU@','',str_replace('@SHMENU-MENUTITLE@',$name_space,str_replace('@SHMENU-LINK@',$murl,$tt)));
				}	
           }
           else {
                foreach ($menu as $name=>$url) {
				    $murl = $url ? str_replace('@','?t=',$url) : '#';
					if ($space_count = $sp[$name.'-spaces'])
					  $name_space = str_repeat('&nbsp;', $space_count) . $name . str_repeat('&nbsp;', $space_count);
					else  
					  $name_space = $name;  					
                    $ret .= "<a href='$murl'>$name_space</a>&nbsp;";

                    if ($sub_menu = $smu[$name.'-submenu']) {
					   //echo 'a',$sub_menu;
					   $_smenu = (array) $m[$sub_menu];
					   $ret .= $this->render_submenu($_smenu,$menu_template, $glue_tag, $glue_end_tag);
                    }					
				}	
		   }	     
		   //echo $ret;
		   return ($ret);
		}
   }
   
   function render_submenu($smenu=null,$template=null,$glue_tag=null, $glue_end_tag=null) {
        $lan = getlocal() ? getlocal() : '0';
        if (empty($smenu))
		   return;
		   
		foreach ($smenu as $m=>$v) {
		    $cv = explode($this->delimiter ,$v);
		
		    if (strstr($m,'title'))
			   $subm_titles[] = $cv[$lan];
			elseif (strstr($m,'link'))
			   $subm_links[] = $cv[$lan];
		}		   
		   
		//echo '>',$smenu;   
		//echo '<pre>';
		//print_r($subm_titles);  
		//print_r($subm_links);
		//echo '</pre>';
		
        $ret = null;
	    $mytemplate = $template?$template:'menu.html';
		//echo '>',$mytemplate;	   
	    $tfile = $this->urlpath .'/' . $this->inpath . '/cp/html/'. $mytemplate ;				
		
        if (is_readable($tfile)) {
            $tt = file_get_contents($tfile);	
		    foreach ($subm_titles as $t=>$title) {
			   $ret .= str_replace('@SHMENU-SUBMENU@','',str_replace('@SHMENU-MENUTITLE@',$title,str_replace('@SHMENU-LINK@',$subm_links[$t],$tt)));	
		    }			
		}
        else {
		    foreach ($subm_titles as $t=>$title) {
			   $ret .= "<a href='$subm_links[$t]'>$title</a>&nbsp;";
		    }		
        }		
		
		$gstart = '<'.$glue_tag.'>';
		$gend = $glue_end_tag ? '</'.$glue_end_tag.'>' :'</'.$glue_tag.'>';
		$out = $gstart . $ret . $gend;
		return ($out);
   }
   
};
}
?>