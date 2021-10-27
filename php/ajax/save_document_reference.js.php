<?php
    include '../properties.php';

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$doc = $_POST["type_doc_"];

if ($doc == 'convention') {
    //$id_doc                     = $_POST["id_doc"];
    //$nom_doc                    = $_POST["nom_doc"];
    //$n_c_date_start             = $_POST["n_c_date_start"];
    //$n_c_date_end               = $_POST["n_c_date_end"];
    //$n_c_date_sign              = $_POST["n_c_date_sign"];
    //$n_c_nb_reconduction        = $_POST["n_c_nb_reconduction"];
    //$n_c_duree_reconduction_mois= $_POST["n_c_duree_reconduction_mois"];
    //$n_c_nom_signataire_1       = $_POST["n_c_nom_signataire_1"];
    //$n_c_nom_signataire_2       = $_POST["n_c_nom_signataire_2"];
    //$n_c_type_signataire_1      = $_POST["n_c_type_signataire_1"];
    //$n_c_type_signataire_2      = $_POST["n_c_type_signataire_2"];
    //$n_c_categorie_site         = $_POST["n_c_categorie_site"];
    //$n_c_commentaire            = $_POST["n_c_commentaire"];
    //$lien                       = $_POST["lien"];
    $result = pg_prepare($dbconn, "convention", "
        INSERT INTO $conventions (
        id_convention, 
        nom_convention, 
        type_conv, 
        d_sign_conv, 
        d_debut_conv, 
        d_fin_conv, 
        duree_reconduction, 
        nb_reconduction, 
        signataire, 
        signataire_2, 
        type_signataire, 
        type_signataire_2, 
        categorie_fede, 
        commentaires,
        lien
        )
        VALUES 
        ($1,$2,'gestion',$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14) "
    );
    pg_execute($dbconn, "convention",array(
        $_POST["id_doc"],
        $_POST["nom_doc"],
        $_POST["n_c_date_start"],
        $_POST["n_c_date_end"],
        $_POST["n_c_date_sign"],
        $_POST["n_c_nb_reconduction"],
        $_POST["n_c_duree_reconduction_mois"],
        $_POST["n_c_nom_signataire_1"],
        $_POST["n_c_nom_signataire_2"],
        $_POST["n_c_type_signataire_1"],
        $_POST["n_c_type_signataire_2"],
        $_POST["n_c_categorie_site"],
        $_POST["n_c_commentaire"],
        $_POST["lien"]
    ));
} elseif ($doc == 'acquisition') {
    //$id_doc                     = $_POST["id_doc"];
    //$nom_doc                    = $_POST["nom_doc"];
    //$n_a_date_sign              = $_POST["n_a_date_sign"];
    //$n_a_notaire                = $_POST["n_a_notaire"];
    //$n_a_prix_vente             = $_POST["n_a_prix_vente"];
    //$n_a_nom_compta             = $_POST["n_a_nom_compta"];
    //$n_a_surf_tot               = $_POST["n_a_surf_tot"];
    //$n_a_commentaire            = $_POST["n_a_commentaire"];
    //$lien                       = $_POST["lien"];
    $result = pg_prepare($dbconn, "acquisition", "
        INSERT INTO $acquisitions (
        id_acquisition, 
        nom_acquisition, 
        date_signature_acquisition, 
        notaire, 
        prix_vente, 
        nom_compta_amort, 
        surface_tot_ha, 
        commentaires,
        lien
        )
        VALUES 
        (
        $1,$2,$3,$4,$5,$6,$7,$8,$9) "
    );
    pg_execute($dbconn, "acquisition",array(
        $_POST["id_doc"],
        $_POST["nom_doc"],
        $_POST["n_a_date_sign"],
        $_POST["n_a_notaire"],
        $_POST["n_a_prix_vente"],
        $_POST["n_a_nom_compta"],
        $_POST["n_a_surf_tot"],
        $_POST["n_a_commentaire"],
        $_POST["lien"]
    )) or die ( pg_last_error());
} elseif ($doc == 'bail_e') {
    //$id_doc                     = $_POST["id_doc"];
    //$nom_doc                    = $_POST["nom_doc"];
    //$n_b_e_date_sign            = $_POST["n_b_e_date_sign"];
    //$n_b_e_notaire              = $_POST["n_b_e_notaire"];
    //$n_b_e_nom_compta           = $_POST["n_b_e_nom_compta"];
    //$n_b_e_surf_tot             = $_POST["n_b_e_surf_tot"];
    //$n_b_e_commentaire          = $_POST["n_b_e_commentaire"];
    //$lien                       = $_POST["lien"];
    
    
        //INSERT INTO $bail_e 
    
    $result = pg_prepare($dbconn, "bail_e", "
        INSERT INTO $bail_e (
        id_bail_e, 
        nom_bail_e, 
        date_signature_bail_e, 
        notaire, 
        nom_compta_amort,
        surface_tot_ha,
        commentaires,
        lien
        )
        VALUES 
        ($1,$2,$3,$4,$5,$6,$7,$8);"
    );
    pg_execute($dbconn, "bail_e",array(
        $_POST["id_doc"],
        $_POST["nom_doc"],
        $_POST["n_b_e_date_sign"],
        $_POST["n_b_e_notaire"],
        $_POST["n_b_e_nom_compta"],
        $_POST["n_b_e_surf_tot"],
        $_POST["n_b_e_commentaire"],
        $_POST["lien"]
    )) or die ( pg_last_error());
    
} elseif ($doc == 'bail_rural') {
    //$id_doc                     = $_POST["id_doc"];
    //$nom_doc                    = $_POST["nom_doc"];
    //$n_b_bailleur               = $_POST["n_b_bailleur"];
    //$n_b_preneur                = $_POST["n_b_preneur"];
    //$n_b_date_sign              = $_POST["n_b_date_sign"];
    //$n_b_date_start             = $_POST["n_b_date_start"];
    //$n_b_date_end               = $_POST["n_b_date_end"];
    //$n_b_commentaire            = $_POST["n_b_commentaire"];
    //$lien                       = $_POST["lien"];
    $result = pg_prepare($dbconn, "bail_rural", "
        INSERT INTO $baux_ruraux (
        id_bail, 
        nom_bail, 
        bailleur, 
        preneur, 
        date_signature, 
        date_debut_bail, 
        date_fin_bail,
        commentaires,
        lien
        )
        VALUES 
        (
        $1,$2,$3,$4,$5,$6,$7,$8,$9) "
    );
    pg_execute($dbconn, "bail_rural",array(
        $_POST["id_doc"],
        $_POST["nom_doc"],
        $_POST["n_b_bailleur"],
        $_POST["n_b_preneur"],
        $_POST["n_b_date_sign"],
        $_POST["n_b_date_start"],
        $_POST["n_b_date_end"],
        $_POST["n_b_commentaire"],
        $_POST["lien"]
    )) or die ( pg_last_error());
} elseif ($doc == 'pret_usage') {
    //$id_doc                     = $_POST["id_doc"];
    //$nom_doc                    = $_POST["nom_doc"];
    //$n_p_preteur                = $_POST["n_p_preteur"];
    //$n_p_emprunteur             = $_POST["n_p_emprunteur"];
    //$n_p_date_sign              = $_POST["n_p_date_sign"];
    //$n_p_date_start             = $_POST["n_p_date_start"];
    //$n_p_date_end               = $_POST["n_p_date_end"];
    //$n_p_commentaire            = $_POST["n_p_commentaire"];
    $result = pg_prepare($dbconn, "pret", "
        INSERT INTO $prets_a_u (
        id_pret, 
        nom_pret, 
        preteur, 
        emprunteur, 
        date_signature, 
        date_debut_pret, 
        date_fin_pret,
        commentaires,
        lien
        )
        VALUES 
        ($1,$2,$3,$4,$5,$6,$7,$8,$9) ;"
    );
    pg_execute($dbconn, "pret",array(
        $_POST["id_doc"],
        $_POST["nom_doc"],
        $_POST["n_p_preteur"],
        $_POST["n_p_emprunteur"],
        $_POST["n_p_date_sign"],
        $_POST["n_p_date_start"],
        $_POST["n_p_date_end"],
        $_POST["n_p_commentaire"],
        $_POST["lien"]
    )) or die ( pg_last_error());
} elseif ($doc == 'ore') {
    //$id_ore                         = $_POST["id_doc"];
    //$nom_ore                        = $_POST["nom_doc"];
    //$n_ore_notaire                  = $_POST["n_ore_notaire"];
    //$n_ore_acquisition              = $_POST["n_ore_acquisition"];
    //$n_ore_date_sign                = $_POST["n_ore_date_sign"];
    //$n_ore_commentaire              = $_POST["n_ore_commentaire"];
    $result = pg_prepare($dbconn, "ore", "
        INSERT INTO $ore (
        id_ore, 
        nom_ore, 
        notaire, 
        nom_acquisition, 
        date_signature_ore, 
        commentaires,
        lien
        )
        VALUES 
        ($1,$2,$3,$4,$5,$6,$7) "
    );
    
    pg_execute($dbconn, "ore",array(
        $_POST["id_doc"],
        $_POST["nom_doc"],
        $_POST["n_ore_notaire"],
        $_POST["n_ore_acquisition"],
        $_POST["n_ore_date_sign"],
        $_POST["n_ore_commentaire"],
        $_POST["lien"]
    )) or die ( pg_last_error());
}  elseif ($doc == 'ddg') {
    //$id_gestion                             = $_POST["lien"];
    //$nom_gestion                            = $_POST["nom_doc"];
    //$n_type_doc_gestion                     = $_POST["n_type_doc_gestion"];
    //$n_gestion_date_start                   = $_POST["n_gestion_date_start"];
    //$n_gestion_date_end                     = $_POST["n_gestion_date_end"];
    //$n_gestion_auteurs                      = $_POST["n_gestion_auteurs"];
    //$n_gestion_commentaire                  = $_POST["n_gestion_commentaire"];
    $result = pg_prepare($dbconn, "ddg", "
        INSERT INTO $ddg (
        id_doc_gestion, 
        nom_doc_gestion, 
        type_doc_gestion, 
        d_debut_doc_gestion, 
        d_fin_doc_gestion, 
        auteurs, 
        commentaires,
        lien)
        VALUES 
        ($1,$2,$3,$4,$5,$6,$7,$8) "
    );
    pg_execute($dbconn, "ddg",array(
        $_POST["lien"],
        $_POST["nom_doc"],
        $_POST["n_type_doc_gestion"],
        $_POST["n_gestion_date_start"],
        $_POST["n_gestion_date_end"],
        $_POST["n_gestion_auteurs"],
        $_POST["n_gestion_commentaire"],
        $_POST["lien"]
    )) or die ( pg_last_error());
}

//ferme la connexion a la BD
pg_close($dbconn);

echo $doc.' enregistré(e)';





?>





