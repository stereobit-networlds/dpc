<?php

$__DPCSEC['ADMDBUSER_']='8;1;1;1;1;1;1;8;9'; //combine webos users with db sys or common users

if (!defined("DATABASE_DPC"))  {
define("DATABASE_DPC",true);

$__DPC['DATABASE_DPC'] = 'database';

$__PRIORITY['DATABASE_DPC'] = 1; //under construction


//require_once ("adodb/adodb.inc.php"); //LOADED AS EXTENSION
//include_once("adodb/adodb-pager.inc.php"); adodb pager system

//require_once("dbconnect.lib.php");
//GetGlobal('controller')->include_dpc('database/dbconnect.lib.php');
$d = GetGlobal('controller')->require_dpc('database/dbconnect.lib.php');
require_once($d);//GetGlobal('controller')->require_dpc('database/dbconnect.lib.php'));

class database {

   var $userLevelID;
   var $dbp;
   var $path, $hosted_path;

   function database() {
      static $i;
	  $db = GetGlobal('db');
	  
      $this->path = paramload('SHELL','prpath');  
	  $this->hosted_path = $this->path;	 	  	  
	  
	  //echo '>>>>>>>>>>>>>',paramload('DATABASE','dbhost');
	  $this->dbp = &$db;

      $this->userLevelID = (((decode(GetSessionParam('UserSecID')))) ? (decode(GetSessionParam('UserSecID'))) : 0);	  

      if ((iniload('DATABASE')) && (!$this->dbp)) { //no re-connection at new
	  
        //$i++; echo $i . ">>>>>>>>>>>>";

        $_Dbtype   = paramload('DATABASE','dbtype');
        $_Dbname   = paramload('DATABASE','dbname');
		
		if (seclevel('ADMDBUSER_',$this->userLevelID)) {//require modules as file becouse db does'nt exist at this state
          $_User     = paramload('DATABASE','dbauser');
          $_Password = paramload('DATABASE','dbapwd');		
		  //echo "admin db user";
		}
		else {
          $_User     = paramload('DATABASE','dbuser');
          $_Password = paramload('DATABASE','dbpwd');
		  //echo "common user";
		}  
        $_Host     = paramload('DATABASE','dbhost');  

		
	    if (defined(_ADODB_)) { 
		
          $ADODB_CACHE_DIR = paramload('SHELL','prpath') . 
		                     paramload('DATABASE','pathcacheq');		
				
          $this->dbp = ADONewConnection($_Dbtype);
          $this->dbp->PConnect($_Host, $_User, $_Password, $_Dbname);
		  //echo 'ADODB loaded !';

          if ( ($cs=paramload('DATABASE','charset')) && (stristr($_Dbtype,'mysqli')) )
            $this->dbp->_connectionID->set_charset($cs);

		}
		else {
		  //echo "ADODB extension not loaded....";

		  $this->dbp = new dbconnect($_Dbtype,null);//,'SQLITE');
		  $this->dbp->PConnect($_Host, $_User, $_Password, $_Dbname);
		}			  
		
		SetGlobal('db',&$this->dbp);//global alias
		//echo $_Dbname;
		
		//test code
        //$sSQL = "SELECT * from users";

        //$result = $db->Execute($sSQL);
        //print_r($result->GetRows());
 /*     $i=0;
        while(!$result->EOF) {
         $i+=1;
         print "$i>" . $result->fields[0] ."\n";
	     $result->MoveNext();
	    }
	    print "Êשהיךüע";
*/
     }
   }
   
	function switch_db($appname=null,$rootdb=null,$returnpointer=null, $check_object=false) {
      if ($appname) {
		 //$this->hosted_path = $this->path . 'instances/' . $appname . '/' ;
		 $this->hosted_path = $this->path . '../' . $appname . '/cp/' ;
	  }
	  //else
	  if ($rootdb)//force root db connection
	    $path = '/home/stereobi/projects/';//demosoft/';
	  else
        $path = $this->hosted_path;
      //echo $path,'-<br>-';
		  		  
	  	
      $_Dbtype   = remote_paramload('DATABASE','dbtype',$path,1);
      $_Dbname   = remote_paramload('DATABASE','dbname',$path,1);
      $_User     = remote_paramload('DATABASE','dbuser',$path,1);
      $_Password = remote_paramload('DATABASE','dbpwd',$path,1);
	  //echo $_Dbname,$_User,$_Password,'!<br>';
		  
      //return ;		  
  	  if ((stristr($_Dbtype,'mysql')) || (stristr($_Dbtype,'mysqli'))) {	
          //$ADODB_CACHE_DIR = paramload('SHELL','prpath') . paramload('DATABASE','pathcacheq');		
				
          $dbp = ADONewConnection($_Dbtype);
          $dbp->PConnect($_Host, $_User, $_Password, $_Dbname);
		  //echo 'ADODB loaded !';
		  
		  if ($check_object) {
			//....WHEN CHECK OBJ ERROR AT GLOBAL MAIL QUEUE DB CALL(ENCODING....????)
			if (is_object($dbp)) {
				if ( ($cs=paramload('DATABASE','charset')) && (stristr($_Dbtype,'mysqli')) ) {
					if (method_exists($dbp, 'set_charset')) {
						$dbp->_connectionID->set_charset($cs);
						//echo 'z';
					}	 
				}	
			}
			else
				return false; 
          }//check object...
          else {
				if ( ($cs=paramload('DATABASE','charset')) && (stristr($_Dbtype,'mysqli')) ) {
					$dbp->_connectionID->set_charset($cs);
					//echo 'z';
				}			  
          }

		  //return pointer...... 
		  if ($returnpointer)
			  return ($dbp);
		  else	
			  SetGlobal('db',&$dbp);//global alias 		  
	   }				
	}   
   
   function clear() {
     
	 $this->dbp = null;
   }
   
   //interface to adodb   
   function qstr($str) {
   
     return ($this->dbp->qstr($str));
   }
   
   function affected() {
     
	 return ($this->dbp->Affected_Rows());
   }
   
   function exesql($sql) {
   
     $ret = $this->dbp->Execute($sql);
	 
	 return ($ret);
   }   
   
   function droptable($table) {
     
	 $sSQL = "drop table if exists " . $table;
	 $this->exesql($sSQL);
	 
   }
   


function DLookUp($Table, $fName, $sWhere) {  
  //global $ADODB_FETCH_MODE;
  //global $_Dbtype, $_Dbname, $_User, $_Password, $_Host;
  $ADODB_FETCH_MODE = GetGlobal('ADODB_FETCH_MODE');  
  $_Dbtype = GetGlobal('_Dbtype');
  $_Dbname = GetGlobal('_Dbname');
  $_User = GetGlobal('_User');
  $_Password = GetGlobal('_Password');
  $_Host = GetGlobal('_Host');

  $db_look = NewADOConnection('mysql');
  $db_look->PConnect($_Host, $_User, $_Password, $_Dbname);  

  //$ADODB_FETCH_MODE = ADODB_FETCH_NUM; //associate field's number 
  SetGlobal('ADO_DB_FETCH_MODE',ADODB_FETCH_NUM);
  
  $result = $db_look->Execute("SELECT " . $fName . " FROM " . $Table . " WHERE " . $sWhere);
  if($result)
    $retval = $result->fields[0];
  else 
    $retval = "";

  //$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; //back to association in  field's name 
  SetGlobal('ADO_DB_FETCH_MODE',ADODB_FETCH_ASSOC);
  
  return ($retval);	
}

/*
function getCheckBoxValue($sVal, $CheckedValue, $UnCheckedValue)
{
  if(!strlen($sVal))
    return ToSQL($UnCheckedValue);
  else
    return ToSQL($CheckedValue);
}

function ProceedError()
{
}

*/

//- function returns options for HMTL control "<select>" as one string
function get_options($sql,$is_search,$is_required,$selected_value) {
  //global $ADODB_FETCH_MODE;
  //$ADODB_FETCH_MODE = GetGlobal('ADODB_FETCH_MODE');   
  
  //if(!$db2) $db2=$db; //-- if it's not estblished we use standart main connection
  if (!$db2) $db2 = &$this->dbp;//SetGlobal('db2',&$this->dbp);  
  
  $options_str="";
  if ($is_search)
    $options_str.="<option value=\"\">All</option>";
  else
  {
    if (!$is_required)
    {
      $options_str.="<option value=\"\"></option>";
    }
  }
  
  //$ADODB_FETCH_MODE = ADODB_FETCH_NUM; //associate field's number 

  $result = $db2->Execute($sql);
  if ($result) {
  while (!$result->EOF)
  {
    $id=$result->fields[0];
    $value=$result->fields[1];
    $selected="";
    if ($id == $selected_value)
    {
      $selected = "SELECTED";
    }
    $options_str.= "<option value='".$id."' ".$selected.">".$value."</option>";
	
	$result->MoveNext();
  }
  }
  //$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; //back to association in  field's name 

  
  return $options_str;
}

};
}
?>