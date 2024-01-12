function autocompleteArray(inp, arr) {
    // console.log(arr);
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
          let re = new RegExp( val, "i");
          let boldname = "";
          let ename = arr[i];
          let isub = ename.search(re);

          if ( isub > -1) {
          //if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("li");
            
            /*make the matching letters bold:*/
            boldname = '<span>'+ename.substr(0,isub);
            boldname += "<strong>" + ename.substr(isub, val.length ) + "</strong>";
            boldname += ename.substr( (isub +val.length), (ename.length - (isub + val.length)))+'</span>';
            //b.innerHTML = ;
            /*insert a input field that will hold the current array item's value:*/
            b.classList.add("list-group-item");
            b.classList.add("justify-content-start");
            b.classList.add("w-100");
            b.classList.add("autocompleteItem");
            b.setAttribute("value", ename);
            b.innerHTML += `${boldname}`;
            /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = ename;
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
                autoClickEnd(inp.value);
            });
            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
  } 

  function autoClickEnd (str_id) {
    if(str_id !== '') {
      let id = str_id.split(' - ')[0];
      //enable filter id with value selected
      keys["id_site"][0] = id;
      filters_active["id_site"] = true;
      //filtre le json
      apply_filters();
      
      addDocRef();
      document.getElementById("input_site").setAttribute("disabled",true);
      document.getElementById("del").classList.add("text-danger");
    } else {
      //apply_filters();
    }
    
  }

  document.getElementById("del").addEventListener("click", function (e) {
    document.getElementById("input_site").value = '';
    //apply_filters();
    clearDocRef();
  });


  function addDocRef() {
    let array_docs = [];
    for (const parcelle in parcelles_f) {
      if ( !array_docs.includes(parcelles_f[parcelle].document_reference) ) {
        array_docs.push(parcelles_f[parcelle].document_reference);
      }
    }
    let content ="";
    let dir_from_localhost = document.getElementById("dir_from_localhost").innerText;
    for (const element of array_docs) {
      //content +=`<div class="mx-1"><a href="${dir_from_localhost}/php/docs/foncier/${element}.pdf" target="_blank" class="link-secondary fs-6"><div>${element}<i class=" mx-1 fas fa-file-pdf text-danger"></i></div></a></div>`;
      content +=`<div class="mx-1"><a href="./php/docs/foncier/${element}.pdf" target="_blank" class="link-secondary fs-6"><div>${element}<i class=" mx-1 fas fa-file-pdf text-danger"></i></div></a></div>`;
    }
    document.getElementById("doc_refs").innerHTML=content; 
    document.getElementById("list_docs").classList.remove("d-none");
  }
  function clearDocRef() {
    document.getElementById("doc_refs").innerHTML='';
    document.getElementById("input_site").removeAttribute("disabled");
    document.getElementById("del").classList.remove("text-danger");
    document.getElementById("list_docs").classList.add("d-none");
    //Supprime le filtre site
    keys["id_site"][0] = 'null';
    //disable filter id with value selected
    filters_active["id_site"] = false;
    //apply_filters();
  }


