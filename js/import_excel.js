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
document.getElementById("export_2021").addEventListener("click", function() {
    export_temps(2021);
});
document.getElementById("export_2022").addEventListener("click", function() {
    export_temps(2022);
});
document.getElementById("export_2023").addEventListener("click", function() {
    export_temps(2023);
});

document.getElementById("anal_2021").addEventListener("click", function() {
    export_model(2021);
});
document.getElementById("anal_2022").addEventListener("click", function() {
    export_model(2022);
});
document.getElementById("anal_2023").addEventListener("click", function() {
    export_model(2023);
});


function export_temps(year_replace) {
    $.ajax({
        url: "php/export_excel/export_temps_personne_year.php",
        type: "POST",
        dataType: "text",
        async    : true,
        data: {
            'nom_personne': document.getElementById("c_user").innerText,
            'year':year_replace
        },
        error    : function(request, error) { 
            alert("Erreur : responseText: "+request.responseText);
            },
        success  : function(data) {
            console.log(data);
            window.location = 'php/files/'+data;
            }
    });
};

function export_model(year_replace) {
    $.ajax({
        url: "php/export_excel/export_model_year.php",
        type: "POST",
        dataType: "text",
        async    : true,
        data: {
            'nom_personne': document.getElementById("c_user").innerText,
            'year':year_replace
        },
        error    : function(request, error) { 
            alert("Erreur : responseText: "+request.responseText);
            },
        success  : function(data) {
            console.log(data);
            window.location = 'php/files/'+data;
            }
    });
};

document.getElementById("load_file").addEventListener("click", function() {
    if( document.getElementById("input_file").files.length == 0 ){
        alert("Aucun fichier selectionné !");
    } else {
        change_load("Chargement du fichier ...");
        if ( validate_extension() ) {
            let active_file = document.getElementById("input_file").files[0];
            let fd = new FormData();
                fd.append('file', active_file);
                fd.append('nom_personne', document.getElementById("c_user").innerText );
                fd.append('year', document.getElementById("year_replace").value);
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
                    if (data !== 'impossible de copier le fichier') {
                        read_excel_file_by_line(data );
                    }
                }
                });
        } else {
            alert("Extension de fichier non valide !");
        }
    }
});

function read_excel_file_by_line(data) {
    change_load("Lecture des lignes du fichier...");
    let row_p1 = data.split('|')[0];
    let file_name = data.split('|')[1].trim();
    console.log(row_p1);
    console.log(file_name);
    for (let row = 2; row < parseInt(row_p1)+1; row++) {
        let fd_ = new FormData();
            fd_.append('file_name', file_name);
            fd_.append('row',row);
        $.ajax({
            url      : "php/read_lines_excel.php",
            type     : 'POST',
            data     : fd_ ,
            processData : false,
            contentType : false,
            async    : true,
            error    : function(request, error) { 
                alert("Erreur : responseText: "+request.responseText);
                if(row = parseInt(row_p1)){
                    change_load();
                }
            },
            success  : function(data) {
                if(data == 1) {
                    document.getElementById("lines_import").innerHTML += `<div class="p-2 border text-success" style="font-size:10px;">Ligne : ${row}</div>`;
                } else {
                    document.getElementById("lines_import").innerHTML += `<div id="a_${row}" class="p-2 border text-danger " style="font-size:10px;"><b>Ligne : ${row}</b></div>`;
                    //write html errors
                    //document.getElementById("lines_import_error").innerHTML += data;

                }
                if(row = parseInt(row_p1)){
                    change_load();
                }

            }
        });
      }
     
}