<?php
class Table{
	public $_nom;

	public $_lesTypes = array();

	public function addType(Type $unType){
		array_push($this->_lesTypes, $unType);
	}


	public function getId(){
		$tab = array("id"=>array());
		$tab["id"]["type"] = "int";
		return $tab;
	}
	public function createJson(){
		$tab = array("key"=>array(), "lesValeurs"=>array());
		foreach($this->_lesTypes as $unType){
			foreach($unType->GET_RETURN_ALL() as $key=>$val){
				
				$tab["key"][$key] = $val;
			}
			
		}
		foreach($this->getId() as $key=>$val){
				
				$tab["key"][$key] = $val;
			}
		//$tab["key"] = $this->getId();
		return json_encode($tab);
	}

	public function name_file(){
		return $this->_nom.".JSON";
	}
}

?>