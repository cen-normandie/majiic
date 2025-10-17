-- Schéma : sh
-- Ce fichier définit les tables utiles au calcul d'indicateurs écologiques,
-- notamment les coefficients de Landolt (H2O, lumière, température, etc.)

-- Table : sh.h2o
-- Source : Landolt E., et al. (2010). Flora indicativa – Ecological Indicator Values and Biological Attributes of the Flora of Switzerland and the Alps.
-- Cette table contient les valeurs d'humidité du sol (H2O) pour chaque taxon.

CREATE TABLE sh.h2o (
    taxon TEXT PRIMARY KEY,           -- Nom scientifique du taxon
    h2o_value TEXT NOT NULL,          -- Valeur d'humidité (1 à 5, ou 'w' pour variabilité extrême)
    commentaire TEXT                  -- Commentaire ou précision sur la valeur
);

-- Table : sh.light
-- Source : Landolt E., et al. (2010)
-- Valeur d'exigence en lumière (L)

CREATE TABLE sh.light (
    taxon TEXT PRIMARY KEY,
    light_value INTEGER CHECK (light_value BETWEEN 1 AND 5),
    commentaire TEXT
);

-- Table : sh.temperature
-- Source : Landolt E., et al. (2010)
-- Valeur d'exigence thermique (T)

CREATE TABLE sh.temperature (
    taxon TEXT PRIMARY KEY,
    temperature_value INTEGER CHECK (temperature_value BETWEEN 1 AND 5),
    commentaire TEXT
);

-- Table : sh.releve_phyto
-- Table de relevés floristiques utilisée pour croiser les taxons avec les valeurs écologiques

CREATE TABLE sh.releve_phyto (
    releve_id SERIAL PRIMARY KEY,
    site TEXT,
    annee INTEGER,
    plot TEXT,
    transect TEXT,
    taxon TEXT NOT NULL,
    coefficient_int INTEGER DEFAULT 1
);
