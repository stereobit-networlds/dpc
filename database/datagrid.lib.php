<?php
//////////////////////////////////////////////////////////////////////////////////////
//
//  Version 1.1.0  (1.10.2006)
//  Author: Leumas Naypoka
//  Site: http://phpbuilder.blogspot.com
// 
//  Creating & Calling
//  ----------------------------------------------------------------------------------  
//  include("datagrid.class.php");
//
//  ob_start();
//  $db_conn=mysql_connect("localhost","thebutto_test","12345");
//  mysql_select_db("thebutto_test",$db_conn);
//  /*** put primary key in first place */
//  $sql="SELECT * FROM tableName ";       
//  $dgrid = new DataGrid();
//  $dgrid->dataSourse($db_handler->connect_id, $sql);	    
//  /*** other settings here ***/
//  $dgrid->bind($debug_mode, $messaging);        
//  ob_end_flush();
//
//  General Settings
//  ---------
//  /*** $param: unique string, ex.: "_abc" */
//  $dgrid->setUniqueNames($param);
//  /*** $param: array("view"=>x) x: 0-tabular(default), 1-columnar */
//  $dgrid->setLayouts(array("view"=>0));
//  /*** $param: "nowrap" - all columns nowrap */
//  $dgrid->setColumnsNoWrap("nowrap");
//  /*** $class: "class_name" - "default"/"Like Adwords"/"salomon"/ */
//  $dgrid->setCssClass($class)

//  Sorting & Paging Settings: 
//  ---------
//  /*** $option: true/false; default - true */
//  $dgrid->allowSorting(true);               
//  /*** $option: true/false; default - true */
//  $dgrid->allowPaging(true);
//  /*** $option1,option2: $option: array("results"=>true, "results_align"=>"left", "pages"=>true, "pages_align"=>"center", "page_size"=>true, "page_size_align"=>"right") or array() */
//  $dgrid->setPagingSettings(array(), array());

//  View Mode Settings: 
//  ---------
//  /*** $option: array("Name1"=>array("header"=>"Name_A","type"=>"label"),
//                      "Name2"=>array("header"=>"Name_B","type"=>"image"),
//                      "Name3"=>array("header"=>"Name_C","type"=>"link", "href"=>"www.yahoo.com", "target"=>"_new"), [,...]); */
//  $dgrid->setColumnsInViewMode(array());

//////////////////////////////////////////////////////////////////////////////////////    
        
class DataGrid
{
    //==========================================================================
    // Data Members
    //==========================================================================

    // rows and columns data members -------------------------------------------
    var $rows;
    var $rowLower;
    var $rowUpper;
    var $columns;            
    var $colLower;
    var $colUpper;
    // data source -------------------------------------------------------------
    var $db_handler;
    var $sql;
    var $sql_view;
    var $dataSet;
    // layout style ------------------------------------------------------------
    var $layouts;  
    // paging variables --------------------------------------------------------
    var $pages_total;
    var $page_current;
    var $req_page_size;
    var $allow_paging;
    var $lower_paging;
    var $upper_paging;    
    // sorting variables -------------------------------------------------------
    var $sort_field;
    var $sort_type;
    var $allow_sorting;
    var $sql_sort;
    // columns style parameters ------------------------------------------------            
    var $nowrap;
    // css style ---------------------------------------------------------------            
    var $css_class;
    var $class_table;
    var $class_tr;
    var $class_th;
    var $class_td;
    var $class_a;
    var $class_a2;
    
    var $headerColor;
    var $tdColor;
    var $rowColor;
    var $rowColor2;
    var $rowColor3;
    var $rowColor4;
    
    
    // table style parameters --------------------------------------------------                        
    var $tblAlign;
    var $tblWidth;
    var $tblBorder;
    var $tblBorderColor;
    var $tblCellSpacing;
    var $tblCellPadding;
    
    // datagrid modes ----------------------------------------------------------                        
    var $modes;
    var $mode;
    var $rid;
    var $tblName;
    var $keyFieldName;

    var $fieldsAsHypertextArray;
    var $foreign_keys_array;
    var $columns_view_mode;
    // debug mode --------------------------------------------------------------                        
    var $debug;
    // message -----------------------------------------------------------------                        
    var $act_msg;
    var $messaging;

    //==========================================================================
    // Member Functions 
    //==========================================================================

