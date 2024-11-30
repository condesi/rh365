
<script type="text/javascript" src="../js/asistencia.js?rev=<?php echo time();?>"></script>
   <div class="row">
        <div class="col-md-12">
          <div class="tile" style="border-radius: 5px;padding: 10px;">
           <div class="tile-body">
              <ul class="nav nav-pills flex-column mail-nav">
                <li class="nav-item active">
                  <i class="fa fa-home fa-lg"></i> /  <label class="" style="text-align: center;"><strong>Bienbenivo Asistencias - <?php echo date("Y"); ?></strong></label>
                  
              
                </li>
              </ul>
            </div>
           
          </div>
        </div>
      </div>

<div class="row" >
 <div class="col-md-12">
  <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">

    <div class="row invoice-info">

<div class="col-sm-4">
    <div class="alin_global"  style="display: flex;">
        <div class="toggle lg">
            <label style="display: inline-block;"><strong>Nuevo/Editar</strong>&nbsp;
            <input type="checkbox" onclick="All_Editar_Nuevo(this)" class="cheboktem" >
            <span class="button-indecator" style="display: inline-block; "></span></label>
        </div>
    </div>
</div>


     <div class="col-sm-4">
       <div class="form-group">
        <div class="alin_global" style="display: flex;" >
          <input class="global_filter form-control" id="fechaasistencia" type="date" style="border-radius: 5px;"  min=<?php date_default_timezone_set('America/Lima'); $hoy= date("Y-m-d"); echo $hoy;?> >&nbsp;&nbsp;<button onclick="Listar_Personal_Asistencia_edit();" class=" btn btn-primary btn-sm"  id="btn_bucar_data" style="display: none"> <em class="fa fa-search" ></em></button>
          </div>
      </div>
     </div>
     <div class="col-sm-4">
     <div class="input-group">
        <input type="text" class="global_filter form-control" id="global_filter" placeholder="Ingresar dato a buscar"  style="border-radius: 5px;">
        <span class="input-group-addon"></span>
      </div>
   </div>
 </div>

 <table id="tabla_persona_asistencia" class="display responsive nowrap table table-sm" style="width:100%">
  <thead>
    <tr>
      <th>N°</th>
      <th>Id</th>
      <th>Nombre y Apellido</th>
      <th>Dni(Cédula)</th>
     
      <th>
        <div class="toggle">
          <label>
            <input type="checkbox" id="check-todo" class="checkbox-general">
            <span class="button-indecator"></span>
          </label>
        </div>
      </th>
    </tr>
  </thead>
  <tfoot>
    <tr>
     <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      
      
    </tr>
  </tfoot>
</table>
  <div class="tile-footer">
        <div class="row">
          <div class="col-md-8 col-md-offset-3">
            <button  class="btn btn-primary btn-sm " type="button" onclick="Registrar_Asistencia()"><em class="fa fa-fw fa-lg fa-check-circle"></em>Registrar</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn-sm " onclick="resetearCheckboxes()"><em class="fa fa-fw fa-lg fa-times-circle"></em>Cancel</a>
          </div>
        </div>
      </div>

</div>
</div>
</div>


<script type="text/javascript">

 $(document).ready(function() {
    hideLoadingOverlay() ;
   ListarPersonasAsistencia();
  });
</script>




