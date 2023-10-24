//INIT VAR
let projets = '';//projets all (1er chargement)
let projets_f = '';//projets filtrés
let actions = '';//actions all (1er chargement)
let actions_f = '';//actions filtrés
let actions_liste = '';//actions liste (1er chargement)
let sites = '';//sites all (1er chargement)
let c_actions = 0; // liste actuelles des actions du projet en édition
let c_action = ''; // id_action actuelle
let c_projet = ''; // id_projet actuel
let edit = false;
let chart = null;



//EVENT ON SWITCH
//$('#2021').change(function() {filters_active["2021"] = ( $(this).prop('checked') );apply_filters();});
//$('#2022').change(function() {filters_active["2022"] = ( $(this).prop('checked') );apply_filters();});
//$('#2023').change(function() {filters_active["2023"] = ( $(this).prop('checked') );apply_filters();});
//$('#2024').change(function() {filters_active["2024"] = ( $(this).prop('checked') );apply_filters();});
//$('#2025').change(function() {filters_active["2025"] = ( $(this).prop('checked') );apply_filters();});
//$('#2026').change(function() {filters_active["2026"] = ( $(this).prop('checked') );apply_filters();});
//$('#2027').change(function() {filters_active["2027"] = ( $(this).prop('checked') );apply_filters();});
//$('#2028').change(function() {filters_active["2028"] = ( $(this).prop('checked') );apply_filters();});
//$('#all').change(function() {clear_dep_filter ();apply_filters();});

//CALL DATA PROJETS
//Le json de l'ensemble des projets sera contenu dans la variable "projets"
//
function load_projets_ajax () {
    $.ajax({
        url      : "php/ajax/projets/projets.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            projets = data ;
            //console.log(`Projets ${projets}`);
            let projets_array = [];
            for (const projet in projets) {
                projets_array.push(projets[projet].id+' - '+projets[projet].name);
            }
            projets_array.sort();
            autocompleteArray(document.getElementById("input_projet"), projets_array);
            load_actions_ajax();
            }
    });
}
function load_actions_ajax () {
    $.ajax({
        url      : "php/ajax/projets/actions_liste.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            actions_liste = data ;
            //console.log(data);
            let actions_liste_array = [];
            for (const action in actions_liste) {
                actions_liste_array.push(actions_liste[action].id+' - '+actions_liste[action].name);
            }
            actions_liste_array.sort();
            autocompleteArray_actions(document.getElementById("input_actions"), actions_liste_array);
            load_financeurs_ajax();
            }
    });
}
let financeurs_liste_array = [];
function load_financeurs_ajax () {
    $.ajax({
        url      : "php/ajax/projets/financeurs_liste.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            financeurs_liste = data ;
            //console.log(data);
            
            for (const financeur in financeurs_liste) {
                financeurs_liste_array.push(financeurs_liste[financeur].id+' - '+financeurs_liste[financeur].name);
            }
            financeurs_liste_array.sort();
            autocompleteArray_financeurs(document.getElementById("input_financeurs"), financeurs_liste_array);
            load_personnes_ajax();
            }
    });
}
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
            //Add GROUP GE_CAEN / GE_ROUEN / ZOO
            personnes_liste_array.push('999 - GE_CAEN');
            personnes_liste_array.push('888 - GE_ROUEN');
            personnes_liste_array.push('777 - ZOO');
            personnes_liste_array.sort();
            autocompleteArray_personnes(document.getElementById("input_personnes"), personnes_liste_array);
            load_sites_ajax();
            
            
            }
    });
}

function load_sites_ajax () {
    $.ajax({
        url      : "php/ajax/sites/sites.ajax.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            sites = data ;
            //Load autocomplete
            init_sites_array();
            //apply_filters();
            change_load();
            //Reload projet if exists
            if (JSON.parse(sessionStorage.getItem('filter_projet')) !== null) {
                //console.log(sessionStorage);
                keys["id_projet"][0] = sessionStorage.getItem('filter_projet');
                filters_active["id_projet"] = true;
                document.getElementById("input_projet").value= sessionStorage.getItem('filter_name_projet');;
                document.getElementById("input_projet").setAttribute("disabled",true);
                document.getElementById("del").classList.add("text-danger");

                apply_filters();
            };
            sessionStorage.clear();
            }
    });
}

