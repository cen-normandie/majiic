var map;
var geojson_;
var sites_names_ids_array = new Object();
var parcelles_names_ids_array = new Object();
var layer_in_geojson = new Object();
var names_ids_tab_array = new Array();
var parcelles_names_ids_tab_array = new Array();



//Style des différentes couches
// Style Over entity
var highlightStyle={
    color:'#000000',
    weight:5,
    opacity:1
    };
var highlightStyle_Yellow={
    color:'#f9eb07',
    weight:5,
    opacity:1
    };
// Style Neutre
var style_sites_rouge={
    fillColor:'#d73737',
    color:'#d73737',
    fillOpacity:0.3,
    weight:4,
    opacity:1
    };
// Style Parcelles
var style_parcelles={
    fillColor:'#000000',
    color:'#000000',
    fillOpacity:0.1,
    weight:2,
    opacity:0.8
    };
// Style APB
var style_apb={
    fillColor:'#998099',
    color:'#998099',
    fillOpacity:0.2,
    weight:2,
    opacity:0.8
    };
    
    
    
// Style NATURA 2000
var style_union_natura={
    fillColor:'#236101',
    color:'#236101',
    fillOpacity:0.2,
    weight:2,
    opacity:0.8
    };
// Style PNR
var style_pnr={
    fillColor:'#66cb2f',
    color:'#66cb2f',
    fillOpacity:0.2,
    weight:2,
    opacity:0.8
    };
// Style RNN
var style_rnn={
    fillColor:'#b922d7',
    color:'#b922d7',
    fillOpacity:0.2,
    weight:2,
    opacity:0.8
    };
// Style RNR
var style_rnr={
    fillColor:'#f405ec',
    color:'#f405ec',
    fillOpacity:0.2,
    weight:2,
    opacity:0.8
    };
// Style znieff1_g2
var style_znieff1_g2={
    fillColor:'#ae4c4c',
    color:'#ae4c4c',
    fillOpacity:0.2,
    weight:2,
    opacity:0.8
    };
// Style znieff2_g2
var style_znieff2_g2={
    fillColor:'#e71212',
    color:'#e71212',
    fillOpacity:0.2,
    weight:2,
    opacity:0.8
    };


