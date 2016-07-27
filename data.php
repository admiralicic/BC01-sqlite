<?php

function seedData($db){
    $db->exec('CREATE TABLE IF NOT EXISTS networks (network VARCHAR(20))');

    $countResult = $db->query('SELECT COUNT(*) as rows FROM networks');
    $count = $countResult->fetchArray();

    if($count['rows'] < 3){
        $db->exec("INSERT INTO networks (network) VALUES ('10.0.0.0/8')");
        $db->exec("INSERT INTO networks (network) VALUES ('10.1.0.0/16')");
        $db->exec("INSERT INTO networks (network) VALUES ('127.0.0.0/8')");
        $db->exec("INSERT INTO networks (network) VALUES ('192.168.8.0/24')");
    };

    $countResult->finalize();
}

function getNetworks($db, $ip){

    $ip_parts = explode(".", $ip);

    $results = $db->query("SELECT * FROM networks WHERE 
        network LIKE '$ip_parts[0]%/8' OR 
        network LIKE '$ip_parts[0].$ip_parts[1]%/16' OR
        network LIKE '$ip_parts[0].$ip_parts[1].$ip_parts[2]%/24'");

    $networks = [];

    while($row = $results->fetchArray(SQLITE3_ASSOC)){
        $networks[] = $row['network'];
    }

    $results->finalize();
    
    return $networks;
}