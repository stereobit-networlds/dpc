<?php

class globals {

  function globals() { 
  }
  
  function get($param) {
  
    return ($GLOBALS[$param]);
  }

  function set($param,$val=null) {
  
    $GLOBALS[$param] = $val;
  }
  
}

?>