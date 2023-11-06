<?php

require_once './models/Mesa.php';

class MesaController extends Mesa
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $codigoMesa = $params['CodigoDeMesa'];
        $estadoMesa = $params['EstadoDeMesa'];

        $newTable = new Mesa();
        $newTable->codigoMesa = $codigoMesa;
        $newTable->estadoMesa = $estadoMesa;
        $newTable->CrearMesa();

        $payload = json_encode(array("mensaje" => "La mesa se ha creado exitosamente"));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::ObtenerTodos();
        $payload = json_encode(array("listaDeMesas" => $lista));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>