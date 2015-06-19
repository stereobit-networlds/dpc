<?php
$__DPCSEC['PIECHART_DPC']='8;0;0;0;0;0;0;8;9';

if ((!defined("PIECHART_DPC")) && (seclevel('PIECHART_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("PIECHART_DPC",true);

$__DPC['PIECHART_DPC'] = 'piechart';


require_once "piechart.lib.php";
//***********************************************************************
//***************Written by Rahman Haqparast February 2003***************
//*******************the class for creating pie charts*******************
//***********************************************************************
class piechart extends chart
{
	var $radius;
	var $final;
	function piechart($r,$na,$el,$co)
	{
		$this->radius=$r;
		$this->elementnames=$na;
		$this->elements=$el;
		$this->colors=$co;
		$this->createimage();
	}
	function createimage()
	{
		$this->calculate();
		$r=$this->radius;
		$image=imagecreate($r*3,$r*2);
		$white=imagecolorallocate($image,255,255,255);
		$black=imagecolorallocate($image,0,0,0);
		for ($k=0;$k<count($this->colors);$k++)
		{
			$fillcolor[$k]=imagecolorallocate($image,$this->colornames[$this->colors[$k]][0],$this->colornames[$this->colors[$k]][1],$this->colornames[$this->colors[$k]][2]);
		}
		imagearc($image,$r,$r,$r*2-1,$r*2-1,0,360,$black);
		for ($j=0;$j<count($this->elements);$j++)
		{
			$degree+=360*$this->fractions[$j];
			imageline($image,$r,$r,$r+$r*cos($degree*pi()/180),$r+$r*sin($degree*pi()/180),$black);
			imagefill($image,$r+15*cos(($degree+5)*pi()/180),$r+15*sin(($degree+5)*pi()/180),$fillcolor[$j]);
			imagefilledrectangle($image,2.1*$r,.7*$r+($r/15)*$j,2.12*$r+($r/25),.7*$r+5+($r/15)*$j,$fillcolor[$j]);
			imagestring($image,0,2.13*$r+$r/20,.71*$r+($r/15)*$j-2,$this->elements[$j]."-".$this->elementnames[$j],$black);
		}	
			$this->final=$image;
	}
	function draw()
	{
			imagejpeg($this->final);
	}
	function out($filename,$quality)
	{
			imagejpeg($this->final,$filename,$quality);
	}
}
?>
