<?php

namespace Controllers;

use Model\Regalo;
use Model\Registro;

class ApiRegalos {
    public static function index() {
        if(!isAdmin()) {
            echo json_encode([]);
            return; 
        }

        $regalos = Regalo::all();

        foreach($regalos as $regalo) {
            $regalo->total = Registro::totalArray(["regalo_id" => $regalo->id, "paquete_id" => "1"]);
        }

        echo json_encode($regalos);
        return;
    }
}