function init_sites_array() {
    let sites_array = [];
    for (const site in sites) {
        sites_array.push(sites[site].id+' - '+sites[site].name);
    }
    autocompleteArray_sites_liste(document.getElementById("input_site"), sites_array);
}


//APPLY FILTERS ON CORRECT FIELD
function apply_filters() {
    projets_f = projets;
    for (const property in filters_active) {
        if(filters_active[property]) {
            //console.log(`${property}: ${filters_active[property]}`);
            projets_f = filtre_obj(projets_f, property);
            actions_f = JSON.parse(projets_f[0].json_actions);
            //sessionStorage.setItem("filter_projet", keys["id_projet"][0]);
            //console.log(actions_f);

            //Test s'il y a des actions ?
            if ((actions_f[0] == null)) {
                console.log("Pas d'actions...");
            } else {
                //mets à jour le tableau des actions
                update_dtActions();
                //update projet infos
                update_projetInfos();
                //Ajoute les évènements pour les cellules du tableau
                add_events_actions();
                dtActions.column( 7 ).visible(edit);
            }
            //si responsable alors edition possible
            const c_ = document.getElementById("c_resp").innerText;
            if (c_ == projets_f[0].responsable_projet) {
                document.getElementById("edition").classList.remove("d-none");
            } else {
                document.getElementById("edition").classList.add("d-none");
            }
        }
    }
    //affiche les graphiques et le tableau des actions
    document.getElementById("panel_all").classList.remove("d-none");
    //si pas de filtre sur les projets clear all data
    if (!filters_active["id_projet"]) {
        console.log('destroy');
        // set actions_f null;
        actions_f = null;
        //destroy graph
        chart.destroy();
        chart_global.destroy();
        //clear datatable;
        dtActions.clear().draw();
        // clear projet data
        document.getElementById("nom_projet").value="...";
        document.getElementById("responsable_projet").value="...";
        document.getElementById("type_projet").value="...";
        document.getElementById("etat_projet").value="...";
        document.getElementById("echelle_projet").value="...";
        document.getElementById("l_p_date_start").value="01-01-2020";
        document.getElementById("l_p_date_end").value="01-01-2020";
        document.getElementById("p_commentaire").value="...";
        document.getElementById("p_color").value="#ffffff";
        document.getElementById("sites_string").value="...";
        document.getElementById("docs").innerHTML= "";
        document.getElementById("project_prct").innerHTML= "";
        document.getElementById("project_prct").removeAttribute('color');
        //off edit visibility
        edit = false;
        document.getElementById("edition").classList.add("d-none");
        document.getElementById("add_an_action").classList.add("d-none");
        document.getElementById("save_projet").classList.add("d-none");
        document.getElementById("panel_all").classList.add("d-none");
        //document.getElementById("export_excel_temps").classList.add("d-none");
    }
};

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


//Initialisation du tableau datatable
const dtActions =$('#actionsDT').DataTable({
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
    dom: '<"top"<"d-flex justify-content-end align-items-center"f>>t', // export excel -->B :<"top"<"d-flex justify-content-end align-items-center"fB>>t
    buttons: [
    { 
        extend: 'excel', 
        text:'Excel',
        className: 'btn btn-sm btn-dark m-2',
        init: function(api, node, config) {
            //$(node).removeClass('dt-button')
        }
    }
    ],
    scrollY: '200px',
    scrollCollapse: true,
    paging: false,
    autoWidth: false
});

document.getElementById("refresh").addEventListener("click", function() {
    location.reload();
})

//////////////////////////////////////////////////////
//Gestion des dom et evenement si responsable projet --> edition possible
//////////////////////////////////////////////////////
document.getElementById("save_projet").addEventListener("click", function() {
    save_projet();
});

