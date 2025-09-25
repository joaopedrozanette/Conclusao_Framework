<?php
require_once("../model/conexao.php");
class CursosDao {
    private $con;
    public function __construct(){
       $this->con=(new Conexao())->conectar();
    }
function inserir($obj) {
    $sql = "INSERT INTO cursos (id, nome, turno) VALUES (?, ?, ?)";
    $stmt = $this->con->prepare($sql);
    $id=$obj->getId();
$nome=$obj->getNome();
$turno=$obj->getTurno();

    $stmt->execute([$id,$nome,$turno]);
}
function listaGeral(){
    $sql = "select * from cursos";
    $query = $this->con->query($sql);
    $dados = $query->fetchAll(PDO::FETCH_ASSOC);
    return $dados;
}
 function buscaPorId($id){
    $sql = "select * from cursos where id=$id";
    $query = $this->con->query($sql);
    $dados = $query->fetch(PDO::FETCH_ASSOC);
    return $dados;
}   
function excluir($id){
    $sql = "delete from cursos where id=$id";
    $query = $this->con->query($sql);
    header("Location:../view/listaCursos.php");
}
}
?>