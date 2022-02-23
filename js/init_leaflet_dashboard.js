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
    
    var ignAttrib = ' IGN / Géoportail';
    var osmAttrib = 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
    var stamenAttrib = 'Map data © <a href="http://maps.stamen.com/#watercolor/12/37.7706/-122.3782">maps.stamen.com</a>  ';
    
    var osmUrlbg='http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png';
    var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var ignOrtho='https://wxs.ign.fr/essentiels/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=ORTHOIMAGERY.ORTHOPHOTOS&format=image/jpeg&style=normal';
    var StamenWaterColor='https://stamen-tiles.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.jpg';

    var osmbg=new L.TileLayer(osmUrlbg,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.6});
    var osm=new L.TileLayer(osmUrl,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.6});
    var ignO = new L.TileLayer(ignOrtho,{minZoom:4,maxZoom:22,attribution:ignAttrib,opacity: 0.7});
    var swc = new L.TileLayer(StamenWaterColor,{minZoom:4,maxZoom:22,attribution:stamenAttrib,opacity: 0.7});

    map.setView(new L.LatLng(49.3,0.52),8);
    map.addLayer(osm);

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
                layer.bindTooltip(feature.properties.nom_site);
            }
    }).addTo(map);
    

    overlaysMaps={"Sites":sites_geojson_feature};
    baseMaps={"Ortho (IGN)":ignO,"OSM":osm,"OSM (Noir & Blanc)":osmbg,"Watercolor":swc};
    ControlLayer=L.control.layers(baseMaps,overlaysMaps).addTo(map);

};

initmap();
























