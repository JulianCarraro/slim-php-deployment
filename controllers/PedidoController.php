<?php

require_once './models/Pedido.php';
require_once './Models/Mesa.php';

    Class PedidoController extends Pedido 
    {
        public function CargarUno($request, $response, $args)
        {
            $params = $request->getParsedBody();

            $codigoDePedido = Pedido::generarNumeroDePedido();
            $codigoDeMesa = $params['codigoMesa'];
            $mailMozo = $params['mailMozo'];
            $nombreCliente = $params['nombreCliente'];
            $fecha = date("Y-m-d H:i:s");

            $idMesa = Mesa::ObtenerIdMesaPorCodigo($codigoDeMesa);
            $idMozo = Usuario::ObtenerIdPorMail($mailMozo);

            if($idMesa != -1)
            {
                if(Mesa::EstaLibre($idMesa))
                {
                    if($idMozo != -1)
                    {
                        if(isset($_FILES['fotoMesa']) && $_FILES['fotoMesa'] != null)
                        {
                            $fotoMesa = Pedido::GuardarImagenPedido("ImagenesMesas/", $_FILES['fotoMesa'], $codigoDeMesa, $codigoDePedido, $nombreCliente);
                        }
                        else
                        {
                            $fotoMesa = "-";
                        }

                        $nuevoPedido = new Pedido();
                        $nuevoPedido->codigoPedido = $codigoDePedido;
                        $nuevoPedido->idMesa = $idMesa;
                        $nuevoPedido->idEmpleado = $idMozo;
                        $nuevoPedido->fecha = $fecha;
                        $nuevoPedido->fotoMesa = $fotoMesa;
                        $nuevoPedido->precioTotal = 0;
                        $nuevoPedido->nombreCliente = $nombreCliente;
                        $nuevoPedido->tiempoEstimado = -1;
                        $nuevoPedido->fechaModificacion = date('Y-m-d');
                        $nuevoPedido->estadoDelPedido = "Pendiente";
                        $nuevoPedido->activo = "SI";
    
                        Mesa::CambiarEstadoDeMesa($nuevoPedido->idMesa, "Con cliente esperando pedido"); //Con cliente esperando pedido
    
                        $nuevoPedido->CrearPedido();
    
                        $payload = json_encode(array("mensaje" => "El Pedido {$codigoDePedido} se creo exitosamente"));
                    }
                    else
                    {
                        $payload = json_encode(array("mensaje" => "El mail del Mozo no Existe"));
                    }                  
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "La mesa esta ocupada"));
                }             
            }
            else
            {
                $payload = json_encode(array("mensaje" => "La mesa ingresada no existe"));
            }

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');

        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Pedido::ObtenerTodos();
            $payload = json_encode(array("listaDePedidos" => $lista));
    
            $response->getBody()->write($payload);
    
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerPedidosSegunEstado($request, $response, $args)
        {
            $params = $request->getQueryParams();
    
            $estadoDelPedido = $params['estadoDelPedido'];
    
            $lista = Pedido::ObtenerPedidosSegunEstado($estadoDelPedido);     
    
            $payload = json_encode(array("listaDePedidos" => $lista));
    
            $response->getBody()->write($payload);
    
            return $response->withHeader('Content-Type', 'application/json');
        }
        

        public function ModificarUno($request, $response, $args)
        {
            $params = $request->getParsedBody();
    
            $codigoDePedido = $params['codigoDePedido'];
            $idMesa = $params['idMesa'];
            $fecha = $params['fecha'];
            $estadoDelPedido = $params['estadoDelPedido'];
            $nombreCliente = $params['nombreCliente'];
            $precioTotal = $params['precioTotal'];
            $mailEmpleado = $params['mailEmpleado'];
            

            $idEmpleado = Usuario::ObtenerIdPorMail($mailEmpleado);

            if($idEmpleado != -1)
            {
                if(Pedido::ModificarPedido($idMesa, $fecha, $estadoDelPedido, $nombreCliente, $precioTotal, $idEmpleado, $codigoDePedido) > 0)
                {
                    $payload = json_encode(array("mensaje" => "El pedido {$codigoDePedido} se actualizo correctamente"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "No se realizaron modificaciones"));
                }
            }
            else
            {
                $payload = json_encode(array("mensaje" => "No existe empleado con ese Mail"));
            }
               
    
            $response->getBody()->write($payload);
    
            return $response->withHeader('Content-Type', 'application/json');
            
        }

        public function BorrarUno($request, $response, $args)
        {
            $idPedido = $args['idPedido'];
    
            if(Pedido::BorrarPedido($idPedido) > 0)
            {
                $payload = json_encode(array("mensaje" => "El pedido {$idPedido} se dio de baja"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "No se realizo la baja"));
            }
    
            $response->getBody()->write($payload);
    
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function MozoPedidoCliente($request, $response, $args)
        {
            $params = $request->getParsedBody();

            $codigoDePedido = $params['codigoDePedido'];
            $estadoDeMesa = $params['nuevoEstadoMesa'];

            $pedido = Pedido::ObtenerPedido($codigoDePedido);
    
            Mesa::CambiarEstadoDeMesa($pedido->idMesa, $estadoDeMesa);

            if($estadoDeMesa == "Con cliente comiendo")
            {
                Pedido::CambiarEstadoDelPedido($codigoDePedido, "Entregado");   
            }
            else if($estadoDeMesa == "Con cliente pagando")
            {
                Pedido::CambiarEstadoDelPedido($codigoDePedido, "Finalizado");   
            }         

            $payload = json_encode(array("mensaje" => "Se ha entregado el pedido correctamente"));
    
            $response->getBody()->write($payload);
    
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerPedidoCliente($request, $response, $args)
        {
            $params = $request->getQueryParams();

            $codigoDeMesa = $params['codigoDeMesa'];
            $codigoDePedido = $params['codigoDePedido'];

            $idMesa = Mesa::ObtenerIdMesaPorCodigo($codigoDeMesa);

            $pedido = Pedido::ObtenerPedido($codigoDePedido);

            if($pedido->idMesa == $idMesa)
            {
                if($pedido->estadoDelPedido == "Pendiente")
                {
                    $payload = json_encode(array("mensaje" => "El pedido {$codigoDePedido} todavia no fue tomado"));
                }
                else if($pedido->estadoDelPedido == "En Preparacion")
                {
                    $payload = json_encode(array("mensaje" => "El estado del pedido {$codigoDePedido} es {$pedido->estadoDelPedido} y tiene un tiempo estimado de 
                    {$pedido->tiempoEstimado} minutos"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "El pedido {$codigoDePedido} ya fue entregado"));
                }
            }
            else
            {
                $payload = json_encode(array("mensaje" => "No existe el pedido {$codigoDePedido} en la mesa {$idMesa}"));
            }

            $response->getBody()->write($payload);
    
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ClienteCalificaPedido($request, $response, $args)
        {
            $params = $request->getParsedBody();

            $codigoDePedido = $params['codigoDePedido'];
            $puntuacion = $params['puntuacion'];
            $comentario = $params['comentario'];

            $pedido = Pedido::ObtenerPedido($codigoDePedido);

            if($pedido->estadoDelPedido = "Finalizado")
            {
                if(Pedido::ModificarPuntuacionYComentario($codigoDePedido, $puntuacion, $comentario) > 0)
                {
                    $payload = json_encode(array("mensaje" => "Se ha calificado correctamente el pedido"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "No se pudo calificar"));
                }
            }
            else
            {
                $payload = json_encode(array("mensaje" => "El pedido todavia no se puede calificar"));
            }

            $response->getBody()->write($payload);
    
            return $response->withHeader('Content-Type', 'application/json');
        }


    }

?>