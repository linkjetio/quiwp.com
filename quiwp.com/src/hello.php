<?php
	include('format.php');
	system('  scp lamp123.php ljadminvm@192.168.0.5:lamp123.php');
	system('date');
	echo "\n\n";
	echo colorize("Your File Has Been Succesfully Copied...", "SUCCESS");
	echo "\n\n";
	system(' scp mydata.sql ljadminvm@192.168.0.5:mydata.sql');
	system('date');
	echo "\n\n";
	echo colorize(" Your File Has Been Succesfully Copied...", "SUCCESS");
	echo "\n\n";
	system('ssh ljadminvm@192.168.0.5');
?>


