<?php
// ivweather.class.php pulls free weather information from http://weather.interceptvector.com/
// Please visit that site and read about the service they provide.
// They gather weather information from around the world and provide it via xml feeds.
// 
// This example script illustrates some of the standard abilities of the class, namely
// getting weather information and making it available to your php script through the inclusion of
// ivweather.class.php, and instantiating an IVWeather object: $ivw = new IVWeather($uvw);
// You can pass a valid ivweather url to this script and display that location's weather 
// by setting the u parameter.  
// The test script is installed here: http://canyoncam.gizmola.com/test_ivweather.php
//
// For example: 
// http://canyoncam.gizmola.com/test_ivweather.php?u=http://weather.interceptvector.com/weather.xml?id=UlNYWDAxMTY%3D
// will display weather information for Vladivostok, Russia.  A listing of locations and their respective ivweather url's
// is available at http://weather.interceptvector.com/list.php.
//
// Note that state appears to be empty for non-US locations, and some information may not available depending 
// the location, or the current weather conditions.
//
// I make use in this demo of weather icon images from the interceptvector site, which correlate to the wid number.  
// If you intend to use these images, please copy them from the interceptvector page rather than referencing them
// directly as I've done in this test script.  Interceptvector has nice images in two sizes.
// Questions or comments can be left for me at http://forum.gizmola.com    	
// 

$__DPCSEC['IVWEATHER_DPC']='1;1;1;1;1;1;1;1;1';

if ( (!defined("IVWEATHER_DPC")) && (seclevel('IVWEATHER_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("IVWEATHER_DPC",true);

$__DPC['IVWEATHER_DPC'] = 'weather';

$__EVENTS['IVWEATHER_DPC'][0]='ivw';

$__ACTIONS['IVWEATHER_DPC'][0]='ivw';


//require_once("ivweather.lib.php");
//GetGlobal('controller')->include_dpc('webserv/ivweather.lib.php');
require_once(GetGlobal('controller')->require_dpc('webserv/ivweather.lib.php'));

class weather {

    var $ret;
	var $uvw;
	var $city;
	var $link;
	var $reqst;

	function weather() {
	  $this->ret = "IV Weather";
	  $this->uvw = ""; 
	  
	  $this->link = paramload('IVWEATHER','link');
	  $this->city = paramload('IVWEATHER','city'); 
	  
	  $this->reqst = "http://" . $this->link . "?id=" . $this->city;
	  //echo $this->reqst; 
	}

    function event($evn) {
       switch ($evn) {		
          case "ivw"   : $this->get_weather(); break;					 	  
       }	
	}	

	function action() {
        $__USERAGENT = GetGlobal('__USERAGENT');
		
        switch ($__USERAGENT) {
	         case 'HTML' : $out = $this->show_weather(); break;
	         case 'GTK'  : $out = $this->ret; break;
	         case 'TEXT' : $out = $this->ret; break;
	         case 'CLI'  : $out = $this->ret; break;			 
	         case 'WAP'  : $out = $this->ret; break;			 
	    }
			
	    return ($out);
	}
	
	function get_weather() {
	
      $this->uvw = $HTTP_GET_VARS["u"];

      if (!isset($this->uvw)) {
	    //$this->uvw = "http://weather.interceptvector.com/weather.xml?id=RUlYWDAwMTQ%3D"; //dublin
		//"http://weather.interceptvector.com/weather.xml?id=R1JYWDAwMTE%3D"; //larissa
		$this->uvw = $this->reqst; //"http://weather.interceptvector.com/weather.xml?id=R1JYWDAwMTk%3D"; //thessaloniki
      }		
	}
	
	function show_weather() {
	
      //$ret =  "<h3>Test of IVWeather class</h3><br>";
	  
      $ivw = new IVWeather($this->uvw);
	  
      $ret .=  "Weather for ". $ivw->rWeatherLocale["city"]. "," . $ivw->rWeatherLocale["state"] . " " . $ivw->rWeatherLocale["country"] . " " . $ivw->rWeatherLocale["region"] . "<br>";
      $ret .=  "Time: $ivw->wdate <br>";
      $ret .=  $ivw->rTemp["temp"] . " degrees " . $ivw->rTemp["tempscale"] . " and $ivw->sky <img src=http://weather.interceptvector.com/common/weather/icons/".$ivw->wid.".gif><br>";
      $ret .=  "Wind speed " . $ivw->rWind["strength"] . "mph from " . $ivw->getWinddecode() . "[" . $ivw->rWind["direction"] . "]<br>";
      $ret .=  "UV: $ivw->uv Visibility $ivw->visibility <br>";
      $ret .=  "Barometric pressure: ". $ivw->barometer . " and relative humidity of " . $ivw->humidity . "%<br>";

      $ret .=  "<table>\n";
      $ret .=  "<tr>";
      for ($i=0; $i < $ivw->getForecastCount(); $i++) {
	    $ret .=  "<td><b>" . $ivw->rForecast[$i]["day"] . "</b><br>". $ivw->rForecast[$i]["date"] ."<br>";
	    $ret .=  "<img src=http://weather.interceptvector.com/common/weather/icons/".$ivw->rForecast[$i]["wid"].".gif><br>";
	    $ret .=  $ivw->rForecast[$i]["sky"]."<br>high:".$ivw->rForecast[$i]["high"]."<br>low:".$ivw->rForecast[$i]["low"] . "<br>Chance Rain:".$ivw->rForecast[$i]["precipitation"]."%</td>";
      }
      $ret .=  "</tr>";
      $ret .=  "</table>\n";

	  $win = new window("IV Weather",$ret);
      $out = $win->render();
	  unset($win);
	  
	  //provider link
      $out .=  "Weather information provided by &nbsp<a href=" . $ivw->rProvider[0]["url"] . "><img border=0 src=\"" . $ivw->rProvider[0]["logo"] . "\" alt=\"". $ivw->rProvider[0]["name"] . "\"></a>";
      $out .=  " and &nbsp<a href=" . $ivw->rProvider[1]["url"] . "><img border=0 src=\"" . $ivw->rProvider[1]["logo"] . "\" alt=\"". $ivw->rProvider[1]["name"] . "\"></a><br>";
		  
	
	  return ($out);
	}

};
}
?>