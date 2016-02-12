<?php
 defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
 set_time_limit (0);ini_set('max_execution_time', 0);
 
require(substr(getcwd(),0,strpos(getcwd(),'public_html')).'public_html/wp-config.php' );

$dbusername = DB_USER; 
$dbpassword = DB_PASSWORD; 
$dbhostname = DB_HOST; 
$dbname   = DB_NAME;

$dboutput=substr(getcwd(),0,strpos(getcwd(),'public_html')).'erpcsv/db_backup'; 
$csvf =  substr(getcwd(),0,strpos(getcwd(),'public_html'))."bridge/erpcsv/master_file.csv";
$csvFile =  substr(getcwd(),0,strpos(getcwd(),'public_html'))."bridge/erpcsv/master_file.csv";
$con=mysqli_connect("localhost",$dbusername,$dbpassword,$dbname); 
 
 