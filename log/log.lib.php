<?php
/*
  
  Program   : Common PHP Librarys
  Module    : _logging.lib  
  Date      : 2003-02-04
  Revision  : 1.0
  Copyright : ML design & techniek 
  Author(s) : Frank Kooger
  
  SYNOPSIS  : 
  
    A class for handling logfiles and writing logrecords. The class
    handles logfiles with max. logsizes and makes a backup of the old
    logfile is its max. size is reached. A timer can be set which logs
    how long the procedure took to finish.

    With this class it's very easy to simultaniously write more than 
    one logfile f.e.:
    
    $log      = new Logger();
    $errlog   = new Logger("error.log", "400.000", 2);
    $debuglog = new Logger("debug.log", "", 4, "new");
    
    $log->p("logtext");
    $errorlog->p("errortext");
    $debuglog->p("debugtext");
    

  SYNTAX:

    $log = new Logger( 
                        [str logfilename], 
                        [str logfilesize], 
                        [str loglevel], 
                        [str fileaction], 
                        [str format] 
                      )
  

    logfilename, logfilesize, loglevel:
                      
      The following GLOBAL parameters are checked at constructiontime, so
      these can be set in a config file or procedure:

        $GLOBAL['logfile']
        $GLOBAL['logsize']
        $GLOBAL['loglevel']

      These can be overriden by the optional construction parameters:

        new Logger( logfilesize, logsize, loglevel, ...)

      If none are set these parameters default to:

        this->logfile  = "default.LOG"
        this->logsize  = 0 (no limit)
        this->loglevel = 2
        
    fileaction:

      'fileaction' can have the values: 'append' (new logitems are appended
      to the logfile '$this->logfile') or 'new' (during construction
      '$this->logfile' is newly created). 'fileaction' defaults to
      'append'.
      
    format:

      'format' can have the values "" (empty) or "plain". In the latter
      case no logheader is written to the logfile. 'format' defaults to
      "".


    USAGE:

    constructor:

      $log = new Logger("", "", "3", "new");

        // A new logfile is written. 'logfilename' and 'logfilesize' are
        // read from the GLOBALS 'logfile' and 'logsize'. If these are not
        // set these values default to 'logfilename="default.LOG"' and
        // 'logfilesize=0' (no sizelimit). The loglevel is set to 3. A
        // logheader and footer is written into the logfile.  A loglevel 3
        // means that every logrecord with a level 1, 2 or 3 (or a
        // logrecord without a loglevel, for this defaults to 3) is
        // written into the logfile. 
      
      $log = new Logger();

        // The default logfile or the GLOBALS 'logfile' is openened for
        // appending.


    timer-function:

      $log->startTimer();

        // starts the timer; writes the durationtime in the logtail.


    logging: 
      
      $log->p("logtext"); 

        // log the line 'logtext' with default loglevel
        // If no loglevel is given, the default loglevel is asumed, so a
        // logrecord without a loglevel will allways be written.

      $log->p("logtext", 2); 

        // log 'logtext' with loglevel 2
        // If the loglevel is set to 2, the record will be written if the
        // default loglevel is 2 or higher.

      $log->p("\nlogtext\n", 2); 

        // log 'logtext' with loglevel 2; write an
        // empty line before and after the logrecord.

      $log->p("logtext", 1); 

        // log 'logtext' with loglevel 1. Using
        // loglevel 1 a warningemail is created if the global parameter
        // $GLOBAL['mailto'] is set.
      

    stop logging:

      $log->close();

        // writes the logtail and timer (if set) and closes the logfile;

      $log->quit();

        // closes the logfile immediatly without writing logtail and
        // timer;


    CHANGELOG :

      2003-02-05, 1.0; first operational version


    TODO:

      The maxlogsize is only checked at construction time. If a procedure
      lasts a long time during which a lot of logrecords are written the
      logfile can grow beyond its maximum. There has to be a
      filesizecheck every 50 logrecords or so.

      A new logfile can only be created in existing directorys. There
      should be a procedure to create a directory-tree if the directory
      doesn't exist yet.

*/


// MAIN CLASS

