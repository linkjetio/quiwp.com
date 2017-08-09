<?php
	echo ("\n\n ########################################## \n\n");	
	echo ("\n\n \e[96m Downloading themes \n\n \e[0m");	
	$themesfile ='themes.txt';
	$lines = file($themesfile);
	foreach($lines as $line_num => $line)
	{
		echo $line;
		system("wget https://downloads.wordpress.org/theme/". $line. '');
		exec("unzip " . trim($line) . " -d " . $WEBROOT ."wp-content/themes");
		exec("rm -R " . trim($line));
	}
?>
