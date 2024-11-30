	
 var tablepersona;

function Listar_Personas(){

   tablepersona=$('#tabla_personas').DataTable({
        "ordering": true,
        "bLengthChange": false,
        "scrollCollapse": true,
        "searching": {
            "regex": true
        },
         "responsive": true,
        dom: 'Bfrtilp',
       
         buttons: ['print', 'pdf', 'csv', 'excel'],
        
  
        "lengthMenu": [
            [10, 20, 50, 100, -1],
            [10, 20, 50, 100, "All"]
        ],
        "pageLength": 10,
        "destroy": true,
        "processing": true,
        "ajax": {
            "url": "../controller/Persona/ControllerGetPersonas.php",
            type: 'POST'
        },
        "columns": [
          { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
          {"data": "idpersona" },
          {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
          {"data": "Dni"},
          {"data": "Salario"},
          {"data": "resulentrevista"},
          { "data": "Estado ",
           "render": function (data, type, row) {
            return "<span class='badge badge-pill " + (row.Estado == "Activo" ? "badge-primary" : "badge-warning") + "'>" + row.Estado + "</span>";
          }},
          { "data": "statedinicio ",
           "render": function (data, type, row) {
            return "<span class='badge badge-pill " + (row.statedinicio == "Off" ? "badge-primary" : "badge-warning") + "'>" + row.statedinicio + "</span>";
          }},
          { "data": "statedinicio",
          render: function(data, type, row) {
                if (data == 'On') {
                    return "<button type='button' class='editar btn btn-primary btn-sm'><i class='fa fa-edit' title='editar'></i></button>"+
                    "&nbsp;<button type='button' class='desactivar btn btn-info btn-sm'><i class='fa fa-toggle-on' ></i></button>";
                } else {
                    return "<button type='button' class='editar btn btn-primary btn-sm'><i class='fa fa-edit' title='editar'></i></button>";
                }
            }}

        ],
        "language": idioma_espanol,
        select: true
    });
    document.getElementById("tabla_personas_filter").style.display = "none";
    $('input.global_filter').on('keyup click', function() {
        filterGlobal();
    });
    $('input.column_filter').on('keyup click', function() {
        filterColumn($(this).parents('tr').attr('data-column'));
    });
     tablepersona.column(1).visible( false );
      $('#btn-place').html( tablepersona.buttons().container());
}

function filterGlobal() {
    $('#tabla_personas').DataTable().search($('#global_filter').val(), ).draw();
}


$('#tabla_personas').on('click', '.editar', function() {
    var data = tablepersona.row($(this).parents('tr')).data();
   
    if (tablepersona.row(this).child.isShown()) {
        var data = tablepersona.row(this).data();
    }
    $("#Contenido_principal").load("personal/view_crear_editar_persona.php?idpersona=" + data.idpersona+"&estado="+true);
     
});



$('#tabla_personas').on('click', '.desactivar', function() {
    var data = tablepersona.row($(this).parents('tr')).data();
 
    if (tablepersona.row(this).child.isShown()){
        var data = tablepersona.row(this).data();
    }
    Swal.fire({
        title: 'Seguro que deseas activar en "Off" a esta persona?',
        text: "Una vez hecho esto, sus dís de jornada se pondrana 0 .",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0720b7',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            Modificar_Estatus(data.idpersona,"Off");
        }
    })
     
});

 function  Modificar_Estatus(idpersona,stado){
     $.ajax({
    url:'../controller/Persona/ControllerChangeStatusOff.php',
    type:'POST',
    data:{idpersona:idpersona,stado:stado}
     }).done(function(resultado){
      var request = JSON.parse(resultado);
       if (request.data==1) {
          Swal.fire({icon: 'success',title: 'Éxito !!',text: 'La operación se completo correctamente.',showConfirmButton: false,timer: 1500})
          tablepersona.ajax.reload();
        }else{

          return Swal.fire("Mensaje de error", ""+request.msg, "error");
        }

     }).fail(function(jqXHR, textStatus, errorThrown) {
  if (jqXHR.status === 403) {
   
    return Swal.fire("Mensaje de error", "No Autorizado"+jqXHR.url+",Iniciar Sessión.", "error");
  } else {
    
    return Swal.fire("Mensaje de error", errorThrown, "error");
  }
});
}


function Show_Personas(idpersona){
   $.ajax({
        
       "url": "../controller/Persona/ControllerShowPersona.php",
        type: 'POST',
        data: {idpersona:idpersona}
    }).done(function(resp) {
     IterarDataPersona(resp);
       
    }).fail(function(jqXHR, textStatus, errorThrown) {
  if (jqXHR.status === 403) {
   
    return Swal.fire("Mensaje de error", "No Autorizado"+jqXHR.url+",Iniciar Sessión.", "error");
  } else {
    
    return Swal.fire("Mensaje de error", errorThrown, "error");
  }
});
}

function IterarDataPersona(result){
 var request = JSON.parse(result);
  //Fotopersona, cvpersona,
  var request =request.data;

  $("#Idpersona").val(request[0]['idpersona']);
  $("#NombrePersona").val(request[0]['Nombre']);
  $("#ApellidoPersona").val(request[0]['Apellidos']);
  $("#correoPersona").val(request[0]['Correo']);
  $("#DniPersona").val(request[0]['Dni']);
  $("#telefonoPersona").val(request[0]['Telefono']);
  $("#direccionPersona").val(request[0]['Direccion']);
  $("#SalarioPersona").val(request[0]['Salario']);
  $("#SexoPersona").val(request[0]['Sexo']);
  $("#MonedaPersona").val(request[0]['Moneda']);
  $("#typePeople").val(request[0]['typePeople']).trigger("change");
  $("#entrevistaPersona").val(request[0]['entrevista']).trigger("change");
  $("#resultentrevistaPersona").val(request[0]['resulentrevista']).trigger("change");
  


  var archivo = request[0]["cvpersona"];
  if(archivo){
    var extension = archivo.substring(archivo.lastIndexOf('.') + 1).toLowerCase();
  }


  if (extension == "pdf") {
   $("#quitarArchivoButton").show();

   $("#nombreArchivoLabel").html('<iframe src="' + (request[0]["cvpersona"].substring(3)) + '"></iframe>');
 }  else {
             // No es un archivo válido (PDF o Word)
   console.log("El archivo no es un PDF . No se encontro archivo.");
 }

 var nombreFoto = request[0]["Fotopersona"];
 $("#contenidoshowfoto").show();

 $("#mostrarimagen").attr("src", nombreFoto ? nombreFoto.substring(3) :'../images/defaulphoto.png');  

 $("#entrevistaPersona").val(request[0]['entrevista']);
 $("#resultentrevistaPersona").val(request[0]['resulentrevista']);
         ////
 $("#phatphoto").val(request[0]["Fotopersona"]);
 console.log(request[0]["cvpersona"])
 $("#phatdocument").val(request[0]["cvpersona"]);
}






function Registrar_personas(editando){
 editando = editando ?? false;
if(validation()){
  var formData= new FormData();

 //Cargando el pdf o word
   var fileInput = document.getElementById('archivoInput');
    for (var i = 0; i < fileInput.files.length; i++) {
    var file = fileInput.files[i];
    formData.append('files[]', file);
      }
    //cargando foto
    var foto = $("#seleccionararchivo")[0].files[0];
    formData.append('foto',foto);
    //los demas atributos
     formData.append('idPersona',$("#Idpersona").val());   
    formData.append('nombres',$("#NombrePersona").val().toUpperCase());
    formData.append('apellidos',$("#ApellidoPersona").val().toUpperCase());
    formData.append('correo',$("#correoPersona").val());
    formData.append('dni',$("#DniPersona").val());
    formData.append('telefono',$("#telefonoPersona").val());
    formData.append('direccion',$("#direccionPersona").val());
    formData.append('salario',$("#SalarioPersona").val());
    formData.append('sexo',$("#SexoPersona").val());
    formData.append('typePeople',$("#typePeople").val());
    formData.append('monedas',$("#MonedaPersona").val());
    formData.append('entrevista',$("#entrevistaPersona").val());
    formData.append('resulentrevistas',$("#resultentrevistaPersona").val());
    formData.append('phatphoto',$("#phatphoto").val());
    formData.append('phatdocument',$("#phatdocument").val());

    $.ajax({
     url: editando === '' ?  '../controller/Persona/ControllerRegisterPersona.php':'../controller/Persona/ControllerActualizarPersona.php',
     type:'post',
     data:formData,
     contentType:false,
     processData:false,
    success: function(respuesta){
       var request = JSON.parse(respuesta);

       if(request.status){        
        Swal.fire({icon: 'success',title: 'Éxito !!',text: 'Se registro con éxito.',showConfirmButton: false,timer: 1500})
          $("#Contenido_principal").load("personal/view_listar_personal.php");
       
      }else{
       return Swal.fire("Mensaje de error", request.msg, "error");
      }
                 
    }
   }).fail(function(jqXHR, textStatus, errorThrown) {
  if (jqXHR.status === 403) {
   
    return Swal.fire("Mensaje de error", "No Autorizado"+jqXHR.url+",Iniciar Sessión.", "error");
  } else {
    
    return Swal.fire("Mensaje de error", errorThrown, "error");
  }
});

}else{
  return Swal.fire("Mensaje de advertencia", "Llene los campos vácios, son obligatorios (*)", "warning");
}
}



function validation(){

    //VALIDACIONES
    $("#from input").each(function (index, element){
      var input=element;
      var id = input.getAttribute("id");

      if($("#"+id).val()=='') {

        if(input.id !='Idpersona' && input.id != 'archivoInput' && 
          input.id !='seleccionararchivo'&& 
          input.id !='phatphoto'&& 
          input.id !='phatdocument'){
         input.classList.add('form-control', 'is-invalid');//booleam=true;
  }
   }})


    $("#from select").each(function (index, element){
      var select=element;
      var id = select.getAttribute("id");

      if($("#"+id).val()=="") {
    select.classList.add('form-control', 'is-invalid');//booleam=true;
  }});


    var chkAsientos = document.getElementsByClassName("is-invalid");

    var asientos = [];
    for(i=0;i<chkAsientos.length;i++){

     asientos.push(chkAsientos[i]);
   }


   if (asientos.length==0) {

    return true;
  }else {
    return false;
  }

}






function onchangeInput() {
  var archivoseleccionado = document.querySelector("#seleccionararchivo");
  var archivos = archivoseleccionado.files;
  var imagenPrevisualizacion = document.querySelector("#mostrarimagen");

  // Si no hay archivos, salimos de la función y quitamos la imagen
  if (!archivos || !archivos.length) {
    imagenPrevisualizacion.src = "";
    return;
  }

  $("#contenidoshowfoto").show();

  // Tomamos el primer archivo para previsualizarlo
  var primerArchivo = archivos[0];

  // Convertimos el archivo a un objeto de tipo objectURL
  var objectURL = URL.createObjectURL(primerArchivo);

  // Establecemos el objectURL como la fuente de la imagen
  imagenPrevisualizacion.src = objectURL;
}





 //VALIDAR INPUT
 function updateValue(e) {
  //capturando todo el input del evento
   var id = e.target.getAttribute("id");
if(id !='archivoInput' && id !='seleccionararchivo' && id !='Idpersona'){
   var input=e.target;
 textContent = e.target.value;
 input.classList.add('form-control', 'is-invalid');
 //Input string
  if(textContent.length>=2){
    input.classList.replace('is-invalid', 'form-control');
    input.classList.add('form-control', 'is-valid');
  }
  return;

}

  
} 

function ValidadSelect(e){
   var select =e.target
     textContent = e.target.value;
     if (textContent=='') {
       select.classList.add('form-control', 'is-invalid');
     }else{
      select.classList.replace('is-invalid', 'form-control');
      select.classList.add('form-control', 'is-valid');
     }
}



   function Quitarfoto(){
  var archivoInput = document.getElementById('seleccionararchivo');
     $("#contenidoshowfoto").hide();
    if(archivoInput){
      archivoInput.value = '';
      $("#mostrarimagen").attr("src",'');
    }

    $("#phatphoto").val('');

 }

 function mostrarNombreArchivo() {
    var archivoInput = document.getElementById('archivoInput');
    var nombreArchivoLabel = document.getElementById('nombreArchivoLabel');
    var quitarArchivoButton = document.getElementById('quitarArchivoButton');

    if (archivoInput.files.length > 0) {
     
      var archivo = archivoInput.files[0];
      nombreArchivoLabel.innerHTML = '<a href="' + URL.createObjectURL(archivo) + '" target="_blank">' + archivo.name + '</a>';
      quitarArchivoButton.style.display = 'block';
    } else {
      nombreArchivoLabel.textContent = '';
      quitarArchivoButton.style.display = 'none';
      
    }
     $("#cvActual").text('');
  }

  function quitarArchivo() {
    var archivoInput = document.getElementById('archivoInput');
    var nombreArchivoLabel = document.getElementById('nombreArchivoLabel');
    var quitarArchivoButton = document.getElementById('quitarArchivoButton');
     $("#cvActual").html('');
    archivoInput.value = '';
    nombreArchivoLabel.textContent = '';
    quitarArchivoButton.style.display = 'none';
    $("#phatdocument").val('');
  }

 