Class Logger{
  var $loglevel, $logfile, $fileaction, $logsize, $mailto, $debug;


  function Logger($str = "", $logsize = "", $level = "", $action = "append", $format = "") { // CONSTRUCTOR
    
    // if no logfilename is given default to:
    if(empty($str)) 
      if(empty($GLOBALS['logfile'])) 
        $this->logfile = "default.LOG";
      else $this->logfile = $GLOBALS['logfile'];
    else $this->logfile = $str;

    // if no loglevel is given default to:
    if(empty($level)) 
      if(empty($GLOBALS['loglevel'])) 
        $this->loglevel = 2;
      else $this->loglevel = $GLOBALS['loglevel'];
    else $this->loglevel = $level;

    // if no logfilesize is given look in the globals
    // whether a logfilesize is defined and use that
    // else use logsize
    if(empty($logsize)) {
      if(empty($GLOBALS['logsize'])) {
        $this->logsize = 0;
      }
      else {
        $matches = preg_replace("/[^0-9]/", "", $GLOBALS['logsize'] ); 
        ! empty($matches) ? $this->logsize = $matches : $this->logsize = 0;
      }
    }
    else { 
      $matches = preg_replace("/[^0-9]/", "", $logsize ); 
      ! empty($matches) ? $this->logsize = $matches : $this->logsize = 0;
    }

    // set action: append (default) appends to logfile
    //             clean writes a new logfile everytime
    if( preg_match("/clean|write|new/i", $action ) ) $this->fileaction = "w";
    else                                             $this->fileaction = "a";

    // set mailto if GLOBAL set
    if(! empty($GLOBALS['mailto'])) {
      $this->mailto = $GLOBALS['mailto'];
    }

    // set format
    $this->format = $format;

//    debug("Results of Logger", $this->logfile, $this->logsize, $this->fileaction, $this->loglevel);
    if($this->openlogfile()) {
      if($this->format != "plain") 
        $this->logheader();
    }
    else
      die("\nSomething wrong with Logger Constructor\n\n");

  } // END CONSTRUCTOR Logger
  
    
  // OPEN LOGFILE

  function openLogfile() {

    if ($this->loglevel > 0) {

      $File = new Filewrapper;

      // check if logfile exists and hasn't reached logfilesize; 
      // else make copy.

      // Before we do a checklogsize, the file must exist.
      // is_sane(name, must_exist, no_symlink, no_dir)

      if(! $File->is_sane($this->logfile,1,0,1)) {
        if (!($this->fp = fopen($this->logfile, "w"))) {
          die("0. could not write new logfile: {$this->logfile}");
        }
        else {
          return(true);
        }
      }

      if(! $this->logsize) {  // no logsize limit
        if (!($this->fp = fopen($this->logfile, $this->fileaction))) 
          die("1. could not open logfile: {$this->logfile}");
      }
      else if(filesize($this->logfile) > $this->logsize) {
              // logfilesize reached
        $File->copy_file ($this->logfile, $this->logfile.".old");
        if (!($this->fp = fopen($this->logfile, "w"))) 
          die("could not write new logfile after backup old one");
      }
      else {  // logsizelimit not reached yet
        if (!($this->fp = fopen($this->logfile, $this->fileaction))) 
          die("2. could not open logfile: {$this->logfile}");
      }

      return(true);

    }

    return(false);

  } // END FUNCTION openLogfile


  // FUNCTION changeLogs

  function changeLogs() {

  } // END FUNCTION changeLogs


  // PRINT LOGHEADER

  function logheader()  {
    $str  = "\n============================="
      ."======================================";
    $str .= "\n============================ "
      ."logrecord ============================";
    $str .= "\nstartlogrecord timestamp : ".date("Y-m-d H:i:s",time())."\n";

    $this->prn($str);
//    fwrite($this->fp, $str."\n");
  }
    

  // WRAPPER TO PRINTFUNCTIE
  
  function p($str = "", $level = "") {

    if(empty($level)) $level = $this->loglevel;

    if($this->format != "plain") 
      $str = "[{$this->loglevel}] ".$str;

    return( $this->prn($str, $level) );
  } // END OF WRAPPER TO PRINTFUNCTIE


  // PRINTFUNCTIE
  
  function prn($str = "", $level = "") {

    if($level <= $this->loglevel) {
      fwrite($this->fp, $str."\n");
      if($this->debug) print($str."\n");
    }

    if($level == 1 && $this->mailto != "") { // send mail
      $subject = "FoutBericht van logger - ".date("d-M-Y H:i:s", time());
      $message = "\n\nMessage: \n\n$str\n\n";
      mail($this->mailto, $subject, $message,
       "From: ErrorLogging@{$_ENV['HOSTNAME']}");
      if($this->loglevel > 0) 
        fwrite($this->fp, "sending mail     : <{$this->mailto}>  $subject");
    }

  } // END FUNCTION prn()


  // FUNCTION FLUSH

  function flush() {
    fflush($this->fp);
  } // END FUNCTION flush


  // FUNCTION TO SWITCH SWITCHES

  function switching($var = "", $val = "") {

    $this->$var = $val;

  } // END FUNCTION SWITCHING


  // FUNCTION close

  function close() {

    if($this->time_start)
      $this->prn("\nTime spent: "
      .$this->endTimer()." seconds");

    if($this->format != "plain") 
      $this->prn("\n======================= "
      ."end  logrecord ============================\n");

    $this->quit();
  }


  // FUNCTION quit

  function quit() {
    if($this->fp) fclose($this->fp);
  } // END FUNCTION quit


  // TIMERFUNCTIONS

  function getmicrotime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
  }

  function startTimer() {
    $this->time_start = $this->getmicrotime();
  }

  function endTimer() {
    return($this->getmicrotime() - $this->time_start);
  }


} // END MAIN CLASS Logger


