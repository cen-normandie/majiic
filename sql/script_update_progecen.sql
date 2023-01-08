/*script_evolution progecen*/


/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */
--Renommer la table projet --> projets et ajout de champs
alter table progecen_copy.projet
add column date_demande_solde date;
alter table progecen_copy.projet
add column date_butoir_dossier date;
CREATE TABLE progecen_copy.projets
(
    id_projet integer NOT NULL primary key,
    nom_projet text COLLATE pg_catalog."default",
	groupe_projet text COLLATE pg_catalog."default",
    territoire text COLLATE pg_catalog."default",
    type_projet text COLLATE pg_catalog."default",
    date_debut date,
    date_fin date,
    etat text COLLATE pg_catalog."default",
    responsable_projet text COLLATE pg_catalog."default",
    multi_site text COLLATE pg_catalog."default",
    nombre_financeur text COLLATE pg_catalog."default",
    remarque_projet text COLLATE pg_catalog."default",
    annee_saisie integer,
    date_demande_solde date,
    date_butoir_dossier date,
    tags text COLLATE pg_catalog."default",
	documents text
);

INSERT INTO progecen_copy.projets (
	id_projet, 
	nom_projet, 
	groupe_projet, 
	territoire, 
	type_projet, 
	date_debut, 
	date_fin, 
	etat, 
	responsable_projet, 
	multi_site, 
	nombre_financeur, 
	remarque_projet, 
	annee_saisie, 
	date_demande_solde, 
	date_butoir_dossier
)
SELECT id::integer, nom_projet, id_projet, territoire, type_projet, date_debut, date_fin, etat, responsable_projet, multi_site, nombre_financeur, remarque_projet, annee_saisie, date_demande_solde, date_butoir_dossier
	FROM progecen_copy.projet;
alter table progecen_copy.projets 
add column color text default '#3a5a6a';
update progecen_copy.projets  set
color = '#3a5a6a';
alter table progecen_copy.projets add column h_total int default 0;


/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */
--Renommer la table liste_cout --> couts
 CREATE TABLE progecen_copy.couts
(
    id serial primary key,
    personne text ,
    nb_h_j double precision,
    annee integer,
    cout_env double precision,
    cout_reel double precision
);
INSERT INTO progecen_copy.couts(
personne,
nb_h_j,
annee,
cout_env,
cout_reel)
SELECT personne, nb_heure_journaliere, annee, cout_env, cout_reel
	FROM progecen_copy.liste_cout;

/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */
--Modifications de la table action --> actions 
DROP TABLE if exists progecen_copy.actions;
--simplification des financements
DROP TABLE if exists progecen_copy.actions;
CREATE TABLE progecen_copy.actions
(
    id serial primary key,
    code_action text  NOT NULL,
    financements text,
    site text COLLATE pg_catalog."default",
    personne text COLLATE pg_catalog."default",
    nb_h double precision,
    id_projet integer,
    id_bdd text COLLATE pg_catalog."default"
);
INSERT INTO progecen_copy.actions (
code_action,
financements,
site,
personne,
nb_h,
id_projet,
id_bdd
)
SELECT 
code_action, 
(
CASE WHEN financeur1 is not null THEN financeur1::text ||'_'||pourcent_f1::text||' ' ELSE null END ||
CASE WHEN financeur2 is not null THEN financeur2::text ||'_'||pourcent_f2::text||' ' ELSE null END ||
CASE WHEN financeur3 is not null THEN financeur3::text ||'_'||pourcent_f3::text||' ' ELSE null END ||
CASE WHEN financeur4 is not null THEN financeur4::text ||'_'||pourcent_f4::text||' ' ELSE null END ||
CASE WHEN financeur5 is not null THEN financeur5::text ||'_'||pourcent_f5::text||' ' ELSE null END ||
CASE WHEN financeur6 is not null THEN financeur6::text ||'_'||pourcent_f6::text||' ' ELSE null END ||
CASE WHEN financeur7 is not null THEN financeur7::text ||'_'||pourcent_f7::text||' ' ELSE null END ||
CASE WHEN financeur8 is not null THEN financeur8::text ||'_'||pourcent_f8::text||' ' ELSE null END ||
CASE WHEN financeur9 is not null THEN financeur9::text ||'_'||pourcent_f9::text||' ' ELSE null END ||
CASE WHEN financeur10 is not null THEN financeur10::text ||'_'||pourcent_f10::text||' ' ELSE null END ||
CASE WHEN financeur11 is not null THEN financeur11::text ||'_'||pourcent_f11::text||' ' ELSE null END ||
CASE WHEN financeur12 is not null THEN financeur12::text ||'_'||pourcent_f12::text||' ' ELSE null END ||
CASE WHEN financeur13 is not null THEN financeur13::text ||'_'||pourcent_f13::text||' ' ELSE null END ||
CASE WHEN financeur14 is not null THEN financeur14::text ||'_'||pourcent_f14::text||' ' ELSE null END ||
CASE WHEN financeur15 is not null THEN financeur15::text ||'_'||pourcent_f15::text||' ' ELSE null END 
) as financements,
site, 
personne, 
nb_heure, 
id_projet, 
id_bdd
FROM progecen_copy.action;
ALTER TABLE progecen_copy.actions
  RENAME COLUMN  id TO id_action;

