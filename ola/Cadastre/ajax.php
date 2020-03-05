<?php
	session_start();
	try
	{
		// On se connecte à PostGre
		$bdd = pg_connect('host=192.168.0.213 port=5432 dbname=majic user=administrateur password=CSNHN1993') or die('failed');
	}
	catch(Exception $e)
	{
		// En cas d'erreur, on affiche un message et on arrête tout
		die('Erreur : '.$e->getMessage());
	}
	
	$header = explode($_POST['sep'], $_SESSION['csvfile']['header']);
	$Phrase = "";
	$first = true;
	foreach ($header as $colName)
	{
		if ($first){$first = false;}else{$Phrase = $Phrase . $_POST['sep'];}
		$Phrase = $Phrase . $colName;
	}
	$Phrase = $Phrase . $_POST['sep'] . "Nom" . $_POST['sep'] . "Prenom" . $_POST['sep'] . "Date naissance" . $_POST['sep'] . "Adresse" . $_POST['sep'] . "Denomination" . $_POST['sep'] . "Type proprietaire" . $_POST['sep'];
	//$_SESSION['csvfile'] = $file;
	foreach ($_SESSION['csvfile']['content'] as $row)
	{
		$elements[0] = "";
		$compteur = 0;
		$isStart = false;
		$isEsc = false;
		for ($i = 0; $i < strlen($row); $i++){
			$car = $row[$i];
			if ($isEsc){
				$elements[$compteur] = $elements[$compteur] . $car;
				$isEsc = false;
			}else{
				if ($isStart)
				{
					if ($car == '"'){
						$isStart = false;
					}else{
						$elements[$compteur] = $elements[$compteur] . $car;
					}
				}else{
					if ($car == "\\"){
						$isEsc = true;
					}else{
						if ($car == '"'){
							$isStart = true;
						}else{
							if ($car == $_POST['sep']){
								$compteur++;
							}else{
								$elements[$compteur] = $elements[$compteur] . $car;
							}	
						}
					}
				}
			}
		}
		//$elements =  explode($_POST['sep'], $row);
		$ref = $elements[$_POST['col']];
		$Phrase = $Phrase . "\n" . $row;
		if ($_POST['geo'] == "T"){
			$result = pg_query($bdd, "SELECT * FROM table_parcellaire_d14 WHERE ST_Intersects('SRID=2154;" . $ref . "'::geometry, localisation)");
			$resp = false;
			if ($result)
			{
				foreach (pg_fetch_all($result) as $rowSQL)
				if ($resp){
					$Phrase = $Phrase . "\n" . $row . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
				}else{
					$Phrase = $Phrase . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
					$resp = true;
				}
			}
			$result = pg_query($bdd, "SELECT * FROM table_parcellaire_d27 WHERE ST_Intersects('SRID=2154;" . $ref . "'::geometry, localisation)");
			if ($result)
			{
				foreach (pg_fetch_all($result) as $rowSQL)
				if ($resp){
					$Phrase = $Phrase . "\n" . $row . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
				}else{
					$Phrase = $Phrase . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
					$resp = true;
				}
			}
			$result = pg_query($bdd, "SELECT * FROM table_parcellaire_d50 WHERE ST_Intersects('SRID=2154;" . $ref . "'::geometry, localisation)");
			if ($result)
			{
				foreach (pg_fetch_all($result) as $rowSQL)
				if ($resp){
					$Phrase = $Phrase . "\n" . $row . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
				}else{
					$Phrase = $Phrase . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
					$resp = true;
				}
			}
			$result = pg_query($bdd, "SELECT * FROM table_parcellaire_d61 WHERE ST_Intersects('SRID=2154;" . $ref . "'::geometry, localisation)");
			if ($result)
			{
				foreach (pg_fetch_all($result) as $rowSQL)
				if ($resp){
					$Phrase = $Phrase . "\n" . $row . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
				}else{
					$Phrase = $Phrase . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
					$resp = true;
				}
			}
			$result = pg_query($bdd, "SELECT * FROM table_parcellaire_d76 WHERE ST_Intersects('SRID=2154;" . $ref . "'::geometry, localisation)");
			if ($result)
			{
				foreach (pg_fetch_all($result) as $rowSQL)
				if ($resp){
					$Phrase = $Phrase . "\n" . $row . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
				}else{
					$Phrase = $Phrase . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
						$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
						'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
					$resp = true;
				}
			}
		}else{
			$dep = substr($ref, 0, 2);
			$result = pg_query($bdd, "SELECT * FROM table_parcellaire_d" . $dep . " WHERE id_par = '" . $ref . "'");
			if ($result)
			{
				foreach (pg_fetch_all($result) as $rowSQL)
				$Phrase = $Phrase . $_POST['sep'] . '"' . $rowSQL['nom_usage'] . '"' . $_POST['sep'] . '"' . $rowSQL['prenom_usage'] . '"' . $_POST['sep'] . '"' .
					$rowSQL['date_naissance'] . '"' . $_POST['sep'] . '"' . $rowSQL['adresse'] . '"' . $_POST['sep'] . '"' . $rowSQL['denomination'] .
					'"' . $_POST['sep'] . '"' . $rowSQL['type_proprio'] . '"';
			}
		}
	}
	if (file_exists('cadastre.txt'))
	{
		unlink('cadastre.txt');
	}
	$file = fopen('cadastre.txt', 'a');
	fputs($file, $Phrase);
	fclose($file);
	echo 'cadastre.txt';
?>