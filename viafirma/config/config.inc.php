<?php
	$VIAFIRMA_CLIENT_VERSION= "2.4.6"; // Versión del cliente php.
	
	// Url del servicio Viafirma.
	$VIAFIRMA_SERVICE_URL="https://testservices.viafirma.com/viafirma";
	
	//URL de aplicacion
	$APPLICATION_URL="http://localhost/GilGamboaAdrian_proyectoAE/";
	
	//URL de retorno
	$VIAFIRMA_RETURN_TO_URL="http://localhost/GilGamboaAdrian_proyectoAE/viafirma-client-php/openidReturn.php";
	

	global $authOpenIDRandSource,$appId,$appPassword,$pathTemporalOpenIDFileStore,$proxyHost,$proxyPort,$proxyUser,$proxyPassword, $maxSizeMegabytes;

	// The application should have write permissions and the directory should exist.
	//La aplicacion tiene que tener permisos de escritura en este directorio y el directorio debe existir.
	$pathTemporalOpenIDFileStore= "C:\\tmp\\viafirmaphp\\oid_store"; // Linux /unix
	//$pathTemporalOpenIDFileStore= "C:\\Temp\\oid_store"; // Windows

	//$authOpenIDRandSource = "/dev/urandom"; // Unix
	$authOpenIDRandSource = null; // Windows
	
	$appId = "dev_UPO";
	$appPassword = "ZGWJM5TZ3W07C1HMAFXKP7X157BFL";
	
	$proxyHost = "";// Proxy configuration ej:192.168.10.22
	$proxyPort = ""; // Port configuration. ej: "3128";
	$proxyUser = "";
	$proxyPassword = "";

	$certAlias = "xnoccio";
	$certPass = "12345";
	
	// Tamaño maximo para un documento en Megabytes (Por defecto=5Mb)
	// Maximun size for a document in Megabytes (Default=5Mb)
    // $maxSizeMegabytes = 6;
?>
