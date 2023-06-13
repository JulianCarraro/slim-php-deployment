<?php

require_once './models/Usuario.php';

class UsuarioController extends Usuario
{
    public function CargarUno($request, $response, $args)
    {

        $params = $request->getParsedBody();

        $nombre = $params['nombre'];
        $clave = $params['clave'];
        $mail = $params['mail'];
        $sector = $params['sector'];

        $newUser = new Usuario();
        $newUser->nombre = $nombre;
        $newUser->clave = $clave;
        $newUser->mail = $mail;
        $newUser->sector = $sector;
        $newUser->CrearUsuario();

        $payload = json_encode(array("mensaje" => "El usuario se ha creado exitosamente"));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::ObtenerTodos();
        $payload = json_encode(array("listaDeUsuarios" => $lista));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

}
?>