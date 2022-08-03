//INIT VAR
let projets = '';//projets all (1er chargement)
let projets_f = '';//projets filtrés
let actions = '';//actions all (1er chargement)
let actions_f = '';//actions filtrés
let actions_liste = '';//actions liste (1er chargement)
let sites = '';//sites all (1er chargement)

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
            console.log(data);
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
        }
    }
    update_view_projet(projets_f);
};

function update_view_projet(projets_json) {
    for (const actions in actions_f) {
        
    }
}
change_load("Chargement des données");

//////////////////////////////////////////////////////
//Gestion des dom et evenement pour ajouter une action
//////////////////////////////////////////////////////
    //FINANCEUR
    let nb_financeurs=0;
    
    function get_financeur_content () {
        let tmpf= document.getElementById("input_financeurs").value;
        let tmpfp= document.getElementById("input_p_financeurs").value;
        return `
        <div  class="d-flex  justify-content-evenly my-2 w-75" id="f_${nb_financeurs}">
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

//////////////////////////////////////////////////////
//Gestion des dom et evenement la liste des actions du projet
//////////////////////////////////////////////////////
function get_action_content () {
        let action = document.getElementById("input_personnes").value;
        let site = document.getElementById("input_personnes").value;
        let heures = document.getElementById("input_personnes").value;
        let financeurs = '';
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
document.getElementById("add_action").addEventListener("click", function() {
    
});




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

