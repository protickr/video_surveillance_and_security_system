<?php
	$pid = exec('pgrep -x project');
	exec('pkill -f project');
	header('Location: index.php');
?>