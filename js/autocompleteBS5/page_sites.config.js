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
  var id_selected = selectedData.id;
  console.log(id_selected);
}