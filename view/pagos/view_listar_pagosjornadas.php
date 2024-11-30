
<script type="text/javascript" src="../js/pagojornada.js?rev=<?php echo time();?>"></script>
<div class="row">
  <div class="col-md-12">
    <div class="tile" style="border-radius: 5px;padding: 10px;">
     <div class="tile-body">
      <ul class="nav nav-pills flex-column mail-nav">
        <li class="nav-item active">
          <i class="fa fa-home fa-lg"></i> / Pagos Jornadas



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
       <h5 class="" ><strong>Pagos Jornadas mensuales - <?php echo date("Y"); ?></strong></h5>
     </div>
     <div class="col-sm-6">
       <div class="input-group">
        <input type="text" class="global_filter form-control" id="global_filter" placeholder="Ingresar dato a buscar"  style="border-radius: 5px;">
        <span class="input-group-addon"></span>
      </div>
    </div>
  </div>

  <table id="tabla_pagos_jornadas" class="display responsive nowrap table table-sm table-xs" style="width:100%">
    <thead>
      <tr>
        <th>N°</th>
        <th>Id</th>
        <th>Nombre y Apellido</th>
        <th>Dni</th>
        <th>Salario</th>
        <th>Fecha Últ. Pago</th>
        <th>Fecha Prox. Pago</th>
        <th>Estado cuenta</th>       
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

<style>
 .container {
  display: flex;

}

.container input,
.container select {
  margin-right: 10px;
}
.fila-seleccionada{

  font-weight: bold;}
</style>


<div class="modal fade" id="modapagosjornadas" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Pagar a:<label id="namepeople"></label> - <?php echo date("Y"); ?>  </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"> 

        <div class="row invoice-info">
         <div class="col-sm-4">

           <div class="container">
            <div class="toggle">
             <label class="col-form-label" for="inputDefault"><strong>Adelantos</strong></label>
             <label>
              <input type="checkbox" id="adelantos" onclick="toggleincluiradelantos(this)" ><span class="button-indecator"></span>
            </label>
          </div>
          <div class="toggle">
           <label class="col-form-label" for="inputDefault"><strong>H. Extras</strong></label>
           <label>
            <input type="checkbox" id="horasextras" onclick="toggleincluirextras(this)" ><span class="button-indecator"></span>
          </label>
        </div>
      </div>
    </div>
    <div class="col-sm-4">

      <div class="form-group">
        <select class="form-control" id="mesSelect">
          <option value="">---Seleccione Mes---</option>
        </select>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">

        <div class="alin_global" style="display: flex;" >
         <input class="form-control" type="number" id="montoInput" placeholder=" S/. Monto">&nbsp;&nbsp;
         <a class="btn  btn-secondary btn-sm" style="font-size: 15px;height: 35px;" onclick="AgregarMesSeleccionadoTable()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
       </div>
     </div>
   </div>

 </div>
 <div class="bs-component" >Últipo pago realizado :<span class="badge badge-primary" id="htmlpago"></span></div> <br>
 <input type="date" name="" id="fechadeultimopago" hidden >
 <input type="text" name="" id="ipersonaspagos" hidden>

 <table class="table table-sm" id="tabla_detall_pagos">
  <thead>
    <tr>
      <th>N°</th>
      <th>Mes Seleccionado</th>
      <th>Fecha</th>
      <th>Cantida</th>
      <th>Monto($)</th>
      <th>Quitar</th>
    </tr>
  </thead>
  <tbody>
   <tbody id="tbody_tabla_detall_pagos"></tbody>

   <tr class="fila-seleccionada" >
    <td class=""><strong>Total Adelantos: </strong></td>
    <td></td>
    <td></td>
    <td></td>
    <td id="total_adelantos">0</td>
  </tr>
  <tr class="fila-seleccionada" >
    <td class=""><strong>Total Horas Extras: </strong></td>
    <td></td>
    <td></td>
    <td></td>
    <td id="total_horas_extras">0</td>
  </tr>
  <tr class="fila-seleccionada" >
   <td class=""><strong>Total Pagar: </strong></td>
   <td ></td>
   <td ></td>
   <td ></td>
   <td  id="total_acumulado">0</td>

 </tr>
