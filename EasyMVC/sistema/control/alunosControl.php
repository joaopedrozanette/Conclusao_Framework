<?php
require_once("../model/alunos.php");
require_once("../dao/alunosDao.php");
class AlunosControl {
    private $alunos;
    private $acao;
    private $dao;
    public function __construct(){
       $this->alunos=new Alunos();
      $this->dao=new AlunosDao();
      $this->acao=$_GET["a"];
      $this->verificaAcao(); 
    }
    function verificaAcao(){
       switch($this->acao){
          case 1:
            $this->inserir();
          break;
          case 2:
            $this->excluir();
          break;
       }
    }
  
    function inserir(){
        $this->alunos->setId($_POST['id']);
		$this->alunos->setNome($_POST['nome']);
		$this->alunos->setIdade($_POST['idade']);
		$this->alunos->setEstrangeiro($_POST['estrangeiro']);
		$this->alunos->setId_curso($_POST['id_curso']);
		
        $this->dao->inserir($this->alunos);
    }
    function excluir(){
        $this->dao->excluir($_REQUEST['id']);
    }
    function alterar(){}
    function buscarId(Alunos $alunos){}
    function buscaTodos(){}

}
new AlunosControl();
?>