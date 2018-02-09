<?php

# Fill our vars and run on cli
# $ php -f db-connect-test.php

$dbname = 'YellowstoneDB1';
$dbuser = 'tylre';
$dbpass = 'tylrePass';
$dbhost = 'localhost';

$connect = mysql_connect($dbhost, $dbuser, $dbpass) or die("Unable to Connect to '$dbhost'");

$db_list = mysql_list_dbs($connect);

while ($row = mysql_fetch_object($db_list)) {
     echo $row->Database . "\n";
}

mysql_select_db($dbname) or die("Could not open the db '$dbname'");

?>