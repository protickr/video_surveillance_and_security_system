<?php
	$status = exec('pgrep -x project');

	$disabledOn="";
	$disabledOff = "";
	$statusMessage = "";

	if($status == ""){
		$disabledOff = "disabled";
		$disabledOn = "";
		$statusMessage = "System Is Not Running"; 
	}else{
		$disabledOn = "disabled"; 
		$disabledOff = "";
		$statusMessage = "System Is Running";
	}

	header("Refresh: 10; url='index.php'");
?>




<html>
  <head>
	<title>ON/OFF</title>
	<style>

	
     #log{
	    width: 400px;
		box-shadow: 0px 0px 31px #888;
        margin-top: 200px;
		margin-bottom: 350px;
		border-radius: 3px;
		height: 300px;
	 }

	 .button {
		background-color: #4CAF50; 
		border: none;
		color: white;
		padding: 15px 32px;
		font-size: 16px;
		margin: 72px 2px;
		cursor: pointer;
		border-radius: 4px;
}

    .button2 {background-color: #f44336;} 
	
		h1{
		background-color: #376e99;
		text-align: center;
		color: white;
		border: 1px dotted #235c1a;
		font-size: 50px;
		border-radius: 39px;
		padding: 25px;
		
	}
	a{
	  text-decoration:none;
	  color:white;
	}

    </style>


  </head>
<body>
<h1 >VIDEO SURVEILLANCE AND SECURITY SYSTEM</h1>

<!------------------------------ON/OFF------------------------>
<center>
<div id="log" >

<a href="config.php" target="_blank">
	<button class="button" <?php echo "$disabledOn"?> >ON</button>
</a>

<a href="off.php">
<button class="button button2" <?php echo "$disabledOff"?> >OFF</button><br/>
</a>

	<?php echo $statusMessage ?>
</div>
</center>





</body>
</html>