    //--------------------------------------------------------------------------
    // default constructor 
    //--------------------------------------------------------------------------
    function DataGrid(){
        $this->unique_extention = "";    
        $this->css_class = "default";
        
        $this->rows = 0;
        $this->rowLower = 0;
        $this->rowUpper = 0;
        $this->columns = 0;            
        $this->colLower = 0;
        $this->colUpper = 0;
        
        $this->layouts['view'] = 0;
        
        $this->pages_total = 0;
        $this->page_current = 0;
        $this->req_page_size = 1;
                
        $this->allow_paging = false;
        $this->lower_paging['results'] = false;
        $this->lower_paging['results_align'] = "left";
        $this->lower_paging['pages'] = false;        
        $this->lower_paging['pages_align'] = "center";
        $this->lower_paging['page_size'] = false;
        $this->lower_paging['page_size_align'] = "right";
        $this->upper_paging['results'] = false;
        $this->upper_paging['results_align'] = "left";
        $this->upper_paging['pages'] = false;        
        $this->upper_paging['pages_align'] = "center";
        $this->upper_paging['page_size'] = false;
        $this->upper_paging['page_size_align'] = "right";
        
        $this->sort_field = "";
        $this->sort_type = "";
        $this->allow_sorting = true;
        $this->sql_view = "";
        $this->sql = "";
        $this->sql_sort = "";
        
        $this->tblAlign['view'] = "center";         
        $this->tblWidth['view'] = "95%";            
        $this->tblBorder['view'] = "1";             
        $this->tblBorderColor['view'] = "#000000";  
        $this->tblCellSpacing['view'] = "0";        
        $this->tblCellPadding['view'] = "0";        
        
        $this->modes = "";
        $this->mode = "view";
        $this->rid = "";
        $this->tblName ="";
        $this->keyFieldName = 0;

        $this->fieldsAsHypertextArray = "";
        $this->foreign_keys_array = array();
        
        $this->columns_view_mode =array();
              
        $this->nowrap = "";
        $this->debug = false;
        $this->messaging = true;

    }

    //--------------------------------------------------------------------------
    // set unique names
    //--------------------------------------------------------------------------
    function setUniqueNames($unique_names = ""){
        $this->unique_extention = $unique_names;
    }

    //--------------------------------------------------------------------------
    // set css class
    //--------------------------------------------------------------------------
    function setCssClass($class = "default"){
        $this->css_class = $class;
        if(strtolower($this->css_class) == "like adwords"){
            $this->class_table  = '.class_table {align: center; width: 100%; border-collapse: collapse; border: 1px solid #3f7c5f; font: normal 80%/140% arial, verdana, helvetica, sans-serif; color: #000; background: #fff;}';
            $this->class_tr ="";
            $this->class_th  = '.class_th {border: 1px solid #e0e0e0; border-bottom: 1px solid #3f7c5f; text-align: center; font-size: 1.3em; font-weight: bold; background: #c6d7cf; padding: 0.3em;}';
            $this->class_td_main  = '.class_td_main {border: 1px solid #e0e0e0; font-size: 1.3em; padding: 0.15em;}';
            $this->class_td  = '.class_td {border: 1px solid #e0e0e0; font-size: 1.3em; padding: 0.15em;}';
            $this->class_a   = 'a.class_a {background: transparent; color: #3f7c5f; text-decoration: none; font-weight: bold;}';
            $this->class_a  .= 'a.class_a:hover {background: transparent; color: #6fac8f; text-decoration: none; font-weight: bold;}';
            $this->class_a  .= 'a.class_a:visited {color: #b98b00; font-weight: bold;}';

            $this->class_a2  = 'a.class_a2 {background: transparent; color: #3f7c5f; text-decoration: none; }';
            $this->class_a2 .= 'a.class_a2:hover {background: transparent; color: #6fac8f; text-decoration: none; }';
            $this->class_a2 .= 'a.class_a2:visited {color: #b98b00; }';

            $this->rowColor="#ffffff";
            $this->rowColor2="#e4f5ef";            
            $this->rowColor3="#ffffff";
            $this->rowColor4="#e4f5ef";            

        }else if(strtolower($this->css_class) == "salomon") {

            $this->class_table  = '.class_table {align: center; width: 100%; border:1px solid #000;border-collapse:collapse;margin:0;padding:0;}';
            $this->class_tr  = "";
            $this->class_th  = '.class_th { padding:0.5em;vertical-align:top;font-weight:normal;border:1px solid #000;border-collapse:collapse;margin:0; text-transform:uppercase;background:#666;color:#fff;background:#999;}';
            $this->class_td_main  = '.class_td_main {padding:0.10em;vertical-align:top;font-weight:normal;border:1px solid #000;border-collapse:collapse;margin:0;font-size: 1.1em;}}';
            $this->class_td  = '.class_td {padding:0.10em;vertical-align:top;font-weight:normal;border:1px solid #000;border-collapse:collapse;margin:0;font-size: 1.1em;}}';
            $this->class_a   = 'a.class_a {color:#003000; text-decoration:none; }';
            $this->class_a  .= 'a.class_a:hover {text-decoration:underline; }';
            $this->class_a  .= 'a.class_a:visited {color:#003000; }';
            $this->class_a2   = 'a.class_a2 {color:#306090;	text-decoration:none;}';
            $this->class_a2  .= 'a.class_a2:hover {color:#306090;	text-decoration:underline;}';
            $this->class_a2  .= 'a.class_a2:visited {color:#000000;	 }';
            $this->rowColor="#f0f0f0";
            $this->rowColor2="#e0e0e0";            
            $this->rowColor3="#d0d0d0";
            $this->rowColor4="#c0c0c0";            

        }else {
            $this->css_class = "defailt";
            $this->class_table  = '.class_table {align: center; width: 100%; border:1px solid #000;border-collapse:collapse;margin:0;padding:0;}';
            $this->class_th  = '.class_th { padding:0.5em;vertical-align:top;font-weight:normal;border:1px solid #ccc; border-bottom: 1px solid #000; border-collapse:collapse;margin:0; text-transform:uppercase;color:#fff;background:#999;}';
            $this->class_td_main  = '.class_td_main {padding:0.10em;vertical-align:center;font-weight:normal;border:1px solid #ccc;border-collapse:collapse;margin:0;font-size: 1.1em;}}';
            $this->class_td  = '.class_td {padding:0.10em;vertical-align:center;font-weight:normal;border:1px solid #ccc;border-collapse:collapse;margin:0;font-size: 1.1em;}}';
            $this->rowColor="#f0f0f0";
            $this->rowColor2="#e0e0e0";            
            $this->rowColor3="#d0d0d0";
            $this->rowColor4="#c0c0c0";            
        }        
    }

