const autoCompleteConfig = [{
    name: 'Admins',
    debounceMS: 250,
    minLength: 2,
    maxResults: 20,
    inputSource: document.getElementById('input_site'),
    targetID: '',
    fetchURL: 'none',
    fetchMap: {id: "id",
               name: "name",
               tablename: "tablename",
               geometry: "geometry"
               },
    ajax: true,
    ajaxurl : './php/ajax/autocomplete/sites.ajax.js.php',
    bold: true
  }
];


// Initiate Autocomplete to Create Listeners
autocompleteBS(autoCompleteConfig);

function resultHandlerBS(inputName, selectedData) {

    //map.fitBounds(layer_in_geojson[id_site].getBounds());
    //get_data_site_on_autocomplete(id_site);
    // change_load ("Chargement du site");
    get_data_site_on_autocomplete(selectedData.id);
    
    var id_selected = selectedData.id;
    console.log(id_selected);

    //sites_geojson_feature.clearLayers();
    // sites_geojson_feature.addData(selectedData.geometry);
    // map.fitBounds(sites_geojson_feature.getBounds());
    display_parcelles_in_area(id_selected, selectedData.tablename );
}