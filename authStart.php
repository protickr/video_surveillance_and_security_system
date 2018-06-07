<?php
	require_once __DIR__.'/dropbox-sdk/Dropbox/autoload.php';
	use \Dropbox as dbx;
	session_start();
	
	$appKey = "your app key";
	$appSecret = "your app secret";
	$appName= "your app name";
	$redirectURI = "http://localhost/project/authFinish.php";

	$appInfo = new dbx\AppInfo($appKey, $appSecret);

	//csrf
	$csrfToken = new dbx\ArrayEntryStore($_SESSION,'dropbox-auth-csrf-token');
	$webAuth = new dbx\WebAuth($appInfo,$appName,$redirectURI,$csrfToken);
?>
