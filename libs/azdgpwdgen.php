<?php
//
///////////////////////////////////////////////////////
// Small AzDGPasswordGenerator class (you may reset this comments)
// Questions: (AzDG Support) <support@azdg.com>
///////////
// Purposes:
// Generate passwords
// *Use chars A-Z,a-z,0-9 and special symbols "_" and "-"
///////////
// Example N 1:
///////////////////////////////////////////////////////
// require_once("AzDGPasswordGenerator.class.inc.php");
// $gen = new G(10, 16); // 10,16 - min and max chars
// $sd2 = $gen->getwww();
// echo $sd2;
// echo "<br>";
///////////
// Example N 2:
///////////////////////////////////////////////////////
// $chararray = array('A','z','D','G'); // Symbols in password
///////////////////////////////////////////////////////
// $gen = new G(20, 20, $chararray);
// $sd = $gen->getwww();
// echo $sd;
// echo "<br>";
///////////////////////////////////////////////////////

class G
{
	var $wc;
	var $w;
	var $l;
	var $minl;
	var $maxl;

	function G($min, $max, $chararray=NULL)
	{
		if($chararray == NULL)
		{
			$this->wc = array('_','-'); // special chars
			for($i=48; $i<58; $i++)
			{
				array_push($this->wc, chr($i)); // 0-9
			}
			for($i=65; $i<91; $i++)
            {
				array_push($this->wc, chr($i)); // A-Z
            }    
			for($i=97; $i<122; $i++)
            {
				array_push($this->wc, chr($i)); // a-z
            }    
			shuffle($this->wc);
		}
		else
		{ $this->wc = $chararray; }

		$this->minl = $min;
		$this->maxl = $max;
	}

    
	function setl()
	{ $this->l = rand($this->minl, $this->maxl); }

	function setMin($min)
	{ $this->minl = $min; }

	function setMax($max)
	{ $this->maxl = $max; }

	function getw()
	{
		$this->w = NULL; 
		$this->setl();

		for($i=0; $i<$this->l; $i++)
		{
			$charnum = rand(0, count($this->wc));
			$this->w .= $this->wc[$charnum];
		}

		return $this->w; 
	}

	function getwww()
	{
		return (htmlentities($this->getw()));
	}
}
?>