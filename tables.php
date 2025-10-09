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
<table class="bpmTopicC"><thead><tr class="headerrow"><th>Col and Row Header</th><td><p>Second column header</p></td><td>Third column header</td></tr></thead><tfoot><tr class="footerrow"><th>Col and Row Footer</th><td><p>Second column footer</p></td><td>Third column footer</td></tr></tfoot><tbody><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Charlotte Delaune</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Clément Blaise Duhaut</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Hana Ghlouci</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Hana Ghlouci</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Jérémy Lebrun</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Marie-Laure Séguin</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Marie-Laure Séguin</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Marie-Laure Séguin</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Marie-Laure Séguin</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Marie-Laure Séguin</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Mireille Pellevilain</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Mireille Pellevilain</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Mireille Pellevilain</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Mireille Pellevilain</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Mireille Pellevilain</th><td>2220</td><td>PRAT_2024</td></tr><tr><th>Mireille Pellevilain</th><td>2220</td><td>PRAT_2024</td></tr></tbody></table>
<p>&nbsp;</p>';

//require_once __DIR__ . '/bootstrap.php';
echo "</br>new pdf";
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
        <td width="33%" style="text-align: right;">My document</td>
    </tr>
</table>');

$mpdf->SetDisplayMode('fullpage');
echo "</br>file get contents";
// Load a stylesheet
$stylesheet = file_get_contents('/var/www/html/majiic/bootstrap-5.0.0/css/bootstrap.css');


echo "</br>write html";
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($html,2);

$mpdf->Output('test.pdf', \Mpdf\Output\Destination::INLINE);

echo "end";
