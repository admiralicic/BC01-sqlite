<?php

require "data.php";

if(count($argv) !== 4){
    die("Invalid number of parameters!".PHP_EOL);
}

if(substr($argv[2], -3) !== ".db"){
    die("Invalid database file extension".PHP_EOL);
}

if(!filter_var($argv[3], FILTER_VALIDATE_IP)){
    die("Invalid ip format!".PHP_EOL);
}

$db = new SQLite3($argv[2]);
seedData($db);
$ip_address = $argv[3];
$networks = getNetworks($db, $ip_address);
$db->close();

foreach ($networks as $network) {
        echo $network.PHP_EOL;
}