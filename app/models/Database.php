<?php
/** Conexión PDO reutilizable a la base de datos MySQL. */
class Database
{
    private static ?PDO $pdo = null;

    public static function conectar(): PDO
    {
        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                BD_HOST,
                BD_NOMBRE
            );

            self::$pdo = new PDO($dsn, BD_USUARIO, BD_CLAVE, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => true,
            ]);
        }

        return self::$pdo;
    }
}
