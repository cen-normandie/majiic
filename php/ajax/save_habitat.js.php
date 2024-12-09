<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST_FG port=$PORT_FG dbname=$DBNAME_FG user=$LOGIN_FG password=$PASS_FG")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 


"
INSERT INTO $tmp (geom, code, lb, typo ,observateur )
values (
    $1, $2, $3, $4, $5
);
"
);

$result = pg_execute($dbconn, "sql", array($_POST["geom"], $_POST["lb_code"], $_POST["lb_lib"], $_POST["lb_typo"],$_POST["observateur"] ));


pg_close($dbconn);
echo 'OK';
?>
