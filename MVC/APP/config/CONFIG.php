<?php


$_SQL_HOST = "localhost";
$_SQL_USER = "root";
$_SQL_PWD = "";
$_SQL_BDD = "test";
$_SQL_TYPE = "mysql";


//CONFIG BASE
$_LANGUE_DEFAUT = "FR";
$_DIR_DEFAUT_CONTROLER = "APP/MesController/";
//CONTROLLER DEFAUT
	include "APP/controller/ControllerAction.php";
	include "APP/controller/Controller.inc.php";
    include("APP/modele/SQL.php");
    include("APP/controller/Role.php");
//MES CONTROLLERS

    $tabControleur = scandir($_DIR_DEFAUT_CONTROLER);


	foreach($tabControleur as $unControleur){
	    if($unControleur!="." && $unControleur!=".."){

            include $_DIR_DEFAUT_CONTROLER."".$unControleur;
        }

	}
	
?>