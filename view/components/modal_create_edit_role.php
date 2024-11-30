<div class="modal fade" id="modal_role" role="dialog">
  <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="icon-container" style="margin-top:5px">
          <i class="fa fa-pencil-square" style="font-size: 20px"  aria-hidden="true"></i>&nbsp;  
        </div>
        <h5 class="modal-title" id="tituloModal"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row invoice-info">

         <div class="col-lg-12">
          <div class="form-group">
            <label class="col-form-label" for="inputDefault"><strong>Nombres</strong></label>
            <input class="" id="idrole" type="text" hidden>
            <input class="form-control form-control-sm" id="namerole" type="text">
          </div>
        </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
        <button class="btn btn-primary btn-sm" id="btnregister" onclick="Register_role()"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Guardar</button>
      </div>

    </div>
  </div>
</div>