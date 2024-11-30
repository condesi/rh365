
var tablehertra;
function Listar_Horas_Extras(){
  tablehertra=$('#tabla_horas_extra').DataTable({
	    "ordering": false,
	    "bLengthChange": false,
	    "scrollCollapse": true,
	    "searching": {
	      "regex": true
	    },
	    "lengthMenu": [
	    [10, 20, 50, 100, -1],
	    [10, 20, 50, 100, "All"]
	    ],
	    "pageLength": 10,
	    "destroy": true,
	    "async": false,
	    "processing": true,
	    "ajax": {
	      "url": "../controller/hextra/controllerGetHorasExtra.php",
	      type: 'POST' 
	    },
	    "columns": [
	    { "data": null,"render": function (data, type, row, meta) {return meta.row + 1; }},
	    { "data": "idpersona"},
	     {"data": null, "render": function (data, type, row) {return row.Nombre + ' ' + row.Apellidos;}},
	     
	    { "data": "Dni"},
	    { "data": "Moneda"},
	    { "data": "Salario"},
	   
	    {
	      "data": "Estado",
	      render: function(data, type, row) {
	        if (data == 'Activo') {
	          return "<span class='badge badge-pill badge-primary'>" + data + "</span>";
	        } else {
	          return "<span class='badge badge-pill badge-warning'>" + data + "</span>";
	        }
	      }
	    }

	    ,{
	      "defaultContent": ""+
	      "<button  type='button' class='masextra btn btn-primary btn-sm'><i class='fa fa-plus-circle' title='+'></i></button>"
	      

	    }],
	    "language": idioma_espanol,
	    select: true
	  });
	  document.getElementById("tabla_horas_extra_filter").style.display = "none";
	  $('input.global_filter').on('keyup click', function() {
	    filterGlobal();
	  });
	  tablehertra.column( 1 ).visible( false );
	}
function filterGlobal() {
	  $('#tabla_horas_extra').DataTable().search($('#global_filter').val(), ).draw();
}

$('#tabla_horas_extra').on('click', '.masextra', function() {
	  var data = tablehertra.row($(this).parents('tr')).data();

	  if (tablehertra.row(this).child.isShown()) {
	    var data = tablehertra.row(this).data();
	  }
	  var idpersona= data.idpersona;


	   $("#modalInscripciones").modal({
	      backdrop: 'static',
	        keyboard: false
	    })

	   //Subiendo al vista datos//
	    $("#idempleadoextra").val(idpersona);
	    $("#salarioactual").val(data.Salario);

	    $("#labelSalarioacutal").html(data.Salario);
	     $("#tbody_tabla_detall").html('');
	    $('#modalInscripciones').modal('show');

	    Extraer_horas_Extra_Personal(idpersona);

	});

function Extraer_horas_Extra_Personal(idpersona){

	$.ajax({
	    url:'../controller/hextra/ControllerExtraerHorasPersona.php',
	     type:'POST',
	           data:{idpersona:idpersona}
	      }).done(function(resultado){
	        var resquest = JSON.parse(resultado);

	      	var html ="";
	        if(resquest.data.length > 0) { 
	          $.each(resquest.data, function(i, item) {
	  
	            html += "<tr id='"+item.idhextra+"'>";  
	            html += "<td>" + (i + Number(1)) + "</td>";
	            html += "<td hidden>0</td>";

	            html += "<td>" + item.fecharegistro + "</td>";
	            html += "<td>" + item.hextra + "</td>";
	            html += "<td>" + item.total + "</td>";
	            html += "<td>" + item.year + "</td>";
	            html += "<td><button class='btn btn-sm' onclick='remove(this)'><em class='fa fa-times'></em></button></td>";
	            html += "</tr>";
	           });

	           $("#tbody_tabla_detall").append(html)
	          } else {
	  
	         }
	     
	     
	 });
	}

function Addtd_table(){
 var currentDate = new Date(); // Obtener la fecha actual
 var fecha = currentDate.toLocaleDateString(); // Convertir la fecha en formato de cadena
 var year = currentDate.getFullYear(); // Obtener el año actual
 var html ="";
	 html += "<tr id='key' >";  
	 html += "<td>✔</td>";
	 html += "<td hidden>0</td>";
	 html += "<td>" + fecha + "</td>";
	 html += "<td><input class='form-control form-control-sm' id='cantidad' type='number' oninput='calcularTotal(this)'></td>";
	 html += "<td id='resultado'><input class='form-control form-control-sm' id='total' type='number' disabled ></td>";
	 html += "<td>" + year + "</td>";
	 html += "<td><button class='btn btn-sm' onclick='remove(this)'><em class='fa fa-times'></em></button></td>";
	 html += "</tr>";
 $('#tbody_tabla_detall').append(html);
	  
}

