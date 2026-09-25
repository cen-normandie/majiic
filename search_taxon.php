<?php session_start(); 
include 'php/properties.php';
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
};
if (!isset($_SESSION['password'])) {
    header('Location: index.php');
    exit();
};
if (!isset($_SESSION['session'])) {
    header('Location: index.php');
    exit();
};
$_SESSION['is_admin'] = false;
$admins = array("f.buissart@cen-normandie.fr", "b.perceval@cen-normandie.fr");
if (in_array($_SESSION['email'], $admins)) {
    $_SESSION['is_admin'] = true;
}
?>
<!doctype html>
<html lang="fr">
  <head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>CEN Normandie</title>
    <link rel="shortcut icon" href="img/CenNormandie.ico" />
    <script>L_PREFER_CANVAS = true;</script>
    
    
    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
    <link href="js/plugins/bs5-datepicker/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
	<link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
  </head>
  <body>
<div class="d-flex w-100 h-100 bg-light" style="min-height:100vh;">
  <?php $_POST["page"] = basename(__FILE__);include("menu.php"); ?>
	<div class="d-flex flex-column col-md-9 col-lg-10 bg-light " >
		<div class="d-flex justify-content-end  bg-dark sticky-top">
			<div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <?php echo $_SESSION['email']; ?></span></div>
			<div class="m-2"><a class="logout text-light" href="php/logout.php" ><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
		</div>
        <div class="d-flex flex-column justify-content-end" style="">
            <div class="d-flex justify-content-start bg-light m-2 border-bottom ">
                <h2 class="bebas">Recherche de Taxons</h2>
                <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem"></div>
            </div>
            <div class="d-flex justify-content-start bg-light m-2 border-bottom ">
                <p class="text-muted">Trouver ici le <span class="text-success"><strong>cd_nom</strong></span> et le <span class="text-danger"><strong>nom valide</strong></span> d'une espèce à partir d'un extrait de texte. exemples : <span class="text-dark"><strong>Rougegorge</strong></span> , <span class="text-warning"><strong>Homo-sapiens</strong></span></p>
            </div>
            <div class="sm m-3">
                <div class="">
                    <div class="d-flex align-items-center">
                        <!-- Colonne gauche -->
                        <div class="col-lg-5">
                            <label class="form-label fw-bold">
                                Taxons à rechercher
                            </label>
                            <textarea id="txtRecherche" class="form-control" rows="15" placeholder="1 taxon par ligne" style="Overflow-y: scroll;"></textarea>
                        </div>
                        <!-- Flèche -->
                        <div class="col-lg-2 text-center d-flex flex-column justify-content-center p-4">
                            <div class="display-4 mb-3 text-dark">
                                →
                            </div>
                            <button id="btnRecherche"
                                    class="btn btn-dark p-2">
                                Rechercher
                            </button>
                            <button id="btnCopier" class="btn btn-success mt-2 visible_s">
                                <i class="far fa-clone me-2"></i> Copier les résultats dans le presse-papiers
                            </button>
                        </div>
                        <!-- Colonne droite -->
                        <div class="col-lg-5">
                            <label class="form-label fw-bold">
                                Taxons trouvés
                            </label>
                            <textarea id="txtResultat" class="form-control" rows="15" readonly style="Overflow-y: scroll;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
		</div>

		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
		</div>
	</div>

</div>



<!-- general.js -->
<script type="text/javascript" src="js/general/general.js" ></script>

<script>

document.getElementById("btnRecherche").addEventListener("click", async () => {
change_load("Calcul en cours");
    const lignes = document
        .getElementById("txtRecherche")
        .value
        .split(/\r?\n/)
        .map(x => x.trim())
        .filter(x => x !== "");

    const response = await fetch("php/ajax/recherche_taxref.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            recherches: lignes
        })
    });

    const data = await response.json();

    let resultat = "";

    data.forEach(r => {
        resultat += `${r.recherche}\t${r.cd_nom}\t${r.cd_ref}\t${r.nom_complet}\t${r.nom_valide}\n`;
    });

    document.getElementById("txtResultat").value = resultat;
    change_load("");
    document.getElementById("btnCopier").classList.remove("visible_s");
});

document.getElementById("btnCopier").addEventListener("click", async () => {

    const texte = document.getElementById("txtResultat").value;

    try {
        await navigator.clipboard.writeText(texte);

        // Optionnel : feedback utilisateur
        const btn = document.getElementById("btnCopier");
        const texteOriginal = btn.textContent;

        btn.textContent = "✅ Copié !";

        setTimeout(() => {
            btn.textContent = texteOriginal;
        }, 2000);

    } catch (err) {
        alert("Impossible de copier le contenu.");
        console.error(err);
    }

});

</script>

  </body>
</html>
