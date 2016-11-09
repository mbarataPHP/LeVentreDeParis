<?php
Class ControllerAction{
	
	
	/*************************
	LES MESSAGES ERREURS
	*************************/
	/**
	* cette methode personnalise le message 404
	* @author Mathieu Barata 21/09/2016 <mathieu@barata.fr>
	*/
	public static function erreur404Action(){
		
		Controller::callVue("ERREUR/404.php"); //appelle la méthode qui génére la vue
	}

    /**
     * cette methode personnalise l'erreur Role
     * @author Mathieu Barata 21/09/2016 <mathieu@barata.fr>
     */
	public static function erreurRoleAction(){
        Controller::callVue("ERREUR/role.php"); //appelle la méthode qui génére la vue
    }
}

?>