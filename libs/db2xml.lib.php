<?php

/**
* Convert a result table from an SQL query into basic XML.
*
* Very simple conversion of SQL query results table to XML data which may then
* be transformed into a suitable structure. The results can be returned as a 
* well-formed fragment _without_ an XML declaration or as a stand-alone XML 
* document. The resultant DOM object can also be accessed by reference to which
* other manipulations are applied before obtaining the result as serialised XML.
* No namespaces are implemented at this stage so the XML structure will need to
* take into account the structure of the database results (field names) since 
* the name of the field will become the element name.
*
* @author       Terence Kearns (terencekearns.com)
* @version      1.0b
* @copyright    Terence Kearns
* @license      LGPL
*/

class DbToXml {

    /**
    * An instance of a DOM XML object.
    *
    * @access   public
    * @var      object  
    */
    var $objDOM;
    
    /**
    * The root element node associated with the DOM XML object instance.
    *
    * @access   public
    * @var      object  
    */
    var $docRoot;
    
    /**
    * The name of the result element (root element). 
	* This is usually changed by the child class.
    *
    * @access   public
    * @var      string  
    */
    var $strResEl               = "result";
    
    /**
    * The name of the row element.
	* This is usually changed by the child class.
    *
    * @access   public
    * @var      string  
    */
    var $strRowEl               = "row";
    
    /**
    * An instance of a PEAR DB result object.
    *
    * @access   public
    * @var      object  
    */
    var $objDBResult;
    
    /**
    * 2D array containing the rows and colums of the result returned from the 
    * passed DB result object.
    *
    * @access   private
    * @var      object  
    */
    var $arrResult              = array();

    /**
    * Field-name/Element-name function mapping.
    *
    * This member is populated with the optional second argument to the 
    * constructor function.
    *
    * @access   private
    * @var      array  
    */
    var $arrCallBacks;
    
    /**
    * Constructor method 
    *
    * Perform requirements checks and initialised resources.
    *
    * @param    object  An instance of a PEAR DB result object.
    * @param    object  An associative array mapping result column names to
	*					user-defined class function names of the child class.
    * @param    object  An instance an existing DOM document object if this
	* 					XML result tree is to be appended to an existing XML
	* 					result tree.
    * @param    object  The node to which this result tree will be appended if
	*					a valid DOM document is to be appended to.
    * @return   void
    */
    
    function DbToXml(&$objDBResult,$arrCallBacks,&$objDOM,&$ndStub) {
        $this->arrCallBacks = $arrCallBacks;
                                        // requirements checks
        if(!function_exists("domxml_new_doc"))
                                        // this is the only error which uses
                                        // die() isntead of $this->throw()
            die(
                "DbToXml: The DOM XML extension is not enabled on this"
                ." PHP server or this version of PHP is older than 4.3.0."
            );

        if(is_array($objDBResult)) {
            $this->arrResult = $objDBResult;
                                    // create our reference DOM object
            $this->objDOM = domxml_new_doc("1.0");
                                    // add a root node to our result DOM 
                                    // document
            $elRoot = $this->objDOM->create_element($this->strResEl);
            $this->ndRoot = $this->objDOM->append_child($elRoot);
        }
        else {
            if(DB::isError($objDBResult)) 
                $this->throw(
                    "DbToXml: The DB result object you passed to DbToXml returned an error:\n"
                    .$objDBResult->getMessage() //.":\n".$objDBResult->getUserinfo() // security risk - displays DB password.
                );
            if(!isset($objDBResult->result)) // a better check should be implemented here.
                $this->throw(
                    "DbToXml: The DB result object you passed to DbToXml "
                    ."is not a valid DB query result."
                );
                                        // initialise resources
            $this->objDBResult =& $objDBResult;
                                        // check to see if this tree is being
                                        // grafted onto an existing node or if
                                        // it is to be created from scratch.
            if(is_object($objDOM) && is_object($ndStub)) {
                                        // use existing DOM object
                $this->objDOM =& $objDOM;
                                        // use provided stub as root node
                $this->ndRoot =& $ndStub;
            }
            else {
                                        // create our reference DOM object
                $this->objDOM = domxml_new_doc("1.0");
                                        // add a root node to our result DOM 
                                        // document
                $elRoot = $this->objDOM->create_element($this->strResEl);
                $this->ndRoot = $this->objDOM->append_child($elRoot);
            }
                                        // fetch the result data (list of rows) 
                                        // into an array of associative arrays.
            while($this->arrResult[] = $this->objDBResult->fetchRow(DB_FETCHMODE_ASSOC));
                                        // get rid of the extra record from the while statement.
            array_pop($this->arrResult);
        }
    }
    
