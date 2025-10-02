<?php
require_once("../model/conexao.php");
class KathelisDao {
    private $con;
    public function __construct(){
       $this->con=(new Conexao())->conectar();
    }
function inserir($obj) {
    $sql = "INSERT INTO kathelis (nome, email) VALUES (?, ?)";
    $stmt = $this->con->prepare($sql);
    $id=$obj->getId();
$nome=$obj->getNome();
$email=$obj->getEmail();

    $stmt->execute([$nome, $email]);
}
function listaGeral(){
    $sql = "select * from kathelis";
    $query = $this->con->query($sql);
    $dados = $query->fetchAll(PDO::FETCH_ASSOC);
    return $dados;
}
 function buscaPorId($id){
    $sql = "select * from kathelis where id=$id";
    $query = $this->con->query($sql);
    $dados = $query->fetch(PDO::FETCH_ASSOC);
    return $dados;
}   
function excluir($id){
    $sql = "delete from kathelis where id=$id";
    $query = $this->con->query($sql);
    header("Location:../view/listaKathelis.php");
}
    function alterar($obj){
    $id=$obj->getId();
$nome=$obj->getNome();
$email=$obj->getEmail();

    $sql = "UPDATE kathelis SET nome = ?, email = ? WHERE id = ?";
    $stmt = $this->con->prepare($sql);
    $stmt->execute([$nome, $email, $id]);
    header("Location:../view/listaKathelis.php");
}
}
?>