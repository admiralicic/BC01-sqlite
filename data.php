<?php

function seedData($db){
    $db->exec('CREATE TABLE IF NOT EXISTS networks 
        (network VARCHAR(20), range_start INT, range_end INT)');

    $countResult = $db->query('SELECT COUNT(*) as rows FROM networks');
    $count = $countResult->fetchArray();

    if($count['rows'] < 3){
        $db->exec("INSERT INTO networks (network, range_start, range_end) VALUES 
            ('10.0.0.0/8', 167772160, 184549375),
            ('10.1.0.0/16', 167837696, 167903231),
            ('127.0.0.0/8', 2130706432, 2147483647),
            ('192.168.8.0/24', 3232237568, 3232237823)");
    };

    $countResult->finalize();
}

function getNetworks($db, $ip){

    $ip_parts = explode(".", $ip);

    $results = $db->query("SELECT * FROM networks WHERE '".convertIpToInt($ip)."' BETWEEN range_start AND range_end");

    $networks = [];

    while($row = $results->fetchArray(SQLITE3_ASSOC)){
        $networks[] = $row['network'];
    }

    $results->finalize();
    
    return $networks;
}

function convertIpToInt($ip){
    $parts = explode(".", $ip);
    return  (int)$parts[0] * (pow(2,24)) +
            (int)$parts[1] * (pow(2,16)) +
            (int)$parts[2] * (pow(2,8)) +
            (int)$parts[3] * (pow(2,0));
};