<?php 
date_default_timezone_set('Europe/Paris');
ini_set('max_execution_time', 72000000);
ini_set("display_errors",0);
ini_set('session.gc_maxlifetime', 3600);
// error_reporting(E_ALL ^ E_DEPRECATED);
// On se connecte ࡬a base de donn꦳
include '../../bdd.php';
require_once "../../php_writeexcel/class.writeexcel_workbook.inc.php";
require_once "../../php_writeexcel/class.writeexcel_worksheet.inc.php";

//on r�cup�re les varaible
$Identifiant_Session = $_GET['Identifiant_Session'];

$req_structure_id = pg_query($bdd, 'SELECT * FROM saisie_observation.structure WHERE "S_ID_SESSION"='."'".$Identifiant_Session."'".'');
$donnees_structure_id = pg_fetch_array($req_structure_id);
$id_structure_connectee = $donnees_structure_id['S_ID'];

//On indique ensuite un emplacement sur le serveur, l� o� sera stock� le fichier
$fname = "pram_exportation.xls";
$workbook = new writeexcel_workbook($fname); // on lui passe en param�tre le chemin de notre fichier
//////////////////////////////POUR GENERER LONGLET DEXPORT SIMPLIFIER//////////////////////////////////
$worksheet =& $workbook->addworksheet('PRAM_Export_Simplifie'); //le param鵲e ici est le nom de la feuille
$worksheet->set_column('A:CI', 60); // le 30 repr괥nte la largeur de chaque colonne
$heading  =& $workbook->addformat(array('bold' => 1, // on met le texte en gras
										'color' => 'black', // de couleur noire
									    'size' => 12, // de taille 12
										'merge' => 1)); // avec une marge
									 // 'fg_color' => 0x66, " & ")));// coloration du fond des cellules
$headings = array('Identifiant PRAM', 'Identifiant de la mare structure', 'Nom usuel', 'Longitude', 'Latitude', 'X (RGG93)', 'Y (RGF93)', 'Num INSEE', 'Commune', 'Statut', 'Date de localisation', 
					'Observateur', 'Structure', 'Propriete', 'Commentaire localisation', 'Validation du fond', 'Identifiant de caracterisation', 'Identifiant PRAM', 'Date de caracterisation', 'Observateur', 'Structure', 'Type de mare', 'Vegetation',
					'Stade evolution', 'Presence abreuvoir', 'Topographie', 'Precision topographie', 'Mare cloturee', 'Haie', 'Forme', 'Longueur', 'Largeur', 'Profondeur',
					'Nature du fond', 'Nature du fond autre', 'Berges', 'Bourelet de curage', '% bourrelet de curage', 'Surpietinement', 'Regime hydrologique', 'Turbidite', 'Couleur specifique eau', 'Precision couleur',
					'Tampon','Exutoire', 'Recouvrement total', 'Recouvrement helophytes', 'Recouvrement hydrophytes enracinees', 'Recouvrement hydrophytes non enracinees', 'Recouvrement algues', 'Eau libre', 'Recouvrement non vegetalise', 
					'Embroussaillement', 'Ombrage', 'Petit patrimoine', 'Precision patrimoine', 'Commentaire caracterisation', 'Alimentation de la mare', 'Precision alimentation autre', 'Contexte de la mare', 'Dechets', 'Taxon EAEE', 'Nom vernaculaire EAEE',
					'Taxon EVEE', 'Nom vernaculaire EVEE', 'Pourcentage EVEE', 'Groupe faunistique', 'Liaison de la mare', 'Precision liaison autre', 'Travaux envisages', 'Precision travaux autres', 'Usage de la mare', 'Date observation',
					'Referentiel taxonomique faune', 'Taxon Taxref', 'ID Taxref', 'Nom vernaculaire', 'Referentiel taxonomique flore', 'Taxon CBNBL', 'ID CBNBL', 'Nombre', 'Denombrement', 'Methode acquisition', 'Commentaire observation', 'Observateur', 'Structure', 'Collecte'); //dꧩnition du texte pour chaque cellules
$worksheet->write_row('A1', $headings, $heading); //On int騲e notre texte et les le format de cellule.

//ON VA FAIRE UNE REQUETE POUR VOIT SI LA STRUCUTRE POSSEDE UN CONTOUR GEOGRAPHIQUE DANS LA TABLE CONTOUR_STRUCTURE
$req_contour_structure = pg_query($bdd, 'SELECT * FROM saisie_observation.structure WHERE "S_ID_SESSION"='."'".$Identifiant_Session."'".' AND geom is Not Null');
$count_contour = pg_num_rows($req_contour_structure);

//ON VA FAIRE UNE REQUETE POUR DETERMINER LE ROLE DE LA STRUCTURE
$req_role_structure = pg_query($bdd, 'SELECT "ROLE" FROM saisie_observation.structure WHERE "S_ID_SESSION"='."'".$Identifiant_Session."'".'');
$role_structure = pg_fetch_array($req_role_structure);

