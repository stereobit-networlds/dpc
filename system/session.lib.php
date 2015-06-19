<?php
/////////////////////////////////////////////////////////////////
// FUNCTIONS RELATED TO A USER SESSION
/////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////
// cookies
/////////////////////////////////////////////////////////////////
function settheCookie($name,$val) {

  $cookie = $_COOKIE[$name];

    if (!$cookie) { 
      setcookie ($name, $val); //create cookie
    }
    else {
      setcookie ($name, "" , time() - 3600); //delete cookie
    }
  //echo $mycookie;
}

function gettheCookie($cname) {

  //echo $_COOKIE[$cname];   
  
  return $_COOKIE[$cname];   
}


////////////////////////////////////////////////////////////////
//   session
////////////////////////////////////////////////////////////////
//savs session param
function SetSessionParam($ParamName, $ParamValue) {	 
  
  $_SESSION[$ParamName] = $ParamValue;
}
//save session param presitent (unregister after end of session )
function SetPreSessionParam($ParamName, $ParamValue) {	 

  $_SESSION[$ParamName]= $ParamValue; 
  
  FreeSessionParam($ParamName);
  
}

function GetSessionParam($ParamName) {

  if ((!isset($_POST[$ParamName]) && !isset($_GET[$ParamName])))// && session_is_registered($ParamName))) 
    $ParamValue = $_SESSION[$ParamName];

  return $ParamValue; 
  
}

function GetPreSessionParam($ParamName) {	 

  $ret = $_SESSION['SESARRAY'][$ParamName]; 
  
  return ($ret);
}

//input param to session array for unregister bulk (autoloaded at SetPreSessionParam)
function FreeSessionParam($param) {
  
  //get session array
  $sesp = (array)GetSessionParam('SESARRAY');
  
  //save param
  if (!in_array($param,$sesp)) $sesp[] = $param;
  //print_r($sesp);
  //print_r($__SESSION);
  
  //save array 
  SetSessionParam('SESARRAY',$sesp);
}

//unregister now
function DeleteSessionParam($param) {
  
  $_SESSION[$param] = null;
}

//unregister all pre session array params (usually at shell destruction or logout)
function ResetSessionParams() {

  //get session array
  $sesp = (array)GetSessionParam('SESARRAY');

  //unregister all array params  
  foreach ($sesp as $sid=>$sparam)
  	   //session_unregister($sparam);
	   $_SESSION[$sparam] = null;
	   
  //delete-uregister session array
  //session_unregister("SESARRAY");
  $_SESSION["SESARRAY"] = null;
  unset($sesp);	      
  
}


////////////////////////////////////////////////////////////////
//   locale
////////////////////////////////////////////////////////////////

function getlocal() {

   $deflang = paramload('SHELL','dlang');
   $curlang = GetSessionParam("locale");
   //echo 'curlang:',$curlang;
   //return (0);//$curlang?$curlang-1:0);
   
   if ($curlang) return ($curlang-1);
            else return ($deflang?$deflang:0);
}

function setlocal($local) {
   //echo $local,'<>';
   SetSessionParam("locale",($local+1));
   //echo GetSessionParam('locale'),'<br>';
}

/////////////////////////////////////////////////////////////////
// theme
/////////////////////////////////////////////////////////////////
function setTheme($theme) {
     SetSessionParam("thema",$theme);
}

function getTheme() {
     return GetSessionParam("thema");
}

?>