let paniers_to_delete =[];
let primes_to_delete =[];
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
                delete_ids_array_panier_ok();
        }
    },
    {
        text: 'Refuser la selection',
        className: 'btn btn-sm btn-danger m-2',
        action: function ( e, dt, node, config ) {
                //var row_ = dtPaniers.rows( { selected: true } ).data();
                //console.log( dtPaniers.rows( { selected: true } )[0] );
                delete_ids_array_panier_ko();
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
//Initialisation du tableau datatable
const dtPrimes =$('#primeDT').DataTable({
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
    dom: '<"top"<"d-flex justify-content-between align-items-center"Bf>>t', 
    buttons: [
        {
            text: 'Valider la selection',
            className: 'btn btn-sm btn-success m-2',
            action: function ( e, dt, node, config ) {
                    delete_ids_array_prime_ok();
            }
        },
        {
            text: 'Refuser la selection',
            className: 'btn btn-sm btn-danger m-2',
            action: function ( e, dt, node, config ) {
                    //var row_ = dtPaniers.rows( { selected: true } ).data();
                    //console.log( dtPaniers.rows( { selected: true } )[0] );
                    delete_ids_array_prime_ko();
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

const dtPaniers_traite =$('#paniersDT_traite').DataTable({
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
        extend: 'excel', 
        text:'Exporter',
        className: 'btn btn-sm btn-outline-success m-2',
        init: function(api, node, config) {
        }
    }
    ],
    createdRow: function(row, data, index) {
        if (data[3] != '' ) {
            if ( ( data[3].includes('rh_panier_ok') ) ) {
                $(row).css('background-color', '#38823d24');
            } else if ( ( data[3].includes('rh_panier_ko') ) ) {
                $(row).css('background-color', '#98222224');
            }
        }
    },
    scrollY: '400px',
    scrollCollapse: true,
    paging: false
});
//Initialisation du tableau datatable
const dtPrimes_traite =$('#primeDT_traite').DataTable({
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
    dom: '<"top"<"d-flex justify-content-between align-items-center"Bf>>t', 
    buttons: [
        { 
            extend: 'excel', 
            text:'Exporter',
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
//dtPaniers.column( 2 ).visible(false);
$('#primeDT tbody').on('click', 'tr', function () {
    $(this).toggleClass('selected');
    let id_ = $(this).attr("id");
    if (primes_to_delete.includes( $(this).attr("id")  ) ) {
        primes_to_delete = primes_to_delete.filter(function( item ) {
            return item !== id_
        });
    } else {
        primes_to_delete.push(id_);
    };
    console.log(primes_to_delete);
});
dtPrimes.column( 0 ).visible(false);
//dtPrimes.column( 2 ).visible(false);


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
                    paniers_liste[panier].date_panier,
                    paniers_liste[panier].saisie,
                    //paniers_liste[panier].validation
                    paniers_liste[panier].prenom
                ] ).node().id = paniers_liste[panier].e_id;
            }
            dtPaniers.draw();
            change_load();
            }
    });
}
load_paniers_ajax();
function load_paniers_traite_ajax () {
    change_load('Chargement');
    $.ajax({
        url      : "php/ajax/rh/paniers_traite.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            paniers_traite_liste = data ;
            for (const panier in paniers_traite_liste) {
                let rowNode = dtPaniers_traite.row.add( [
                    paniers_traite_liste[panier].e_id,
                    paniers_traite_liste[panier].personne, 
                    paniers_traite_liste[panier].date_du_panier,
                    paniers_traite_liste[panier].commentaire,
                    paniers_traite_liste[panier].validation_rh
                ] ).node().id = paniers_traite_liste[panier].e_id;
            }
            dtPaniers_traite.draw();
            change_load();
            }
    });
}
load_paniers_traite_ajax();


function load_primes_ajax () {
    change_load('Chargement');
    $.ajax({
        url      : "php/ajax/rh/primes.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            primes_liste = data ;
            console.log(data);
            for (const prime in primes_liste) {
                let rowNode_ = dtPrimes.row.add( [
                    primes_liste[prime].e_id,
                    primes_liste[prime].personne, 
                    primes_liste[prime].date_prime,
                    primes_liste[prime].saisie,
                    //primes_liste[prime].validation
                    primes_liste[prime].prenom
                ] ).node().id = primes_liste[prime].e_id;
            }
            dtPrimes.draw();
            change_load();
            }
    });
}
load_primes_ajax();
function load_primes_traite_ajax () {
    change_load('Chargement');
    $.ajax({
        url      : "php/ajax/rh/primes_traite.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            primes_traite_liste = data ;
            console.log(data);
            for (const prime in primes_traite_liste) {
                let rowNode_ = dtPrimes_traite.row.add( [
                    primes_traite_liste[prime].e_id,
                    primes_traite_liste[prime].personne, 
                    primes_traite_liste[prime].date_de_prime,
                    primes_traite_liste[prime].commentaire,
                    primes_traite_liste[prime].validation_rh
                ] ).node().id = primes_traite_liste[prime].e_id;
            }
            dtPrimes_traite.draw();
            change_load();
            }
    });
}
load_primes_traite_ajax();

