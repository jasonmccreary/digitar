<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'encryption.class.php';

$con1 = mysql_connect('localhost', 'digitarb_super', 'diehard') or exit(mysql_error());
mysql_select_db('digitarb_archive', $con1) or exit(mysql_error());

$con2 = mysql_connect('localhost', 'digitar_site', '46016A9f') or exit(mysql_error());
mysql_select_db('digitar_archief', $con2) or exit(mysql_error());

$usersquery = mysql_query('select * from users', $con2) or exit(mysql_error());
while ($row = mysql_fetch_assoc($usersquery)) {
    $encrypter = new encryption;
    $pass = $encrypter->decrypt($row['pass']);
    echo $row['login'].' -> '.$pass.'<br/>';
}

// echo '<pre>';

// echo '<b>Nieuw</b><br/>';
// $query = mysql_query('SHOW TABLES',$con1) or die(mysql_error());
// while ($t = mysql_fetch_assoc($query)) {
// 	echo $t['Tables_in_digitarb_archive'].'<br/>';
// }

// echo '<br/><b>Oud</b><br/>';
// $query = mysql_query('SHOW TABLES',$con2) or die(mysql_error());
// while ($t = mysql_fetch_assoc($query)) {
// 	echo $t['Tables_in_digitar_archief'].'<br/>';
// }
