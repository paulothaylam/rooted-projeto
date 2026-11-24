<?php

declare(strict_types=1);

final class Database
{
    public static function conectar(): PDO
    {
        $host = getenv('DB_HOST') ?: 'db';
        $port = (int) (getenv('DB_PORT') ?: 3306);
        $database = getenv('DB_NAME') ?: 'rooted';
        $username = getenv('DB_USER') ?: 'app';
        $password = getenv('DB_PASSWORD') ?: '';

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $database
        );

        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