function delete_ids_array_panier_ok () {
    change_load('Validation des paniers');
    let length_ = paniers_to_delete.length;
    let x_ = 0;
    for(const id__ in paniers_to_delete ) {
        
        $.ajax({
            url      : "php/ajax/rh/valide_panier_ok.js.php",
            data     : {id_panier:paniers_to_delete[id__]},
            method   : "POST",
            dataType : "text",
            async    : true,
            error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
            success  : function(data) {
                x_ ++;
                paniers_liste = data ;
                console.log(data);
                if (x_ = length_ ) {
                    dtPaniers.rows( '.selected' ).remove().draw();
                    paniers_to_delete=[];
                }

                change_load();
                }
        });       
    }    
}
function delete_ids_array_panier_ko () {
    change_load('Validation des paniers');
    let length_ = paniers_to_delete.length;
    let x_ = 0;
    for(const id__ in paniers_to_delete ) {
        
        $.ajax({
            url      : "php/ajax/rh/valide_panier_ko.js.php",
            data     : {id_panier:paniers_to_delete[id__]},
            method   : "POST",
            dataType : "text",
            async    : true,
            error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
            success  : function(data) {
                x_ ++;
                paniers_liste = data ;
                console.log(data);
                if (x_ = length_ ) {
                    dtPaniers.rows( '.selected' ).remove().draw();
                    paniers_to_delete=[];
                }

                change_load();
                }
        });       
    }    
}
function delete_ids_array_prime_ok () {
    change_load('Validation des primes salissure');
    let length_ = primes_to_delete.length;
    let x_ = 0;
    for(const id__ in primes_to_delete ) {
        
        $.ajax({
            url      : "php/ajax/rh/valide_prime_ok.js.php",
            data     : {id_prime:primes_to_delete[id__]},
            method   : "POST",
            dataType : "text",
            async    : true,
            error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
            success  : function(data) {
                x_ ++;
                primes_liste = data ;
                //console.log(data);
                if (x_ = length_ ) {
                    dtPrimes.rows( '.selected' ).remove().draw();
                    primes_to_delete=[];
                }
                change_load();
                }
        });       
    }    
}
function delete_ids_array_prime_ko () {
    change_load('Validation des primes salissure');
    let length_ = primes_to_delete.length;
    let x_ = 0;
    for(const id__ in primes_to_delete ) {
        
        $.ajax({
            url      : "php/ajax/rh/valide_prime_ko.js.php",
            data     : {id_prime:primes_to_delete[id__]},
            method   : "POST",
            dataType : "text",
            async    : true,
            error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
            success  : function(data) {
                x_ ++;
                primes_liste = data ;
                console.log(data);
                if (x_ = length_ ) {
                    dtPrimes.rows( '.selected' ).remove().draw();
                    primes_to_delete=[];
                }

                change_load();
                }
        });       
    }    
}

/* document.getElementById("export_paniers_m1").addEventListener("click", function() {
    $.ajax({
        url      : "php/export_excel/export_paniers_m1.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            window.location = 'php/files/'+data;
            }
    });   
});
document.getElementById("export_primes_m1").addEventListener("click", function() {
    $.ajax({
        url      : "php/export_excel/export_primes_m1.php",
        data     : {},
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);change_load();},
        success  : function(data) {
            window.location = 'php/files/'+data;
            }
    });   
});
 */

