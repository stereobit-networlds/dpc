<?php
##############################################################################################################################################
##			information																														##
##############################################################################################################################################
##																																			##
##	description:		class mixer is for creation of a color mixer																		##
##	version:			1.0.0																												##
##	filename:			mixer.class.php																										##
##	author:				jürgen kaucher																										##
## 	author Email: 		juergen@kaucher.org																									##
##	created:			2002-09-25																											##
##	last modified:		2002-09-25																											##
##																																			##
##############################################################################################################################################
##			class mixer																														##
##############################################################################################################################################
##																																			##
class vgamixer {
	// (vga:16 colors)
	var $vga=array(
		'black'=>'000000',
		'grey'=>'808080',
		'maroon'=>'800000',
		'red'=>'FF0000',
		'green'=>'008000',
		'lime'=>'00FF00',
		'olive'=>'808000',
		'yellow'=>'FFFF00',
		'navy'=>'000080',
		'blue'=>'0000FF',
		'purple'=>'800080',
		'fuchsia'=>'FF00FF',
		'teal'=>'008080',
		'aqua'=>'00FFFF',
		'silver'=>'C0C0C0',
		'white'=>'FFFFFF');
		
	function vgamixer() {
	
       if (iniload('JAVASCRIPT')) {	
	       $code = $this->javascript();
	   
		   $js = new jscript;
           $js->load_js("$code","",1);	 
		   unset ($js);
	   }	
	}
	
	function render($value) {       

		$out = ("<form name=form action=\"\"><table border=2 width=300 align=center><tr>");
		
		$i=0;
		while (list ($key, $val) = each ($this->vga)) {
			if (($i=='5')||($i=='7')||($i=='13')||($i=='14')||($i=='15')) $col=$this->vga['black'];
			else $col=$this->vga['white'];
			$out .= "<td bgcolor=#".$val." width=100 align=center><font color=".$col.">#".$val."</font></td>";
			$out .= "<td bgcolor=#".$val." width=100 align=center><font color=".$col.">".$key."</font></td>";
			$out .= "<td bgcolor=#".$val." align=center><input type=button value='Set color' onClick=Set('".$val."')></td>";
			$out .= "</tr><tr>";
			$i++;
		}
		$out .= "</tr></table></form>";
		
		return ($out);
	}
	
	function javascript() {
     
	    $out = "<!--
		function Set(t) {
			document.getElementsByTagName(\"body\")[0].bgColor = t;
		}
		//-->";
		
		return ($out);	
	}
}
##																																			##
##############################################################################################################################################
?>