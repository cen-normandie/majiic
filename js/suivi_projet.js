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

//EVENT ON SWITCH
$('#2021').change(function() {filters_active["2021"] = ( $(this).prop('checked') );apply_filters();});
$('#2022').change(function() {filters_active["2022"] = ( $(this).prop('checked') );apply_filters();});
$('#2023').change(function() {filters_active["2023"] = ( $(this).prop('checked') );apply_filters();});
$('#2024').change(function() {filters_active["2024"] = ( $(this).prop('checked') );apply_filters();});
$('#2025').change(function() {filters_active["2025"] = ( $(this).prop('checked') );apply_filters();});
$('#2026').change(function() {filters_active["2026"] = ( $(this).prop('checked') );apply_filters();});
$('#2027').change(function() {filters_active["2027"] = ( $(this).prop('checked') );apply_filters();});
$('#2028').change(function() {filters_active["2028"] = ( $(this).prop('checked') );apply_filters();});
$('#all').change(function() {clear_dep_filter ();apply_filters();});

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
            console.log(data);
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
            console.log(data);
            
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

            apply_filters();
            change_load();
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
            console.log(actions_f);
        }
    }
    
    update_view_projet(projets_f);
};

//Charge les éléments d'un projet existant
function update_view_projet(projets_json) {
    for (const actions in actions_f) {
        //LOAD PROJET PROPERTIES

        const personnes_actions = actions_f[actions].personne_action.split('|');
        

        document.getElementById('list_actions').insertAdjacentHTML("beforeend", 
        `
        <div class="d-flex flex-column w-100 p-2">
            <div class="d-flex w-100 gx-1 align-items-center justify-content-between bg-success">
                <div class="">
                    <label id="id_action_${actions}" class="col-form-label">${actions_f[actions].id_action}</label>
                </div>
                <div class="">
                    <input type="text" id="nom_action_${actions}" value="${actions_f[actions].code_action}" disabled></input>
                </div>
                <div class="">
                    <input type="text"  id="financeurs_action_${actions}" value="${actions_f[actions].financements}" disabled></input>
                </div>
                <div class="">
                    <input type="text"  id="site_action_${actions}" value="${actions_f[actions].site}" disabled></input>
                </div>
                <div class="">
                    <input type="text"  id="heures_action_${actions}" value="${actions_f[actions].nb_h}" disabled></input>
                </div>
                <div class="">
                    <button id="add_p_action_${actions}" class="text-light border-0 bg-success fs-6 m-1 px-1" ><i class="fas fa-user-plus"></i></button>
                </div>
                <div>
                    <button id="del_action_${actions}" class="text-light border-0 bg-success fs-6 m-1 px-1" ><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-end align-items-center id="list_personnes_action_${actions}">
            </div>
        </div>`
        );
        for (const pe in personnes_actions) {
            //document.getElementById("list_personnes_action_"+actions).insertAdjacentHTML("beforeend", `
            //<div id=""><span class="badge mt-1 bg-success text-light">${personnes_actions[pe]}<i id="" class="ps-1 fas fa-window-close"></i></span></div>`);
            console.log(personnes_actions[pe]);
          }
        

    }
}
change_load("Chargement des données");

//////////////////////////////////////////////////////
//Gestion des dom et evenement pour ajouter pré-créer une action
//////////////////////////////////////////////////////
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
    
    function get_personne_content () {
        let tmpp= document.getElementById("input_personnes").value;
        return `
        <div  class="d-flex  justify-content-evenly my-2 w-75" id="f_${nb_personnes}">
            <div class="w-75" >
                <div class="input-group input-group-sm">
                    <span for="input_financeurs" class="input-group-text">P_${nb_personnes} : </span>
                    <input type="text" class="form-control" id="input_personnes_${nb_personnes}" aria-describedby="basic-addon3" value="${tmpp}">
                </div>
            </div>
            <div class="ml-2">
                <div id="minus_p_${nb_personnes}" type="button" class="btn btn-outline-secondary btn-sm"><i class="fas fa-minus"></i></div>
            </div>
        </div>
        `;
    }
    
    document.getElementById("plus_p").addEventListener("click", function() {
        document.getElementById('list_p').insertAdjacentHTML("beforeend", get_personne_content() );
        document.getElementById("minus_p_"+nb_personnes).addEventListener("click", function() {
            this.parentNode.parentNode.remove();
            nb_personnes--;
        }); 
        nb_personnes++;
    });


