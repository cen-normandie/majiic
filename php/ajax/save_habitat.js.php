<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST_FG port=$PORT_FG dbname=$DBNAME_FG user=$LOGIN_FG password=$PASS_FG")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 


"
INSERT INTO $tmp (geom, code, lb, typo ,observateur, typo_b, code_b, lb_b, typo_c, code_c, lb_c )
values (
    $1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11
);
"
);

$result = pg_execute($dbconn, "sql", array($_POST["geom"], $_POST["lb_code"], $_POST["lb_lib"], $_POST["lb_typo"],$_POST["observateur"],
$_POST["lb_code_b_"], $_POST["lb_lib_b_"], $_POST["lb_typo_b_"],
$_POST["lb_code_c_"], $_POST["lb_lib_c_"], $_POST["lb_typo_c_"]));


pg_close($dbconn);
echo 'OK';
?>