    //--------------------------------------------------------------------------
    // write css class
    //--------------------------------------------------------------------------
    function writeCssClass(){
        echo "<style>";
        echo $this->class_table;
        echo $this->class_tr;
        echo $this->class_th;
        echo $this->class_td_main;
        echo $this->class_td;
        echo $this->class_a;
        echo $this->class_a2;
        echo "</style>";    
    }

    //--------------------------------------------------------------------------
    // set data source 
    //--------------------------------------------------------------------------
    function dataSourse($db_handl, $sql){
        $this->db_handler = $db_handl;
        $this->sql_view = $sql;
        $this->sql = $sql;
        
        if(isset($_REQUEST['sort_field'])) $this->sql_sort = " ORDER BY ".$_REQUEST['sort_field']." ".$_REQUEST['sort_type'];
        else if(!substr_count($this->sql, "ORDER BY"))  $this->sql_sort = " ORDER BY 1 DESC ";
        $this->getDataSet($this->sql_sort);            
    }    

    //--------------------------------------------------------------------------
    // get DataSet
    //--------------------------------------------------------------------------
    function getDataSet($fsort, $limit=""){
       $db = GetGlobal('db');	
	
        //echo $this->sql.$fsort." ".$limit."<br>";
        //$this->dataSet = mysql_query($this->sql.$fsort." ".$limit, $this->db_handler);        
		$this->dataSet = $db->Execute($this->sql.$fsort." ".$limit,2);
		
        //if ((!$this->dataSet) || (mysql_affected_rows($this->db_handler) <= 0) ){
		if ((!$this->dataSet) || ($db->affected_rows() <= 0) ){
            $this->tblOpen();
            $this->noDataFound();
            $this->tblClose();
            exit;
        }
        //$this->rows = mysql_num_rows($this->dataSet);
		$this->rows = $db->num_rows($this->dataSet);
        $this->rowLower = 0;
        $this->rowUpper = $this->rows;

        $this->colLower = 0;
        //$this->columns = mysql_num_fields($this->dataSet);
		$this->columns = $db->MetaColumns('subscribers');
        $this->colUpper = $this->columns;
    }
    
    //--------------------------------------------------------------------------
    // bind data and draw 
    //--------------------------------------------------------------------------
    function bind($debug_mode=false, $messaging = true){
        $this->debug = $debug_mode;
        if($this->debug) error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
        $this->messaging = $messaging;
        
        //$this->mode
        if(isset($_REQUEST['mode']) && ($_REQUEST['mode'] === "delete") && (isset($_REQUEST['rid']))){          
           $this->rid = $_REQUEST['rid'];
           $this->sql = $this->sql_view; $this->getDataSet($this->sql_sort);            
           $this->deleteRow($this->rid);
           $this->mode = "view";
        }
        if($this->dataSet){
            if(($this->mode === "edit") || ($this->mode === "add")) $layout_type = "edit";
            else $layout_type = "view";
            if($this->layouts[$layout_type] == 0) $this->draw_tabular();
            else if($this->layouts[$layout_type] == 1) $this->draw_columnar();
            else $this->draw_tabular();            
        }
    }

