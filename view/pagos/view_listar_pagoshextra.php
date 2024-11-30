
<script type="text/javascript" src="../js/pagoextra.js?rev=<?php echo time();?>"></script>
<div class="row">
  <div class="col-md-12">
    <div class="tile" style="border-radius: 5px;padding: 10px;">
     <div class="tile-body">
      <ul class="nav nav-pills flex-column mail-nav">
        <li class="nav-item active">
          <i class="fa fa-home fa-lg"></i> / Pagos horas extra

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
       <h5 class="" ><strong>Lista de horas extras acumuladas - <?php echo date("Y"); ?></strong></h5>
     </div>
     <div class="col-sm-6">
       <div class="input-group">
        <input type="text" class="global_filter form-control" id="global_filter" placeholder="Ingresar dato a buscar"  style="border-radius: 5px;">
        <span class="input-group-addon"></span>
      </div>
    </div>
  </div>

  <table id="tabla_extras_horas" class="display responsive nowrap table table-sm table-xs" style="width:100%">
    <thead>
      <tr>
        <th>N°</th>
        <th>Id</th>
        <th>Nombre y Apellido</th>
        <th>Dni</th>
        <th>Salario</th>
        <th>T. Horas(h)</th>
        <th>T. Monto($/.)</th>
        <th>F. Desde</th>
        <th>F. Hasta</th>
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
        <th></th>
        <th></th>
      </tr>
    </tfoot>

  </table>

</div>
</div>
</div>

<style type="text/css">
  .fila-seleccionada {
    background-color: #e6f7ff; /* Color de fondo para la fila seleccionada */
    font-weight: bold; /* Texto en negrita para la fila seleccionada */
  }
  .total-row{
    background: #080808;
        font-weight: bold;
        color: white;
  }

</style>


<div class="modal fade" id="modapagoshoraextra" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Pagar hora extra a: <label id="namepeople"></label> - <?php echo date("Y"); ?>  </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"> 


        <div class="bs-component">
          <div class="alert alert-dismissible alert-success">
            <button class="close" type="button" data-dismiss="alert">×</button><strong>Bien!</strong>  Seleccione la fila que desea pagar.  <a class="alert-link" href="#">Informativo.</a>.
          </div>
        </div>

        <input type="text" name="" id="idempleadoextrapago" hidden>

        <table class="table table-sm" id="tabla_detall_pagos">
          <thead>
            <tr>
              <th>N°</th>
              <th>Fecha</th>
              <th>Cant. Horas</th>
              <th>Monto($)</th>
              <th>Año</th>

            </tr>
          </thead>
          <tbody>
           <tbody id="tbody_tabla_detall_pagos"></tbody>
           <tr >
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
          </tr>
           <tr class="total-row">
            <td class="">Total:</td>
            <td ></td>
            <td ></td>
            <td  id="total_acumulado">S./ 0.0</td>
            <td ></td>
          </tr>
        </table>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button class="btn btn-primary" id="btnregister" onclick="capturarCodigosSeleccionados(this)">Pagar</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalPaymentImprimir" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Imprir boleta </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"> 
         <label id="namepeople_print">[]</label>
       <input  id="idpersonaimprimir" type="text" hidden>
       <div class="form-group">
        <label for="exampleSelect1"><strong>Seleccione Fecha</label>
          <select class="form-control" id="fechaimprimir">
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button class="btn btn-primary" id="btn-register"  onclick="printPayment(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i id="icono" class="fa fa-print" aria-hidden="true"></i>Imprimir</button>
      </div>

    </div>
  </div>
</div>


<style>
  .btn-xs {
      padding: 1px 5px;
      font-size: 12px;
      line-height: 1.5;
      border-radius: 3px;
  }
 

  .table-xs th {
      padding: 2px;
      border: 0;
      border-bottom: 1px solid #ddd;
      font-size: 14px;
  }
    
  .table-xs tbody {
      border-top: 0;
  }
    
  .table> :not(:first-child) {
      border-top: 0;
  }
    
  .table-xs td {
      padding: 1px 1px;
      font-size: 11px;
  }
    
  .table-xs tr {
      height: 20px !important;
  }
</style>

<div class="modal fade" id="modal_tiket_extra" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <div class="icon-container" style="margin-top:5px">
          <i class="fa fa-print" style="font-size: 20px"  aria-hidden="true"></i>&nbsp;  
        </div>
        <h5 class="modal-title" id="">Imprimir tiket</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="" style="-webkit-transform:scale(0.99);">
        <div class="row" style="margin: 3px;">

            <div class="col-lg-12" style="padding: 0">

                <img src="../images/logo.png" onerror="this.onerror=null; this.src='../images/defaultphoto.png'" style="max-width: 90%; height: auto; display: block; margin-left: auto; margin-right: auto;" alt="Logo">

               <p style="margin:0;font-size:100%;text-align: center;font-family:Verdana, Geneva, sans-serif;">Sistema de gestión de RH</p>
               <p style="margin:0;font-size:80%;text-align: center;font-family:Verdana, Geneva, sans-serif;">Pago Hrs extra</p>
               <p id="peopeleNames" style="margin:0;font-size:80%;"></p>
               <p id="salaryPeople" style="margin:0;font-size:80%;"></p>
               <p id="currentDate" style="margin:0;font-size:80%;"></p>

            </div>

            <div class="col-lg-12" style="padding: 0">
                <p style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;border-top: 1.5px dashed #000; margin-top: 5px;margin-bottom: 5px;"></p>
                <table class="table table-hover table-xs" style=" width: 100%;">
                        <thead>
                            <tr>
                                <th style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;">Fech</th>
                                <th style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;text-align: center;">Hrs</th>
                                <th>.</th>
                                <th style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;text-align: right;"> Total</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_body_payment_extra">
                            
                        </tbody>
                    </table>
                
                 <table class="table table-hover table-xs" style=" width: 100%;">
                  <tbody>
                    <tr style="white-space: nowrap;">
                       <td style=""></td>
                      <td style="text-align: center; vertical-align: middle; width: 100%;">Total.
                      </td>
                      <td style="text-align: right;" id="extra_amout_total">0</td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="col-lg-12" style="padding: 0">
            </div>
            
        </div>
    </div>
        
      </div>
      <div class="modal-footer">
       
        <button class="btn btn-success btn-sm " id="btnregister" onclick="printing(this)"><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i>Imprimir</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
 $(document).ready(function() {
   PagosHorasExtraPersonal(); 
    hideLoadingOverlay() ; 
 });

 function capturarValor(checkbox) {
  if (checkbox.checked) {
    var valor = checkbox.value;
    console.log("Valor capturado:", valor);
  }
}



</script>