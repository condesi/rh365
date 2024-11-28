<?php
require '../../public/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Recibir los datos del cuerpo de la solicitud POST
$record = json_decode(file_get_contents("php://input"), true);
$company = $record["company"];
$data = $record['data']; // Contiene los resultados filtrados
$persona= $data[0];
date_default_timezone_set('America/New_York'); 
$money='S/.';
$totalDay = 0;
$totalAdl = 0;
$totalExt = 0;
$logo = "../../images/logo.png";
$escudoBase64 = "data:image/png;base64," . base64_encode(file_get_contents($logo));

try {
    // Crear un objeto de opciones para Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    // Inicializar Dompdf
    $dompdf = new Dompdf($options);

    // Crear el contenido del PDF con los datos recibidos
    $html = '<html>
    <head>
        <style type="text/css">
     

body {
    margin: 0
}

table {
    border-collapse: collapse;
    border-spacing: 0
}
td,
th {
    padding: 0
}



body {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    margin: 6rem auto 0;
    max-width: 800px;
    background: white;
   
    padding: 2rem

     font-size: 12px;
    box-sizing: border-box
}

*,
*:before,
*:after {
    -moz-box-sizing: inherit !important;
    box-sizing: inherit !important
}

a {
    color: #009688;
    text-decoration: none
}

p {
    margin: 0
}

.row {
    position: relative;
    display: block;
    width: 100%;
    font-size: 0 !important;
}

.col,
.logoholder,
.me,
.info,
.bank,
[class*="col-"] {
    vertical-align: top;
    display: inline-block;
    font-size: 1rem;
    padding: 0 1rem;
    min-height: 1px
}

.col-4 {
    width: 25%
}

.col-3 {
    width: 33.33%
}

.col-2 {
    width: 50%
}

.col-2-4 {
    width: 75%
}

.col-1 {
    width: 100%
}

.text-center {
    text-align: center
}

.text-right {
    text-align: right
}

header {
    margin: 1rem 0 0;
    padding: 0 0 2rem 0;
    border-bottom: 3pt solid #1fe175
}

header p {
    font-size: .9rem
}

header a {
    color: #000
}

.logo {
    margin: 0 auto;
    width: auto;
    height: auto;
    border: none;
    fill: #009688
}

.logoholder {
    width: 14%
}

.me {
    width: 30%
}

.info {
    width: 30%
}

.bank {
    width: 26%
}

.section {
    margin: 2rem 0 0
}

.client {
    margin: 0 0 3rem 0
}

h1 {
    margin: 0;
    padding: 0;
    font-size: 2rem;
    color: #0a3aef
}

.invoice_detail{
    border: solid 1px #009688;
    padding:10px;
    height:25px;
    text-align:center
}

.invoicelist-body {
    margin: 1rem
}

.invoicelist-body table {
    width: 100%
}

.invoicelist-body thead {
    text-align: left;
    border-bottom: 1pt solid #666
}

.invoicelist-body td,
.invoicelist-body th {
    position: relative;
    padding: 1rem
}

.invoicelist-body tr:nth-child(even) {
    background: #ccc
}

.invoicelist-body tr:hover .removeRow {
    display: block
}

.invoicelist-body input {
    display: inline;
    margin: 0;
    border: none;
    width: 80%;
    min-width: 0;
    background: transparent;
    text-align: left
}

.invoicelist-footer {
    margin: 1rem
}

.invoicelist-footer table {
    float: right;
    width: 25%
}

.invoicelist-footer table td {
    padding: 1rem 2rem 0 1rem;
    text-align: right
}

.invoicelist-footer table tr:nth-child(2) td {
    padding-top: 0
}

.invoicelist-footer table #total_price {
    font-size: 2rem;
    color: #009688
}

.note {
    margin: 1rem
}

.hidenote .note {
    display: none
}

