<?php

require_once __DIR__ . '/../config/database.php';


$perfilUsuario = $_SESSION['rol']; // "admin", "usuario", "Supervisor"


// Obtener el ID del perfil del usuario
$sqlPerfil = "SELECT id FROM perfiles WHERE nombre = '$perfilUsuario'";
$stmtPerfil = $conn->prepare($sqlPerfil);

$stmtPerfil->execute();
$resultPerfil = $stmtPerfil->get_result();
$perfilId = $resultPerfil->fetch_assoc()['id'];

$sqlMenu="";
$stmtMenu="";
$resultMenu="";

$sqlMenu="select m.* from menu m, menu_perfil mp
WHERE mp.perfil_id=$perfilId 
AND mp.menu_id = m.id                 
AND m.parent_id ='0'
order by m.orden asc"; 



$stmtMenu = $conn->prepare($sqlMenu);
$stmtMenu->execute();
$resultMenu = $stmtMenu->get_result();
$menus = [];

while ($row = $resultMenu->fetch_assoc()) {
    $menus[] = $row;
}


$sqlSubMenu="";
$stmtSubMenu="";
$resultSubMenu="";

$sqlSubMenu="select m.* from menu m, menu_perfil mp
WHERE mp.perfil_id=$perfilId 
AND mp.menu_id = m.id                 
AND m.parent_id !=0
order by m.orden asc";

$stmtSubMenu = $conn->prepare($sqlSubMenu);
$stmtSubMenu->execute();
$resultSubMenu = $stmtSubMenu->get_result();
$submenus = [];

while ($row = $resultSubMenu->fetch_assoc()) {
    $submenus[] = $row;
}

/* echo ("el ID del Perfil: ".$perfilId);
echo "<br>";
echo "<pre>"; print_r($menus); echo "</pre>"; echo "<br>";
echo "<pre>"; print_r($submenus); echo "</pre>";
exit();   */


// Estructurar submenús por parent_id
$menuTree = [];

foreach ($menus as $menu) {
    $menuTree[$menu['id']] = $menu;
    $menuTree[$menu['id']]['submenus'] = [];
}

// Asignar submenús a sus respectivos padres
foreach ($submenus as $submenu) {
    if (isset($menuTree[$submenu['parent_id']])) {
        $menuTree[$submenu['parent_id']]['submenus'][] = $submenu;
    }
}


?>
