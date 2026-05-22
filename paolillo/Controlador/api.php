<?php
    session_start();
    require_once '../Modelo/conexion.php';
    require_once 'usuario.php';
    require_once 'publicacion.php';

    $usuarioObj = new Usuario($conn);
    $publicacionObj = new Publicacion($conn);

    $method = $_SERVER['REQUEST_METHOD'];
    $endpoint = $_SERVER['PATH_INFO'] ?? '/';

    header('Content-Type: application/json');

    switch($method){
        case 'GET':
            if($endpoint == '/usuarios'){
                $usuarios = $usuarioObj->getAllUsuarios();
                echo json_encode($usuarios);
            }elseif(preg_match('/\/usuarios\/(\d+)/', $endpoint, $matches)){
                $id = $matches[1];
                $usuario = $usuarioObj->getUsuarioById($id);
                if($usuario){
                    echo json_encode($usuario);
                }else{
                    http_response_code(404);
                    echo json_encode(["error" => "Usuario no encontrado"]);
                }
            }elseif($endpoint == '/publicaciones'){
                $publicaciones = $publicacionObj->getAllPublicaciones();
                echo json_encode($publicaciones);
            }
            break;
        case 'POST':
            if($endpoint == '/usuarios'){
                $data = json_decode(file_get_contents('php://input'), true);
                if($usuarioObj->validarUsuario($data) !== true){
                    echo $usuarioObj->validarUsuario($data);
                    exit();
                }
                $result = $usuarioObj->insertarUsuario($data);
                echo $result;
            }elseif($endpoint == '/login'){
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $usuarioObj->loginUsuario($data);
                echo $result;
            }elseif($endpoint == '/logout'){
                $result = $usuarioObj->logoutUsuario();
                echo $result;
            }elseif($endpoint == '/publicar'){
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $publicacionObj->publicar($data);
                echo $result;
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
    }
?>