    //--------------------------------------------------------------------------
    // set settings functions
    //--------------------------------------------------------------------------
    function setLayouts($layouts){
        if(isset($layouts['view'])) $this->layouts['view'] = $layouts['view']; else $this->layouts['view'] = 0;
        if(isset($layouts['edit'])) $this->layouts['edit'] = $layouts['edit']; else $this->layouts['edit'] = 0;
    }
    function setColumnsNoWrap($nowrap){ $this->nowrap = $nowrap; }
    function setTableSettings($parameters){
        if(isset($parameters['view']['align'])) $this->tblAlign['view'] = $parameters['view']['align'];
        if(isset($parameters['view']['width'])) $this->tblWidth['view'] = $parameters['view']['width'];
        if(isset($parameters['view']['border'])) $this->tblBorder['view'] = $parameters['view']['border'];
        if(isset($parameters['view']['bordercolor'])) $this->tblBorderColor['view'] = $parameters['view']['bordercolor'];
        if(isset($parameters['view']['cellspacing'])) $this->tblCellSpacing['view'] = $parameters['view']['cellspacing'];
        if(isset($parameters['view']['cellpadding'])) $this->tblCellPadding['view'] = $parameters['view']['cellpadding'];
    }
    function setPagingSettings($lower=false, $upper=false){
        if($lower){
            if($lower['results']) $this->lower_paging['results'] = $lower['results'];
            if($lower['results_align']) $this->lower_paging['results_align'] = $lower['results_align'];
            if($lower['pages']) $this->lower_paging['pages'] = $lower['pages'];            
            if($lower['pages_align']) $this->lower_paging['pages_align'] = $lower['pages_align'];
            if($lower['page_size']) $this->lower_paging['page_size'] = $lower['page_size'];
            if($lower['page_size_align']) $this->lower_paging['page_size_align'] = $lower['page_size_align'];
        }
        if($upper){
            if($upper['results']) $this->upper_paging['results'] = $upper['results'];
            if($upper['results_align']) $this->upper_paging['results_align'] = $upper['results_align'];
            if($upper['pages']) $this->upper_paging['pages'] = $upper['pages'];            
            if($upper['pages_align']) $this->upper_paging['pages_align'] = $upper['pages_align'];
            if($upper['page_size']) $this->upper_paging['page_size'] = $upper['page_size'];
            if($upper['page_size_align']) $this->upper_paging['page_size_align'] = $upper['page_size_align'];
        }            
    }
    function allowSorting($option = true){ $this->allow_sorting = $option; }    
    function allowPaging($option = true){ $this->allow_paging = $option; }
    
    //--------------------------------------------------------------------------
    // set Fields As Hypertext
    //--------------------------------------------------------------------------    
    function setFieldsAsHypertext($fieldsArray){
        $this->fieldsAsHypertextArray = array();
        $this->fieldsAsHypertextArray = array_merge($this->fieldsAsHypertextArray, $fieldsArray);
    }   

    //--------------------------------------------------------------------------
    // set Columns in View Mode
    //--------------------------------------------------------------------------    
    function setColumnsInViewMode($columns){
        foreach($columns as $fldName => $fldValue){
            $this->columns_view_mode[$fldName] = $fldValue;
        }
    }

