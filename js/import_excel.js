//////////////////////////////////////////////////////
//DOM FILES UPLOAD
//////////////////////////////////////////////////////
function validate_extension() {
    let fileName = document.getElementById("input_file").files[0].name;
    let fileExtension = fileName.split('.').pop();
    if ((fileExtension=='xlsx' )||(fileExtension=='xls')) {
        return true;
    } else {
        return false;
    }

}



document.getElementById("load_file").addEventListener("click", function() {
    if( document.getElementById("input_file").files.length == 0 ){
        alert("Aucun fichier selectionné !");
    } else {
        change_load("Chargement du fichier ...");
        if ( validate_extension() ) {
            let active_file = document.getElementById("input_file").files[0];
            let fd = new FormData();
                fd.append('file', active_file);
                $.ajax({
                url      : "php/upload_excel.php",
                type     : 'POST',
                data     : fd ,
                processData : false,
                contentType : false,
                async    : true,
                error    : function(request, error) { 
                    alert("Erreur : responseText: "+request.responseText);
                    change_load();
                },
                success  : function(data) {
                    change_load();
                    console.log(data);
                    if (data !== 'impossible de copier le fichier') {
                        console.log(data+" next ");
                        read_excel_file_by_line();
                    }
                }
                });
        } else {
            alert("Extension de fichier non valide !");
        }
    }
});

function read_excel_file_by_line() {
    change_load("Lecture des lignes du fichier...");
}