/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */
--Modifications de la table temps_personnes --> temps
CREATE TABLE progecen_copy.temps
(
    e_id serial NOT NULL primary key,
    e_id_projet integer NOT NULL,
	e_nom_projet text COLLATE pg_catalog."default",
    e_id_action text COLLATE pg_catalog."default" NOT NULL,
    e_nom_action text COLLATE pg_catalog."default",
    e_id_site text COLLATE pg_catalog."default",
    e_objet text COLLATE pg_catalog."default",
    e_start timestamp with time zone,
    e_end timestamp with time zone,
    e_lieu text COLLATE pg_catalog."default",
    e_remarques text COLLATE pg_catalog."default",
    e_personne text COLLATE pg_catalog."default",
    e_nb_h double precision,
    e_date_saisie timestamp without time zone DEFAULT (now())::date,
    e_salissure boolean DEFAULT false,
    e_panier boolean DEFAULT false,
    e_date_saisie_salissure timestamp without time zone DEFAULT (now())::date,
    e_date_valide_panier date,
	e_date_valide_salissure date
);
INSERT INTO progecen_copy.temps (
	e_id_projet, 
	e_nom_projet, 
	e_id_action, 
	e_id_site, 
	e_objet, 
	e_start, 
	e_end, 
	e_lieu, 
	e_remarques, 
	e_personne, 
	e_nb_h, 
	e_date_saisie, 
	e_salissure, 
	e_panier, 
	e_date_saisie_salissure, 
	e_date_valide_panier, 
	e_date_valide_salissure
)
SELECT 
id_projet, 
nom_projet,
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
CASE WHEN salissure = 1 THEN true ELSE false END, 
CASE WHEN panier = 1 THEN true ELSE false END,
date_saisie_salissure, 
date_valide_panier,
date_valide_salissure
	FROM progecen_copy.temps_personnes;
    
/* ATTENTION LA COLONNE id_projet n'est pas bonne */
--Suppression colonne id
--ajout colonne groupe de projet

--Modification type e_id d'integer vers text(uuid)
ALTER TABLE progecen_copy.temps
ALTER COLUMN e_id TYPE text USING e_id::text;
ALTER TABLE progecen_copy.temps 
ALTER COLUMN e_id_projet TYPE text USING e_id_projet::text;
ALTER TABLE progecen_copy.temps
ADD COLUMN e_color_selected text;
--Defaut uuid on temps
ALTER TABLE progecen_copy.temps ALTER COLUMN e_id SET DEFAULT uuid_generate_v4()::text;

/* Ajout champ real previ sur table actions et projets */
/* AJOUT DES CHAMPS */
ALTER TABLE progecen_copy.actions ADD COLUMN nb_h_previ numeric;
ALTER TABLE progecen_copy.actions ADD COLUMN nb_h_real numeric;
ALTER TABLE progecen_copy.actions ALTER COLUMN nb_h_real TYPE numeric USING nb_h::numeric;
ALTER TABLE progecen_copy.actions DROP COLUMN nb_h;

ALTER TABLE progecen_copy.projets ADD COLUMN nb_h_previ numeric;
ALTER TABLE progecen_copy.projets ADD COLUMN nb_h_real numeric;
ALTER TABLE progecen_copy.projets ALTER COLUMN nb_h_real TYPE numeric USING h_total::numeric;
ALTER TABLE progecen_copy.projets DROP COLUMN h_total;



/* FONCTION generant un uuid */
CREATE OR REPLACE FUNCTION public.uuid_calendar()
RETURNS text
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE 
AS $BODY$
BEGIN
                RETURN md5(random()::text || clock_timestamp()::text)::uuid;
        END;
$BODY$;

/* FONCTION trigger calculant e_nb_h */
DROP TRIGGER IF EXISTS trigger_calcul_nb_h_temps ON progecen_copy.temps;
DROP FUNCTION IF EXISTS progecen_copy.calcul_nb_h_temps();

