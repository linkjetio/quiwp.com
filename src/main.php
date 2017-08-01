<?php
	include('format.php');
	system('scp install.php ljadminvm@192.168.0.5:install.php');
	system('date');
	echo "\n\n";
	echo colorize("Your File Has Been Succesfully Copied...", "SUCCESS");
	echo "\n\n";
	system('scp database.sql ljadminvm@192.168.0.5:database.sql');
	system('date');
	echo "\n\n";
	echo colorize("Your File Has Been Succesfully Copied...", "SUCCESS");
	echo "\n\n";
	system('ssh ljadminvm@192.168.0.5');
?>


