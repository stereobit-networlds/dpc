<?php
if (defined("DATABASE_DPC")) {
	   
$__DPCSEC['DATAFORMS_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("DATAFORMS_DPC")) && (seclevel('DATAFORMS_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("DATAFORMS_DPC",true);

$__DPC['DATAFORMS_DPC'] = 'dataforms';

//$d = GetGlobal('controller')->require_dpc('database/database.dpc.php');
//require_once($d);

$__EVENTS['DATAFORMS_DPC'][0]='dataformsinsert';
$__EVENTS['DATAFORMS_DPC'][1]='dataformsupdate';

$__ACTIONS['DATAFORMS_DPC'][0]='dataformsinsert';
$__ACTIONS['DATAFORMS_DPC'][1]='dataformsupdate';

$__LOCALE['DATAFORMS_DPC'][0]='_POST;Post;Καταχώρηση';
$__LOCALE['DATAFORMS_DPC'][1]='_CLEAR;Clear;Απαλοιφή';

class dataforms extends database {  

    var $table_info;
	var $method;
	var $form_name,$form_title;
	var $form_cs,$form_cp;
	var $form_text_width,$form_text_length;
	var $read_only;
	var $combo_list;
	var $form_radio_preset;
	var $form_date_width,$form_date_length;
	var $form_textarea_cols,$form_textarea_rows;
	var $sql_to_execute, $sqlresult;
	var $post;
	var $missing_field;
	var $goto, $template, $tokensout;
	var $p_key,$primary_key; //wicth is the pr key (label ans the result to drive to at insert)
    var $affected;
	var $urlpath, $inpath;
	
    function __construct() {
	  
	   database::database();
	   
	   $this->urlpath = paramload('SHELL','urlpath');
	   $this->inpath = paramload('ID','hostinpath');		   
	   
	   $this->method = null;//insert,update
	   $this->sql_to_execute = null;
	   $this->sqlresult = null;
	   
	   $this->form_title = 'form';
	   $this->form_name = 'form';
	   //init form attr
	   $this->setform('form','form',0,0,50,256); 
	   $this->setformadv();
	   
	   $this->post = false;
	   $this->missing_field = null;
	   $this->goto = null;
	   
	   $this->template = null;
	   $this->tokensout = null;
	}
	
	function event($event=null) {

	   $this->post = true;
		   
	   if (!$this->check_required_fields()) return;
	   
	   switch ($event) {
	
		 case 'dataformsinsert' : $this->insert_dataform(); break;
								  
		 case 'dataformsupdate' : $this->update_dataform(); break;
		 
		 default                :	   
	   }
	}
	
	function action ($action=null) {
	   //show message
	   
	   //echo 'SQL2EXECUTE:',$this->sql_to_execute;//<<<<<<<
	   
	   //echo 'AFFECTED',$this->dbp->Affected_Rows(),'...';//<<<<<<<
	   
	   //if (!$this->check_required_fields()) return('xxx');//<<<<<<<
	      
	   switch ($action) {
	
		 case 'dataformsinsert' :
		 case 'dataformsupdate' :
		 
		 default                :	
		 
		 if ($this->dbp->Affected_Rows()) {
		   $act = $this->driveto();
		   $w = new window('Record affected',$act);
		   $out .= $w->render("center::50%::0::group_dir_body::center::0::0::");
		   unset($w);
		 }
		 else {
		   $act = $this->driveto();
		   $w = new window('Record not affected',$act);
		   $out .= $w->render("center::50%::0::group_dir_body::center::0::0::");
		   unset($w);		 
		 }

		                             
	   }
	   
	   return ($out);
		   
	}
	
	//independent connection
	private function dbconnect() {
	}	
	
	//preset from attributes
	public function setform($title,$name,$cs=0,$cp=0,$twidth=50,$tlength=50,$clist=0,$pradio=1) {
	  //set form attributes
	  $this->form_title = $title; //echo $title;
	  $this->form_name = $name;	  
	  
	  $this->form_cs = $cs;
	  $this->form_cp = $cp;
	  $this->form_text_width = $twidth;
	  $this->form_text_length = $tlength;
	  $this->read_only = 0;
	  $this->combo_list = $clist;
	  $this->form_radio_preset = $pradio;  
	}
	
	//extending parameters due to call_method valimitation
	public function setformadv($dwidth=10,$dlength=10,$tcols=10,$trows=10,$pkey=null) {
	
	  $this->form_date_width = $dwidth;
	  $this->form_date_length = $dlength;	
	  
	  $this->form_textarea_cols = $tcols; 
	  $this->form_textarea_rows = $trows;
	
	  $this->p_key = $pkey;   		
	}
	
	public function setformgoto($goto) {
	
	  $this->goto = $goto;
	}
	
	public function setformtemplate($template) {
	  
	   $this->template = $this->urlpath .'/' . $this->inpath . '/cp/html/'. str_replace('.',getlocal().'.',$template.'.htm') ; 
	   //echo $t;
	   if (is_readable($this->template)) {
		 //$mytemplate = file_get_contents($this->template); ..read after...
		 $this->tokensout = 1;
       }	
	}
		
	//create form
	public function getform($dsn,$csactions,$submitbutton='Submit',$resetbutton=true,$csfields=null,$cstitles=null,$cslookup=null,$updaterecordwhere=null) {
	
	   if (!defined('FORM_DPC')) die('form dpc required!');
	   
	   if (($this->post) && ($this->check_required_fields())) {
	     //data submited ... procced action ..
		 //echo '>>>>>>',$this->goto;
		 /*if ($this->goto) {
		   $fout = $this->driveto($this->goto);
		   return ($fout);
		 } */ 
	   }	 
	   
	   if (($this->post) && (!$this->check_required_fields())) 
	     //$fout = 'Field required!';
		 $fout = writecl("Field required!",'#FFFFFF','#FF0000');	   
	   
	   $table = $this->dsn_analyze($dsn);
	   if (!$table)
	     $table = $dsn;
	   
	   $ca = explode(',',$csactions);
	   $cf = ($csfields ? explode(',',$csfields) : null);
	   $ct = explode(',',$cstitles);
	   $cl = $this->analyze_lookup($cslookup);
	   $cp = $this->form_cp;//0;
	   $cs = $this->form_cs;//0;
	   $submit_title = $submitbutton;//'SubmiT';
	   //$reset_title = 'ReseT';	   
	   if ($reset_title=$resetbutton)
	     $hasReset = $resetbutton;//true;
	   
	   //print_r($cf);
	   $fields = $this->get_table($table,$cf);
	   
	   if (($this->method=='UPDATE') and 
	       ($where=str_replace(',',' and ',$updaterecordwhere)))	{
	     //echo $updaterecordwhere;
		 //echo "<br>", $where;
		 $this->get_update_record($table,$fields,$where);
	   }	   
	   
	   $url = seturl("t=".$ca[0]);
	   $form = new mform($this->form_title, $this->form_name, FORM_METHOD_POST, $url, $hasReset);   
	   $form->addGroup("standart","");	   
	   
	   $meter = 0;
	   foreach ($fields as $name=>$type) {
		   //echo $name,"=>",$type,"<br>";//<<<<<
		   
		   $globaltype = strtoupper(substr($type,0,3));
		   //echo $globaltype;//<<<<<
		   		   
		   if (!$title=$ct[$meter])//default name = title
		     $title = $name;
		   
		   if ((strstr($title,'*')) ||	
		       ($this->getfield_attribute($name,'notnull')==1)) {	  
			 $required_fields[$name]=1;
			 $title .= '*'; //required
		   }
		   //else
		     //$required_fields[$title] = 0;
		   if (($this->post) && ($name==$this->missing_field))	 
		     $title .= writecl("(required)",'#FFFFFF','#FF0000');	 
		  
		   switch ($globaltype) {
		     
			 //sqlite
			 case 'TEX' : $this->addTextAreafield($form,$title,$name,$cl[$meter]);
			              break;
			 case 'DAT' : $this->addDateTimefield($form,$title,$name,$cl[$meter]); 
			              break;		 
			 case 'INT' : $this->addDefaultfield($form,$title,$name,$cl[$meter]);
			              break;
			 						  
			 case 'VAR' : $x = $this->getfield_attribute($name,'type',1);
		                  //echo 'x=',$x;//<<<<<
						  $this->addDefaultfield($form,$title,$name,$cl[$meter]);
						  break;
			 
		     default    : //$form->addElement("standart",new form_element_text($title,$name,GetParam($name),"forminput",50,255,0));
			              $this->addDefaultfield($form,$title,$name,$cl[$meter]);
		   }
		   $meter+=1;
	   }
	   //save table name
	   $form->addElement(FORM_GROUP_HIDDEN,new form_element_hidden ("_table", $table));	
	   //save fields name
	   $form->addElement(FORM_GROUP_HIDDEN,new form_element_hidden ("_fields", str_replace('"','@',serialize($fields))));	   
	   //save required fields name
	   $form->addElement(FORM_GROUP_HIDDEN,new form_element_hidden ("_required", str_replace('"','@',serialize($required_fields))));		   
	   //save goto action
	   $form->addElement(FORM_GROUP_HIDDEN,new form_element_hidden ("_goto", $this->goto));		   

	   //set command
	   switch ($this->method) {
	     case 'INSERT' : if (($ca[0]) && (!$ca[1])) //one action so set hidden field
	                       $form->addElement(FORM_GROUP_HIDDEN,new form_element_hidden ("FormAction", "dataformsinsert"));	
		                 break;
		 case 'UPDATE' : if (($ca[0]) && (!$ca[1])) //one action so set hidden field
	                       $form->addElement(FORM_GROUP_HIDDEN,new form_element_hidden ("FormAction", "dataformsupdate"));	
	                     //save fields name
	                     $form->addElement(FORM_GROUP_HIDDEN,new form_element_hidden ("_updatewhere", $where));	   
		                 break;
		 default       :     
	     //print_r($ca);
	     //echo count($ca);
	     if (($ca[0]) && (!$ca[1])) //one action so set hidden field
	       $form->addElement(FORM_GROUP_HIDDEN,new form_element_hidden ("FormAction", $ca[0]));	   
	     //else getform will post ca elemets as posts with name=FormAction
	   }
	   
       if ($this->tokensout) {//template...
	   
	       $tokens = $form->getFormTokens($ca,$cp,$cs,$submit_title,$reset_title);	
		 	   
		   $mytemplate = file_get_contents($this->template);
		   $ret = $this->combine_tokens($mytemplate,$tokens);
		   return ($ret);		   
	   }
	   else {//default view....      
	   	 
	     // Showing the form
	     $fout .= $form->getform ($ca,$cp,$cs,$submit_title,$reset_title);	
	   
	     return ($fout);		 
	   }	 
	}
	
	protected function addDefaultfield(&$form,$title,$name,$lookup=null) {
	
	   if ($lookup) {
	     if (is_array($lookup))
	       $form->addElement("standart",new form_element_combo($title,$name,GetParam($name),"forminput",$this->combo_list,$lookup,0));	
		 elseif (is_numeric($lookup)) { //length of field
		 
	       $this->form_text_width = $lookup;
	   
	       if ($name=='state') //greek map??????????
		     $form->addElement("standart",new form_element_greekmap($title,$this->form_name,$name,GetParam($name),"forminput",$this->form_text_width,$this->form_text_length,$this->read_only));	
		   elseif (($name=='country') && ($this->method=='INSERT'))
		     $form->addElement("standart",new form_element_text($title,$name,$this->get_country_from_ip(),"forminput",$this->form_text_width,$this->form_text_length,$this->read_only));  
		   else
             $form->addElement("standart",new form_element_text($title,$name,GetParam($name),"forminput",$this->form_text_width,$this->form_text_length,$this->read_only));			 
		 }  
		 else //string
		   $form->addElement("standart",new form_element_combo_file($title,$name,GetParam($name),"forminput",$this->combo_list,0,$lookup));  
	   }	
	   else	{ //default .. as in length of field with standart length
	     if ($name=='state') //greek map??????????
		   $form->addElement("standart",new form_element_greekmap($title,$this->form_name,$name,GetParam($name),"forminput",$this->form_text_width,$this->form_text_length,$this->read_only));	
		 elseif (($name=='country') && ($this->method=='INSERT'))
		   $form->addElement("standart",new form_element_text($title,$name,$this->get_country_from_ip(),"forminput",$this->form_text_width,$this->form_text_length,$this->read_only));  
		 else
           $form->addElement("standart",new form_element_text($title,$name,GetParam($name),"forminput",$this->form_text_width,$this->form_text_length,$this->read_only));	 
	   }	 
	}
	
	protected function addBooleanfield(&$form,$title,$name,$lookup=null) {
	
	   if ($lookup) {
	     if (is_array($lookup)) 
           $form->addElement("standart",new form_element_radio($title,$name,$this->form_radio_preset,"forminput",count($lookup),$lookup));	  	
		 else
		   $form->addElement("standart",new form_element_radio_file($title,$name,$this->form_radio_preset,"forminput",2,$lookup));	  	  
	   }
	   else
	     $form->addElement("standart",new form_element_radio($title,$name,$this->form_radio_preset,"forminput",2,array ("0" => localize('_OXI',getlocal()), "1" => localize('_NAI',getlocal()))));	  	   	   
	}
	
	protected function addDateTimefield(&$form,$title,$name,$lookup=null) {
	
	   if (is_numeric($lookup)) //length of field
	     $this->form_date_width = $lookup;  
		 	
	   if (defined('DATEPICK_DPC')) 
	     $form->addElement("standart",new form_element_date($title,$this->form_name,$name,GetParam($name),"forminput",$this->form_date_width,$this->form_date_length,$this->read_only));	 	
	   else
         $form->addElement("standart",new form_element_text($title,$name,GetParam($name),"forminput",$this->form_date_width,$this->form_date_length,$this->read_only));	 	
	}
	
	protected function addTextAreafield(&$form,$title,$name,$lookup=null) {
	
	   if (is_numeric($lookup)) //length of field
	     $this->form_textarea_cols = $lookup;
		 
	   $form->addElement("standart",new form_element_textarea($title,$name,GetParam($name),"forminput",$this->form_textarea_cols,$this->form_textarea_rows));	 		   
	}
	
	public function getlist($dsn,$csactions,$submitbutton='Submit',$resetbutton=true,$csfields=null,$cstitles=null,$cslookup=null) {
	}	
	
	
	protected function get_table($table,$optional_array_fields=null) {
	
	   $fields = $this->dbp->MetaColumns($table);
	   //echo "<pre>";
	   //print_r($fields);
	   //echo "</pre>";	
	   
	   foreach ($fields as $id=>$f) {
	   
	    //save table info
	    $this->table_info[$f->name] = $f;
	    //echo "</pre>";			
		//print_r($this->table_info);
	    //echo "</pre>";			
	   
	    if (is_array($optional_array_fields)) {
		   //print_r($optional_array_fields);  
	       if (in_array($f->name,$optional_array_fields))  
		     $ret[$f->name] = $f->type;
		}	 
		else
		  $ret[$f->name] = $f->type;//all   
		  
	   }	   
	   
	   return ($ret);//fields as array
	}
	
	//<phpform> compliant replacement
	public function get_record($dsn,$action,$csfields,$whereclause=null) {
	
	   $where = str_replace(',',' and ',$whereclause);
	   $sql = 'select ' . $csfields . 'where ' . $where;    
	   
	   $resultset = $this->dbp->Execute($sql,2);
	   $rec = $this->dbp->fetch_array($resultset);	 
	   	   
	   foreach ($rec as $name=>$value) {
		   //echo $name,"=>",$type,"<br>";//<<<<<<
		   $form[] = $name;
		   $form[] = $value;
	   }
	   $form_array['form'] = (array)$form;
	   $form_array['action'] = $action;
	   print_r($form_array);
	   
	   return (serialize($form_array));	   
	}
	
	//fetch data from record to update
	protected function get_update_record($table,$fields,$where) {
	
	   $sql = "select ";
	
	   /*$meter = 0; $max = count($fields)-1;
	   foreach ($fields as $name=>$type) {	
	     $sql .= $name;
		 if ($meter<$max) $sql .= ',';
		 $meter+=1;
	   }*/
	   $sql .= implode(',',array_keys($fields));   
	   $sql .= " from ". $table . " where " . $where;
	   
	   //echo $sql;
	   $resultset = $this->dbp->Execute($sql,2);
	   //$rec = $resultset->fields;
	   //print_r($resultset->fields);
	   foreach ($resultset->fields as $n=>$data) {	     
	       if (!is_numeric($n))
		     $rec[$n] = $data;
	   }	   	
	   
	   //fill post params with data
	   foreach ($rec as $name=>$value) {
		   //echo $name,"=>",$type,"<br>";
           $_POST[$name] = $value;
	   }	   	   
	}	

	protected function insert_dataform() {
	
	   $table = GetParam('_table'); //echo $table,'>';
	   $fields = unserialize(str_replace('@','"',GetParam('_fields')));  //print_r($fields);
	   
       //send back the id of max id and save the next as id+1
	   $this->primary_key = $this->get_id($table,'id')+1;//$this->p_key);<<<<<<<ERROR
	   if ($this->primary_key) 
	     SetParam('id',$this->primary_key);
								  	   
	   
	   if (($table) && (!empty($fields))) {
	   
	     $sql = 'insert into ' . $table;
		 $sql .= '(';
	     $sql .= implode(',',array_keys($fields));
		 $sql .= ') values (';
		 
	     foreach ($fields as $name=>$type) {	
		   $gtype = strtoupper(substr($type,0,3));	
		   switch ($gtype) {
			 //sqlite
			 case 'INT' : $vals[] = GetParam($name); break;	 
			 case 'TEX' : 
			 case 'DAT' : 	  		  
			 case 'VAR' : 
			 
		     default    : $vals[] = $this->dbp->qstr(GetParam($name));		     
		   } 		 
	     }		
		 
		 $sql .= implode(',',$vals);
		 $sql .= ')';
		 
	     //echo $sql; 
		 $this->sql_to_execute = $sql;
		 $this->sqlresult = $this->dbp->Execute($sql);
		 
	   }
	}
	
	protected function update_dataform() {
	
	   $table = GetParam('_table');
	   $fields = unserialize(str_replace('@','"',GetParam('_fields')));
	   $where = GetParam('_updatewhere');
	   
	   if (($table) && (!empty($fields)) && ($where)) {
	   
	     $sql = 'update ' . $table;
		 $sql .= ' set ';
		 
		 $meter = 0; $max = count($fields)-1;
         foreach ($fields as $name=>$type) {	
		 
		   $gtype = strtoupper(substr($type,0,3));	
		   switch ($gtype) {
			 //sqlite
			 case 'INT' : $sql .= $name.'='.GetParam($name);break;	 
			 case 'TEX' : 
			 case 'DAT' : 	  		  
			 case 'VAR' : 
			 
		     default    : $sql .= $name.'='. $this->dbp->qstr(GetParam($name));		     
		   } 		 
	       
		   if ($meter<$max) $sql .= ',';
		   $meter+=1;
		 } 
		 
		 $sql .= ' where '.$where;
		 
	     //echo $sql; 
		 $this->sql_to_execute = $sql;
		 $this->sqlresult = $this->dbp->Execute($sql);
	   }	
	}
	
	protected function getfield_attribute($name,$attribute,$extract=null) {
	
	  $element = $this->table_info->name[$attribute];
	
	  
	  if ($extract) {
	    $pattern = "@[(].*?(.*?)[)]@";
	    preg_match_all($pattern,$htmldata,$matches);
	    $_arrays = $matches[0];
	    //print_r($_arrays);//<<<<<<<<<<<<<<<<<<<<<<<<
	  }
	  
	  return ($element);
	}
	
	protected function check_required_fields() {
	
	  $required_fields = unserialize(str_replace('@','"',GetParam('_required')));
	  
	  //print_r($required_fields);
	  if (!empty($required_fields)) {
	    foreach ($required_fields as $name=>$dummy) {
	   
	      //echo '>',GetParam($name),"<br>";
	      if (GetParam($name)=='') {
		    $this->missing_field = $name;
		    return false;  
		  } 	
	    }
	  }
	  return true;
	}	
	
	protected function driveto() {
	  
	   $goto = GetParam('_goto');
	  
	   $p = explode(':',$goto);
	   
	   $to = $p[0];
	   $go = $p[1]; 
	   $title = $p[2];
	   
	   $id = $this->primary_key;
	
	   switch ($to) {
	   
	     case 'LINK'   : $ret = $go; break;
		 case 'DPCLINK': $ret = seturl("t=$go" . "&id=$id",$title); break;
		 case 'PHPDAC' : $ret = GetGlobal('controller')->calldpc_method($go); break;
	
	     default :
	               //$fout = 'Ok!'; 
		           $ret = writecl("Ok!",'#000000','#00FF00');
	   }	 
	   return ($ret);	
	}
	
	private function analyze_lookup($cslookup) {
	
	  $lb = explode(",",$cslookup);
	  
	  foreach ($lb as $id=>$look) {
	     
		 if (strstr($look,'..')) {//a..z,1..100 cases
		   $set = explode('..',$look);

		   for ($i=$set[0];$i<=$set[1];$i++)
		     $ret[$id][] = $i;
		 }
		 elseif (strstr($look,'_')) {//file or table of type table_field
		   
		   $file = paramload('SHELL','prpath') . str_replace('_','.',$look);  
		   if (is_readable($file)) {
		     $ret[$id] = explode(',',@file_get_contents($file));
		   }
		   else {//table
		     $p = explode('_',$look);
			 if (!$db2) $db2 = &$this->dbp;
			 $sql = "select " .$p[1]. " from " .$p[0];
			 $result = $db2->Execute($sql,2);
	         $recs = $db2->fetch_array_all($result);	
			 foreach ($recs as $i=>$rec) 
			   $ret[$id][] = $rec[0];
		   }
		 }
		 elseif (is_numeric($look)) {
		   //echo '>',$look,"<br>";
		   $ret[$id] = $look;
		 }
		 else {//combo file (.opt file)
		    $ret[$id] = $look;//name string 
		 }
	  }
	  //print_r($ret);
	  return ($ret);
	}	
	
	//as dsn define action.table where action = insert or update
	private function dsn_analyze($dsn) {
	
	  $ret = array();
	
	  $parts = explode('.',$dsn);
	  //print_r($parts);
	  $table = $parts[1];
	  $this->method = strtoupper($parts[0]);
	  //echo $this->method;
	  
	  return ($table);
	}

	private function analyze_dsn($dsn) {
	
	  $ret = array();
	
	  $parts = explode('@',$dsn);
	  $userpart = $parts[0];
	  $dbpart = $parts[1];
	  
	  $userdetails = explode(":",$userpart);
	  $username = $userdetails[0];
	  $userpwd = $userdetails[1];
	  
	  $dbdetails = explode(".",$userpart);
	  $dbname = $dbdetails[0];
	  $dbtable = $dbdetails[1];
	  
	  $ret['uname'] = $username;
	  $ret['upwd'] = $userpwd;
	  $ret['host'] = $dbname;
	  $ret['table']= $dbtable;
	  
	  return ($ret);
	}
	
    function get_country_from_ip() {
     
	  if (defined('COUNTRY_DPC')) {
         $mycountry = GetGlobal('controller')->calldpc_method("country.find_country");
	     //return "Greece";
	     return ($mycountry);
	  }
    }
	
	function set_id($id) {
	
	  $this->p_key = $id;
	}
	
	function get_id($table,$id) {
	
	  //if ($this->id) {
	    $sql = "select max($id) from " . $table;
		//echo $sql;
	    $result = $this->dbp->Execute($sql,2);
		
		$recs = $result->fields;	
		//print_r($recs);
	    $ret = $recs[0];
	    //echo $ret,'>>>>>';
	    return ($ret);
      //}		
	}	
	
	function combine_tokens($template_contents,$tokens) {
	
	    if (!is_array($tokens)) return;
		
		if (defined('FRONTHTMLPAGE_DPC')) {
		  $fp = new fronthtmlpage(null);
		  $ret = $fp->process_commands($template_contents);
		  unset ($fp);
          //$ret = GetGlobal('controller')->calldpc_method("fronthtmlpage.process_commands use ".$template_contents);		  		
		}		  		
		else
		  $ret = $template_contents;
		  
		//echo $ret;
	    foreach ($tokens as $i=>$tok) {
            //echo $tok,'<br>';
		    $ret = str_replace("$".$i."$",$tok,$ret);
	    }
		//clean unused token marks
		for ($x=$i;$x<20;$x++)
		  $ret = str_replace("$".$x."$",'',$ret);
		//echo $ret;
		return ($ret);
	}		 	

};
}
}
else die("DATABASE DPC REQUIRED!");
?>