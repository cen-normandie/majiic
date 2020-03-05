<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Consultation de la base MAJIC</title>
    </head>
    <?php session_start(); ?>
    <body>
        <section>
            <?php
                if (isset($_FILES['csvfile']) AND $_FILES['csvfile']['error'] == 0)
                {
                    $fileinfos = pathinfo($_FILES['csvfile']['name']);
                    $extension_upload = $fileinfos['extension'];
                    $extensions_auth = array('csv');
                    if (in_array($extension_upload, $extensions_auth))
                    {
                        // On lit le fichier
                        $csvfile = fopen($_FILES['csvfile']['tmp_name'], 'r+');
                        // On renseigne d'abord le header
                        $file['header'] = trim(fgets($csvfile));
                        // On agrège chacune des lignes
                        while (($line = fgets($csvfile)) !== false){
                            $file['content'][] = trim($line);
                        }
                        fclose($csvfile);
                        $_SESSION['csvfile'] = $file;
            ?>
            
            <!--<form method="post">-->
                <h1>Réglages de lecture du fichier</h1>
                <label for="sep">Séparateur de colonne : </label>
                <input type="text" name="sep" required id="sepInput"><br>
                <label for="colname">Indiquer la colonne : </label>
                <div id="divColName"></div>
                <input type="checkbox" name="case" id="case" /> <label for="case">Géométrie?</label><br>
                <input type="submit" value="OK" id="submitInput"/><br>
                <div id="response"></div>
            <!--</form>-->
            <script>
                var csvHeader = "<?php echo str_replace('"', '\\"', $_SESSION['csvfile']['header']); ?>";
                var csvContent = [<?php
                                        $comma = false;
                                        foreach ($_SESSION['csvfile']['content'] as $content)
                                        {
                                            if (!$comma) {$comma = true;}else{echo ', ';}
                                            echo '"' . str_replace('"', '\\"', $content) . '"';
                                        }
                                    ?>];
                function insertCol(item, index){
                    var div = document.getElementById("divColName");
                    var newInput = document.createElement('input');
                    newInput.type = "radio";
                    newInput.name = "colname";
                    newInput.value = "col" + index;
                    newInput.required = "required";
                    var newLabel = document.createElement('label');
                    newLabel.for = "col" + index;
                    newLabel.innerHTML = item;
                    var newBR = document.createElement('br');
                    div.appendChild(newInput);
                    div.appendChild(newLabel);
                    div.appendChild(newBR);
                }
                insertCol(csvHeader, 0);
                
                function deleteDiv(){
                    var div = document.getElementById("divColName");
                    div.innerHTML = "";
                }
                
                sepInput = document.getElementById("sepInput");
                sepInput.addEventListener('keyup', function(e) {
                    var sep = e.target.value;
                    deleteDiv();
                    if (sep == "")
                    {
                        insertCol(csvHeader, 0);
                    }else{
                        colNameValues = csvHeader.split(sep);
                        colNameValues.forEach(insertCol);
                    }
                });
                
                var submitInput = document.getElementById("submitInput");
                submitInput.addEventListener('click', function(){
                    sepInput = document.getElementById("sepInput");
                    launch = true;
                    if (sepInput.value == ""){
                        alert("Il me faut un séparateur de colonne, please.");
                        launch = false;
                    }
                    var colList = document.getElementsByName("colname");
                    var ncol = -1;
                    for (var i = 0; i < colList.length; i++)
                    {
                        if (colList[i].checked) ncol = i;
                    }
                    if (ncol == -1){
                        alert("Il me faut une colonne de référence cadastrale, please.");
                        launch = false;
                    }
                    if (launch)
                    {
                        var xhr = new XMLHttpRequest();
                        xhr.addEventListener('readystatechange', function() {
                            if (xhr.readyState === XMLHttpRequest.DONE && (xhr.status === 200)) {
                                var responseDiv = document.getElementById("response");
                                var textExport = xhr.responseText;
                                alert(textExport);
                                textExport = "cadastre.txt";
                                responseDiv.innerHTML = "<a href = \"" + textExport + "\">Télécharger le fichier ici</a>";
                            }else{
                                if (xhr.readyState === XMLHttpRequest.DONE)
                                {
                                    alert("Il y a eu une erreur AJAX : " + xhr.status + ".");
                                }
                            }
                        });
                        xhr.open('POST', 'http://localhost/Cadastre/ajax.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        Geo = "F";
                        if (document.getElementById("case").checked) Geo = "T";
                        xhr.send('sep=' + encodeURIComponent(sepInput.value) + '&col=' + encodeURIComponent(ncol) + '&geo=' + Geo);
                    }
                });
            </script>
            <?php
                    }else{
                        echo "Le format du fichier n'est pas bon.";
                    }
                }else{
                    echo "Il y a eu une erreur lors de l'envoi du fichier.";
                }
            ?>
            
        </section>
    </body>
</html>