.note h2 {
    margin: 0;
    font-size: 1rem;
    font-weight: bold
}
    </style>
    </head>
    <body class="hidetax hidenote hidedate">';


   
    // Añadir la estructura HTML
    $html .= '<header class="row">
  <div class="logoholder text-center">
    <img src="assets/img/logo.png">
  </div><!--.logoholder-->

  <div class="me">
    <p>
      <strong>RH365</strong><br>
      sistema de gestion<br>
      de recursos humanos.<br>
      
    </p>
  </div><!--.me-->

  <div class="info">
    <p>
      Web: <a href="https://danny365.com/">Portal web</a><br>
      E-mail: <a href="">danny@gmail.com</a><br>
      Tel: +51 964706345<br>
      
    </p>
  </div><!-- .info -->

  <div class="bank">
    <p>
      Ruc: <br>
      Pais:Perú <br>
      Twitter: @Ncerna <br>
      
    </p>
  </div><!--.bank-->

</header>';

$html .= '<div class="row section">

  <div class="col-2">
    <h1>Factura</h1>
  </div><!--.col-->

  <div class="col-2 text-right details">
    <p>
      Fecha: <br>
      Factura #: <br>
     Hora: 
    </p>
  </div><!--.col-->
  
  
  
  <div class="col-2">
    

    <p class="client">
      <strong>Facturar a</strong><br>
      Cerna Espinoza Nimer<br>  
    Lima-Tingo maria<br>
    Persona empleador
    </p>
  </div><!--.col-->
  
  
  <div class="col-2">
   
   <p class="client">
      <strong>Facturar a</strong><br>
      Cerna Espinoza Nimer<br>  
    Lima-Tingo maria<br>
    Persona empleador
    </p>
  </div><!--.col-->

  

</div>';

$html .= '<div class="row section" style="margin-top:-1rem">
<div class="col-1">
  <table style="width:100%">
    <thead>
  <tr class="invoice_detail">
      <th width="25%" style="text-align">Facturador</th>
       <th width="25%">Orden</th>
      <th width="20%">Enviar por</th>
      <th width="30%">Términos y condiciones</th>
   </tr> 
    </thead>
    <tbody>
  <tr class="invoice_detail">
      <td width="25%" style="text-align">John Doe</td>
       <td width="25%">#PO-2020 </td>
      <td width="20%">RH365</td>
      <td width="30%">Pago al contado</td>
   </tr>
  </tbody>
  </table>
</div>

</div><!--.row-->

<div class="invoicelist-body">
  <table>
    <thead>
      <tr><th width="5%">N°</th>
      <th width="60%">Descripción</th>
      
      <th width="10%">Cant.</th>
      <th width="15%">Precio</th>
      
      <th width="10%">Total</th>
    </tr></thead>
    <tbody>
      <tr>
        <td width="5%"><span>12345</span></td>
        <td width="60%"><span>Descripción</span></td>
        <td class="amount">2</td>
        <td class="rate">5</td>
        
        <td class="sum">99.00</td>
      </tr>
    <tr>
      <td> <span>12345</span></td>
        <td><span >Descripción</span></td>
        <td class="amount">2</td>
        <td class="rate">99</td>
       
        <td class="sum">99.00</td>
      </tr>
        
        </tbody>
  </table>
 
</div><!--.invoice-body-->

<div class="invoicelist-footer">
  <table>
    <tbody>
    <tr>
      <td><strong>Total:</strong></td>
      <td id="total_price">396.00</td>
    </tr>
  </tbody></table>
</div>
<footer class="row">
  <div class="col-1 text-center">
    <p class="notaxrelated">El monto de la factura no incluye el impuesto sobre las ventas.</p>
    
  </div>
</footer>

</body>
</html>';



    // Cargar el HTML en Dompdf
    $dompdf->loadHtml($html);

    // Establecer el tamaño del papel y la orientación
    $dompdf->setPaper('A4', 'portrait');

    // Renderizar el PDF
    $dompdf->render();

    // Establecer el encabezado para indicar que es un archivo PDF
    header('Content-Type: application/pdf');

    // Enviar el archivo PDF al navegador
    echo $dompdf->output();

    // Terminar la ejecución del script para evitar la adición de cualquier contenido adicional
    exit();
} catch (Exception $e) {
    echo 'Error al generar el PDF: ', $e->getMessage();
    exit();
}
