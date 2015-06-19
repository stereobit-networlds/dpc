<?php
require_once ("socket.lib.php");

$Datos='';

if ($goOpen)
	{$MSocket = new msocket ("www.google.com");
		$MSocket->Envia ("GET / \n\n");
		$Datos =	$MSocket->Recibe();
	 unset ($MSocket);}

if ($goClose)
	{$MSocket = new msocket ("www.google.com");
	$Datos = $MSocket->EnviaRecibe ("GET / \n\n");
  unset ($MSocket);}
	
echo $Datos;
?>
<br>
<hr>
<a href="<?=$PHP_SELF?>?goOpen=1">[Click Aqui para ver google en modo socket abierto]</a>
<br>
<hr>
<a href="<?=$PHP_SELF?>?goOpen=1">[Click Aqui para ver google en modo socket cerrado]</a>
<hr>
<br>