function initmap() {
    // set up the map
    map = new L.Map('map'//,{drawControl: true}
                    );
    // create the tile layer with correct attribution
    //var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    //var osmUrlcolor='http://{s}.tile.stamen.com/watercolor/{z}/{x}/{y}.jpg';
    var osmUrlbg='http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png';
    var ignOrtho='http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=ORTHOIMAGERY.ORTHOPHOTOS&format=image/jpeg&style=normal';
    var ignSCAN25='http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=GEOGRAPHICALGRIDSYSTEMS.MAPS&format=image/jpeg&style=normal';
    var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors / IGN © ORTHO 2006-2010 / IGN © PARCELLAIRE';
    var wmsLayer = L.tileLayer.wms('http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/r/wms?', {layers: 'CADASTRALPARCELS.PARCELS',attribution:osmAttrib});
    //var wmsLayer = L.tileLayer.wms('http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/r/wms?', {layers: 'CADASTRALPARCELS.PARCELS',attribution:osmAttrib});
    
    //var osm=new L.TileLayer(osmUrl,{minZoom:1,maxZoom:24,attribution:osmAttrib});
    //var osmcolor=new L.TileLayer(osmUrlcolor,{minZoom:1,maxZoom:24,attribution:osmAttrib});
    var osmbg=new L.TileLayer(osmUrlbg,{minZoom:4,maxZoom:22,attribution:osmAttrib});
    var ignO = new L.TileLayer(ignOrtho,{minZoom:4,maxZoom:22,attribution:osmAttrib});
    var ignS = new L.TileLayer(ignSCAN25,{minZoom:4,maxZoom:22,attribution:osmAttrib});


    map.setView(new L.LatLng(49.3,0.52),8);
    map.addLayer(osmbg);
    
    // Créer une couche geojson vide pour les sites
    sites_geojson_feature = L.geoJson(false, {
        style:style_sites_rouge,
        onEachFeature: function(feature,layer)
            {
                layer.on("click",function(e){
                    map.fitBounds(layer.getBounds());
                    });
                layer.on("mouseover",function(e){
                    layer.setStyle(highlightStyle_Yellow);
                });
                layer.on("mouseout",function(e){
                    sites_geojson_feature.resetStyle(e.target);
                });
            layer_in_geojson[feature.properties.id_site] = layer;
            layer.bindTooltip(feature.properties.nom_site);
            }
    }).addTo(map);
    
    // Créer une couche geojson vide pour les parcelles
    sites_parcelles_geojson_feature = L.geoJson(false, {
        style:style_parcelles,
        onEachFeature: function(feature,layer)
            {
                layer.on("click",function(e){
                    });
                layer.on("mouseover",function(e){
                    layer.setStyle(highlightStyle_Yellow);
                });
                layer.on("mouseout",function(e){
                    sites_parcelles_geojson_feature.resetStyle(e.target);
                });
            //console.log(feature.properties.id_site);
            layer_in_geojson[feature.properties.id_unique] = layer;
            layer.bindTooltip('Section : '+feature.properties.id_unique.substring(17, 19)+', Num : '+ feature.properties.id_unique.substring(19, 23));
            }
    }).addTo(map);
    
    overlaysMaps={"Sites":sites_geojson_feature, "Parcelles":sites_parcelles_geojson_feature };
    baseMaps={"Ortho (IGN)":ignO,"SCAN 25 (IGN)":ignS, "BD Parcellaire (IGN)":wmsLayer, "OSM (Noir & Blanc)":osmbg};
    ControlLayer=L.control.layers(baseMaps,overlaysMaps).addTo(map); 

    $.ajax({
        url      : "php/ajax/load_sites.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            $(data.features).each(function(key, data) {
                //ajoute les geojson
                sites_geojson_feature.addData(data);
                sites_names_ids_array[data.properties.id_site] = data.properties.nom_site;
            });
            //map.fitBounds(sites_geojson_feature.getBounds());
                
            for (var y in sites_names_ids_array) {
                names_ids_tab_array.push( sites_names_ids_array[y] + ' - ' + y );
            };
        }// END SUCCESS
        });// End ajax
     $.ajax({
        url      : "php/ajax/load_parcelles_map.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            $(data.features).each(function(key, data) {
                //ajoute les geojson
                sites_parcelles_geojson_feature.addData(data);
                parcelles_names_ids_array[data.properties.id_unique] = data.properties.id_unique;
            });
            for (var y in parcelles_names_ids_array) {
                parcelles_names_ids_tab_array.push( parcelles_names_ids_array[y] + ' - ' + y );
            };
        }// END SUCCESS
        });// End ajax
};



initmap();



