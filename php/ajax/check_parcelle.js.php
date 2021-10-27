<?php
    include '../properties.php';

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$out_put = '';




//$result = "SELECT idpar FROM ff_d14_2017.d14_2017_pnb10_parcelle WHERE idpar = '".$p_insee."' || '".$p_prefixe."' || lpad('".$p_section."',2,'0') || lpad('".$p_num."',4,'0');";


$result = pg_prepare($dbconn, "sql1", 
"SELECT idpar FROM $parcelles_cad WHERE idpar = $1 || lpad($2,3,'0') || lpad($3,2,'0') || lpad($4,4,'0');");
$result = pg_execute($dbconn, "sql1", array($_POST["p_insee"],$_POST["p_prefixe"],$_POST["p_section"],$_POST["p_num"]));
while($row = pg_fetch_row($result))
{
  $out_put =trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

if ($out_put == '') {
    echo 'non';
} else {
    echo 'oui';
}

?>





