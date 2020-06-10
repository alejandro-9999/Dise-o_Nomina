
<?php 
require_once 'dompdf/autoload.inc.php';
                
use Dompdf\Adapter\CPDF;      
use Dompdf\Dompdf;
use Dompdf\Exception;

$dompdf = new Dompdf();
session_start();
$dompdf->loadHtml($_SESSION['variable']);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();



           
?>
