<?php
	function nomChampCreate(){

		
		$input = ecrire("taper le du nom champ: ");
		if($input==""){
			$input = false;
		}
		return $input;
	}
	function listeCreateType(){
		output("[string, int, datetime, bool]");
		$input = ecrire("taper votre type de champ[string]:");

		switch($input){
			case "":
				$type =  __String();
			break;
			case "string":
				$type = __String();
			break;
			case "int":
				$type = __Int();
			break;
			case "bool":
				$type = __Bool();
			break;
			case "datetime":
				$type = _date();
			break;
			default:
				$type = listeCreateType();
		}
		
		return $type;
	}

	function lesChamps(Table $uneTable){
		$tab = array();
		$nomChamp = true;
		while($nomChamp!=false){
			$nomChamp = nomChampCreate();
			if($nomChamp!=false){
				$type = listeCreateType();
				$unType = _setType($nomChamp, $type);
				$uneTable->addType($unType);
			}
		}
		return $uneTable;
	}
	function _setType($nomChamp, $type){
		$unType = new Type();
		$type["nom"] = $nomChamp;
		
		switch($type["type"]){
			case "string":
				$unType->_SET_STRING($type);
			break;
			case "int":
				$unType->_SET_INT($type);
			break;
			case "bool":
				$unType->_SET_BOOL($type);
			break;
			case "datetime":
				$unType->_SET_DATETIME($type);
			break;
		}
		
		return $unType;
	}
	function __String(){
		$tab = array("type"=>"string");
		
		$tab["nbr"] = nbrCaractere();

		$tab["null"] = champ_null();

		return $tab;
	}
	function __Int(){
		$tab = array("type"=>"int");
		$tab["null"] = champ_null();
		return $tab;
	}
	function __Bool(){
		$tab = array("type"=>"bool");
		$tab["null"] = champ_null();
		return $tab;
	}
	function _date(){
		//datetime
		$tab = array("type"=>"datetime");
		$tab["null"] = champ_null();
		return $tab;
	}


	function nbrCaractere(){
		$nbr = ecrire("nombre  caractere[500]:");
		if($nbr == ""){
			$nbr = "500";
		}
		if(!preg_match("#^[0-9]{1,}$#", $nbr)){
			$nbr = nbrCaractere();
		}

		return intval($nbr);
	}

	function champ_null(){
		$_null = ecrire("champ null[false]:");
		if($_null == ""){
			$_null = "false";
		}

		if($_null!="false" and $_null!="true"){
			$return = champ_null();
		}else{
			if($_null=="true"){
				$return = true;
			}else{
				$return = false;
			}
		}
		return $return;
	}


	function createFichierJson(\Table $uneTable){
		$filename = 'modele/fichier/'.$uneTable->name_file();
		if (!file_exists($filename)) {
		   
			
			// Ajoute le tableau de json
			$current = $uneTable->createJson();
			// Écrit le résultat dans le fichier
			file_put_contents($filename, $current);
		} else {
		    output("Le fichier $filename existe déjà.");
		}
	}
?>

<?php

?>