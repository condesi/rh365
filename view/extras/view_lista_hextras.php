
<script type="text/javascript" src="../js/hextras.js?rev=<?php echo time();?>"></script>
   <div class="row">
        <div class="col-md-12">
          <div class="tile" style="border-radius: 5px;padding: 10px;">
           <div class="tile-body">
              <ul class="nav nav-pills flex-column mail-nav">
                <li class="nav-item active">
                  <i class="fa fa-home fa-lg"></i> / Horas Extras
              
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
         <h5 class="" ><strong>Registro de horas extras- <?php echo date("Y"); ?></strong></h5>
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

  <table id="tabla_horas_extra" class="display responsive nowrap table table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>id</th>
                        <th>Nombre y Apellido</th>
                        <th>N° de Doc</th>
                        <th>Moneda</th>
                        <th>salario</th>
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




<div class="modal fade" id="modalInscripciones" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Registro horas extra Personal - <?php echo date("Y"); ?> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="row d-print-none mt-2" style="display: flex;">
             <!-- <div class="inline-labels">
                <h5>Cálculo de salario: Salario actual/23.83</h5><h5>Horas diarios: 08</h5><h5></h5>
              </div>-->
              <div class="col-12 text-left">

                <div class="inline-labels">
                   <label>Salario Actual:</label>
                    <label id="labelSalarioacutal">0</label>&nbsp;&nbsp;
                    <input type="checkbox" name="opcion" value="0.2" onclick="toggleCheckbox(this)" >
                    <label style="margin-right: 15px;"><strong>20%</strong></label>
                    <input type="checkbox" name="opcion" value="0.3" onclick="toggleCheckbox(this)">
                    <label style="margin-right: 15px;"><strong>30%</strong></label>
                    <input type="checkbox" name="opcion" value="0.4" onclick="toggleCheckbox(this)">
                    <label style="margin-right: 15px;"><strong>40%</strong></label>
                    <input type="checkbox" name="opcion" value="0.5" onclick="toggleCheckbox(this)">
                    <label style="margin-right: 15px;"><strong>50%</strong></label>
                    <input type="checkbox" name="opcion" value="0.7" onclick="toggleCheckbox(this)">
                    <label style="margin-right: 15px;"><strong>70%</strong></label>
                    <input type="checkbox" name="opcion" value="1" onclick="toggleCheckbox(this)">
                    <label style="margin-right: 15px;"><strong>100%</strong></label>

                </div>
               
              </div>
                <div class="col-12 text-right"><a class="btn  btn-secondary btn-sm" onclick="Addtd_table()"  style="color: white;margin-top: -60px;"><i class="fa fa-plus-circle" aria-hidden="true"></i></a></div>
              </div>
              
           <input type="text" name="" id="idempleadoextra" hidden >
           <input type="text" name="" id="salarioactual" hidden>
            <table class="table table-sm table-xs" id="tabadetallesid">
              <thead>
                <tr>
                  <th>N°</th>
                  <th>Fecha</th>
                  <th>Cant. Horas</th>
                  <th>Monto($)</th>
                  <th>Año</th>
                  <th>Quitar</th>
                </tr>
              </thead>
              <tbody>
               <tbody id="tbody_tabla_detall"></tbody>
            </table>
         
          </div>
          
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary  btn-sm" id="btnregister" onclick="guardarValores()">Guardar</button>
          </div>
       
      </div>
    </div>
  </div>
  </div>



<script type="text/javascript">

 $(document).ready(function() {
  Listar_Horas_Extras();
   hideLoadingOverlay() ;
    });

    </script>