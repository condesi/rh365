
<script type="text/javascript" src="../js/permission.js?rev=<?php echo time();?>"></script>
<div class="row">
  <div class="col-md-12">
    <div class="tile" style="border-radius: 5px;padding: 10px;">
     <div class="tile-body">
      <ul class="nav nav-pills flex-column mail-nav">
        <li class="nav-item active">
          <i class="fa fa-home fa-lg"></i> / Gestion de permisos



        </li>
      </ul>
    </div>

  </div>
</div> 
</div>

<div class="row" id="section_tabale">
 <div class="col-md-12">
  <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">
   <div class="row invoice-info">

     <div class="col-sm-6">
       <h5 class="" ><strong> Gestion de permisos - <?php echo date("Y"); ?></strong></h5>
     </div>
     <div class="col-sm-6">
       <div class="input-group">
        <input type="text" class="global_filter form-control" id="global_filter" placeholder="Ingresar dato a buscar"  style="border-radius: 5px;">
        <span class="input-group-addon"></span>
      </div>
    </div>
  </div>
<div class="table-responsive">
  <table id="tbl_permission" class="display responsive nowrap table table-sm table-xs" style="width:100%">
    <thead>
        <tr>
          <th>N°</th>
          <th>Id</th>
          <th>Nombre y Apellido</th>
          <th>Tp. permiso</th>
          <th>Periodo</th>
          <th>Días/Hrs</th>
           <th>D.(Tot/Disp)</th>
          <th>Estado</th>
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
</div>
<style type="text/css">
   #previewRoom1{
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 160px;
    display: inline-block;
    margin: 0 auto;
}

</style>

<?php include('../components/modal_permission.php'); ?>


<script type="text/javascript">
 $(document).ready(function() {
  inspectBackgroundAsync();
  Listar_Personas_Permission();
    hideLoadingOverlay() ; 
 });



</script>