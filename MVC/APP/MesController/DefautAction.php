<?php
Class DefautAction{
	
	public static function indexAction(){
		Controller::callVue("defaut.php"); //appelle la méthode qui génére la vue
	}

}
?>