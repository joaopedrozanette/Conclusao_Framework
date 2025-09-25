<?php
require_once("../model/cursos.php");
require_once("../dao/cursosDao.php");
class CursosControl {
    private $cursos;
    private $acao;
    private $dao;
    public function __construct(){
       $this->cursos=new Cursos();
      $this->dao=new CursosDao();
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
        $this->cursos->setId($_POST['id']);
		$this->cursos->setNome($_POST['nome']);
		$this->cursos->setTurno($_POST['turno']);
		
        $this->dao->inserir($this->cursos);
    }
    function excluir(){
        $this->dao->excluir($_REQUEST['id']);
    }
    function alterar(){}
    function buscarId(Cursos $cursos){}
    function buscaTodos(){}

}
new CursosControl();
?>