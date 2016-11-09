<?php

	//id, email, pseudo, datetime
	class BDDJSON{

		public static $NOM_DOCUMENT = ".JSON";
		public static $EMPLACEMENT_FICHIERS = "modele/fichier/";
		public static $EMPLACEMENT_FICHIERS_BY_CMD = "modele/fichier";
		public static $fichier = null;
		
		/**
		*
		* @see SELECT
		* @author barata mathieu <mathieu@barata.fr>
		* @param string $table le nom de la table
		*/
		public static function lireAll($table){
			
			BDDJSON::ouvreFichier($table); //ouvre le fichier
			//ecrire code
			$chaine = ""; 
		
			//lit le fichier cibler puis initilise dans la variable $chaine;
			while($ligne = fgets(BDDJSON::$fichier)){
				$chaine .= $ligne;
			}
			$jsonArray = json_decode($chaine, true);

			$tab = array();
			
			foreach($jsonArray["lesValeurs"] as $valeur){
				$i=0;
				$sousTab = array();
				foreach($jsonArray["key"] as $keyKey => $valeurKey){
					if(!isset($valeur[$i])){ //c'est ou cas ou c'est un nouveaux champ et les valeur n'existe pas encore sur le champ
						$valeur[$i] = null;
					}
					$sousTab[$keyKey] = BDDJSON::getInitiliseType($valeurKey["type"], $valeur[$i]);
					$i++;
					
				}
				array_push($tab, $sousTab);
			}
			BDDJSON::fermerFichier(); //ferme le fichier

			return $tab; //retourne la table de fichier
		}

		/**
		* Insere des valeurs dans le fichier
		* @param $table string nom du fichier sans l'extension ex "fichier.JSON le paramétre va afficher 'fichier' "
		* @param $tableau array le tableu contient les informations d'enregistrement
		*/
		public static function inserer($table , $tableau){
			BDDJSON::ouvreFichier($table);
			$i=0;
			$lesCles = BDDJSON::getKeyTable($table);
			$bool = true;
			$tableau["id"] = -1;
			//supprimme les valeurs qui ne sont pas dans le fichier JSON de la collections "key"
			foreach($tableau as $key=>$val){
				if(!array_key_exists ($key , $lesCles )){
					$tableau[$key] = null;
					unset($tableau[$key]);
				}
			}
			$tableauInfo = array();
			$tableauInfo["bool"] = true;
			//foreach met la valeur array en json 
			foreach($lesCles as $key=>$uneCle){
				if($tableauInfo["bool"]){
					if(!isset($tableau[$key])){
						$tableau[$key] = null;
					}
					switch ($uneCle["type"]) {
						case 'string':

							$tab = BDDJSON::insert_String($uneCle, $tableau[$key]);
							$tableauInfo["bool"] = $tab["bool"];
						break;
						case 'int':
							$tab = BDDJSON::insert_Integer($uneCle, $tableau[$key]);
							$tableauInfo["bool"] = $tab["bool"];
							
						break;
						case 'datetime':
							$tab = BDDJSON::insert_date($uneCle, $tableau[$key]);
							$tableauInfo["bool"] = $tab["bool"];
							$tableau[$key] = $tab["date"];

						break;
						
					}
				}
			}

			if($tableauInfo["bool"]){ //si le information sont correcte
				$tableau["id"] = BDDJSON::getIdMax($table);
				BDDJSON::ecrire($table, $tableau);
			}
			
			BDDJSON::fermerFichier(); //ferme le fichier

			 return $tableauInfo;
		}

		/**
		* Affiche les noms des tables
		* @return string tous les nom des table
		* @author barata mathieu <mathieu@barata.fr>
		*/
		public static function getAllTables(){
			$dir    = BDDJSON::$EMPLACEMENT_FICHIERS_BY_CMD;
			$lesFichiers = scandir($dir);
			$tabReturn = array();
			foreach($lesFichiers as $unFichier){
				$tab = explode(".", $unFichier);
				$extension = ".".$tab[count($tab)-1];
				if($extension==BDDJSON::$NOM_DOCUMENT){
					$nomComplet = "";
					for($i=0;$i<count($tab)-1;$i++){
						if($i==0){
							$nomComplet .= $tab[$i];
						}else{
							$nomComplet .= ".".$tab[$i];
						}
					}

					array_push($tabReturn, $nomComplet);
				}
			}
			return $tabReturn;
		}
		/**
		* @param string nom de table
		* @return string les type de chmap de la table
		* @author barata mathieu <mathieu@barata.fr>
		*/
		public static function getKeyTable($table){
			BDDJSON::ouvreFichier($table);
			//ecrire code
			$chaine = ""; 
			//lit le fichier cibler puis initilise dans la variable $chaine;
			while($ligne = fgets(BDDJSON::$fichier)){
				$chaine .= $ligne;
			}
			$jsonArray = json_decode($chaine, true);
			BDDJSON::fermerFichier();

			return $jsonArray["key"];
		}
		public static function getValeurTable($table){
			BDDJSON::ouvreFichier($table);
			//ecrire code
			$chaine = ""; 
			//lit le fichier cibler puis initilise dans la variable $chaine;
			while($ligne = fgets(BDDJSON::$fichier)){
				$chaine .= $ligne;
			}
			$jsonArray = json_decode($chaine, true);
			BDDJSON::fermerFichier();

			return $jsonArray["lesValeurs"];
		}
		/**
			AUTRE FONCTIONS
		**/



		/*
		* MES TYPES
		*/
		/**
		*
		*
		*/
		public static function insert_String($lesTypes, $valeur){
			
			$tab["bool"] = true;
			if(!isset($lesTypes["null"])){
				$lesTypes["null"] = false;
			}

			if(is_int($valeur)){
				$valeur = strval($valeur);
			}
			if($lesTypes["null"]){
				$valeur = "";
			}
			if(is_null($valeur)){
				$tab["bool"] = false; //erreur
			}

			if($tab["bool"] and is_string($valeur)){
				if(strlen ( $valeur ) <= intval($lesTypes["nbr"])){

				}
			}else{
				$tab["bool"] = false; //erreur
			}
			return $tab;
		}

		public static function insert_Integer($lesTypes, $valeur){
			
			$tab["bool"] = true;
			if(!isset($lesTypes["null"])){
				$lesTypes["null"] = false;
			}

			
			
			if(is_null($valeur)){
				$tab["bool"] = false; //erreur
			}

			if($tab["bool"] and is_int($valeur)){
				
			}else{
				$tab["bool"] = false; //erreur
			}
			return $tab;
		}

		public static function insert_date($lesTypes, $valeur){
			
			$tab["bool"] = true;
			if (!is_a($valeur, 'DateTime')) {
				$valeur = new DateTime();
			}
			$tab["date"] = $valeur->format("Y-m-d H:i:s");
			return $tab;
		}


		/*
		* MES AUTRE METHODE
		*/
		public static function getIdMax($table){
			$lesValeurs = BDDJSON::lireAll($table);
			$id = 0;
			foreach($lesValeurs as $val){
				if($val["id"]>$id){
					$id = $val["id"];
				}
			}

			return ($id+1);
		}

		/**
		* convertie les formats string de json en format plus adapté pour PHP 
		*
		*/
		public static function getInitiliseType($type, $valeur){
			$valeurKey = null;
			switch($type){
				case "string":
					$valeurKey = strval($valeur);
				break;
				case "int":
					$valeurKey = intval($valeur);
				break;
				case "datetime":
					$valeurKey = new DateTime($valeur);
				break;

			}

			return $valeurKey;
		}

		public static function ecrire($table, $uneValeurTab){
			$lesCles = BDDJSON::getKeyTable($table);
			$tab = array();
			foreach($lesCles as $key=>$val){
				array_push($tab, $uneValeurTab[$key]);
			}
			
			$lesValeurs = BDDJSON::getValeurTable($table);
			array_push($lesValeurs, $tab);
			$tableau = array();
			$tableau["key"] = $lesCles;
			$tableau["lesValeurs"] = $lesValeurs;
			$chaineJsonString = json_encode($tableau);
			//ecrire dans un fichier
			BDDJSON::ouvreFichier($table);
			fseek(BDDJSON::$fichier, 0); // On remet le curseur au début du fichier
			fputs(BDDJSON::$fichier, $chaineJsonString); 
			BDDJSON::fermerFichier();
		}
		/**
		*
		* @author barata@mathieu <mathieu@barata.fr>
		*/
		public  static function ouvreFichier($table){
			BDDJSON::$fichier = fopen(BDDJSON::$EMPLACEMENT_FICHIERS.$table.BDDJSON::$NOM_DOCUMENT, 'r+'); //Ouvre le fichier en lecture et écriture
		}

		public static function fermerFichier(){
			if(BDDJSON::$fichier!=null){
				fclose(BDDJSON::$fichier);
				BDDJSON::$fichier = null;
			}
		}


		//CMD

		public static function reloadRepertoire(){
			BDDJSON::$EMPLACEMENT_FICHIERS = BDDJSON::$EMPLACEMENT_FICHIERS_BY_CMD."/";
			
		}

	}

/*$tabInserer = array("email"=>"tot@toto.fr",  "comment"=>'ff"dfff"');

BDDJSON::inserer("livre", $tabInserer);*/
?>