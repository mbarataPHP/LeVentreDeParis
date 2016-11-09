<?php
/**
 * Controller
 * Definition de la classe Controller utilisée pour le Blog
 * 
 * @package    controller
 * @subpackage classes
 * @author     Barata Mathieu
 * @copyright lesHalles 25/09/2016
 */
Class Controller{
	/**
    * les caracteristique 
    * @var array $SERVER
    */
	public static $SERVER = array();
	/**
    * tableu de traductio
    * @var array $traduction
    */
	public static $traduction = array();
	/************************
	*LES VUE
	************************/
	/**
	* appelle la vue avec sa langue
	* @author Mathieu Barata <mathieu@barata.fr>
	*/
	public static function callVue($nomFichierHtmlOrPhp, $tabVariable = array()){
		
		//redirection pagehtml ou page php (View)
		$nomFichierHtmlOrPhp = "APP/view/".$nomFichierHtmlOrPhp;
		//capture les langues
		$lesMots = Controller::$traduction;

		extract($tabVariable); //covertie array en variable

		include $nomFichierHtmlOrPhp; //appel la vue
		
	}
	
	/**
	* affiche le lien
	* @param $nomRoute String le nom de la ROUTE
	* @global $ROUTE le tableau de route
	* @return String le lien de la route complet
	* @author barata mathieu <mathieu@barata.fr>
	*/
	public static function lien($nomRoute = "", $tabVar = array()){
		global $ROUTE; //apple
		$url = "";
		if($nomRoute=="" and isset($ROUTE[0])){
			$url = $ROUTE[0]["url"];
		}
		$i=0;
		while($i<count($ROUTE) and $nomRoute!=$ROUTE[$i]["nomRoute"]){
			$i++;
		}
		if($i<count($ROUTE)){
			$url = $ROUTE[$i]["url"];
			foreach($tabVar as $key=>$var){
				$url = str_replace("{".$key."}", $var, $url);
			}
		}
		return Controller::$SERVER["LIEN_DEFAUT"].$url;
	}
	/**
	* lien javascript, css, image
	* @author barata mathieu <mathieu@barata.fr>
     * @see CSS, JaVascript, image
     *
	* @param le lien du fichier
	* @return string lien du fichier vers le repertoire web
	*/
	public static function lien_WEB($lien){
		return Controller::$SERVER["LIEN_DEFAUT"]."WEB/".$lien;
	}


	/**
	* Cette methode permet de rediriger le route choisie par l'utilisateur vers son controller et sa methode
	* @author barata mathieu <mathieu@barata.fr>
	* @global $ROUTE le tableau de route
	*/
	public static function redirectionRoute(){
		global $ROUTE; //appel
	
		foreach($ROUTE AS $uneRoute){
			$tableauRouteConfig = Controller::remplaceRouteVariable($uneRoute);
			
			if($tableauRouteConfig["routeValide"]){

                if(Role::verifValidation($tableauRouteConfig["role"])){
                    Controller::$SERVER["laRoute"] = $tableauRouteConfig; //sauvegarde la route
                    call_user_func_array (array($uneRoute["controller"], $uneRoute["methode"]), $tableauRouteConfig["variable"] ); //redirection vers la controller et la methode
                }else{
                    call_user_func(array("ControllerAction", "erreurRoleAction")); //le role ne correspond pas à l'utilisateur
                }
				exit;
			}
		}
		$erreur = Controller::getRequest("GET", "erreur");
		if($erreur=="403"){
			echo ";)";
		}else{
			call_user_func(array("ControllerAction", "erreur404Action")); //erreur 404 la route url ne correspond pas à la route demander dans le tableau
		}
		
	}
	/**
	* cette methode permet de traiter la route en question 
	* @param array() $uneRoute recupre une route dans le tableau Route
	* @author barata mathieu <mathieu@barata.fr>
	* @access public
	* @return capture la route avec le boolean si elle est valide
	*/
	public static function remplaceRouteVariable($uneRoute){
		$tableau = array();
		$tableau["route"] = $uneRoute;
		$tableau["routeVar"] = array(); //tableau de variable de route
		$tableau["variable"] = array(); //tableau de variable de la methode cibler 
		$tableau["routeValide"] = false; //la route n'a pas était trouvée
        if(!isset($uneRoute["role"])){
            $tableau["role"] = "anonyme";
        }else{
            $tableau["role"] = $uneRoute["role"];
        }

		$patterns = '(\{[0-9A-Za-z.]{1,}\})';
		preg_match_all($patterns, $uneRoute["url"], $matches); //explode {} en tableau 
		foreach($matches[0] as $uneVariable){
			array_push($tableau["routeVar"], $uneVariable);
		}
		$tableau["nouvelleRoute"] = preg_replace($patterns, '([0-9A-Za-z.]{1,})', $uneRoute["url"]);
		
		$tabUrl = explode("/", Controller::$SERVER["url"]); //explode l'URL
		$tabRoute = explode("/", $uneRoute["url"]); //explode la route
		if(count($tabUrl)==count($tabRoute)){ //si le count de l'url est pareille avec le count de la route il faut la traite avec l'expression regulier
			// la condition if posséde deu
			if(
				(preg_match_all("#".$tableau["nouvelleRoute"]."#", Controller::$SERVER["url"]) and $tableau["nouvelleRoute"]!="" )
				OR
				($tableau["nouvelleRoute"]=="" and $tableau["nouvelleRoute"]==Controller::$SERVER["url"]) //c'est ou cas ou l'url est vide, on n'utilise pas l'expression regulier

			){

				$tableau["routeValide"] = true;
				$y=0;
				foreach($tabRoute as $separer){

					$i=0;
					while($i<count($tableau["routeVar"]) and $separer!=$tableau["routeVar"][$i]){
						$i++;
					}
					if($i<count($tableau["routeVar"])){

						//SUPPRESSION DES "{}"
						$chaine = $separer;
						$chaine = str_replace("{", "", $chaine);
						$chaine = str_replace("}", "", $chaine);


						$tableau["variable"][$chaine] = $tabUrl[$y];
					}
					$y++;
				}
			}
		}

		return $tableau;
	}

	/**
	* Recupere les informations Request
     * @author barata mathieu <mathieu@barata.fr>
	* @param string $type GET ou POST
	* @param string name de la request
	* @param string defaut 
	* @return string|false|array $defaut
	*/
	public static function getRequest($type, $name, $defaut = false){
		if($type=="POST" && isset($_POST[$name])){
			$defaut = $_POST[$name];
		}elseif($type=="GET" && isset($_GET[$name])){
			$defaut = $_GET[$name];
		}

		return $defaut;
	}
	/**
	* verifie le format de la date si elle est valide
	* @param $date string la date sous format anglais par defaut;
	* @author barata mathieu <mathieu@barata.fr>
	* @param $format le format de la date
	* @return boolean 
	*/
	public static function validateDate($date, $format = 'Y-m-d H:i:s'){
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}

    /**
     * @author barata mathieu <mathieu@barata.fr>
     * Retourne l'adresse ip de l'utilisateur
     * @return string|false $ipaddress
     */
    public static function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = false;
        return $ipaddress;
    }
}

?>