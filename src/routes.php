<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    //return $this->renderer->render($response, 'index.phtml', $args);
    $rows = [];
    if (($gestor = fopen( __DIR__ . "/trabajadores.csv", "r")) !== FALSE) {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
            $row = array(
                "codigo" => $datos[0],
                "trabajador" => $datos[1],
                "documento" => $datos[2],
                "tienda" => $datos[3]
            );
            array_push($rows, $row);
        }
        fclose($gestor);
    }

    return $this->view->render($response, 'home.html', ['trabajadores' => $rows]);
    //return $response->withJson($rows);
});
