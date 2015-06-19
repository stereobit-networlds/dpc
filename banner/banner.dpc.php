<?php

$__DPCSEC['BANNER_DPC']='1;1;1;1;1;1;1;1;9';
$__DPCSEC['ADMBNR_']='2;1;1;1;1;1;1;2;9';

if ((!defined("BANNER_DPC")) && (seclevel('BANNER_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("BANNER_DPC",true);

$__DPC['BANNER_DPC'] = 'banner';

$__PARSECOM['BANNER_DPC']['rotate']='_ROTATEBANNER_';

///
// Simple banner rotator.
// - put banners in specified directory, then just use the script by calling BannerR::rotate();
// - handles only unified size banners (more precisely, it just doesn't care).
// - Apache needs write permissions to directory where BANNERLIST resides.
//
// (c) 2002, Berkus
///


//define("BANNERDIR", "/");                   // dir where banners live
//define("BANNERLIST", BANNERDIR."banners.rotator"); // bannerlist filename

class banner
{
   
   var $bpath;
   var $blist;
   var $path;
   var $url;
   
   //add by be
   function banner() {

      $this->path = paramload('SHELL','prpath');
      $this->url = paramload('SHELL','urlbase');
	  $this->bpath = paramload('BANNER','path'); 
	  $this->blist = $this->path . paramload('BANNER','listfile'); 
   }

   // Rotate banners in specified directory.
   function rotate($x=140,$y=50) {
 
      // Try and open banner list.
      if (file_exists($this->blist)) {//echo "$this->blist>>>";
         $items = file($this->blist);
      } else {
         $items = array();
      }

      if (count($items) < 1) // no more banners to show, start over
      { 
         if ($handle = opendir($this->path."/".$this->bpath)) {//echo $this->path,"/",$this->bpath,"...";
             while (false !== ($file = readdir($handle))) { 
               if ($file != "." && $file != ".." && $file != basename($this->blist)) {
                  $items[] = $file;
               } 
             }
            closedir($handle);
         }
         shuffle($items);
      }

      // pick up first banner from list
      $banner = trim(array_shift($items));

      // write banner list back
      $f = fopen($this->blist, "w");
      if (!$f) { error_log("Cannot write banner list.", 0); return $this->blist.$banner; }
      foreach ($items as $i)
         if( $i)
            fputs($f, trim($i)."\n");
      fclose($f);
	  
	  //echo "$this->bpath.$banner>>>>";

      //return $this->bpath.$banner;
	  $out = "<table><tr><td><!-- BANNER --><img src=" . $this->bpath.$banner . " width=\"$x\" height=\"$y\" alt=\"BANNER\" border=\"0\"><!-- /BANNER --></td></tr></table>";

	  return ($out);
   }

}
}
?>