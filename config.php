<?php
    //dropbox linking
    $disabled=$buttonText="";
    if(isset($_GET['drop'])){
      if(isset($_COOKIE["rand"])){
          if($_GET['drop'] == $_COOKIE["rand"]){

          setcookie("rand","",time()-1,"");
          $disabled = "disabled";
          $buttonText = "Dropbox Account Linked";
        }else{
            header('Location: index.php');
        }
      }else{
        header('Location: index.php');
      }

    }else{
        $disabled = "";
        $buttonText = "Click To Link Dropbox Account";
    }
?>

<!DOCTYPE html>
<html>
<style>
input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
    width: 30%;
    background-color: #2994e8;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}

div {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
	width: 70%;
	box-shadow: 0px 0px 31px #888;
}

	h3{
		background-color: #376e99;
		text-align: center;
		color: white;
		border: 1px dotted #235c1a;
		font-size: 28px;
		border-radius: 39px;
		padding: 25px;
		margin:0px;
	}
		label
	   {
		float: left;
		}
p{
	background-color: #f2f2f2;
	color: red;
}
#drpButton
{
	
    background-color: #fb3505;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 800;
}
</style>

<script type="text/javascript" language="javascript">
function validateForm() {
    var x = document.forms["myForm"]["frname"].value;
    if (x == "" || isNaN(x) ||x<15 || x>25 ) {
        alert("Frame Rate must be 15-25");
        return false;
    }
	
	var x = document.forms["myForm"]["trvalue"].value;
    if (x == "" || isNaN(x) ||x<5 || x>10 ) {
        alert("Threshold Value must be 5-10");
        return false;
    }
	
	var x = document.forms["myForm"]["trarea"].value;
    if (x == "" || isNaN(x) ||x<100 || x>500 ) {
        alert("Area must be 100-500");
        return false;
    }
	
	var x = document.forms["myForm"]["upinterval"].value;
    if (x == "" || isNaN(x) ||x<1 || x>10) {
        alert("Interval must be 1sec-10sec");
        return false;
    }
	
	var x = document.forms["myForm"]["mfcount"].value;
    if (x == "" || isNaN(x) ||x<2 || x>5 ) {
        alert("Max frame count must be 2-5");
        return false;
    }
	
	var x = document.forms["myForm"]["mitime"].value;
    if (x == "" || isNaN(x) ||x<60 || x>300 ) {
        alert("Mail interval must be 60sec-300sec");
        return false;
    }
	
	var x = document.forms["myForm"]["deaddress"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        alert("Destination e-mail address is not valid");
        return false;
    }

    var x = document.getElementById("drpButton").disabled;
    if(x == false){
      return false;
    }
}

</script>
<body>

<h3 >VIDEO SURVEILLANCE AND SECURITY SYSTEM</h3>
<center>
<div id="sysConf">
<h2 style="background-color:#2994e8;color:white;padding: 13px;border-radius: 3px;margin:0;">SYSTEM CONFIGURATION</h2><br><br>

     <a href="drop.php" style="text-decoration: none" >
        <button id="drpButton" <?php echo $disabled ?> > <?php echo $buttonText ?> </button>
     </a>
   <br/>

 
<form name="myForm" method="post" action="on.php" onsubmit="return validateForm()">

	<label>FRAME RATE:</label>
          <input type="text" name="frname" placeholder="Rate must be 15-25" >

  <label>THRESHOLD VALUE:</label>
          <input type="text" name="trvalue" placeholder="Value must be 5-10" >

   <label>TARGET AREA:</label>
          <input type="text" name="trarea" placeholder="Area must be 100-500" >
		  

  <label>UPLOAD INTERVAL:</label>
          <input type="text" name="upinterval" placeholder="Interval must be 1sec-10sec"  >

 
    <label>MAX FRAME COUNT:</label>
          <input type="text" name="mfcount" placeholder="Max frame count must be 2-5" >

      <label>MAIL INTERVAL TIME:</label>
          <input type="text" name="mitime" placeholder="Mail interval must be 60sec-300sec" >

		  
  <label>DESTINATION EMAIL ADDRESS:</label>
        <input type="text" name="deaddress" placeholder="Destination email address" >
	<p><b>You Are Free To Close This Window After Submiting</b></p>	  
    
    <input type="submit" value="Submit" name="submit"/>
  </form>

</div>

</center>
</body>
</html>
