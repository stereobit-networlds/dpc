<?
	# Put all files in current directory(include subdirectories) in ZIP archive

    require("phpzip.inc.php");

	$z = new PHPZip();
	$z -> Zip("", "out.zip");
?>