document.getElementById("edit_projet").addEventListener("click", function() {
    edit = document.getElementById("edit_projet").checked;
    //console.log(edit);
    document.getElementById("nom_projet").disabled = !edit;
    document.getElementById("responsable_projet").disabled = !edit;
    document.getElementById("type_projet").disabled = !edit;
    document.getElementById("etat_projet").disabled = !edit;
    document.getElementById("echelle_projet").disabled = !edit;
    document.getElementById("p_date_start").disabled = !edit;
    document.getElementById("p_date_end").disabled = !edit;
    document.getElementById("p_commentaire").disabled = !edit;
    document.getElementById("p_color").disabled = !edit;

    if(edit) {
        document.getElementById("add_an_action").classList.remove("d-none");
        document.getElementById("save_projet").classList.remove("d-none");
        //document.getElementById("export_excel_temps").classList.remove("d-none");
    } else {
        document.getElementById("add_an_action").classList.add("d-none");
        document.getElementById("save_projet").classList.add("d-none");
        //document.getElementById("export_excel_temps").classList.add("d-none");
    }
    dtActions.column( 7 ).visible(edit);
});
//masque la colonne pour le 1er chargement
change_load("Chargement des données");

//export excel temps des projets
document.getElementById("export_excel_temps").addEventListener("click", function() {
    $.ajax({
        url: "php/export_excel/export_excel_temps_du_projet.php",
        type: "POST",
        dataType: "text",
        async    : true,
        data: {
            'id_projet':projets_f[0].id
        },
        error    : function(request, error) { 
            alert("Erreur : responseText: "+request.responseText);
            },
        success  : function(data) {
            //console.log(data);
            window.location = 'php/files/'+data;
            }
    });
});

