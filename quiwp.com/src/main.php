<?php
	system('scp quiwp.php ljadminvm@192.168.0.5:');
	echo ("\e[34m");
	system('date');
	echo ("\e[0m");
	echo ("\n\n");
	echo ("\e[32m Your File Has Been Succesfully Copied...\e[0m");
	echo ("\n\n");
	echo ("\e[96m Installing PHP..\e[0m\n\n");	
	system('ssh ljadminvm@192.168.0.5 apt-get install php7.0 php7.0-mysql libapache2-mod-php7.0 php7.0-cli php7.0-cgi php7.0-gd');
	echo ("\n\n");
	echo ("\e[32m PHP Succesfully Installed...\e[0m");
	echo ("\n\n");
	system('ssh ljadminvm@192.168.0.5');
?>


