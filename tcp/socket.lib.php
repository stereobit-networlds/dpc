<?php
class msocket
{
var $Hostname;
var $Port;
var $Socket;

function msocket ($Hostname, $Port=80)
{
	$this->Hostname = $Hostname;
	$this->Port     = $Port;
	return $this;
}

function EnviaRecibe ($Mensaje)
{
	$Retorno=0;
	$this->Envia ($Mensaje);
	$Retorno = $this->Recibe();
	return $Retorno;	
}

function Envia ($Mensaje)
{
	$Retorno=0;
	$this->_Conecta();
	$Retorno = fwrite ($this->Socket, $Mensaje);
	return $Retorno;
}

function Recibe()
{
	$Retorno='';
	if (!$this->Socket){ return $Retorno; }	
	while (!feof ($this->Socket))
		{$Retorno .= fgets ($this->Socket, 4096);}
	$this->_Desconecta();
	return $Retorno;
}

function _Conecta()
{
	$Retorno =0;
	$this->Socket =fsockopen($this->Hostname , $this->Port , $err_num, $err_msg, 30);
	$Retorno = $this->Socket;
	return $Retorno;
}

function _Desconecta()
{
	$Retorno =0; 
	$Retorno =fclose ($this->Socket);
	return $Retorno;
}

}
?>