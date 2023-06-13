<?php

Class Mesa
{
    public $idMesa;
    public $codigoMesa;
    public $estadoMesa;

    public function __construct()
    {
        
    }

    public function CrearMesa()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO mesas (CodigoDeMesa, EstadoDeMesa) VALUES (:codigodemesa, :estadodemesa)");
        $consulta->bindValue(':codigodemesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estadodemesa', $this->estadoMesa, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ObtenerTodos()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT ID, CodigoDeMesa, EstadoDeMesa");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
}

?>