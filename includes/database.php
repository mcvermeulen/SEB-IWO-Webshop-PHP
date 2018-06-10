<?php

function DatabaseConnect()
{
    $config = [
        'hostname' => getenv('HOSTNAME'),
        'port' => getenv('SQL_PORT'),
        'user' => getenv('SQL_USER'),
        'pass' => getenv('SQL_PASS'),
        'database' => getenv('SQL_DB')
    ];

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    try {
        $dsn = "sqlsrv:Server=$config[hostname],$config[port];Database=$config[database];ConnectionPooling=0";
        $connection = new PDO ($dsn, $config['user'], $config['pass'], $options);
        return $connection;
    } catch (PDOException $e) {
        die("Failed to get DB handle: " . $e->getMessage());
    }
}