//////////////////////////////////////////////////////
//Gestion des dom et evenement pour ajouter pré-créer une action
//////////////////////////////////////////////////////
    // MODAL Action
    let modal_action = new bootstrap.Modal(document.getElementById('ModalAddAction'), {
        keyboard: false
      })
    document.getElementById("add_action").addEventListener("click", function() {
        modal_action.hide();
    });
    document.getElementById("cancel_action_modal").addEventListener("click", function() {
        modal_action.hide();
    });
    
    
    //FINANCEUR
    let nb_financeurs=0;
    
    function get_financeur_content () {
        let tmpf= document.getElementById("input_financeurs").value;
        let tmpfp= document.getElementById("input_p_financeurs").value;
        return `
        <div  class="d-flex justify-content-evenly my-2 w-75 e_financeurs" id="f_${nb_financeurs}">
            <div class="w-75" >
                <div class="input-group input-group-sm">
                    <span for="input_financeurs" class="input-group-text">F_${nb_financeurs} : </span>
                    <input type="text" class="form-control" id="input_financeurs_${nb_financeurs}" aria-describedby="basic-addon3" value="${tmpf}">
                    <span class="input-group-text">%_${nb_financeurs} :</span>
                    <input type="number" class="form-control" id="input_p_financeurs_${nb_financeurs}" aria-describedby="basic-addon3" value="${tmpfp}">
                </div>
            </div>
            <div class="ml-2">
                <div id="minus_f_${nb_financeurs}" type="button" class="btn btn-outline-secondary btn-sm"><i class="fas fa-minus"></i></div>
            </div>
        </div>
        `;
    }
    
    document.getElementById("plus_f").addEventListener("click", function() {
        document.getElementById('list_f').insertAdjacentHTML("beforeend", get_financeur_content() );
        document.getElementById("minus_f_"+nb_financeurs).addEventListener("click", function() {
            this.parentNode.parentNode.remove();
            nb_financeurs--;
        }); 
        nb_financeurs++;
    });
    

    //PERSONNES
    let nb_personnes=0;

    //Fonction de tri des objets du JSON par propriété   
    function GetSortOrder(prop) {    
        return function(a, b) {    
            if (a[prop] > b[prop]) {    
                return 1;    
            } else if (a[prop] < b[prop]) {    
                return -1;    
            }    
            return 0;    
        }    
    }    


    //DATATABLE pour la liste des actions
    function update_dtActions () {
        const project = projets_f;
        const json_previ = [];
        const json_real = [];
        const json_previ_sum = [];
        const json_real_sum = [];
        let nb_actions = 0;
        dtActions.clear();
            const data_previ_sum  = new Object();
            const data_real_sum = new Object();
            data_previ_sum.y = 0;
            data_previ_sum.name = '';
            data_real_sum.y = 0;
            data_real_sum.name = '';
            data_real_sum.color = project[0].color;
            for (const actions in actions_f) {
                //console.log(actions_f);
                //console.log(projets_f);
                    nb_actions++;
                    const p_ = actions_f[actions].personne_action ?? '';
                    const personnes_actions = p_.split('|');
                    var badges_ = '';
                    var x = `
                    <button id="add_p_action_${actions_f[actions].id_action}" id_action="${actions_f[actions].id_action}" class="btn btn-sm bg-light text-warning fs-6 px-1" ><i class="fas fa-user-plus"></i></button>
                    <button id="del_action_${actions_f[actions].id_action}" id_action="${actions_f[actions].id_action}" class="btn btn-sm bg-light text-danger fs-6 px-1"><i class="fas fa-trash-alt"></i></button>`
                    //Liste des badges personnes
                    for (const pe in personnes_actions) {
                        badges_ = badges_ + '<div id=""><span class="badge mt-1 bg-secondary text-light">'+personnes_actions[pe]+'</span></div>'; //<i id="" class="ps-1 fas fa-window-close"></i>
                    }
                    //Ajoute les actions dans le tableau
                    let rowNode = dtActions.row.add( [
                        actions_f[actions].id_action, //id_action
                        actions_f[actions].code_action, //code_action
                        actions_f[actions].financements ?? '', //financeurs
                        actions_f[actions].site ?? '', //site
                        actions_f[actions].previ ?? 0, //nb_h_previ
                        actions_f[actions].realise ?? 0, //nb_h_real
                        badges_, //personnes
                        x //test badges
                    ] ).draw();
    
                    //formate un nouveau json des actions pour alimenter les graphiques
                    const data_previ = new Object();
                    const data_real = new Object();
                    if (!!actions_f[actions].personne_action) { 
                        data_previ.name = actions_f[actions].personne_action +" - "+actions_f[actions].code_action;
                        data_previ.y = actions_f[actions].previ ?? 0;
                        data_previ_sum.y = data_previ_sum.y + (actions_f[actions].previ ?? 0);
                        data_real.name = actions_f[actions].personne_action +" - "+actions_f[actions].code_action;
                        data_real.y = actions_f[actions].realise ?? 0;
                        data_real_sum.y = data_real_sum.y + (actions_f[actions].realise ?? 0);
                        data_real.color = project[0].color;
                        json_previ.push(data_previ);
                        json_real.push(data_real);
                    }
                }
                dtActions.draw();
                projets_f.nb_actions = nb_actions;

                json_previ.sort(GetSortOrder("name"));
                json_real.sort(GetSortOrder("name"));
                json_previ_sum.push(data_previ_sum);
                json_real_sum.push(data_real_sum);
                document.getElementById("project_prct").innerHTML = Math.round((data_real_sum.y*100 / data_previ_sum.y)) + '%' ?? 0;
                document.getElementById("project_prct").setAttribute('style', 'color:'+project[0].color);

    
        /*      console.log('json_previ');
                console.log(json_previ);
                console.log('json_real');
                console.log(json_real); */
    
                //Chargement du graphique des actions nom_projet_, color_, real_, previ_
                graph_(projets_f[0].name, json_real, json_previ);
                graph_sum(projets_f[0].name, json_real_sum, json_previ_sum);
                
                     
    }

    //fonction de mise à jour des données du projet
    function update_projetInfos() {
        const project = projets_f;
        document.getElementById("nom_projet").value=project[0].name;
        document.getElementById("responsable_projet").value=project[0].responsable_projet;
        document.getElementById("type_projet").value=project[0].type_projet;
        document.getElementById("etat_projet").value=project[0].etat;
        document.getElementById("echelle_projet").value=project[0].territoire;
        document.getElementById("p_date_start").value=project[0].date_debut;
        document.getElementById("p_date_end").value=project[0].date_fin;
        document.getElementById("p_commentaire").value=project[0].commentaire_projet;
        document.getElementById("p_color").value=project[0].color;
        document.getElementById("sites_string").value=project[0].sites;
        if (typeof project[0].files !== 'undefined') {
            //console.log(typeof project[0].files);
            if ((project[0].files !== null) && (project[0].files !=='')) {
                let str_ = '<ul class="list-group list-group">';
                let d = project[0].files.split(', ').length;
                let str__ = '';
                if (d > 1) {
                    let nbd = project[0].files.split(', ');
                    nbd.forEach(element => str__ = str__+ `<a class="list-group-item col-12 text-truncate" href="./php/files/${element}" target="blank_" ><span style="font-size:12px;">${element}</span></a>` );
                } else if (project[0].files.split(', ').length = 1) {
                    str__ = `<a class="list-group-item col-12 text-truncate" href="./php/files/${project[0].files}" target="blank_" ><span style="font-size:12px;">${project[0].files}</span></a>`;
                }
                str_ +='</ul>';
                document.getElementById("docs").innerHTML=str__;
            } else {
                document.getElementById("docs").innerHTML='<ul class="list-group list-group w-100"><li class="list-group-item">ø</li></ul>';
            };
        };
        
    }

    //fonction d'enregistrement du projet en cours
    function save_projet () {
        const projet_ = new Object();
        //console.log(projets_f[0].id);
        projet_.id = projets_f[0].id;
        projet_.name = document.getElementById("nom_projet").value;
        projet_.responsable_projet = document.getElementById("responsable_projet").value;
        projet_.type_projet = document.getElementById("type_projet").value;
        projet_.etat_projet = document.getElementById("etat_projet").value;
        projet_.echelle_projet = document.getElementById("echelle_projet").value;
        projet_.p_date_start = document.getElementById("p_date_start").value;
        projet_.p_date_end = document.getElementById("p_date_end").value;
        projet_.p_commentaire = document.getElementById("p_commentaire").value;
        projet_.p_color = document.getElementById("p_color").value;
        const ProjetJsonString= JSON.stringify(projet_);
        $.ajax({
            url: "php/ajax/projets/edit_projet/update_projet.js.php",
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
                console.log(data);
                document.getElementById("t_content").innerHTML= 'Projet enregistré la page peut être rechargée !';
                $('#toast_info').toast('show');
                //location.reload();
                }
        });
        
    }


