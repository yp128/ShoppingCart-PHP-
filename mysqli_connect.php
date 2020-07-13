<?php

define('DB_USER','root');
define('DB_PASS','');
define('DB_HOST','localhost');
define('DB_NAME','yashPatelStore');

$dbc=@mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) OR die("databse can not connect: ". mysqli_connect_error());

mysqli_set_charset($dbc,'utf8');

?>