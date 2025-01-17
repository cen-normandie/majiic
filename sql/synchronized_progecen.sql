--DROP TABLE IF EXISTS progecen_copy.action;
--DROP TABLE IF EXISTS progecen_copy.projet;
--DROP TABLE IF EXISTS progecen_copy.temps_personnes;
--DROP TABLE IF EXISTS progecen_copy.fdtada;
--DROP TABLE IF EXISTS progecen_copy.fdttech;
--DROP TABLE IF EXISTS progecen_copy.liste_action;
--DROP TABLE IF EXISTS progecen_copy.liste_cout CASCADE;
DROP TABLE IF EXISTS progecen_copy.liste_action;
CREATE TABLE progecen_copy.liste_action as 
(
	SELECT 
	ROW_NUMBER () OVER () as id_action,
	code_action as definition,
	''::varchar(1) as niveau
	FROM progecen_copy.actions GROUP BY 2 ORDER BY 2,1
);

/*
update progecen_copy.group set
personnes = (SELECT string_agg(technicien, ' , ') FROM progecen_copy.liste_equipetechnique_rouen)
where id_group = 'GE_ROUEN';
update progecen_copy.group set
personnes = (SELECT string_agg(technicien, ' , ') FROM progecen_copy.liste_equipezoot_rouen)
where id_group = 'ZOO';
update progecen_copy.group set
personnes = ('Samuel Vigot , Yann Gary , Frédéric Labaune , Antonin Lepeltier')
where id_group = 'GE_CAEN';
*/

/*
DROP TABLE IF EXISTS progecen_copy.liste_equipetechnique_rouen CASCADE;
DROP TABLE IF EXISTS progecen_copy.liste_equipezoot_rouen CASCADE;
DROP VIEW IF EXISTS progecen_copy.analyse_financeur;
DROP VIEW IF EXISTS progecen_copy.analyse_prime;
DROP VIEW IF EXISTS progecen_copy.analyse_temps_financeur;
*/

--PROJETS
DELETE FROM progecen_copy.projets;
INSERT INTO progecen_copy.projets(
	id_projet, 
	nom_projet, 
	territoire, 
	type_projet, 
	date_debut, 
	date_fin, 
	etat, 
	responsable_projet, 
	multi_site, 
	nombre_financeur, 
	commentaire_projet, 
	annee_saisie)
SELECT 
id, 
nom_projet, 
territoire, 
type_projet, 
date_debut, 
date_fin, 
etat, 
responsable_projet, 
multi_site, 
nombre_financeur, 
remarque_projet, 
annee_saisie
	FROM bd_progecen.projet;

--ACTION
DELETE FROM progecen_copy.actions;
INSERT INTO progecen_copy.actions(
	id_action, 
	code_action, 
	financements, 
	site, 
	personnes, 
	id_projet, 
	nb_h_previ
)
SELECT 
id, 
code_action, 
(
	(select CASE WHEN financeur1 is not null THEN financeur1||'_'||pourcent_f1::int::text||'|' ELSE null END FROM bd_progecen.action  where id=a.id )
	||
	(select CASE WHEN financeur2 is not null THEN '|'||financeur2||'_'||pourcent_f2::int::text ELSE '|ø_ø' END FROM bd_progecen.action  where id=a.id )
	||
	(select CASE WHEN financeur3 is not null THEN '|'||financeur3||'_'||pourcent_f3::int::text ELSE '|ø_ø' END FROM bd_progecen.action  where id=a.id )
	||
	(select CASE WHEN financeur4 is not null THEN '|'||financeur4||'_'||pourcent_f4::int::text ELSE '|ø_ø' END FROM bd_progecen.action  where id=a.id )
	||
	(select CASE WHEN financeur5 is not null THEN '|'||financeur5||'_'||pourcent_f5::int::text ELSE '|ø_ø' END FROM bd_progecen.action  where id=a.id )
),
replace(site, 'NaN', ''), 
personne, 
id_projet, 
nb_heure
	FROM bd_progecen.action a;
update progecen_copy.actions set financements = replace (financements,'|ø_ø', '');
update progecen_copy.actions set financements = replace (financements,'|ø_ø', '');
update progecen_copy.actions set financements = replace (financements,'|ø_ø', '');
update progecen_copy.actions set financements = replace (financements,'|ø_ø', '');
update progecen_copy.actions set financements = replace (financements,'|ø_ø', '');

--TEMPS
-- FROM FDW
DELETE FROM progecen_copy.temps;
INSERT INTO progecen_copy.temps(
	e_id, 
	e_id_projet, 
	--e_nom_projet, 
	e_id_action, 
	e_nom_action, 
	e_id_site, 
	e_objet, 
	e_start, 
	e_end, 
	e_lieu, 
	e_commentaire, 
	e_personne, 
	e_nb_h, 
	e_date_saisie, 
	e_salissure, 
	e_panier, 
	e_date_valide_panier,
	e_date_saisie_salissure, 
	e_date_valide_salissure
)

