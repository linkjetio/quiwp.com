<?php	
	include('format.php');
	echo "****************PHP Insatllation**************";
	echo "\n\n";	
	system(' apt-get install php7.0 php7.0-mysql libapache2-mod-php7.0 php7.0-cli php7.0-cgi php7.0-gd');
	echo "\n\n";
	echo colorize(" PHP Succesfully Installed...", "SUCCESS");
	echo "\n\n";
	echo "***************MYSQL installation****************";
	system('sudo apt-get install mysql-client mysql-server');
	echo "\n\n";
	echo colorize("MYSQL Succesfully Installed...", "SUCCESS");
	echo "\n\n";		
	echo "****************Apache Insatllation**************";
	echo "\n\n";
	system('sudo apt-get install apache2 apache2-utils ');
	system('sudo systemctl enable apache2');
	system('sudo systemctl start apache2');
	echo "\n\n";
	echo colorize(" Apache Succesfully Installed...", "SUCCESS");
	echo "\n\n";
	echo "**************Creating Database********************** \n\n";	
	system('mysql -u root -p"mysql" < ~/mydata.sql');
	echo "\n\n";
	echo colorize(" Your Database Has abeen Successfully Created...!", "SUCCESS");
	echo "\n\n";
	echo "******************Now Install Wordpress******************";
	echo "\n\n";
	system('wget -c http://wordpress.org/latest.tar.gz');	
	system('tar -xzvf latest.tar.gz');
	system('sudo rsync -av wordpress/* /var/www/html/');
	system('sudo chown -R www-data:www-data /var/www/html/');
	system('sudo chmod -R 755 /var/www/html/');
	system('cd /var/www/html/');
   	system('sudo mv wp-config-sample.php wp-config.php');
	system('sudo systemctl restart apache2.service'); 
	system('sudo systemctl restart mysql.service');
?>

