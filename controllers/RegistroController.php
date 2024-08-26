<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Paquete;
use Model\Ponente;
use Model\Usuario;
use Model\Registro;
use Model\Categoria;
use Model\EventosRegistros;
use Model\Regalo;

class RegistroController {
    public static function crear(Router $router) {

        if(!isAuth()) {
            header("location: /");
            return;
        }

        //Verifica si el usuario ya esta registrado
        $registro = Registro::where("usuario_id", $_SESSION["id"]);

        if(isset($registro) && ($registro->paquete_id === "3" || $registro->paquete_id === "2")) {
            header("location: /boleto?id=" . urlencode($registro->token));
            return;
        }

        if(isset($registro) && $registro->paquete_id === "1") {
            header("location: /finalizar-registro/conferencias");
            return;
        }

        $router->render("registro/crear", [
            "titulo" => "Finalizar Registro"
        ]);
    }
    public static function gratis() {

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            if(!isAuth()) {
                header("location: /login");
                return;
            }

            $registro = Registro::where("usuario_id", $_SESSION["id"]);
            if(isset($registro) && $registro->paquete_id === "3") {
                header("location: /boleto?id=" . urlencode($registro->token));
                return;
            }

            $token = substr(md5(uniqid(rand(), true )), 0, 8);


            $datos = [
                "paquete_id" => 3,
                "pago_id" => "",
                "token" => $token,
                "usuario_id" => $_SESSION["id"]
            ];

            //Registrar al usuario
            $registro = new Registro($datos);
            $registro->guardar();

            if($registro) {
                header("location: /boleto?id=" . urlencode($registro->token));
                return;
            }
        }
    }

    public static function boleto(Router $router) {

        //Validar la URL
        $id = $_GET["id"];

        if(!$id || !strlen($id) === 8) {
            header("location: /");
            return;
        }

        //Buscar el token en la base de datos
        $registro = Registro::where("token", $id);
        if(!$registro) {
            header("location: /");
            return;
        }
        //Llenar la tabla de referencias
        $registro->usuario = Usuario::find($registro->usuario_id);
        $registro->paquete = Paquete::find($registro->paquete_id);

        $router->render("registro/boleto", [
            "titulo" => "Asistencia a DevWebCamp",
            "registro" => $registro
        ]);
    }

    public static function pagar() {

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            if(!isAuth()) {
                header("location: /login");
                return;
            }

            //Validar que POST no venga vacio
            if(empty($_POST)) {
                echo json_encode([]);
                return;
            }

            $datos = $_POST;
            $datos["token"] = substr(md5(uniqid(rand(), true )), 0, 8);
            $datos["usuario_id"] = $_SESSION["id"];

            //Registrar al usuario
            try {
                $registro = new Registro($datos);
                $resultado = $registro->guardar();
                echo json_encode($resultado);
            } catch (\Throwable $th) {
                echo json_encode([
                    "resultado" => "error"
                ]);
            }   
        }
    }

    public static function conferencias(Router $router) {

        if(!isAuth()) {
            header("location: /login");
            return;
        }

        //Validar que el usuario tenga el plan presencial
        $usuario_id = $_SESSION["id"];
        $registro = Registro::where("usuario_id", $usuario_id);

        if(isset($registro) && $registro->paquete_id === "2") {
            header("location: /boleto?id=" . urlencode($registro->token));
            return;
        }

        if($registro->paquete_id !== "1") {
            header("location: /");
            return;
        }

        //redireccionar a boleto virutal en caso de haber finalizado el registro
        if(isset($registro->regalo_id) && $registro->paquete_id === "1") {
            header("location: /boleto?id=" . urlencode($registro->token));
            return;
        }

        $eventos = Evento::ordenar("hora_id", "ASC");

        $evento_formateado = [];

        foreach($eventos as $evento) {
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);

            if($evento->categoria_id === "1" && $evento->dia_id === "1") {
                $evento_formateado["conferencias_v"][] = $evento;
            }
            if($evento->categoria_id === "1" && $evento->dia_id === "2") {
                $evento_formateado["conferencias_s"][] = $evento;
            }
            if($evento->categoria_id === "2" && $evento->dia_id === "1") {
                $evento_formateado["workshops_v"][] = $evento;
            }
            if($evento->categoria_id === "2" && $evento->dia_id === "2") {
                $evento_formateado["workshops_s"][] = $evento;
            }
        }

        $regalos = Regalo::all("ASC");

        //Manejando el registro mediante $_POST
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            if(!isAuth()) {
                header("location: /login");
                return;
            }

            $eventos = explode(",", $_POST["eventos"]);
            if(empty($eventos)) {
                echo json_encode(["resultado" => false]);
                return;
            }

            //Obtener el registro del usuario
            $registro = Registro::where("usuario_id", $_SESSION["id"]);
            if(!isset($registro)) {
                echo json_encode(["resultado" => false]);
                return;
            }

            $evento_array = [];
            //validar la disponibilidad de los eventos seleccionados
            foreach($eventos as $evento_id) {
                $evento = Evento::find($evento_id);

                //Comprobar que el evento exista
                if(!isset($evento) || $evento->disponibles === "0") {
                    echo json_encode(["resultado" => false]);
                    return;
                }
                
                $evento_array[] = $evento;
            }

            foreach($evento_array as $evento) {
                $evento->disponibles -= 1;
                $evento->guardar();

                //Almacenar el registro
                $datos = [
                    "evento_id" => $evento->id,
                    "registro_id" => $registro->id
                ];

                $registro_usuario = new EventosRegistros($datos);
                $registro_usuario->guardar();

                //Almacenar el regalo
                $registro->sincronizar(["regalo_id" => $_POST["regalo_id"]]);
                $resultado = $registro->guardar();

                if($resultado) {
                    echo json_encode([
                        "resultado" => $resultado,
                        "token" => $registro->token
                    ]);
                } else {
                    echo json_encode(["resultado" => false]);
                }
                return;

            }
        }

        $router->render("registro/conferencias", [
            "titulo" => "Elige Workshops & Conferencias",
            "eventos" => $evento_formateado,
            "regalos" => $regalos
        ]);
    }
}