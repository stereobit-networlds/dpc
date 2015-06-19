<?php
/************************************************************************/
/* ivweather.class.php                                                  */
/* ===========================                                          */
/* Copyright (c) 2003 by David Rolston  (gizmo@gizmola.com)             */
/* http://www.gizmola.com                                               */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
// IVWeather.class.php retrieves free current and forecast weather      //
// information via xml from interceptvector.com, loads this data into   //
// an object and provides a few helpful member functions.               //
// See test_ivweather.php for examples of how to access the member      //
// variables and call the member functions.  See comments in class.		//
//																		//
// For information about the xml weather feeds,                         //
// visit http://weather.interceptvector.com  //                         //
//                                                                      //
// This class is meant to be self contained and does not require        //
// anything besides standard php functionality. It does not use php's   //
// xml parsing libraries.                                               //
// To use, in your script require ivweather.class.php and Instantiate   //
// an IVWeather object:    $ivw = new IVWeather("URL");                 //
// For a list of URL's see http://weather.interceptvector.com/list.php  //
//																		//
// Questions or comments can be left at http://forum.gizmola.com    	//
/************************************************************************/

define("UNDEF", "undef");
//class Def
class IVWeather {
	// Properties
	var $weatherurl = "http://weather.interceptvector.com/weather.xml?id=VVNDQTEyMjU%3D";
	
	// Basic information identifying the location of the weather information
	var $rWeatherLocale = array("city" => UNDEF, "state" => UNDEF, "country" => UNDEF, "region" => UNDEF);
	//Future use
	//var $rDate = array("dt" => 0, "dtdate" => 0, "dttime" => 0);
	
	// Datetime of the weather information.  All dates appear to be EDT.  
	var $wdate;
	
	// Current temperature information.  Defaults to Fahrenheit.  See interceptvector for method of passing param to 
	// receive temp in celsius
	var $rTemp = array("temp" => 0, "realtemp", "tempscale" => UNDEF);
	
	var $rWind = array("direction" => UNDEF, "strength" => 0);
	var $barometer;
	var $humidity;
	var $uv;
	var $visibility;
	var $wid;
	// wid(weather id) corresponds to the weather description in sky.  
	// 
	var $sky;
	
	// Usually there are 5 days of forecasts.  
	// array("date" => UNDEF, "day" => UNDEF, "high" => UNDEF,
	//       "low" => UNDEF, "wid" => UNDEF, "sky" => UNDEF, "precipitation" => UNDEF); 
	var $rForecast = array();
	var $fccnt = -1;
	