//EDITION DES ACTIONS
let ModalAddPersonne = new bootstrap.Modal(document.getElementById('ModalAddPersonne'), {
    keyboard: false
  });
let ModalDelAction = new bootstrap.Modal(document.getElementById('ModalDelAction'), {
    keyboard: false
  });
document.getElementById("add_action_personne").addEventListener("click", function() {
    const myUAction = new Object();
    myUAction.id_action = document.getElementById("id_action_update").innerText;
    myUAction.personne = document.getElementById("input_personnes").value.split(' - ')[1];
    //add Ajax function to have valid id_action
    let  UActionJsonString= JSON.stringify(myUAction);
    //Sauvegarde de la personne en BDD
    $.ajax({
        url: "php/ajax/projets/edit_projet/update_action_with_personne.js.php",
        type: "POST",
        dataType: "json",
        async    : true,
        data: {
            'action': UActionJsonString
        },
        error    : function(request, error) { 
            alert("Erreur : responseText: "+request.responseText);
            },
        success  : function(data) {
            //console.log(data);
            document.getElementById("t_content").innerHTML= 'Action modifiée avec succès la page peut être rechargée !';
            $('#toast_info').toast('show');
            sessionStorage.clear();
            }
    });
    ModalAddPersonne.hide();
});
document.getElementById("cancel_action_personne").addEventListener("click", function() {
    ModalAddPersonne.hide();
});

document.getElementById("del_action_complete").addEventListener("click", function() {
    const myUActionDel = new Object();
    myUActionDel.id_action = document.getElementById("id_action_delete").innerText;
    myUActionDel.id_projet = projets_f[0].id;
    //add Ajax function to have valid id_action
    let  UActionJsonString= JSON.stringify(myUActionDel);
    console.log(UActionJsonString);
    //Sauvegarde de la personne en BDD
    $.ajax({
        url: "php/ajax/projets/edit_projet/del_action.js.php",
        type: "POST",
        dataType: "text",
        async    : true,
        data: {
            'action': UActionJsonString
        },
        error    : function(request, error) { 
            alert("Erreur : responseText: "+request.responseText);
            ModalDelAction.hide();
            },
        success  : function(data) {
            console.log(data);
            ModalDelAction.hide();
            document.getElementById("t_content").innerHTML= 'Action supprimée avec succès !';
            $('#toast_info').toast('show');
            }
    });
});
document.getElementById("cancel_del_action").addEventListener("click", function() {
    ModalDelAction.hide();
});

