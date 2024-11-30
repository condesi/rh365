
<script type="text/javascript" src="../js/user.js?rev=<?php echo time();?>"></script>
<?php include('../components/title_global_wiev.php'); header_wiev("Usuarios");?>

<div class="row">
  <div class="col-md-6">
    <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">
      <div style="border-bottom: 1px solid #dee2e6;">
         <h5 class="tile-title"><i class="fa fa-key" style="font-size: 20px"  aria-hidden="true"></i>&nbsp;Cambio de contraseña</h5>
        
      </div>

       <!-- Modal body -->
      <div class="modal-body">
         <div class="form-horizontal">
                <div class="mb-12 row">
                  <label class="form-label col-md-4" for="inputDefault"><strong>Contraseña Actual (*)</strong></label>
                  <div class="col-md-8 form-group" >
                  
                   <input class="form-control" type="password" id="current_pass"  placeholder="Ingrese Clave Actual" >
                  </div>
                </div>
                 <div class="form-horizontal">
                <div class="mb-12 row">
                  <label class="form-label col-md-4" for="inputDefault"><strong>Contraseña Nueva (*)</strong></label>
                  <div class="col-md-8 form-group" >
                    <input class="form-control" type="password"  id="new_pass" placeholder="Clave nueva" >
                  </div>
                </div>
          </div>
           <div class="form-horizontal">
                <div class="mb-12 row">
                  <label class="form-label col-md-4" for="inputDefault"><strong>Vuelve a escribir la contraseña nueva (*)</strong></label>
                  <div class="col-md-8 form-group" >
                    <input class="form-control" type="password"  id="newpassse_cond" placeholder="Repita la clave nueva" >
                  </div>
                </div>
          </div>
          </div>
      </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-sm" id="btnregister" onclick="Change_password(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i>Actualizar</button>
      </div>
  </div>
</div>
<div class="clearix">
</div>
</div>
<script type="text/javascript">
 $(document).ready(function() {
   hideLoadingOverlay() ;
 })

</script>