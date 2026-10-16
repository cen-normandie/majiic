var map;
var shape_for_db_;

// Style des polygones dessinés
var contour_rose={
    color:'#ff09e0',
    fillOpacity:0.4,
    weight:3,
    opacity:0.8
    };
// Style survol
var contour_jaune={
    color:'#f9eb07',
    fillOpacity:0,
    weight:2,
    opacity:1
    };
// Style des parcelles existantes
var contour_green={
    color:'#30842b',
    fillOpacity:0.1,
    weight:2,
    opacity:0.8
    };
// Style des sites
var contour_site={
    color:'#1f6feb',
    fillOpacity:0,
    weight:2,
    opacity:0.8
    };

function initmap() {
    // set up the map
    map = new L.Map('map');
    var ignAttrib = ' IGN / Géoportail';
    var osmAttrib = 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
    var ignOrtho='https://data.geopf.fr/wmts?service=WMTS&request=GetTile&version=1.0.0&layer=CADASTRALPARCELS.PARCELS&style=normal&tilematrixset=PM&format=image/png&tilematrix={z}&tilerow={y}&tilecol={x}';

    var ignO = new L.TileLayer(ignOrtho,{minZoom:4,maxZoom:22,attribution:ignAttrib,opacity: 0.7});
    var osmLink = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmL = new L.TileLayer(osmLink,{minZoom:4,maxZoom:22,attribution:osmAttrib,opacity: 0.7});
    map.setView(new L.LatLng(49.3,0.52),8);
    map.addLayer(ignO);

    // Couche des sites (consultation)
    sites_ = L.geoJson(false, {
        style:contour_site,
        onEachFeature: function(feature,layer)
            {
                if (feature.properties && feature.properties.nom_site) {
                    layer.bindTooltip(feature.properties.nom_site, {sticky:true});
                }
                layer.on("mouseover",function(e){
                    layer.setStyle(contour_jaune);
                });
                layer.on("mouseout",function(e){
                    sites_.resetStyle(e.target);
                });
            }
    }).addTo(map);

    // Couche geojson vide pour les polygones dessinés
    parcelles_le_dessin = L.geoJson(false, {
        style:contour_rose,
        onEachFeature: function(feature,layer)
            {
                layer.on("click",function(e){
                    map.fitBounds(layer.getBounds());
                    });
            }
    }).addTo(map);

    // Couche des parcelles existantes
    parcelles_le_exist = L.geoJson(false, {
        style:contour_rose,
        onEachFeature: function(feature,layer)
            {
                var content = `
<div class="col-lg-12 leaf_title">
    <div class="col-sm-12">
        <div class="form-group">
        <span>Identifiant : </span><span class="fw-bold">${feature.properties.uuid || ''}</span>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
        <span>Numérisateur : </span><span class="fw-bold">${feature.properties.numerisateur || ''}</span>
        </div>
    </div>
</div>`;
                layer.on("mouseover",function(e){
                    layer.setStyle(contour_jaune);
                });
                layer.on("mouseout",function(e){
                    parcelles_le_exist.resetStyle(e.target);
                });
                layer.bindPopup(content, {maxWidth : 400});
            }
    }).addTo(map);

    overlaysMaps={
        "Sites CEN":sites_,
        "Parcelles (dessins)":parcelles_le_dessin,
        "Parcelles déjà renseignées 'Libre Évolution'":parcelles_le_exist
        };
    baseMaps={"OpenStreetMap":osmL,"Parcellaire (IGN)": ignO};
    ControlLayer=L.control.layers(baseMaps,overlaysMaps).addTo(map);
    L.control.scale().addTo(map);

    //draw
    map.addControl(new L.Control.Draw({
        edit: {
            featureGroup: parcelles_le_dessin,
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
        parcelles_le_dessin.addLayer(layer);

        var shape = layer.toGeoJSON()
        shape_for_db_ = JSON.stringify(shape.geometry);
    });
};

// Chargement de la couche des sites
function load_sites() {
    $.ajax({
        url      : "php/ajax/load_sites_cen.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success  : function(data) {
            for (const geojson in data) {
                sites_.addData(data[geojson].geojson);
            }
        }
    });
}

// Chargement des parcelles existantes : couche carto + DataTable
function load_carto() {
    $.ajax({
        url      : "php/ajax/load_parcelles_le.js.php",
        data     : {},
        method   : "POST",
        dataType : "json",
        async    : true,
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        success: function(data) {
            if (!Array.isArray(data)) {
                console.error("load_carto : la réponse n'est pas un tableau", data);
                return;
            }
            data.forEach(function(item, i) {
                let g = item && item.geojson;
                if (!g || !g.geometry) {
                    console.error("Élément invalide à l'index " + i, item);
                    return;
                }
                parcelles_le_exist.addData(g);
                let p = g.properties || {};
                // peuplement direct du DataTable existant
                dtParcellesLe.row.add([
                    p.uuid || '',
                    p.numerisateur || p.utilisateur || '',
                    p.nom_site || ''
                ]);
            });
            dtParcellesLe.draw();
        }
    });
}


$("#save_hab").click(function(){

    $.ajax({
        url      : "php/ajax/save_parcelles_le.js.php",
        data     : {
            geom: shape_for_db_,
            numerisateur: $("#input_observateur").val()
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

var dtParcellesLe =    $('#parcellesle').DataTable({
    "language": {
    "paginate": {
    "previous": "Préc.",
    "next": "Suiv."
    },
    "search": "Filtrer :",
    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
    "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
    "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
    "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
    "sInfoPostFix":    "",
    "sLoadingRecords": "Chargement en cours...",
    "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
    "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau"
},
dom: 't<"bottom"<"d-flex justify-content-between align-items-center"fpB>>',
buttons: [
    { 
    extend: 'excel', 
    text:'Excel',
    className: 'btn btn-success my-2'
    }
    ],
paging: true
 });

dtParcellesLe.column(0).visible(false);
