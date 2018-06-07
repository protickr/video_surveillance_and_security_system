<?php
    $frname=$trvalue=$trarea=$upinterval=$mfcount=$mitime=$deaddress="";
    $errorMessage="";

             $frname = trim($_POST["frname"]);
             $trvalue = trim($_POST["trvalue"]);
             $trarea = trim($_POST["trarea"]);
             $upinterval = trim($_POST["upinterval"]);
             $mfcount = trim($_POST["mfcount"]);
             $mitime = trim($_POST["mitime"]);
             $deaddress = trim($_POST["deaddress"]);
		
             $res = passthru('python /home/pi/motion.py '.$frname.' '.$trvalue.' '.$trarea.' '.$upinterval.' '.$mfcount.' '.$mitime.' '.$deaddress.' '.$_COOKIE["key"]);

		      //echo 'Values: '.$frname.' '.$trvalue.' '.$trarea.' '.$upinterval.' '.$mfcount.' '.$mitime.' '.$deaddress.' '.$_COOKIE["key"];
?>