//////////////////////////////////////////////////////
//DOM FILES UPLOAD
//////////////////////////////////////////////////////
document.getElementById("save_file").addEventListener("click", function() {
    if( document.getElementById("input_file").files.length == 0 ){
        console.log("no files selected");
    }
    let active_file = $("#input_file").prop('files')[0];
    let fd = new FormData();
        fd.append('file', active_file);
        fd.append('id_projet', projets_f[0].id);
        $.ajax({
        url      : "php/upload_doc.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                console.log(data);
                document.getElementById("t_content").innerHTML= 'Fichier enregistré la page peut être rechargée !';
                $('#toast_info').toast('show');
                }
        });
});



//////////////////////////////////////////////////////
//Gestion des dom et evenement pour la liste des actions du projet
//////////////////////////////////////////////////////
function get_action_content () {
        const myAction = new Object();
/*         let action = document.getElementById("input_actions").value;
        let site = document.getElementById("input_site").value;
        let heures = document.getElementById("input_heures").value; */
        const e_financeurs_l = document.querySelectorAll(".e_financeurs");
        let i=0;
        let str_f='';
        e_financeurs_l.forEach(fx => {
            //console.log(document.getElementById("input_financeurs_"+i).value);
            //console.log(document.getElementById("input_financeurs_"+i).value.split(' - ')[1]);
            const fx_ = (document.getElementById("input_financeurs_"+i).value.split(' - ')[1] ? document.getElementById("input_financeurs_"+i).value.split(' - ')[1] : '');
            const fx_p = document.getElementById("input_p_financeurs_"+i).value;
            str_f +=(i>0 ? '|' : '');
            str_f=str_f+fx_+"_"+fx_p;
            i++;
	    });
        myAction.action_name = (document.getElementById("input_actions").value.split(' - ')[1] ? document.getElementById("input_actions").value.split(' - ')[1] : '');
        myAction.site = (document.getElementById("input_site").value.split(' - ')[1] ? document.getElementById("input_site").value.split(' - ')[1] : '');
        myAction.heures = document.getElementById("input_heures").value;
        myAction.financeurs = str_f;
        //myAction.id_projet = projets_f[0].id_projet;
        myAction.id_projet = projets_f[0].id;

        console.log(myAction);

        //add Ajax function to have valid id_action
        let  ActionJsonString= JSON.stringify(myAction);
        $.ajax({
            url: "php/ajax/projets/edit_projet/add_action_in_projet.js.php",
            type: "POST",
            dataType: "json",
            async    : true,
            data: {
                'action': ActionJsonString
            },
            error    : function(request, error) { 
                alert("Erreur : responseText: "+request.responseText);
                },
            success  : function(data) {
                console.log(data);
                document.getElementById("t_content").innerHTML= 'Action enregistrée avec succès la page peut être rechargée!';
                $('#toast_info').toast('show');
                }
        });




        /* document.getElementById('list_actions').insertAdjacentHTML("beforeend", 
        `
        <div class="d-flex flex-column flex-grow-1 m-2 border border-dark rounded">
            <div class="d-flex gx-1 align-items-center justify-content-between ">
                <div class="col-xs-1 text-truncate">
                    <label id="id_action_${c_actions}" id_action="" class="p-1 col-form-label text-secondary">${c_actions}</label>
                </div>
                <div class="col-xs-3 text-truncate">
                    <span type="text" id="nom_action_${c_actions}" value="">${action}</span>
                </div>
                <div class="col-xs-3">
                    <span type="text"  id="financeurs_action_${c_actions}" value="" >${str_f}</span>
                </div>
                <div class="col-xs-2">
                    <span type="text"  id="site_action_${c_actions}" value="" >${site}</span>
                </div>
                <div class="col-xs-1">
                    <span type="text"  id="heures_action_${c_actions}" value="" disabled>${heures}</span>
                </div>
                <div class="col-xs-1">
                    <button id="add_p_action_${c_actions}" id_action="" class=" bg-light border-0 text-dark fs-6 m-1 px-1" ><i class="fas fa-user-plus"></i></button>
                    <button id="del_action_${c_actions}" id_action="" class=" bg-light border-0 text-danger fs-6 m-1 px-1" ><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-end align-items-center" id="list_personnes_action_${c_actions}">
            </div>
        </div>`); */
    }
document.getElementById("add_action").addEventListener("click", function() {
    get_action_content();
    //add_events_actions(); //buttons add user, delete, modal..
    //c_actions++;
});

