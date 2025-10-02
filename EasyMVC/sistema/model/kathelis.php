<?php
class Kathelis {
	private $id;
	private $nome;
	private $email;

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
	function getEmail(){
		return $this->email;
	}
	function setEmail($email){
		$this->email=$email;
	}

}
?>