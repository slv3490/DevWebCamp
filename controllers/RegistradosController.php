<?php

namespace Controllers;
use MVC\Router;
use Model\Ponente;
use Classes\Paginacion;
use Model\Paquete;
use Model\Registro;
use Model\Usuario;

class RegistradosController {
    public static function index(Router $router) {

        //verifica si no es admin lo redirecciona al login
        if(!isAdmin()) {
            header("location: /login");
        }

        $pagina_actual = $_GET["page"];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if(!$pagina_actual || $pagina_actual < 1) {
            header("location: /admin/registrados?page=1");
        }

        $registros_por_pagina = 10;
        $total = Registro::total();

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        if($paginacion->total_paginas() < $pagina_actual) {
            header("location: /admin/registrados?page=1");
        }

        $registros = Registro::paginar($registros_por_pagina, $paginacion->offset());
        foreach($registros as $registro) {
            $registro->usuario = Usuario::find($registro->usuario_id);
            $registro->paquete = Paquete::find($registro->paquete_id);
        }

        $router->render("admin/registrados/index", [
            "titulo" => "Usuarios Registrados",
            "registros" => $registros,
            "paginacion" => $paginacion->paginacion()
        ]);
    }
}