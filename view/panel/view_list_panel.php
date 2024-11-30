
<script type="text/javascript" src="../js/panel.js?rev=<?php echo time();?>"></script>
  
 <?php include('../components/title_global_wiev.php'); header_wiev("Resumen de horas");?>

<div class="row" >
 <div class="col-md-12">
  <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">

    <div class="row invoice-info">
      <div class="col-sm-4">
       <div class="input-group" id="btn-place"></div>
     </div>
     <div class="col-sm-4">
       <h5 class="" style="text-align: center;"><strong>Resumen de horas - <?php echo date("Y"); ?></strong></h5>
     </div>
     <div class="col-sm-4">
     <div class="input-group">
        <input type="text" class="global_filter form-control" id="global_filter" placeholder="Ingresar dato a buscar"  style="border-radius: 5px;">
        <span class="input-group-addon"></span>
      </div>
   </div>
 </div>

 <table id="table_panel" class="display responsive nowrap table table-sm" style="width:100%">
  <thead>
    <tr>
      <th>Número</th>
      <th>N°</th>
      <th>Nombres</th>
      <th>Apellidos</th>
      <th>Código</th>
      <th>Tip. Horario</th>
      <th>Mes</th>
      <th>Tolal horas mensuales</th>
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
      <th></th>
      <th></th>
    </tr>
  </tfoot>
</table>

</div>
</div>
</div>

<?php include('../components/modal_detall_hours.php'); ?>

<script type="text/javascript">
 $(document).ready(function() {List_Panel();
runBackgroundAsync();
  hideLoadingOverlay() ;});
 </script>