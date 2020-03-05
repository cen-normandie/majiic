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
    var ignOrtho='http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=ORTHOIMAGERY.ORTHOPHOTOS&format=image/jpeg&style=normal';
    var ignSCAN25='http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/wmts?service=WMTS&request=GetTile&version=1.0.0&tilematrixset=PM&tilematrix={z}&tilecol={x}&tilerow={y}&layer=GEOGRAPHICALGRIDSYSTEMS.MAPS&format=image/jpeg&style=normal';
    var ignAttrib='<a href="http://www.ign.fr/">IGN © </a> IGN© WM(T)S BD ORTHO, BD PARCELLES, BD SCAN';
    
    var ign_cadastre =new L.tileLayer.wms('http://wxs.ign.fr/apgyusriiwvbm0osuwsff2dg/geoportail/r/wms?', {layers: 'CADASTRALPARCELS.PARCELS',attribution:ignAttrib,opacity:0.8});
    var ignO = new L.TileLayer(ignOrtho,{minZoom:1,maxZoom:24,attribution:ignAttrib,opacity:0.8});
    var ignS = new L.TileLayer(ignSCAN25,{minZoom:1,maxZoom:24,attribution:ignAttrib,opacity:0.8});
    
    
    map.setView(new L.LatLng(48.900,-0.47),8);
    map.addLayer(ign_cadastre);
    
    
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
                        async    : true,
                        error    : function(request, error) { alert("L'identifiant de parcelle n'a pas de correspondance majiic DGFIP v.2017\nContactez votre géomaticien");},
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
    baseMaps={"IGN Parcellaire":ign_cadastre, "IGN Ortho":ignO,"IGN SCAN25":ignS };
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







