//EDITION DES ACTIONS
let ModalAddPersonne = new bootstrap.Modal(document.getElementById('ModalAddPersonne'), {
    keyboard: false
  })


//////////////////////////////////////////////////////
//Gestion des dom et evenement pour la liste des actions du projet
//////////////////////////////////////////////////////
function get_action_content () {
        let action = document.getElementById("input_actions").value;
        let site = document.getElementById("input_site").value;
        let heures = document.getElementById("input_heures").value;
        const e_financeurs_l = document.querySelectorAll(".e_financeurs");
        let i=0;
        let str_f='';
        e_financeurs_l.forEach(fx => {
            const fx_ = document.getElementById("input_financeurs_"+i).value;
            const fx_p = document.getElementById("input_p_financeurs_"+i).value;
            str_f +=(i>0 ? '|' : '');
            str_f=str_f+fx_+"_"+fx_p;
            i++;
	    });
        document.getElementById('list_actions').insertAdjacentHTML("beforeend", 
        `
        <div class="d-flex flex-column w-100 p-2">
            <div class="d-flex w-100 gx-1 align-items-center justify-content-between bg-success">
                <div class="">
                    <label id="id_action_${c_actions}" class="col-form-label">${c_actions}</label>
                </div>
                <div class="">
                    <input type="text" id="nom_action_${c_actions}" value="${action}" disabled></input>
                </div>
                <div class="">
                    <input type="text"  id="financeurs_action_${c_actions}" value="${str_f}" disabled></input>
                </div>
                <div class="">
                    <input type="text"  id="site_action_${c_actions}" value="${site}" disabled></input>
                </div>
                <div class="">
                    <input type="text"  id="heures_action_${c_actions}" value="${heures}" disabled></input>
                </div>
                <div class="">
                    <button id="add_p_action_${c_actions}" class="text-light border-0 bg-success fs-6 m-1 px-1" ><i class="fas fa-user-plus"></i></button>
                </div>
                <div>
                    <button id="del_action_${c_actions}" class="text-light border-0 bg-success fs-6 m-1 px-1" ><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-end align-items-center id="list_personnes_action_${c_actions}">
                <div id="a0_p0"><span class="badge mt-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></div>
            </div>
        </div>`);
    }
document.getElementById("add_action").addEventListener("click", function() {
    get_action_content();
    add_events_actions (); //buttons add user, delete, modal..
    c_actions++;
});

function add_events_actions () {
    const elements1 = document.querySelectorAll(`[id^="add_p"]`);
    elements1.forEach(element => {
        element.addEventListener("click", function() {
            //c_action = projets_f.id
            ModalAddPersonne.show(element.getAttribute('id'));
        });
    });

}



//////////////////////////////////////////////////////
//Gestion des dom et evenement pour le graphique
//////////////////////////////////////////////////////
function graph_( nom_projet_, color_, real_, previ_, initiales_) {
    let nom_projet = 'Projets';
    let color = '#000';
    let real = 30;
    let previ = 55;
    let initiales = 'BP';
    let graphzz = new Highcharts.chart("graph1", {
            chart: {
                type: 'bar',
                height:250,
                events: {
                    addSeries: function () {
                        setTimeout(function () {}, 1000);
                    }
                },
                backgroundColor:'#f8f9fa'
            },
            title: {
                text: nom_projet
            },
            xAxis: {
                categories: ['Projets A','Projet B','Projet C']//,
                //max: 20
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'nb jours',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: '',
                shared: true
            },
            plotOptions: {
                bar: {
                    grouping: false,
                    groupPadding:0
                }
            },
            legend: {
                enabled:false
            },
            credits: {
                enabled: false
            },
            colors:['#212529'],
            series: [{
                name: 'Réalisé',
                data: [5,10,30],
                pointPlacement: 0
            }, {
                name: 'Prévisionnel',
                color:'rgba(0,0,0,.2)',
                data: [10,20,60],
                pointPlacement: 0
            }]
            });
}

