<!-- Modal para categorías -->
<div class="modal fade" id="modal_category_permiss" role="dialog">
  <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="icon-container" style="margin-top: 5px">
          <i class="fa fa-pencil-square" style="font-size: 20px" aria-hidden="true"></i>&nbsp;
        </div>
        <h5 class="modal-title" id="tituloModalCategory"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row invoice-info">
          <div class="col-lg-12">
            <div class="form-group">
              <label class="col-form-label" for="inputDefault"><strong>Nombre</strong></label>
             
              <input class="form-control form-control-sm" id="name_category" type="text">
               <span id="nameErrorCategory" class="text-danger"></span>
            </div>
          </div>
          <div class="col-lg-12">
          <div class="form-group">
              <label for="exampleSelect1"><strong> Dias</strong></label>
              <input class="form-control form-control-sm" id="day_permission" type="number">
               <span id="nameErrorDay" class="text-danger"></span>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="form-group">
              <label class="col-form-label" for="inputDefault"><strong>Descripción</strong></label>
              <textarea class="form-control form-control-sm" id="description_permiss"></textarea>
            </div>
          </div>
           <div class="col-lg-12">
          <div class="form-group">
              <label for="exampleSelect1"><strong> Estado</strong></label>
              <select class="form-control" id="status_permiss" style="width:100%;">
                <option value="1" selected>Activo</option>
                <option value="0">Inactivo</option>
              </select><br><br>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
        <button class="btn btn-primary btn-sm" id="btnregister_category" onclick="Register_type(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i id="icono" class="fa fa-check-circle-o" aria-hidden="true"></i> Guardar</button>
      </div>
    </div>
  </div>
</div>
