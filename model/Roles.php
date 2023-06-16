<?php
class Rol extends EntidadBase
{
    private $id_rol;
    private $nombre_rol;

    public function __construct()
    {
        $table = 'roles';
        $id = 'id_rol';
        // $campo_estado = 'estado_rol';
        parent::__construct($table, $id);
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

    public function getAllJoin()
    {
        try {
            $query = "SELECT t1.id_usuario, t1.email_usuario, t2.nombre_rol 
                        FROM usuarios t1 
                        LEFT JOIN roles t2 
                        ON (t1.fk_id_rol = t2.id_rol)";

            $stmt = $this->db()->prepare($query);
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
