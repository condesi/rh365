<script type="text/javascript" src="../js/dashboard.js?rev=<?php echo time();?>"></script>


      <style>
       .inline-p {
           display: inline-block;
           margin-right: 10px;
         }
     </style>


<div class="row">
          
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="widget-small primary " style="background: white; color: black;"><i class="icon fa fa-users fa-3x"></i>
            <div class="info">
              <h4>Personas</h4>
              <p id="" class="inline-p">Total:</p>
              <p class="inline-p" id="TotasPersonadashboard">0</p>
            </div>
          </div>
        </div>
       <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="widget-small info "><i class="icon fa fa-steam fa-3x "></i>
            <div class="info">
              <h4>Trabajando</h4>
              <p id="" class="inline-p">Total:</p>
              <p class="inline-p" id="totaldetrabajadoresdash">0</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="widget-small warning "><i class="icon fa fa fa-money fa-3x "></i>
            <div class="info">
              
              <h4>Adelantos</h4>
              <p id="" class="inline-p">S/.</p>
              <p class="inline-p" id="totaldeadelantosdash">0</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="widget-small " style="background:#5425eb"><i class="icon fa  fa-clock-o fa-3x"></i>
            <div class="info">
              <h4>Hrs. extras</h4>
             <p id="" class="inline-p">S/.</p>
             <p  class="inline-p" id="totaldehorasextrasdash">0</p>
            </div>
          </div>
        </div>
      </div>



<div class="row">
        <div class="col-md-6">
          
          <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">
            <p style="text-align: center;"><strong>Horas extras Mensuales - <?php echo date("Y"); ?></strong></p>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="barCharextras" width="184" height="103" style="width: 184px; height: 103px;"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile" style="border-top: 3px solid #0720b7;border-radius: 5px;">
            <p style="text-align: center;"><strong>Adelantos Mensuales - <?php echo date("Y"); ?></strong></p>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="barCharadelantos" width="184" height="103" style="width: 184px; height: 103px;"></canvas>
            </div>
          </div>
        </div>
        
      </div>

<script type="text/javascript">

 $(document).ready(function() {
    hideLoadingOverlay() ;
   DashboardSisteme();
  });
</script>

<script type="text/javascript">
  
      
    </script>