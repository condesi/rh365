

async function DashboardSisteme() {
  try {
    const respuesta = await fetch('../controller/dashboard/ControllerDashboardInfo.php');
    const resultado = await respuesta.json();
    if(resultado.auth){
      $("#TotasPersonadashboard").text(resultado.personas);
      $("#totaldetrabajadoresdash").text(resultado.trabajo);
      $("#totaldeadelantosdash").text(resultado.adelantos);
      $("#totaldehorasextrasdash").text(resultado.horas);
      GraficoBarrasHorasExtra2d(resultado.hextras);
      GraficoBarrasAdelantos2d(resultado.dataadelantos);
    }else  Swal.fire("Mensaje de error", errorThrown, "error");

        // Hacer algo con la respuesta del servidor
      } catch (error) {
        if (error instanceof SyntaxError) {
          console.error('Error al analizar JSON en la respuesta del servidor.');
        } else {
          console.error('Error en la petición:', error);
        }
      }
    }


function GraficoBarrasAdelantos2d(data){

       // Obtener el elemento canvas
     var canvas = document.getElementById("barCharadelantos");

      // Obtener el contexto 2D del canvas
      var ctx = canvas.getContext("2d");

      // Crear un array con las etiquetas de los meses
     var labels = data.map(function (dato) {
     // Obtener el año y mes de la fecha
     var [anio, mes] = dato.mes.split("-");
     // Crear un objeto Date con el año y mes
     var fecha = new Date(anio, parseInt(mes) - 1); // Restar 1 al mes ya que los meses en JavaScript van de 0 a 11
     // Obtener el nombre del mes
     var nombreMes = fecha.toLocaleString("es", { month: "long" });

     return nombreMes;
     });

     // Crear un array con los valores de los montos
       var value = data.map(function (dato) {
         return dato.Monto;
       });

     // Crear el objeto de configuración del gráfico
      var config = {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Adelantos",
              data: value,
              backgroundColor: "rgba(255, 99, 132, 0.6)",
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
          plugins: {
            tooltip: {
              enabled: true,
              callbacks: {
                label: function (context) {
                  var datasetLabel = "total adelantos s/.";
                  var adelantos = context.parsed.y;
                  return datasetLabel + ": " + adelantos ;
                },
              },
            },
          },
        },

      };

      // Crear y renderizar el gráfico
      var grafico = new Chart(ctx, config);
}


function GraficoBarrasHorasExtra2d(data){

     // Obtener el elemento canvas
          var canvas = document.getElementById("barCharextras");

          // Obtener el contexto 2D del canvas
          var ctx = canvas.getContext("2d");

           // Crear un array con las etiquetas de los meses
        var labels = data.map(function (dato) {
        // Obtener el año y mes de la fecha
        var [anio, mes] = dato.mes.split("-");
        // Crear un objeto Date con el año y mes
        var fecha = new Date(anio, parseInt(mes) - 1); // Restar 1 al mes ya que los meses en JavaScript van de 0 a 11
        // Obtener el nombre del mes
        var nombreMes = fecha.toLocaleString("es", { month: "long" });

        return nombreMes;
      });

      // Crear un array con los valores de las horas extras
      var valores = data.map(function (dato) {
        return dato.valor;
      });

      // Crear el objeto de configuración del gráfico
      var config = {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Horas Extras",
              data: valores,
              backgroundColor: "rgba(75, 192, 192, 0.6)",
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
          plugins: {
            tooltip: {
              enabled: true,
              callbacks: {
                label: function (context) {
                  var datasetLabel = "total Horas Extras";
                  var horasExtras = context.parsed.y;
                  var total = data[context.dataIndex].total;

                  return datasetLabel + ": " + horasExtras + " (Total: S/. " + total + ")";
                },
              },
            },
          },
        },
      };

      // Crear y renderizar el gráfico
      var grafico = new Chart(ctx, config);
}


