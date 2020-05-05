<?php

namespace Housekeeping\Database;

use PDO;

class Database
{
    private static ?PDO $connection = null;

    public static function boot()
    {
        self::$connection = new PDO($_ENV['DATABASE_CONNECTION'] . ':host=' . $_ENV['DATABASE_HOST'] . ';dbname=' . $_ENV['DATABASE_NAME'] . ';charset=utf8', $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWORD']);
    }

    public static function connection(): PDO
    {
        return self::$connection;
    }
}
