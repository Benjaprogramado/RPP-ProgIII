<?php

class FileManager
{

    /////////////////////////////TXT/////////////////////////////////////

    public static function saveTxt($dato, $file)
    {
        if (is_string($file)) {
            $archivo = fopen($file, 'a+');
            fwrite($archivo, $dato);
            fclose($archivo);
        } else {
            echo ("Nombre de archivo invalido!!");
        }
    }

    public static function readTxt($file)
    {
        if (is_string($file)) {
            $archivo = fopen($file, 'r');
            $lista = array();

            while (!feof($archivo)) {
                $linea = fgets($archivo);
                $datos = explode('*', $linea);
                if (count($datos) > 1) {
                    array_push($lista, $datos);
                }
            }
            fclose($archivo);

            return $lista;
        } else {
            echo ("Nombre de archivo invalido!!");
        }
    }
    ///////////////////////////JSON///////////////////////////////////////////

    public static function saveJson($file, $array)
    {
        if (is_string($file)) {
            $archivo = fopen($file, 'w');
            fwrite($archivo, json_encode($array));
            fclose($archivo);
        } else {
            echo ("Nombre de archivo invalido!!");
        }
    }

    public static function readJson($file)
    {
        if (file_exists($file)) {
            $archivo = fopen($file, 'r');
            $size = filesize($file);
            if ($size > 0) {
                $fread = fread($archivo, $size);
            } else {
                $fread = "{}";
            }
            fclose($archivo);
            $array = array();
            $array = json_decode($fread);
        } else {
            $array = array();
        }
        return $array;
    }

    public static function borrarJson($file)
    {
        $array = array();
        self::saveJson($file, $array);
    }




    ///////////////////////////////SERIALIZE////////////////////////////////////

    public static function saveSerialize($file, $array)
    {
        if (is_string($file)) {
            $archivo = fopen($file, 'w');
            fwrite($archivo, serialize($array));
            fclose($archivo);
        } else {
            echo ("Nombre de archivo invalido!!");
        }
    }

    public static function readSerialize($file)
    {

        if (file_exists($file)) {
            $archivo = fopen($file, 'r');
            $size = filesize($file);
            if ($size > 0) {
                $fread = fread($archivo, $size);
            } else {
                $fread = "{}";
            }
            fclose($archivo);
            $array = array();
            $array = unserialize($fread);
        } else {
            $array = array();
        }
        return $array;
    }
}
