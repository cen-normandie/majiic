var map;
var geojson_;

//Style des différentes couches
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


function initmap() {
    // set up the map
    map = new L.Map('map');
    
    var ignAttrib = ' IGN © ORTHO / IGN © PARCELLAIRE / IGN © SCAN25';
    var osmAttrib = 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
    
    var osmUrlbg='http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png';
    var ignOrtho='http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=ORTHOIMAGERY.ORTHOPHOTOS&format=image/jpeg&style=normal';
    var ignSCAN25='http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=GEOGRAPHICALGRIDSYSTEMS.MAPS&format=image/jpeg&style=normal';
    var wmsLayer = L.tileLayer.wms('http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/r/wms?', {layers: 'CADASTRALPARCELS.PARCELS',attribution:ignAttrib,opacity: 0.5});

    var osmbg=new L.TileLayer(osmUrlbg,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.5});
    var ignO = new L.TileLayer(ignOrtho,{minZoom:4,maxZoom:22,attribution:ignAttrib,opacity: 0.5});
    var ignS = new L.TileLayer(ignSCAN25,{minZoom:4,maxZoom:22,attribution:ignAttrib,opacity: 0.5});
    
    
    var lyr = L.geoportalLayer.WMTS(
        {
            layer  : "ORTHOIMAGERY.ORTHOPHOTOS"
        },
        {
            opacity : 0.8
        }
    ) ;
    lyr.addTo(map); // ou map.addLayer(lyr);

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
                layer.bindLabel(feature.properties.nom_site);
            }
    }).addTo(map);
    
//Gp.Services.getConfig({
//    apiKey : "7b2gdeangufdo4oyg6d42bp2",
//    onSuccess : go
//}) ;

    overlaysMaps={"Sites":sites_geojson_feature};
    baseMaps={"Ortho (IGN)":lyr,"OSM (Noir & Blanc)":osmbg};
    ControlLayer=L.control.layers(baseMaps,overlaysMaps).addTo(map);

};

initmap();
