    /**
    * Used to implement exception handling for this class.
    *
    * What this method does is self-explanatory. You may like to create a 
    * template (<xsl:template match="//exception" />) to handle error output
    * gracefully. Alternatively, you may like to override this function if you
    * already implement another exception handling scheme.
    *
    * @param    string  The error message
    * @return   void
    */
    // you may want to override this method.
	function throw($strErrMsg) {
        $this->objDOM = domxml_new_doc("1.0");
        $elRoot = $objDOM->create_element("exception");
        $ndRoot = $objDOM->append_child($elRoot);
        $ndRoot->set_content($strErrMsg);
        $ndRoot->set_attribute("source","DbToXml");
	}
	
    /**
    * Convert the RDBMS data into XML elements.
    *
    * Run the fetch method on the result object to obtain the result data in a 
    * 2D array. Iterate over this 2D array to first obtain the rows and then the
    * fields which are created and appended as elements using DOM XML methods.
    *
    * @return   void
    */
    function execute() {
                                        // work-around so name can be re-defined
                                        // *after* the constructor.
        if($this->strResEl != "result") $this->ndRoot->set_name($this->strResEl);
                                        // try to include a record of the SQL
                                        // used to generate this fragment.
                                        // THE FOLLOWING CODE DOESN'T WORK BECAUSE 
                                        // SOMETHIMES THE "LAST_QUERY" IS NOT THE 
                                        // ONE USED.
        /*
        if(isset($this->objDBResult->dbh->last_query)) {
            $elQry = $this->objDOM->create_element("DbToXml_source_sql");
            $ndQry = $this->ndRoot->append_child($elQry);
            $ndQry->set_content(trim($this->objDBResult->dbh->last_query));
        }
        */
                                        // the following check is done outside the
                                        // array looping to increase performance.
                                        // performing the check for each field or
                                        // each row is unnecesary if there are no
                                        // callbacks. This raises _consistency_
                                        // issues when maintaining two separate 
                                        // blocks of almost identical code.
                                        // I would have added support for regular
                                        // expressions but that would have proven
                                        // too costly for performance.
        if(count($this->arrCallBacks)) {
                                        // iterate through the result list
                                        // calling user-defined functions as we 
                                        // go
            foreach($this->arrResult AS $arrRow) {
                                        // add a row element for each row in the 
                                        // result list
                $elRow = $this->objDOM->create_element($this->strRowEl);
                $ndRow = $this->ndRoot->append_child($elRow);
                                        // iterate through the fields in the row
                foreach($arrRow AS $fieldName => $fieldVal) {
                                        // DON'T CREATE EMPTY TAGS!
                    if(strlen($fieldVal) && !is_int($fieldName)) {
                                        // add an element for each non-empty field
                        $elField = $this->objDOM->create_element($fieldName);
                        $ndField = $ndRow->append_child($elField);
                                        // CHECK FOR CALLBACKS - this check impacts 
                                        // on performance - let alone execution of
                                        // the requested function.
                        if(isset($this->arrCallBacks[$fieldName])) {
                            $funcName = $this->arrCallBacks[$fieldName];
                                        // re-assign the field value to whatever
                                        // is returned by the callback.
                            $fieldVal = $this->$funcName($this->objDOM,$ndField,$fieldVal);
                        }
                        $ndField->set_content($fieldVal);
                    }
                }
            }
        }
                                        // if no call-backs car defined
        else {
            foreach($this->arrResult AS $arrRow) {
                                        // add a row element for each row in the 
                                        // result list
                $elRow = $this->objDOM->create_element($this->strRowEl);
                $ndRow = $this->ndRoot->append_child($elRow);
                                        // iterate through the fields in the row
                foreach($arrRow AS $fieldName => $fieldVal) {
                                        // DON'T CREATE EMPTY TAGS!
                    if(strlen($fieldVal) && !is_int($fieldName)) {
                                        // add an element for each non-empty field
                        $elField = $this->objDOM->create_element($fieldName);
                        $ndField = $ndRow->append_child($elField);
                        $ndField->set_content($fieldVal);
                    }
                }
            }
        }
        
                                        // at the end of this class function, 
                                        // all the query results are in-memory
                                        // but it is still up to the user to
                                        // extract it.
    }

    /**
    * Method to serialise the resultant DOM data to an XML document string.
    *
    * This returns a full stand-alone well-formed XML document. The serialised
    * output is also formatted.
    *
    * @return   string  Well-formed XML document (results)
    */
    function xmlGetDoc() {
        return $this->objDOM->dump_mem(true);
    }
    
