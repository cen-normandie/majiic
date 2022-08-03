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
    map = new L.Map('map');
    
    var ignAttrib = ' IGN / Géoportail';
    var osmAttrib = 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
    
    //var osmUrlbg='http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png';
    //var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var ignOrtho='https://wxs.ign.fr/essentiels/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=ORTHOIMAGERY.ORTHOPHOTOS&format=image/jpeg&style=normal';

    //var osmbg=new L.TileLayer(osmUrlbg,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.6});
    //var osm=new L.TileLayer(osmUrl,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.6});
    var ignO = new L.TileLayer(ignOrtho,{minZoom:4,maxZoom:22,attribution:ignAttrib,opacity: 0.7});

    map.setView(new L.LatLng(49.3,0.52),8);
    map.addLayer(ignO);
    
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
            //layer.bindLabel(feature.properties.nom_site);
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
            layer.bindLabel('Section : '+feature.properties.id_unique.substring(17, 19)+', Num : '+ feature.properties.id_unique.substring(19, 23));
            }
    }).addTo(map);
    
    overlaysMaps={"Sites":sites_geojson_feature, "Parcelles":sites_parcelles_geojson_feature };
    baseMaps={"Ortho (IGN)":ignO};
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



























