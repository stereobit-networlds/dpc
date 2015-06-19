<?
	# Put all files from list in ZIP archive

    require("phpzip.inc.php");

	$z = new PHPZip();

	# Create File List
	$files[]="phpzip.inc.php";
	$files[]="sample1.php";
	$files[]="sample2.php";

	$z -> Zip($files, "out.zip");
?>