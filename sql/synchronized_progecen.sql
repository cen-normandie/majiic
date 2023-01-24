DROP TABLE IF EXISTS progecen_copy.action;
DROP TABLE IF EXISTS progecen_copy.projet;
DROP TABLE IF EXISTS progecen_copy.temps_personnes;
DROP TABLE IF EXISTS progecen_copy.fdtada;
DROP TABLE IF EXISTS progecen_copy.fdttech;
DROP TABLE IF EXISTS progecen_copy.liste_action;
DROP TABLE IF EXISTS progecen_copy.liste_cout CASCADE;
CREATE TABLE progecen_copy.liste_action as 
(
	SELECT 
	ROW_NUMBER () OVER () as id_action,
	code_action as definition,
	''::varchar(1) as niveau
	FROM progecen_copy.actions GROUP BY 2 ORDER BY 2,1
);

update progecen_copy.group set
personnes = (SELECT string_agg(technicien, ' , ') FROM progecen_copy.liste_equipetechnique_rouen)
where id_group = 'GE_ROUEN';
update progecen_copy.group set
personnes = (SELECT string_agg(technicien, ' , ') FROM progecen_copy.liste_equipezoot_rouen)
where id_group = 'ZOO';
update progecen_copy.group set
personnes = ('Samuel Vigot , Yann Gary , Frédéric Labaune , Antonin Lepeltier')
where id_group = 'GE_CAEN';

DROP TABLE IF EXISTS progecen_copy.liste_equipetechnique_rouen CASCADE;
DROP TABLE IF EXISTS progecen_copy.liste_equipezoot_rouen CASCADE;
DROP VIEW IF EXISTS progecen_copy.analyse_financeur;
DROP VIEW IF EXISTS progecen_copy.analyse_prime;
DROP VIEW IF EXISTS progecen_copy.analyse_temps_financeur;

--PROJETS
--DELETE FROM progecen_copy.projets;
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
--DELETE FROM progecen_copy.actions;
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
	(select CASE WHEN financeur1 is not null THEN financeur1||'_'||pourcent_f1::int::text ELSE null END FROM bd_progecen.action  where id=a.id )
	||
	(select CASE WHEN financeur2 is not null THEN financeur2||'_'||pourcent_f2::int::text ELSE 'ø_ø|' END FROM bd_progecen.action  where id=a.id )
	||
	(select CASE WHEN financeur3 is not null THEN financeur3||'_'||pourcent_f3::int::text ELSE 'ø_ø|' END FROM bd_progecen.action  where id=a.id )
	||
	(select CASE WHEN financeur4 is not null THEN financeur4||'_'||pourcent_f4::int::text ELSE 'ø_ø|' END FROM bd_progecen.action  where id=a.id )
	||
	(select CASE WHEN financeur5 is not null THEN financeur5||'_'||pourcent_f5::int::text ELSE 'ø_ø' END FROM bd_progecen.action  where id=a.id )
),
site, 
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
