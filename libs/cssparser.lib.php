<?php
/*

 this class parses CSS Files/Strings and returns associative array
 containing all css parameters. 
 it can also be called to generate css from associative array

@version        0.1

@example

    $oCSS=new CSS();
    $oCSS->parseFile("style.css");
    echo $oCSS["body"]["font-family"];
    $oCSS->css["body"]["background-image"]="url(background2.gif)";
    $newcss_string=$oCSS->buildcss();

@author     Mike Ettl(michael@ettl.com)

*/

class CSS
{

# associative array containing css-tags $this->CSS["body"]["background-color"] 
var $css;

# array containing all css-tags 
var $csstags;

# CSS String
var $cssstr;

	function CSS() {
	/* Init */
		$this->css="";
		$this->csstags="";
		$this->cssstr="";
	}

    function parseFile($filename)  {
	/* Open File and Parse it */
    	$fp=fopen($filename,"r") or die("Error opening file $filename");
    	$css_str = fread($fp, filesize ($filename));
    	fclose($fp);
		return($this->parse($css_str));
    }
	
	function parse($css_str) {
	/* Parse CSS to Array */
		$this->cssstr=$css_str;
		$this->css="";
		$this->csstags="";
    	$css_str=preg_replace("/[\s]+/","",$css_str);
    	$css_class = explode("}", $css_str);

    	while (list($key,$val) = each ($css_class))
    	{
    	    $aCSSObj=explode("{",$val);
			if ($aCSSObj[0]) {
    			$this->csstags[]=$aCSSObj[0];
#    			echo "<pre>".$aCSSObj[0]."</pre>\n\n";
        	    $a=explode(";",$aCSSObj[1]);
        	    while(list($key,$val0) = each ($a))
        	    {
        		  if($val0)
        		  {
#    	 		   echo "<pre>\t$key:$val0</pre>\n";		  
        	       $aCSSSub=explode(":",$val0);
        	       $aCSSItem[$aCSSSub[0]]=$aCSSSub[1];
        	      }
        	    }
        	    $this->css[$aCSSObj[0]]=$aCSSItem;
        	    unset($aCSSItem);
			}
			if (strstr($aCSSObj[0],",")) {
				/* there is a comma - duplicate tag name and delete original tag */
				$aTags=explode(",",$aCSSObj[0]);
				foreach($aTags as $key0 => $value0) {
					$this->css[$value0]=$this->css[$aCSSObj[0]];
				}
				unset($this->css[$aCSSObj[0]]);
			}				
    	} 
    	unset($css_str,$css_class,$aCSSSub,$aCSSItem,$aCSSObj);
    	return $this->css;
    }

	function buildcss() {
	/* Builds CSS on Base of Array */
		$this->cssstr="";
		foreach($this->css as $key0 => $value0) {
			$this->cssstr .= "$key0 {\n";
			foreach($this->css[$key0] as $key1 => $value1) {
				$this->cssstr .= "\t$key1:$value1;\n";
			}
			$this->cssstr .="}\n";
		}
		return ($this->cssstr);
	}
}

?>