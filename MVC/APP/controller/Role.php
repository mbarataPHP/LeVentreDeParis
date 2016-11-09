<?php

/**
 * Class Role
 */
class Role{

    public static function debutConnection(){

    }

    public static function getIdUtilisateur(){
        $id = false;
        if(isset($_SESSION["ROLE"]["id"]) && isset($_SESSION["ROLE"]["IP"]) && $_SESSION["ROLE"]["IP"]==Controller::get_client_ip()){
            $id = $_SESSION["ROLE"]["id"];
        }
        return $id;
    }

    /**
     * Verif si la validation
     * @param $role
     * @return bool
     */
    public static function verifValidation($role){
        $returnBool = false;

        if(!isset($_SESSION["ROLE"]["ROLE"])){
            $_SESSION["ROLE"]["ROLE"] = "anonyme";
        }
        switch($role){
            case "administrateur":
                if($_SESSION["ROLE"]["ROLE"]=="administrateur"){
                    $returnBool = true;
                }
                break;

            case "utilisateur":
                if($_SESSION["ROLE"]["ROLE"]=="administrateur" || $_SESSION["ROLE"]["ROLE"]=="utilisateur"){
                    $returnBool = true;
                }
                break;
            default:
                if($_SESSION["ROLE"]["ROLE"]=="administrateur" || $_SESSION["ROLE"]["ROLE"]=="utilisateur" || $_SESSION["ROLE"]["ROLE"]=="anonyme"){
                    $returnBool = true;
                }
        }
        return $returnBool;
    }

    /**
     * intilise une nouvelle conection de l'utilisateur
     * @author Mathieu Barata <mathieu@barata.fr>
     * @param integer $idUtilisateur
     * @param string $role
     */
    public static function initialiseConnection($idUtilisateur, $role = "utilisateur"){
        $_SESSION["ROLE"]["id"] = $idUtilisateur;
        $_SESSION["ROLE"]["date"] = new DateTime();

        switch($role){
            case "administrateur":
                $_SESSION["ROLE"]["ROLE"] = "administrateur";
                break;

            case "utilisateur":
                $_SESSION["ROLE"]["ROLE"] = "utilisateur";
                break;
            default:
                $_SESSION["ROLE"]["ROLE"] = "anonyme";
        }

        $_SESSION["ROLE"]["IP"] = Controller::get_client_ip();
    }

    /**
     * Décoonexion de l'utilisateur
     *@author Mathieu Barata <mathieu@barata.fr>
     */
    public static function deconnexion(){
        if(isset($_SESSION["ROLE"])){
            $_SESSION["ROLE"] = null;
            unset($_SESSION["ROLE"]);
        }

    }
}
Role::debutConnection();