    //--------------------------------------------------------------------------
    // table draw functions 
    //--------------------------------------------------------------------------
    function tblOpen($ajax=false){
        $text = "<table id='grdTbl' class='class_table'>".chr(13);
        if($ajax) return $text; else echo $text;
    }
    function tblClose($ajax=false){
        $text = "</table>".chr(13);
        if($ajax) return $text; else echo $text;    
    }
    function rowOpen($id, $rowColor, $ajax=false){
        $text = "<tr class='class_tr' bgcolor='$rowColor' id='$id'>".chr(13);
        if($ajax) return $text; else echo $text;        
    }
    function rowClose($ajax=false){
        $text = "</tr>".chr(13);
        if($ajax) return $text; else echo $text;            
    }
    function mainColOpen($align='left', $colSpan=0, $nowrap='', $width='', $ajax=false){
        $text = "<th class='class_th' "; $text .= ($width !=='')? "width='$width'" : ""; $text .= "colspan='$colSpan' $nowrap>";
        if($ajax) return $text; else echo $text;                
    }
    function mainColClose($ajax=false){
        $text = "</th>".chr(13);
        if($ajax) return $text; else echo $text;                
    }
    function colOpen($align='left', $colSpan=0, $nowrap='', $ajax=false, $bgcolor='', $class_td='class_td', $bgcolor=''){
        $text = "<td class='$class_td' "; $text .= ($bgcolor !== '')? "bgcolor='$bgcolor'":""; $text .= "style='text-align: ".$align.";' colspan='$colSpan'  $nowrap>";
        if($ajax) return $text; else echo $text;                
    }
    function colClose($ajax=false){
        $text = "</td>".chr(13);
        if($ajax) return $text; else echo $text;                
    }

  
    //--------------------------------------------------------------------------
    // draw in tabular layout
    //--------------------------------------------------------------------------
    function draw_tabular(){
        $this->writeCssClass();
        if((isset($_REQUEST['mode']) &&  ($_REQUEST['mode'] !== "add")) || (!isset($_REQUEST['mode']))) $this->paging_part_1();  
        if($this->allow_paging) $this->paging_part_2(false,$this->upper_paging, false, true, "Upper");
        $this->tblOpen();

        // draw column headers
        $this->rowOpen(0, $this->headerColor);
        for($c = $this->colLower; $c < $this->colUpper; $c++){
            if(($this->mode === "view") && ($this->canViewField($this->getFieldName($c)))){
                $this->mainColOpen("center",0,$this->nowrap);
                    if($this->allow_sorting){
                        $href_string = $this->combine_url("view");
                        if(isset($_REQUEST['sort_type']) && $_REQUEST['sort_type'] == "asc") $sort_type="desc";
                        else $sort_type="asc";
                        $href_string .= "&sort_field=".($c+1)."&sort_type=".$sort_type;                        
                        $this->setUrlStringPaging($href_string);
                        echo "<b><a class='class_a' href='$href_string'>".ucfirst($this->getHeaderName($this->getFieldName($c)))."</a></b>";
                    }else{
                        echo "<b>".ucfirst($this->getHeaderName($this->getFieldName($c)))."</b>";                        
                    }
                $this->mainColClose();
            }
        }
        $this->rowClose();        

        // draw rows        
        for($r = $this->rowLower; (($r >=0 && $this->rowUpper >=0) && ($r < $this->rowUpper) && ($r < ($this->rowLower + $this->req_page_size))); $r++){                   
            $row = mysql_fetch_array($this->dataSet);
            if($r % 2 == 0){$this->rowOpen($r, $this->rowColor); $main_td_color=$this->rowColor3;}
            else  {$this->rowOpen($r, $this->rowColor2); $main_td_color=$this->rowColor4;}
            if($this->modes['view']['allow']){
                      $curr_url = $this->combine_url("edit", $row[$this->keyFieldName]);
                      $this->setUrlStringPaging($curr_url);
                      $this->setUrlStringSorting($curr_url);
                      if(isset($_REQUEST['new']) && (isset($_REQUEST['new']) == 1)){
                        $curr_url .= "&new=1";
                      }
                      if ($this->modes['edit']['byfield'] == ""){ 
                        $this->colOpen("center",0,$this->nowrap,false,"","class_td_main",$main_td_color);echo "&nbsp;<a class='class_a' href='$curr_url'>Edit</a>&nbsp;";$this->colClose();
                      }else{                      
                        $this->colOpen("left",0,$this->nowrap,false,"","class_td_main", $main_td_color);echo "&nbsp;<a class='class_a' href='$curr_url'>".$row[$this->modes['edit']['byfield']]."</a>&nbsp;";$this->colClose();
                      }
            }                      
            // draw columns
            for($c = $this->colLower; $c < $this->colUpper; $c++){
                if(is_numeric($row[$c])) $col_align = "right"; else $col_align = "left";
                if(($this->mode === "view") && ($this->canViewField($this->getFieldName($c)))){
                    $this->colOpen($col_align,0,$this->nowrap);
                    echo $this->getFieldValueByType($row[$c], $c);
                    $this->colClose();                    
                }
            }
            $this->rowClose();
        }               
        if($r == $this->rowLower){ $this->noDataFound(); }
        $this->tblClose();
        if($this->allow_paging) $this->paging_part_2(false,$this->lower_paging, true, true, "Lower");               
    }    
  
    //--------------------------------------------------------------------------
    // draw in columnar layout
    //--------------------------------------------------------------------------
    function draw_columnar(){
        $r = ""; //???
        $this->writeCssClass();
        if(($this->layouts['view'] == 0) && ($this->layouts['edit'] == 1)){
            $this->req_page_size = 1;            
        }else{
        if((isset($_REQUEST['mode']) &&  ($_REQUEST['mode'] !== "add")) || (!isset($_REQUEST['mode']))) $this->paging_part_1();  
        }
        
        if($this->allow_paging) $this->paging_part_2(false,$this->upper_paging, false, true, "Upper");
        echo "<form name='frmEditRow$this->unique_extention' method='post'>";
        $this->tblOpen();
        // draw header
        $this->rowOpen($r, $this->headerColor);
        $this->mainColOpen("center",0,$this->nowrap);echo "<b>Field</b>";$this->mainColClose();
        $this->mainColOpen("center",0,$this->nowrap);echo "<b>Field Value</b>";$this->mainColClose();
        $this->rowClose();        

        // draw rows
        for($r = $this->rowLower; (($r < $this->rowUpper) && ($r < ($this->rowLower + $this->req_page_size))); $r++){                   
            $row = mysql_fetch_array($this->dataSet);
            for($c = $this->colLower; $c < $this->colUpper; $c++){
                if($r % 2 == 0) $this->rowOpen($r, $this->rowColor);
                else  $this->rowOpen($r, $this->rowColor2);
                
                // column headers
                if(($this->mode === "view") && ($this->canViewField($this->getFieldName($c)))){
                    $this->colOpen("left",0,$this->nowrap);                   
                    echo "&nbsp;<b>".ucfirst($this->getFieldName($c))."</b>";
                    $this->colClose();
                }                
                
                // column data                
                $col_align = "right"; 
                if(($this->mode === "view") && ($this->canViewField($this->getFieldName($c)))){
                    $this->colOpen($col_align,0,$this->nowrap);
                    echo $this->getFieldValueByType($row[$c], $c)."&nbsp;";
                    $this->colClose();                    
                }
                $this->rowClose();                       
            }
            $this->rowOpen($r, $this->rowColor);
            $this->colOpen("left",2,$this->nowrap);
            $this->colClose();
            $this->rowClose();                       
        }
        $this->tblClose();
        echo "<br />";
        $this->tblOpen();$this->rowOpen($r, $this->rowColor2);        
        if($r == $this->rowLower){ $this->noDataFound(); }
        else{
            $this->mainColOpen("center",0,$this->nowrap);
            $this->mainColClose();            
        }
        $this->rowClose();
        $this->tblClose();        
  
        echo "</form>";
        if($this->allow_paging) $this->paging_part_2(false,$this->lower_paging, true, true, "Lower");               
    }            

