<?php	
	echo ("\e[96m Installing MYSQL \e[0m \n\n");
	system('sudo apt-get install mysql-client mysql-server');
	echo "\n\n";
	echo ("\e[92m MYSQL Succesfully Installed...\e[0m");

	echo ("\n\n ########################################## \n\n");		
	echo ("\e[96m Installing Apache Server...\e[0m");
	echo "\n\n";
	system('sudo apt-get install apache2 apache2-utils ');
	system('sudo systemctl enable apache2');
	system('sudo systemctl start apache2');
	echo ("\n\n");
	echo ("\e[92mApache Succesfully Installed...\e[0m \n\n");
	echo ("\n\n ########################################## \n\n");		
	
	echo ("\e[96m Now Install Wordpress \e[0m\n\n");
	echo ("\n\n");
	$WEBROOT = readline("Your webroot directory? (Include trailing slash. i.e. /var/www/html): ");
	$VHOSTPATH = readline("Enter your vhost file path (i.e. /etc/apache2/users/mysite.conf):  ");
	$SERVERNAME = readline("What is your server name? ");
	$APACHEUSER = readline("What is the user apache runs under? ");
	$MYSQLDB= readline("Enter MySQL Database name: ");
	$MYSQLHOST = readline("Enter MYSQL Host: ");
	$MYSQLUSER = readline("Enter MYSQL User: ");
	$MYSQLPWD = readline("Enter MYSQL Password: ");
	$_SERVER['HTTP_HOST'] = $SERVERNAME;

	echo ("\n\n \e[96m Creating DB \n\n \e[0m");
	if(strlen($MYSQLPWD)) 
	{
		exec("mysql -h " . $MYSQLHOST . " -u " . $MYSQLUSER . " -p " . $MYSQLPWD . "  'CREATE DATABASE IF NOT EXISTS '" . 			$MYSQLDB . ";");
		echo ("\e[92m Your Database Has been Successfully Created...! \e[0m");
		echo ("\e[34m");
		system('date');
		echo ("\e[0m");	
	}
	else 
	{
	 	exec("mysql -h " . $MYSQLHOST . " -u " . $MYSQLUSER . "  'CREATE DATABASE IF NOT EXISTS '" . $MYSQLDB . ";");
	}
	
 	echo ("\n\n ########################################## \n\n");

	echo ("\e[96m Downloading WordPress \e[0m \n\n");
	exec('wget http://wordpress.org/latest.tar.gz');

	echo ("\n\n ########################################## \n\n");	
	
	echo ("\e[96m Unpacking WordPresss \e[0m \n\n");
	exec('tar xzf latest.tar.gz');

	echo("\e[96m moving wordpress into the webroot \e[0m \n\n" . $WEBROOT);
	exec('mkdir -p "' . $WEBROOT . '"');
	exec('cp -rf wordpress/* "' . $WEBROOT . '"'); 
	exec('rm -rf wordpress');

	echo("\n\n\e[96m Setup folder permissions..\e[0m \n\n ");
	exec('chown -R ' . $APACHEUSER . ':ljadminvm ' . $WEBROOT);

	echo("\e[96m Add entry in /etc/hosts file...\e[0m \n\n");
	exec('echo "192.168.0.2\t' . $SERVERNAME . '" >> sudo /etc/hosts');

	echo("\e[96m Setting up the vhost...\e[0m \n\n");
	$VHOST='NameVirtualHost *:80' . PHP_EOL . PHP_EOL;
	$VHOST.='<Directory ' . $WEBROOT . '>' . PHP_EOL;
	$VHOST.='Options Indexes FollowSymLinks MultiViews' . PHP_EOL;
	$VHOST.='AllowOverride All' . PHP_EOL;
	$VHOST.='Order allow,deny' . PHP_EOL;
	$VHOST.='Allow from all' . PHP_EOL;
	$VHOST.='' . PHP_EOL;

	$VHOST.='' . PHP_EOL;
	$VHOST.='DocumentRoot ' . $WEBROOT . PHP_EOL;
	$VHOST.= 'ServerName ' . $SERVERNAME . PHP_EOL;
	$VHOST.= 'DirectoryIndex index.php' . PHP_EOL;
	$VHOST.='';

	$fw = fopen($VHOSTPATH, "w");
	fwrite($fw, $VHOST);

	echo("\n\n \e[96m Setting up the config file...\e[0m\n\n");
	$config_file = file($WEBROOT . 'wp-config-sample.php');
	$secret_keys = file_get_contents( 'https://api.wordpress.org/secret-key/1.1/salt/' );
	$secret_keys = explode( "\n", $secret_keys );
	foreach ( $secret_keys as $k => $v )
	{
	    $secret_keys[$k] = substr( $v, 28, 64 );
	}
	array_pop($secret_keys);
	$config_file = str_replace('database_name_here', $MYSQLDB, $config_file);
	$config_file = str_replace('username_here', $MYSQLUSER, $config_file);
	$config_file = str_replace('password_here', $MYSQLPWD, $config_file);
	$config_file = str_replace('localhost', $MYSQLHOST, $config_file);
	$config_file = str_replace("'AUTH_KEY',         'put your unique phrase here'", "'AUTH_KEY',         '{$secret_keys[0]}'", $config_file);
	$config_file = str_replace("'SECURE_AUTH_KEY',  'put your unique phrase here'", "'SECURE_AUTH_KEY',  '{$secret_keys[1]}'", $config_file);
	$config_file = str_replace("'LOGGED_IN_KEY',    'put your unique phrase here'", "'LOGGED_IN_KEY',    '{$secret_keys[2]}'", $config_file);
	$config_file = str_replace("'NONCE_KEY',        'put your unique phrase here'", "'NONCE_KEY',        '{$secret_keys[3]}'", $config_file);
	$config_file = str_replace("'AUTH_SALT',        'put your unique phrase here'", "'AUTH_SALT',        '{$secret_keys[4]}'", $config_file);
	$config_file = str_replace("'SECURE_AUTH_SALT', 'put your unique phrase here'", "'SECURE_AUTH_SALT', '{$secret_keys[5]}'", $config_file);
	$config_file = str_replace("'LOGGED_IN_SALT',   'put your unique phrase here'", "'LOGGED_IN_SALT',   '{$secret_keys[6]}'", $config_file);
	$config_file = str_replace("'NONCE_SALT',       'put your unique phrase here'", "'NONCE_SALT',       '{$secret_keys[7]}'", $config_file);

	if(file_exists($WEBROOT .'wp-config.php'))
	{
	    unlink($WEBROOT .'wp-config.php');
	}

	$fw = fopen($WEBROOT . 'wp-config.php', "a");
	foreach ( $config_file as $line_num => $line )
	{
	    fwrite($fw, $line);
	}
	
	require("plugin-installer.php");
	require("theme-installer.php");

	echo ("\n\n ########################################## \n\n");		
	echo("\e[96m Installing WordPress \e[0m \n\n");
	define('ABSPATH', $WEBROOT);
	define('WP_CONTENT_DIR', 'wp-content/');
	define('WPINC', 'wp-includes');
	define( 'WP_LANG_DIR', WP_CONTENT_DIR . '/languages' );

	define('WP_USE_THEMES', true);
	define('DB_NAME', $MYSQLDB);
	define('DB_USER', $MYSQLUSER);
	define('DB_PASSWORD', $MYSQLPWD);
	define('DB_HOST', $MYSQLHOST);

	$_GET['step'] = 2;
	$_POST['weblog_title'] = "My Site";
	$_POST['user_name'] = "user123";
	$_POST['admin_email'] = "uesr123@example.com";
	$_POST['blog_public'] = true;
	$_POST['admin_password'] = "xxxxx";
	$_POST['admin_password2'] = "xxxxx";

	require(ABSPATH . 'wp-admin/install.php');
	require(ABSPATH . 'wp-load.php');
	require(ABSPATH . WPINC . '/class-wp-walker.php');
	require(ABSPATH . 'wp-admin/includes/upgrade.php');
		
	echo('restarting apache');
	exec('apachectl -k graceful');
	echo('Your WordPress site is ready. Navigate to http://' . $SERVERNAME . ' in your web browser');
?>
