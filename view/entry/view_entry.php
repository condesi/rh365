
  <style>
  	#user_entry{background: linear-gradient(to right, #324ba1, #2bd385); padding: 15px;}
    .user-profile { text-align: center;}
    .user-profile img {
            width: 70px;
            height: 70px; /* Tamaño uniforme */
            border-radius: 50%;
            object-fit: cover;
        }

    .user-details { margin-top: 10px;  text-align: center;}
    .user-details p {font-weight: bold;margin: 0; }
    </style>

    

<script type="text/javascript" src="../js/entry.js?rev=<?php echo time();?>"></script>
 <?php include('../components/title_global_wiev.php'); header_wiev("Asistentes");?>

 <div id="user_entry">
    <div class="col-sm-12">
         <div class="form-group">
         <input class="form-control form-control-sm" id="search" type="text" placeholder="Ingrese para buscar" autocomplete="off" oninput="searchFilter(this)">
    </div>
    </div>
    <div class="row"  id="conteiner_user"></div>
 </div>

  <?php include('../components/modal_entry.php'); ?>


<script type="text/javascript">
 $(document).ready(function() {
  runBackgroundAsync();
  list_user_individual();
   hideLoadingOverlay() ;
});

 function searchFilter(e){
    var inputValue = e.value;
    userFilters(inputValue.toLowerCase());
 }
 </script>


