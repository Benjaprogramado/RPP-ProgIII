<?php
require_once './class/filemanager.php';

class Usuario extends FileManager
{
    public $_email;
    public $_tipo;
    public $_password;

    public function __construct($email, $tipo, $_password)
    {
        $this->_email = $email;
        $this->_tipo = $tipo;
        $this->_password = $_password;
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
        return $this->_email . '*' . $this->_tipo . '*' . $this->_password . PHP_EOL;
    }


    public static function readUserJson()
    {
        try {
            $lista = FileManager::readJson("./data/user.json");
            return $lista;
        } catch (Exception $e) {
            echo ("Error al leer archivo en formato json");
            return false;
        }
    }


    public static function saveUserJson($dato)
    {
        $listaDatos = Usuario::readUserJson();
        array_push($listaDatos, $dato);
        try {
            FileManager::saveJson("./data/user.json", $listaDatos);
            return true;
        } catch (Exception $e) {
            echo ("Error al escribir archivo en formato json");
            return false;
        }
    }



    public static function obtenerUsuario($email, $password)
    {
        $aux = false;

        $listaDatos = Usuario::readUserJson();
        if (empty($listaDatos)) {
            echo ("NO HAY USUARIOS CARGADOS");
            return false;
        } else {
            foreach ($listaDatos as $value) {
                if ($value->_email == $email && $value->_password == $password) {
                    $aux = true;
                    return new Usuario($value->_email, $value->_tipo, $value->_password);
                }
            }
        }
        if ($aux == false) {
            echo ("USUARIO NO EXISTE");
            return false;
        }
    }

    
    public static function validarEmail($email)
    {
        $aux = false;
        $listaDatos = Usuario::readUserJson();
        if (empty($listaDatos)) {
            return true;
        } else {
            foreach ($listaDatos as $value) {
                if ($value->_email == $email) {
                    $aux = true;
                }
            }
        }
        if ($aux == false) {
            return true;
        }
        if ($aux == true) {
            echo ("Email ya registrado");
            return false;
        }
    }


    public static function validarTipo($tipo)
    {
        if ($tipo == "admin" || $tipo == "user") {
            return true;
        } else {
            echo ("Tipo de usuario incorrecto");
            return false;
        }
    }

}
