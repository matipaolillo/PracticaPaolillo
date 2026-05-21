<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

class Usuario{
    private $conn;

    public function __constructor($conn){
        $this->conn == $conn;
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
    public function insertUsuario($data){
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
					return json_encode(["success" => "Usuario registrado con éxito"]);
				} else {
					http_response_code(400);
					return json_encode(["error" => "No se pudo registrar el usuario"]);
				}
			
            
        
    }
}
?>