function remove(t) {
	   var row = t.parentNode.parentNode; // Obtener el elemento <tr> padre
	    var idxestra = row.getAttribute('id'); // Obtener el código eliminando los primeros 3 caracteres ("key")
	    var idempleado= $("#idempleadoextra").val();

	   if( idxestra != "key" ){
	      
	    	$.ajax({
	       url:'../controller/hextra/ControllerRemoverExtraPersonal.php',
	        type:'POST',
	         data:{idempleado:idempleado,idxestra:idxestra}
	        }).done(function(resultado){
	          var resquest = JSON.parse(resultado);
	        if (resquest.data==1) {
	          row.remove(); // Eliminar la fila del DOM
	          }else{
	          	return Swal.fire("Mensaje de error", "No se pudo completar el registro."+resquest.msg, "error");  
	          }
	        });
	   }else{
	     row.remove(); // Eliminar la fila del DOM
	   }
	}
function calcularTotal(input) {
	  var cantidad = parseFloat(input.value); // Obtener el valor ingresado en el input de cantidad
	  var totalInput = input.parentNode.nextElementSibling.querySelector('#total'); // Obtener el input de total
	  var salario= $("#salarioactual").val();


	  if (!isNaN(cantidad)) {
        
        //capturando el check seleccionado
	  	var checkboxes = document.querySelector('input[type="checkbox"]:checked');
        var porcentaje = checkboxes ? parseFloat(checkboxes.value) : 0; // Captura el valor del checkbox o toma 0 por defecto
        if (isNaN(porcentaje)) {
         porcentaje = 0; // Toma 0 si el valor del checkbox no es un número válido
        }
        //Seccion de operacione//////
      var saldo = salario/23.83;//cálculo de salraio ==>diario
      var precioporhora = saldo/8; //precio por hora ==entonces cuanto gana por dia trabajando 8 h diarios

	  var total = cantidad * precioporhora; // cantidad ingresado por precio de hora
	  
	  var procentajetotal= total*porcentaje; //aplicacion de procentaje seleccionada

	  var nuevototal= new Number(total)+ Number(procentajetotal);
      
	    totalInput.value = nuevototal.toFixed(2); // Mostrar el resultado en el input de total
	  } else {
	    totalInput.value = ''; // Limpiar el valor del input de total si la cantidad no es un número válido
	  }
	  /////fin de seccion de operaciones//////
	}

	 function toggleCheckbox(checkbox) {
     var checkboxes = document.getElementsByName(checkbox.name);
      for (var i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i] !== checkbox) {
        checkboxes[i].checked = false;

      }
     }

  calcularMultiplicacion(checkbox);
   
 }

function calcularMultiplicacion(checkbox) {
  var salario = $("#salarioactual").val(); // Obtener el valor del salario actual
  var filas = document.querySelectorAll("#tabadetallesid tbody tr"); // Seleccionar todas las filas de la tabla

  filas.forEach(function(fila) {
    var checkboxValue = parseFloat(checkbox.value); // Obtener el valor del checkbox como número
    var valorinput = fila.querySelector("#cantidad"); // Obtener el elemento de cantidad en la fila actual
    var totaltd = fila.querySelector("#total"); // Obtener el elemento de total en la fila actual

    var totalhoras = valorinput !== null ? (valorinput.value ? parseFloat(valorinput.value) : 0) : 0;
    // Si valorinput no es nulo, obtener su valor y convertirlo a número. Si es nulo, asignar 0 a totalhoras

    var porcentaje = checkbox.checked ? checkboxValue : 0; // Si el checkbox está marcado, asignar su valor, de lo contrario, asignar 0
    var saldo = salario / 23.83; // Cálculo del saldo
    var precioporhora = saldo / 8; // Cálculo del precio por hora
    var total = totalhoras * precioporhora; // Cálculo del total
    var procentajetotal = total * porcentaje; // Cálculo del total con el porcentaje aplicado
    var nuevototal = total + procentajetotal; // Cálculo del nuevo total

    if (totaltd !== null) {
      totaltd.value = nuevototal.toFixed(2); // Establecer el valor de totaltd con el nuevo total, redondeado a 2 decimales
    }
  });
}

function guardarValores() {
	 var idpesona= $("#idempleadoextra").val();
	 var valores = [];

	  $('#tbody_tabla_detall tr').each(function() {
	    var cantidad = parseFloat($(this).find('#cantidad').val());
	    var total = parseFloat($(this).find('#total').val());

	    if (!isNaN(cantidad) && !isNaN(total)) {
	      valores.push({
	      	idpesona:idpesona,
	        cantidad: cantidad,
	        total: total
	      });
	      }
	     });

	      if(valores?.length==0){ return Swal.fire("Mensaje de advertencia", "No hay nada para registrar.", "warning");}

	       $.ajax({
	         url:'../controller/hextra/ControllerRegisterExtraPersonal.php',
	          type:'POST',
	           data:{valores: JSON.stringify(valores)}
	           }).done(function(resultado){
	             var resquest = JSON.parse(resultado);

	           if (resquest.data == 1) {
	           	  $('#modalInscripciones').modal('hide');
	        	 Swal.fire({icon: 'success',title: 'Éxito !!',text: resquest.msg,showConfirmButton: false,timer: 1500})
	          }else{
	          	return Swal.fire("Mensaje de error", "No se pudo completar el registro."+resquest.msg, "error");  
	          }
	 });
	}


