<?php
session_start();
if(isset($_SESSION['username'])){
  header('Location: ../view/home.php');
  exit; // Añade esta línea para detener la ejecución del script después de redireccionar
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Login | sistem</title>
  </head>
  <style>
.swal2-popup{
  font-size:0.7rem !important;
}
#namesistem{
  font-size: 12px;
}

#body_fonnd:before {
    content: '';
    position: fixed;
    width: 100vw;
    height: 100vh;
    background-image: url('../images/banner.jpg');
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    -webkit-background-size: cover;
    background-size: cover;
    background-color: #2a5555;
}

.form-row {
    display: flex;
    justify-content: center; /* Centra horizontalmente los elementos */
   
}

.form-group {
    margin-right: 10px; /* Espacio entre los elementos */
}
.label-text {
    display: flex;
    align-items: center;
   
    border-radius: 99em;
    transition: 0.25s ease;
        }
        .selected {
            background-color: #d6d6e5;
        }

</style>
  <body id="body_fonnd">
  
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
       
      </div>
      
      <div class="login-box" style="border-radius: 10px">
    <div class="login-form" autocomplete="false">
        <h3 class="login-head">
        <img src="../images/logo.png" style="max-width: 35%;">
            <p id="namesistem" class="semibold-text mb-2"></p>
        </h3>

        <div class="form-group">
            <div id="msg_login"></div>
        </div>

        <div class="login_content"> <!-- Contenido de login -->
            
             <div class="form-group">
                 <label class="control-label">USERNAME</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span></div>
                     <input class="form-control" type="text" placeholder="Email" id="txt_user"  autocomplete="off">  
              </div>
             </div>

             <div class="form-group">
                 <label class="control-label">PASSWORD</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span></div>
                      <input class="form-control" type="password" placeholder="Password" id="text_paswoed">
                      <div class="input-group-append show-hide-password" style="display: none;">
                      <span class="input-group-text"> <i class="fa fa-eye-slash" aria-hidden="true"></i></span></div>
             </div>
           </div>

            <div class="form-group">
                <div class="utility">
                    <div class="animated-checkbox">
                        <label>
                            <input type="checkbox"><span class="label-text">Mantener sesión</span>
                        </label>
                    </div>
                    <p class="semibold-text mb-2"><a href="#" id="markAttendance"> Marcar </a></p>
                </div>
            </div>
            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block" onclick="verifyUser(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i>Ingresar</button>
            </div>
        </div>

        <div class="attendance_content" style="display: none;"> <!-- Contenido de asistencia oculto por defecto -->
          <p style="font-size: 1.4rem; font-weight: bold;" class="login-head">Maracar asistencia</p>
            
            <div class="form-row">
                <div class="form-group">
                    <p class="login-head" style="font-size: 0.8rem; font-weight: bold;"><i class="fa fa-calendar"></i>&nbsp;<span id="fecha"></span></p>
                </div>
                <div class="form-group">
                    <h4 class="login-head" style="font-size: 0.8rem; font-weight: bold;"><i class="fa fa-clock-o"></i> &nbsp;<span id="hora"></span></h4>
                </div>
            </div>
           

        <div class="animated-radio-button" style="text-align: center;">
            <label>
                <input type="radio"  name="attendance" value="entrada"  checked><span class="label-text selected" style="padding: 0.375em 0.75em 0.375em 0.375em;">Entrada</span>
            </label>
            <label>
                <input type="radio" name="attendance" value="salida" ><span class="label-text" style="padding: 0.375em 0.75em 0.375em 0.375em;" >Salida</span>
            </label>
       </div>

         <div class="form-group">
            <label class="control-label">Ingrese código</label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-key" aria-hidden="true"></i></span></div>
                 <input class="form-control" type="text" placeholder="Ingres codigo" id="code_user" autocomplete="off"> 
          </div>
         </div>

            <div class="form-group">
              <div class="d-flex justify-content-between align-items-center utility">
                <a href="#" id="backToLogin"><i class="fa fa-chevron-left"></i>&nbsp;Ingresar</a>

                <div class="form-check" style="display:none;">
                  <input class="form-check-input" type="checkbox" id="checkbox_maps">
                  <label class="form-check-label" for="checkbox_maps">
                    <i class="fa fa-street-view" aria-hidden="true"></i>&nbsp;maps
                  </label>
                </div>

              </div>
            </div>


            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block" onclick="attendanceTracker(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i class="fa fa-sign-out"></i>Rgistrar</button>
            </div>
           
        </div>

    </div>
</div>
    </section>


<div class="modal fade" id="modal_attendance" role="dialog">
   <div class="modal-dialog modal-sm" >
    <div class="modal-content">
      <div class="modal-body" >

         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <!-- Imagen centrada y circular -->
        <div style="text-align: center;">
          <img src="" id="phot_user_attendance" style="width: 50px; height: 50px; border-radius: 50%;" alt="Imagen">
        </div>
        <!-- Nombre en negrita -->
       <div style="line-height: 1;">
    <p style="text-align: center; font-weight: bold;" id="data_peope"></p>
    <p style="text-align: center;" id="date_register"></p>
    <p id="action_attendance" style="text-align: center; font-weight: bold; font-size: small;"></p> 
</div>

        
      </div>
    </div>
  </div>
</div>



    <!-- Essential javascripts for application to work-->
    <script src="../public/jquery-3.3.1.min.js"></script>
    <script src="../public/popper.min.js"></script>
    <script src="../public/bootstrap.min.js"></script>
    <script src="../public/main.js"></script>
        <script type="text/javascript" src="../public/plugins/sweetalert2/sweetalert2.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="../public/plugins/pace.min.js"></script>
    <script src="../js/config.js"></script>
    <script src="../js/traker.js"></script>
    <script src="../js/user.js"></script>

<script>
    $(document).ready(function(){
        let compannyName='Sistema de Gestión de Recursos Humanos';
        $('#markAttendance').click(function(e){
            e.preventDefault();
            $('.login_content').hide();
            $('.attendance_content').show();
            $("#msg_login").html('');
             $("#code_user").val('');
        });

        $('#backToLogin').click(function(e){
            e.preventDefault();
            $('.attendance_content').hide();
            $('.login_content').show();
            $("#msg_login").html('');
           
        });

     let companny = localStorage.getItem("companny");
        companny = JSON.parse(companny);
        if (companny && (companny.isGeoLocation || companny.isByCheckpoints)) {
            $(".form-check").show();
          } else {
          
            $(".form-check").hide();
          }

         compannyName = companny.name ? companny.name : compannyName;
         $("#namesistem").text(compannyName);

       });
   
$('input[type="radio"]').change(function() {
$('.label-text').removeClass('selected'); 
 $(this).next().addClass('selected'); 
});

setInterval(mostrarHora, 1000);

</script>
  
  </body>
</html>