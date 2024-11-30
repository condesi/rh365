
 <div class="modal fade" id="modal_hoursDetall" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Resumen de horas por persona - <?php echo date("Y"); ?> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        
      </div>
      <div class="modal-body"> 
           
           <table id="table_Detall" class="display responsive nowrap" style="width:100%">
            <thead>
                    <tr>
                        <th>N°</th>
                        <th></th>
                        <th>Fechas</th>
                        <th>Turnos</th>
                        <th>H. entrada</th>
                        <th>H. salida</th>
                        <th>Total Horas</th>
                         <th>Día</th>
                      
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
          
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
           
          </div>
       
      </div>
    </div>
  </div>