
var tlb_pointUser;
 var pointUser = {id:'',id_user:'' };

function getUsers(){

	tlb_pointUser = $('#tbl_pointUser').DataTable({
	    ...datatableConfig,
	    "pageLength": 10,
	    "destroy": true,
	    "processing": true,
	    "ajax": createAjaxConfigAll('#tbl_pointUser', 'checkpointUser', 'controllerGetUsers.php','GET',pointUser),
	    "columns": [
	    { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; } },
	    { "data": "iduser" },
	    { "data": null, "render": function (data, type, row) { return row.name + ' ' + row.lastname; } },
	   
	    {
	     "defaultContent": "<button type='button' class='addPoint btn btn-primary btn-sm'><i class='fa fa-plus-circle' ></i></button>&nbsp;<button  type='button' class='showPoint btn btn-secondary btn-sm'><i class='fa fa-eye'></i></button>"
	    }

	    ],
	    "language": idioma_espanol,
	    select: true
	  });
	  document.getElementById("tbl_pointUser_filter").style.display = "none";
	  $('input.global_filter').on('keyup click', function () {
	    filtercheckpoints();
	  });

	 tlb_pointUser.column(1).visible(false);

}

function filtercheckpoints() {
  $('#tbl_pointUser').DataTable().search($('#global_filter').val()).draw();
}

$('#tbl_pointUser').on('click', '.addPoint', function () {
  var data = tlb_pointUser.row(tlb_pointUser.row(this).child.isShown() ? this : $(this).parents('tr')).data();

  pointUser.id_user=data.iduser;
  $('#tbody_tabla_pointUser').empty();
  $('#div_select').show();
  $('#footer_checkpoint').show();
  $("#userPoint_title").text('Agregar para :' +data.name+' '+data.lastname );




  $.get('../controller/checkpointUser/controllerGetByUser.php', { id: data.iduser })
  .done(function (resultado) {
        var response = JSON.parse(resultado);
        if (response.status){
        	let {data}= response;
        	 showCheckpoint(data);
        } 
        else  Swal.fire("Mensaje de error", response.msg, "error");
        })
		  .fail(function (jqXHR, textStatus, errorThrown) {
		    if (jqXHR.status === 403) {
		      Swal.fire("Mensaje de error", "No Autorizado.", "error");
		    } else {
		      Swal.fire("Mensaje de error", errorThrown, "error");
		    }
		  });
});

$('#tbl_pointUser').on('click', '.showPoint', function () {
  var data = tlb_pointUser.row(tlb_pointUser.row(this).child.isShown() ? this : $(this).parents('tr')).data();

   pointUser.id_user=data.iduser;
   $('#tbody_tabla_pointUser').empty();
   $('#div_select').hide();
   $('#footer_checkpoint').hide();
    $("#userPoint_title").text('Vizualizando de:' +data.name+' '+data.lastname );


  $.get('../controller/checkpointUser/controllerGetByUser.php', { id: data.iduser })
  .done(function (resultado) {
        var response = JSON.parse(resultado);
        if (response.status){
        	let {data}= response;
        	 showCheckpoint(data);
        }  
        else  Swal.fire("Mensaje de error", response.msg, "error");
        })
		  .fail(function (jqXHR, textStatus, errorThrown) {
		    if (jqXHR.status === 403) {
		      Swal.fire("Mensaje de error", "No Autorizado.", "error");
		    } else {
		      Swal.fire("Mensaje de error", errorThrown, "error");
		    }
		  });
});


function showCheckpoint(data ){
      $('#tbody_tabla_pointUser').empty();
    	var html ="";
      if(data.length > 0) { 
        $.each(data, function(i, item) {

          html += "<tr id='"+item.id+"'>";  
          html += "<td>" + (i + Number(1)) + "</td>";
          html += "<td hidden>"+ item.id_checkpoint+"</td>";

          html += "<td>" + item.name + "</td>";
          html += "<td>" + item.latitude + "</td>";
          html += "<td>" + item.longitude + "</td>";
          html += "<td><button class='btn btn-sm' onclick='remove(this)'><em class='fa fa-times'></em></button></td>";
          html += "</tr>";
         });

         $("#tbody_tabla_pointUser").append(html)
        } else {
       
        Swal.fire({ toast: true, position: 'top-right', icon: 'info', title: 'Sin datos',
      text: 'No se encontro ningun registro .',showConfirmButton: false,timer: 2000});
       }     
	 
	}

	function remove(t) {
	   var row = t.parentNode.parentNode; // Obtener el elemento <tr> padre
	    var id = row.getAttribute('id'); // Obtener el código eliminando los primeros 3 caracteres ("key")
	    var id_user = pointUser.id_user;

			   if(id){
			      
			    	$.ajax({
			       url:'../controller/checkpointUser/ControllerRemoverPoint.php',
			        type:'get',
			         data:{id_user:id_user,id:id}
			        }).done(function(resultado){
			          var resquest = JSON.parse(resultado);
			        if (resquest.status) {
			           row.parentNode.removeChild(row);
			           getCheckpoints();
			          }else{
			          	return Swal.fire("Mensaje de error", "No se pudo completar el registro."+resquest.msg, "error");  
			          }
			        });
			   }else{
			      row.parentNode.removeChild(row);
			   }

	   var filas = $('#tbody_tabla_pointUser tr');
    filas.each(function (index) {
      $(this).find('td:first').text(index + 1);
    });
	}


