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
            b = document.createElement("div");
            
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
      console.log(currentFocus);
      console.log(x);
      console.log(x[currentFocus]);
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
      keys["id_projet"][0] = id;
      filters_active["id_projet"] = true;
      //session filter
      sessionStorage.setItem("filter_projet",id);
      sessionStorage.setItem("filter_name_projet",str_id);
      //filtre le json
      apply_filters();
      
      document.getElementById("input_projet").setAttribute("disabled",true);
      document.getElementById("del").classList.add("text-danger");
    } else {
      //apply_filters();
    }
    
  }

  document.getElementById("del").addEventListener("click", function (e) {
    document.getElementById("input_projet").value = '';
    //apply_filters();
    clearProjet();
  });



  function clearProjet() {
    document.getElementById("input_projet").removeAttribute("disabled");
    document.getElementById("del").classList.remove("text-danger");
    //Supprime le filtre projet
    keys["id_projet"][0] = 'null';
    //disable filter id with value selected
    filters_active["id_projet"] = false;
    //DESTROY Sessions
    sessionStorage.clear();
    apply_filters();
  }