    //--------------------------------------------------------------------------
    // combine url 
    //--------------------------------------------------------------------------
    function combine_url($mode, $rid=""){
        if(isset($_REQUEST['act'])){
            $a_url = "?act=".$_REQUEST['act']."&mode=".$mode."";
        }else {
            $a_url = "?mode=add";
        }
        if($rid !== "") $a_url .= "&rid=".$rid;
        return $a_url;         
    }
   
    //--------------------------------------------------------------------------
    // paging function - part 1
    //--------------------------------------------------------------------------
    function paging_part_1($ajax=false,$p='', $page_size=''){
        // (1) if we got a wrong number of page -> set page=1
        $req_page_num  = "";
        if($ajax){
            if($page_size) $this->req_page_size = $page_size;
            else $this->req_page_size = 10;
            if($p) $req_page_num  = $p;
            else $req_page_num = 1;
        }else {
            //if($_REQUEST[page_size]) $this->req_page_size = $_REQUEST[page_size];
            if(isset($_REQUEST['page_size'])) $this->req_page_size = $_REQUEST['page_size'];
            else $this->req_page_size = "10";
            if(isset($_REQUEST['p']))$req_page_num  = $_REQUEST['p'];
        }      
        
        if(is_numeric($req_page_num)){
            if($req_page_num > 0) $this->page_current = $req_page_num;
            else $this->page_current = 1;
        }else{
            $this->page_current = 1;
        }
                
        // (2) set pages_total & page_current vars for paging
        if(($srow=mysql_num_rows($this->dataSet)) > 0){
            if(is_float($srow / $this->req_page_size))
                $this->pages_total = intval(($srow / $this->req_page_size) + 1);
            else
                $this->pages_total = intval($srow / $this->req_page_size);
            }else{
                $this->pages_total = 0;
            }   
            if($this->page_current > $this->pages_total) $this->page_current = $this->pages_total;
               
                
        // (3) run until current start row
        $rows_offset = (($this->page_current-1)*$this->req_page_size);
        if($rows_offset >= 0){
            mysql_data_seek($this->dataSet, $rows_offset);
            $this->rowLower += $rows_offset;
        }
    }

