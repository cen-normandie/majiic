<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Consultation de la base MAJIC</title>
    </head>
	<?php session_start(); ?>
    <body>
		<section>
			<form method="post" action="sendfile.php" enctype="multipart/form-data">
				<h1>Consulter les relevés de propriétés depuis une liste .csv</h1>
				<label for="csvfile">Merci de charger le .csv contenant les données à consulter : </label>
				<input type="file" name="csvfile" required>
				<input type="submit" value="Envoyer"/>
			</form>
		</section>
    </body>
</html>