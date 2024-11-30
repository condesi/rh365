
<?php
$idpersona = $_GET['idpersona'] ?? '';
$estado = $_GET['estado'] ?? '';
?>

<script type="text/javascript" src="../js/persona.js?rev=<?php echo time();?>"></script>

<div class="row">
  <div class="col-md-12">
    <div class="tile" style="border-radius: 5px;padding: 10px;">
     <div class="tile-body">
      <ul class="nav nav-pills flex-column mail-nav">
        <li class="nav-item active">
          <i class="fa fa-home fa-lg"></i> / Personas
          <a class="btn btn-secondary btn-sm float-right" type="button" onclick="cargar_contenido('Contenido_principal','../view/personal/view_listar_personal.php')"><em class="fa fa-arrow-left"></em>Atras</a>
          
        </li>
      </ul>
    </div>
    
  </div>
</div>
</div>
<div class="row" >
  <div class="col-md-12">
    <div class="tile"  style="border-top: 3px solid #0720b7;border-radius: 5px;">
      <form autocomplete="false" id="from" onsubmit="return false"   action="#" enctype="multipart/form-data" onsubmit="return false">
        <section class="invoice" >
         <div class="icon-container" style=" display: flex;">
           <i class="fa fa-pencil-square" style="margin-top: 5px;"></i>&nbsp;  
           <h5 class="mb-3 " id="tituloModal"> Nuevo  registro</h5> 
         </div>

         <div class="row invoice-info">

           <div class="col-lg-4">
            <div class="form-group">
              <label for="inputDefault"><strong>Nombre (*)</strong></label>
              <input class="form-control" id="Idpersona" type="text" hidden>
              <input class="form-control" id="NombrePersona" type="text"   placeholder="Nombre" autocomplete="false" >
              
            </div>
            <div class="form-group">
             <label for="inputDefault"><strong>Apellidos (*)</strong></label>
             <input class="form-control" id="ApellidoPersona" type="text" placeholder="Apellidos." >
             
           </div>
           <div class="form-group"> 
             <label for="inputDefault"><strong>Correo (*)</strong></label>
             <input class="form-control" id="correoPersona" type="text" placeholder="corre@***" >
           </div>
           
           <div class="form-group">
            <label for="inputDefault"><strong>Documento (*)</strong></label>
            <input class="form-control" id="DniPersona" type="text" placeholder="Cedula o pasaporte" >
            
          </div>
          <div class="form-group">
           <label for="inputDefault"><strong>Telefono (*)</strong></label>
           <input class="form-control" id="telefonoPersona" type="number"  placeholder="Telefono" >
           
         </div>
         <div class="form-group">
        <label for="exampleSelect1"><strong>Tipo de personal</strong></label>
        <select class="form-control" id="typePeople" >
         <option value="">---Seleccione--</option>
         <option value="Normal">Normal</option>
         <option value="Chofer">Chofer</option>
         <option value="Electrisista">Electrisista</option>
         <option value="Bonberos">Bonberos</option>
         <option value="Vendedor">Vendedor</option>
         <option value="Jardinero">Jardinero</option>
         <option value="Cajero">Cajero</option>
          <option value="otros">otros</option>
       </select>
     </div>
       </div>




       <div class="col-lg-4">
        <div class="form-group">
         <label for="inputDefault"><strong>Dirección (*)</strong></label>
         <input class="form-control" id="direccionPersona" type="text" placeholder="Dir." >
       </div>
       <div class="form-group">
         <label for="inputDefault"><strong>Salario (*)</strong></label>
         <input class="form-control" id="SalarioPersona" type="number" placeholder="S/." >
       </div>
       <div class="form-group">
        <label for="exampleSelect1"><strong>Sexo (*)</strong></label>
        <select class="form-control" id="SexoPersona" >
          <option value="">---Seleccione-- </option>
          <option value="Hombre">Hombre</option>
          <option value="Mujer">Mujer</option>
        </select>
      </div>
      
      <div class="form-group">
        <label for="exampleSelect1"><strong>Monedas (*)</strong></label>
        <select class="form-control" id="MonedaPersona" >
          <option value="">---Seleccione-- </option>
          <option value="PESOS">$DOP</option>
          <option value="DOLAR">$USD</option>
          <option value="SOLES">SOLES (s/.)</option>
        </select>
      </div>
      <div class="form-group">
        <label for="exampleSelect1"><strong>Entrevista (*)</strong></label>
        <select class="form-control" id="entrevistaPersona" >
         <option value="">---Seleccione--</option>
         <option value="Pendiente">Pendiente</option>
         <option value="SI">SI</option>
         <option value="NO">NO</option>
       </select>
     </div>
   </div>



   <div class="col-lg-4" >
    <div class="form-group">
      <label for="exampleSelect1"><strong>Resultado entrevista (*)</strong></label>
      <select class="form-control" id="resultentrevistaPersona" >
       <option value="">---Seleccione--</option>
       <option value="Pendiente">Pendiente</option>
       <option value="Aprobó">Aprobó</option>
       <option value="No Aprobó">No Aprobó</option>
     </select>
   </div>
   
   <div class="form-group">
     <label for="inputDefault"><strong>Curiculum (CV.pdf)</strong></label>
     
     
     <input type="file" class="form-control" name="archivo" accept=".pdf" id="archivoInput" onchange="mostrarNombreArchivo()">
     
     <div style="display:flex;">
      <a class="nav-link"><label id="nombreArchivoLabel"></label></a>
      <a id="cvActual" href="" target="_blank"></a><br>
      <a class="float-right" type="button" id="quitarArchivoButton" onclick="quitarArchivo()" style="display: none;"><i class="fa fa-times-circle" aria-hidden="true" style="color: black;margin-top: 15px"></i></a>
    </div>
  </div>


 <div class="profile">
           <div id="previewContainer">
             <button class="btn btn-sm text-end text-end" onclick="Quitarfoto()" id="removeButton">
              <i class="fa fa-times-circle" aria-hidden="true"></i></button>
             <img id="mostrarimagen" src="../images/user/default.png" alt=" Sin Foto...">
          </div>
          <input type="file" class="form-control"  id="seleccionararchivo" accept="image/*" onchange="onchangeInput()" />

        </div>


  
  <input type="text" name="" id="phatphoto" hidden>
  <input type="text" name="" id="phatdocument" hidden>

</div>
</div>



<div class="tile-footer" style="text-align: right;">
  <button id="ButtGaued" class="btn btn-primary btn-sm" onclick="Registrar_personas('<?php echo $estado; ?>');" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Registrar</button>
  <a id="ButtCancelar" class="btn btn-secondary btn-sm" onclick="cargar_contenido('Contenido_principal','../view/personal/view_listar_personal.php')"><i class="fa fa-fw fa-lg fa-times-circle" style="color: white;"></i><strong style="color: white;">Cancelar</strong></a>
</div>

</section>
</form>
</div>
</div>
</div>
<script type="text/javascript">


 $(document).ready(function() {
 hideLoadingOverlay() ;
   var idpersona = '<?php echo $idpersona; ?>';

   if ('<?php echo $idpersona; ?>' !== '' && '<?php echo $estado; ?>' !== '') {

     $("#tituloModal").text('Actualizar Usuario');
     $("#ButtGaued").text('Actualizar');

     Show_Personas(idpersona);
   }else{

   }

   
 });


 $('input').on('keydown', function(e) {
   updateValue(e);
 })

 $('select').on('change', function(e) {
   ValidadSelect(e);
 });


</script>