CREATE FUNCTION progecen_copy.calcul_nb_h_temps()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
declare
BEGIN 
	UPDATE progecen_copy.temps SET e_nb_h = EXTRACT(epoch FROM (NEW.e_end - NEW.e_start)::interval )/3600 
	WHERE e_id = NEW.e_id;

	--Update table ACTIONS heure projet total
	with t as (
		select sum(e_nb_h) as total  from progecen_copy.temps
		where e_id_projet = new.e_id_projet
		and e_id_action = new.e_id_action
		and e_personne = new.e_personne
	)
	update progecen_copy.actions set nb_h_real = t.total
	from t 
	where actions.id_projet = new.e_id_projet::integer
	and actions.id_action = new.e_id_action::integer
	and actions.personnes ~* new.e_personne;
	--Update table PROJETS
	with t as (
		select sum(nb_h_real) as total  from progecen_copy.actions 
		where id_projet = new.e_id_projet::integer
	)
	update progecen_copy.projets set nb_h_real = t.total
	from t where projets.id_projet = new.e_id_projet::integer;

RETURN NULL; 
END;
$BODY$;

/* trigger */
CREATE TRIGGER trigger_calcul_nb_h_temps
    AFTER INSERT OR UPDATE
    ON progecen_copy.temps
	FOR EACH ROW
	WHEN ((pg_trigger_depth() < 1))
    EXECUTE PROCEDURE progecen_copy.calcul_nb_h_temps();

/* L'info des responsables projets sera ajouté à la table users.users bd majiic */
alter table users.users add column u_responsable boolean default false;

/* CREATION DE LA TABLE financeurs */
drop table if exists progecen_copy.financeurs;
create table progecen_copy.financeurs (
id_financeur serial not null primary key,
nom_financeur text,
commentaire text
);
INSERT INTO progecen_copy.financeurs(id_financeur,nom_financeur)
select id_financeur::integer, financeur from progecen_copy.liste_financeur;
with cf as (select count(*)+1 as nb from progecen_copy.financeurs)
SELECT setval('progecen_copy.financeurs_id_financeur_seq', cf.nb , true) from cf;


ALTER TABLE progecen_copy.actions
  RENAME COLUMN personne TO personnes;

ALTER TABLE progecen_copy.projets
    ADD COLUMN sites text default '';


/* TRIGGER LISTE SITE */
drop function if exists progecen_copy.update_sites_liste_projets();
CREATE FUNCTION progecen_copy.update_sites_liste_projets()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
declare
BEGIN 
	WITH a as 
	(select string_agg(site,', ') as sites_ , id_projet from progecen_copy.actions where id_projet = NEW.id_projet  and site <> '' group by 2 order by 1)
	UPDATE progecen_copy.projets p SET sites = a.sites_
	FROM a
	WHERE p.id_projet = a.id_projet;
RETURN NULL; 
END;
$BODY$;
CREATE TRIGGER trigger_update_sites_liste_projets
    AFTER INSERT OR UPDATE 
    ON progecen_copy.actions
    FOR EACH ROW
    WHEN ((pg_trigger_depth() < 1))
    EXECUTE PROCEDURE progecen_copy.update_sites_liste_projets();




CREATE TABLE progecen_copy.files
(
    id integer NOT NULL DEFAULT nextval('progecen_copy.files_id_seq'::regclass),
    id_projet integer NOT NULL,
    file_name text COLLATE pg_catalog."default",
    CONSTRAINT files_pkey PRIMARY KEY (id)
);

CREATE FUNCTION progecen_copy.update_projets_files()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
declare
BEGIN 
	WITH a as 
	(select string_agg(file_name,', ') as files_ , id_projet from progecen_copy.files where id_projet = NEW.id_projet  group by 2 order by 1)
	UPDATE progecen_copy.projets p SET files = a.files_
	FROM a
	WHERE p.id_projet = a.id_projet;
RETURN NULL; 
END;
$BODY$;

CREATE TRIGGER trigger_files
    AFTER INSERT OR UPDATE OR DELETE
    ON progecen_copy.files
    FOR EACH ROW
    WHEN ((pg_trigger_depth() < 1))
    EXECUTE PROCEDURE progecen_copy.update_projets_files();


