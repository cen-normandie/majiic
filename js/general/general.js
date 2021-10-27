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