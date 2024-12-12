var map;
var shape_for_db_;
// Style Neutre
var contour_jaune={
    color:'#f9eb07',
    fillOpacity:0,
    weight:2,
    opacity:1
    };
// Style Rose
var contour_rose={
    color:'#ff09e0',
    fillOpacity:0.4,
    weight:0.4,
    opacity:0.8
    };
var contour_green={
    color:'#30842b',
    fillOpacity:0.1,
    weight:2,
    opacity:0.8
    };
function initmap() {
    // set up the map
    map = new L.Map('map');
    var ignAttrib = ' IGN / Géoportail';
    var osmAttrib = 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
    var ignOrtho='https://data.geopf.fr/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=ORTHOIMAGERY.ORTHOPHOTOS&format=image/jpeg&style=normal';
    var ignO = new L.TileLayer(ignOrtho,{minZoom:4,maxZoom:22,attribution:ignAttrib,opacity: 0.7});
    var osmLink = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmL = new L.TileLayer(osmLink,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.7});
    map.setView(new L.LatLng(49.3,0.52),8);
    map.addLayer(osmL);
    // Créer une couche geojson vide pour les Contours Admin
    poly_ = L.geoJson(false, {
        style:contour_rose,
        onEachFeature: function(feature,layer)
            {
                layer.on("click",function(e){
                    map.fitBounds(layer.getBounds());
                    });
                layer.on("mouseover",function(e){
                    layer.setStyle(contour_jaune);
                });
                layer.on("mouseout",function(e){
                    poly_.resetStyle(e.target);
                });
            }
    }).addTo(map);
    poly_exist = L.geoJson(false, {
        style:contour_green,
        onEachFeature: function(feature,layer)
            {
                let links_='';
                if (feature.properties.taxons) {
                    let taxons_ = feature.properties.taxons.split(', ');
                    for (const taxon in taxons_) {
                        let split = taxons_[taxon].split(' - ');
                        let cd_nom__ = split[1];
                        let nom_complet__ = split[0];
                        links_ +=`<div class="mx-1">
                          <a href="https://inpn.mnhn.fr/espece/cd_nom/${cd_nom__}" target="_blank" class="link-success style="font-size:11px"">
                            <div>${nom_complet__}</div>
                          </a>
                        </div>`;
                    }
                }

                var content = `
<div class="col-lg-12 leaf_title" style="max-height:400px;overflow-y:scroll;" >
    <div class="col-sm-12">
        <div class="form-group">
        <span>Code : </span><span class="fw-bold">${feature.properties.code}</span>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
        <span>Typo : </span><span class="fw-bold">${feature.properties.typo}</span>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
        <span>Libellé : </span><span class="fw-bold">${feature.properties.lb}</span>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
        <span>Date saisie : </span><span class="fw-bold">${feature.properties.annee_saisie}</span>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
        <span>Observateur : </span><span class="fw-bold">${feature.properties.observateur}</span>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
        <li class="list-group-item d-flex justify-content-between align-items-center bg-light"><span class="" style="font-size:10px">Taxons (associés si existants):</span></li>
            <li class="list-group-item">
                <div id="" class="d-flex flex-wrap align-items-start" style="max-height:150px;overflow-y:scroll;">
                ${links_}
                </div>
            </li>
        </li>
        </div>
    </div>
</div>`;
                layer.on("click",function(e){

                    });
                layer.on("mouseover",function(e){
                    $("#code").val(feature.properties.code);
                    $("#nom_complet").val(feature.properties.lb);
                    $("#annee").val(feature.properties.annee_saisie);
                    $("#observateur").val(feature.properties.observateur);
                    $("#jdd_carto").val(feature.properties.jdd_carto);
                    let typo = '';
                    switch (feature.properties.typo) {
                        case '7':
                            typo='Eunis 2012';
                          break;
                        case '100':
                            typo='Eurovegchecklist, 2016';
                        case '107':
                            typo='Eunis 2022';
                            break;
                        default:
                          console.log(`Sorry, non défini`);
                    }
                    $("#typo").val(typo);
                    $("#taxons_").html(links_);
                    


                });
                layer.on("mouseout",function(e){
                    $("#code").val('...');
                    $("#nom_complet").val('...');
                    $("#annee").val('...');
                    $("#observateur").val('...');
                    $("#jdd_carto").val('...');
                    $("#typo").val('...');
                    $("#taxons_").html('...');
                });
                layer.bindPopup(content, {maxWidth : 400})
                .on('popupopen', function (popup) {
                    
                })
                .on('popupclose', function (popup) {
                });
            }
    }).addTo(map);
    overlaysMaps={"Polygones (dessins)":poly_,"Polygones existants":poly_exist};
    baseMaps={"OpenStreetMap":osmL,"Ortho (IGN)": ignO};
    ControlLayer=L.control.layers(baseMaps,overlaysMaps).addTo(map);
    L.control.scale().addTo(map);
    //draw
    map.addControl(new L.Control.Draw({
        edit: {
            featureGroup: poly_,
            poly: {
                allowIntersection: false
            }
        },
        draw: {
            marker: false,
            circle: false,
            circlemarker: false,
            rectangle: false,
            polyline: false,
            polygon: {
                allowIntersection: false,
                showArea: true
            }
        }
    }));

    map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;
        $("#modalHabitat").modal("show");
        let u_ = $("#courriel").html().trim();
        $("#input_observateur").val( u_ );
        poly_.addLayer(layer);
        
        var shape = layer.toGeoJSON()
        shape_for_db_ = JSON.stringify(shape.geometry); 
    });

};



