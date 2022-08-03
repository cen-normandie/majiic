function debounce(func, wait, immediate) {
    console.log("debounce");
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

function clearListBS() {
  let autocompleteBSDiv = document.getElementById("autocompleteBS-list");
  if( autocompleteBSDiv != null ) autocompleteBSDiv.remove();
}

function addResultsBS(config, results, term) {
  clearListBS();
  const newDiv = document.createElement('div');
  const sourceBS = config.inputSource;

  newDiv.classList.add("autocompleteBS-items");
  newDiv.setAttribute('data-forinputbs', sourceBS.id);
  newDiv.setAttribute('data-current', -1);
  newDiv.setAttribute('data-results', results.length);

  if ( results.length === 0 ) {
    console.log('No Matches - Push a Message onto Results');
    let pseudoResult = { [config.fetchMap.id]: "noMatchesBS", [config.fetchMap.name]: "No Matches Found - Please try again..." };
    results.push(pseudoResult);
  }
  newDiv.id = "autocompleteBS-list";
  let resultCounter = 0;
  
  results.forEach( function(result) {
    let listDiv = document.createElement('div');
    let listInput = document.createElement('input');

    //If bold option is true in config
    if(config.bold) {
        //search with case insensitive
        let re = new RegExp( decodeURIComponent(term), "i");
        let boldname = "";
        let ename = result[config.fetchMap.name];
        let isub = ename.search(re);
        if ( isub > -1) {
            boldname = ename.substr(0,isub);
            boldname += "<strong>" + ename.substr(isub, term.length ) + "</strong>";
            boldname += ename.substr( (isub +term.length), (ename.length - (isub + term.length)));
            listDiv.innerHTML = boldname;
        } else {
            //Terms Substring isn't in the name
            //Cannot apply Bold style
            listDiv.innerHTML = result[config.fetchMap.name];
        }
    } else {
        listDiv.innerHTML = result[config.fetchMap.name];
    }
    

    listInput.id = 'autoBS-' + resultCounter;
    listInput.setAttribute('value', result[config.fetchMap.name]);
    listInput.setAttribute('data-id', result[config.fetchMap.id]);
    listInput.setAttribute('data-resultid', resultCounter);
    listInput.hidden = true;

    listDiv.append(listInput);
    console.log(listInput.getAttribute("data-id"));
    newDiv.append(listDiv);
    resultCounter++;

  });

    newDiv.addEventListener("click", function(e) {
      const autocompleteBSDiv = document.getElementById("autocompleteBS-list");
      let totalResults = parseInt(autocompleteBSDiv.dataset.results);
      let inputSource = autocompleteBSDiv.dataset.forinputbs;

      if ( totalResults === 0 ) {
        console.log('not a valid entry');
        document.getElementById(inputSource).focus();
        return;
      }
     
      let selectedElement = e.target;
      let selectedValue = selectedElement.querySelector('input');
      config.inputSource.value = selectedValue.value;
      if ('function' === typeof window.resultHandlerBS) {
        resultHandlerBS(config.name, results[selectedValue.dataset.resultid]);
      }
      clearListBS();
    });

  console.log('Add autocompleteBS-list Input Source: ' + sourceBS.id);
  sourceBS.parentElement.append(newDiv);

  }

function handleInputBS(e, config, obj) {
  console.log('handleInputBS');
  console.log(config);
  let inputValue = e.target.value;
  
  if ( inputValue.length < config.minLength ) {
    clearListBS();
    return;
  }
  if(config.ajax) {
          //xmlhttp
          let xmlhttp = new XMLHttpRequest();
          let method = 'POST';
          let url = config.ajaxurl;
          xmlhttp.open(method, url , true);
          xmlhttp.overrideMimeType('text/xml; charset=UTF-8');
          xmlhttp.onerror = function () {
              console.log("** An error occurred during the transaction");
          };
          xmlhttp.onreadystatechange = function() {
              if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                  results = JSON.parse(xmlhttp.response);
                  if ( !Array.isArray(results) ) {
                      console.log('Was expecting an array from the Fetch API - Setting to Empty');
                      results = [];
                  }
                  if ( results.length > config.maxResults ) results.length = config.maxResults;
                  addResultsBS(config, results, encodeURIComponent(inputValue));
              }
          }
          var formData = new FormData();
          formData.append("term", encodeURIComponent(inputValue) );
          xmlhttp.send(formData);
  } else {
      let fetchURL = config.fetchURL.replace('{term}', encodeURIComponent(inputValue));
      fetch(config.fetchURL + '?term='+ encodeURIComponent(inputValue) )
      fetch(fetchURL )
      .then(response => response.json())
      .then(response => {
          results = response;
          if ( !Array.isArray(results) ) {
              console.log('Was expecting an array from the Fetch API - Setting to Empty');
              results = [];
          }
          if ( results.length > config.maxResults ) results.length = config.maxResults;
          addResultsBS(config, results, encodeURIComponent(inputValue) );
      })
      .catch(error => console.error('Error:', error)); 
  }
}

