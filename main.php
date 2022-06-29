<?php
require 'wp_func.php';

if(count($argv) !== 5){
	echo 'Wrong number of arguments supplied!' . PHP_EOL . ' Usage : php main.php --mode sql --hash \'$P$HASH\'';
    exit;
}
$start_time = microtime(true);

for($arg_count=1; $arg_count<count($argv); $arg_count++){
    switch($argv[$arg_count]){
        case '--mode':
        case '-m':
            $mode = $argv[$arg_count + 1];
            break;
        case '--hash':
        case '-h':
            $hash = $argv[$arg_count + 1];
            if(!is_string($hash) || is_null($hash) || strlen($hash) < 1){
                exit('Invalid hash, Make sure you\'ve provided hash with enclosed string');
            }
            break;
    }
}

$servername = "localhost";
$username = "root";
$password = "";
// $hash_to_crack = '$P$Be0yzCOtkrjAPOZcedJdhV122xVJSV0';

try {
  $conn = new PDO("mysql:host=$servername;dbname=dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

$query = 'select id,password from users';

// $query = "SELECT CONCAT('*', UPPER(SHA1(UNHEX(SHA1('mypass'))))) as pass";
// $stmt = $conn->query($query);
// $res = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($res);

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    if( wp_check_password( $row['password'],$hash ) ){
        echo 'Found matching password for hash :' . $row['password']; 
        exit;
    }
}

$end_time = microtime(true);
$execution_time = $end_time - $start_time;

echo "execution time : " . $execution_time . " seconds";