function clear_all_layer() {
    //geojson_layer
    poly.clearLayers();
}


function load_carto () {
    $.ajax({
        url      : "php/ajax/load_carto.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            console.log(data[0].geojson);
            //poly_exist.addLayer(data[0].geojson);
            for (const geojson in data) {
                //habitats_array.push(habitats[habitat].cd_hab+' - '+habitats[habitat].lb_hab_fr);
                poly_exist.addData(data[geojson].geojson);
            }
            
            }
    });
}


function load_habitat_ajax () {
    $.ajax({
        url      : "php/ajax/load_habitat.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            habitats = data ;
            //console.log(data);
            let habitats_array = [];
            for (const habitat in habitats) {
                habitats_array.push(habitats[habitat].cd_hab+' - '+habitats[habitat].lb_hab_fr+' - '+habitats[habitat].cd_typo);
            }
            habitats_array.sort();
            autocompleteArray_FG(document.getElementById("input_hab"), habitats_array);
            autocompleteArray_FG(document.getElementById("input_hab_b"), habitats_array);
            autocompleteArray_FG(document.getElementById("input_hab_c"), habitats_array);
            }
    });
}

$("#save_hab").click(function(){ 
    let lb_code_b, lb_lib_b, lb_typo_b ='';
    let lb_code_c, lb_lib_c, lb_typo_c ='';
    if ($("#input_hab_b").val()) {
        lb_code_b= $("#input_hab_b").val().split(' - ')[0];
        lb_lib_b= $("#input_hab_b").val().split(' - ')[1];
        lb_typo_b= $("#input_hab_b").val().split(' - ')[2];
    }
    if ($("#input_hab_c").val()) {
        lb_code_c= $("#input_hab_c").val().split(' - ')[0];
        lb_lib_c= $("#input_hab_c").val().split(' - ')[1];
        lb_typo_c= $("#input_hab_c").val().split(' - ')[2];
    }
            

    $.ajax({
        url      : "php/ajax/save_habitat.js.php",
        data     : {
            geom: shape_for_db_,
            lb_code: $("#input_hab").val().split(' - ')[0],
            lb_lib: $("#input_hab").val().split(' - ')[1],
            lb_typo: $("#input_hab").val().split(' - ')[2],
            observateur: $("#input_observateur").val(),
            lb_code_b_:lb_code_b, 
            lb_lib_b_:lb_lib_b, 
            lb_typo_b_:lb_lib_b,
            lb_code_c_:lb_code_c, 
            lb_lib_c_:lb_lib_c, 
            lb_typo_c_:lb_lib_c
        },
        method   : "POST",
        dataType : "text",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
                console.log("saved");
                $("#modalHabitat").modal("hide");
                location.reload();
            }
    });
 });

































