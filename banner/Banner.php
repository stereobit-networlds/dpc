<?php
///
// Simple banner rotator.
// - put banners in specified directory, then just use the script by calling BannerR::rotate();
// - handles only unified size banners (more precisely, it just doesn't care).
// - Apache needs write permissions to directory where BANNERLIST resides.
//
// (c) 2002, Berkus
///

define("BANNERDIR", "banners/");                   // dir where banners live
define("BANNERLIST", BANNERDIR."banners.rotator"); // bannerlist filename

class BannerR
{
   // Rotate banners in specified directory.
   function rotate()
   {
      // Try and open banner list.
      if (file_exists(BANNERLIST)) {
         $items = file(BANNERLIST);
      } else {
         $items = array();
      }

      if (count($items) < 1) // no more banners to show, start over
      { 
         if ($handle = opendir(BANNERDIR)) {
             while (false !== ($file = readdir($handle))) { 
               if ($file != "." && $file != ".." && $file != basename(BANNERLIST)) {
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
      $f = fopen(BANNERLIST, "w");
      if (!$f) { error_log("Cannot write banner list.", 0); return BANNERDIR.$banner; }
      foreach ($items as $i)
         if( $i)
            fputs($f, trim($i)."\n");
      fclose($f);

      return BANNERDIR.$banner;
   }
}

?>