<?php
class GrupoTablero extends EntidadBase
{
    private $id_gt;
    private $nombre_grupo;
    private $estado_grupo;
    private $imagen_grupo;

    public function __construct()
    {
        $table = 'grupos_tableros';
        $id = 'id_gt';
        $campo_estado = 'estado_grupo';
        parent::__construct($table, $id, $campo_estado);
    }

    /*-----------------------------GETTERS & SETTERS-----------------------------*/

    public function __GET($k)
    {
        return $this->$k;
    }

    public function __SET($k, $v)
    {
        $this->$k = $v;
    }

    public function getAll()
    {
        try {
            $query = "SELECT * FROM grupos_tableros";

            $stmt = $this->db()->prepare($query);
            $stmt->execute();

            $result = [];

            foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
                $imagenURL = "assets/img/".$r->nombre_grupo.".png";
                $r->imagen_grupo = (file_exists($imagenURL)) ? $r->nombre_grupo : "No";
                $result[] = $r;
            }

            return $result;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function getForCombox()
    {

        try {

            $stm = $this->db()->prepare("SELECT * FROM grupos_tableros WHERE estado_grupo = 1 ORDER BY nombre_grupo ASC");
            $stm->execute();

            $resultSet = [];
            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $resultSet[] = $r;
            }

            return $resultSet;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function traerGruposTableros($email)
    {
        try {
            $query = "SELECT t1.*, t2.nombre_grupo 
                        FROM usuarios_has_grupos_tableros t1 
                        LEFT JOIN grupos_tableros t2 
                        ON (t1.grupos_tableros_id_gt = t2.id_gt)
                        LEFT JOIN usuarios t3 
                        ON (t1.usuarios_id_usuario = t3.id_usuario) 
                        WHERE t3.email_usuario = ? 
                        AND t2.estado_grupo = 1 
                        ORDER BY t2.nombre_grupo";

            $stmt = $this->db()->prepare($query);
            $stmt->bindParam(1, $email, PDO::PARAM_STR);
            $stmt->execute();

            $result = [];

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $result[] = $r;
            }

            return $result;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
