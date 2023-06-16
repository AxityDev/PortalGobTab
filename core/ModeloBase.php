<?php
class ModeloBase extends EntidadBase{
    private $table;
    
    public function __construct($table, $adapter) {
        $this->table= $table;
        parent::__construct($table, $adapter);
    }
    
    //Aqui podemos montarnos metodos para los modelos de consulta
    
}
?>


