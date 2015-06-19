<?php
if (!defined("STOREBUFFER_DPC")) {
define("STOREBUFFER_DPC",true);

class storebuffer {

	var $buffer = array();
	var $name;

	function storebuffer($sesname='storebuffer') {

		$this->name = $sesname;
		$this->buffer= (array)$this->getStore();

	}

    function isin($id) {

        reset ($this->buffer); 
        while (list ($buffer_num, $buffer_data) = each ($this->buffer)) {
        //foreach ($this->buffer as $buffer_num => $buffer_data) {                             
           if ($buffer_data == $id) return true;                                    
        }                       
        return false;
    } 

    function _count() {
	
        $i=0;
		if ($this->buffer) {
          reset ($this->buffer); 
          while (list ($buffer_num, $buffer_data) = each ($this->buffer)) { 
          //foreach ($this->buffer as $buffer_num => $buffer_data) {                            
             if ($buffer_data != 'x') $i+=1;                                    
          }                       
          return ($i);
		}
		else
		  return 0;
		
    } 

    function notempty() {

        reset ($this->buffer); 
        while (list ($buffer_num, $buffer_data) = each ($this->buffer)) {
        //foreach ($this->buffer as $buffer_num => $buffer_data) { 
           $mchar = strlen($buffer_data); 
           if ($mchar > 1) return true;
        }                       
        return false;
    } 

    function addto($id) {

        if (!($this->isin($id))) {
           $x=0;
           reset ($this->buffer);
           while (list ($buffer_num, $buffer_data) = each ($this->buffer)) {                             
           //foreach ($this->buffer as $buffer_num => $buffer_data) {
              if ($buffer_data=="x") {
                 $this->buffer[$buffer_num] = $id;
                 $x = 1; 
                 break;
              } 
           }
           if ($x==0) $this->buffer[] = $id;

           $this->setStore(); 
        }	
    } 

    function remove($id) {

        reset ($this->buffer);
        while (list ($buffer_num, $buffer_data) = each ($this->buffer)) {             
        //foreach ($this->buffer as $buffer_num => $buffer_data) {	
              if ($buffer_data == $id) { 
                 $this->buffer[$buffer_num] = "x";  
                 break;
              }                                   
        }                    
		$this->setStore();
    }

    function clear() {

        reset ($this->buffer);
        while (list ($buffer_num, $buffer_data) = each ($this->buffer)) {             
        //foreach ($this->buffer as $buffer_num => $buffer_data) {
              $this->buffer[$buffer_num] = "x";                                    
        } 
		$this->setStore();
    }
	
    function setStore() {

        SetSessionParam($this->name,$this->buffer);                                
    }

    function getStore() {
  
        return (GetSessionParam($this->name));
    }

};
}
?>