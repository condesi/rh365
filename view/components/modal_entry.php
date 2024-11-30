<div class="modal fade" id="entry_modal" role="dialog">
  <div class="modal-dialog modal-sm">
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
            <label class="col-form-label" ><strong>Ingrese código</strong></label>
       
            <input class="form-control form-control-sm" id="codeUser" type="text" placeholder="Ingrese código">
          </div>
        </div>
        </div>
      </div>
      <div class="modal-footer">
       
        <button class="btn btn-primary btn-sm btn-lg btn-block" id="btnregister" onclick="Register_Asistencia(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i>Ingresar</button>
      </div>

    </div>
  </div>
</div>