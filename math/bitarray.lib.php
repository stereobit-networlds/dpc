<?php

	/**
	*	$RCSfile: lib.BitArray.php,v $
	*	@author 	$Author: Cornelius Bolten $
	*	@version	$Revision: 1.2 $
	*	@package	BitArray
	*	
	*	@copyright
	*	The Initial Developer of the Original Code is Cornelius Bolten.
	*	Portions created by Cornelius Bolten are Copyright (C) 2004 by Cornelius Bolten.
	*	All Rights Reserved.	
	*	Usage is free for non-commercial work. see http://www.phpclasses.org/browse/package/1540.html
	*	for more information.
	*
	*	@see
	*  	Latest releases are available at http://www.phpclasses.org/browse/package/1540.html 
	*	For feedback, bug reports or enhancements please contact the author at 
	*	c.bolten@grafiknews.de. Thanks a lot!
	*
	*	@description
	*	This is a PHP-port of the #.NET-BitArray Class.
	*	This Class manages a compact array of bit values, which are represented as Booleans, 
	*	where true indicates that the bit is on (1) and false indicates the bit is off (0).
	*
	*	@example
	*		working with BitArray	->	example.BitArray.php
	*		not-comparison			->	example.not.BitArray.php
	*		or-comparison			->	example.or.BitArray.php
	*		xor-comparison			->	example.xor.BitArray.php
	*		and-comparison			->	example.and.BitArray.php
	*
	**/
	
	class BitArray {
		/**
		* @access private         
		* @var BitString       
		*/		
		var $BitString;
		
		/**
		* @access private         
		* @var BitArrayLength       
		*/		
		var $Length = 0;		
		
		/**
		*	constuctor
		*
		*	Initializes a new instance of the BitArray class that can hold the 
		*	specified number of bit values, which are initially set to false.
		*
		*	@access public
		*	@param integer number of bit values
		*/		
		function BitArray($BitArrayLength) {
			if($BitArrayLength >= 33) {
				echo "BitArrayLength can not be greater than 32";
				return false;
			}
			$this->Length	=	$BitArrayLength-1;
			$this->setupBitArray();
		}		
		
		function setBitArray($dec) {
			$this->BitString = str_pad((string)decbin($dec),$this->Length,"0",STR_PAD_LEFT);
		}
		
		function getBitArray() {
			return bindec($this->BitString);
		}
		
		/**
		*	set
		*
		*	Sets the bit at a specific position in the BitArray to the specified value.
		*
		*	@access public
		*	@param integer index
		*	@param boolean value
		*	@return boolean	
		*/			
		function set($index, $value) {
			if($index >= $this->Length)
				return false;
				
			if(!is_bool($value))
				return false;
			else 
				$this->BitString[$index]	=	$this->bool2int($value);
				
			return true;
		}
		
		/**
		*	setAll
		*
		*	Sets all bits in the BitArray to the specified value.
		*
		*	@access public
		*	@param boolean value
		*	@return boolean	
		*/			
		function setAll($value) {
			if(!is_bool($value)) {
				return false;
			} else {
				for($i=0; $i <= $this->Length; $i++) {
					$this->BitString[$i] = $this->bool2int($value);
				}
			}
			return true;
		}		
		
		/**
		*	get
		*
		*	Gets the value of the bit at a specific position in the BitArray.
		*
		*	@access public
		*	@param integer index
		*	@return boolean	
		*/			
		function get($index) {
			if($index >= $this->Length)
				return false;
			else			
				return $this->int2bool($this->BitString[$index]);
		}
		
		/**
		*	getAll
		*
		*	Gets all values of the bits in the BitArray.
		*
		*	@access public
		*	@return array	
		*/			
		function getAll() {
			for($i=0; $i<=$this->Length; $i++) {
				$return[]	=	$this->get($i);
			}
			return $return;
		}		
		
		/**
		*	_or
		*
		*	Performs the bitwise OR operation on the elements in the current 
		*	BitArray against the corresponding elements in the specified BitArray
		*
		*	@access public
		*	@param BitArray 
		*	@return array	
		*/			
		function _or($CompBitArray) {
			for($i=0; $i<=$this->Length; $i++) {
				$result[$i]	=	($this->get($i) or $CompBitArray->get($i));
			}
			return $result;
		}	
		
		/**
		*	_xor
		*
		*	Performs the bitwise eXclusive OR operation on the elements in the current 
		*	BitArray against the corresponding elements in the specified BitArray.
		*
		*	@access public
		*	@param BitArray 
		*	@return array	
		*/			
		function _xor($CompBitArray) {
			for($i=0; $i<=$this->Length; $i++) {
				$result[$i]	=	($this->get($i) xor $CompBitArray->get($i));
			}
			return $result;
		}
		
		/**
		*	_not
		*
		*	Inverts all the bit values in the current BitArray, so that elements set to 
		*	true are changed to false, and elements set to false are changed to true. 
		*
		*	@access public
		*/			
		function _not() {
			for($i=0; $i<=$this->Length; $i++) {
				$val	=	!$this->get($i);
				$this->set($i,$val);
			}
			return true;
		}	
		
		/**
		*	_and
		*
		*	Performs the bitwise AND operation on the elements in the current 
		*	BitArray against the corresponding elements in the specified BitArray 
		*
		*	@access public
		*	@param BitArray 
		*	@return array		
		*/			
		function _and($CompBitArray) {
			for($i=0; $i<=$this->Length; $i++) {
				$result[$i]	=	($this->get($i) and $CompBitArray->get($i));
			}
			return $result;
		}							

		/**
		*	getBool
		*
		*	converts a int to the equivalent bool 
		*
		*	@access private
		*	@param string value
		*	@param boolean value	
		*/
		function int2bool($value) {
			if($value == '1')
				return true;
			else
				return false;
		}
		
		/**
		*	setBool
		*
		*	converts a bool to the equivalent integer value as string 
		*
		*	@access private
		*	@param boolean value
		*	@param string value	
		*/		
		function bool2int($value) {
			if($value == true)
				return '1';
			else
				return '0';			
		}

		/**
		*	setupBitArray
		*
		*	sets up the BitArray with the length passed to the constructor 
		*
		*	@access private
		*	@param string [$libPath] Path to Library
		*	@param array [$libPath] Paths to Library	
		*/		
		function setupBitArray() {
			for($i = 0; $i <= $this->Length; $i++) {
				$this->BitString .= $this->bool2int(false);
			}
		}
	}
	
?>










