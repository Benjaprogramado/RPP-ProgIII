<?php

require_once './class/filemanager.php';
require_once './class/servicios.php';

class Auto extends FileManager
{

    public $_patente;
    public $_marca;
    public $_modelo;
    public $_precio;


    public function __construct($patente, $marca, $modelo, $precio)
    {
        $this->_patente = $patente;
        $this->_marca = $marca;
        $this->_modelo = $modelo;
        $this->_precio = $precio;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __toString()
    {
        return $this->_patente . '*' . $this->_marca . '*' . $this->_modelo . '*' . $this->_precio . PHP_EOL;
    }

    public static function readAutoJson()
    {
        try {
            $lista = FileManager::readJson("./data/vehiculos.json");
            return $lista;
        } catch (Exception $e) {
            echo ("Error al leer archivo en formato json");
            return false;
        }
    }


    public static function saveAutoJson($dato)
    {
        $listaDatos = self::readAutoJson();
        array_push($listaDatos, $dato);
        try {
            FileManager::saveJson("./data/vehiculos.json", $listaDatos);
            return true;
        } catch (Exception $e) {
            echo ("Error al escribir archivo en formato json");
            return false;
        }
    }

    public static function borrarAutoJson()
    {
        return parent::borrarJson("./data/vehiculos.json");
    }



    public static function deleteAutoJson($dato)
    {
        $listaDatos = self::readAutoJson();
        $borrar = self::findCar($dato->_patente);
        self::borrarAutoJson();
        foreach ($listaDatos as $value) {

            if ($value->_patente != $borrar->_patente) {
                self::saveAutoJson($value);
            }
        }
    }




    public static function findCar($patente)
    {
        $aux = false;

        $listaDatos = Auto::readAutoJson();
        if (empty($listaDatos)) {

            return false;
        } else {
            foreach ($listaDatos as $value) {
                if ($value->_patente == $patente) {
                    $aux = true;
                    return new Auto($patente, $value->_marca, $value->_modelo, $value->_precio);
                }
            }
        }
        if ($aux == false) {

            return false;
        }
    }

    public static function findCarKI($patente)
    {
        $aux = false;

        $listaDatos = Auto::readAutoJson();
        if (empty($listaDatos)) {
            echo ("NO EXISTE " . $patente);
            return false;
        } else {
            foreach ($listaDatos as $value) {
                if (strcasecmp($value->_patente, $patente) == 0) {
                    $aux = true;
                    return new Auto($value->_patente, $value->_marca, $value->_modelo, $value->_precio);
                }
            }
        }
        if ($aux == false) {
            echo ("NO EXISTE " . $patente);
            return false;
        }
    }
}
