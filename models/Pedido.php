<?php

Class Pedido
{
    public $idPedido;
    public $codigoPedido;
    public $idProducto;
    public $idMesa;
    public $estadoDelPedido;
    public $tiempoEstimado;
    public $fecha;
    public $urlFotoMesa;
    public $precioAcumuladoPedido;
    public $idEmpleado;
    public $idCliente;
    public $idEncuesta;

    public function __construct()
    {
        
    }

    // public function CrearPedido()
    // {
    //     $objAcessoDatos = AccesoDatos::ObtenerInstancia();
    //     $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO pedidos (ID, CodigoPedido, IdProducto, IdMesa, EstadoDelPedido, TiempoEstimado, FechaYHora, UrlFotoMesa, PrecioTotalAcumulado, IdEmpleado, IdCliente, IdEncuesta) 
    //     VALUES (:codigopedido, :idproducto, :idmesa, :estadodelpedido)");
    //     $consulta->bindValue(':codigopedido', $this->codigoPedido, PDO::PARAM_STR);
    //     $consulta->bindValue(':idproducto', $this->idProducto, PDO::PARAM_INT);
    //     $consulta->bindValue(':idmesa', $this->idMesa, PDO::PARAM_INT);
    //     $consulta->bindValue(':estadodelpedido', $this->estadoDelPedido, PDO::PARAM_STR);
    //     $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
    //     $consulta->execute();
    // }

    //HASTA ACA EL QUE HICE PRIMER SPRINT

    
}

?>