<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOSTPRAM port=$PORTPRAM dbname=$DBNAMEPRAM user=$LOGINPRAM password=$PASSPRAM")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
    "
    with ca1 as (
        select
        \"C_OBSV\" as id_obs,
        count(*) as nb_car
        from $car_pram
        where \"C_DATE\" > 1735689601
        group by 1
        order by 2 desc
    ),
    la1 as (
        select
        \"L_OBSV\" as id_obs,
        count(*) as nb_loc
        from $loc_pram
        where \"L_DATE\" > 1735689601
        group by 1
        order by 2 desc
    )
    SELECT array_to_json(array_agg(row_to_json(t))) FROM
    (
        select mail_u, nom_u, profil_u, id_observateur_pram, id_structure_pram, nom_structure_pram,
        coalesce(c.nb_car,0) as nb_car,
        coalesce(l.nb_loc,0) as nb_loc
        from $spop_users
        left join ca1 c on c.id_obs = id_observateur_pram::int
        left join la1 l on l.id_obs = id_observateur_pram
        where c.id_obs is not null or l.id_obs is not null
        order by 8 desc
    ) t
    "
);


$result = pg_execute($dbconn, "sql", array()) or die ('Connexion impossible :'. pg_last_error());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>