<?php
	require 'authStart.php';
	var_dump($_GET["code"]);
	list($accessToken)=$webAuth->finish($_GET);
	var_dump($accessToken);
	setcookie("key",$accessToken,time() + (86400 * 30), "/");
	header('Location: drop.php');
?>