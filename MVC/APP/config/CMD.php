<?php
	
	include "app/listeCommande.php";
	include "./modele/BDDJSON.php";
	include "app/modeleCommande.php";
	include "app/CLASS/Table.php";
	include "app/CLASS/Type.php";

	
	if(isset($argv[1])){
		traitement($argv[1]);
	}else{
		listCommande();
	}
?>