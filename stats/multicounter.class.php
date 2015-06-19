<?php
/**
 * Counter that keeps track of multiple pages, counts each visitor's IP only
 * once a day and even remembers monthly snapshots.
 *
 * A single text file is used for storage, so no database is necessary and no
 * cookies are sent to the browser either.
 *
 * Please note that most methods should not be called directly as this might
 * cause problems with the file lock. A flag ($disabled) is set at the end of
 * the constructor which will prevent some methods from being called. The
 * reason why these methods exist in the first place is to break up the
 * code a little bit...
 *
 * Quick start:
 * just include the class and instantiate it like this:
 *   <?php require_once("multicounter.class.php"); $c = new MultiCounter() ?>
 *   Refer to the constructor's description for more options.
 * get the history of recent months as an array:
 *   $h = $c->getHistory();
 * get all counters as an array:
 *   $a = MultiCounter::getAllCounters();
 *
 * Author: Stefan Ihringer <stefan@chromosomnia.org>
 * Version: 1.0 (01 Sep 2002)
 * Please let me know if you find any bugs so I can update the file on phpclasses.org
 */
class MultiCounter
{
	var $ident;						// identifier of page of which hits are to be counted
	var $value = 0;					// counter's value for the selected page
	var $since = 0;					// timestamp of the first page hit (so you can write "x visitors since July 2000")
	var $access = array();			// most recent IPs for the page and the timestamps (which are keys in this array)
	var $max_ips = 10;				// maximum number of IPs in $access. Increase if you've got lots of hits each day.
	var $timeout = 86400;			// after 24 hours a user is allowed to be counted again
	var $filename = ".counter";		// filename of counter file (plaintext) relative to document root
	var $disabled = FALSE;			// will be set to true if file couldn't be opened. Saving will then be disabled.
	var $fp;						// file pointer
	var $a = array();				// array containing the lines of the whole data file

	/**
	 * Creates a new counter object.
	 *
	 * You may omit the parameters for the constructor. In this case the
	 * current page's file name (without .php extension) is used as an identifier
	 * and the counter is incremented automatically. If you just want to skip
	 * the file name to set $inc=FALSE you may use "" as a filename.
	 *
	 * @param string	identifier of page the counter intended for (limited
	 *					to 16 characters (A-Z, 0-9 and underscore), case
	 *					insensitive, longer identifiers are shortened and
	 *					invalid IDs are set to "unknown".)
	 * @param string	an alternative filename (defaults to ".counter")
	 *					to use for data storage. Relative to document root.
	 * @param bool		TRUE (default if omitted) if the counter should be
	 *					incremented or FALSE if the counter should be
	 *					initialized only (without incrementing it).
	 */
	function MultiCounter($id = "", $fn = "", $inc = TRUE)
	{
		// auto-detect identifier
		if($id == "")
		{
			preg_match("/(\w+)\.php$/", $_SERVER['PHP_SELF'], $matches);
			if(count($matches) > 0)
				$id = $matches[1];
			else
				$id = "unknown";
		}
		// check identifier
		if(!preg_match("/^[\w]+$/", $id))
			$this->ident = "unknown";
		else
			$this->ident = substr(strtolower($id), 0, 16);
		// alternative file name?
		if($fn) $this->filename = $fn;
		// load counter
		$this->open();
		$this->load();
		// increase?
		if($inc)
		{
			$this->increase();
			$this->save();
		}
		$this->close();
		$this->disabled = TRUE;
	}

	/**
	 * Opens and locks the data file.
	 *
	 * If the file could neither be opened nor created, $this->disabled will
	 * be set to TRUE which prevents further loading and saving of the counter.
	 */
	function open()
	{
		if($this->disabled) return;
		$fn = $_SERVER['DOCUMENT_ROOT']."/".$this->filename;
		$this->fp = @fopen($fn,"r+");
		if(!$this->fp)
		{
			// if file not found then try to create it
			$this->fp = @fopen($fn,"w+");
			if(!$this->fp)
			{
				// no write access on server => fail silently
				$this->disabled = TRUE;
				return;
			}
		}
		flock($this->fp, LOCK_EX);
	}

