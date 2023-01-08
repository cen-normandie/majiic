let paniers_to_delete =[];
//Initialisation du tableau datatable
const dtPaniers =$('#panierDT').DataTable({
    "language": {
        "paginate": {
            "previous": "Préc.",
            "next": "Suiv."
        },
        "search": "Filtrer :",
        "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau"
    },
    dom: '<"top"<"d-flex justify-content-between align-items-center"Bf>>t', // export excel -->B :<"top"<"d-flex justify-content-end align-items-center"fB>>t
    buttons: [
    {
        text: 'Valider la selection',
        className: 'btn btn-sm btn-success m-2',
        action: function ( e, dt, node, config ) {
                //var row_ = dtPaniers.rows( { selected: true } ).data();
                //console.log( dtPaniers.rows( { selected: true } )[0] );
                delete_ids_array_panier();
        }
    },
    { 
        extend: 'excel', 
        text:'Excel',
        className: 'btn btn-sm btn-outline-success m-2',
        init: function(api, node, config) {
        }
    }
    ],

    scrollY: '400px',
    scrollCollapse: true,
    paging: false
});

$('#panierDT tbody').on('click', 'tr', function () {
    $(this).toggleClass('selected');
    let id_ = $(this).attr("id");
    if (paniers_to_delete.includes( $(this).attr("id")  ) ) {
        paniers_to_delete = paniers_to_delete.filter(function( item ) {
            return item !== id_
        });
    } else {
        paniers_to_delete.push(id_);
    };
    console.log(paniers_to_delete);
});
dtPaniers.column( 0 ).visible(false);


//Initialisation du tableau datatable
const dtPrimes =$('#primesDT').DataTable({
    "language": {
        "paginate": {
            "previous": "Préc.",
            "next": "Suiv."
        },
        "search": "Filtrer :",
        "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau"
    },
    dom: '<"top"<"d-flex justify-content-end align-items-center"fB>>t', // export excel -->B :<"top"<"d-flex justify-content-end align-items-center"fB>>t
    buttons: [
    { 
        extend: 'excel', 
        text:'Excel',
        className: 'btn btn-sm btn-success m-2',
        init: function(api, node, config) {
            //$(node).removeClass('dt-button')
        }
    }
    ],
    scrollY: '400px',
    scrollCollapse: true,
    paging: false
});
$('#primesDT tbody').on('click', 'tr', function () {
    $(this).toggleClass('selected');
});

function load_paniers_ajax () {
    change_load('Chargement');
    $.ajax({
        url      : "php/ajax/rh/paniers.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            paniers_liste = data ;
            console.log(data);
            for (const panier in paniers_liste) {
                let rowNode = dtPaniers.row.add( [
                    paniers_liste[panier].e_id,
                    paniers_liste[panier].personne, 
                    paniers_liste[panier].eligibilite,
                    paniers_liste[panier].saisie,
                    paniers_liste[panier].validation
                ] ).node().id = paniers_liste[panier].e_id;
            }
            dtPaniers.draw();
            change_load();
            }
    });
}
load_paniers_ajax();

function delete_ids_array_panier () {
    change_load('Validation des paniers');
    let length_ = paniers_to_delete.length;
    let x_ = 0;
    for(const id__ in paniers_to_delete ) {
        
        $.ajax({
            url      : "php/ajax/rh/valide_panier.js.php",
            data     : {id_panier:paniers_to_delete[id__]},
            method   : "POST",
            dataType : "text",
            async    : true,
            error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
            success  : function(data) {
                x_ ++;
                paniers_liste = data ;
                //console.log(data);
                if (x_ = length_ ) {
                    dtPaniers.rows( '.selected' ).remove().draw();
                    paniers_to_delete=[];
                }

                change_load();
                }
        });

        
    }
    
}

/* let rowNode = dtPaniers.row.add( [
    'actions_f[actions].id_action', //id_action
    'actions_f[actions].code_action', //code_action
    'actions_f[actions].financements' ?? '', //financeurs
    'actions_f[actions].site' 
] ).draw(); */