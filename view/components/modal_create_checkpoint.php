
<div class="modal fade" id="checkpoint_modal" role="dialog">
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
      <p id="status" style="display: inline;  font-weight: bold;"></p>&nbsp;  
       <button class="btn btn-sm" style="display: inline;" onclick="getLocation()"><i class="fa fa-repeat" ></i></button>

       <section class="invoice">
        <div class="row mb-4">
          <div class="col-6">
            <div class="">
              <label class="col-form-label"><strong> Latitud</strong></label>
              <input class="form-control form-control-sm" id="latitude_checkpoit" type="text" style="font-weight: bold; color: black;font-size: 14px">
            </div>
            <div class="">
              <label class="col-form-label"><strong> Longitud</strong></label>
              <input class="form-control form-control-sm" id="longitude_checkpoit" type="text" style="font-weight: bold; color: black;font-size: 14px">
            </div>
            <div class="">
                <label for="exampleSelect1"><strong> Estado</strong></label>
                <select class="form-control" id="status_checkpoit" style="width:100%;">
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select><br><br>
              </div>
          </div>
          <div class="col-6">
           <div class="">
              <label class="col-form-label"><strong>Nombre</strong></label>
              <input class="form-control form-control-sm" id="name_checkpoit" type="text">
            </div>
               <div class="">
              <label class="col-form-label"><strong>Margen de error </strong></label>
              <input class="form-control form-control-sm" id="haversine_checkpoit" type="text" readonly>
            </div>
            <div class="">
              <label class="col-form-label"><strong>Radio</strong></label>
              <input class="form-control form-control-sm" id="threshold_checkpoit" type="number" style="font-weight: bold; color: black;font-size: 14px">
            </div>
            </div>
         </section>
 
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
        <button class="btn btn-primary btn-sm" id="btnregister" onclick="registerCheckpoint(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i> Guardar</button>
      </div>

    </div>
  </div>
</div>