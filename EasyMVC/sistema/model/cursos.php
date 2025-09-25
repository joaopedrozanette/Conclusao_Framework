<?php
class Cursos {
	private $id;
	private $nome;
	private $turno;

	function getId(){
		return $this->id;
	}
	function setId($id){
		$this->id=$id;
	}
	function getNome(){
		return $this->nome;
	}
	function setNome($nome){
		$this->nome=$nome;
	}
	function getTurno(){
		return $this->turno;
	}
	function setTurno($turno){
		$this->turno=$turno;
	}

}
?>