	/**
	 * Loads the counter's value from the data file.
	 *
	 * The file has to be open for reading.
	 */
	function load()
	{
		if($this->disabled) return;
		// Reads the whole file into memory except for the line containing the current
		// page's hit number which is parsed to yield the required data.
		while(!feof($this->fp))
		{
			// 4096 bytes per line... if you save lots of IPs this might not be enough.
			$buf = fgets($this->fp, 4096);
			$tokens = preg_split("/[ \t]+/", $buf, 4);
			// If line starting with current page identifier has been found, read value und IPs.
			if(count($tokens) == 4 && $tokens[0] == $this->ident)
			{
				$this->value = (integer)$tokens[1];
				$this->since = (integer)$tokens[2];
				$this->access = @unserialize($tokens[3]) or $this->access = array();
				ksort($this->access);
			}
			elseif ($buf != "\n")
				$this->a[] = $buf;
		}
		if($this->since == 0) $this->since = time();
	}

	/**
	 * Increases the counter if visitor's IP isn't blocked.
	 *
	 * This function also does some cleanup: It prevents saving too many
	 * IP adresses and it will create "history comments". A history comment
	 * is a monthly snapshot of the counter. It's saved as a comment (a line
	 * starting with #) so it won't affect the counter's log file in any way.
	 */
	function increase()
	{
		if($this->disabled) return;
		$banned = FALSE;
		$now = time();
		// create a snapshot if more than a month has passed since the
		// last time the counter has been accessed.
		if(count($this->access) > 0)
		{
			end($this->access);
			$old_timestamp = key($this->access);
			$old_month_nr = $this->month_nr($old_timestamp);
			$new_month_nr = $this->month_nr($now);
			if($new_month_nr > $old_month_nr)
			{
				$newline = "# ".$this->ident;
				$newline .= strlen($this->ident) >= 14 ? "\t" : (strlen($this->ident) >= 6 ? "\t\t" : "\t\t\t");
				$newline .= $this->value."\t".$old_timestamp."\t".date("(F Y)",$old_timestamp)."\n";
				$this->a[] = $newline;
			}
		}
		// check if visitor has already accessed the counter recently
		$x = array_search($_SERVER['REMOTE_ADDR'], $this->access);
		if(is_int($x) && $now - $x < $this->timeout)
		{
			$banned = TRUE;
			unset($this->access[$x]);
		}
		if(!$banned) $this->value++;
		// Log this page hit (and clean up while we're at it)
		$this->access[$now] = $_SERVER['REMOTE_ADDR'];
		$i = 0;
		foreach($this->access as $timestamp => $ip)
		{
			$i++;
			if($i > $this->max_ips || $now - $timestamp >= $this->timeout)
				unset($this->access[$timestamp]);
		}
		// create new line for log file
		$newline = $this->ident;
		$newline .= strlen($this->ident) == 16 ? "\t" : (strlen($this->ident) >= 8 ? "\t\t" : "\t\t\t");
		$newline .= $this->value."\t".$this->since."\t".serialize($this->access)."\n";
		$this->a[] = $newline;
	}

	/**
	 * Saves the counter.
	 *
	 * This method writes all lines that have been stored into memory by load()
	 * back to the data file (nicely formatted. The file format is simple.
	 * There are four fields separated by whitespace: identifier, counter,
	 * timestamp of 1st hit and serialized array data.
	 */
	function save()
	{
		if($this->disabled) return;
		rewind($this->fp);
		ftruncate($this->fp, 0);
		foreach($this->a as $line)
			fputs($this->fp, $line);
	}

	/**
	 * Closes the data file.
	 */
	function close()
	{
		if($this->disabled) return;
		flock($this->fp, LOCK_UN);
		fclose($this->fp);
	}

	/**
	 * Returns the counter's value.
	 *
	 * @return int	The number of visits to the page.
	 */
	function getValue()
	{
		return $this->value;
	}

