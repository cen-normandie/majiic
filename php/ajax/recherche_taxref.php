<?php

header('Content-Type: application/json; charset=utf-8');
include '../properties.php';
try {

    $pdo = new PDO(
        "pgsql:host=$DBHOST_GEONATURE;dbname=$DBNAME_GEONATURE",
        "$LOGIN_GEONATURE",
        "$PASS_GEONATURE",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Seuil de sélection des candidats trigrammes
    //$pdo->exec("SET pg_trgm.similarity_threshold = 0.4");

    $input = json_decode(file_get_contents('php://input'), true);

    $recherches = $input['recherches'] ?? [];

    $resultats = [];

    $sqlRecherche = "
        WITH p AS (
            SELECT regexp_replace(
                lower(unaccent(:nom)),
                '[^a-z0-9]',
                '',
                'g'
            ) AS recherche
        )
        SELECT *
        FROM (
            SELECT
                t.cd_nom,
                t.nom_complet,
                t.nom_vern,
                t.cd_ref,
                t.nom_valide,
                t.id_rang,

                greatest(
                    similarity(t.nom_complet_norm, p.recherche),
                    similarity(t.nom_vern_norm, p.recherche)
                ) AS score
            FROM taxonomie.taxref_search t
            CROSS JOIN p
            WHERE
                (
                    t.nom_complet_norm % p.recherche
                    OR
                    t.nom_vern_norm % p.recherche
                )
        ) r
        
        ORDER BY r.score DESC
        LIMIT 1
    ";

//WHERE r.score >= 0.4

    $stmtRecherche = $pdo->prepare($sqlRecherche);

    foreach ($recherches as $recherche) {

        $recherche = trim($recherche);

        if ($recherche === '') {
            continue;
        }

        $stmtRecherche->execute([
            'nom' => $recherche
        ]);

        $row = $stmtRecherche->fetch();

        if ($row) {

            $resultats[] = [
                'recherche'   => $recherche,
                'cd_nom'      => $row['cd_nom'],
                'cd_ref'      => $row['cd_ref'],
                'nom_complet' => $row['nom_complet'],
                'nom_valide'  => $row['nom_valide'],
                'id_rang'     => $row['id_rang'],
                'score'       => round($row['score'], 3)
            ];

        } else {

            $resultats[] = [
                'recherche'   => $recherche,
                'cd_nom'      => '',
                'cd_ref'      => '',
                'nom_complet' => '',
                'nom_valide'  => '',
                'id_rang'     => '',
                'score'       => ''
            ];
        }
    }

    echo json_encode(
        $resultats,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}