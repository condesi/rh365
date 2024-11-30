
<script type="text/javascript" src="../js/pointUser.js?rev=<?php echo time();?>"></script>
  <?php include('../components/title_global_wiev.php'); header_wiev("Gestion de puestos");?>
<div class="row" >
 <div class="col-md-6">
  <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">
       <div class="row">
        <div class="col-md-6">
          <i class="fa fa-list" aria-hidden="true"></i>
        </div>
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" class="global_filter form-control" id="global_filter" placeholder="Ingresar dato a buscar" style="border-radius: 5px;">
          </div>
        </div>
      </div>
 

 <table id="tbl_pointUser" class="display responsive nowrap table table-sm " style="width:100%">
  <thead>
    <tr>
      <th>N°</th>
      <th></th>
      <th>Usuarios</th>
      <th>Acci&oacute;n</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </tfoot>
</table>
</div>
</div>

 <div class="col-md-6">
  <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">
       <div class="modal-header">
        <h5 class="modal-title" id="userPoint_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="form-group" id="div_select" style="display: none;">
        <div class="alin_global" style="display: flex;" >
          <select class="form-control" id="checkpoint_id" style="width:100%;" onchange="selectCheckpoint(this)">
          </select>&nbsp;&nbsp;
         
         <a class="btn  btn-secondary btn-sm" style="font-size: 15px;height: 35px;" onclick="keyBoardAction()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
       </div>
     </div>

        <table class="table table-sm table-xs" id="tabadetallesid">
          <thead>
            <tr>
              <th >N°</th>
              <th>Puesto</th>
              <th>Latitud</th>
              <th>Longitud</th>
              <th>Quitar</th>
            </tr>
          </thead>
          <tbody>
           <tbody id="tbody_tabla_pointUser"></tbody>
        </table>

        <div class="modal-footer" id="footer_checkpoint" style="display:none">
        <button type="button" class="btn btn-secondary btn-sm" onclick="clearInputRegister()"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
        <button class="btn btn-primary btn-sm" id="btnregister" onclick="registerUserCheckpoint(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i  class="fa fa-check-circle-o" aria-hidden="true"></i> Guardar</button>
      </div>
</div>
</div>
</div>


<script type="text/javascript">
 $(document).ready(function() {
  hideLoadingOverlay();
  getUsers();
  getCheckpoints();
});
</script>