	/**
	 * Returns the date of the first hit.
	 *
	 * @return int	The UNIX time (seconds since 1.1.1970) of the first hit.
	 */
	function getSince()
	{
		return $this->since;
	}

	/**
	 * Returns the month number for a given month/year combination or a timestamp.
	 *
	 * There are two ways to call this method: If you use one parameter only, a
	 * UNIX timestamp is expected. If you use two parameters, the first one is
	 * the year and the second one is the month.
	 *
	 * @param int	The year (e.g. 2002) OR a UNIX timestamp
	 * @param int	The month (between 1 and 12) if the 1st parameter was a year
	 * @return int	The result of $year * 12 + ($month - 1)
	 */
	function month_nr($a, $b = NULL)	{
		if(is_null($b))
		{
			$tmp = getdate($a);
			return $tmp['year'] * 12 + $tmp['mon'] - 1;
		}
		else
			return $a * 12 + $b - 1;
	}

	/**
	 * Returns the monthly history of the counter.
	 *
	 * Contrary to the storage format of the data file, this array contains the
	 * hits of each month and not the total number of hits. The array's keys
	 * are in the form "mm/yyyy" (e.g. "08/2002"). Months with zero hits are
	 * also included even though they are sometimes not listed in the data file.
	 *
	 * @return array	An array containing the monthly counter statistics.
	 */
	function getHistory()
	{
		$h = array();
		// "collect" all relevant history comments for this counter
		foreach($this->a as $line)
			if(preg_match('/^# '.$this->ident.'[\t ]+(\d+)[\t ]+(\d+)[\t ]\([a-z]+ \d\d\d\d\)/i', $line, $matches))
				$h["{$matches[2]}"] = $matches[1];
		// add month of last access (which might not be over yet)
		if(count($this->access) > 0)
		{
			end($this->access);
			$h["".key($this->access)] = $this->value;
		}
		ksort($h);
		// $old_* variables contain the values of the last known month
		// $new_* variables contain the values of the loop's current, new month
		// Every month that is missing in between will be filled up by the algorithm
		// which internally uses "month numbers" (years and months combined to count sequencially)
		$result = array();
		$old_month_nr = 0;
		$old_hits = 0;
		foreach($h as $timestamp => $new_hits)
		{
			$new_month_nr = $this->month_nr($timestamp);
			if($old_month_nr == 0) $old_month_nr = $new_month_nr - 1;	// first loop only
			do
			{
				$old_month_nr++;
				$y = floor($old_month_nr/12);
				$m = ($old_month_nr%12)+1;
				if($m < 10)
					$m = "0".$m;
				$result[$m."/".$y] = $old_month_nr<$new_month_nr?0:($new_hits - $old_hits);
			}
			while($old_month_nr < $new_month_nr);
			$old_hits = $new_hits;
		}
		return $result;
	}

	/**
	 * Returns all counters saved in a file (for stats)
	 * (static method)
	 *
	 * @return array	An array containing all counter identifiers
	 */
	function getAllCounters($file = ".counter")
	{
		$result = array();
		$fn = $_SERVER['DOCUMENT_ROOT']."/".$file;
		$fp = @fopen($fn,"r+");
		if(!$fp) return $result;
		flock($fp, LOCK_SH);
		// read line-by-line
		while(!feof($fp))
		{
			$buf = fgets($fp, 4096);
			if(preg_match("/^(\w+)[\t ]+\d+[\t ]+\d+[\t ]+.*$/i", $buf, $matches))
			{
				// matched data line
				$result[] = $matches[1];
			}
			else if(preg_match("/^# (\w+)[\t ]+\d+[\t ]+\d+[\t ]\([a-z]+ \d\d\d\d\)$/i", $buf, $matches))
			{
				// matched history comment
				$result[] = $matches[1];
			}
		}
		flock($fp, LOCK_UN);
		fclose($fp);
		// remove duplicates in array
		return array_unique($result);
	}
}

?>