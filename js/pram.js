let users_geomares = [];
//Initialisation du tableau datatable
const dtUsers =$('#usersDT').DataTable({
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
    //dom: '<"top"<"d-flex justify-content-between align-items-center"Bf>>t',
    dom: '' + 't' + '<"d-flex align-items-center gap-2 px-2"fB<"ms-auto"p>>',
        buttons: [
          { extend: 'excel', text: 'Excel', className: 'btn btn-sm btn-outline-success', init: function(api, node, config) {$(node).removeClass('btn-secondary')}},
        ],

    scrollY: '600px',
    scrollCollapse: true,
    paging: false
});


function load_users_ajax () {
    $.ajax({
        url      : "ajax/pram/pram.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            users_geomares = data ;
            for (const user in users_geomares) {
                let rowNode = dtUsers.row.add( [
                    users_geomares[user].mail_u,
                    users_geomares[user].nom_u, 
                    users_geomares[user].profil_u,
                    users_geomares[user].id_observateur_pram,
                    users_geomares[user].nom_structure_pram,
                    users_geomares[user].nb_car,
                    users_geomares[user].nb_loc
                ] ).node().id = users_geomares[user].mail_u;
            }
            dtUsers.draw();
            }
    });
}
load_users_ajax();