    /**
    * Method to serialise the resultant DOM data to an XML fragment string.
    *
    * This method returns a well-balenced XML fragment originating at the 
    * document root.
    *
    * @return   string  Well-balanced XML fragment (results)
    */
    function xmlGetFrag() {
        return "\n\n".$this->objDOM->dump_node($this->ndRoot,true)."\n\n";
    }

    /**
    * Accessor method for DOM result object.
    *
    * A REFERENCE to the DOM object created in this object instance is returned
    * so that the user may directly modify the DOM before results are serialised
    * into XML. An example usage might be to ad an xsl-stylesheet processing
    * instruction.
    *
    * @return   ref     A reference to the in-memory DOM object
    */
    function &objGetDOM() {
        return $this->objDOM;
    }

    /**
    * Clean up function to flush existing data.
    *
    * Calling this function is needed if the constructor is to be called more
    * than once (in a loop for instance).
    *
    * @return   void
    */
    function reset() {
        $this->arrCallBacks=null;
        $this->arrResult=array();
        $this->objDOM=null;
        $this->ndRoot=null;
        // now the constructor will need to be called again before this
        // object instance can be used further.
    }






/******************************************************************************\
* IMPORTANT NOTICE!
* All of the class methods below this notice are not essential to the operation
* of this class. They are mearly a collection of utility functions that some
* people might find useful as call-backs. They also provide useful examples
* (templates) on how to construct a callback function.
\******************************************************************************/



    /**
    * User-friendly date-formatting for unix timestamps
    *
    * call-back function to convert a unix timestamp into an element which is
    * easier to proces by an XSLT template that is not aware of how to 
    * deconstruct unix timestamp into a human readable date/time.
    *
    * @param    docObj  A reference to the document object in which this tag
    *                   resides.
    * @param    node    A reference to the node to which all the attributes
    *                   and values will apply
    * $param    string  original contents of the element/field
    * @return   void
    */
    function unixTsToReadable(&$objDOM,$ndField,$intTs) {
        $intTs = (integer)$intTs;
        if($intTs < 0) return;
        $ndField->set_attribute("unixTS",$intTs);
        $ndField->set_attribute("ODBCformat",date("Y-m-d H:i:s",$intTs));
        $ndField->set_attribute("year",date("Y",$intTs));
        $ndField->set_attribute("month",date("m",$intTs));
        $ndField->set_attribute("day",date("d",$intTs));
        $ndField->set_attribute("hour",date("H",$intTs));
        $ndField->set_attribute("min",date("i",$intTs));
    }

    /**
    * User-friendly date-formatting for ODBC formatted timestamps
    *
    * call-back function to convert a ODBC formatted timestamp into an element 
    * which is easier to proces by an XSLT template that is not aware of how to 
    * deconstruct unix timestamp into a human readable date/time. 
    * Warning: incorrect formatting of the original string will cause PHP to
    * generate errors. If someone want to write a regex parser for an ODBC
    * formatted timestamp, then be my guest and email it to me.
    *
    * @param    docObj  A reference to the document object in which this tag
    *                   resides.
    * @param    node    A reference to the node to which all the attributes
    *                   and values will apply
    * $param    string  original contents of the element/field
    * @return   string  The original ODBC formatted string
    */
    function odbcToReadable(&$objDOM,$ndField,$odbcTs) {
        if(trim($odbcTs) == "") return;
        
        // example of MS SQL select snippet producing expected format.
        // CAST(DATEPART(yyyy,myDate) AS varchar(64))    + '-' +
        // CAST(DATEPART(mm,myDate) AS varchar(64))      + '-' +
        // CAST(DATEPART(dd,myDate) AS varchar(64))      + ' ' +
        // CAST(DATEPART(hh,myDate) AS varchar(64))      + ':' +
        // CAST(DATEPART(n,myDate) AS varchar(64)) AS myODBCDate,
        
        $arrTs = explode(" ",$odbcTs);
        $arrDate = explode("-",$arrTs[0]);
        $arrTime = explode(":",$arrTs[1]);
        $year   = $arrDate[0];
        $month  = $arrDate[1];
        $day    = $arrDate[2];
        $hour   = $arrTime[0];
        $min    = $arrTime[1];
        if($year < 1970 && $year != 1900) $year = date("Y");
        $unixTs = mktime($hour,$min,0,$month,$day,$year);
        $this->unixTsToReadable($objDOM,$ndField,$unixTs);
        return $odbcTs;
    }
}

?>