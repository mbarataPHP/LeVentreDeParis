<?php
class Type{
	public $_type = null;

	public $_nom = null;

	public $_null = false;

	public $_nbrCaractere;


	//STRING
	public function _STRING(){
		$tab = array($this->_nom=>array());
		$tab[$this->_nom]["type"] = $this->_type;
		$tab[$this->_nom]["null"] = $this->_null;
		$tab[$this->_nom]["nbr"] = $this->_nbrCaractere;

		return $tab;
	}
	
	public function _SET_STRING($tab){
		$this->_nom = $tab["nom"];
		$this->_type = $tab["type"];
		$this->_null = $tab["null"];
		$this->_nbrCaractere = $tab["nbr"];
	}


	//INT
	public function _INT(){
		$tab = array($this->_nom=>array());
		$tab[$this->_nom]["type"] = $this->_type;
		$tab[$this->_nom]["null"] = $this->_null;
		
		return $tab;
	}

	public function _SET_INT($tab){
		$this->_nom = $tab["nom"];
		$this->_type = $tab["type"];
		$this->_null = $tab["null"];
		
	}


	//BOOL
	public function _BOOL(){
		$tab = array($this->_nom=>array());
		$tab[$this->_nom]["type"] = $this->_type;
		$tab[$this->_nom]["null"] = $this->_null;
		
		return $tab;
	}

	public function _SET_BOOL($tab){
		$this->_nom = $tab["nom"];
		$this->_type = $tab["type"];
		$this->_null = $tab["null"];
		
	}


	//DATETIME
	public function _DATETIME(){
		$tab = array($this->_nom=>array());
		$tab[$this->_nom]["type"] = $this->_type;
		$tab[$this->_nom]["null"] = $this->_null;
		
		return $tab;
	}

	public function _SET_DATETIME($tab){
		$this->_nom = $tab["nom"];
		$this->_type = $tab["type"];
		$this->_null = $tab["null"];
		
	}

	public function GET_RETURN_ALL(){
		switch($this->_type){
			case "string":
				return $this->_STRING();
			break;
			case "int":
				return $this->_INT();
			break;
			case "bool":
				return $this->_BOOL();
			break;
			case "datetime":
				return $this->_DATETIME();
			break;
		}

	}
}

?>