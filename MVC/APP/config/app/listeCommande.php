<?php


function output($chaine){
	$chaine .= "\n";
	print($chaine);
}
function listCommande(){
	print(" BDD:showTables \\\cette commande permet de voir tout les tables.\n");
	print(" BDD:newTable \\\cette commande permet de creer une table.\n");
	print(" BDD:{nomTable}:info \\\cette commande permet de voir les configurations des champs sur la table.\n");
	print(" BDD:{nomTable}:all \\\cette commande permet de voir les enregistrements sur la table.\n");
}
function notFound(){
	print(" La commande n'existe pas.\n");
	print("==================>.\n\n");
	listCommande();	
}

function ecrire($nom){
	
    if (PHP_OS == 'WINNT') { //windows
        echo $nom;
        $line = stream_get_line(STDIN, 1024, PHP_EOL);
    } else {
        $line = readline($nom);
    }
 	return $line;
}
function traitement($ligneCommande){
	$tab = explode(":", $ligneCommande);
	switch($tab[0]){
		case "BDD":
			traitement_BDD($tab);
		break;
		default:
			notFound();
	}
}


function creerTable(){
	$nomTable = "";
	while($nomTable==""){
		$nomTable = ecrire("nom de la table : ");
	}

	return $nomTable;
}

function traitement_BDD($tab){
	BDDJSON::reloadRepertoire();
	if(isset($tab[1])){
		switch($tab[1]){
			case "showTables":
				$tableau = BDDJSON::getAllTables();
				print("liste de table (".count($tableau).")\n");
				print("<==================\n");
				foreach($tableau as $uneTable){
					print($uneTable."\n");
				}
				
				print("==================>\n\n");
			case "newTable":
				$uneTable = new Table();
				$nomTable = creerTable();
				$uneTable->_nom = $nomTable;
				$uneTable = lesChamps($uneTable);

				createFichierJson($uneTable);
			break;
			break;
			default:
				$tableau = BDDJSON::getAllTables();
				if (in_array($tab[1], $tableau)) { //BDD:{table} le nom de table est present dans la BDD
					if(isset($tab[2])){
						switch($tab[2]){
							case "info":
								$lesCles = BDDJSON::getKeyTable($tab[1]);// les clés
								
								foreach($lesCles as $key=>$val){
									print($key);
									print("\t[");
									foreach($val as $sousKey=>$sousVal){
										print(" ".$sousKey.":".$sousVal.",");
									}
									print("]\n");
								}
							break;
							case "all":
								$lesValeurs = BDDJSON::lireAll($tab[1]); //les valeurs
								
								foreach($lesValeurs as $val){
									print("{ ");
									foreach($val as $key=>$valeur){
										if (is_a($valeur, 'DateTime')) {
											$valeur = $valeur->format("d-m-Y H:i:s");
										}
										if(is_int($valeur)){
											$valeur = strval($valeur);
										}
										print(" ".$key.":".$valeur.",");
									}
									print(" }\n");
								}
							
							break;
							default:
								notFound();
						}
					}else{
						notFound();
					}
				}else{
					notFound();
				}
				
		}
	}else{
		notFound();
	}
}



?>