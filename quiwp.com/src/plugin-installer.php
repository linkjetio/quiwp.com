<?php
	echo ("\n\n #################################### \n\n");
	echo ("\n\n \e[96m Downloading plugins \n\n \e[0m");
	$file1 ='plugins.txt';
	$lines = file($file1);
	foreach($lines as $line_num => $line)
	{
		echo $line;
		system("wget https://downloads.wordpress.org/plugin/". $line. '');
		exec("unzip " . trim($line) . " -d " . $WEBROOT ."wp-content/plugins");
		exec("rm -R " . trim($line));
	}
?>
