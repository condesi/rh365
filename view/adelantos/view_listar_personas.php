
<script type="text/javascript" src="../js/adelantos.js?rev=<?php echo time();?>"></script>
   <div class="row">
        <div class="col-md-12">
          <div class="tile" style="border-radius: 5px;padding: 10px;">
           <div class="tile-body">
              <ul class="nav nav-pills flex-column mail-nav">
                <li class="nav-item active">
                  <i class="fa fa-home fa-lg"></i> / Adelantos
              
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
      <div class="col-sm-6">
         <h5 class="" ><strong>Contenido de adelantos - Adelantos - <?php echo date("Y"); ?></strong></h5>
        </div>
     <div class="col-sm-2">
       
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
    </tr>
  </tfoot>
</table>


</div>
</div>
</div>


<div class="modal fade" id="modaladelantos" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tituloModal">Registro de nuevos Adelantos <?php echo date("Y"); ?> </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				
			 <div class="inline-labels">
			 <div class="form-group" style="display: flex;">
              <label style="margin-right: 10px;"><strong>Salario:</strong></label>
              <label id="labelSalarioacutal"style="margin-right: 25px;">0</label>
              <label style="margin-right: 10px;"><strong>Datos:</strong></label>
              <label id="labelDatosPersonales" style="margin-right: 25px;"></label>
            </div>
					
			 <div class="form-group" style="display: flex;">
              <label style="margin-right: 2px;"><strong>Ingrese Monto:</strong></label>
              <input class="form-control form-control-sm" id="montoadelanto" type="number" style="margin-right: 2px;">
              <a class="btn btn-secondary btn-sm" onclick="RegistrarNuevoAdelanto()" style="color: white; margin-right: 2px;height: 30px">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
              </a>
            </div>	
				</div>
				<input type="text" name="" id="idpersonaadelanto" hidden>
				<input type="text" name="" id="salarioactual" hidden>
				<table class="table table-sm" id="tabadetallesid">
					<thead>
						<tr>
							<th>N°</th>
							<th>Fecha</th>
							<th>Monto(Cantidad)</th>
							<th>Año</th>
							<th>Quitar</th>
						</tr>
					</thead>
					<tbody>
						<tbody id="tbody_tabla_detall"></tbody>
						     <tr style="background: #dde2ed;">
                            <td colspan='2'><strong>Total Monto:</strong></td>
                            <td id='totalMontoCell'><strong>0</strong></td>
                            <td colspan='2'></td>
                            </tr>
						
					</table>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
					
				</div>

			</div>
		</div>
	</div>

<style type="text/css">
  .total-row {
  background-color: #f0f0f0;
  font-weight: bold;
}

</style>


 <div class="modal fade" id="modalreportesadelantos" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Adelantos Registrados - <?php echo date("Y"); ?> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"> 
           
           <table id="tableAdelantroslist" class="display responsive nowrap" style="width:100%">
            <thead>
                    <tr>
                        <th>N°</th>
                        <th>Fechas</th>
                        <th>Horas</th>
                        <th>Monto</th>
                        <th>Descripción</th>
                        <th>Estado</th>
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
                    </tr>
                </tfoot>
              </table>
         
          </div>
          
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
           
          </div>
       
      </div>
    </div>
  </div>


<script type="text/javascript">

 $(document).ready(function() {
  Listar_Personas();
  hideLoadingOverlay() ;
    });
$("#montoadelanto").keypress(function(event) {
  if (event.which === 13) {
    event.preventDefault(); // Evitar el comportamiento predeterminado del Enter (enviar el formulario, si lo hay)
   
    RegistrarNuevoAdelanto();
  }
});
    </script>