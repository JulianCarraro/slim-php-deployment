<?php

Class Mesa
{
    public $idMesa;
    public $codigoMesa;
    public $estadoMesa;
    public $fechaAlta;
    public $fechaModificacion;
    public $activo;

    public function __construct(){}

    public function CrearMesa()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO mesas (codigoMesa, estadoMesa, fechaAlta, fechaModificacion, activo) 
        VALUES (:codigoMesa, :estadoMesa, :fechaAlta, :fechaModificacion, :activo)");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa', $this->estadoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', $this->fechaModificacion, PDO::PARAM_STR);
        $consulta->bindValue(':activo', $this->activo, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ObtenerTodos()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function ObtenerIdMesaPorCodigo($codigoDeMesa)
    {
        $listaMesa = Mesa::ObtenerTodos();
        $idMesa = -1;

        foreach($listaMesa as $mesa) 
        {
            if($mesa->codigoMesa == $codigoDeMesa)
            {
                $idMesa = $mesa->idMesa;
                break;
            }
        }

        return $idMesa;
    }
    

    public static function CambiarEstadoDeMesa($idMesa, $nuevoEstado)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();

        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE mesas SET estadoMesa = :estado, fechaModificacion = :fechaModificacion 
        WHERE idMesa = :idMesa");

        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $nuevoEstado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

    }

    public static function ModificarMesa($idMesa, $codigoDeMesa, $estadoMesa)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE mesas SET codigoMesa = :codigoMesa, estadoMesa = :estadoMesa, fechaModificacion = :fechaModificacion WHERE idMesa = :idMesa");

        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $codigoDeMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa', $estadoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function BorrarMesa($idMesa)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE usuarios SET activo = :activo, fechaModificacion = :fechaModificacion WHERE idMesa = :idMesa");

        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':activo', "NO", PDO::PARAM_INT);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function CerrarMesa($codigoDeMesa)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE mesas SET estadoMesa = :estadoMesa, fechaModificacion = :fechaModificacion WHERE codigoMesa = :codigoDeMesa");

        $consulta->bindValue(':codigoDeMesa', $codigoDeMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa', "Cerrado", PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function EstaLibre($idMesa)
    {
        $listaMesa = Mesa::ObtenerTodos();
        $estaLibre = false;

        foreach($listaMesa as $mesa) 
        {
            if($mesa->idMesa == $idMesa && $mesa->estadoMesa == "Cerrada")
            {
                $estaLibre = true;
                break;
            }
        }

        return $estaLibre;
    }

    public static function ObtenerMesaMasUsada($pedidos)
    {
        $contadorIdMesa = array();

        foreach($pedidos as $pedido)
        {
            $idMesa = $pedido->idMesa;

            if(isset($contadorIdMesa[$idMesa]))
            {
                $contadorIdMesa[$idMesa]++;
            }
            else
            {
                $contadorIdMesa[$idMesa] = 1;
            }
        }

        $mesaMasUsada = array_search(max($contadorIdMesa), $contadorIdMesa);

        return $mesaMasUsada;
    }

    public static function ObtenerMejoresComentarios($pedidos)
    {
        $mejoresComentarios = array();

        usort($pedidos, function($a, $b)
        {
            $comparacion = $b->puntuacion - $a->puntuacion;
            return $comparacion;
        });

        $mejoresPedidos = array_slice($pedidos, 0, 3);

        foreach($mejoresPedidos as $pedido)
        {
            $mejoresComentarios[] = $pedido->comentario;
        }

        return $mejoresComentarios;
    }
    
}

?>