    //--------------------------------------------------------------------------
    // paging function - part 2
    //--------------------------------------------------------------------------    
    function paging_part_2($ajax=false, $lu_paging=false, $upper_br, $lower_br, $type="1"){
        // (4) display paging line
        $text = "";
        $param1 = "document.getElementById('hdnProvince').value";
        $param2 = "document.getElementById('selCity').value";        
        $param6 = "'1'";
        $param7 = "'asc'";                           
        $onClick = "onClick=\"xajax_fillDepartments($param1, $param2, $param6, $param7, $this->page_current, $this->req_page_size); return false; \"";
        if($this->pages_total > 1){
            $href_string = $this->combine_url("view");
            $this->setUrlStringSorting($href_string);
            $text .= "<script type='text/JavaScript'>";
            $text .= "function setPageSize".$type."(){document.location.href = '$href_string&page_size='+document.frmPaging$this->unique_extention".$type.".page_size".$type.".value;}";
            $text .= "</script>";
            $href_string .= "&page_size=".$this->req_page_size;
            $text .= "<form name='frmPaging$this->unique_extention".$type."'>";
            if($lu_paging['results'] || $lu_paging['pages'] || $lu_paging['page_size']) if($upper_br) $text .= "<br />";
            $text .= "<table align='$this->tblAlign' width='$this->tblWidth' border='0' >";
            $text .= "<tr><td align='".$lu_paging['results_align']."' nowrap>";
            if($lu_paging['results']){
                $text .= "&nbsp;Results:&nbsp;";
                if(($this->page_current * $this->req_page_size) <= $this->rows) $total = ($this->page_current * $this->req_page_size);
                else $total = $this->rows;
                $text .= ($this->page_current * $this->req_page_size - $this->req_page_size + 1)." - ".$total;
                $text .= "&nbsp;of about:&nbsp;";
                $text .= $this->rows;
                $text .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";            
            }
            $text .= "</td><td align='".$lu_paging['pages_align']."' nowrap>";
            if($lu_paging['pages']){
                $text .= "&nbsp;Pages:&nbsp;";
                $text .= "&nbsp;<a title='first' class='class_a' style='text-decoration: none;' href='$href_string&p=1'>|<<</a>";
                if($this->page_current > 1) $text .= "&nbsp;<a class='class_a' style='text-decoration: none;' title='previous' href='$href_string&p=".($this->page_current - 1)."'><<</a>";
                else $text .= "&nbsp;<a class='class_a' style='text-decoration: none;' title='previous' href='$href_string&p=".$this->page_current."'><<</a>";
                $text .= "&nbsp;";
                $low_window_ind = $this->page_current - 3;
                $high_window_ind = $this->page_current + 3;
                if($low_window_ind > 1){ $start_index = $low_window_ind; $text .= "..."; }
                else $start_index = 1;
                if($high_window_ind < $this->pages_total) $end_index = $high_window_ind;
                else $end_index = $this->pages_total;
                for($ind=$start_index; $ind <= $end_index; $ind++){
                    if($ind == $this->page_current) $text .= "&nbsp;<a class='class_a' style='text-decoration: underline;' title='current' href='$href_string&p=".$ind."'><b><u>" . $ind . "</u></b></a>";
                    else $text .= "&nbsp;<a class='class_a' style='text-decoration: none;' href='$href_string&p=".$ind."'>".$ind."</a>";
                    if($ind < $this->pages_total) $text .= ",&nbsp;";
                    else $text .= "&nbsp;";
                }
                if($high_window_ind < $this->pages_total) $text .= "...";
                if($this->page_current < $this->pages_total) $text .= "&nbsp;<a class='class_a' style='text-decoration: none;' title='next' href='$href_string&p=".($this->page_current + 1)."'>>></a>";
                else $text .= "&nbsp;<a class='class_a' style='text-decoration: none;' title='next' href='$href_string&p=".$this->page_current."'>>></a>";
                $text .= "&nbsp;<a class='class_a' style='text-decoration: none;' title='last' href='$href_string&p=".$this->pages_total."'>>>|</a>";
            }
            $text .= "</td><td align='".$lu_paging['page_size_align']."' nowrap>";            
            if($lu_paging['page_size']){
                $text .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";            
                $text .= "&nbsp;Page size:&nbsp;";                
                $text .= "<select name='page_size".$type."' onChange='setPageSize".$type."()'>";            
                $text .= "<option value='10' "; if($this->req_page_size == 10) $text .= "selected"; $text .= ">10</option>";
                $text .= "<option value='25' "; if($this->req_page_size == 25) $text .= "selected"; $text .= ">25</option>";
                $text .= "<option value='50' "; if($this->req_page_size == 50) $text .= "selected"; $text .= ">50</option>";
                $text .= "<option value='100' "; if($this->req_page_size == 100) $text .= "selected"; $text .= ">100</option>";
                $text .= "<option value='250' "; if($this->req_page_size == 250) $text .= "selected"; $text .= ">250</option>";
                $text .= "<option value='500' "; if($this->req_page_size == 500) $text .= "selected"; $text .= ">500</option>";
                $text .= "<option value='1000' "; if($this->req_page_size == 1000) $text .= "selected"; $text .= ">1000</option>";
                $text .= "</select>";
            }
            $text .= "</td></tr>";            
            $text .= "</table>";
            $text .= "</form>";
            if($lu_paging['results'] || $lu_paging['pages'] || $lu_paging['page_size']) if($lower_br) $text .= "<br />";
            if($ajax) return $text;
            else echo $text;
        }               
    }
   
    //--------------------------------------------------------------------------
    // function - no data found
    //--------------------------------------------------------------------------
    function noDataFound($ajax=false){
        $text = $this->rowOpen(0, $this->rowColor, $ajax);
        $add_column = 0;
        if($this->modes['add'][$this->mode] || $this->modes['edit'][$this->mode]) $add_column += 1;
        if($this->mode['delete']) $add_column += 1;
        $text .= $this->colOpen("center", ($this->colUpper + $add_column),"", $ajax); 
        if($ajax) $text .= "<br>No data found<br>&nbsp;"; else echo "<br>No data found<br>&nbsp;";
        $text .= $this->colClose($ajax);                   
        $text .= $this->rowClose($ajax);
        if($ajax) return $text;
    }

    //--------------------------------------------------------------------------
    // get field offset
    //--------------------------------------------------------------------------
    function getFieldOffset($field_name){
        for($ind=0; $ind < mysql_num_fields($this->dataSet); $ind++){
            if($this->getFieldName($ind) === $field_name)
               return $ind;
        }
        return -1;
    }
   
    //--------------------------------------------------------------------------
    // check if field can Be Hypertext
    //--------------------------------------------------------------------------
    function canBeHypertext($field_name){
        if(array_key_exists($field_name, $this->fieldsAsHypertextArray)){
           return $this->fieldsAsHypertextArray[$field_name]; 
        }
        return false;
    }

