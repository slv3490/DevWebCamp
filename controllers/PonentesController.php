<?php

namespace Controllers;

use Classes\Paginacion;
use MVC\Router;
use Model\Ponente;
use Intervention\Image\ImageManagerStatic as Image;

class PonentesController {
    public static function index(Router $router) {
        //verifica si no es admin lo redirecciona al login
        if(!isAdmin()) {
            header("location: /login");
        }

        $pagina_actual = $_GET["page"];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if(!$pagina_actual || $pagina_actual < 1) {
            header("location: /admin/ponentes?page=1");
        }

        $registros_por_pagina = 5;
        $total = Ponente::total();

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        if($paginacion->total_paginas() < $pagina_actual) {
            header("location: /admin/ponentes?page=1");
        }

        $ponentes = Ponente::paginar($registros_por_pagina, $paginacion->offset());

        $router->render("admin/ponentes/index", [
            "titulo" => "Ponentes / Conferencistas",
            "ponentes" => $ponentes,
            "paginacion" => $paginacion->paginacion()
        ]);
    }
    public static function crear(Router $router) {
        //verifica si no es admin lo redirecciona al login
        if(!isAdmin()) {
            header("location: /login");
        }

        $alertas = [];
        $ponente = new Ponente;

        $redes = json_decode($ponente->redes);

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            //verifica si no es admin lo redirecciona al login
            if(!isAdmin()) {
                header("location: /login");
            }
            //Leer la imagen
            if(!empty($_FILES["imagen"]["tmp_name"])) {
                $carpeta_imagenes = "../public/img/speakers";

                //crear la carpeta si no existe
                if(!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0777, true);
                }
                $imagen_png = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,800)->encode("png",80);
                $imagen_webp = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,800)->encode("webp",80);
            
                $nombre_imagen = md5(uniqid(rand(), true));

                $_POST["imagen"] = $nombre_imagen;
            }

            $_POST["redes"] = json_encode($_POST["redes"], JSON_UNESCAPED_SLASHES);

            $ponente->sincronizar($_POST);

            //Validar
            $alertas = $ponente->validar();
            
            //Guardar el registro
            if(empty($alertas)) {
                //Guardar las imagenes
                $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");

                //Guardar en la DB
                $resultado = $ponente->guardar();

                if($resultado) {
                    header("location: /admin/ponentes");
                }
            }
        }

        $router->render("admin/ponentes/crear", [
            "titulo" => "Registrar Ponentes",
            "alertas" => $alertas,
            "ponente" => $ponente,
            "redes" => $redes
        ]);
    }
    public static function editar(Router $router) {
        //verifica si no es admin lo redirecciona al login
        if(!isAdmin()) {
            header("location: /login");
        }

        $alertas = [];
        //Validar el id
        $id = $_GET["id"];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if(!$id) {
            header("Location: /admin/ponentes");
        }

        $ponente = Ponente::find($id);

        if(!$ponente) {
            header("Location: /admin/ponentes");
        }

        $ponente->imagen_actual = $ponente->imagen;


        if($_SERVER["REQUEST_METHOD"] === "POST") {
            //verifica si no es admin lo redirecciona al login
            if(!isAdmin()) {
                header("location: /login");
            }
            if(!empty($_FILES["imagen"]["tmp_name"])) {
                $carpeta_imagenes = "../public/img/speakers";

                //crear la carpeta si no existe
                if(!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0777, true);
                }
                $imagen_png = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,800)->encode("png",80);
                $imagen_webp = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,800)->encode("webp",80);
            
                $nombre_imagen = md5(uniqid(rand(), true));

                $_POST["imagen"] = $nombre_imagen;
                debugear($_FILES);
            } else {
                $_POST["imagen"] = $ponente->imagen_actual;
            }

            $_POST["redes"] = json_encode($_POST["redes"], JSON_UNESCAPED_SLASHES);
            $ponente->sincronizar($_POST);

            $alertas = $ponente->validar();

            if(empty($alertas)) {
                if(isset($nombre_imagen)) {
                    $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");
                }

                $resultado = $ponente->guardar();

                if($resultado) {
                    header("location: /admin/ponentes");
                }
            }

        }

        $redes = json_decode($ponente->redes);

        $router->render("admin/ponentes/editar", [
            "titulo" => "Actualizar Ponente",
            "alertas" => $alertas,
            "ponente" => $ponente,
            "redes" => $redes
        ]);
    }
    public static function eliminar() {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            //verifica si no es admin lo redirecciona al login
            if(!isAdmin()) {
                header("location: /login");
            }

            $id = $_POST["id"];
            $ponente = Ponente::find($id);

            if(!isset($ponente)) {
                header("location: /admin/ponentes");
            }

            $resultado = $ponente->eliminar();

            if($resultado) {
                header("location: /admin/ponentes");
            }

            debugear($ponente);
        }
    }
}