function handleKeyDownBS(e, config) {

  const autocompleteBSDiv = document.getElementById("autocompleteBS-list");
  const sourceBS = config.inputSource;
  
  if ( ! autocompleteBSDiv ) return;

  let currentPosition = parseInt(autocompleteBSDiv.dataset.current);
  let totalResults = parseInt(autocompleteBSDiv.dataset.results);

  if ( autocompleteBSDiv.dataset.forinputbs == e.target.id ) {
    let keyPressed = parseInt(e.keyCode);
    let keyAction = '';

    if ( keyPressed === 40 || keyPressed === 9 ) keyAction = 'down'
    if ( keyPressed === 38 || (e.shiftKey && keyPressed == 9) ) keyAction = 'up'
    if ( keyPressed === 13 ) keyAction = 'enter';
    if ( keyPressed === 27 ) keyAction = 'escape';

    if (keyAction) console.log(keyAction);
  
    switch ( keyAction ) {
      case 'down':
        e.preventDefault();
        if ( totalResults === 0 ) return;
        if ( currentPosition === -1 ) {
          currentPosition = 1;
        } else {
          currentPosition++;
        }
        if ( currentPosition > totalResults ) currentPosition = 1;
        console.log('New Position: ' + currentPosition);
        autocompleteBSDiv.dataset.current = currentPosition;
        setPositionBS(config, currentPosition);
        break;
      case 'up':
        e.preventDefault();
        if ( totalResults === 0 ) return;
        if ( currentPosition === -1 ) {
          currentPosition = 1;
        } else {
          currentPosition--;
        }
        if ( currentPosition < 1 ) currentPosition = totalResults;
        console.log('New Position: ' + currentPosition);
        autocompleteBSDiv.dataset.current = currentPosition;
        setPositionBS(config, currentPosition);
        break;
      case 'enter':
        e.preventDefault();
        clearListBS();
        console.log(currentPosition);
        //config.targetID.value = results[currentPosition - 1][config.fetchMap.id];
        if ('function' === typeof window.resultHandlerBS) {
          resultHandlerBS(config.name, results[currentPosition - 1]);
        }
        break;
      case 'escape':
        e.preventDefault();
        config.inputSource.value = '';
        //config.targetID.value = '';
        clearListBS();
        break;
    }
  } else {
    console.log('No Key Action');
  }

}

function setPositionBS(config, positionBS) {
  console.log('setPositionBS');
  const autocompleteBSDiv = document.getElementById("autocompleteBS-list");
  if ( ! autocompleteBSDiv ) return;

  const listItems = Array.from(autocompleteBSDiv.children);

  listItems.forEach( function(listItem) {
    let selectedValue = listItem.querySelector('input');
     console.log(selectedValue.dataset.resultid);
    if ( parseInt(selectedValue.dataset.resultid) == positionBS - 1 ) {
      listItem.classList.add("autocompleteBS-active");
      config.inputSource.value = selectedValue.value;
    } else {
      listItem.classList.remove("autocompleteBS-active");
    }
  });
  
}

function clickCheckBS(e, config) {
/*    const autocompleteBSDiv = document.getElementById("autocompleteBS-list");
   console.log('clickCheckBS - Document Click Handler');

   if ( ! autocompleteBSDiv ) return;

   let sourceBS = autocompleteBSDiv.dataset.forinputbs;

   if ( sourceBS == e.target.id ) {
     console.log('Clicked in Target: ' + sourceBS);
   } else {
     console.log('Clicked outside target with an active list - Send back to input');
     document.getElementById(sourceBS).focus();
   } */

}


function autocompleteBS(configBS, obj) {

  // General Document Level Click Hander
  //document.addEventListener('click',  function(e) { clickCheckBS(e); } );

  configBS.forEach( function(config) {
    var updateValueDebounce = debounce(function(e) {
      handleInputBS(e, config, obj);
    }, config.debounceMS);
    config.inputSource.addEventListener('input', updateValueDebounce);
    config.inputSource.addEventListener('keydown',  function(e) { handleKeyDownBS(e,config); } );
  });
  

}


