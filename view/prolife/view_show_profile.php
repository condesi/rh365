<?php session_start();?>
<script type="text/javascript" src="../js/user.js?rev=<?php echo time();?>"></script>

<div class="row">
  <div class="col-md-6">
    <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">
      
      <div style="border-bottom: 1px solid #dee2e6;">
         <h5 class="tile-title"><i class="fa fa-user" style="font-size: 20px"  aria-hidden="true"></i>&nbsp;Actualizar perfil</h5>
        
      </div>
      <div class="modal-body">
        <div class="row invoice-info">

          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label" for="inputDefault"><strong> Nombres</strong></label>
              <input class="form-control form-control-sm" id="name" type="text">
            </div>
            <div class="form-group">
              <label class="col-form-label" for="inputDefault"><strong> Apellidos</strong></label>
              <input class="form-control form-control-sm" id="lastname" type="text">
            </div>
            <div class="form-group">
              <label class="col-form-label" for="inputDefault"><strong> Usuario</strong></label>
              <input class="form-control form-control-sm" id="username" type="text">
            </div>
            <div class="form-group">
              <label for="exampleSelect1"><strong>Rol ususario</strong></label>
              <select class="form-control" id="role_id" style="width:100%;">
                <option value="<?php echo $_SESSION["role_id"]; ?>"><?php echo $_SESSION["namerole"]; ?></option>
                <!-- Aquí puedes agregar más opciones según sea necesario -->
              </select><br><br>
            </div>

          </div>
          <div class="col-lg-6">

           <div class="form-group">
            <label class="col-form-label" for="inputDefault"><strong> Contraseña</strong></label>
            <input class="form-control form-control-sm" id="passwordfirst" type="password"  readonly>
          </div>
          <div class="form-group">
            <label class="col-form-label" for="inputDefault"><strong> Repite contraseña</strong></label>
            <input class="form-control form-control-sm" id="passwordsecond" type="password" readonly >
          </div>
          <div class="form-group">
            <label class="col-form-label" for="inputDefault"><strong>Código(*)</strong></label>
            <input class="form-control form-control-sm" id="code" type="text">
          </div>

          <div class="profile">
           <div id="previewContainer">
             <button class="btn btn-sm text-end text-end" onclick="removeImage(1)" id="removeButton">
              <i class="fa fa-times-circle" aria-hidden="true"></i></button>
              <img id="preview1" src="" alt=" Sin Foto...">
            </div>
            <input class="form-control form-control-sm" type="file" id="photo1" name="photo1" accept="image" onchange="previewImage(this,1)" >

          </div>
        </div>
      </div>

    </div>
     <div class="modal-footer">
        <button class="btn btn-primary btn-sm" id="btnregister" onclick="registerUser(this)"><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i>Actualizar</button>
      </div>
  </div>
</div>
<div class="clearix">
</div>
</div>
<script type="text/javascript">
 $(document).ready(function() {
   hideLoadingOverlay() ;
 try {
      const id_user = "<?php echo $_SESSION['iduser']; ?>";
      openPrefileUser(id_user);
    } catch (error) { alert("No Autorizado!."+error);}
});

</script>