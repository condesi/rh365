<?php
session_start();
if(!isset($_SESSION['username'])){
  header('Location: ../login/login.php'); 
}
//require_once "../Config/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
  <!-- Twitter meta-->

  <title>HR365 | Home</title>
  <link rel="shortcut icon" href="../images/logo.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <link rel="stylesheet" type="text/css" href="../css/responsive.css">
  <link rel="stylesheet" href="../public/DataTables/datatables.min.css">
  <!-- Font-icon css-->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body class="app sidebar-mini">
    <!-- LOADING-->
    
  <!-- Navbar-->
  <header class="app-header"><a class="app-header__logo" href="#">HR365</a>
    <!-- Sidebar toggle button-->
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <?php include 'menu/app-nav/app-nav.php' ?>
  </header>
  <!-- Sidebar menu-->
  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
  <aside class="app-sidebar">

   <?php include'menu/sidebar/sidebar_App.php' ?>
 </aside>
 <main class="app-content">
   <style>
    .swal2-popup{
      font-size:0.7rem !important;
    }
  </style>

 <div class="overlay_load"  style="text-align: center; display:none;">
    <div class="spinner-border text-primary" style="width: 2rem; height: 2rem;"></div>
    <br />
    Cargando.....
  </div>
  <div id="Contenido_principal">
    <!--<div class="loading-overlay" id="loadingOverlay">
    <img src="../images/loading/ajax-loader.gif" alt="Loading" class="loading-spinner">
    <img src="../images/loading/icon.png" alt="Logo" class="loading-logo">
  </div>-->
  </div>

  
   


  <?php include'menu/fotter/app-fotter.php' ?>
</main>

<!-- Content End -->
<!-- Essential javascripts for application to work-->
<script src="../public/jquery-3.3.1.min.js"></script>
<script src="../public/popper.min.js"></script>
<script src="../public/bootstrap.min.js"></script>
<script src="../public/main.js"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="../public/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script src="../public/DataTables/datatables.min.js"></script>
<script src="../public/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="../js/config.js"></script>
<script src="../js/dashboard.js"></script>
<script src="../js/user.js"></script>

<script type="text/javascript" src="../public/plugins/sweetalert2/sweetalert2.js"></script>


<script type="text/javascript">
  
  $(document).ready(function() {

    /*CARGAR DASHBOARD*/
     <?php if (isAccess(1)) { ?>
        cargar_contenido('Contenido_principal', 'dashboard/view_dashboard.php');  
        <?php } ?>

        getCompanyDetails();
  });

  function cargar_contenido(contenedor,contenido){
    
    showLoadingOverlay();
   $("#"+contenedor).load(contenido);
   detectarTamañoPantalla();
 }
    // Función para detectar el tamaño de la pantalla
    function detectarTamañoPantalla() {
      var anchoPantalla = window.innerWidth;
      var bodyElement = document.querySelector('body');
  // Agregar la clase "sidenav-toggled" en dispositivos móviles
  if (anchoPantalla <= 767) {
    bodyElement.classList.remove('sidenav-toggled');
  } else {
    //bodyElement.classList.add('sidenav-toggled');
  }
}
//agregar la clase "active" al elemento <a> sin interferir con la funcionalidad existente
function toggleActiveClass(elemento) {
  // Obtener todos los elementos relevantes
  var elementos = document.querySelectorAll('.app-menu__item');
  // Eliminar la clase "active" de todos los elementos
  for (var i = 0; i < elementos.length; i++) {
    elementos[i].classList.remove('active');
  }
  // Agregar la clase "active" al elemento actual
  elemento.classList.add('active');
}


async function getCompanyDetails() {
    const companyId = 1;
    try {
        const response = await fetch(`../controller/company/ControllerEditCompany.php?id=${companyId}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        if (response.status) {
            const {data} = await response.json();
            localStorage.setItem("companny", JSON.stringify(data));

            $('#name').val(data?.name || '');
            $('#description').val(data?.description || '');
            $('#address').val(data?.address || '');
            $('#phone').val(data?.phone || '');
            $('#currency').val(data?.currency || '');
            $('#email').val(data?.email || '');
            $('#ruc').val(data?.ruc || '');
            $('#branch').val(data?.branch || '');

            $('#isNormalAccess').val(data?.isNormalAccess || '');
            $('#isGeoLocation').val(data?.isGeoLocation || '');
            $('#isByCheckpoints').val(data?.isByCheckpoints || '').trigger('change');
            

            $('#phatCurrentflag').val(data?.flag || '');
            $('#phatCurrentlogo').val(data?.logo || '');

            const logoSrc = data?.logo ? 'images/' + data?.logo : '../images/defaulphoto.png';
            const flagSrc = data?.flag ? 'images/' + data?.flag : '../images/defaulphoto.png';
            setPreviewImage('logo', 'logoPreview', logoSrc);
            setPreviewImage('flag', 'flagPreview', flagSrc);
           

        } else {
          console.log('Error: La compañía no se encontró.');
        }
       
    } catch (error) {
       Swal.fire("Mensaje de error", error.responseText, "error");
    }
  
}

function setPreviewImage(inputId, previewId, imageUrl) {
    const $input = $(`#${inputId}`);
    const $preview = $(`#${previewId}`);

    // Verifica si hay una imagen cargada
    if ($input.length && imageUrl) {
        $preview.attr('src', imageUrl);
        $preview.css('display', 'block');  // Muestra la vista previa
    } else {
        $preview.attr('src', '');  // Limpia la vista previa
        $preview.css('display', 'none');  // Oculta la vista previa
    }
}

</script>
<!-- Google analytics script-->

</body>
</html>