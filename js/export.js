function load_personnes_ajax () {
    $.ajax({
        url      : "php/ajax/projets/personnes_liste.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            personnes_liste = data ;
            let personnes_liste_array = [];
            for (const personne in personnes_liste) {
                personnes_liste_array.push(personnes_liste[personne].id+' - '+personnes_liste[personne].name);
            }
            personnes_liste_array.sort();
            autocompleteArray_personnes(document.getElementById("input_personnes"), personnes_liste_array);
            }
    });
}
//export excel temps des projets
document.getElementById("export_p_2021").addEventListener("click", function() {
    export_(2021);
});
document.getElementById("export_p_2022").addEventListener("click", function() {
    export_(2022);
});
document.getElementById("export_p_2023").addEventListener("click", function() {
    export_(2023);
});
document.getElementById("export_p_2024").addEventListener("click", function() {
    export_(2024);
});

//export excel temps des projets GLOBAL
document.getElementById("export_2021").addEventListener("click", function() {
    export_global(2021);
});
document.getElementById("export_2022").addEventListener("click", function() {
    export_global(2022);
});
document.getElementById("export_2023").addEventListener("click", function() {
    export_global(2023);
});
document.getElementById("export_2024").addEventListener("click", function() {
    export_global(2024);
});

function export_(year) {
    if (document.getElementById("input_personnes").value !== '') {
        change_load('chargement des données veuillez patienter...');
        $.ajax({
            url: "php/export_excel/export_temps_personne_year.php",
            type: "POST",
            dataType: "text",
            async    : true,
            data: {
                'year': year,
                'nom_personne': document.getElementById("input_personnes").value.split(' - ')[1]
            },
            error    : function(request, error) { 
                alert("Erreur : responseText: "+request.responseText);
                change_load();
                },
            success  : function(data) {
                console.log(data);
                change_load();
                window.location = 'php/files/'+data;
                }
        });
    } else {
        alert('Aucune personne n\'est selectionnée');
    }
}
function export_global(year) {
    change_load('chargement des données veuillez patienter...');
    $.ajax({
        url: "php/export_excel/export_global.php",
        type: "POST",
        dataType: "text",
        async    : true,
        data: {
            'year': year
        },
        error    : function(request, error) { 
            alert("Erreur : responseText: "+request.responseText);
            change_load();
            },
        success  : function(data) {
            console.log(data);
            window.location = 'php/files/'+data;
            change_load();
            }
    });
}