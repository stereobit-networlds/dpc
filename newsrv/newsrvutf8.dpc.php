<?php
$__DPCSEC['NEWSRVUTF8_DPC']='1;1;1;1;1;1;1;1;1';
$__DPCSEC['NEWSRVUTF8_UPLOAD']='2;1;1;1;1;1;1;1;2';

if ( (!defined("NEWSRVUTF8_DPC")) && (seclevel('NEWSRVUTF8_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("NEWSRVUTF8_DPC",true);

$__DPC['NEWSRVUTF8_DPC'] = 'newsrvutf8';

$a = GetGlobal('controller')->require_dpc('newsrv/newsrv.dpc.php');
require_once($a);

GetGlobal('controller')->get_parent('NEWSRV_DPC','NEWSRVUTF8_DPC');

$__EVENTS['NEWSRVUTF8_DPC'][0]='newsrvutf8';
//$__EVENTS['NEWSRVUTF8_DPC'][1]='news';
//$__EVENTS['NEWSRVUTF8_DPC'][2]='upload2srv';

$__ACTIONS['NEWSRVUTF8_DPC'][0]='newsrvutf8';
//$__ACTIONS['NEWSRVUTF8_DPC'][1]='news';
//$__ACTIONS['NEWSRVUTF8_DPC'][2]='upload2srv';
//$__ACTIONS['NEWSRVUTF8_DPC'][3]='index';//dummy

$__LOCALE['NEWSRVUTF8_DPC'][0]='NEWSRVUTF8_DPC;News;Νεα;';
$__LOCALE['NEWSRVUTF8_DPC'][1]='_MORE;More;Περισσότερα;';

//$__PARSECOM['NEWSRV_DPC']['render']='_NEWS_';


class newsrvutf8 extends newsrv {

    function newsrvutf8() {
  
      newsrv::newsrv();
    }
  
    function event($evn=null) {

       switch ($evn) {		
          case "newsrvutf8" : $this->readnews($this->nbuffer,$this->preordered);
		                      break;	
          case "news"       : break;							  
          case "upload2srv" : $this->upload_file(); 
		                      //$this->read();
							  break;	
							  

       }							  	  	  	   
	}	

	function action($action=null) {
	
	   $out = setNavigator(localize("NEWSRVUTF8_DPC",getlocal()));

       switch ($action) {		
          case "newsrvutf8" : $out .= $this->shownews($this->nbuffer);				 	  
          case "news"       : break;	
          case "upload2srv" : break;		 	  
       }	   
	   
	   return ($out);
	}  
	
	//override
	//the title of new is in a txt file in format title@text or must be utf8 html...
	function readnews($nbuffer=null) {
	   $this->newsfiles = array();
	   $this->newstimes = array();	   
	   $this->photofiles = array();	 
	   $this->newstitles = array();  
       //echo $this->path;
	   
	   if (is_dir($this->path)) {
	   
         $d = @opendir($this->path);
		 $meter = 0; 
         while($file = @readdir($d)) {   
            if ($file != "." && $file != "..") {
			  //echo $file;
		      //if (!is_dir($this->path.$file)) { 
			 foreach ($this->extensions as $num=>$ext) { 
			  if (strstr($file,$ext)) {
			  
			    $s = stat($this->path.$file); 
				$localeparts = explode("_",$file);
				$localefile = $localeparts[0];
				$locale = $localeparts[1];
				$showparts = explode("-",$localefile);

				if (($locale==getlocal()) || (!isset($locale))) {
				  //print_r($d);echo "<br>";
				  //$date = date("Ymd",$d[9]); echo $date;
				  $ss = $s[9]; //echo $ss;
				  $timein = strval(time() - $ss); //echo $timein;
				  $this->newstimes[$ss] = $timein;
			      $this->newsfiles[$ss] = $file;
				  
				  /*if ($showparts[1]) 
				    $this->newstitles[$ss] = str_replace($ext,"",$showparts[1]);
				  else 
				    $this->newstitles[$ss] = str_replace($ext,"",$showparts[0]);				  
				  */	
				  $parts = explode('<@>',file_get_contents($this->path.$file));
	              $this->newstitles[$ss]  = $parts[0];			  
				  
				  $pfile = $this->path.str_replace($ext,$this->iext,$localefile);				  
				  if (file_exists($pfile))
				    $this->photofiles[$ss]= str_replace($ext,$this->iext,$localefile);
					
   	              $meter+=1;  
				  //MUST ALL BE NAMED BY DATE ex.20050228-name.extension_lang
				  if (($this->preordered) &&	
				      ($nbuffer) && ($nbuffer<=$meter)) {
			  		  @closedir($d);
	                  krsort($this->newsfiles);	
					  //print_r($this->newsfiles);			  
					  return 0;
				  } //else..it reads alphabetically not by date..
			    }		
			  }	
			 } 		  
			}		   
		 }
		 @closedir($d);
	   }
	   krsort($this->newsfiles);	
	   //print_r($this->newsfiles);
	   //print_r($this->newstitles);
	}	
	
	//overide
	//get the second part of format name@text
	function shownews($nbuffer=null,$head=null,$class='',$trim=null) {
	
	   if (!$class) $class = 'group_article_body';

       if ($head) $out = setNavigator(localize("NEWSRV_DPC",getlocal()));
	   
	   $maxc = count($this->newsfiles);
	   
	   if ($nbuffer) $this->nbuffer = $nbuffer;   
	   //if ($maxc<$this->nbuffer) $this->nbuffer = $maxc;
	
	   //for ($i=0;$i<$this->nbuffer;$i++) {
	   $i=0;
	   if (is_array($this->newsfiles)) {
	   foreach ($this->newsfiles as $times=>$file) {
	   
	     $f = $this->path.$file; //echo $f;
		 
		 $parts = explode('<@>',file_get_contents($f));
	     $contents = $parts[1];//file_get_contents($f);

		 reset($this->extensions);
		 /*foreach ($this->extensions as $num=>$ext) {
		   if (stristr($file,$ext)) 
		     $title = str_replace($ext,'',$file);
			 //added locale '_locale' replace
			 $locext = '_'.getlocal();
			 $title = str_replace($locext,'',$title);
		 }  */
		 $title = seturl("t=newsrv#".$times,$this->newstitles[$times]);

		 $article = '<b>' . $title . "</b><br><br>" . 
		           '[<b>' . date($this->tstyle,$times) . '</b>] ';
		 if ($trim) {	
		    //echo $contents,"<br>";	   
			$article .= substr($contents,0,$trim). '...<br>';
			$article .= seturl('t=newsrv#'.$times,localize('_MORE',getlocal()));
		 }		   
		 else
		   $article .= $this->set_anchor($times) . $contents;
				   
		 $data[] = $article;		   
		 $attr[] = "left;99%";
		 
		 if ($p=$this->photofiles[$times]) {
		   //$url = $this->apppath . paramload('NEWSRV','dirname') . $p;
		   
		   $selected_lang = getlocal()?getlocal():0;
		   $news_file = $this->prpath . "public/" . paramload('NEWSRV','dirname') .$selected_lang."/".$p;
		   //echo $news_file,'>>>';
	       if (is_readable($news_file))
		     $url = $this->apppath . paramload('NEWSRV','dirname') ."_".$selected_lang."/".$p;	 
		   else	 
		     $url = $this->apppath . paramload('NEWSRV','dirname') . $p;
			 		   
		   $data[] = "<img src=\"$url\">";// width=\"100\" height=\"100\" alt=\"\">";
		   $attr[] = "right;1%";
		 } 		 
				 
	     $mynews = new window(null,$data,$attr);
	     $out .= $mynews->render(" ::100%::0::$class::left;100%;::");	   	 	
		 unset ($mynews);
		 unset ($data); unset ($attr);
		 
		 $out .= "<hr>";
		 $i+=1;		 
		 if (($nbuffer) && ($i>=$nbuffer)) break;//local call
		 elseif ($i>$this->nbuffer) break;//global call
		 //else echo '>',$i; 
	   }
	   }
	   return ($out);
	}	

};
}
?>