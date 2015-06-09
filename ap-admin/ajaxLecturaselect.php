<?php
header('Content-Type: application/json');

$pagina = $_GET["pagina"];

if ($pagina == null) {
$pagina = 0;
}
require_once '../require/comun.php';
$bd = new BaseDatos();
$modelo = new ModeloLectura($bd);
$enlaces = Paginacion::getEnlacesPaginacionJSON($pagina, $modelo->count(), Configuracion::RPP);
echo "{\n";
echo '"paginas":'.json_encode($enlaces).',';
 
echo '"lecturas":'.$modelo->getListJSON($pagina, Configuracion::RPP).'}';
