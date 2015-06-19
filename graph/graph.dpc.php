<?php

$__DPCSEC['GRAPH_DPC']='8;0;0;0;0;0;0;8;9';

if ((!defined("GRAPH_DPC")) && (seclevel('GRAPH_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("GRAPH_DPC",true);

$__DPC['GRAPH_DPC'] = 'graph';

//
// Por Ricardo Costa - ricardo.community@globo.com - 2002
// Classe para exibi��o de gr�fico em linhas
//
//  graph
//  Propriedades
//    +---- lines    // Array com as linhas
//    +---- max      // Maior ponto do gr�fico
//    +---- cols     // N�mero de Colunas
//  Fun��es
//    +---- addLine()  // Adiciona uma linha a lines
//    +---- show()     // Exibe o gr�fico
//
//
      

class graph {

   var $lines = Array();
   var $max = 0;
   var $cols = 0;
   
   # Exibe o gr�fico ########################################################################## Revis�o 28/11/2002 #
   function show($size = 1) {
	 
	  # Tamanho do gr�fico
      $realWidth = 500 * $size;
      $realHeight = 260 * $size;
      $imgWidth = 440 * $size;
      $imgHeight = 210 * $size;
      $margin = 30 * $size;

      $sepV = $imgWidth/($this->cols - 1);
	  $sepH = $this->max/10;
      # Tamanho do gr�fico
 
      $image = ImageCreate($realWidth, $realHeight);

      # Cores
      $color0 = ImageColorAllocate($image, 75, 240, 75);
      $color1 = ImageColorAllocate($image, 75, 240, 75);
	  $color2 = ImageColorAllocate($image, 10, 10, 255);
      $color3 = ImageColorAllocate($image, 240, 75, 75);
      $color4 = ImageColorAllocate($image, 200, 10, 240);
      $color5 = ImageColorAllocate($image, 0, 0, 0);

      $white = ImageColorAllocate($image, 255, 255, 255);
      $gray = ImageColorAllocate($image, 80, 80, 80);
      $silver = ImageColorAllocate($image, 240, 240, 240);
      $msilver = ImageColorAllocate($image, 220, 220, 220);
      # Cores

      # Definindo a �rea ###############################################################################################
      ##################################################################################################################
      ImageColorTransparent($image ,$bg);
      ImageFill($image , 0, 0, $silver);
      Imagefilledrectangle ($image, $margin, $margin, $imgWidth + $margin - 1, $imgHeight + $margin - 1, $white);
      ImageRectangle($image ,$margin ,$margin ,$imgWidth + $margin - 1, $imgHeight + $margin - 1, $gray);
      ImageRectangle($image ,0 ,0 ,$realWidth - 1, $realHeight - 1, $gray);

      # Linhas Verticais
      $wid = $margin + $sepV - 1;
       
      ImageLine($image, $margin, $imgHeight + $margin - 5, $margin, $imgHeight + $margin + 5, $gray); 
      ImageLine($image, $imgWidth + $margin - 1, $imgHeight + $margin - 5, $imgWidth + $margin - 1, $imgHeight + $margin  + 5, $gray); 

      for ($c = 1; $c <= ($this->cols - 2); $c++)  {
         ImageLine($image, $wid, $margin + 1, $wid, $imgHeight + $margin, $msilver); 
         ImageLine($image, $wid, $imgHeight + $margin - 5, $wid, $imgHeight + $margin + 5, $gray); 
         $wid += $sepV;    
      }	  
      # Linhas Verticais
   
      # Linhas Horizontais
      $l = 0;
      $ll = 0;

	  while($l <= $imgHeight) {
         ImageLine($image, $margin - 5, $margin + $l, $imgWidth + $margin - 1, $margin + $l, $gray); 
         if (round($this->max - $ll) < 10)
            Imagestring($image, 2, 15, $margin + $l - 7, round($this->max - $ll), $gray); 
         elseif (round($this->max - $ll) < 100)
	   	    Imagestring($image, 2, 10, $margin + $l - 7, round($this->max - $ll), $gray); 
         else
		    Imagestring($image, 2, 5, $margin + $l - 7, round($this->max - $ll), $gray); 
		 
         $ll += $sepH;
		 $l += $imgHeight/10;
      }	  
      # Linhas Horizontais

      # Definindo a �rea ###############################################################################################
      ##################################################################################################################

      for ($lc = 0; $lc <= count($this->lines) - 1; $lc++) {
   	     $wid = $margin;
   	     # Linhas do Gr�fico
         for ($l = 0; $l <= ($this->cols - 2); $l++)  {
            $p2 = $imgHeight - round(($this->lines[$lc][$l + 1] * $imgHeight)/$this->max - $margin);
            $p1 = $imgHeight - round(($this->lines[$lc][$l] * $imgHeight)/$this->max - $margin);

			$color = "color".($lc + 1);
			
			ImageLine($image, $wid - 2, $p1, $wid + $sepV - 2, $p2, $$color); 
            Imagefilledrectangle($image, $wid + $sepV - 2, $p2 - 2, $wid + $sepV + 2, $p2 + 2, $$color); 
            $wid += $sepV;    
         }	  
         # Linhas do Gr�fico
      }

      header("Content-type: image/gif"); 
      Imagegif($image);
   }
   # Exibe o gr�fico ########################################################################## Revis�o 28/11/2002 #
	  
	  
	  
   # Adiciona uma linha ######################################################################### Revis�o 28/11/2002 #
   function addLine($points) {	  
      $npoints = $points;
	  sort($npoints);

	  $cols = count($npoints);
      $max = $npoints[$cols - 1];

	  if ($cols > $this->cols) $this->cols = $cols;
	  if ($max > $this->max) $this->max = $max;

 	  $this->lines[] = $points;
   }
   # Adiciona uma linha ######################################################################### Revis�o 28/11/2002 #


};
}
?>