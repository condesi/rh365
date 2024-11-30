
<script type="text/javascript" src="../js/persona.js?rev=<?php echo time();?>"></script>
   <div class="row">
        <div class="col-md-12">
          <div class="tile" style="border-radius: 5px;padding: 10px;">
           <div class="tile-body">
              <ul class="nav nav-pills flex-column mail-nav">
                <li class="nav-item active">
                  <i class="fa fa-home fa-lg"></i> / Personal
                  
                  <a class="btn btn-primary icon-btn btn-sm float-right" onclick="cargar_contenido('Contenido_principal','../view/personal/view_crear_editar_persona.php')"><i class="fa fa-plus-circle " aria-hidden="true" style="color: white;"></i>&nbsp;<strong style="color: white;" >Nuevo Registro</strong></a>
              
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
          <div class="input-group" id="btn-place"></div>
        </div>
     <div class="col-sm-4">
       <h5 class="" ><strong>Gestión de Personas - <?php echo date("Y"); ?></strong></h5>
     </div>
     <div class="col-sm-4">
     <div class="input-group">
        <input type="text" class="global_filter form-control" id="global_filter" placeholder="Ingresar dato a buscar"  style="border-radius: 5px;">
        <span class="input-group-addon"></span>
      </div>
   </div>
 </div>

 <table id="tabla_personas" class="display responsive nowrap table table-sm" style="width:100%">
  <thead>
    <tr>
      <th>N°</th>
      <th>ID</th>
      <th>Nombre y Apellido</th>
      <th>Dni(Cédula)</th>
      <th>Salario</th>
      <th>Resul. entrevista</th>
      <th>Estado</th>
      <th>En Trabajo?</th>
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

<script type="text/javascript">


 $(document).ready(function() {
   hideLoadingOverlay() ;

      Listar_Personas();
 
    });
    </script>

