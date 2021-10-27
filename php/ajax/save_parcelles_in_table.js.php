<?php
    include '../properties.php';

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

    $type = '';
    $count_parcelle             = $_POST["count_parcelle"];
    $p_insee                    = $_POST["p_insee"];
    $p_prefixe                  = $_POST["p_prefixe"];
    $p_section                  = $_POST["p_section"];
    $p_num                      = $_POST["p_num"];
    $pp_                        = $_POST["pp_"];
    $p_conv                     = $_POST["p_conv"];
    $p_acqu                     = $_POST["p_acqu"];
    $p_site                     = $_POST["p_site"];
    $p_ddg                      = $_POST["p_ddg"];
    $p_ore                      = $_POST["p_ore"];



$sql_check  = "SELECT id_unique FROM $parcelles WHERE SPLIT_PART(id_unique,'|',2) = '".$p_insee.$p_prefixe.$p_section.$p_num."' ";
$res        = pg_query($dbconn,$sql_check) or die ( pg_last_error());

//IF EXISTS --> UPDATE DATA
if (pg_num_rows($res) > 0 ) {
    //UPDATE DATA with docref et doc_gestion
    $type = 'update';
    $sql_update = "UPDATE $parcelles 
        SET 
        id_convention = '".$p_conv."' , 
        id_acquisition = '".$p_acqu."' , 
        id_doc_gestion = '".$p_ddg."', 
        id_ore = '".$p_ore."', 
        id_unique = '".$p_site."|'||'".$p_insee.$p_prefixe."'||lpad('".$p_section."',2,'0')||lpad('".$p_num."',4,'0')||'|'|| coalesce( nullif('".$p_acqu."','ø'), nullif('".$p_conv."','ø') , nullif('".$p_ore."','ø'), '' ) 
        WHERE SPLIT_PART(id_unique,'|',2) = '".$p_insee.$p_prefixe."'||lpad('".$p_section."',2,'0')||lpad('".$p_num."',4,'0') ;";
    echo (pg_query($dbconn,$sql_update) or die ( pg_last_error()));
} else {
    $type = 'insert';
    //INSERT NEW PARCELLE in table
    $sql_insert = "
        INSERT INTO $parcelles (
        id_unique, 
        id_group, 
        id_convention,
        id_acquisition,
        pp,
        type_group,
        id_doc_gestion,
        id_ore,
        doc_reference
        )
        VALUES 
        (
        '".$p_site."|'||'".$p_insee.$p_prefixe."'||lpad('".$p_section."',2,'0')||lpad('".$p_num."',4,'0')||'|'|| coalesce( nullif('".$p_acqu."','ø'), nullif('".$p_conv."','ø'), nullif('".$p_ore."','ø'),'' ),
        '".$p_site."',
        '".$p_conv."',
        '".$p_acqu."',
        '".$pp_."',
        'site',
        '".$p_ddg."',
        '".$p_ore."',
        coalesce( nullif('".$p_acqu."','ø'), nullif('".$p_conv."','ø'), nullif('".$p_ore."','ø'), '' )
        ) ";
    echo (pg_query($dbconn,$sql_insert) or die ( pg_last_error()));
    
}

//$id_parcelle = $p_insee.$p_prefixe.$p_section.$p_num;

//ferme la connexion a la BD
pg_close($dbconn);

//echo $count_parcelle.' : '.$id_insee.' enregistré'.' DDG : '.$p_ddg.'operation : '.$type.' '.$sql_update;





?>





