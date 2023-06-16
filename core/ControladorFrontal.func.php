<?php

/**
 * Verifica si el controlador pasado como parametro existe y lo devuelve
 *
 * @access public
 * @param string $controller controlador
 * @return controllerObj
 */
function cargarControlador($controller){
    $controlador=ucwords($controller).'Controller';
    $strFileController='controller/'.$controlador.'.php';
    
    if(!is_file($strFileController)){
        $strFileController='controller/'.ucwords(CONTROLADOR_DEFECTO).'Controller.php';   
    }
    
    require_once $strFileController;
    $controllerObj=new $controlador();
    return $controllerObj;
}

/**
 * Carga la funcion pasada (parametro) del controlador (parametro)
 *
 * @access public
 * @param object $controllerObj controlador
 * @param string $controller controlador
 */
function cargarAccion($controllerObj,$action){
    $accion=$action;
    $controllerObj->$accion();
}

/**
 * Verifica si existe el método pasado como parametro
 *
 * @access public
 * @param object $controllerObj controlador
 */
function lanzarAccion($controllerObj){
    if(isset($_GET["action"]) && method_exists($controllerObj, $_GET["action"])){
        cargarAccion($controllerObj, $_GET["action"]);
    }else{
        cargarAccion($controllerObj, ACCION_DEFECTO);
    }
}

?>
