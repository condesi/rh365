
<?php 
date_default_timezone_set('America/Lima');
        $dateCurrent = new DateTime('now');
        $dayNumber = $dateCurrent->format('N');
        $dateOnly = $dateCurrent->format('Y-m-d');
        
 ?>
<style type="text/css">
    #map {
    height: 500px;
    width: 100%;
}
</style>
<script type="text/javascript" src="../js/traker.js?rev=<?php echo time();?>"></script>
 <div class="row">
        <div class="col-md-12">
          <div class="tile" style="border-radius: 5px;padding: 10px;">
           <div class="tile-body">
              <ul class="nav nav-pills flex-column mail-nav">
                <li class="nav-item active">
                  <i class="fa fa-home fa-lg"></i> / Reportes Reporte asistencias
                </li>
              </ul>
            </div>
           
          </div>
        </div>
      </div>
<div class="row">
 <div class="col-md-12">
  <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">
      
    <div class="row invoice-info">
      <div class="col-sm-3">
       <div class="clasbtn_exportar">
        <div class="input-group" id="btn-place"></div>
      </div>
     </div>
     <div class="col-sm-3">
      <div class="form-group">
       <input class="form-control form-control" type="Date" id="start_date" value="<?php echo $dateOnly; ?>"  >
      </div>
      
     </div>
    <div class="col-sm-3" style="display: flex;">
    <input class="form-control form-control" type="Date" id="end_date" value="<?php echo $dateOnly; ?>">&nbsp;
    <br><button onclick="getTrakers(this);" class="btn btn-primary btn-sm" type="button" style="font-size: 13px;height: 35px;margin-top: 1px;border-radius: 5px"><i class="fa fa-search"></i></button>
   </div>

   <div class="col-sm-3">
      <div class="input-group">
        <input type="text" class="global_filter form-control" id="global_filter" placeholder="Ingresar dato a buscar"  style="border-radius: 5px;" >
        <span class="input-group-addon"></span>
      </div>
   </div>
 </div>

    <table id="tbl_trakes" class="display responsive nowrap table table-sm " style="width:100%">
    <thead>
        <tr>
            <th>N°</th>
             <th></th>
            <th scope="col" >Usuarios</th>
            <th scope="col" >Día</th>
            <th scope="col" >Entrada</th>
            <th scope="col" >Salida</th>
            <th scope="col" >Latitud</th>
            <th scope="col" >Longitud</th>
            <th scope="col" >Turno</th>
            <th scope="col" >Estado</th>
            <th scope="col" >Fecha</th>
            <th></th>
          
           
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
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
  </table>

</div>
</div>
</div>


<div class="modal fade" id="show_maps_modal" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div class="icon-container" style="margin-top:5px">
          <i class="fa fa-pencil-square-o" style="font-size: 20px"  aria-hidden="true"></i>&nbsp;  
        </div>
        <h5 class="modal-title" id="tituloModal_mps"></h5>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <section class="invoice">
        <div id="map"></div>
         </section>
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
       
      </div>

    </div>
  </div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik"></script>

<script type="text/javascript">
 $(document).ready(function() {
 hideLoadingOverlay();
 runBackgroundAsync();
 getTrakers();
      
    });
    </script>