const autoCompleteConfig = [{
    name: 'Admins',
    debounceMS: 250,
    minLength: 2,
    maxResults: 20,
    inputSource: document.getElementById('autocompleteAdmin'),
    targetID: '',
    fetchURL: 'none',
    fetchMap: {id: "id",
               name: "name",
               tablename: "tablename",
               geometry: "geometry"
               },
    ajax: true,
    ajaxurl : './php/ajax/autocomplete/com_parcelle.ajax.js.php',
    bold: true
  }
];


// Initiate Autocomplete to Create Listeners
autocompleteBS(autoCompleteConfig);

function resultHandlerBS(inputName, selectedData) {
    change_load ('Chargement des parcelles');
    var id_selected = selectedData.id;
    admin_geojson_feature.clearLayers();
    admin_geojson_feature.addData(selectedData.geometry);
    map.fitBounds(admin_geojson_feature.getBounds());
    display_parcelles_in_area(id_selected, selectedData.tablename );
}