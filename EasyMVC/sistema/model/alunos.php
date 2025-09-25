<?php
class Alunos {
	private $id;
	private $nome;
	private $idade;
	private $estrangeiro;
	private $id_curso;

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
	function getIdade(){
		return $this->idade;
	}
	function setIdade($idade){
		$this->idade=$idade;
	}
	function getEstrangeiro(){
		return $this->estrangeiro;
	}
	function setEstrangeiro($estrangeiro){
		$this->estrangeiro=$estrangeiro;
	}
	function getId_curso(){
		return $this->id_curso;
	}
	function setId_curso($id_curso){
		$this->id_curso=$id_curso;
	}

}
?>