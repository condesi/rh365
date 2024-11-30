

<div class="modal" id="modal_Passwoed">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <div class="icon-container" style="margin-top:5px">
          <i class="fa fa-key" aria-hidden="true" style="font-size: 20px"></i>&nbsp;  
        </div>
        <h5 class="modal-title"> Cambiar Contraseña </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
         <div class="form-horizontal">
                <div class="mb-12 row">
                  <label class="form-label col-md-4" for="inputDefault"><strong>Contraseña Actual (*)</strong></label>
                  <div class="col-md-8 form-group" >
                  
                   <input class="form-control" type="password" id="currentpass"  placeholder="Ingrese Clave Actual" >
                  </div>
                </div>
                 <div class="form-horizontal">
                <div class="mb-12 row">
                  <label class="form-label col-md-4" for="inputDefault"><strong>Contraseña Nueva (*)</strong></label>
                  <div class="col-md-8 form-group" >
                    <input class="form-control" type="password"  id="newpass" placeholder="Clave nueva" >
                  </div>
                </div>
          </div>
           <div class="form-horizontal">
                <div class="mb-12 row">
                  <label class="form-label col-md-4" for="inputDefault"><strong>Vuelve a escribir la contraseña nueva (*)</strong></label>
                  <div class="col-md-8 form-group" >
                    <input class="form-control" type="password"  id="newpasssecond" placeholder="Repita la clave nueva" >
                  </div>
                </div>
          </div>
          </div>
      </div>

      <!-- Modal footer -->
       <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
        <button class="btn btn-primary btn-sm" id="btnregister" onclick="Change_password(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i class="fa fa-check-circle-o" aria-hidden="true"></i> Guardar</button>
      </div>

    </div>
  </div>
</div>