/*

  Class Filewrapper, based on:
	File 1.0 - A wrapper class to common PHP file operations
	Copyright (c) 1999 CDI, cdi@thewebmasters.net

*/

Class Filewrapper
{
	var $ERROR = "";
	var $BUFFER = -1;
	var $STATCACHE = array();
	var $TEMPDIR = '/tmp';
	var $REALUID = -1;
	var $REALGID = -1;

	function Filewrapper()
	{
		global $php_errormsg;
		return;
	}

	function clear_cache()
	{
		unset($this->STATCACHE);
		$this->STATCACHE = array();
		return true;
	}

	function is_sane($fileName = "", $must_exist = 0, $noSymLinks = 0, $noDirs = 0)
	{
		$exists = false;

		if(empty($fileName)) {	return false; }
		if($must_exist != 0)
		{
			if(!file_exists($fileName))
			{
				$this->ERROR = "is_sane: [$fileName] does not exist";
				return false;
			}
			$exists = true;
		}
		if($exists)
		{
			if(!is_readable($fileName))
			{
				$this->ERROR = "is_sane: [$fileName] not readable";
				return false;
			}

			if($noDirs != 0)
			{
				if(is_dir($fileName))
				{
					$this->ERROR = "is_sane: [$fileName] is a directory";
					return false;
				}
			}

			if($noSymLinks != 0)
			{
				if(is_link($fileName))
				{
					$this->ERROR = "is_sane: [$fileName] is a symlink";
					return false;
				}
			}

		} // end if exists

		return true;		
	}


//	**************************************************************

	function read_file ($fileName = "" )
	{
		$contents = "";

		if(empty($fileName))
		{
			$this->ERROR = "read_file: No file specified"; 
			return false;
		}
		if(!$this->is_sane($fileName,1,0,1))
		{
			// Preserve the is_sane() error msg
			return false;
		}
		$fd = @fopen($fileName,"r");

		if( (!$fd) || (empty($fd)) )
		{
			$this->ERROR = "read_file: File error: [$php_errormsg]";
			return false;
		}

		$contents = fread($fd, filesize($fileName) );

		fclose($fd);

        return $contents;
	}


//	**************************************************************
	function write_file ($fileName,$Data)
	{
		$tempDir = $this->TEMPDIR;
		$tempfile   = tempnam( $tempDir, "cdi" );

		if(!$this->is_sane($fileName,0,1,1))
		{
			return false;
		}

		if (file_exists($fileName))
		{
			if (!copy($fileName, $tempfile))
			{
				$this->ERROR = "write_file: cannot create backup file [$tempfile] :  [$php_errormsg]";
				return false;
			}
		}

		$fd = @fopen( $tempfile, "a" );

		if( (!$fd) or (empty($fd)) )
		{
			$myerror = $php_errormsg;
			unlink($tempfile);
			$this->ERROR = "write_file: [$tempfile] access error [$myerror]";
			return false;
		}

		fwrite($fd, $Data);

		fclose($fd);

		if (!copy($tempfile, $fileName))
		{
			$myerror = $php_errormsg;   // Stash the error, see above
			unlink($tempfile);
			$this->ERROR = "write_file: Cannot copy file [$fileName] [$myerror]";
			return false;
		}

		unlink($tempfile);

		if(file_exists($tempfile))
		{
			// Not fatal but it should be noted
			$this->ERROR = "write_file: Could not unlink [$tempfile] : [$php_errormsg]";
		}
		return true;
	}

//	**************************************************************
	function copy_file ($oldFile = "", $newFile = "")
	{
		if(empty($oldFile))
		{
			$this->ERROR = "copy_file: oldFile not specified";
			return false;
		}
		if(empty($newFile))
		{
			$this->ERROR = "copy_file: newFile not specified";
			return false;
		}
		if(!$this->is_sane($oldFile,1,0,1))
		{
			// preserve the error
			return false;
		}
		if(!$this->is_sane($newFile,0,1,1))
		{
			// preserve it
			return false;
		}

		if (! (@copy($oldFile, $newFile)))
		{
			$this->ERROR = "copy_file: cannot copy file [$oldFile] [$php_errormsg]";
			return false;
		}

		return true;
	}

//	**************************************************************
	function rename_file ($oldFile = "", $newFile = "")
	{
		if(empty($oldFile))
		{
			$this->ERROR = "rename_file: oldFile not specified";
			return false;
		}
		if(empty($newFile))
		{
			$this->ERROR = "rename_file: newFile not specified";
			return false;
		}
		if(!$this->is_sane($oldFile,1,0,1))
		{
			// preserve the error
			return false;
		}
		if(!$this->is_sane($newFile,0,1,1))
		{
			// preserve it
			return false;
		}

		if (! (@rename($oldFile, $newFile)))
		{
			$this->ERROR = "rename_file: cannot rename file [$oldFile] [$php_errormsg]";
			return false;
		}

		return true;
	}


	function is_owner($fileName, $uid = "")
	{
		if(empty($uid))
		{
			if($this->REALUID < 0)
			{
				$tempDir = $this->TEMPDIR;
				$tempFile = tempnam($tempDir,"cdi");
				if(!touch($tempFile))
				{
					$this->ERROR = "is_owner: Unable to create [$tempFile]";
					return false;
				}
				$stats = stat($tempFile);
				unlink($tempFile);
				$uid = $stats[4];
			}
			else
			{
				$uid = $this->REALUID;
			}
		}
		$fileStats = stat($fileName);
		if( (empty($fileStats)) or (!$fileStats) )
		{
			$this->ERROR = "is_owner: Unable to stat [$fileName]";
			return false;
		}

		$this->STATCACHE = $fileStats;

		$owner = $fileStats[4];
		if($owner == $uid)
		{
			return true;
		}

		$this->ERROR = "is_owner: Owner [$owner] Uid [$uid] FAILED";
		return false;
	}

	function is_inGroup($fileName, $gid = "")
	{
		if(empty($gid))
		{
			if($this->REALGID < 0)
			{
				$tempDir = $this->TEMPDIR;
				$tempFile = tempnam($tempDir,"cdi");
				if(!touch($tempFile))
				{
					$this->ERROR = "is_inGroup: Unable to create [$tempFile]";
					return false;
				}
				$stats = stat($tempFile);
				unlink($tempFile);
				$gid = $stats[5];
			}
			else
			{
				$gid = $this->REALGID;
			}
		}
		$fileStats = stat($fileName);
		if( (empty($fileStats)) or (!$fileStats) )
		{
			$this->ERROR = "is_inGroup: Unable to stat [$fileName]";
			return false;
		}

		$this->STATCACHE = $fileStats;

		$group = $fileStats[5];
		if($group == $gid)
		{
			return true;
		}

		$this->ERROR = "is_inGroup: Group [$group] Gid [$gid] FAILED";
		return false;
	}

	function get_real_uid()
	{
		$tempDir = $this->TEMPDIR;
		$tempFile = tempnam($tempDir,"cdi");
		if(!touch($tempFile))
		{
			$this->ERROR = "is_owner: Unable to create [$tempFile]";
			return false;
		}
		$stats = stat($tempFile);
		unlink($tempFile);
		$uid = $stats[4];
		$gid = $stats[5];
		$this->REALUID = $uid;
		$this->REALGID = $gid;
		return $uid;
	}

	function get_real_gid()
	{
		$uid = $this->get_real_uid();
		if( (!$uid) or (empty($uid)) )
		{
			return false;
		}
		return $this->REALGID;
	}

}	// end class Filewrapper


?>
