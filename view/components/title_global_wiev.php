 
<?php
function header_wiev($titulo) {
    $html = '
        <div class="row">
        <div class="col-md-12">
          <div class="tile" style="border-radius: 5px;padding: 10px;">
           <div class="tile-body">
              <ul class="nav nav-pills flex-column mail-nav">
                <li class="nav-item active">
                  <i class="fa fa-home fa-lg"></i> / ' . (empty( $titulo) ? " Título Predeterminado" : $titulo) . '
                </li>
              </ul>
            </div>
           
          </div>
        </div>
      </div>
    ';
    echo $html;
}
?>