function add_events_actions () {
    const elements1 = document.querySelectorAll(`[id^="add_p"]`);
    const elements2 = document.querySelectorAll(`[id^="del_action_"]`);
    elements1.forEach(element => {
        element.addEventListener("click", function() {
            //c_action = projets_f.id
            document.getElementById("id_action_update").textContent=element.getAttribute('id').replace('add_p_action_', '');
            ModalAddPersonne.show(element.getAttribute('id'));
        });
    });
    elements2.forEach(element => {
        element.addEventListener("click", function() {
            //c_action = projets_f.id
            document.getElementById("id_action_delete").textContent=element.getAttribute('id').replace('del_action_', '');
            ModalDelAction.show(element.getAttribute('id'));
        });
    });
}



document.getElementById("add_action_personne").addEventListener("click", function() {
/*     if (!!document.getElementById("input_personnes").value) {
        console.log("list_personnes_action_"+document.getElementById("id_action_update").textContent);
        let s = '<div id=""><span class="badge mt-1 bg-success text-light">'+document.getElementById("input_personnes").value+'<i id="" class="ps-1 fas fa-window-close"></i></span></div>';
        document.getElementById("list_personnes_action_"+document.getElementById("id_action_update").textContent ).insertAdjacentElement("beforeend", s)
    } */
    
});

//////////////////////////////////////////////////////
//Gestion des dom et evenement pour le graphique par action
//////////////////////////////////////////////////////
function graph_( nom_projet_, real_, previ_) {
    chart = new Highcharts.chart('container', {
        chart: {
            type: 'bar',
            backgroundColor:'#f8f9fa'
        },
        title: {
            text: ``,//Réal / Prévi  ${nom_projet_}
            align: 'center'
        },
        subtitle: {
            text: '',
            align: 'center'
        },
        plotOptions: {
            series: {
                grouping: false,
                borderWidth: 0
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        tooltip: {
            shared: true,
            headerFormat: '<span style="font-size: 15px">{point.point.name}</span><br/>',
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: {point.y}<br/>'
        },
        xAxis: {
            type: 'category',
        title: {
            text: null
        },
        min: 0,
        max: ((projets_f.nb_actions<10) ? projets_f.nb_actions-1 : 10)  ,
        scrollbar: {
            enabled: true
        },
        tickLength: 0
        },
        yAxis: [{
            title: {
                text: 'nb heures'
            },
            showFirstLabel: false
        }],
        series: [{
            color: 'rgba(0,0,0,.2)',
            pointPlacement: 0,
            data: previ_,
            name: 'Previsionnel'
        }, {
            name: 'Réalisé',
            id: 'main',
            dataLabels: [{
                enabled: true,
                inside: true,
                style: {
                    fontSize: '10px'
                }
            }],
            data:real_
                //[{"name":"aa","y":22,"color":color_},
                //{"name":"bb","y":33,"color":color_}]
            
        }],
        exporting: {
            allowHTML: true
        }
    });
}


//////////////////////////////////////////////////////
//Gestion des dom et evenement pour le graphique general
//////////////////////////////////////////////////////
function graph_sum( nom_projet_, real_, previ_) {
    chart_global = new Highcharts.chart('container_sum', {
        chart: {
            type: 'bar',
            height: 100,
            backgroundColor:'#f8f9fa'
        },
        title: {
            text: ``,//Réal / Prévi  ${nom_projet_}
            align: 'center'
        },
        subtitle: {
            text: '',
            align: 'center'
        },
        plotOptions: {
            series: {
                grouping: false,
                borderWidth: 0
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        tooltip: {
            shared: true,
            headerFormat: '<span style="font-size: 15px">{point.point.name}</span><br/>',
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: {point.y}<br/>'
        },
        xAxis: {
            type: 'category',
            accessibility: {
                description: 'Countries'
            }
        },
        yAxis: [{
            title: {
                text: 'nb heures'
            },
            showFirstLabel: false
        }],
        series: [{
            color: 'rgba(0,0,0,.2)',
            pointPlacement: 0,
            data: previ_,
            name: 'Previsionnel'
        }, {
            name: 'Réalisé',
            id: 'main',
            dataLabels: [{
                enabled: true,
                inside: true,
                style: {
                    fontSize: '10px'
                }
            }],
            data:real_
        }],
        exporting: {
            allowHTML: true
        }
    });
}