<div class="modal fade" id="permission_modal" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="icon-container" style="margin-top:5px">
          <i class="fa fa-bed" style="font-size: 20px" aria-hidden="true"></i>&nbsp;  
        </div>
        <h5 class="modal-title" id="tituloModal"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="form-group">
              <label for="room_name"><strong>Nombres</strong></label>
              <input type="text" class="form-control" id="people_name_per" readonly>
            </div>
            <div class="form-group">
              <label for="room_number"><strong>Dirección</strong></label>
              <input type="text" class="form-control" id="address_people" readonly>
            </div>
    
            <div class="row invoice-info">
              <div class="col-6 col-md-6">
                  <label for="">Dias Corespondients</label>
                  <label class="form-control" id="corresponding_days"  readonly style="font-weight: bold;color: black;"></label>
              </div>
              <div class="col-6 col-md-6">
                  <label for="">Cantidad(Day/Hors)</label>
                  <label class="form-control" id="remaining_label"  readonly style="font-weight: bold;color: black;"></label>
              </div>
              <div class="col-6 col-md-6">
                  <label for="">Restantes</label>
                  <label class="form-control" id="used_label"  readonly style="font-weight: bold;color: black;"></label>
              </div>
               <div class="col-6 col-md-6">
                  <label for="">Disponibles</label>
                  <label class="form-control" id="available_label"  readonly style="font-weight: bold;color: black;"></label>
              </div>
          </div>

          </div>
          <div class="col-lg-6">
             <div class="form-group">
              <label for="typePermission"><strong>Tipo Permiso</strong></label>
              <select class="form-control" id="id_typePermission"  onchange="selectTypePermission(this)"></select>
            </div>
            
            
            <div id="fechaDiv" style="display: none;">
            <div class="form-group">
              <label for=""><strong>fecha inicio</strong></label>
              <input type="date" class="form-control" id="start_date" onchange="handleDateChange(this)" >
            </div>
           
            <div class="form-group">
              <label for=""><strong>fecha final</strong></label>
               <input type="date" class="form-control" id="end_date" onchange="handleDateChange(this)" >
            </div>
            </div>

             <div id="horaDiv" style="display: none;">
            <div class="form-group">
              <label for=""><strong>hora inicio</strong></label>
              <input type="time" class="form-control" id="start_time" onchange="handleTimeChange(this)">
            </div>
           
            <div class="form-group">
              <label for=""><strong>hora final</strong></label>
             
              <input type="time" class="form-control" id="end_time" onchange="handleTimeChange(this)" >
            </div>
             </div>

            
        <div class="profile">
          <div id="previewContainer">
            <button class="btn btn-sm text-end text-end" onclick="removeImageRoom(1)" id="removeButton">
              <i class="fa fa-times-circle" aria-hidden="true"></i>
            </button>
            <img id="previewRoom1" src="" alt=" Sin Foto..." >
          </div>
          <input class="form-control form-control-sm" type="file" id="photoRoom1" name="photoRoom1" accept="image" onchange="previewImageRoom(this, 1)">
        </div>
  
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
        <button class="btn btn-primary btn-sm" id="btnregister" onclick="register_permission(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span>
          <i id="icono" class="fa fa-check-circle-o" aria-hidden="true"></i> Guardar</button>
      </div>
    </div>
  </div>
</div>
