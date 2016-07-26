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
$networks = getNetworks($db);
$db->close();

$ip_address = $argv[3];

$convert_ip_to_int = function($ip){

    $parts = explode(".", $ip);

    return  (int)$parts[0] * (pow(2,24)-1) +
            (int)$parts[1] * (pow(2,16)-1) +
            (int)$parts[2] * (pow(2,8)-1) +
            (int)$parts[3] * (pow(2,0));
};

$ip_address = $convert_ip_to_int($ip_address);
foreach ($networks as $network) {
    $parts = explode("/", $network);
    $netaddress = $parts[0];
    $subnet = $parts[1];

    $rangeStart = $convert_ip_to_int($netaddress);
    $rangeEnd = $rangeStart + (pow(2,32-(int)$subnet));

    if($ip_address >= $rangeStart && $ip_address <= $rangeEnd){
        echo $network.PHP_EOL;
    }
}