<?php
/*
 * Filename.....: inc_ktimer.php
 * Purpose......: Stop watches for multiple timed events 
 * Features.....: Function stop() only works once per timer
 *                Function restart() makes stop() work again
 * Erstellt am..: 24. Juni 2002
 *       _  __      _ _
 *  ||| | |/ /     (_) |        Wirtschaftsinformatiker IHK
 * \. ./| ' / _ __  _| |_ ___   www.ingoknito.de
 * - ^ -|  < | '_ \| | __/ _ \  
 * / - \| . \| | | | | || (_) | Peter Klauer
 *  ||| |_|\_\_| |_|_|\__\___/  06131-651236
 * mailto.......: knito@knito.de
 *
 * Changes:
 */
 
if( !isset( $inc_ktimer_geladen ) )
{
  $inc_ktimer_geladen = 1; // Prevent multiple loading
  
  # any string message will work for NEW_TIMER
  # (make it short)
  # this message will be shown when the timer is
  # started but not stopped yet
  define( NEW_TIMER, 'not stopped, still running' );
   
  class ktimer
  {
    var $times = array(); // Holds all timer values
    var $precision = 5;   // for PHP4 round( $value, $this->precision )
   
    function start($name)
    {
      # create a new timer entry in the array
      $this->times[$name] = array( microtime(), 0, NEW_TIMER );
    }
    
    function stop($name)
    {
      # only stops when the timer is running ( == NEW_TIMER )
      # multiple stopping does not change the elapsed time value
      # if the timer is not restarted
      # the elapsed time is calculated here
      if( $this->times[$name][2] == NEW_TIMER )
      {
        $this->times[$name][1] = microtime();
        
        $a = $this->makefloattime( $this->times[$name][0] );
        $b = $this->makefloattime( $this->times[$name][1] );
       
        $this->times[$name][2] = $b - $a;
      }
    }
    
    function restart($name)
    {
      # if you want to restart a timer after stopping
      # setting this value makes function stop() work again
      # start value remains unchanged
      $this->times[$name][2] = NEW_TIMER;
    }
    
    function value($name)
    {
      # this function only returns the calculated runtime value
      $result = $this->times[$name][2];
      if( ($result != NEW_TIMER) && (PHP_VERSION >= 4.0) )
      {
        $result = round( $result, $this->precision);
      }
      return $result;
    }   
    
    function makefloattime( $mtimestring )
    {
      # makes a float out of the microtime() - string value
      list( $milli, $seconds ) = explode( ' ',$mtimestring );
      return( $seconds + $milli );
    }
  } // end of class timer
  
} // end of if ! isset inc_timer_geladen

/* Example:
 * You can have more than one timers running.
 * This code shows two timers.
 
include 'inc_ktimer.php';

$t = new ktimer;
$t->precision = 3; // works only for PHP >= 4.0

$t->start('skript');

  ... any code, maybe connection to sql server ... 

$t->start('runtime');

  ... any code, maybe a sql query ...

$t->stop('runtime');

echo '<br>sql running '.$t->value('runtime').' seconds.';

  ... some more code ...

$t->stop('skript');  
echo '<br>skript running '.$t->value('skript').' seconds';  

*/
?>