function keyBoardAction(){
	var id_checkpoint = $("#checkpoint_id").val();
 filtrarCheckpoints(id_checkpoint);
}

	function selectCheckpoint(element){
   var id_checkpoint = element.value;
   if(id_checkpoint) filtrarCheckpoints(id_checkpoint);
}

function filtrarCheckpoints(id){
	
   var checkpoint = checkpointTem.filter(function(item) {
        return item.id_checkpoint === id;
    });
    if (checkpoint.length > 0) {
    	var html = "";
	    html += "<tr>";
	    html += "<td>1</td>";
	    html += "<td hidden>" + checkpoint[0].id_checkpoint + "</td>";
	    html += "<td>" + checkpoint[0].name + "</td>";
	    html += "<td>"+ checkpoint[0].latitude  +"</td>";
	    html += "<td>" + checkpoint[0].longitude + "</td>";
	    html += "<td><button class='btn btn-sm' onclick='remove(this)'><em class='fa fa-times'></em></button></td>";
	    html += "</tr>";
	    $('#tbody_tabla_pointUser').append(html);

    // Actualizar el número de filas en la tabla
	    var filas = $('#tbody_tabla_pointUser tr');
	    filas.each(function (index) {
	      $(this).find('td:first').text(index + 1);
	    });

     
    } else {
        console.log('No se encontraron usuarios con el ID especificado.');
    }
}

var checkpointTem;
async function getCheckpoints(id_checkpoint) {
    try {
        const resultado = await $.get('../controller/checkpointUser/controllerGetCheckpoint.php');
        const response = JSON.parse(resultado);
        if (response.status) {
            const { data } = response;
            checkpointTem = data;
            let cadena = "";
            if (data.length > 0) {
                for (let i = 0; i < data.length; i++) {
                    cadena += `<option value='${data[i].id_checkpoint}'${data[i].id_checkpoint == id_checkpoint ? " selected" : ""}>${data[i].name} ${data[i].longitude} ${data[i].latitude}</option>`;
                }
            } else {
                cadena += "<option value=''>NO SE ENCONTRARON REGISTROS</option>";
            }
            $("#checkpoint_id").html(cadena);
        } else {
            Swal.fire("Mensaje de error", response.msg, "error");
        }
    } catch (error) {
        Swal.fire("Mensaje de error", "Error al obtener los datos", "error");
    }
}


function registerTable(){
	 var registros = [];
		    $('#tbody_tabla_pointUser tr').each(function(index) {
		        var registro = {};
		        var $cells = $(this).find('td');
		        registro.id_checkpoint = $cells.eq(1).text(); 
		        registros.push(registro);
		    });

	    return registros;
}


function registerUserCheckpoint(btn){
	var points = registerTable();
  //var datos = JSON.stringify(registros);
    toggleIconChange(btn);
   
    $.post('../controller/checkpointUser/controllerPost.php', {points: points ,id_user:pointUser.id_user})
    .done(function(resultado) {
      var response = JSON.parse(resultado);
        if (response.status) {
          Swal.fire({ position: 'top-end',icon: 'success', title: 'Éxito !!', text: response.msg, showConfirmButton: false, timer: 1500 })
         clearInputRegister();
          getCheckpoints();
        } else { Swal.fire("Mensaje de error", response.msg, "error"); }
        
      })
    .fail(function(jqXHR, textStatus, errorThrown) {
      if (jqXHR.status === 403) {
        Swal.fire("Mensaje de error", "No Autorizado, Iniciar Sesión.", "error");
      } else {
        Swal.fire("Mensaje de error", errorThrown, "error");
      }
    });
 
 toggleIconRollback(btn);
}

function clearInputRegister(){
	 $('#checkpoint_id').val("").trigger("change");
	 $('#tbody_tabla_pointUser').empty();
	 pointUser.id_user='';
	 $('#div_select').hide();
   $('#footer_checkpoint').hide();
    $("#userPoint_title").text('');
}