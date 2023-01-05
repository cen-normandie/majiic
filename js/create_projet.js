//Datepickers
$("#p_date_start").datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$("#p_date_end").datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});

//fonction d'enregistrement du projet en cours
function save_projet () {
    const projet_ = new Object();
    projet_.name = document.getElementById("nom_projet").value;
    projet_.responsable_projet = document.getElementById("responsable_projet").value.split(' - ')[1];
    projet_.type_projet = document.getElementById("type_projet").value;
    projet_.etat_projet = document.getElementById("etat_projet").value;
    projet_.echelle_projet = document.getElementById("echelle_projet").value;
    projet_.p_date_start = document.getElementById("p_date_start").value;
    projet_.p_date_end = document.getElementById("p_date_end").value;
    projet_.p_commentaire = document.getElementById("p_commentaire").value;
    projet_.p_color = document.getElementById("p_color").value;
    const ProjetJsonString= JSON.stringify(projet_);
    $.ajax({
        url: "php/ajax/projets/create_projet/create_projet.js.php",
        type: "POST",
        dataType: "json",
        async    : true,
        data: {
            'projet':ProjetJsonString
        },
        error    : function(request, error) { 
            alert("Erreur : responseText: "+request.responseText);
            },
        success  : function(data) {
            console.log(data)
            }
    });   
}
document.getElementById("save_projet").addEventListener("click", function() {
    save_projet();
});