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
      * {
    font-size: 12px;
    font-family: "Times New Roman";
}

td,
th,
tr,
table {
    border-top: 1px solid black;
    border-collapse: collapse;
    width: 100%;
}

td.description,
th.description {
    width: 95px;
    max-width: 95px;
}

td.quantity,
th.quantity {
    width: 60px;
    max-width: 60px;
    word-break: break-all;
}

td.price,
th.price {
    width: 60px;
    max-width: 60px;
    word-break: break-all;
}
th.hors_ {
    width: 90px;
    max-width: 90px;
    word-break: break-all;
}

.centered {
    text-align: center;
    align-content: center;
}

.ticket {
    justify-content: center;
    width: 455px;
    max-width: 455px;
    display:flex;
}

img {
    max-width: 65px;
    width: 65px;
}
.negrita {
 font-weight: bold;
 }

@media print {
    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
}
    </style>
    </head>
    <body>';


   
    // Añadir la estructura HTML
    $html .= '<div class="ticket">
            <img src="' . $escudoBase64 . '" >
            <p class="centered">'.$company['name'].'
                <br>Nombres: '.$persona['Nombre'].'
                <br>Apellidos: '.$persona['Apellidos'].'
                <br>Dni(Cédula): '.$persona['Dni'].'</p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">N°</th>
                        <th class="description">Fechas pagados</th>
                        <th class="price">Tipo</th>
                        <th class="hors_">Horas</th>
                        <th class="price">Monto('.$company['currency'].')</th>
                    </tr>
                </thead>
                <tbody>';


              
                foreach ($data as $index => $record) {
                    $html .= '<tr>
                    <td class="quantity">' . ($index + 1) . '</td>';

                    if (isset($record['type']) && $record['type'] == 'DAY') {

                        $totalDay += floatval($record['montoP']);
                        $html .= '<td class="description">' . $record['fechasPagados'] . '</td>
                        <td class="description">' . $record['type'] . '</td>
                        <td class="description">' . $record['extrahours'] . '</td>
                        <td class="description">' . number_format($record['montoP'], 2) . '</td>';
                        

                    } elseif (isset($record['type']) && $record['type'] == 'ADL') {

                        $totalAdl += floatval($record['montoP']);
                        $html .= '<td class="description">' . $record['fechasPagados'] . '</td>
                        <td class="description">' . $record['type'] . '</td>
                        <td class="description">' . $record['extrahours'] . '</td>
                        <td class="description">' . number_format($record['montoP'], 2) . '</td>';

                    } elseif (isset($record['type']) && $record['type'] == 'EXT') {

                        $totalExt += floatval($record['montoP']);
                        $html .= '<td class="description">' . $record['fechasPagados'] . '</td>
                        <td class="description">' . $record['type'] . '</td>
                        <td class="description">' . $record['extrahours'] . '</td>
                        <td class="description">' . number_format($record['montoP'], 2) . '</td>';
                    }

                    $html .= '</tr>';
                }

            $html .= '<tr class="negrita">
                         <td class="quantity" colspan="3"></td>
                        <td class="" >Total jornadas (DAY) </td>
                        <td class="">'.number_format($totalDay, 2).' '.$company['currency'].'</td>
                    </tr>
                    <tr class="negrita">
                         <td class="quantity" colspan="3"></td>
                        <td class="" >Total adelantos (ADL) </td>
                        <td class="">'.number_format($totalAdl, 2).' '.$company['currency'].'</td>
                    </tr>
                    <tr class="negrita">
                         <td class="quantity" colspan="3"></td>
                        <td class="" >Total de horas extras (EXT) </td>
                        <td class="">'.number_format($totalExt, 2).' '.$company['currency'].'</td>
                    </tr>
                    <tr class="negrita">
                        <td class="quantity" colspan="3"></td>
                        <td class="" >Total recibido </td>';
                        $total = ($totalDay + $totalExt) - $totalAdl;
                        $total = isset($total) ? $total : 0;
                        $html .= '<td class="">'.number_format($total, 2).' '.$company['currency'].'</td>
                    </tr>
                </tbody>
            </table>
            <p class="centered">Gracias por su preferencia!
                <br>'.date('Y-m-d H:i:s').'</p>
        </div>';
    

   

    // Cerrar las etiquetas body y html
    $html .= '</body></html>';

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
