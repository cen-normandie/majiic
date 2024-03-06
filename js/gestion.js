var tab = new Object();
var form_function = {};


function create_toast( success_ , text) {
    
    if (success_) {
        $('#info_txt').text( text );
        var element = document.getElementById("infos_toast");
            element.classList.add("border-success");
            element.classList.add("text-success");
        var element = document.getElementById("iii");
            element.classList.add("text-success");
        var element = document.getElementById("info_txt").innerText='Document copié sur le serveur et enregistré en BDD';
    }
    else {
        $('#info_txt').text( text );
        var element = document.getElementById("infos_toast");
            element.classList.add("border-danger");
            element.classList.add("text-danger");
        var element = document.getElementById("iii");
            element.classList.add("text-danger");
        var element = document.getElementById("info_txt").innerText='Attention le document n\'a pas été enregistré vérifiez les champs';
    }

    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl)
    })
    toastList.forEach(toast => toast.show())
}


//LISTENERS 
//Datepicker
// $('#testpicker').datepicker({
// 	format: 'dd-mm-yyyy',
// 	language: "fr"
// });
$('#n_c_date_start').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_c_date_end').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_c_date_sign').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_a_date_sign').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_b_e_date_sign').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_b_date_sign').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_b_date_start').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_b_date_end').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_p_date_sign').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_p_date_start').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});

$('#n_p_date_end').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_ore_date_sign').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_gestion_date_start').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_gestion_date_end').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_gestion_date_maj').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
});
$('#n_date_1').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    language: 'fr',
    autoclose: true
    //onSelect: function(dateText) {
    //    console.log(dateText);
    //    //display("Selected date: " + dateText + "; input's current value: " + this.value);
    //    $('#n_date_2').data("DateTimePicker").setMinDate(dateText.date);
    //}
});

$('#n_date_2').datepicker({
    format : 'dd-mm-yyyy',
    locale: 'fr',
    useCurrent: false,
    autoclose: true
});


$("#n_date_1").on("dp.change", function (e) {
            $('#n_date_2').data("datepicker").minDate(e.date);
        });

//ajout des evenement masquer la liste des couches externes
//showliste = true;
//$('#liste_couches_button').click( function () {
//    if (showliste) {
//        $('#liste_couches').animate({left: '-300px'});
//        showliste = false;
//    } else {
//        $('#liste_couches').animate({left: '0px'});
//        showliste = true;
//    }
//});


function ts_type_signataire () {
    var start_date          =  $('#n_date_1').val();
    var end_date            =  $('#n_date_2').val();
    if ((start_date != '') && (end_date != '')) {
        $.ajax( {
        method   : "POST",
        url: "php/ajax/graph/ts_type_signataire.js.php",
        dataType : "json",
        data: {
                start_date          : start_date,
                end_date            : end_date
        },
        success: function( data ) 
        {
        //var array_general = [];
        //$.each(data, function (index, value) {
        //    array_general.push([parseFloat(value.split('__')[1])]);
        //    array_general.push( value.split('__')[0] parseFloat(value.split('__')[1]));
        //    {"name":"EPCI","y":0}
        //    });
        //    g_type_signataire(array_general);
        //}
            console.log(data);
            //console.log([{"name":"EPCI","y":0},{"name":"Mixte","y":4.40},{"name":"Acquisition","y":45.64},{"name":"Public","y":226.09},{"name":"Association","y":4.40},{"name":"Privé","y":298.03}]);
            //console.log(JSON.parse('[{"type":"EPCI","value":0},{"type":"Mixte","value":4.40},{"type":"Acquisition","value":45.64},{"type":"Public","value":226.09},{"type":"Association","value":4.40},{"type":"Privé","value":298.03}"]'));
            g_type_signataire(data);
            
        }
        });
    } else {
        alert('Les champs dates ne sont pas valides !');
    }

}

//#26338b bleu
//#009839 vert
//#ff931d orange
//#e30513 rouge
//#ffde00 jaune
//#82358c violet
//#808080 gris
//#c7b299 sable
//#28abe2 bleu ciel

function g_type_signataire( array ) {
        Highcharts.chart('chart_type_signataire', {
            colors: [
                '#26338b', //bleu
                '#009839', //vert
                '#ff931d', //orange
                '#ffde00', //jaune
                '#e30513', //rouge
                '#82358c', //violet
            ],
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '% de superficie par type de signataire'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '% de superficie',
                //data: [{"name":"EPCI","y":0},{"name":"Mixte","y":4.40},{"name":"Acquisition","y":45.64},{"name":"Public","y":226.09},{"name":"Association","y":4.40},{"name":"Privé","y":298.03}]
                data: array
            }]
        });
}




