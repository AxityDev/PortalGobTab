<?php
class Tablero extends EntidadBase
{
    private $id_tablero;
    private $nombre_tablero;
    private $cliente_tablero;
    private $pais_tablero;
    private $linea_tablero;
    private $titulo_tablero;
    private $url_tablero;
    private $fk_id_gt;

    public function __construct()
    {
        $table = 'tableros';
        $id = 'id_tablero';
        $campo_estado = 'estado_tablero';
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
            $query = "SELECT t1.*, 
                             t2.nombre_grupo
                        FROM tableros AS t1 
                        LEFT JOIN grupos_tableros AS t2 
                        ON (t1.fk_id_gt = t2.id_gt) where estado_tablero='1'
                        ORDER BY nombre_tablero";

            $stmt = $this->db()->prepare($query);
            $stmt->execute();

            $result = [];

            foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
                $imagenURL = "assets/img/" . $r->nombre_tablero . ".png";
                $r->imagen_tablero = (file_exists($imagenURL)) ? $r->nombre_tablero : "No";
                $result[] = $r;
            }

            return $result;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function getAllForTab($fk_id_gt,$email)
    {
        try {
            $query = "SELECT t1.*, t2.email_usuario FROM tableros AS t1 LEFT JOIN accesos_tableros AS t2 ON (t1.id_tablero = t2.fk_id_tablero) WHERE fk_id_gt= ? and email_usuario= ? and estado_tablero='1' ORDER BY nombre_tablero";

            $stmt = $this->db()->prepare($query);
            $stmt->bindParam(1, $fk_id_gt, PDO::PARAM_STR);
            $stmt->bindParam(2, $email, PDO::PARAM_STR);
            $stmt->execute();

            $result = [];

            foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
                $imagenURL = "assets/img/" . $r->nombre_tablero . ".png";
                $r->imagen_tablero = (file_exists($imagenURL)) ? $r->nombre_tablero : "No";
                $result[] = $r;
            }

            return $result;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    
    public function getById($id)
    {
        try {
            $query = "SELECT * FROM tableros WHERE id_tablero = ?";

            $stmt = $this->db()->prepare($query);
            $stmt->bindParam(1, $id, PDO::PARAM_STR);
            $stmt->execute();

            $resultSet = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $resultSet;
        } catch (Exception $e) {
            die($e->getMessage());
            return $result['estado'] = "error";
        }
    }

    public function getAllByGroup($fk_id_gt)
    {
        try {
            $usuario = new Usuario();

            $query = "SELECT * FROM tableros WHERE fk_id_gt = ? AND estado_tablero = 1";

            $stmt = $this->db()->prepare($query);
            $stmt->bindParam(1, $fk_id_gt, PDO::PARAM_STR);
            $stmt->execute();

            $result = [];

            foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r) {
                
                //if ($valorIdTab == $r->id_tablero){
                //    $imagenURL = "assets/img/" . $r->nombre_tablero . ".png";
                //    $r->imagen_tablero = (file_exists($imagenURL)) ? $r->nombre_tablero : "No";
                //    $result[] = $r;
                //}
                
                    $imagenURL = "assets/img/" . $r->nombre_tablero . ".png";
                    $r->imagen_tablero = (file_exists($imagenURL)) ? $r->nombre_tablero : "No";
                    $result[] = $r;
                
                //$result = "error";
                
            }

            return $result;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }






    public function actualizarTablero($form2_id_tablero, $form2_fecha_publicacion, $form2_desc_tablero, $form2_comercial_responsable, $form2_lider_responsable, $form2_nombre_pila_cliente, $form2_actualizacion_automatica)
    {
        try {
            $query = "UPDATE tableros SET
                        fecha_publicación = ?,
                        desc_tablero = ?,
                        comercial_responsable = ?,
                        lider_responsable = ?,
                        nombre_pila_cliente = ?,
                        actualizacion_automatica = ?
                        WHERE id_tablero = ?";

            $stmt = $this->db()->prepare($query);
            $stmt->bindParam(1, $form2_fecha_publicacion, PDO::PARAM_STR);
            $stmt->bindParam(2, $form2_desc_tablero, PDO::PARAM_STR);
            $stmt->bindParam(3, $form2_comercial_responsable, PDO::PARAM_STR);
            $stmt->bindParam(4, $form2_lider_responsable, PDO::PARAM_STR);
            $stmt->bindParam(5, $form2_nombre_pila_cliente, PDO::PARAM_STR);
            $stmt->bindParam(6, $form2_actualizacion_automatica, PDO::PARAM_INT);
            $stmt->bindParam(7, $form2_id_tablero, PDO::PARAM_STR);
            $result = $stmt->execute();

            $resp['estado'] = "exito";

            if (!$result) {
                $resp['estado'] = "falla";
            }
            return $resp;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
