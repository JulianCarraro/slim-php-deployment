<?php

require_once './models/Producto.php';

class ProductoController extends Producto
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $sector = $params['sector'];
        $nombre = $params['nombre'];
        $precio = $params['precio'];

        $newTable = new Producto();
        $newTable->sector = $sector;
        $newTable->nombre = $nombre;
        $newTable->precio = $precio;
        $newTable->CrearProducto();

        $payload = json_encode(array("mensaje" => "El producto se ha creado exitosamente"));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::ObtenerTodos();
        $payload = json_encode(array("listaDeProductos" => $lista));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>