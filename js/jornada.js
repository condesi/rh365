
var tablejornada;
var idsPersonasSeleccionadas = []; // Variable para almacenar los IDs de las filas seleccionadas

function ListarPersonasOff() {
  tablejornada = $('#tabla_jornadas').DataTable({
    "ordering": true,
    "bLengthChange": false,
    "scrollCollapse": true,
    "searching": {
      "regex": true
    },
    "lengthMenu": [
      [10, 20, 50, 100, -1],
      [10, 20, 50, 100, "All"]
    ],
    "pageLength": 8,
    "destroy": true,
    "processing": true,
    "ajax": {
      "url": "../controller/jornada/ControllerGetPersonasOff.php",
      "type": 'POST'
    },
    "columns": [
      { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
      { "data": "idpersona" },
      {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
      
      { "data": "Dni" },
      { "data": "entrevista" },
      { "data": "resulentrevista" },
      {
        "data": null,
        "render": function (data, type, row) {
          var isChecked = idsPersonasSeleccionadas.includes(row.idpersona) ? "checked" : "";
          return '<div class="toggle-flip" style="margin-top: -10px"><label><input type="checkbox" class="select-checkbox" value="' + row.idpersona + '" ' + isChecked +
           '><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span></label></div>';
        }
      },
    ],
    "language": idioma_espanol,
    select: true,
    stateSave: true, // Guardar el estado de la tabla
    stateDuration: -1 // Mantener el estado guardado de forma indefinida
  });

  // Agregar evento de clic a las checkboxes
  $('#tabla_jornadas').on('click', '.select-checkbox', function () {
    var idPersona = $(this).val();
    if ($(this).is(':checked')) {
      idsPersonasSeleccionadas.push(idPersona);
    } else {
      var index = idsPersonasSeleccionadas.indexOf(idPersona);
      if (index !== -1) {
        idsPersonasSeleccionadas.splice(index, 1);
      }
    }
  });

  document.getElementById("tabla_jornadas_filter").style.display = "none";
  $('input.global_filter').on('keyup click', function() {
    filterGlobal();
  });
  tablejornada.column(1).visible( false );
}

function filterGlobal() {
  $('#tabla_jornadas').DataTable().search($('#global_filter').val(), ).draw();
}

function Registrar_Jornada(){
  var typeShift =$("#typeShift").val();
  var fechaAsisten =$("#fechaInicio").val();
 // Obtener el elemento de entrada por su ID
  var inputElement = document.getElementById("fechaInicio");
  var isValid = inputElement.value.trim() !== "";
  inputElement.classList.remove(isValid ? "is-invalid" : "is-valid");
  inputElement.classList.add(isValid ? "is-valid" : "is-invalid");

  if (fechaAsisten.length == 0){
    return Swal.fire("Mensaje de adnertencia", "Ingrese la fecha de inicio .", "warning");
    
  }
  if (typeShift.length == 0){
    return Swal.fire("Mensaje de adnertencia", "Ingrese Tipo de turno .", "warning");
    
  }
  if (typeShift != 'Normal'){
    return Swal.fire("Mensaje de adnertencia", "El sistema en v3. solo esta disponible turno <strong>'Normal'</strong>", "warning");
    
  }
  if ( idsPersonasSeleccionadas.length==0){
    return Swal.fire("Mensaje de adnertencia", " seleccione al menos una persona.", "warning");
    
  }


     var idsPersonas = idsPersonasSeleccionadas.toString();
    $.ajax({
      url:'../controller/jornada/ControllerInicioJornada.php',
      type:'POST',
      data:{fechaAsisten:fechaAsisten,typeShift:typeShift,idsPersonas:idsPersonas}
     }).done(function(resultado){
     	 var request = JSON.parse(resultado);
      
       if (request.data==1) {
         inputElement.classList.remove(isValid ? "is-invalid" : "is-valid");
          tablejornada.ajax.reload();
          Swal.fire({icon: 'success',title: 'Éxito !!',text: 'Se Registro corectamente.',showConfirmButton: false,timer: 1500})
          
       }else{

        return Swal.fire("Mensaje de error", "No se pudo completar el registro."+request.msg, "error");  
        }
   
     });
}