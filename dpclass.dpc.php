<?php

abstract class _dpc {
  
  abstract public function action(); 
  abstract public function event();  

}

interface _dpcobj {

  public function render();
  public function headtitle();
}

interface _dpclib {

  public function example();
}

//example class absatraction
class dpclass extends _dpc implements _dpcobj {
  
  function __construct() {
  }
  
  public function event($event=null) {
    //....
  }
  
  public function action($action=null) {
    //....
  }
  
  public function render() {
    //....
  }
  
  public function headtitle() {
  }  
  
  function __destruct() {
    //....
  }
  
}


?>