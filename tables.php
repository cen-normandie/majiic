<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "test pdf";
require_once '/var/www/html/majiic/vendor/autoload.php';
echo "</br>require done";
$html = '
<h1>mPDF</h1>
<h2>Tables</h2>
<h3>CSS Styles</h3>
<p>The CSS properties for tables and cells is increased over that in html2fpdf. It includes recognition of THEAD, TFOOT and TH.<br />See below for other facilities such as autosizing, and rotation.</p>
<table border="1">
<tbody>
<tr>
<th>Header 1</th>
<th>Header 2</th>
<th>Header 3</th>
</tr>
<tr>
<td>Row 1 - Col 1</td>
<td>Row 1 - Col 2</td>
<td>Row 1 - Col 3</td>
</tr>
<tr>
<td>Row 2 - Col 1</td>
<td>Row 2 - Col 2</td>
<td>Row 2 - Col 3</td>
</tr>
</tbody></table>
<p>&nbsp;</p>';

//require_once __DIR__ . '/bootstrap.php';
echo "</br>new pdf";
$mpdf = new \Mpdf\Mpdf([
	'mode' => 'utf-8',
	'margin_left' => 32,
	'margin_right' => 25,
	'margin_top' => 27,
	'margin_bottom' => 25,
	'margin_header' => 16,
	'margin_footer' => 13,
    'tempDir' => '/var/www/html/majiic/download/pdf'
]);

$mpdf->SetDisplayMode('fullpage');
echo "</br>file get contents";
// Load a stylesheet
$stylesheet = file_get_contents('/var/www/html/majiic/bootstrap-5.0.0/css/bootstrap.css');


echo "</br>write html";
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($html,2);

$mpdf->Output('/var/www/html/majiic/download/pdf/test.pdf', \Mpdf\Output\Destination::INLINE);

echo "end";
