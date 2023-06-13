<?php

require_once("./db/AccesoDatos.php");

class Usuario
{
    public $id;
    public $nombre;
    public $clave;
    public $mail;
    public $sector;

    public function __construct()
    {
        
    }

    public function CrearUsuario()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO usuarios (Nombre, Clave, Mail, Sector) VALUES (:nombre, :clave, :mail, :sector)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ObtenerTodos()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT ID, Nombre, Clave, Mail, Sector");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }
}

?>