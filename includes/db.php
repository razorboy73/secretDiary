<?php ob_start();

$db["db_host"] = "localhost";
$db["db_user"] = "root";
$db["db_pass"] = "";
$db["db_name"] = "secretdiary";


foreach($db as $key => $value){
    define(strtoupper($key), $value);

}


$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(mysqli_connect_error()){
    die("Database Connect Issue");
}

?>