//// SUIVI.JS //////////////////////////////////////////////////////////////////////////////////////////////////////////////
//layers_array = new Array(); // array of events
//apb             =L.geoJson(false, {style:style_apb         , onEachFeature: function(feature,layer){layer.on("mouseover",function(e){layer.setStyle(highlightStyle_Yellow);});layer.on("mouseout",function(e){apb.resetStyle(e.target);});layer.bindTooltip('ID : '+feature.properties.id+', Nom : '+ feature.properties.nom);}});
//n2cesic         =L.geoJson(false, {style:style_union_natura, onEachFeature: function(feature,layer){layer.on("mouseover",function(e){layer.setStyle(highlightStyle_Yellow);});layer.on("mouseout",function(e){n2cesic.resetStyle(e.target);});layer.bindTooltip('ID : '+feature.properties.id+', Nom : '+ feature.properties.nom);}});
//n2zps           =L.geoJson(false, {style:style_union_natura, onEachFeature: function(feature,layer){layer.on("mouseover",function(e){layer.setStyle(highlightStyle_Yellow);});layer.on("mouseout",function(e){n2zps.resetStyle(e.target);});layer.bindTooltip('ID : '+feature.properties.id+', Nom : '+ feature.properties.nom);}});
////n2joue          =L.geoJson(false, {style:style_union_natura, onEachFeature: function(feature,layer){layer.on("mouseover",function(e){layer.setStyle(highlightStyle_Yellow);});layer.on("mouseout",function(e){n2joue.resetStyle(e.target);});layer.bindTooltip('ID : '+feature.properties.id+', Nom : '+ feature.properties.nom);}});
//pnr             =L.geoJson(false, {style:style_pnr         , onEachFeature: function(feature,layer){layer.on("mouseover",function(e){layer.setStyle(highlightStyle_Yellow);});layer.on("mouseout",function(e){pnr.resetStyle(e.target);});layer.bindTooltip('ID : '+feature.properties.id+', Nom : '+ feature.properties.nom);}});
//rnn             =L.geoJson(false, {style:style_rnn         , onEachFeature: function(feature,layer){layer.on("mouseover",function(e){layer.setStyle(highlightStyle_Yellow);});layer.on("mouseout",function(e){rnn.resetStyle(e.target);});layer.bindTooltip('ID : '+feature.properties.id+', Nom : '+ feature.properties.nom);}});
//rnr             =L.geoJson(false, {style:style_rnr         , onEachFeature: function(feature,layer){layer.on("mouseover",function(e){layer.setStyle(highlightStyle_Yellow);});layer.on("mouseout",function(e){rnr.resetStyle(e.target);});layer.bindTooltip('ID : '+feature.properties.id+', Nom : '+ feature.properties.nom);}});
//znieff1_g2      =L.geoJson(false, {style:style_znieff1_g2  , onEachFeature: function(feature,layer){layer.on("mouseover",function(e){layer.setStyle(highlightStyle_Yellow);});layer.on("mouseout",function(e){znieff1_g2.resetStyle(e.target);});layer.bindTooltip('ID : '+feature.properties.id+', Nom : '+ feature.properties.nom);}});
//znieff2_g2      =L.geoJson(false, {style:style_znieff2_g2  , onEachFeature: function(feature,layer){layer.on("mouseover",function(e){layer.setStyle(highlightStyle_Yellow);});layer.on("mouseout",function(e){znieff2_g2.resetStyle(e.target);});layer.bindTooltip('ID : '+feature.properties.id+', Nom : '+ feature.properties.nom);}});
//
//// SUIVI.JS //////////////////////////////////////////////////////////////////////////////////////////////////////////////
//function Layer_ (name_,id_layer_,name_layer_,table_layer_,obj_json_layer_) {
//    this.name                   = name_;
//    this.id_layer               = id_layer_;
//    this.name_layer             = name_layer_;
//    this.table_layer            = table_layer_;
//    this.obj_json_layer         = obj_json_layer_;
//    layers_array[name_]         = this;
//};
//// SUIVI.JS //////////////////////////////////////////////////////////////////////////////////////////////////////////////
//new Layer_(     "apb",                  "id_mnhn",              "nom_site",             " carto_inpn.apb "               , apb          );
//new Layer_(     "n2cesic",              "sitecode",             "sitename",             " carto_inpn.natura_ce_sic "     , n2cesic );
//new Layer_(     "n2zps",                "sitecode",             "sitename",             " carto_inpn.natura_zps "        , n2zps );
////new Layer_(     "n2joue",               "sitecode",             "sitename",             " carto_inpn.natura_joue_sic_ue ", n2joue );
//new Layer_(     "pnr",                  "id_mnhn",              "nom_site",             " carto_inpn.pnr "               , pnr          );
//new Layer_(     "rnn",                  "id_local",             "nom_site",             " carto_inpn.rnn "               , rnn          );
//new Layer_(     "rnr",                  "id_local",             "nom_site",             " carto_inpn.rnr "               , rnr          );
//new Layer_(     "znieff1_g2",           "id_mnhn",              "nom",                  " carto_inpn.znieff1_g2 "        , znieff1_g2   );
//new Layer_(     "znieff2_g2",           "id_mnhn",              "nom",                  " carto_inpn.znieff2_g2 "        , znieff2_g2   );



























