<?php
require_once("../model/conexao.php");
class AlunosDao {
    private $con;
    public function __construct(){
       $this->con=(new Conexao())->conectar();
    }
function inserir($obj) {
    $sql = "INSERT INTO alunos (id, nome, idade, estrangeiro, id_curso) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->con->prepare($sql);
    $id=$obj->getId();
$nome=$obj->getNome();
$idade=$obj->getIdade();
$estrangeiro=$obj->getEstrangeiro();
$id_curso=$obj->getId_curso();

    $stmt->execute([$id,$nome,$idade,$estrangeiro,$id_curso]);
}
function listaGeral(){
    $sql = "select * from alunos";
    $query = $this->con->query($sql);
    $dados = $query->fetchAll(PDO::FETCH_ASSOC);
    return $dados;
}
 function buscaPorId($id){
    $sql = "select * from alunos where id=$id";
    $query = $this->con->query($sql);
    $dados = $query->fetch(PDO::FETCH_ASSOC);
    return $dados;
}   
function excluir($id){
    $sql = "delete from alunos where id=$id";
    $query = $this->con->query($sql);
    header("Location:../view/listaAlunos.php");
}
}
?>