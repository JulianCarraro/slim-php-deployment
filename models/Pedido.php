<?php

include_once("Usuario.php");
include_once("Mesa.php");

Class Pedido
{
    public $_idPedido;
    public $_codigoPedido;
    public $_idMesa;
    public $_fecha;
    public $_fotoMesa;
    public $_precioAcumuladoPedido;
    public $_idEmpleado;
    public $_idCliente;

    public function __construct()
    {

    }

    public function CrearPedido()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO pedidos (CodigoPedido, IdMesa, Fecha, FotoMesa, PrecioTotalAcumulado, IdEmpleado, IdCliente) 
        VALUES (:_codigoPedido, :_idMesa, :_fecha, :_fotoMesa, :_precioTotalAcumulado, :_idEmpleado, :_idCliente)");

        $consulta->bindValue(':_codigoPedido', $this->_codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':_idMesa', $this->_idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':_fecha', $this->_fecha, PDO::PARAM_STR);
        $consulta->bindValue(':_fotoMesa', $this->_fotoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':_precioTotalAcumulado', $this->_precioAcumuladoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':_idEmpleado', $this->_idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':_idCliente', $this->_idCliente, PDO::PARAM_INT);
    
        $consulta->execute();

    }

    public static function ObtenerTodos()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    function generarNumeroDePedido() 
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longitud = 6;
        $codigo = '';
        
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        return $codigo;
    }
    
}

?>