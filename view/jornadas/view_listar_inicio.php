<script type="text/javascript" src="../js/jornada.js?rev=<?php echo time();?>"></script>
   <div class="row">
        <div class="col-md-12">
          <div class="tile" style="border-radius: 5px;padding: 10px;">
           <div class="tile-body">
              <ul class="nav nav-pills flex-column mail-nav">
                <li class="nav-item active">
                  <i class="fa fa-home fa-lg"></i> / Jornadas
                  
              
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
       <h5 class="" style="text-align: center;"><strong>Fecha de inicio de jornada - <?php echo date("Y"); ?></strong></h5>
     </div>
     <div class="col-sm-2">

       <div class="form-group">
         
        
        <select class="form-control" id="typeShift" >
         <option value="">----Turno----</option>
         <option value="Normal">Normal</option>
         <option value="Perzonalizado">Perzonalizado</option>
         <option value="Nocturno">Nocturno</option>
         <option value="Mañana">Mañana</option>
       </select>
    
      </div>
     </div>
     <div class="col-sm-2">
      
       <div class="form-group">
          <input class="global_filter form-control" id="fechaInicio" type="date" style="border-radius: 5px;"  min=<?php date_default_timezone_set('America/Lima'); $hoy= date("Y-m-d"); echo $hoy;?> >
      </div>
     </div>
     <div class="col-sm-4">
     <div class="input-group">
        <input type="text" class="global_filter form-control" id="global_filter" placeholder="Ingresar dato a buscar"  style="border-radius: 5px;">
        <span class="input-group-addon"></span>
      </div>
   </div>
 </div>

 <table id="tabla_jornadas" class="display responsive nowrap table table-sm" style="width:100%">
  <thead>
    <tr>
      <th>N°</th>
      <th>Id</th>
      <th>Nombre y Apellido</th>
      <th>Dni(Cédula)</th>
      <th>Entrevista</th>
      <th>Resul. entrevista</th>
      <th>Acci&oacute;n</th>
 
    </tr>
  </thead>
  <tfoot>
    <tr>

      <th></th>
      <th></th>
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
            <button  class="btn btn-primary btn-sm " type="button" onclick="Registrar_Jornada()"><em class="fa fa-fw fa-lg fa-check-circle"></em>Registrar</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn-sm " href="#"><em class="fa fa-fw fa-lg fa-times-circle"></em>Cancel</a>
          </div>
        </div>
      </div>

</div>
</div>
</div>

<script type="text/javascript">

 $(document).ready(function() {
ListarPersonasOff();
 hideLoadingOverlay() ;
 
    });
    </script>