<?php
require_once './class/filemanager.php';

class Servicio extends FileManager
{
    public $_nombre;
    public $_id;
    public $_tipo;
    public $_precio;
    public $_demora;

    public function __construct($nombre, $id, $tipo, $precio, $demora)
    {
        $this->_nombre = $nombre;
        $this->_id = $id;
        $this->_tipo = $tipo;
        $this->_precio = $precio;
        $this->_demora = $demora;
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
        return $this->_nombre . '*' . $this->_id . '*' . $this->_tipo . $this->_precio. '*' . $this->_demora. '*' .PHP_EOL;

    }

    public static function saveServicioJson($dato)
    {
        $listaDatos = self::readServicioJson();
        array_push($listaDatos, $dato);
        try {
            FileManager::saveJson("./data/tiposServicios.json", $listaDatos);
            return true;
        } catch (Exception $e) {
            echo ("Error al escribir archivo en formato json");
            return false;
        }
    }

    public static function readServicioJson()
    {
        try {
            $lista = FileManager::readJson("./data/tiposServicios.json");
            return $lista;
        } catch (Exception $e) {
            echo ("Error al leer archivo en formato json");
            return false;
        }
    }
    public static function rassignPrice()
    {
    }
}
