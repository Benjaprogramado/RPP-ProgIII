<?php

require_once './class/filemanager.php';
require_once './class/servicios.php';
require_once './class/auto.php';


class Turno extends FileManager
{

    public $_fecha;
    public $_patente;
    public $_marca;
    public $_modelo;
    public $_precio;


    public function __construct($patente, $marca, $modelo, $precio, $fecha)
    {
        $this->_fecha = $fecha;
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
        return $this->_fecha . '*' .$this->_patente . '*' . $this->_marca . '*' . $this->_modelo . '*' . $this->_precio . PHP_EOL;
    }

    public static function readTurnosJson()
    {
        try {
            $lista = FileManager::readJson("./data/turnos.json");
            return $lista;
        } catch (Exception $e) {
            echo ("Error al leer archivo en formato json");
            return false;
        }
    }


    public static function saveTurnosJson($dato)
    {
        $listaDatos = self::readTurnosJson();
        array_push($listaDatos, $dato);
        try {
            FileManager::saveJson("./data/turnos.json", $listaDatos);
            return true;
        } catch (Exception $e) {
            echo ("Error al escribir archivo en formato json");
            return false;
        }
    }
}