<?php
static $count = 0;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
    destroysession();
} 

function destroysession(){
 session_destroy();

}

function isAccess($permiso=null) {
global $count;
    if (empty($_SESSION['access'])) {
        echo "<li style='background:#000000'> <a class='app-menu__item'>
             <i class='app-menu__icon fa fa-lock'></i> <span class='app-menu__label'>No Autorizado</span>
            </a>
           </li>";
        return false;
    }
   return in_array($permiso, array_column($_SESSION['access'], 'id_sidebar'));
  
}




function Item($permiso){
	global $count;

    if ($count < count($_SESSION['access'])) {
        $idsidebar = $_SESSION['access'][$count]['id_sidebar'];
        $count++;
        return in_array($idsidebar, array_column($_SESSION['access'], 'id_sidebar'));
    }

    return false; // Evita intentar acceder a índices fuera de los límites del array
}

?>