if($role_structure['ROLE'] == "administrateur"){
	$req_filtre = pg_query($bdd, 'SELECT saisie_observation.localisation."ID" AS "ID", "L_ID", "L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", 
											(SELECT "STRUCTURE" FROM saisie_observation.structure WHERE structure."S_ID"::text = "L_STRP"), "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", "C_COMT"
								FROM saisie_observation.localisation, menu_deroulant.l_statut, saisie_observation.structure, ign_bd_topo.commune, saisie_observation.observateur, menu_deroulant.l_propr
								WHERE saisie_observation.localisation."L_STATUT" = menu_deroulant.l_statut."ID"::text
								AND saisie_observation.localisation."L_PROP" = menu_deroulant.l_propr."ID"::text
								AND saisie_observation.localisation."L_OBSV" = saisie_observation.observateur."ID"::text
								AND saisie_observation.localisation."L_ADMIN" = ign_bd_topo.commune."Num_INSEE"::text
								AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
								
								ORDER BY saisie_observation.localisation."L_ID"');
}
elseif($role_structure['ROLE'] == "observateur"){
	//SI count_contour = 1 alors on filtre les mares comprise dans le perimetre de la strucuture sinon on prend les mares qui posède l'identifiant en plus des filtres
	if($count_contour == 1){
		$req_filtre = pg_query($bdd, 'with structure as (select structure.geom as geom, "S_ID" from saisie_observation.structure where "S_ID_SESSION" = '."'".$Identifiant_Session."'".'),
										numdepartement as (select "Num_Dep" from ign_bd_topo.departement inner join structure on st_intersects(departement.geom, structure.geom)),
										commune as (select "Nom_Commune", "Num_INSEE" from ign_bd_topo.commune, numdepartement where commune."Num_Dep"::text = numdepartement."Num_Dep"::text),
										mare as (select * from saisie_observation.localisation, structure, numdepartement, commune where left(localisation."L_ADMIN", 2)::text = numdepartement."Num_Dep"::text and localisation."L_ADMIN" = commune."Num_INSEE" and st_contains(structure.geom, st_transform(localisation.geom,2154)))

										select   mare."ID" AS "ID", "L_ID", "L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", (SELECT "S_ID" FROM saisie_observation.structure WHERE structure."S_ID"::text = "L_STRP"), 
											(SELECT "STRUCTURE" FROM saisie_observation.structure WHERE structure."S_ID"::text = "L_STRP"), "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", "C_COMT"
										 from mare
										 inner join menu_deroulant.l_statut ON mare."L_STATUT" = menu_deroulant.l_statut."ID"::text
										 inner join saisie_observation.observateur ON mare."L_OBSV" = saisie_observation.observateur."ID"::text
										 inner join menu_deroulant.l_propr ON mare."L_PROP" = menu_deroulant.l_propr."ID"::text');
								
		// $req_filtre = pg_query($bdd, 'SELECT saisie_observation.localisation."ID" AS "ID", "L_ID", "L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", 
											// (SELECT "STRUCTURE" FROM saisie_observation.structure WHERE structure."S_ID"::text = "L_STRP"), "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", "C_COMT"
								// FROM saisie_observation.localisation, menu_deroulant.l_statut, saisie_observation.structure, ign_bd_topo.commune, saisie_observation.observateur, menu_deroulant.l_propr
								// WHERE saisie_observation.localisation."L_STATUT" = menu_deroulant.l_statut."ID"::text
								// AND saisie_observation.localisation."L_PROP" = menu_deroulant.l_propr."ID"::text
								// AND saisie_observation.localisation."L_OBSV" = saisie_observation.observateur."ID"::text
								// AND saisie_observation.localisation."L_ADMIN" = ign_bd_topo.commune."Num_INSEE"::text
								// AND ((st_intersects(structure.geom, st_transform(localisation.geom,2154))
								// AND "S_ID_SESSION"='."'".$Identifiant_Session."'".') OR (saisie_observation.localisation."L_STRP"::text = structure."S_ID"::text AND structure."S_ID"::text = '."'".$id_structure_connectee."'".'))
								// ORDER BY saisie_observation.localisation."L_ID"');
	}else{
	//REQUETE SUR LA TABLE LOCALISATION
	$req_filtre = pg_query($bdd, 'SELECT "ID", "L_ID", "L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", "C_COMT" 
									FROM (SELECT saisie_observation.localisation."ID" AS "ID", saisie_observation.localisation."L_ID", saisie_observation.localisation."L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", saisie_observation.localisation."C_COMT"
											FROM saisie_observation.localisation, menu_deroulant.l_statut, saisie_observation.structure, ign_bd_topo.commune, saisie_observation.observateur, menu_deroulant.l_propr, saisie_observation.caracterisation
											WHERE saisie_observation.localisation."L_STATUT" = menu_deroulant.l_statut."ID"::text
											AND saisie_observation.localisation."L_PROP" = menu_deroulant.l_propr."ID"::text
											AND saisie_observation.localisation."L_OBSV" = saisie_observation.observateur."ID"::text
											AND saisie_observation.localisation."L_ADMIN" = ign_bd_topo.commune."Num_INSEE"::text
											AND saisie_observation.localisation."L_ID"::text = saisie_observation.caracterisation."L_ID"::text
											AND saisie_observation.caracterisation."C_STRP"::text = saisie_observation.structure."S_ID"::text
											AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
											AND saisie_observation.structure."S_ID_SESSION" = '."'".$Identifiant_Session."'".'
											UNION ALL
											SELECT saisie_observation.localisation."ID" AS "ID", saisie_observation.localisation."L_ID", saisie_observation.localisation."L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", saisie_observation.localisation."C_COMT"
											FROM saisie_observation.localisation, menu_deroulant.l_statut, saisie_observation.structure, ign_bd_topo.commune, saisie_observation.observateur, menu_deroulant.l_propr, saisie_observation.observation, saisie_observation.caracterisation
											WHERE saisie_observation.localisation."L_STATUT" = menu_deroulant.l_statut."ID"::text
											AND saisie_observation.localisation."L_PROP" = menu_deroulant.l_propr."ID"::text
											AND saisie_observation.localisation."L_OBSV" = saisie_observation.observateur."ID"::text
											AND saisie_observation.localisation."L_ADMIN" = ign_bd_topo.commune."Num_INSEE"::text
											AND saisie_observation.localisation."L_ID"::text = saisie_observation.caracterisation."L_ID"::text
											AND saisie_observation.caracterisation."C_STRP"::text = saisie_observation.structure."S_ID"::text
											AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
											AND saisie_observation.structure."S_ID_SESSION" = '."'".$Identifiant_Session."'".'
											UNION ALL
											SELECT saisie_observation.localisation."ID" AS "ID", saisie_observation.localisation."L_ID", saisie_observation.localisation."L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", saisie_observation.localisation."C_COMT"
											FROM saisie_observation.localisation, menu_deroulant.l_statut, saisie_observation.structure, ign_bd_topo.commune, saisie_observation.observateur, menu_deroulant.l_propr, saisie_observation.caracterisation
											WHERE saisie_observation.localisation."L_STATUT" = menu_deroulant.l_statut."ID"::text
											AND saisie_observation.localisation."L_PROP" = menu_deroulant.l_propr."ID"::text
											AND saisie_observation.localisation."L_OBSV" = saisie_observation.observateur."ID"::text
											AND saisie_observation.localisation."L_ADMIN" = ign_bd_topo.commune."Num_INSEE"::text
											AND saisie_observation.localisation."L_ID"::text = saisie_observation.caracterisation."L_ID"::text
											AND saisie_observation.caracterisation."C_STRP"::text = saisie_observation.structure."S_ID"::text
											AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
											AND saisie_observation.structure."S_ID_SESSION" = '."'".$Identifiant_Session."'".') t
									GROUP BY "ID", "L_ID", "L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", "C_COMT"');
	}							
}
elseif($role_structure['ROLE'] == "utilisateur"){
	//REQUETE SUR LA TABLE LOCALISATION
	$req_filtre = pg_query($bdd, 'SELECT "ID", "L_ID", "L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", "C_COMT" 
									FROM (SELECT saisie_observation.localisation."ID" AS "ID", saisie_observation.localisation."L_ID", saisie_observation.localisation."L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", saisie_observation.localisation."C_COMT"
											FROM saisie_observation.localisation, menu_deroulant.l_statut, saisie_observation.structure, ign_bd_topo.commune, saisie_observation.observateur, menu_deroulant.l_propr, saisie_observation.caracterisation
											WHERE saisie_observation.localisation."L_STATUT" = menu_deroulant.l_statut."ID"::text
											AND saisie_observation.localisation."L_PROP" = menu_deroulant.l_propr."ID"::text
											AND saisie_observation.localisation."L_OBSV" = saisie_observation.observateur."ID"::text
											AND saisie_observation.localisation."L_ADMIN" = ign_bd_topo.commune."Num_INSEE"::text
											AND saisie_observation.localisation."L_ID"::text = saisie_observation.caracterisation."L_ID"::text
											AND saisie_observation.caracterisation."C_STRP"::text = saisie_observation.structure."S_ID"::text
											AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
											AND saisie_observation.structure."S_ID_SESSION" = '."'".$Identifiant_Session."'".'
											UNION ALL
											SELECT saisie_observation.localisation."ID" AS "ID", saisie_observation.localisation."L_ID", saisie_observation.localisation."L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", saisie_observation.localisation."C_COMT"
											FROM saisie_observation.localisation, menu_deroulant.l_statut, saisie_observation.structure, ign_bd_topo.commune, saisie_observation.observateur, menu_deroulant.l_propr, saisie_observation.observation
											WHERE saisie_observation.localisation."L_STATUT" = menu_deroulant.l_statut."ID"::text
											AND saisie_observation.localisation."L_PROP" = menu_deroulant.l_propr."ID"::text
											AND saisie_observation.localisation."L_OBSV" = saisie_observation.observateur."ID"::text
											AND saisie_observation.localisation."L_ADMIN" = ign_bd_topo.commune."Num_INSEE"::text
											AND saisie_observation.localisation."L_ID"::text = saisie_observation.observation."L_ID"::text
											AND saisie_observation.observation."O_STRP"::text = saisie_observation.structure."S_ID"::text
											AND localisation."L_DATE" > '."'631148400'".' AND localisation."L_DATE" is not Null
											AND saisie_observation.structure."S_ID_SESSION" = '."'".$Identifiant_Session."'".'
											UNION ALL
											SELECT saisie_observation.localisation."ID" AS "ID", saisie_observation.localisation."L_ID", saisie_observation.localisation."L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", saisie_observation.localisation."C_COMT"
											FROM saisie_observation.localisation, menu_deroulant.l_statut, saisie_observation.structure, ign_bd_topo.commune, saisie_observation.observateur, menu_deroulant.l_propr
											WHERE saisie_observation.localisation."L_STATUT" = menu_deroulant.l_statut."ID"::text
											AND saisie_observation.localisation."L_PROP" = menu_deroulant.l_propr."ID"::text
											AND saisie_observation.localisation."L_OBSV" = saisie_observation.observateur."ID"::text
											AND saisie_observation.localisation."L_ADMIN" = ign_bd_topo.commune."Num_INSEE"::text
											AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
											AND localisation."L_DATE" > '."'631148400'".' AND localisation."L_DATE" is not Null
											AND saisie_observation.structure."S_ID_SESSION" = '."'".$Identifiant_Session."'".') t
									GROUP BY "ID", "L_ID", "L_IDSTRP", "L_STRP", "L_NOM", "STATUT", "L_ADMIN", "L_DATE", "L_OBSV", "L_PROP", "L_COOX", "L_COOY", "L_COOX93", "L_COOY93", "S_ID", "STRUCTURE", "Nom_Commune", "OBS_PRENOM", "OBS_NOM", "PROPR", "L_VALID", "OBS_NOM_PRENOM", "C_COMT"');
}

$x=2;
while($resultat = pg_fetch_array($req_filtre)){
	///ON REINITIALISE LES VARAIBLE DE CARACTIRISATION
	$ID_CARAC = "";
	$L_ID = "";
	$C_DATE = "";
	$OBS_NOM_PRENOM = "";
	$STRUCTURE = "";
	$TYPE = "";
	$VEGET = "";
	$EVOLUTION = "";
	$ABREUV = "";
	$TOPO = "";
	$C_TOPO_AUTRE = "";
	$CLOTURE = "";
	$HAIE = "";
	$FORM = "";
	$C_LONG = "";
	$C_LARG = "";
	$PROF = "";
	$NATFOND = "";
	$C_NATFOND_AUTRE = "";
	$BERGES = "";
	$BOURRELET = "";
	$C_BOURRELET_POURCENTAGE = "";
	$PIETINEMENT = "";
	$HYDROLOGIE = "";
	$TURBIDITE = "";
	$COULEUR = "";
	$C_COULEUR_PRECISION = "";
	$TAMPON = "";
	$EXUTOIRE = "";
	$C_RECOU_TOTAL = "";
	$C_RECOU_HELOPHYTE = "";
	$C_RECOU_HYDROPHYTE_E = "";
	$C_RECOU_HYDROPHYTE_NE = "";
	$C_RECOU_ALGUE = "";
	$C_RECOU_EAU_LIBRE = "";
	$C_RECOU_NON_VEGET = "";
	$EMBROUS = "";
	$OMBRAGE = "";
	$PATRIMOINE = "";
	$C_PATRIMOINE_AUTRE = "";
	$C_COMT = "";
	$ALIMENTATION = "";
	$ALIMENTATION_AUTRE = "";
	$CONTEXT = "";
	$DECHETS = "";
	$TAXON = "";
	$NOM_VERNACULAIRE = "";
	$TAXON_VEG = "";
	$NOM_VERNACULAIRE_VEG = "";
	$POURCENTAGE = "";
	$FAUNE = "";
	$LIAISON = "";
	$LIAISON_AUTRE = "";
	$TRAVAUX = "";
	$TRAVAUX_AUTRE = "";
	$USAGE = "";
	$O_DATE = "";
	$O_REFE = "";
	$O_NLAT = "";
	$O_REID = "";
	$O_NVER = "";
	$O_REF2 = "";
	$O_NLA2 = "";
	$O_REI2 = "";
	$O_NBRE = "";
	$O_NBRT = "";
	$SACQ = "";
	$O_COMT = "";
	$OBS_NOM_PRENOM_OBSERVATION = "";
	$STRUCTURE_OBSERVATION = "";
	$STYP = "";
			
	///ON ECRIT LES CELLULE PROPRE A LA LOCALISATION
	$worksheet->write("A".$x,$resultat['L_ID']); // ici on va ꤲire une cellules bien dꧩnie
	$worksheet->write("B".$x,utf8_decode(rtrim($resultat['L_IDSTRP'], " & ")));
	$worksheet->write("C".$x,utf8_decode(rtrim($resultat['L_NOM'], " & ")));
	$worksheet->write("D".$x,$resultat['L_COOX']);
	$worksheet->write("E".$x,$resultat['L_COOY']);
	$worksheet->write("F".$x,$resultat['L_COOX93']);
	$worksheet->write("G".$x,$resultat['L_COOY93']);
	$worksheet->write("H".$x,$resultat['L_ADMIN']);
	$worksheet->write("I".$x,$resultat['Nom_Commune']);
	$worksheet->write("J".$x,utf8_decode(rtrim($resultat['STATUT'], " & ")));
	$worksheet->write("K".$x,date('d/m/Y', $resultat['L_DATE']));
	$worksheet->write("L".$x,utf8_decode(rtrim($resultat['OBS_NOM_PRENOM'], " & ")));
	$worksheet->write("M".$x,utf8_decode(rtrim($resultat['STRUCTURE'], " & ")));
	$worksheet->write("N".$x,utf8_decode(rtrim($resultat['PROPR'], " & ")));
	$worksheet->write("O".$x,utf8_decode(rtrim($resultat['L_COMT'], " & ")));
	$worksheet->write("P".$x,utf8_decode(rtrim($resultat['L_VALID'], " & ")));
	
		//ON FAIS UNE REQUETE SUR LA CARACTERISATION
		$req_filtre_carac = pg_query($bdd, 'SELECT saisie_observation.caracterisation."ID_CARAC" AS "ID_CARAC", "C_STRP", saisie_observation.caracterisation."L_ID" AS "L_ID", "C_DATE", "OBS_NOM_PRENOM", (SELECT "STRUCTURE" FROM saisie_observation.structure WHERE structure."S_ID"::text = "C_STRP"::text), "TYPE", "VEGET", "EVOLUTION", 
												"ABREUV", "TOPO", "C_TOPO", "C_TOPO_AUTRE", "CLOTURE", "HAIE", "FORM", "C_LONG", "C_LARG", "PROF", "NATFOND", "C_NATFOND", "C_NATFOND_AUTRE", "BERGES", "BOURRELET", "C_BOURRELET",
												"C_BOURRELET_POURCENTAGE", "PIETINEMENT", "HYDROLOGIE", "TURBIDITE", "COULEUR", "C_COULEUR", "C_COULEUR_PRECISION", "TAMPON", "EXUTOIRE", "C_RECOU_TOTAL", "C_RECOU_HELOPHYTE", 
												"C_RECOU_HYDROPHYTE_E", "C_RECOU_HYDROPHYTE_NE", "C_RECOU_ALGUE", "C_RECOU_EAU_LIBRE", "C_RECOU_NON_VEGET", "EMBROUS", "OMBRAGE", saisie_observation.caracterisation."C_COMT" AS "C_COMT"
									FROM saisie_observation.caracterisation
									LEFT JOIN saisie_observation.observateur ON saisie_observation.caracterisation."C_OBSV" = saisie_observation.observateur."ID"
									LEFT JOIN saisie_observation.localisation ON saisie_observation.localisation."L_ID" = saisie_observation.caracterisation."L_ID"
									LEFT JOIN saisie_observation.structure ON saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
									LEFT JOIN menu_deroulant.c_abreuv ON saisie_observation.caracterisation."C_ABREUV" = menu_deroulant.c_abreuv."ID"
									LEFT JOIN menu_deroulant.c_berges ON saisie_observation.caracterisation."C_BERGES" = menu_deroulant.c_berges."ID"
									LEFT JOIN menu_deroulant.c_bourrelet ON saisie_observation.caracterisation."C_BOURRELET" = menu_deroulant.c_bourrelet."ID"
									LEFT JOIN menu_deroulant.c_cloture ON saisie_observation.caracterisation."C_CLOTURE" = menu_deroulant.c_cloture."ID"
									LEFT JOIN menu_deroulant.c_couleur ON saisie_observation.caracterisation."C_COULEUR" = menu_deroulant.c_couleur."ID"
									LEFT JOIN menu_deroulant.c_embrous ON saisie_observation.caracterisation."C_EMBROUS" = menu_deroulant.c_embrous."ID"
									LEFT JOIN menu_deroulant.c_evolution ON saisie_observation.caracterisation."C_EVOLUTION" = menu_deroulant.c_evolution."ID"
									LEFT JOIN menu_deroulant.c_exutoire ON saisie_observation.caracterisation."C_EXUTOIRE" = menu_deroulant.c_exutoire."ID"
									LEFT JOIN menu_deroulant.c_form ON saisie_observation.caracterisation."C_FORM" = menu_deroulant.c_form."ID"
									LEFT JOIN menu_deroulant.c_haie ON saisie_observation.caracterisation."C_HAIE" = menu_deroulant.c_haie."ID"
									LEFT JOIN menu_deroulant.c_hydrologie ON saisie_observation.caracterisation."C_HYDROLOGIE" = menu_deroulant.c_hydrologie."ID"
									LEFT JOIN menu_deroulant.c_natfond ON saisie_observation.caracterisation."C_NATFOND" = menu_deroulant.c_natfond."ID"
									LEFT JOIN menu_deroulant.c_ombrage ON saisie_observation.caracterisation."C_OMBRAGE" = menu_deroulant.c_ombrage."ID"
									LEFT JOIN menu_deroulant.c_pietinement ON saisie_observation.caracterisation."C_PIETINEMENT" = menu_deroulant.c_pietinement."ID"
									LEFT JOIN menu_deroulant.c_prof ON saisie_observation.caracterisation."C_PROF" = menu_deroulant.c_prof."ID"
									LEFT JOIN menu_deroulant.c_tampon ON saisie_observation.caracterisation."C_TAMPON" = menu_deroulant.c_tampon."ID"
									LEFT JOIN menu_deroulant.c_topo ON saisie_observation.caracterisation."C_TOPO" = menu_deroulant.c_topo."ID"
									LEFT JOIN menu_deroulant.c_turbidite ON saisie_observation.caracterisation."C_TURBIDITE" = menu_deroulant.c_turbidite."ID"
									LEFT JOIN menu_deroulant.c_type ON saisie_observation.caracterisation."C_TYPE" = menu_deroulant.c_type."ID"
									LEFT JOIN menu_deroulant.c_veget ON saisie_observation.caracterisation."C_VEGET" = menu_deroulant.c_veget."ID"
									WHERE caracterisation."L_ID" = '."'".$resultat['L_ID']."'".'
									AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
									ORDER BY saisie_observation.caracterisation."ID_CARAC"');
	
		
		
		while($resultat_carac = pg_fetch_array($req_filtre_carac)){
			$ID_CARAC = $resultat_carac['ID_CARAC']." & ".$ID_CARAC;
			$L_ID = $resultat_carac['L_ID']." & ".$L_ID;
			$C_DATE = date('d/m/Y', $resultat_carac['C_DATE'])." & ".$C_DATE;
			$OBS_NOM_PRENOM = $resultat_carac['OBS_NOM_PRENOM']." & ".$OBS_NOM_PRENOM;
			$STRUCTURE = $resultat_carac['STRUCTURE']." & ".$STRUCTURE;
			$TYPE = $resultat_carac['TYPE']." & ".$TYPE;
			$VEGET = $resultat_carac['VEGET']." & ".$VEGET;
			$EVOLUTION = $resultat_carac['EVOLUTION']." & ".$EVOLUTION;
			$ABREUV = $resultat_carac['ABREUV']." & ".$ABREUV;
			$TOPO = $resultat_carac['TOPO']." & ".$TOPO;
			if($resultat_carac['C_TOPO'] == "5"){
				$C_TOPO_AUTRE = $resultat_carac['C_NATFOND_AUTRE']." & ".$C_TOPO_AUTRE;
			}else{
				$C_TOPO_AUTRE = "NaN"." & ".$C_TOPO_AUTRE;
			}
			$CLOTURE = $resultat_carac['CLOTURE']." & ".$CLOTURE;
			$HAIE = $resultat_carac['HAIE']." & ".$HAIE;
			$FORM = $resultat_carac['FORM']." & ".$FORM;
			$C_LONG = $resultat_carac['C_LONG']." & ".$C_LONG;
			$C_LARG = $resultat_carac['C_LARG']." & ".$C_LARG;
			$PROF = $resultat_carac['PROF']." & ".$PROF;
			$NATFOND = $resultat_carac['NATFOND']." & ".$NATFOND;
			if($resultat_carac['C_NATFOND'] == "5"){
				$C_NATFOND_AUTRE = $resultat_carac['C_NATFOND_AUTRE']." & ".$C_NATFOND_AUTRE;
			}else{
				$C_NATFOND_AUTRE = "NaN"." & ".$C_NATFOND_AUTRE;
			}
			$BERGES = $resultat_carac['BERGES']." & ".$BERGES;
			$BOURRELET = $resultat_carac['BOURRELET']." & ".$BOURRELET;
			if($resultat_carac['C_BOURRELET'] == "2"){
				$C_BOURRELET_POURCENTAGE = $resultat_carac['C_BOURRELET_POURCENTAGE']." & ".$C_BOURRELET_POURCENTAGE;
			}else{
				$C_BOURRELET_POURCENTAGE = "NaN"." & ".$C_BOURRELET_POURCENTAGE;
			}
			$PIETINEMENT = $resultat_carac['PIETINEMENT']." & ".$PIETINEMENT;
			$HYDROLOGIE = $resultat_carac['HYDROLOGIE']." & ".$HYDROLOGIE;
			$TURBIDITE = $resultat_carac['TURBIDITE']." & ".$TURBIDITE;
			$COULEUR = $resultat_carac['COULEUR']." & ".$COULEUR;
			if($resultat_carac['C_COULEUR'] == "3"){
				$C_COULEUR_PRECISION = $resultat_carac['C_COULEUR_PRECISION']." & ".$C_COULEUR_PRECISION;			
			}else{
				$C_COULEUR_PRECISION = "NaN"." & ".$C_COULEUR_PRECISION;
			}
			$TAMPON = $resultat_carac['TAMPON']." & ".$TAMPON;
			$EXUTOIRE = $resultat_carac['EXUTOIRE']." & ".$EXUTOIRE;
			$C_RECOU_TOTAL = $resultat_carac['C_RECOU_TOTAL']." & ".$C_RECOU_TOTAL;
			$C_RECOU_HELOPHYTE = $resultat_carac['C_RECOU_HELOPHYTE']." & ".$C_RECOU_HELOPHYTE;
			$C_RECOU_HYDROPHYTE_E = $resultat_carac['C_RECOU_HYDROPHYTE_E']." & ".$C_RECOU_HYDROPHYTE_E;
			$C_RECOU_HYDROPHYTE_NE = $resultat_carac['C_RECOU_HYDROPHYTE_NE']." & ".$C_RECOU_HYDROPHYTE_NE;
			$C_RECOU_ALGUE = $resultat_carac['C_RECOU_ALGUE']." & ".$C_RECOU_ALGUE;
			$C_RECOU_EAU_LIBRE = $resultat_carac['C_RECOU_EAU_LIBRE']." & ".$C_RECOU_EAU_LIBRE;
			$C_RECOU_NON_VEGET = $resultat_carac['C_RECOU_NON_VEGET']." & ".$C_RECOU_NON_VEGET;
			$EMBROUS = $resultat_carac['EMBROUS']." & ".$EMBROUS;
			$OMBRAGE = $resultat_carac['OMBRAGE']." & ".$OMBRAGE;
			$C_COMT = $resultat_carac['C_COMT']." & ".$C_COMT;
			
			
			
			
			// $PATRIMOINE = $resultat_carac['PATRIMOINE']." & ".$PATRIMOINE;
			// if($resultat_carac['C_PATRIMOINE'] == "6"){
				// $C_PATRIMOINE_AUTRE = $resultat_carac['C_PATRIMOINE_AUTRE']." & ".$C_PATRIMOINE_AUTRE;	
			// }else{
				// $C_PATRIMOINE_AUTRE = "NaN"." & ".$C_PATRIMOINE_AUTRE;	;
			// }
			
			
				//ON FAIS LES REQUETE POUR LE BATI PATRIMOINE DE LA MARE
				$req_filtre_patrimoine =  pg_query($bdd, 'SELECT saisie_observation.caracterisation_patrimoine."ID_CARAC", menu_deroulant.c_patrimoine."PATRIMOINE", saisie_observation.caracterisation_patrimoine."PATRIMOINE_AUTRE"
											FROM saisie_observation.caracterisation_patrimoine, menu_deroulant.c_patrimoine, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
											WHERE saisie_observation.caracterisation_patrimoine."PATRIMOINE" = menu_deroulant.c_patrimoine."ID"
											AND saisie_observation.caracterisation_patrimoine."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
											AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
											AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
											AND caracterisation_patrimoine."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
											AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
											ORDER BY saisie_observation.caracterisation_patrimoine."ID_CARAC"');
				
				
				while($resultat_patrimoine = pg_fetch_array($req_filtre_patrimoine)){
					$PATRIMOINE = $resultat_patrimoine['PATRIMOINE']." & ".$PATRIMOINE;
					if($resultat_patrimoine['PATRIMOINE'] == "autre"){
						$PATRIMOINE_AUTRE = $resultat_patrimoine['PATRIMOINE_AUTRE']." & ".$PATRIMOINE_AUTRE;	
					}else{
						$PATRIMOINE_AUTRE = "NaN"." & ".$PATRIMOINE_AUTRE;	
					}
				}
				
				//ON FAIS LES REQUETE POUR LALIMENTATION DE LA MARE
				$req_filtre_alim =  pg_query($bdd, 'SELECT saisie_observation.caracterisation_alimentation."ID_CARAC", menu_deroulant.c_alimentation."ALIMENTATION", saisie_observation.caracterisation_alimentation."ALIMENTATION_AUTRE"
											FROM saisie_observation.caracterisation_alimentation, menu_deroulant.c_alimentation, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
											WHERE saisie_observation.caracterisation_alimentation."ALIMENTATION" = menu_deroulant.c_alimentation."ID"
											AND saisie_observation.caracterisation_alimentation."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
											AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
											AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
											AND caracterisation_alimentation."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
											AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
											ORDER BY saisie_observation.caracterisation_alimentation."ID_CARAC"');
				
				
				while($resultat_alim = pg_fetch_array($req_filtre_alim)){
					$ALIMENTATION = $resultat_alim['ALIMENTATION']." & ".$ALIMENTATION;
					if($resultat_alim['ALIMENTATION'] == "autre"){
						$ALIMENTATION_AUTRE = $resultat_alim['ALIMENTATION_AUTRE']." & ".$ALIMENTATION_AUTRE;	
					}else{
						$ALIMENTATION_AUTRE = "NaN"." & ".$ALIMENTATION_AUTRE;	
					}
				}
				
				//ON FAIS POUR LE CONTEXT DE LA MARE
				$req_filtre_context = pg_query($bdd, 'SELECT  saisie_observation.caracterisation_context."ID_CARAC", menu_deroulant.c_context."CONTEXT"
												FROM saisie_observation.caracterisation_context, menu_deroulant.c_context, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
												WHERE saisie_observation.caracterisation_context."CONTEXT" = menu_deroulant.c_context."ID"
												AND saisie_observation.caracterisation_context."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
												AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
												AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
												
												AND caracterisation_context."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
												AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
												ORDER BY saisie_observation.caracterisation_context."ID_CARAC"');
				
				
				while($resultat_context = pg_fetch_array($req_filtre_context)){
					$CONTEXT = $resultat_context['CONTEXT']." & ".$CONTEXT;
				}
				
				//ON FAIT POUR LES DECHETS DE LA MARE
				$req_filtre_dechets = pg_query($bdd, 'SELECT  saisie_observation.caracterisation_dechets."ID_CARAC", menu_deroulant.c_dechets."DECHETS"
													FROM saisie_observation.caracterisation_dechets, menu_deroulant.c_dechets, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
													WHERE saisie_observation.caracterisation_dechets."DECHETS" = menu_deroulant.c_dechets."ID"
													AND saisie_observation.caracterisation_dechets."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
													AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
													AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
													
													AND caracterisation_dechets."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
													AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
													ORDER BY saisie_observation.caracterisation_dechets."ID_CARAC"');
				
				
				while($resultat_dechets = pg_fetch_array($req_filtre_dechets)){
					$DECHETS = $resultat_dechets['DECHETS']." & ".$DECHETS;
				}
				
				//ON FAIT POUR LES EAEEE
				$req_filtre_eaee = pg_query($bdd, 'SELECT  saisie_observation.caracterisation_eaee."ID_CARAC", menu_deroulant.c_eaee."TAXON", menu_deroulant.c_eaee."NOM_VERNACULAIRE"
												FROM saisie_observation.caracterisation_eaee, menu_deroulant.c_eaee, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
												WHERE saisie_observation.caracterisation_eaee."EAEE" = menu_deroulant.c_eaee."ID"
												AND saisie_observation.caracterisation_eaee."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
												AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
												AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
												
												AND caracterisation_eaee."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
												AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
												ORDER BY saisie_observation.caracterisation_eaee."ID_CARAC"');
				
				
				while($resultat_eaee = pg_fetch_array($req_filtre_eaee)){
					$TAXON = $resultat_eaee['TAXON']." & ".$TAXON;
					$NOM_VERNACULAIRE = $resultat_eaee['NOM_VERNACULAIRE']." & ".$NOM_VERNACULAIRE;
				}
				
				//ON FAIS POUR LES EVEE
				$req_filtre_evee = pg_query($bdd, 'SELECT  saisie_observation.caracterisation_evee."ID_CARAC", menu_deroulant.c_evee."TAXON", menu_deroulant.c_evee."NOM_VERNACULAIRE", menu_deroulant.c_evee_pourcent."POURCENTAGE"
												FROM saisie_observation.caracterisation_evee, menu_deroulant.c_evee, menu_deroulant.c_evee_pourcent, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
												WHERE saisie_observation.caracterisation_evee."EVEE" = menu_deroulant.c_evee."ID"
												AND saisie_observation.caracterisation_evee."EVEE_POURCENT" = menu_deroulant.c_evee_pourcent."ID"
												AND saisie_observation.caracterisation_evee."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
												AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
												AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
												
												AND caracterisation_evee."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
												AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
												ORDER BY saisie_observation.caracterisation_evee."ID_CARAC"');
				
				
				while($resultat_evee = pg_fetch_array($req_filtre_evee)){
					$TAXON_VEG = $resultat_evee['TAXON']." & ".$TAXON_VEG;
					$NOM_VERNACULAIRE_VEG = $resultat_evee['NOM_VERNACULAIRE']." & ".$NOM_VERNACULAIRE_VEG;
					$POURCENTAGE = $resultat_evee['POURCENTAGE']." & ".$POURCENTAGE;
				}
				
				//ON FAIS POUR GROUPE FAUNISTIQUE
				$req_filtre_gf = pg_query($bdd, 'SELECT  saisie_observation.caracterisation_faune."ID_CARAC", menu_deroulant.c_faune."FAUNE"
												FROM saisie_observation.caracterisation_faune, menu_deroulant.c_faune, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
												WHERE saisie_observation.caracterisation_faune."FAUNE" = menu_deroulant.c_faune."ID"
												AND saisie_observation.caracterisation_faune."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
												AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
												AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
												
												AND caracterisation_faune."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
												AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
												ORDER BY saisie_observation.caracterisation_faune."ID_CARAC"');
			
				
				while($resultat_gf = pg_fetch_array($req_filtre_gf)){
					$FAUNE = $resultat_gf['FAUNE']." & ".$FAUNE;
				}
				
				//ON FAIS POUR LA LIAISON DE LA MARE
				$req_filtre_liaison = pg_query($bdd, 'SELECT  saisie_observation.caracterisation_liaison."ID_CARAC", menu_deroulant.c_liaison."LIAISON", saisie_observation.caracterisation_liaison."LIAISON_AUTRE"
													FROM saisie_observation.caracterisation_liaison, menu_deroulant.c_liaison, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
													WHERE saisie_observation.caracterisation_liaison."LIAISON" = menu_deroulant.c_liaison."ID"
													AND saisie_observation.caracterisation_liaison."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
													AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
													AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
													
													AND caracterisation_liaison."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
													AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
													ORDER BY saisie_observation.caracterisation_liaison."ID_CARAC"');
				
				
				while($resultat_liaison = pg_fetch_array($req_filtre_liaison)){
					$LIAISON = $resultat_liaison['LIAISON']." & ".$LIAISON;
					if($resultat_liaison['LIAISON'] == "autre"){
						$LIAISON_AUTRE = $resultat_liaison['LIAISON_AUTRE']." & ".$LIAISON_AUTRE;
					}else{
						$LIAISON_AUTRE = "NaN"." & ".$LIAISON_AUTRE;
					}
				}
				
				//ON FAIS LES TRAVAUX DE LA MARE
				$req_filtre_trav = pg_query($bdd, 'SELECT  caracterisation_travaux."ID_CARAC", c_travaux."TRAVAUX", caracterisation_travaux."TRAVAUX_AUTRE"
												FROM saisie_observation.caracterisation_travaux, menu_deroulant.c_travaux, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
												WHERE caracterisation_travaux."TRAVAUX" = c_travaux."ID"
												AND saisie_observation.caracterisation_travaux."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
												AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
												AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
												
												AND caracterisation_travaux."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
												AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
												ORDER BY caracterisation_travaux."ID_CARAC"');
				
				
				while($resultat_trav = pg_fetch_array($req_filtre_trav)){
					$TRAVAUX = $resultat_trav['TRAVAUX']." & ".$TRAVAUX;
					if($resultat_trav['TRAVAUX'] == "autre"){
						$TRAVAUX_AUTRE = $resultat_trav['TRAVAUX_AUTRE']." & ".$TRAVAUX_AUTRE;
					}else{
						$TRAVAUX_AUTRE = "NaN"." & ".$TRAVAUX_AUTRE;
					}
				}
				
				
				//ON FAIS LUSAGE PRINCIPALE DE LA MARE
				
				$req_filtre_usage = pg_query($bdd, 'SELECT  caracterisation_usage."ID_CARAC", c_usage."USAGE"
													FROM saisie_observation.caracterisation_usage, menu_deroulant.c_usage, saisie_observation.caracterisation, saisie_observation.localisation, saisie_observation.structure
													WHERE caracterisation_usage."C_USAGE" = c_usage."ID"
													AND saisie_observation.caracterisation_usage."ID_CARAC" = saisie_observation.caracterisation."ID_CARAC"
													AND saisie_observation.caracterisation."L_ID" = saisie_observation.localisation."L_ID"
													AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
													
													AND caracterisation_usage."ID_CARAC" = '."'".$resultat_carac['ID_CARAC']."'".'
													AND caracterisation."C_DATE" > '."'631148400'".' AND caracterisation."C_DATE" is not Null
													ORDER BY caracterisation_usage."ID_CARAC"');
				
				
				while($resultat_usage = pg_fetch_array($req_filtre_usage)){
					$USAGE = $resultat_usage['USAGE']." & ".$USAGE;
				}				
		}
			
		//ON ECRIT LES LIGNE CORRESPONDANT POUR LA CARACTERISATION CONCANTENER AVEC / SI PLUSIEURS CARACTERISATION	
		$worksheet->write("Q".$x,rtrim($ID_CARAC, " & ")); // ici on va ꤲire une cellules bien dꧩnie
		$worksheet->write("R".$x,rtrim($L_ID, " & ")); // ici on va ꤲire une cellules bien dꧩnie
		$worksheet->write("S".$x,rtrim($C_DATE, " & "));
		$worksheet->write("T".$x,utf8_decode(rtrim($OBS_NOM_PRENOM, " & ")));
		$worksheet->write("U".$x,utf8_decode(rtrim($STRUCTURE, " & ")));
		$worksheet->write("V".$x,utf8_decode(rtrim($TYPE, " & ")));
		$worksheet->write("W".$x,utf8_decode(rtrim($VEGET, " & ")));
		$worksheet->write("X".$x,utf8_decode(rtrim($EVOLUTION, " & ")));
		$worksheet->write("Y".$x,utf8_decode(rtrim($ABREUV, " & ")));
		$worksheet->write("Z".$x,utf8_decode(rtrim($TOPO, " & ")));
		$worksheet->write("AA".$x,utf8_decode(rtrim($C_TOPO_AUTRE, " & ")));
		$worksheet->write("AB".$x,utf8_decode(rtrim($CLOTURE, " & ")));
		$worksheet->write("AC".$x,utf8_decode(rtrim($HAIE, " & ")));
		$worksheet->write("AD".$x,utf8_decode(rtrim($FORM, " & ")));
		$worksheet->write("AE".$x,utf8_decode(rtrim($C_LONG, " & ")));
		$worksheet->write("AF".$x,utf8_decode(rtrim($C_LARG, " & ")));
		$worksheet->write("AG".$x,utf8_decode(rtrim($PROF, " & ")));
		$worksheet->write("AH".$x,utf8_decode(rtrim($NATFOND, " & ")));
		$worksheet->write("AI".$x,utf8_decode(rtrim($C_NATFOND_AUTRE, " & ")));
		$worksheet->write("AJ".$x,utf8_decode(rtrim($BERGES, " & ")));
		$worksheet->write("AK".$x,utf8_decode(rtrim($BOURRELET, " & ")));
		$worksheet->write("AL".$x,utf8_decode(rtrim($C_BOURRELET_POURCENTAGE, " & ")));
		$worksheet->write("AM".$x,utf8_decode(rtrim($PIETINEMENT, " & ")));
		$worksheet->write("AN".$x,utf8_decode(rtrim($HYDROLOGIE, " & ")));
		$worksheet->write("AO".$x,utf8_decode(rtrim($TURBIDITE, " & ")));
		$worksheet->write("AP".$x,utf8_decode(rtrim($COULEUR, " & ")));
		$worksheet->write("AQ".$x,utf8_decode(rtrim($C_COULEUR_PRECISION, " & ")));
		$worksheet->write("AR".$x,utf8_decode(rtrim($TAMPON, " & ")));
		$worksheet->write("AS".$x,utf8_decode(rtrim($EXUTOIRE, " & ")));
		$worksheet->write("AT".$x,utf8_decode(rtrim($C_RECOU_TOTAL, " & ")));
		$worksheet->write("AU".$x,utf8_decode(rtrim($C_RECOU_HELOPHYTE, " & ")));
		$worksheet->write("AV".$x,utf8_decode(rtrim($C_RECOU_HYDROPHYTE_E, " & ")));
		$worksheet->write("AW".$x,utf8_decode(rtrim($C_RECOU_HYDROPHYTE_NE, " & ")));
		$worksheet->write("AX".$x,utf8_decode(rtrim($C_RECOU_ALGUE, " & ")));
		$worksheet->write("AY".$x,utf8_decode(rtrim($C_RECOU_EAU_LIBRE, " & ")));
		$worksheet->write("AZ".$x,utf8_decode(rtrim($C_RECOU_NON_VEGET, " & ")));
		$worksheet->write("BA".$x,utf8_decode(rtrim($EMBROUS, " & ")));
		$worksheet->write("BB".$x,utf8_decode(rtrim($OMBRAGE, " & ")));
		$worksheet->write("BC".$x,utf8_decode(rtrim($PATRIMOINE, " & ")));
		$worksheet->write("BD".$x,utf8_decode(rtrim($PATRIMOINE_AUTRE, " & ")));
		$worksheet->write("BE".$x,utf8_decode(rtrim($C_COMT, " & ")));
		$worksheet->write("BF".$x,utf8_decode(rtrim($ALIMENTATION, " & ")));
		$worksheet->write("BG".$x,utf8_decode(rtrim($ALIMENTATION_AUTRE, " & ")));
		$worksheet->write("BH".$x,utf8_decode(rtrim($CONTEXT, " & ")));
		$worksheet->write("BI".$x,utf8_decode(rtrim($DECHETS, " & ")));
		$worksheet->write("BJ".$x,utf8_decode(rtrim($TAXON, " & ")));
		$worksheet->write("BK".$x,utf8_decode(rtrim($NOM_VERNACULAIRE, " & ")));
		$worksheet->write("BL".$x,utf8_decode(rtrim($TAXON_VEG, " & ")));
		$worksheet->write("BM".$x,utf8_decode(rtrim($NOM_VERNACULAIRE_VEG, " & ")));
		$worksheet->write("BN".$x,utf8_decode(rtrim($POURCENTAGE, " & ")));
		$worksheet->write("BO".$x,utf8_decode(rtrim($FAUNE, " & ")));
		$worksheet->write("BP".$x,utf8_decode(rtrim($LIAISON, " & ")));
		$worksheet->write("BQ".$x,utf8_decode(rtrim($LIAISON_AUTRE, " & ")));
		$worksheet->write("BR".$x,utf8_decode(rtrim($TRAVAUX, " & ")));
		$worksheet->write("BS".$x,utf8_decode(rtrim($TRAVAUX_AUTRE, " & ")));
		$worksheet->write("BT".$x,utf8_decode(rtrim($USAGE, " & ")));
		
		
		//ON FAIS POUR LES OBSERVATION FAUNE FLORE
		
		$req_filtre_obsv = pg_query($bdd, 'SELECT  "L_STRP", observation."L_ID",  observation."O_DATE", observation."O_REFE", observation."O_NLAT", observation."O_REID", observation."O_NVER", 
												   observation."O_REF2", observation."O_NLA2", observation."O_REI2", observation."O_NBRE", observation."O_NBRT", o_sacq."SACQ", 
												   observation."O_COMT", observateur."OBS_NOM_PRENOM", (SELECT "STRUCTURE" FROM saisie_observation.structure WHERE structure."S_ID"::text = "L_STRP"), o_styp."STYP"
											FROM saisie_observation.observation, menu_deroulant.o_sacq, saisie_observation.observateur, saisie_observation.structure, menu_deroulant.o_styp, saisie_observation.localisation
											WHERE saisie_observation.observation."L_ID" = saisie_observation.localisation."L_ID"
											AND observation."O_SACQ" = o_sacq."ID"
											AND observation."O_OBSV" = saisie_observation.observateur."ID"
											AND observation."O_STYP" = menu_deroulant.o_styp."ID"
											
											AND observation."L_ID" = '."'".$resultat['L_ID']."'".'
											AND saisie_observation.localisation."L_STRP"::text = saisie_observation.structure."S_ID"::text
											ORDER BY observation."L_ID"');
		
		
		while($resultat_obser = pg_fetch_array($req_filtre_obsv)){
			$O_DATE = date('d/m/Y', $resultat_obser['O_DATE'])." & ".$O_DATE;
			$O_REFE = $resultat_obser['O_REFE']." & ".$O_REFE;
			$O_NLAT = $resultat_obser['O_NLAT']." & ".$O_NLAT;
			$O_REID = $resultat_obser['O_REID']." & ".$O_REID;
			$O_NVER = $resultat_obser['O_NVER']." & ".$O_NVER;
			$O_REF2 = $resultat_obser['O_REF2']." & ".$O_REF2;
			$O_NLA2 = $resultat_obser['O_NLA2']." & ".$O_NLA2;
			$O_REI2 = $resultat_obser['O_REI2']." & ".$O_REI2;
			$O_NBRE = $resultat_obser['O_NBRE']." & ".$O_NBRE;
			$O_NBRT = $resultat_obser['O_NBRT']." & ".$O_NBRT;
			$SACQ = $resultat_obser['SACQ']." & ".$SACQ;
			$O_COMT = $resultat_obser['O_COMT']." & ".$O_COMT;
			$OBS_NOM_PRENOM_OBSERVATION = $resultat_obser['OBS_NOM_PRENOM']." & ".$OBS_NOM_PRENOM_OBSERVATION;
			$STRUCTURE_OBSERVATION = $resultat_obser['STRUCTURE']." & ".$STRUCTURE_OBSERVATION;
			$STYP = $resultat_obser['STYP']." & ".$STYP;
		}
		
		
		$worksheet->write("BU".$x,rtrim($O_DATE, " & "));
		$worksheet->write("BV".$x,rtrim($O_REFE, " & "));
		$worksheet->write("BW".$x,utf8_decode(rtrim($O_NLAT, " & ")));
		$worksheet->write("BX".$x,rtrim($O_REID, " & "));
		$worksheet->write("BY".$x,utf8_decode(rtrim($O_NVER, " & ")));
		$worksheet->write("BZ".$x,rtrim($O_REF2, " & "));
		$worksheet->write("CA".$x,rtrim($O_NLA2, " & "));
		$worksheet->write("CB".$x,rtrim($O_REI2, " & "));
		$worksheet->write("CC".$x,rtrim($O_NBRE, " & "));
		$worksheet->write("CD".$x,utf8_decode(rtrim($O_NBRT, " & ")));
		$worksheet->write("CE".$x,utf8_decode(rtrim($SACQ, " & ")));
		$worksheet->write("CF".$x,utf8_decode(rtrim($O_COMT, " & ")));
		$worksheet->write("CG".$x,utf8_decode(rtrim($OBS_NOM_PRENOM_OBSERVATION, " & ")));
		$worksheet->write("CH".$x,utf8_decode(rtrim($STRUCTURE_OBSERVATION, " & ")));
		$worksheet->write("CI".$x,utf8_decode(rtrim($STYP, " & ")));
			
				
	$x++;
	// $ID_MARE = $resultat['L_ID'];
}

$workbook->close(); // on ferme le fichier Excel creer

?>

<a href="pram_exportation.xls">Telecharger le fichier</a>