<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

class Usuario{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    // Metodos

    public function getAllUsuarios(){
    $query = "SELECT * FROM usuario";
    $result = mysqli_query($this->conn, $query);
    $usuarios = [];
    while($row = mysqli_fetch_assoc($result)){
        $usuarios[] = $row;
    }
    return $usuarios;
    }
    public function getUsuarioById($id){
        $query = "SELECT * FROM usuario WHERE id = $id";
        $result = mysqli_query($this->conn, $query);
        $usuario = mysqli_fetch_assoc($result);
        return $usuario;
    }
    public function insertarUsuario($data){
        if(!isset($data['usr_name']) || !isset($data['usr_pass']) || !isset($data['usr_email']) || !isset($data['imagen'])){
            http_response_code(400);
			return json_encode(["error" => "Datos incompletos"]);
        }
            $usr_name = $data['usr_name'];
            $usr_pass = password_hash($data['usr_pass'], PASSWORD_DEFAULT);
            $usr_email = $data['usr_email'];
            $imagen = $data['imagen'];
            if (!preg_match('/^data:image\/(\w+);base64,/', $imagen, $type)) {
                http_response_code(400);
                return json_encode(["error" => "Formato de imagen inválido"]);
            }
				$imagen = substr($imagen, strpos($imagen, ',') + 1);
				$imagen = base64_decode($imagen);
				$ext = strtolower($type[1]);
				$img_name = uniqid() . "." . $ext;
				$img_path = __DIR__ . "/uploads/" . $img_name;
				if (!is_dir(__DIR__ . "/uploads/")) {
					mkdir(__DIR__ . "/uploads/", 0777, true);
				}
                if (file_put_contents($img_path, $imagen) === false) {
						http_response_code(500);
						return json_encode(["error" => "No se pudo guardar la imagen"]);
					}
                try{
                    $stmt = $this->conn->prepare("INSERT INTO usuario (usr_name, usr_pass, usr_email, imagen) VALUES (?,?,?,?)");
                    $stmt->bind_param("ssss", $usr_name, $usr_pass, $usr_email, $img_name);
                    $result = $stmt->execute();
                }catch(Exception $e){
                    if (file_exists($img_path)) {
                        unlink($img_path);
                    }
                    http_response_code(500);
                    return json_encode(["error" => "Error al insertar el usuario: " . $e->getMessage()]);
                }
                if($result){
                    http_response_code(201);
					return $result;
					

				} else {
					http_response_code(400);
					return json_encode(["error" => "No se pudo registrar el usuario"]);
				}
			
            
        
    }
    public function loginUsuario($data){
        if(!isset($data['usr_email']) || !isset($data['usr_pass'])){
            http_response_code(400);
            return json_encode(["error" => "Datos incompletos"]);
        }else{
            $usr_email = $data['usr_email'];
            $usr_pass = $data['usr_pass'];
            try{
                $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE usr_email = ?");
                $stmt->bind_param("s", $usr_email);
                $stmt->execute();
                $result = $stmt->get_result();
                $usuario = $result->fetch_assoc();
                if($usuario && password_verify($usr_pass, $usuario['usr_pass'])){
                    
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nombre'] = $usuario['usr_name'];
                    $_SESSION['usuario_email'] = $usuario['usr_email'];
                    $_SESSION['usuario_imagen'] = $usuario['imagen'];
                    $stmt->close();
                    $this->conn->close();
                    http_response_code(200);
                    return json_encode(["success" => "Login exitoso", "usuario" => $usuario]);

                } else {
                    http_response_code(401);
                    return json_encode(["error" => "Credenciales inválidas"]);
                }
            }catch(Exception $e){
                http_response_code(500);
                return json_encode(["error" => "Error al intentar el login: " . $e->getMessage()]);
            }
        }
    }
    public function logoutUsuario(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();
        session_unset();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        http_response_code(200);
        return json_encode(["success" => "Logout exitoso"]);
    }
    public function validarUsuario($data){
        $email = trim($data['usr_email'] ?? '');
        $pwd = $data['usr_pass'] ?? '';
        $nombre = trim($data['usr_name'] ?? '');
        $pwd_confirm = $data['usr_pass_confirm'] ?? '';

        if(!isset($data['usr_email']) || !isset($data['usr_pass']) || !isset($data['usr_name']) || !isset($data['usr_pass_confirm'])){
            http_response_code(400);
            return false;
        }else{
            if (empty($nombre) || empty($email) || empty($pwd) || empty($pwd_confirm)) {
            http_response_code(400);
            return false;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                return false;
            }
            if (strlen($pwd) < 8) {
                http_response_code(400);
                return false;
            }
            if ($pwd !== $pwd_confirm) {
                http_response_code(400);
                return false;
            }
            return true;
        }
    } 
    
}
?>