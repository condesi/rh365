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
          <div id="typeDocumentPrint" class="ticket-print" style="-webkit-transform:scale(0.99);">
        <div class="row" style="margin: 3px;">

            <div class="col-lg-12" style="padding: 0">
               <p style="margin:0;font-size:100%;text-align: center;font-family:Verdana, Geneva, sans-serif;">TICKET DEPOSITO</p>
               <p style="margin:0;font-size:80%;text-align: center;font-family:Verdana, Geneva, sans-serif;">Mov. 896 </p>
               <p style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;">PLATAFORMA. 346</p>
               <p style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;">CODIGO: 123 </p>
               <p style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;">FECHA: 20-24-10</p>
            </div>

            <div class="col-lg-12" style="padding: 0">
                <p style="margin:0;font-size:80%;font-family:Verdana, Geneva, sans-serif;border-top: 1.5px dashed #000; margin-top: 5px;margin-bottom: 5px;"></p>
                <table>
                  <tbody>
                    <tr>
                      <td style="width: 60%;">
                          <p style="margin:0;font-size:90%;font-family:Verdana, Geneva, sans-serif;"><strong>MONTO </strong></p>
                      </td>
                      <td style="width: 14%;text-align: right"><strong>10 PEN</strong></td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="col-lg-12" style="padding: 0">
            </div>
            <div class="watermark" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.5; z-index: 999; pointer-events: none;">
                <p style="font-size: 50px; font-family: 'Arial', sans-serif; color: #ccc; transform: rotate(-45deg); margin: 0;">Cancelado</p>
            </div>
        </div>
    </div>
        
      </div>
      <div class="modal-footer">
       
        <button class="btn btn-success btn-sm " id="btnregister" onclick="Register_Asistencia(this)"><span class="spinner-border spinner-border-sm"  style="display: none;"></span><i id="icono"  class="fa fa-check-circle-o" aria-hidden="true"></i>Imprimir</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar</button>
      </div>

    </div>
  </div>
</div>