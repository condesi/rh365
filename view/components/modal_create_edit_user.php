
<div class="modal fade" id="user_modal" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div class="icon-container" style="margin-top:5px">
          <i class="fa fa-pencil-square-o" style="font-size: 20px"  aria-hidden="true"></i>&nbsp;  
        </div>
        <h5 class="modal-title" id="tituloModal"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row invoice-info">

          <div class="col-lg-12" id="conten_inclid_proples">
            <div class="form-group">
              <input type="checkbox" id="toggleSelect" onchange="toggleSelectVisibility()">
              <label for="toggleSelect">Incluir Personas existentes?</label>
              <select class="form-control form-control-sm" id="people_id" style="width:100%;display: none;" onchange="selecPeopleOnChange(this)">
              </select>
            </div>
          </div>


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
            <label for="exampleSelect1"><strong> Rol ususario </strong></label>
              
              <select class="form-control" id="role_id" style="width:100%;">
                    </select><br><br>
            </div>

        </div>
        <div class="col-lg-6">
          
             <div class="form-group">
            <label class="col-form-label" for="inputDefault"><strong> Contraseña</strong></label>
            <input class="form-control form-control-sm" id="passwordfirst" type="password">
          </div>
          <div class="form-group">
            <label class="col-form-label" for="inputDefault"><strong> Repite contraseña</strong></label>
            <input class="form-control form-control-sm" id="passwordsecond" type="password">
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
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
        <button class="btn btn-primary btn-sm" id="btnregister" onclick="registerUser(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i> Guardar</button>
      </div>

    </div>
  </div>
</div>