<?php

class Conectar{

    public static function conexion() {
        try {
            $pdo = new PDO('sqlsrv:Server=;Database=nombreDB', 'UsuarioDB', 'PasswordDB');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (exception $e) {
            die($e->getMessage());
        }

} 
}
?>