SELECT 
id, 
id_projet, 
coalesce((select a.id from bd_progecen.action a  
 	left join bd_progecen.projet p
 	on a.id_projet = p.id
 	where temps_personnes.id_action = a.code_action and temps_personnes.id_projet = p.id and temps_personnes.personne = a.personne 
 limit 1),row_number() over() ),
id_action, 
id_site, 
objet, 
date_debut, 
date_fin, 
lieu, 
remarque, 
personne, 
nb_heures, 
date_saisie, 
case when coalesce(salissure,0) = 1 THEN true else false end , 
case when coalesce(panier,0) = 1 THEN true else false end, 
date_valide_panier, 
date_saisie_salissure, 
date_valide_salissure
	FROM bd_progecen.temps_personnes;

-- UPDATE sur la table temps l'id_action est nom de l'action et pas un id . il faut ajouter un filtre sur le site également.
INSERT INTO progecen_copy.temps_by_action(
	e_id, 
	e_id_projet, 
	--e_nom_projet, 
	e_id_action, 
	e_nom_action, 
	e_id_site, 
	e_objet, 
	e_start, 
	e_end, 
	e_lieu, 
	e_commentaire, 
	e_personne, 
	e_nb_h, 
	e_date_saisie, 
	e_salissure, 
	e_panier, 
	e_date_valide_panier,
	e_date_saisie_salissure, 
	e_date_valide_salissure
)
SELECT 
id, 
id_projet, 
coalesce((select a.id from bd_progecen.action a  
 	left join bd_progecen.projet p
 	on a.id_projet = p.id
 	where temps_personnes.id_action = a.code_action 
		  and temps_personnes.id_projet = p.id 
		  and temps_personnes.personne = a.personne 
		  and temps_personnes.id_site = a.site -->ADD ###################
limit 1
		 ),row_number() over() ),
id_action, 
id_site, 
objet, 
date_debut, 
date_fin, 
lieu, 
remarque, 
personne, 
nb_heures, 
date_saisie, 
case when coalesce(salissure,0) = 1 THEN true else false end , 
case when coalesce(panier,0) = 1 THEN true else false end, 
date_valide_panier, 
date_saisie_salissure, 
date_valide_salissure
	FROM bd_progecen.temps_personnes;

-- VOIR LES ERREURS + de 12 000 lignes
-- ### ERRORS code action projet faux
/*with t_ok as (select * from 
	progecen_copy.temps_by_action
)
SELECT 
temps.e_id, 			t_ok_r.e_id as _e_id ,  
temps.e_id_projet, 		t_ok_r.e_id_projet as _e_id_projet ,  
temps.e_nom_projet, 	t_ok_r.e_nom_projet as _e_nom_projet ,  
temps.e_id_action, 		t_ok_r.e_id_action as _e_id_action ,  
temps.e_id_action, 		t_ok_r.e_id_action as _e_id_action ,  
temps.e_nom_action, 	t_ok_r.e_nom_action as _e_nom_action ,  
temps.e_id_site, 		t_ok_r.e_id_site as _e_id_site ,  
temps.e_objet, 			t_ok_r.e_objet as _e_objet ,  
temps.e_start, 			t_ok_r.e_start as _e_start ,  
temps.e_end, 			t_ok_r.e_end as _e_end ,  
temps.e_commentaire, 	t_ok_r.e_commentaire as _e_commentaire ,  
temps.e_personne, 		t_ok_r.e_personne as _e_personne ,  
temps.e_nb_h, 			t_ok_r.e_nb_h as _e_nb_h
	FROM progecen_copy.temps
	left join t_ok as t_ok_r on (t_ok_r.e_id = temps.e_id and t_ok_r.e_start = temps.e_start and t_ok_r.e_id_projet = temps.e_id_projet and t_ok_r.e_personne = temps.e_personne)
where 
temps.e_id not like '%-%'
and 
 exists (
select * from t_ok
	 where 
	 (t_ok.e_id = temps.e_id and (t_ok.e_id_action <> temps.e_id_action) )
)
*/




--Suppression de l'historique
DELETE FROM progecen_copy.temps_suivi;

--Suppression des events negatifs
DELETE  from progecen_copy.temps
where e_end < e_start;

--Mise à jour du nom_projet dans les temps 
update progecen_copy.temps set 
e_nom_projet = p.nom_projet
from progecen_copy.projets p
where p.id_projet::text = e_id_projet;

--Correction des champs sites dans action + temps
UPDATE progecen_copy.actions set 
site = REPLACE(site, '00AAA', 'ø site' )
WHERE site like '%00AAA%';
UPDATE progecen_copy.actions set 
site = REPLACE(site, 'NaN', '' )
WHERE site like '%NaN%';
UPDATE progecen_copy.temps set 
e_id_site = REPLACE(e_id_site, '00AAA', 'ø site' )
WHERE e_id_site like '%00AAA%';
UPDATE progecen_copy.temps set 
e_id_site = REPLACE(e_id_site, 'NaN', '' )
WHERE e_id_site like '%NaN%';

UPDATE progecen_copy.actions set 
financements = REPLACE(financements, '||', '|' );