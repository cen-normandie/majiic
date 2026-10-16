<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 


"
INSERT INTO $parcelles_le ( geom, utilisateur, is_ddg )
values (
    st_transform(st_setsrid( $1::geometry ,4326),2154), $2, $3
);
"
);

$result = pg_execute($dbconn, "sql", array($_POST["geom"], $_POST["numerisateur"], $_POST["is_ddg"]));


pg_close($dbconn);
echo 'OK';
?>
