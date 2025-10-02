<?php
require_once("../model/kathelis.php");
require_once("../dao/kathelisDao.php");

class KathelisControl {
    private $kathelis;
    private $acao;
    private $dao;

    public function __construct(){
        $this->kathelis = new Kathelis();
        $this->dao = new KathelisDao();
        // protege quando 'a' não vier
        $this->acao = isset($_REQUEST['a']) ? (int)$_REQUEST['a'] : 1;
        $this->verificaAcao(); 
    }

    function verificaAcao(){
        switch ($this->acao){
            case 1: $this->inserir();  break;
            case 2: $this->excluir();  break;
            case 3: $this->alterar();  break;
        }
    }

    function inserir(){
        // Não seta o id aqui, é gerado automatico pelo bd
        $this->kathelis->setNome($_POST['nome'] ?? '');
		$this->kathelis->setEmail($_POST['email'] ?? '');
		
        $this->dao->inserir($this->kathelis);
        header("Location:../view/listaKathelis.php");
        exit;
    }

    function excluir(){
        $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
        if ($id > 0) {
            $this->dao->excluir($id);
        }
        header("Location:../view/listaKathelis.php");
        exit;
    }

    function alterar(){
        // No editar o hidden '{$pk}' existe; valida antes de usar
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) {
            header("Location:../view/listaKathelis.php");
            exit;
        }
        $this->kathelis->setId($id);
        $this->kathelis->setNome($_POST['nome'] ?? '');
		$this->kathelis->setEmail($_POST['email'] ?? '');
		
        $this->dao->alterar($this->kathelis);
        header("Location:../view/listaKathelis.php");
        exit;
    }

    function buscarId(Kathelis $kathelis){}
    function buscaTodos(){}
}
new KathelisControl();
?>