<?php

/**
 * ModeloLectura clase
 *
 * @author Pedro T Hernandez <pedrothdc@pixelariumstudio.es>
 */
class ModeloLectura {

    private $bd = null;
    private $tabla = "Lecturas";

    function __construct($bd) {
        $this->bd = $bd;
    }

    function add(Lectura $lectura) {
        $sql = "INSERT INTO $this->tabla VALUES (null, :contadori, :contadorf, :eficiencia, CURDATE(),:sector )";
        $param['contadori'] = $lectura->getContadorI();
        $param['contadorf'] = $lectura->getContadorF();
        $param['eficiencia'] = $lectura->getEficiencia();
        $param['sector'] = $lectura->getSector();

        $r = $this->bd->setConsulta($sql, $param);
        if (!$r) {
            return -1;
        }
        return $this->bd->getAutonumerico();
    }

    function delete($id) {
        $sql = "DELETE FROM $this->tabla WHERE id=:id";
        $param['id'] = $id;
        $r = $this->bd->setConsulta($sql, $param);
        if (!$r) {
            return -1;
        }
        return $this->bd->getNumeroFilas();
    }

    function edit(Lectura $objeto) {
        $asignacion = "nombre=:nombrecategoria";
        $condicion = "id=:id";
        $parametros["nombrecategoria"] = $objeto->getNombrecategoria();
        $parametros["id"] = $objeto->getId();
        return $this->editConsulta($asignacion, $condicion, $parametros);
    }

    function get($id) {
        $sql = "SELECT * FROM $this->tabla where id=:id";
        $param['id'] = $id;
        $r = $this->bd->setConsulta($sql, $param);
        if ($r) {
            $lectura = new Lectura();
            $lectura->set($this->bd->getFila());
            return $lectura;
        }
        return null;
    }

    function getJSON($id) {
        return $this->get($id)->getJSON();
    }

    function getListJSON($pagina = 0, $rpp = 3, $condicion = "1=1", $parametros = array(), $orderby = "1") {
        $pos = $pagina * $rpp;
        $sql = "select * from "
                . $this->tabla .
                " where $condicion order by $orderby limit $pos, $rpp";
        $this->bd->setConsulta($sql, $parametros);
        $r = "[ ";
        while ($fila = $this->bd->getFila()) {
            $objeto = new Lectura();
            $objeto->set($fila);
            $r .= $objeto->getJSON() . ",";
        }
        $r = substr($r, 0, -1) . "]";
        return $r;
    }

    function getListJSON2($condicion = "1=1", $parametros = array(), $orderby = "1") {

        $sql = "select * from "
                . $this->tabla .
                " where $condicion order by $orderby";
        $this->bd->setConsulta($sql, $parametros);
        $r = "[ ";
        while ($fila = $this->bd->getFila()) {
            $objeto = new Lectura();
            $objeto->set($fila);
            $r .= $objeto->getJSON() . ",";
        }
        $r = substr($r, 0, -1) . "]";
        return $r;
    }
    
    function deleteID($id) {
        $sql = "delete from $this->tabla where id = :id";
        $parametros["id"] = $id;
        $r = $this->bd->setConsulta($sql, $parametros);
        if (!$r) {
            return -1;
        }
        return $this->bd->getNumeroFilas();
    }

    function getList($principio = 0, $rpp = 5, $condicion = "1=1", $orderby = 1, $parametros = array()) {
        $list = array();
        $sql = "select * from $this->tabla WHERE $condicion order by $orderby limit $principio, $rpp";
        $r = $this->bd->setConsulta($sql, $parametros);
        if (!$r) {
            return null;
        } else {
            while ($fila = $this->bd->getFila()) {
                $objeto = new Lectura();
                $objeto->set($fila);
                $list[] = $objeto;
            }
            return $list;
        }
    }

    function count($condicion = "1=1", $parametros = array()) {
        $sql = "select count(*) from $this->tabla where $condicion";
        $r = $this->bd->setConsulta($sql, $parametros);
        if ($r) {
            $f = $this->bd->getFila();
            return $f[0];
        }
        return -1;
    }

}
