<?php

$__DPCSEC['SHSLIDESHOW_DPC']='1;1;1;2;2;2;2;2;9';

if ((!defined("SHSLIDESHOW_DPC")) && (seclevel('SHSLIDESHOW_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SHSLIDESHOW_DPC",true);

$__DPC['SHSLIDESHOW_DPC'] = 'shslideshow';


$__EVENTS['SHSLIDESHOW_DPC'][1]='slideshow';
$__EVENTS['SHSLIDESHOW_DPC'][2]='slideshow1';
$__EVENTS['SHSLIDESHOW_DPC'][3]='slideshow2';

$__ACTIONS['SHSLIDESHOW_DPC'][1]='slideshow';
$__ACTIONS['SHSLIDESHOW_DPC'][2]='slideshow1';
$__ACTIONS['SHSLIDESHOW_DPC'][3]='slideshow2';


$__LOCALE['SHSLIDESHOW_DPC'][0]='SHSLIDESHOW_CNF;Slideshow;Slideshow';	   

	   
class shslideshow {

   var $path, $urlpath, $inpath, $menufile;
   var $delimiter;
   var $control_buttons;
	
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
	  
       $this->menufile = $this->path . 'slideshow.ini';
	   $this->delimiter = ',';
	   
	   $this->control_buttons = remote_paramload('SLIDESHOW','controls', $this->path);
	   
	   //javascript call
       $this->javascript();	   
   }
   

   function event($event=null) {
   
       switch ($event) {

		 case 'slideshow'     :	
		 default              :  						
	   }
   }
   

   function action($action=null) {

       switch ($action) {

		 case 'slideshow'     :						
		 default              : 
	   }
	   
	   return ($out);
   }   
   			

   function render($menu_template=null,$glue_tag=null) {
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
		    $image = explode($this->delimiter ,$menu_item['image']); 
			$alt = explode($this->delimiter ,$menu_item['alt']);
			$text = explode($this->delimiter ,$menu_item['text']);
			$bg = explode($this->delimiter ,$menu_item['bg']);


		    //$menu[$title[$lan]] = array($link[$lan];
			$slideshow[] = array('title'=>$title[$lan],
			                     'link'=>$link[$lan],
								 'image'=>$image[$lan],
								 'alt'=>$alt[$lan],
								 'text'=>$text[$lan],
								 'bg'=>$bg[$lan],
								 );
		  }
		}
		

		//print_r($slideshow);
		if (!empty($slideshow)) {
		   $ret = null;
	       $mytemplate = $menu_template?$menu_template:'slideshow.html';
		   //echo '>',$mytemplate;	   
	       $tfile = $this->urlpath .'/' . $this->inpath . '/cp/html/'. $mytemplate ; 	

           /*if (is_readable($tfile)) {
                $tt = file_get_contents($tfile);
                foreach ($menu as $name=>$url) {
				    $murl = $url ? str_replace('@','?t=',$url) : '#';
					$name_space = $name;  
				    $ret .= str_replace('@SUBMENU@','',str_replace('@TITLE@',$name_space,str_replace('@LINK@',$murl,$tt)));
				}	
           }
           else {*/
		        $sret = null;
                foreach ($slideshow as $i=>$slideparams) {
				
				    if (!empty($slideparams)) {
				      $url = $slideparams['link'] ? str_replace('@','?t=',$slideparams['link']) : '#';  

					  $sret .= "
				<div class=\"slide\">
					<div class=\"slide-inner\">
						<a href=\"$url\" class=\"fpss_img\">
							<span>
								<span style=\"background:url(".$slideparams['bg'].") no-repeat;\">
									<span>
										<img src=\"".$slideparams['image']."\" alt=\"".$slideparams['alt']."\" />
									</span>
								</span>
							</span>
						</a>
						<div class=\"fpss-introtext\">
						<div class=\"slidetext\">
						".$slideparams['text']."
						</div>
						</div>
				    </div>
				</div>					  
					  ";
					}
				}	
		   //}	

           $ret = "
<div id=\"fpss-outer-container\">
	<div id=\"fpss-container\">		   
		<div id=\"fpss-slider\">
			<div id=\"slide-wrapper\">
				<div id=\"slide-outer\">		   
		   ";
		   
		   $ret .= $sret;
		   
		   $ret .= "
				</div>
			</div>
		</div>
		<div id=\"navi-outer\">
			<div id=\"pseudobox\"></div>
				<div class=\"ul_container\">
					<ul>		
		   ";
		   
		   $sret2 = null;
           foreach ($slideshow as $i=>$slideparams) {
				
				    if (!empty($slideparams)) {
                      $sret2 .= "
						<li>
							<a class=\"navi\" href=\"#\" title=\"".$slideparams['title']."\">
								<span class=\"navbar-img\">
									<img src=\"".$slideparams['image']."\" alt=\"".$slideparams['alt']."\" />
								</span>
								<span class=\"navbar-key\">$i</span>
								<span class=\"navbar-title\">".$slideparams['title']."</span>
								<span class=\"navbar-tagline\">".$slideparams['title']."</span>
								<span class=\"navbar-clr\"></span>
							</a>
						</li>					  
                      "; 					  
		            }
		   }
		   
		   $ret .= $sret2;
		   
		   if ($this->control_buttons)
		        $ret = "
						<li class=\"noimages\"><a id=\"fpss-container_next\" href=\"#\" title=\"Next\"></a></li>
						<li class=\"noimages\"><a id=\"fpss-container_playButton\" href=\"#\" title=\"Play/Pause Slide\">Pause</a></li>
						<li class=\"noimages\"><a id=\"fpss-container_prev\" href=\"#\" title=\"Previous\"></a></li>		   
		   ";
		   
		   $ret .= "
					</ul>
				</div>
			</div>
		<div class=\"fpss-clr\"></div>
	</div>
	<div class=\"fpss-clr\"></div>
</div>		   
		   ";
		   
		   //echo $ret;
		   return ($ret);
		}
   }
   
   function javascript() {
   
        $jsapi = "http://www.google.com/jsapi";
   
        $jquery = "google.load(\"jquery\", \"1.6.2\");";   
   
 	    $jscode = "
    //<![CDATA[
	jQuery.noConflict();
	jQuery(document).ready(function($){
		$('.fpssModuleContainer').frontpageSlideshow({
			'crossFadeDelay':6000,
			'crossFadeSpeed':1000,
			'loaderDelay':800,
			'navEvent':'click',
			'autoslide':true,
			'textPlay':'Play',
			'textPause':'Pause'
		});
	});
	//]]>		
		";  
   
      if (iniload('JAVASCRIPT')) {	
	  	
		   $js = new jscript;	
		   $js->load_js('http://www.google.com/jsapi',"",null,null,1);
		   $js->load_js($jquery,"",1);
		   $js->load_js('fpss.packed.js');
           $js->load_js($jscode,"",1);			   
		   unset ($js);
	  }   
       
   
   /*
<!-- JoomlaWorks "Frontpage Slideshow (standalone)" (v2.4) starts here -->
<!--script type="text/javascript">
	//<![CDATA[
	document.write('<style type="text/css" media="all">@import "http://www.wayoflife.gr/template_css.php?w=774&h=300&sw=200";</style>');
	//]]>
</script-->
<!-- Load jQuery remotely -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("jquery", "1.6.2");</script>
<script type="text/javascript" src="http://www.stereobit.gr/fpss/includes/js/fpss.packed.js"></script>
<script type="text/javascript">
	//<![CDATA[
	jQuery.noConflict();
	jQuery(document).ready(function($){
		$('.fpssModuleContainer').frontpageSlideshow({
			'crossFadeDelay':6000,
			'crossFadeSpeed':1000,
			'loaderDelay':800,
			'navEvent':'click',
			'autoslide':true,
			'textPlay':'Play',
			'textPause':'Pause'
		});
	});
	//]]>
</script>

<!-- JoomlaWorks "Frontpage Slideshow (standalone)" (v2.4) ends here -->   
*/

        return ($jsret);
   }
   
   
};
}
?>