    //--------------------------------------------------------------------------
    // check if field type needs ''(text) or not (numeric...)
    //--------------------------------------------------------------------------
    function isText($field_name){
        $field_type = mysql_field_type($this->dataSet, $this->getFieldOffset($field_name));
        $result = true;
        switch (strtolower($field_type)){
            case 'int':     // int: TINYINT, SMALLINT, MEDIUMINT, INT, INTEGER, BIGINT, TINY, SHORT, LONG, LONGLONG, INT24
            case 'real':    // real: FLOAT, DOUBLE, DECIMAL, NUMERIC        
                $result = false; break;
            case 'string':  // string: CHAR, VARCHAR, TINYTEXT, TEXT, MEDIUMTEXT, LONGTEXT, ENUM, SET, VAR_STRING
            case 'date':    // date: DATE
                $result = true; break;
            default:
                $result = true; break;
        }
        return $result;    
    }

    //--------------------------------------------------------------------------
    // setUrlString Sorting
    //--------------------------------------------------------------------------  
    function setUrlStringSorting(&$curr_url){
        if(isset($_REQUEST['sort_field'])) {
           $this->sort_field = $_REQUEST['sort_field'];
           $curr_url .= "&sort_field=".$this->sort_field;
        }else {
            $curr_url .= "&sort_field=1";
        }; // make pKey      
        if(isset($_REQUEST['sort_type'])) {
            $this->sort_type = $_REQUEST['sort_type'];
            $curr_url .= "&sort_type=".$this->sort_type;
        } else {
            $curr_url .= "&sort_type=desc";
        };          
    }

    //--------------------------------------------------------------------------
    // setUrlString Pading
    //--------------------------------------------------------------------------  
    function setUrlStringPaging(&$curr_url){
        if($this->layouts['edit'] == 0){            
            if(isset($_REQUEST['page_size'])){
                $this->req_page_size = $_REQUEST['page_size'];
                $curr_url .= "&page_size=".$this->req_page_size;
            }else{ 
                $curr_url .= "&page_size=".$this->req_page_size;
            }            
        }else{            
            if($this->mode === "view"){
                $curr_url .= "&page_size=".$this->req_page_size;
            }else{
                if(isset($_REQUEST['page_size'])) $this->req_page_size = $_REQUEST['page_size']; 
                $curr_url .= "&page_size=".$this->req_page_size;
            }
        }
        if(isset($_REQUEST['p'])) {
            $this->page_current = $_REQUEST['p'];
            $curr_url .= "&p=".$this->page_current;
        }else {
            $this->page_current = 1;
            $curr_url .= "&p=1";
        };
    } 

    ////////////////////////////////////////////////////////////////////////////
    // View & Edit mode methods
    ////////////////////////////////////////////////////////////////////////////
    //--------------------------------------------------------------------------
    // check if field exists & can be viewed
    //--------------------------------------------------------------------------
    function canViewField($field_name){
        if($this->mode === "view"){
            if(array_key_exists($field_name, $this->columns_view_mode)) return true;    
        }        
        return false;
    }

    //--------------------------------------------------------------------------
    // check if field exists & can be viewed
    //--------------------------------------------------------------------------
    function getHeaderName($field_name){        
        if($this->mode === "view"){
            if(array_key_exists($field_name, $this->columns_view_mode))
                return $this->columns_view_mode[$field_name]['header']; 
        }        
        return $field_name;
    }
    //--------------------------------------------------------------------------
    // Returns field name
    //--------------------------------------------------------------------------
    function getFieldName($field_offset){
        $field_name = mysql_field_name($this->dataSet,$field_offset);
        if($field_name) return $field_name;
        else return $field_offset;
    }  
    
    //--------------------------------------------------------------------------
    // getFieldValueByType
    //--------------------------------------------------------------------------
    function getFieldValueByType($field_value, $ind){
        $field_name = $this->getFieldName($ind);
        if($this->mode === "view"){
            if(array_key_exists($field_name, $this->columns_view_mode)){
                switch($this->columns_view_mode[$field_name]['type']){
                    case "label":
                        return "&nbsp;<label>".trim($field_value)."</label>&nbsp;"; break;
                    case "image":
                        if((trim($field_value) !== "") && (file_exists(trim($field_value))))  
                            return "&nbsp;<img src='".trim($field_value)."' border=1 width='50px' height='30px' align='middle' />&nbsp;";
                        else
                            return "<div style='align: middle; display: block; border-color: #000000; width:50px; height: 30px'>[No]</div>";
                        break;
                    case "link":
                        return "&nbsp;<a class='class_a2' href='".$this->columns_view_mode[$field_name]['href']."' target='".$this->columns_view_mode[$field_name]['target']."'>".trim($field_value)."</a>&nbsp;";                    
                        break;
                    default:
                        return "&nbsp;<label>".trim($field_value)."</label>&nbsp;"; break;
                }
            }            
        }        
        return false;
    }


}// end of class




?>