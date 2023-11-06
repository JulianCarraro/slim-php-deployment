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
        $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO mesas (CodigoDeMesa, EstadoDeMesa) VALUES (:codigoMesa, :estadoMesa)");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa', $this->estadoMesa, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ObtenerTodos()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    function generarNumeroDeMesa() 
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longitud = 5;
        $codigo = '';
        
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        return $codigo;
    }

    public static function ObtenerIdMesaPorCodigo($codigoDeMesa)
    {
        $listaMesa = Mesa::ObtenerTodos();
        $idMesa = -1;

        foreach($listaMesa as $mesa) 
        {
            if($mesa->codigoMesa == $codigoDeMesa)
            {
                $idMesa = $mesa->id;
                break;
            }
        }

        return $idMesa;
    } 
}

?>