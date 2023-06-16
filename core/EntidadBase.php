<?php
class EntidadBase
{
    private $db;
    private $table;
    private $id;
    private $campo_estado;

    public function __construct($table, $id, $campo_estado = NULL)
    {
        try {
            require_once 'Conectar.php';
            $this->table = (string) $table;
            $this->id = (string) $id;
            $this->campo_estado = (string) $campo_estado;
            $this->db = Conectar::conexion();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    protected function db()
    {
        return $this->db;
    }

    public function redirect($controlador = CONTROLADOR_DEFECTO, $accion = ACCION_DEFECTO, $parametros = NULL)
    {
        if ($parametros == NULL) {
            header("Location:index.php?controller=" . $controlador . "&action=" . $accion);
        } else {
            $strParam = "";
            for ($i = 0; $i < count($parametros); $i++) {
                $strParam = $strParam . "&" . $parametros[$i][0] . "=" . $parametros[$i][1];
            }
            header("Location:index.php?controller=" . $controlador . "&action=" . $accion . $strParam);
        }
    }

    public function getAll()
    {

        try {

            $stm = $this->db->prepare("SELECT * FROM $this->table");
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

    public function getForCombox()
    {

        try {

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE $this->campo_estado = 1");
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

    public function getAllOrderBy($column)
    {
        try {

            $stm = $this->db->prepare("SELECT * FROM $this->table ORDER BY $column ASC");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $resultSet[] = $r;
            }

            return $resultSet;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function getById($id)
    {
        try {
            $query = $this->db->query("SELECT * FROM $this->table WHERE $this->id = $id");

            $resultSet = false;
            if ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $resultSet = $row;
            }

            return $resultSet;
        } catch (Exception $e) {
            die($e->getMessage());
            return $result['estado'] = "error";
        }
    }

    public function getBy($column, $value)
    {
        if($this->campo_estado != NULL){
            $query = $this->db->query("SELECT * FROM $this->table WHERE $column = '$value' AND $this->campo_estado = 1");
        }else{
            $query = $this->db->query("SELECT * FROM $this->table WHERE $column = '$value'");
        }

        $resultSet = false;
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $resultSet[] = $row;
        }

        return $resultSet;
    }

    public function deleteById($id)
    {
        try {
            $query = $this->db->query("DELETE FROM $this->table WHERE $this->id = $id");
            return $query;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function deleteBy($column, $value)
    {
        $query = $this->db->query("DELETE FROM $this->table WHERE $column='$value'");
        return $query;
    }

    public function activateDeactivate($id, $est)
    {
        try {
            $stm = $this->db->prepare("UPDATE $this->table SET $this->campo_estado = $est WHERE $this->id = $id");
            $stm->execute();
            return $stm;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /*
     * Aqui podemos montarnos un monton de métodos que nos ayuden
     * a hacer operaciones con la base de datos de la entidad
     */
}
