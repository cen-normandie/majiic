<?php
include '../properties.php';
$arr = array();

$term = $_POST["term"];
$schema = $_POST["schema"];
$table = $_POST["table"];
$field1 = $_POST["field1"];
$field2 = $_POST["field2"];

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", "
SELECT row_to_json(fc)
FROM (
	select 'features' as features, array_to_json(array_agg(t)) As entities
    from (
      select l.l_id as id, l.l_nom as nom, l_table_name as tablename
    from $schema.$table  l
	where $field1 ~* $1 or $field2 ~* $1 
	order by 1
    ) t
) fc
");
$result = pg_execute($dbconn, "sql", array($term));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
//echo json_encode($arr);
?>

