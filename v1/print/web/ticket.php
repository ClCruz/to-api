<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Metzli/autoload.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");

    use Metzli\Encoder\Encoder;
    use Metzli\Renderer\PngRenderer;
//die('http://localhost/v1/print/web/ticket.intern?imthebossofme='.gethost()."&code=".$_REQUEST["code"]);
    $html = file_get_contents('http://localhost/v1/print/web/ticket_html?imthebossofme='.gethost()."&code=".$_REQUEST["code"]);
    // die($html);


// $content = ob_get_clean();
// //ob_end_clean();
// die($html);

$mpdf = new \Mpdf\Mpdf();
// $mpdf = new \Mpdf\Mpdf([
//     'debug' => true,
//     'allow_output_buffering' => true
// ]);
$mpdf->WriteHTML($html);
$mpdf->Output();
die();

?>