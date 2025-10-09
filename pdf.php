<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$data_ = $_POST['tableau'];
$name_ = $_POST['name'];

require_once '/var/www/html/majiic/vendor/autoload.php';

$html = '
<h1>Export des temps</h1>
<h2></h2>
<h3></h3>
<p></p>
'.$data_.'
<p>&nbsp;</p>';

//require_once __DIR__ . '/bootstrap.php';

$mpdf = new \Mpdf\Mpdf([
	'mode' => 'utf-8',
	'margin_left' => 10,
	'margin_right' => 10,
	'margin_top' => 20,
	'margin_bottom' => 20,
	'margin_header' => 10,
	'margin_footer' => 10,
    'default_font_size' => 8,
    'tempDir' => '/var/www/html/majiic/download/pdf'
]);

$mpdf->SetHTMLFooter('
<table width="100%">
    <tr>
        <td width="33%">{DATE j-m-Y}</td>
        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right;">Export de temps</td>
    </tr>
</table>');

$mpdf->SetDisplayMode('fullpage');

// Load a stylesheet
$stylesheet = file_get_contents('/var/www/html/majiic/bootstrap-5.0.0/css/bootstrap.css');



$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($html,2);

$mpdf->Output($name_, \Mpdf\Output\Destination::INLINE);


