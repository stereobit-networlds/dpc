#!/usr/lib/cgi-bin/php4 -q
<?php

require ("daemon.lib.php");

$dmn = new daemon;
$dmn->setAddress ('127.0.0.1');
$dmn->setPort (19123);
$dmn->Header = "GNUPHPtial server (v0.0.1b) (Debian GNU/Linux)";

$dmn->start ();  //this routine creates a socket

$dmn->setCommands (array ("help", "quit", "date", "shutdown"));
//list of valid commands that must be accepted by the server

$dmn->CommandAction ("help", "command_handler"); //add callback
$dmn->CommandAction ("quit", "command_handler"); // by calling 
$dmn->CommandAction ("date", "command_handler"); //this routine
$dmn->CommandAction ("shutdown", "command_handler");
$dmn->listen (); //from here... your program will enter an endless loop
//until manually broken. This example contains a shutdown command that
//would shutdown this daemon.

function command_handler ($command, $arguments, $dmn) {
        switch ($command) {
        case 'HELP':
                //commands are converted to uppercase by default. If you want to
                //disable this, look into tokenise().
                $commands = implode (' ', $dmn->valid_commands);
                $dmn->Println ('Valid Commands: ');
                $dmn->Println ($commands);
                return true;
                break;
        case 'QUIT':
                return false;
                break;
        case 'DATE':
                $dmn->Println (date ("H:i:s d/m/Y"));
                return true;
                break;
        case 'SHUTDOWN':
                $dmn->shutdown ();
                exit;
                break;
        }
}

?>