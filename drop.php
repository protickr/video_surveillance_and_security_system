<?php
	require 'authStart.php';
	require_once __DIR__.'/dropbox-sdk/Dropbox/autoload.php';
	use \Dropbox as dbx;
	
	if(!(isset($_COOKIE["key"]))){
		setcookie("key","",time() + (86400 * 30), "/");
	}
	//$_COOKIE['key'] = "";

	function authRedirect(){
		global $webAuth;
		$authUrl = $webAuth->start();
		header('Location: '.$authUrl);
		exit();
	}

	if($_COOKIE["key"] != ""){
		$Client = new dbx\Client($_COOKIE["key"], $appName, 'UTF-8');
		try{
			$Client->getAccountInfo();

			$rand = rand(234,85798);
			setcookie("rand",$rand, time()+10,"/" );
			header('Location: config.php?drop='.$rand);
		} catch(dbx\Exception_InvalidAccessToken $e) {
			authRedirect();
		}
	}else{
		authRedirect();

	}
	
?>
