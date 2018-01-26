# Swirkz
Swirkz Chat is a collaborated chat created by students to improve their programming skills. Use on your own risk 😉

### Quick Start
Download files:
```
$ git clone git@github.com:pilotpirxie/swirkz.git
```
or:
```
$ wget https://github.com/pilotpirxie/swirkz/archive/1.0.zip
```
Create database:
```
mysql> CREATE DATABASE swirkz
```
and import schema from config/swirkz.sql:
```
mysql -u username -p database_name < swirkz.sql
```
And finally, insert your config info in config/db_conn.php
```
<?php
$db_host = 'localhost';
$db_name = 'swirkz';
$db_user = 'root';
$db_pass = '';
```

License
```
MIT
```
