<?php
class Publicacion {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function publicar($data){
        if(!isset($data['mensaje']) || !isset($data['id_usuario'])){
            http_response_code(400);
            return json_encode(["error" => "Datos incompletos"]);
        }else{
            $mensaje = $data['mensaje'];
            $id_usuario = $data['id_usuario'];
            try{
                $stmt = $this->conn->prepare("INSERT INTO publicaciones (mensaje, id_usuario) VALUES (?, ?)");
                $stmt->bind_param("si", $mensaje, $id_usuario);
                $result = $stmt->execute();
            }catch(Exception $e){
                http_response_code(500);
                return json_encode(["error" => "Error al intentar publicar: " . $e->getMessage()]);
            }
            if($result){
                http_response_code(201);
                return json_encode(["success" => "Publicación creada exitosamente"]);
            } else {
                http_response_code(400);
                return json_encode(["error" => "No se pudo crear la publicación"]);
            }
        }
        public function getAllPublicaciones(){
            $query = "SELECT * FROM publicaciones";
            $result = mysqli_query($this->conn, $query);
            $publicaciones = [];
            while($row = mysqli_fetch_assoc($result)){
                $publicaciones[] = $row;
            }
            return $publicaciones;
                }
            }
?>