<?php
//---- EXAMPLE FILE - BACKUP

//---- Including the class.
include 'mysql_backup.lib.php';

//---- Database host, name user and pass.
//---- Change these to match your mysql database.
$db_host = "localhost"	//---- Database host(usually localhost).
$db_name = "test"	//---- Your database name.
$db_user = "root"	//---- Your database username.
$db_pass = ""	//---- Your database password.

//---- The name of the file which you want to save backup file in it.
//---- If this file does not exist it will be created.(over writed).
//---- You need to use physical path to this file. e.g. ../data/backup.txt
$output = "backup.txt";

//---- If this is true, only tables' structure will be stored.
//---- If this is false, tables' structure and all their data(everything) will be stored.
$structure_only = false;

//---- instantiating object.
$backup = new mysql_backup($db_host,$db_name,$db_user,$db_pass,$output,$structure_only);	

//---- calling the backup method finally creats a file with the name specified in $output
//     and stors everythig so you can copy the file anywhere you want. This file will be
//     restored with another method of this class named "restore" that is described in
//     example-backup.php
$backup->backup();
?>