/* SUIVI du TEMPS */
CREATE TABLE progecen_copy.temps_suivi
(
	op text,
	date_op timestamp with time zone,
    e_id text COLLATE pg_catalog."default" NOT NULL,
    e_id_projet text COLLATE pg_catalog."default" NOT NULL,
    e_nom_projet text COLLATE pg_catalog."default",
    e_id_action text COLLATE pg_catalog."default" NOT NULL,
    e_nom_action text COLLATE pg_catalog."default",
    e_id_site text COLLATE pg_catalog."default",
    e_objet text COLLATE pg_catalog."default",
    e_start timestamp with time zone,
    e_end timestamp with time zone,
    e_lieu text COLLATE pg_catalog."default",
    e_commentaire text COLLATE pg_catalog."default",
    e_personne text COLLATE pg_catalog."default",
    e_nb_h double precision,
    e_date_saisie timestamp without time zone DEFAULT (now())::timestamp without time zone,
    e_salissure boolean DEFAULT false,
    e_panier boolean DEFAULT false,
    e_date_saisie_salissure timestamp without time zone DEFAULT (now())::timestamp without time zone,
    e_date_valide_panier date,
    e_date_valide_salissure date
);
CREATE FUNCTION progecen_copy.f_temps_suivi()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
BEGIN
IF (TG_OP = 'DELETE') THEN 
INSERT INTO progecen_copy.temps_suivi SELECT 'DELETE', now(), OLD.*; 
RETURN OLD; 
ELSIF (TG_OP = 'UPDATE') THEN 
INSERT INTO progecen_copy.temps_suivi SELECT 'UPDATE', now(), NEW.*; 
RETURN NEW; 
ELSIF (TG_OP = 'INSERT') THEN 
INSERT INTO progecen_copy.temps_suivi SELECT 'INSERT', now(), NEW.*; 
RETURN NEW; 
END IF; 
RETURN NULL; 
END;
$BODY$;

CREATE TRIGGER suivi_temps
    AFTER INSERT OR DELETE OR UPDATE 
    ON progecen_copy.temps
    FOR EACH ROW
    EXECUTE PROCEDURE progecen_copy.f_temps_suivi();

/* CREATE TABLE GROUP for GE_CAEN GE_ROUEN */
create table progecen_copy.group (
id_group text primary key,
personnes text
);
insert into progecen_copy.group (id_group, personnes) values ('GE_CAEN', 'Simon Deliquaire Benoit Perceval');
insert into progecen_copy.group (id_group, personnes) values ('GE_ROUEN', 'Fabien Deblangy Benoit Perceval');

/*CREATE TABLE temps import tmp*/
--not used yet
/* CREATE TABLE progecen_copy.temps_import
(
    e_id_projet text --can be null not used,
    e_nom_projet text COLLATE pg_catalog."default",
    e_id_action text COLLATE pg_catalog."default" NOT NULL,
    e_nom_action text COLLATE pg_catalog."default",
    e_id_site text COLLATE pg_catalog."default",
    e_objet text COLLATE pg_catalog."default",
    e_start timestamp with time zone,
    e_end timestamp with time zone,
    e_lieu text COLLATE pg_catalog."default",
    e_commentaire text COLLATE pg_catalog."default",
    e_personne text COLLATE pg_catalog."default",
    e_nb_h double precision,
    e_date_saisie timestamp without time zone DEFAULT (now())::timestamp without time zone,
    e_salissure boolean DEFAULT false,
    e_panier boolean DEFAULT false,
    e_date_saisie_salissure timestamp without time zone DEFAULT (now())::timestamp without time zone,
    e_date_valide_panier date,
    e_date_valide_salissure date
); */


/* LISTE DES responsables depuis la table users.users
select '<option value = "'||u_id||' - '||u_prenom||' '||u_nom ||'">'||u_id||' - '||u_prenom||' '||u_nom ||'</option>', u_id,
'"'||u_prenom||' '||u_nom||'"=>"'||u_id||' - '||u_prenom||' '||u_nom||'",'
	FROM users.users
where u_responsable is true 
order by 2;
*/

/*/////////////////////////////////////////////////////////
Modifier les table actions et projets pour definir la valeur par defaut des clefs primaires avec nextval(); 
Projets 
    --> nb_h_previ nb_h_real mettre les valeurs par defaut à  0
    --> multisite mettre non par defaut ?




*/


/* CREATION TABLE actions_gp ?? */
/* pour gestion des actions communes --> test_ge_caen */


select  	strpos('Benoit Perceval|Lydie Doisy', 'z') 
where  	strpos('Benoit Perceval|Lydie Doisy', 'z') >0

with personne as (
	select regexp_matches( 'Benoit Perceval|Lydie Doisy', 'z') as z
)
select personne.z[1] from personne where personne.z[1] is not null


select    regexp_matches( 'Benoit Perceval|Lydie Doisy', 'Lydie')::array[1]
select array_to_string(regexp_split_to_array('Benoit Perceval|Lydie Doisy', 'Lydie'))

select   'Lydie' ~* 'Benoit Perceval|Lydie Doisy'





