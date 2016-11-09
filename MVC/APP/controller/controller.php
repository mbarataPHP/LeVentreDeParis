<?php
session_start();//demarre la session
////////////////////////////
// CONTROLLER PRINCIPALE //
///////////////////////////



//MES CONFIGS
	include "APP/config/ROUTE.php";//MA ROUTE
	include "APP/config/langue.php";//les langue francais anglais et farnçais
	include "APP/config/CONFIG.php";//Les configuration de l'admin
//MES MODELES
	include "APP/modele/BDDJSON.php";//MA BDD

	//permet de creer le lien href="/.."></a> 
	function url(){
		$remplace = str_replace("\\", "/", __DIR__);
		$url = str_replace($_SERVER['DOCUMENT_ROOT'], "", $remplace);
		$url = str_replace("controller", "", $url);
		$url = str_replace("APP/", "", $url);
		return $url;
	}

	
	Controller::$SERVER["LIEN_DEFAUT"] = url(); //initilise le lien base dans le controller
	
	

	if(!isset($_SESSION["LANGUE"])){ //si la langue n'existe pas il faut mettre une lague par defaut
		$_SESSION["LANGUE"] = $_LANGUE_DEFAUT; //la lague choisie par l'admin;
	}
	Controller::$SERVER["url"] = ""; //initilise 
	if(isset($_GET["url"])){
		Controller::$SERVER["url"] = $_GET["url"]; //initilise la route choisie par l'utilisateur
	}

	
	
	//capture les mot en français ou englais en fonction de l'utilisateur;
	foreach($LANGUE as $key=>$uneLangue){
		Controller::$traduction[$key]=$uneLangue[$_SESSION["LANGUE"]];
	}
	//parcour une deuxieme fois le tableau LANGUE pour savoir si la langue en question est complet
	foreach($LANGUE as $key=>$uneLangue){
		if (!array_key_exists($key, Controller::$traduction)) {
			Controller::$traduction[$key]=$uneLangue[$_LANGUE_DEFAUT];
		}
	}
	//PARCOUR DEU TABLEAU DE ROUTE
	Controller::redirectionRoute();
	
	
?>