var block_targeted_val = "convention";

// $('#type_doc').on('change', function() {
//     var block_target = $(this).children(":selected").attr("val_");
//     $('#'+block_targeted_val).hide();
//     console.log(block_target);
//     $('#'+block_target).show();
//     block_targeted_val = block_target;
    
//     $('#save_new_doc').removeClass('green').removeClass('red').removeClass('orange').removeClass('blue').removeClass('purple').removeClass('yelloie').removeClass('ddgdark');
//     $('#save_new_doc').addClass($('#'+block_target).find("div > div> h3").attr('class'));
//     //$(this).children(":selected").attr("val_");
//     //$('#save_new_doc').addClass('newClassWithYourStyles').removeClass('btn-default');
// });
// TODO Change to TABS
$('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("id").replace('v-pills-','').replace('-tab',''); // activated tab
    block_targeted_val = target;
  });





$('#save_new_doc').click(
    function () {
        var file_name_end = '';
        var id_doc = '';
        var nom_doc = '';
        var url_d = "php/copy_file_pdf.php";
        switch (block_targeted_val) {
            case 'convention':
                id_doc = 'n_c_id';
                nom_doc = 'n_c_nom';
                break;
            case 'acquisition':
                id_doc = 'n_a_id';
                nom_doc = 'n_a_nom';
                break;
            case 'bail_e':
                id_doc = 'n_b_e_id';
                nom_doc = 'n_b_e_nom';
                break;
            case 'bail_rural':
                id_doc = 'n_b_id';
                nom_doc = 'n_b_nom';
                break;
            case 'pret_usage':
                id_doc = 'n_p_id';
                nom_doc = 'n_p_nom';
                break;
            case 'ore':
                id_doc = 'n_ore_id';
                nom_doc = 'n_ore_nom';
                break;
            case 'ddg':
                id_doc = 'n_gestion_nom';
                nom_doc = 'n_gestion_nom';
                url_d = "php/copy_file_pdf_gestion.php";
                break;
            };
        var active_file = $("#doc_pdf").prop('files')[0];
        var fd = new FormData();
        fd.append('file', active_file);
        fd.append('id_doc', $("#"+id_doc).val());
        console.log(active_file);
        $.ajax({
        // copy du fichier pdf sur le serveur
        url      : url_d,
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                console.log(data);
                file_name_end = data;
                    if( data == 'Errors' ) {
                        create_toast(true , 'La copie sur le serveur a échouée !' )
                    } else {
                        method_prefix = 'save_bdd_';
                        method_name = block_targeted_val;
                        console.log(method_prefix + method_name);
                        //Call fonction de maniere dynamique
                        window[method_prefix + method_name](id_doc, file_name_end);
                    }
                }
        });

    }
);

$('#save_new_doc_autre').click(
    function () {
        var id_site = $("#id_site_autre_doc").val();
        var active_file = $("#doc_pdf_autre").prop('files')[0];
        var name_doc = active_file.name;
        console.log(name_doc);
        var fd = new FormData();
        fd.append('file', active_file);
        fd.append('name_doc', name_doc);
        fd.append('id_site', id_site);
        console.log(active_file);
        console.log('this is ok');
        $.ajax({
        // copy du fichier pdf sur le serveur
        url      : "php/copy_file_pdf_autre.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
/*         success  : function(data) {
                console.log(data);
                refresh_page();
                }
        }); */
        success  : function(data) {
            //refresh_page();
            ( (data.includes("0")) ? create_toast(false , 'Document non-enregistré en BDD -_- ' ) : create_toast(true , 'Document Enregistré !' ) );
            /* setTimeout(() => { 
                refresh_page(); 
            }, 6000); */
            }
        });
        
    }
);

function save_bdd_convention(id_doc_, lien) {
        var fd = new FormData();
        fd.append('id_doc', $("#"+id_doc_).val());
        fd.append('nom_doc', $("#n_c_nom").val());
        fd.append('n_c_date_start', $("#n_c_date_start").val());
        fd.append('n_c_date_end', $("#n_c_date_end").val());
        fd.append('n_c_date_sign', $("#n_c_date_sign").val());
        fd.append('n_c_nb_reconduction', $("#n_c_nb_reconduction").val());
        fd.append('n_c_duree_reconduction_mois', $("#n_c_duree_reconduction_mois").val());
        fd.append('n_c_nom_signataire_1', $("#n_c_nom_signataire_1").val());
        fd.append('n_c_nom_signataire_2', $("#n_c_nom_signataire_2").val());
        fd.append('n_c_type_signataire_1', $("#n_c_type_signataire_1").val());
        fd.append('n_c_type_signataire_2', $("#n_c_type_signataire_2").val());
        fd.append('n_c_categorie_site', $("#n_c_categorie_site").val());
        fd.append('n_c_commentaire', $("#n_c_commentaire").val());
        fd.append('type_doc_', 'convention');
        fd.append('lien',lien);
        $.ajax({
        // chargement du fichier externe monfichier-ajax.php
        url      : "php/ajax/save_document_reference.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                //refresh_page();
                ( (data.includes("0")) ? create_toast(false , 'Document non-enregistré en BDD -_- ' ) : create_toast(true , 'Document Enregistré !' ) );
                setTimeout(() => { 
                    refresh_page(); 
                }, 6000);
                }
        });
    };
