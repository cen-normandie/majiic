function change_load (txt_what) {
    //$("#loader").toggleClass("visible_s");
    //$("#loader").html('<img style="" src="./img/spin.png" class="rotate_ mx-4"> '+txt_what);
    if (txt_what != undefined) {
        $("#loader").removeClass("visible_s");
        $("#loader").html('<img style="" src="./img/spin.png" class="rotate_ mx-4"> '+txt_what);
    } else {
        $("#loader").addClass("visible_s");
        $("#loader").html('');
    }

};

function hexToRgb(hex) {
  // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
  var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
  hex = hex.replace(shorthandRegex, function(m, r, g, b) {
    return r + r + g + g + b + b;
  });

  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? {
    r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16),
    z: parseInt(result[1], 16)+','+parseInt(result[2], 16)+','+parseInt(result[3], 16)
  } : null;
}

//get uuid
let new_uuid='';
function get_uuid() {
    $.ajax({
        url: 'php/ajax/uuid/get_uuid.js.php',
        data     : {},
        method   : "POST",
        dataType : "text",
        error    : function(request, error) { alert("Erreur : responseText: "+request.responseText);},
        async    : false,
        success: function (response) {
            new_uuid=response;
            }
        });
}

//formatdate
function padTo2Digits(num) {
  return num.toString().padStart(2, '0');
}

function formatDate(date) {
  return (
    [
      date.getFullYear(),
      padTo2Digits(date.getMonth() + 1),
      padTo2Digits(date.getDate()),
    ].join('-') +
    ' ' +
    [
    //carefull
      padTo2Digits(date.getHours()-2),
      padTo2Digits(date.getMinutes()),
      padTo2Digits(date.getSeconds()),
    ].join(':')
  );
}