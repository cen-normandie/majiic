var map;
var geojson_;
var sites_names_ids_array = new Object();
var parcelles_names_ids_array = new Object();
var layer_in_geojson = new Object();
var names_ids_tab_array = new Array();
var parcelles_names_ids_tab_array = new Array();



//Style des différentes couches
// Style Over entity
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
    weight:1,
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



function initmap() {
    // set up the map
    map = new L.Map('map'//,{drawControl: true}
                    );
    var osmUrlbg='http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png';
    var ignOrtho='http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=ORTHOIMAGERY.ORTHOPHOTOS&format=image/jpeg&style=normal';
    var ignSCAN25='http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=GEOGRAPHICALGRIDSYSTEMS.MAPS&format=image/jpeg&style=normal';
    var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors / IGN © ORTHO 2006-2010 / IGN © PARCELLAIRE';
    var wmsLayer = L.tileLayer.wms('http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/r/wms?', {layers: 'CADASTRALPARCELS.PARCELS',attribution:osmAttrib});

    var osmbg=new L.TileLayer(osmUrlbg,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.6});
    var ignO = new L.TileLayer(ignOrtho,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.6});
    var ignS = new L.TileLayer(ignSCAN25,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.6});


    map.setView(new L.LatLng(49.3,0.52),8);
    map.addLayer(osmbg);
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
            layer.bindLabel('Section : '+feature.properties.id_unique.substring(17, 19)+', Num : '+ feature.properties.id_unique.substring(19, 23));
            }
    }).addTo(map);
    // Créer une couche geojson vide pour les sites
    sites_geojson_feature = L.geoJson(false, {
        style:style_sites_rouge,
        onEachFeature: function(feature,layer)
            {
                layer.on("click",function(e){
                    map.fitBounds(layer.getBounds());
                    });
            layer.bindLabel(feature.properties.nom_site);
            }
    }).addTo(map);
    
    
    
    overlaysMaps={"Parcelles":sites_parcelles_geojson_feature,"Sites":sites_geojson_feature };
    baseMaps={"Ortho (IGN)":ignO,"SCAN 25 (IGN)":ignS, "BD Parcellaire (IGN)":wmsLayer, "OSM (Noir & Blanc)":osmbg};
    ControlLayer=L.control.layers(baseMaps,overlaysMaps).addTo(map);
    
};

initmap();
