function save_bdd_acquisition(id_doc_, lien) {
        var fd = new FormData();
        fd.append('id_doc', $("#"+id_doc_).val());
        fd.append('nom_doc', $("#n_a_nom").val());
        fd.append('n_a_date_sign', $("#n_a_date_sign").val());
        fd.append('n_a_notaire', $("#n_a_notaire").val());
        fd.append('n_a_prix_vente', $("#n_a_prix_vente").val());
        fd.append('n_a_nom_compta', $("#n_a_nom_compta").val());
        fd.append('n_a_surf_tot', $("#n_a_surf_tot").val());
        fd.append('n_a_commentaire', $("#n_a_commentaire").val());
        fd.append('type_doc_', 'acquisition');
        fd.append('lien',lien);
        $.ajax({
        // chargement du fichier externe monfichier-ajax.php
        url      : "php/ajax/save_document_reference.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        dataType : "text",
        success  : function(data) {
                //refresh_page();
                ( (data.includes("0")) ? create_toast(false , 'Document non-enregistré en BDD -_- ' ) : create_toast(true , 'Document Enregistré !' ) );
                setTimeout(() => { 
                    refresh_page(); 
                 }, 6000);
                }
        });
    };
    

function save_bdd_bail_e(id_doc_, lien) {
        var fd = new FormData();
        fd.append('id_doc', $("#"+id_doc_).val());
        fd.append('nom_doc', $("#n_b_e_nom").val());
        fd.append('n_b_e_date_sign', $("#n_b_e_date_sign").val());
        fd.append('n_b_e_notaire', $("#n_b_e_notaire").val());
        fd.append('n_b_e_prix_vente', $("#n_b_e_prix_vente").val());
        fd.append('n_b_e_nom_compta', $("#n_b_e_nom_compta").val());
        fd.append('n_b_e_surf_tot', $("#n_b_e_surf_tot").val());
        fd.append('n_b_e_commentaire', $("#n_b_e_commentaire").val());
        fd.append('type_doc_', 'bail_e');
        fd.append('lien',lien);
        $.ajax({
        // chargement du fichier externe monfichier-ajax.php
        url      : "php/ajax/save_document_reference.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                //refresh_page();
                ( (data.includes("0")) ? create_toast(false , 'Document non-enregistré en BDD -_- ' ) : create_toast(true , 'Document Enregistré !' ) );
                setTimeout(() => { refresh_page(); }, 6000);
                }
        });
    };
    

function save_bdd_bail_rural(id_doc_, lien) {
        var fd = new FormData();
        fd.append('id_doc', $("#"+id_doc_).val());
        fd.append('nom_doc', $("#n_b_nom").val());
        fd.append('n_b_bailleur', $("#n_b_bailleur").val());
        fd.append('n_b_preneur', $("#n_b_preneur").val());
        fd.append('n_b_date_sign', $("#n_b_date_sign").val());
        fd.append('n_b_date_start', $("#n_b_date_start").val());
        fd.append('n_b_date_end', $("#n_b_date_end").val());
        fd.append('n_b_commentaire', $("#n_b_commentaire").val());
        fd.append('type_doc_', 'bail_rural');
        fd.append('lien',lien);
        $.ajax({
        // chargement du fichier externe monfichier-ajax.php
        url      : "php/ajax/save_document_reference.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                //refresh_page();
                ( (data.includes("0")) ? create_toast(false , 'Document non-enregistré en BDD -_- ' ) : create_toast(true , 'Document Enregistré !' ) );
                setTimeout(() => { refresh_page(); }, 6000);
                }
        });
    };

