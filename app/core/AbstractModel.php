<?php

namespace App\Core;

use MongoDB\Client as MongoClient;

class AbstractModel
{
    public static function getDB()
    {
        $client = new MongoClient("mongodb://localhost:27017");
        $db = $client->email_parser;
        return $db;
    }
}