</table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
  <button class="btn btn-primary" id="btn-register" onclick="Registrar_Pagos_mensuales(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i>Pagar</button>
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


<div class="modal fade" id="modapagosimprimir" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Imprir Pagos realizado <?php echo date("Y"); ?> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"> 
       <input  id="idpersonaimprimir" type="text" hidden>
       <div class="form-group">
        <label for="exampleSelect1"><strong>Seleccione Fecha</label>
          <select class="form-control" id="fechaimprimir">
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button class="btn btn-primary" id="btn-register"  onclick="ImprimiReportePago(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span>
         <i id="icono"  class="fa fa-print" aria-hidden="true"></i>Imprimir</button>

        
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modal_tiket" role="dialog">
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
          <div class="ticket-print" style="-webkit-transform:scale(0.99);">
        <div class="row" style="margin: 3px;">

            <div class="col-lg-12" style="padding: 0">

                <img src="../images/logo.png" onerror="this.onerror=null; this.src='../images/defaultphoto.png'" style="max-width: 90%; height: auto; display: block; margin-left: auto; margin-right: auto;" alt="Logo">

               <p style="margin:0;font-size:100%;text-align: center;font-family:Verdana, Geneva, sans-serif;">Sistema de gestión de RH</p>
               <p style="margin:0;font-size:80%;text-align: center;font-family:Verdana, Geneva, sans-serif;">Pago de jornada</p>
               <p id="peopeleNames" style="margin:0;font-size:80%;"></p>
               <p id="salaryPeople" style="margin:0;font-size:80%;"></p>
               <p id="currentDate" style="margin:0;font-size:80%;"></p>

            </div>


               <div  style=" width: 100%;">
                <p style="margin:0;font-size:80%;border-top: 1.5px dashed #000; margin-top: 5px;margin-bottom: 5px;"></p>
                      <table class="table table-hover table-xs" style=" width: 100%;">
                        <thead>
                            <tr>
                                <th style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;">Fech</th>
                                <th style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;text-align: center;">Mes</th>
                                <th>.</th>
                                <th style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;text-align: right;"> Total</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_body">
                            
                        </tbody>
                    </table>
                    <table class="table table-hover table-xs" style=" width: 100%;">
                  <tbody>
                    <tr style="white-space: nowrap;">
                       <td style=""></td>
                      <td style="text-align: center; vertical-align: middle; width: 100%;">Adelanto
                          
                      </td>
                     
                      <td style="text-align: right;" id="amout_adelantos">0</td>
                    </tr>
                    <tr style="white-space: nowrap;">
                       <td ></td>
                      <td style="text-align: center; vertical-align: middle; width: 100%;">Hrs. Extra
                          
                      </td>
                     
                      <td style="text-align: right;" id="hrExtraAmount">0</td>
                    </tr>
                    <tr style="white-space: nowrap;">
                      <td style="width: 60%;">
                          <p style="margin:0;font-size:90%;font-family:Verdana, Geneva, sans-serif;"><strong>TOTAL </strong></p>
                      </td>
                      <td></td>
                      <td style="width: 14%;text-align: right" id="amout_total"></td>
                    </tr>
                  </tbody>
                </table>
                   
                    <p style="font-size:70%;text-align: right;">Gracias por tu preferencia!. </p>
                
                   </div>
            
        </div>
    </div>
        
      </div>
      <div class="modal-footer">
       
        <button class="btn-xs btn btn-success btn-sm " id="btnregister" onclick="printing(this)"><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i>Imprimir</button>
        <button type="button" class="btn-xs btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
      </div>

    </div>
  </div>
</div>


<script type="text/javascript">
 $(document).ready(function() {
   ActualizarStatePagosbackgroundRunn();
   PagosJornadasMensualesPersonal(); 
   cargarComboMeses();
    hideLoadingOverlay() ; 
 });



</script>