function save_bdd_pret_usage(id_doc_, lien) {
        var fd = new FormData();
        fd.append('id_doc', $("#"+id_doc_).val());
        fd.append('nom_doc', $("#n_p_nom").val());
        fd.append('n_p_preteur', $("#n_p_preteur").val());
        fd.append('n_p_emprunteur', $("#n_p_emprunteur").val());
        fd.append('n_p_date_sign', $("#n_p_date_sign").val());
        fd.append('n_p_date_start', $("#n_p_date_start").val());
        fd.append('n_p_date_end', $("#n_p_date_end").val());
        fd.append('n_p_commentaire', $("#n_p_commentaire").val());
        fd.append('type_doc_', 'pret_usage');
        fd.append('lien',lien);
        $.ajax({
        // chargement du fichier externe monfichier-ajax.php
        url      : "php/ajax/save_document_reference.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                //refresh_page();
                ( (data.includes("0")) ? create_toast(false , 'Document non-enregistré en BDD -_- ' ) : create_toast(true , 'Document Enregistré !' ) );
                setTimeout(() => { refresh_page(); }, 6000);
                }
        });
    };

function save_bdd_ore(id_doc_, lien) {
        var fd = new FormData();
        fd.append('id_doc', $("#"+id_doc_).val());
        fd.append('nom_doc', $("#n_ore_nom").val());
        fd.append('n_ore_notaire', $("#n_ore_notaire").val());
        fd.append('n_ore_acquisition', $("#n_ore_acquisition").val());
        fd.append('n_ore_date_sign', $("#n_ore_date_sign").val());
        fd.append('n_ore_commentaire', $("#n_ore_commentaire").val());
        fd.append('type_doc_', 'ore');
        fd.append('lien',lien);
        $.ajax({
        // chargement du fichier externe monfichier-ajax.php
        url      : "php/ajax/save_document_reference.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                //refresh_page();
                ( (data.includes("0")) ? create_toast(false , 'Document non-enregistré en BDD -_- ' ) : create_toast(true , 'Document Enregistré !' ) );
                setTimeout(() => { refresh_page(); }, 6000);
                }
        });
    };

function save_bdd_ddg(id_doc_, lien) {
        var fd = new FormData();
        fd.append('id_doc', $("#"+id_doc_).val());
        fd.append('nom_doc', $("#n_gestion_nom").val());
        fd.append('n_type_doc_gestion', $("#n_type_doc_gestion").val());
        fd.append('n_gestion_date_start', $("#n_gestion_date_start").val());
        fd.append('n_gestion_date_end', $("#n_gestion_date_end").val());
        let default_date = ( ($("#n_gestion_date_maj").val()) ?  $("#n_gestion_date_maj").val() : ($("#n_gestion_date_start").val())  );
        fd.append('n_gestion_date_maj', default_date );
        fd.append('n_gestion_auteurs', $("#n_gestion_auteurs").val());
        fd.append('n_gestion_commentaire', $("#n_gestion_commentaire").val());
        fd.append('n_multisite', $("#n_multisite").val());
        fd.append('type_doc_', 'ddg');
        fd.append('lien',lien);
        $.ajax({
        // chargement du fichier externe monfichier-ajax.php
        url      : "php/ajax/save_document_reference.js.php",
        type     : 'POST',
        data     : fd ,
        processData : false,
        contentType : false,
        async    : false,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                console.log("doc : "+id_doc_);
                console.log("lien : "+lien);
                //refresh_page();
                console.log(default_date);
                ( (data.includes("0")) ? create_toast(false , 'Document non-enregistré en BDD -_- ' ) : create_toast(true , 'Document Enregistré !' ) );
                setTimeout(() => { 
                    //refresh_page(); 
                }, 6000);
                }
        });
    };

function refresh_page () {
    location.reload(true);
};



//$( "input[id^='layer_leaflet_']" ).each(function( index ) {
//    $(this).change( function () {
//        if (this.checked) {
//            console.log("checked");
//            downlayer = "";
//            var fd = new FormData();
//            fd.append('id_layer'    , layers_array[$(this).attr("name")].id_layer      );
//            fd.append('name_layer'  , layers_array[$(this).attr("name")].name_layer    );
//            fd.append('table_layer' , layers_array[$(this).attr("name")].table_layer   );
//            $.ajax({
//                // chargement du fichier externe monfichier-ajax.php
//                url      : "php/ajax/load_leaflet_layer.js.php",
//                type     : 'POST',
//                data     : fd ,
//                dataType : "json",
//                processData : false,
//                contentType : false,
//                async    : false,
//                error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
//                success  : function(data) {
//                        console.log(data);
//                        downlayer = data;
//                        }
//            });
//            layers_array[$(this).attr("name")].obj_json_layer.addData(downlayer);
//            map.addLayer(layers_array[$(this).attr("name")].obj_json_layer);
//        } else {
//            console.log("You didn't check it! Let me check it for you.");
//            layers_array[$(this).attr("name")].obj_json_layer.clearLayers();;
//        }
//    })
//});