	// Providers is an array of information allowing acknowledgement of the providers
	//	array("name" => UNDEF, "url" => UNDEF, "logo" => UNDEF);
	var $rProvider = array();
	var	$provcnt = -1;
	

//methods
//IVWeather constructor.  Pass a valid IVWeather URL
//
	function IVWeather($pWeatherURL) {
	
		function gettag($pTagline) {			
			//$lrTag = array();
			$pTagLine = trim($pTagline);
			$lt = substr(strstr($pTagline, "<"),1);
			$lt = substr($lt,0,strpos($lt,">"));
			$rlt = explode(" ", $lt);
			$lt = $rlt[0];
			return $lt;
		}
		
		function gettagvalue($pTag, $pTagline) {
			$pTagLine = trim($pTagline);
			$ltgend = "</" . $pTag . ">";
			$e = strpos($pTagline, $ltgend);
			if ($e!==false) {
				$ltgstart = "<" . $pTag . ">";						
				$ltgsz = strlen($ltgstart);		
				$ltspos = strpos($pTagline, $ltgstart);	
				$lt = substr($pTagline, $ltspos+$ltgsz, (strpos($pTagline, $ltgend)-1) - $ltgsz - $ltspos + 1);		
			} else {
				$lt="";
			}
			return $lt;
		}
		
		function gettagparmvalue($pParm, $pTagline) {
			$pTagLine = trim($pTagline);
			$e = strpos($pTagline, $pParm);
			if ($e!==false) {
				if (ereg("^.+$pParm=\"(.+)\"", $pTagline, $arr)) {
					$lt = $arr[1];
				}	
			} else {
				$lt="";
			}
			return $lt;
		}
		
		$lrFile = array();
		
		$this->weatherurl = $pWeatherURL;
		if (!$lhandle = fopen($this->weatherurl, "R")) {
			$this->$err = 1;
			$this->$errmsg = "Unable to open weather url";
			return 1;
		} else {			
			while (!feof ($lhandle)) {
				$lbuffer = fgets($lhandle, 4096);
	  			$lrFile[] = $lbuffer;
			}
			fclose($lhandle);
		}
		// 
	
		for ($i=0; $i<count($lrFile); $i++) {
			$ltag = gettag($lrFile[$i]);
			switch ($ltag) {
				case "provider" :
					$this->provcnt++;	
					$this->rProvider[] = array("name" => UNDEF, "url" => UNDEF, "logo" => UNDEF);
					break;
				case "forecast" :
					$this->fccnt++;
					$this->rForecast[] = array("date" => gettagparmvalue("date",$lrFile[$i]), "day" => UNDEF, "high" => UNDEF, "low" => UNDEF, "wid" => UNDEF, "sky" => UNDEF, "precipitation" => UNDEF); 
					break;
				case "city" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rWeatherLocale["city"]=$lval;
					break;
				case "state" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rWeatherLocale["state"]=$lval;
					break;
				case "country" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rWeatherLocale["country"]=$lval;
					break;
				case "region" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rWeatherLocale["region"]=$lval;
					break;
				case "date" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->wdate=$lval;
					break;
				case "realtemp" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rTemp["realtemp"]=$lval;
					break;	
				case "temp" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rTemp["temp"]=$lval;
					break;
				case "tempScale" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rTemp["tempscale"]=$lval;
					break;
				case "direction" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rWind["direction"]=$lval;
					break;				
				case "strength" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rWind["strength"]=$lval;
					break;									
				case "barometer" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->barometer=$lval;
					break;						
				case "humidity" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->humidity=$lval;
					break;						
				case "uv" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->uv=$lval;
					break;		
				case "visibility" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->visibility=$lval;
					break;									
				case "wid" :
					if ($this->fccnt < 0 ) {
						$lval = gettagvalue($ltag, $lrFile[$i]);
						$this->wid=$lval;
					} else {
						$lval = gettagvalue($ltag, $lrFile[$i]);
						$this->rForecast[$this->fccnt]["wid"]=$lval;
					}
					break;				
				case "sky" :
					if ($this->fccnt < 0 ) {
						$lval = gettagvalue($ltag, $lrFile[$i]);
						$this->sky=$lval;
					} else {
						$lval = gettagvalue($ltag, $lrFile[$i]);
						$this->rForecast[$this->fccnt]["sky"]=$lval;
					}
					break;	
				case "name" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rProvider[$this->provcnt]["name"]=$lval;
					break;	
				case "url" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rProvider[$this->provcnt]["url"]=$lval;
					break;	
				case "logo" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rProvider[$this->provcnt]["logo"]=$lval;
					break;
				case "day" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rForecast[$this->fccnt]["day"]=$lval;
					break;			
				case "high" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rForecast[$this->fccnt]["high"]=$lval;
					break;		
				case "low" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rForecast[$this->fccnt]["low"]=$lval;
					break;		
				case "precipitation" :
					$lval = gettagvalue($ltag, $lrFile[$i]);
					$this->rForecast[$this->fccnt]["precipitation"]=$lval;
					break;						
			}
		}
		return 0;
		
	}
	// There can be a variable number of Forecasts
	// Use this 
	function getForeCastCount() {
		return $this->fccnt+1;
	}
	
	function getProviderCount() {
		return $this->provcnt+1;
	}
	
	function getWinddecode() {
		$pt = $this->rWind["direction"];
		for ($i=0; $i < strlen($pt); $i++) {
			if ($i > 0) {
				$t = $t . " ";
			}
			switch ($pt[$i]) {
				case "N" :  
					$t = $t . "North";
					break;
				case "S" :
					$t = $t . "South";
					break;
				case "E" :
					$t = $t . "East";
					break;
				case "W" : 
					$t = $t . "West";
					break;
			}			
		}		
		return $t;
	}
	
//
} // end class IVWeather
?>
