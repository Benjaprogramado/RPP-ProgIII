<?php


date_default_timezone_set("America/Argentina/Buenos_Aires");

require_once "./class/usuario.php";
require_once "./class/servicios.php";
require_once "./class/auto.php";
require_once './class/turnos.php';


require_once "./class/jwt.php";

require __DIR__ . '/vendor/autoload.php';
//composer require firebase/php-jwt
//use \Firebase\JWT\JWT;

$method = $_SERVER["REQUEST_METHOD"];
$path_info = $_SERVER["PATH_INFO"];


switch ($method) {
    case 'POST': //AGREGA DATOS
        switch ($path_info) {
            case "/registro":

                if (isset($_POST['email']) && isset($_POST['tipo']) && isset($_POST['password'])) {

                    if (Usuario::validarEmail($_POST['email']) == true && Usuario::validarTipo($_POST['tipo']) == true) {

                        $usuario = new Usuario($_POST['email'], $_POST['tipo'], $_POST['password']);
                        if ($usuario->saveUserJson($usuario) == true) {
                            echo "Se grabo el usuario";
                        } else {
                            echo "Error al grabar el usuario";
                        }
                    } else {
                        echo " ERROR, REGISTRO CANCELADO";
                    }
                } else {
                    echo "FALTAN DATOS DEL USUARIO";
                }
                break;

            case "/login":
                if (isset($_POST['email']) && isset($_POST['password'])) {

                    if ($usuario = Usuario::obtenerUsuario($_POST['email'], $_POST['password'])) {

                        $payload = array("email" => $usuario->_email, "tipo" => $usuario->_tipo);
                        $token = Token::generarToken($payload);
                        echo "SU TOKEN SE CREO CORRECTAMENTE  \n";
                        var_dump($token);
                        return $token;
                    } else {
                        echo " EMAIL O CLAVE INCORRECTOS";
                        return false;
                    }
                } else {
                    echo "FALTAN DATOS DEL USUARIO";
                }

                break;
            case "/vehiculo":
                if (isset($_POST['patente']) && isset($_POST['marca']) && isset($_POST['modelo']) && isset($_POST['precio'])) {

                    if ($token = Token::decifrarToken($_SERVER['HTTP_TOKEN'])) {

                        $listaAutos = Auto::readAutoJson();
                        $autoIngresado = new Auto($_POST['patente'], $_POST['marca'], $_POST['modelo'], $_POST['precio']);
                        if (Auto::findCar($autoIngresado->_patente) == false) {
                            Auto::saveAutoJson($autoIngresado);
                            echo "Se grabo el auto correctamente";
                        } else {
                            echo "Error al grabar, patente ya ha sido registrada";
                        }
                    } else {
                        echo ("Error de autentificación ");
                    }
                } else {
                    echo "FALTAN DATOS DEL VEHICULO";
                }
                break;

            case "/patente":
                // if (isset($_POST['patente']) && isset($_POST['tipo'])) {

                //     if ($token = Token::decifrarToken($_SERVER['HTTP_TOKEN'])) {
                //         if ($token->tipo == "user") {

                //             $ingreso = new Auto($_POST['patente'], date('d-m-Y h:i A'), $_POST['tipo'], $token->email);
                //             Auto::saveAutoJson($ingreso);
                //             echo ("Se cargo el ingreso correctamente");
                //         } else {
                //             echo ("Usuario no autorizado para esta acción ");
                //         }
                //     } else {
                //         echo ("Error de autentificación ");
                //     }
                // } else {
                //     echo "FALTAN DATOS DE INGRESO";
                // }
                // break;

            default:
                # code...
                break;
        }
        break;
    case 'GET': //LISTA RECURSOS
        switch ($path_info) {
            case "/patente":
                if (isset($_GET['patente'])) {

                    if ($token = Token::decifrarToken($_SERVER['HTTP_TOKEN'])) {

                        if (($auto = Auto::findCarKI($_GET['patente'])) != false) {
                            echo ("DATOS DEL VEHICULO \n\n");
                            echo ("PATENTE: " . $auto->_patente . "\n");
                            echo ("MARCA: " . $auto->_marca . "\n");
                            echo ("MODELO: " . $auto->_modelo . "\n");
                            echo ("PRECIO: " . $auto->_precio . "\n");
                        }
                    } else {
                        echo ("Error de autentificación ");
                    }
                } else {
                    echo "FALTAN DATOS DEL VEHICULO";
                }
                break;
            case "/servicio":
                if (isset($_GET['nombre']) && isset($_GET['id']) && isset($_GET['tipo']) && isset($_GET['precio']) && isset($_GET['demora'])) {

                    if ($token = Token::decifrarToken($_SERVER['HTTP_TOKEN'])) {

                        $newService = new Servicio($_GET['nombre'], $_GET['id'], $_GET['tipo'], $_GET['precio'], $_GET['demora']);
                        Servicio::saveServicioJson($newService);
                        echo "SE INGRESO CORRECTAMENTE EL SERVICIO";
                    } else {
                        echo ("Error de autentificación ");
                    }
                } else {
                    echo "FALTAN DATOS DEL SERVICIO";
                }
                break;
            case "/turno":

                if (isset($_GET['patente'])) {

                    if ($token = Token::decifrarToken($_SERVER['HTTP_TOKEN'])) {

                        if (($auto = Auto::findCarKI($_GET['patente'])) != false) {
                            $fecha = date('d-m-Y');

                            $newTurno= new Turno($auto->_patente,$auto->_marca,$auto->_modelo,$auto->_precio,$fecha);
                            Turno::saveTurnosJson($newTurno);
                            echo ("TURNO AGENDADO CON EXITO!! ");

                        } else {
                            echo ("PATENTE INEXISTENTE!! ");
                        }
                    } else {
                        echo ("Error de autentificación ");
                    }
                } else {
                    echo "FALTAN DATOS DEL TURNO";
                }

                break;

            default:
                # code...
                break;
        }

        break;
    case 'PUT': //MODIFICA RECURSOS
        # code...
        break;
    case 'DELETE': //BORRA RECURSOS
        # code...
        break;

    default:
        # code...
        break;
}
