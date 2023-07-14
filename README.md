# Interview Project developed in CI4
Author: Vivekanand Saraswati
Author Email: vivekanand.saraswati@gmail.com

#Instructions - 
Create Database using mysql or PhpMyAdmin
Import table via db.sql
Change below Database details in app/Config/Database.php from line no 29
'hostname' => 'your_DB_host_name', #for local it is localhost
'username' => 'your_DB_username',
'password' => 'your_DB_password',
'database' => 'your_DB_Name',

#Run Project
Open terminal/cmd and go to folder and type below command
php spark serve
It will run project on http://localhost:8080 if port 8080 is open

## Server Requirements

PHP version 7.4 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
