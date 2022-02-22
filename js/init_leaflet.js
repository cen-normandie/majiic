var map;

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
    map = new L.Map('map'//,{drawControl: true}
    );
    

    //TileLayer
    var ignAttrib = ' IGN / Géoportail';
    var osmAttrib = 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
    var stamenAttrib = 'Map data © <a href="http://maps.stamen.com/#watercolor/12/37.7706/-122.3782">maps.stamen.com</a>  ';
    
    var osmUrlbg='http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png';
    var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var ignOrtho='https://wxs.ign.fr/essentiels/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=ORTHOIMAGERY.ORTHOPHOTOS&format=image/jpeg&style=normal';
    var StamenWaterColor='https://stamen-tiles.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.jpg';
    //var ign_cadastre='https://wxs.ign.fr/essentiels/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=CADASTRALPARCELS.PARCELS&format=image/jpeg&style=normal';

    var osmbg=new L.TileLayer(osmUrlbg,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.6});
    var osm=new L.TileLayer(osmUrl,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.6});
    var ignO = new L.TileLayer(ignOrtho,{minZoom:4,maxZoom:22,attribution:ignAttrib,opacity: 0.7});
    //var ignC = new L.TileLayer(ign_cadastre,{minZoom:4,maxZoom:22,attribution:ignAttrib,opacity: 0.7});
    var swc = new L.TileLayer(StamenWaterColor,{minZoom:4,maxZoom:22,attribution:stamenAttrib,opacity: 0.7});
    
    
    map.setView(new L.LatLng(48.900,-0.47),8);
    map.addLayer(swc);
    
    
    // Créer une couche geojson vide pour les Contours Admin
    admin_geojson_feature = L.geoJson(false, {
        style:contour_jaune
    }).addTo(map);
    
    
    parcelles = L.geoJson(false, {
        style:contour_rose,
        onEachFeature: function (feature, layer) //functionality on click on feature
            {
var content = '\
<div class="col-lg-12 leaf_title" >\
    <div class="col-sm-12">\
        <div class="form-group">\
        <span>Commune : '+feature.properties.commune+'</span>\
        </div>\
    </div>\
    <div class="col-sm-12">\
        <div class="form-group">\
        <span>Prefixe : '+feature.properties.prefixe+'</span>\
        </div>\
    </div>\
    <div class="col-sm-12">\
        <div class="form-group">\
        <span>Section : '+feature.properties.section+'</span>\
        </div>\
    </div>\
    <div class="col-sm-12">\
        <div class="form-group">\
        <span>Numéro : '+feature.properties.numero+'</span>\
        </div>\
    </div>\
</div>';
                
                
                layer.on("mouseover",function(e){
                    layer.setStyle(contour_green);
                });
                layer.on("mouseout",function(e){
                    parcelles.resetStyle(e.target);
                });
                layer.bindPopup(content, {maxWidth : 400})
                .on('popupopen', function (popup) {
                    
                })
                .on('popupclose', function (popup) {
                });
                layer.on("click",function(e){
                    $.ajax({
                        url      : "php/ajax/get_data.js.php",
                        data     : {id: feature.properties.id},
                        method   : "POST",
                        dataType : "json",
                        async    : false,
                        error    : function(request, error) { alert("L'identifiant de parcelle n'a pas de correspondance majiic DGFIP v.2018\nContactez votre géomaticien");},
                        success  : function(data) {
                            //dt4.clear().draw();
                            data.forEach(function(obj) {
                                x.t_nb_rows++;
                                var rowNode = dt4.row.add( [
                                x.t_nb_rows,
                                obj.idpar,
                                obj.nom_usage,
                                obj.prenom_usage,
                                obj.date_naissance,
                                obj.adresse_prop +' '+ obj.cp_prop,
                                obj.ddenom,
                                obj.typproptxt,
                                obj.ssuf
                                ] ).draw( true ).node();
                            });
                            
                        }
                    });
                });
            },
    }).addTo(map);
    
    overlaysMaps={"Parcelles":parcelles,"Contours Administratifs":admin_geojson_feature};
    baseMaps={ "IGN Ortho":ignO,"OSM":osm,"OSM (Noir & Blanc)":osmbg,"Watercolor":swc }; //"IGN Parcellaire":ign_cadastre,
    //baseMaps={"OSM N&B":osmbg,"OSM Watercolor":osmWatercolor};
    ControlLayer=L.control.layers(baseMaps,overlaysMaps).addTo(map); 
        
    map.on('zoomend', function() {
    var zoomlevel = map.getZoom();
        if (zoomlevel  <=14){
                map.removeLayer(parcelles);
        }
        else {
                map.addLayer(parcelles);
        }
    });
    L.control.scale().addTo(map);
};



function clear_all_layer() {
    //geojson_layer
    admin_geojson_feature.clearLayers();
    parcelles.clearLayers();
    sessionStorage.clear();
}







































