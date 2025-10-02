<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
class Creator {
    private $con;
    private $servidor ;
    private $banco;
    private $usuario;
    private $senha;
    private $tabelas;
    function __construct() {
        if(isset($_GET['id']))
            $this->buscaBancodeDados();
        else {
            $this->criaDiretorios();
            $this->conectar(1);
            $this->buscaTabelas();
            $this->ClassesModel();
            $this->ClasseConexao();
            $this->ClassesControl();
            $this->classesView();
            $this->ClassesDao();
            $this->compactar();
            header("Location:index.php?msg=2");
        }
    }//fimConsytruct
    function criaDiretorios() {
        $dirs = [
            "sistema",
            "sistema/model",
            "sistema/control",
            "sistema/view",
            "sistema/dao",
            "sistema/css"
        ];

        foreach ($dirs as $dir) {
            if (!file_exists($dir)) {
                if (!mkdir($dir, 0777, true)) {
                    header("Location:index.php?msg=0");
                }
            }
        }
        copy('estilos.css','sistema/css/estilos.css');
    }//fimDiretorios
    function conectar($id){
        $this->servidor = $_REQUEST["servidor"];
        $this->usuario = $_REQUEST["usuario"];
        $this->senha = $_REQUEST["senha"];
        if ($id == 1) {
           $this->banco = $_POST["banco"];
        }
        else{
            $this->banco = "mysql";


        }
        try {
            $this->con = new PDO(
                "mysql:host=" . $this->servidor . ";dbname=" . $this->banco,
                $this->usuario,
                $this->senha
            );
        } catch (Exception $e) {

           header("Location:index.php?msg=1");
        }
    }//fimConectar
    function buscaBancodeDados(){
        try {
                $this->conectar(0);
                $sql = "SHOW databases";
                $query = $this->con->query($sql);
                $databases = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($databases as $database){
                    echo "<option>".$database["Database"]."</option>";
                }
                $this->con=null;
            }
        catch (Exception $e) {
            header("Location:index.php?msg=3");

        }
    }//BuscaBD
    function buscaTabelas(){
       try {
           $sql = "SHOW TABLES";
           $query = $this->con->query($sql);
           $this->tabelas = $query->fetchAll(PDO::FETCH_ASSOC);
       }
       catch (Exception $e) {
           header("Location:index.php?msg=3");
       }
    }//fimBuscaTabelas
    function buscaAtributos($nomeTabela){
        $sql="show columns from ".$nomeTabela;
        $atributos = $this->con->query($sql)->fetchAll(PDO::FETCH_OBJ);
        return $atributos;
    }//fimBuscaAtributos
    function ClassesModel() {
        foreach ($this->tabelas as $tabela) {
            $nomeTabela = array_values((array) $tabela)[0];
            $atributos=$this->buscaAtributos($nomeTabela);
            $nomeAtributos="";
            $geters_seters="";
            foreach ($atributos as $atributo) {
                $atributo=$atributo->Field;
                $nomeAtributos.="\tprivate \${$atributo};\n";
                $metodo=ucfirst($atributo);
                $geters_seters.="\tfunction get".$metodo."(){\n";
                $geters_seters.="\t\treturn \$this->{$atributo};\n\t}\n";
                $geters_seters.="\tfunction set".$metodo."(\${$atributo}){\n";
                $geters_seters.="\t\t\$this->{$atributo}=\${$atributo};\n\t}\n";
            }
            $nomeClasse=ucfirst($nomeTabela);
            $conteudo = <<<EOT
<?php
class {$nomeClasse} {
{$nomeAtributos}
{$geters_seters}
}
?>
EOT;
            file_put_contents("sistema/model/{$nomeTabela}.php", $conteudo);

        }
    }//fimModel
    function ClasseConexao(){
        $conteudo = <<<EOT

<?php
class Conexao {
    private \$server;
    private \$banco;
    private \$usuario;
    private \$senha;
    function __construct() {
        \$this->server = '{$this->servidor}';
        \$this->banco = '{$this->banco}';
        \$this->usuario = '{$this->usuario}';
        \$this->senha = '{$this->senha}';
    }
    
    function conectar() {
        try {
            \$conn = new PDO(
                "mysql:host=" . \$this->server . ";dbname=" . \$this->banco,\$this->usuario,
                \$this->senha
            );
            return \$conn;
        } catch (Exception \$e) {
            echo "Erro ao conectar com o Banco de dados: " . \$e->getMessage();
        }
    }
}
?>
EOT;
        file_put_contents("sistema/model/conexao.php", $conteudo);
    }//fimConexao
    function ClassesControl(){
    foreach ($this->tabelas as $tabela) {
        $nomeTabela = array_values((array)$tabela)[0];
        $atributos = $this->buscaAtributos($nomeTabela);
        $nomeClasse = ucfirst($nomeTabela);

        // (mantido do teu original - não é usado, mas não quebra nada)
        $posts = "";
        foreach ($atributos as $atributo) {
            $atributo = $atributo->Field;
            $posts .= "\$this->{$nomeTabela}->set".ucFirst($atributo)."(\$_POST['{$atributo}']);\n\t\t";
        }

        // NOVO: preparar inserts/updates sem PK
        $postsInsert = "";
        $postsUpdate = "";
        $campoPk = "";
        foreach ($atributos as $att) {
            if ($att->Key == "PRI") {
                $campoPk = $att->Field;
                continue; // não popular PK nos setters de insert/update
            }
            $nome = $att->Field;
            $Met = ucfirst($nome);
            $postsInsert .= "\$this->{$nomeTabela}->set{$Met}(\$_POST['{$nome}']);\n\t\t";
            $postsUpdate .= "\$this->{$nomeTabela}->set{$Met}(\$_POST['{$nome}']);\n\t\t";
        }
        // nomes padronizados para usar no heredoc
        $pk = $campoPk;
        $Pk = ucfirst($campoPk);

        $conteudo = <<<EOT
<?php
require_once("../model/{$nomeTabela}.php");
require_once("../dao/{$nomeTabela}Dao.php");

class {$nomeClasse}Control {
    private \${$nomeTabela};
    private \$acao;
    private \$dao;

    public function __construct(){
        \$this->{$nomeTabela} = new {$nomeClasse}();
        \$this->dao = new {$nomeClasse}Dao();
        \$this->acao = \$_GET["a"];
        \$this->verificaAcao();
    }

    function verificaAcao(){
        switch(\$this->acao){
            case 1: \$this->inserir(); break;
            case 2: \$this->excluir(); break;
            case 3: \$this->alterar(); break; // UPDATE
        }
    }

    function inserir(){
        {$postsInsert}
        \$this->dao->inserir(\$this->{$nomeTabela});
        header("Location:../view/lista{$nomeClasse}.php");
        exit;
    }

    function excluir(){
        \$this->dao->excluir(\$_REQUEST['id']);
        header("Location:../view/lista{$nomeClasse}.php");
        exit;
    }

    function alterar(){
        // valida PK recebido via hidden
        \$id = isset(\$_POST['{$pk}']) ? (int)\$_POST['{$pk}'] : 0;
        if (\$id <= 0) {
            header("Location:../view/lista{$nomeClasse}.php");
            exit;
        }
        // seta PK apenas uma vez
        \$this->{$nomeTabela}->set{$Pk}(\$id);

        // setters para campos que não são PK
        {$postsUpdate}

        \$this->dao->alterar(\$this->{$nomeTabela});
        header("Location:../view/lista{$nomeClasse}.php");
        exit;
    }
}
?>
EOT;

        file_put_contents("sistema/control/{$nomeTabela}Control.php", $conteudo);
    }
} // fimControl

    function compactar() {
        $folderToZip = 'sistema';
        $outputZip = 'sistema.zip';
        $zip = new ZipArchive();
        if ($zip->open($outputZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return false;
        }
        $folderPath = realpath($folderToZip);  // Corrigido aqui
        if (!is_dir($folderPath)) {
            return false;
        }
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folderPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folderPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        return $zip->close();
    }//fimCompactar
   function ClassesDao(){
    foreach ($this->tabelas as $tabela) {
        $nomeTabela = array_values((array)$tabela)[0];
        $nomeClasse = ucfirst($nomeTabela);

        // 1) NÃO mexa em $atribObjs antes de extrair PK/colunas
        $atribObjs = $this->buscaAtributos($nomeTabela);

        // Descobre PK a partir dos objetos (precisa do ->Key / ->Field)
        $pkCampo = "";
        foreach ($atribObjs as $att) {
            if ($att->Key == "PRI") {
                $pkCampo = $att->Field;
                break;
            }
        }

        // Lista de TODAS as colunas a partir dos objetos
        $colsTodas = array_map(function($o){ return $o->Field; }, $atribObjs);

        // Colunas SEM PK (para INSERT e SET do UPDATE)
        $colsSemPk = array_values(array_filter($colsTodas, function($c) use($pkCampo){ return $c !== $pkCampo; }));

        // Preparos para INSERT (só colunas sem PK)
        $sqlColsInsert = implode(', ', $colsSemPk);
        $qsInsert      = implode(', ', array_fill(0, count($colsSemPk), '?'));

        $paramsInsert = [];
        foreach ($colsSemPk as $c) { $paramsInsert[] = "\$obj->get".ucfirst($c)."()"; }
        $paramsInsert = implode(', ', $paramsInsert);

        // Preparos para UPDATE: SET "c1=?, c2=? ..." e params (campos sem PK + PK no final)
        $setUpdate = implode(', ', array_map(function($c){ return "$c = ?"; }, $colsSemPk));
        $paramsUpdate = [];
        foreach ($colsSemPk as $c) { $paramsUpdate[] = "\$obj->get".ucfirst($c)."()"; }
        $paramsUpdate[] = "\$obj->get".ucfirst($pkCampo)."()"; // WHERE PK
        $paramsUpdate = implode(', ', $paramsUpdate);

        // ---- A PARTIR DAQUI, mantenho teu bloco antigo (se precisar dele em outra parte) ----
        // (ele não interfere no INSERT/UPDATE novos)
        $atributos = array_map(function($obj) {
            return $obj->Field;
        }, $atribObjs);

        $id="";
        foreach($atribObjs as $atributo) {
            if ($atributo->Key == "PRI")
                $id = $atributo->Field;
        }

        $sqlCols = implode(', ', $atributos);
        $placeholders = implode(', ', array_fill(0, count($atributos), '?'));
        $vetAtributos=[];
        $AtributosMetodos="";

        foreach ($atributos as $atributo) {
            $atr=ucfirst($atributo);
            array_push($vetAtributos,"\${$atributo}");
            $AtributosMetodos.="\${$atributo}=\$obj->get{$atr}();\n";
        }
        $atributosOk=implode(",",$vetAtributos);
        // --------------------------------------------------------------------

        $conteudo = <<<EOT
<?php
require_once("../model/{$nomeTabela}.php");
require_once("../control/Conexao.php");

class {$nomeClasse}Dao {
    private \$con;
    function __construct(){
        \$this->con = (new Conexao())->conectar();
    }

    function inserir(\$obj) {
        // INSERT sem enviar PK (auto_increment)
        \$sql = "INSERT INTO {$nomeTabela} ({$sqlColsInsert}) VALUES ({$qsInsert})";
        \$stmt = \$this->con->prepare(\$sql);
        \$stmt->execute([{$paramsInsert}]);
    }

    function alterar(\$obj){
        // UPDATE só com campos não-PK e WHERE no PK
        \$sql = "UPDATE {$nomeTabela} SET {$setUpdate} WHERE {$pkCampo} = ?";
        \$stmt = \$this->con->prepare(\$sql);
        \$stmt->execute([{$paramsUpdate}]);
    }

    function listaGeral(){
        \$sql = "SELECT * FROM {$nomeTabela}";
        \$query = \$this->con->query(\$sql);
        return \$query->fetchAll(PDO::FETCH_ASSOC);
    }

    function buscaPorId(\$id){
        \$sql = "SELECT * FROM {$nomeTabela} WHERE {$pkCampo}=\$id";
        \$query = \$this->con->query(\$sql);
        return \$query->fetch(PDO::FETCH_ASSOC);
    }

    function excluir(\$id){
        \$sql = "DELETE FROM {$nomeTabela} WHERE {$pkCampo}=\$id";
        \$this->con->query(\$sql);
        header("Location:../view/lista{$nomeClasse}.php");
    }
}
?>
EOT;

        file_put_contents("sistema/dao/{$nomeTabela}Dao.php", $conteudo);
    }
} // fimDao

    function classesView() {
        //formulários
        foreach ($this->tabelas as $tabela) {
            $nomeTabela = array_values((array) $tabela)[0];
            $atributos=$this->buscaAtributos($nomeTabela);
            $formCampos="";
            foreach ($atributos as $atributo) {
                    
                $atributo=$atributo->Field;
                $formCampos .= "<label for='{$atributo}'>{$atributo}</label>\n";
                $formCampos .= "<input type='text' " .
                "value='<?php echo \$obj?\$obj['{$atributo}']:''; ?>'" . 
                "name='{$atributo}'><br>\n";
            }
            $conteudo = <<<HTML
<?php
    require_once('../dao/{$nomeTabela}Dao.php');
    \$obj=null;
    if(isset(\$_GET['id']))
    \$obj=(new {$nomeTabela}Dao())->buscaPorId(\$_GET['id']);
    \$acao=\$obj?3:1;
?>
<html>
    <head>
        <title>Cadastro de {$nomeTabela}</title>
        <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
        <form action="../control/{$nomeTabela}Control.php?a=<?php echo \$acao ?>" method="post">
        <h1>Cadastro de {$nomeTabela}</h1>
            {$formCampos}
             <button type="submit">Enviar</button>
        </form>
    </body>
</html>
HTML;
            file_put_contents("sistema/view/{$nomeTabela}.php", $conteudo); // Exemplo salvando como arquivo
        }
        //Listas
        foreach ($this->tabelas as $tabela) {
            $nomeTabela = array_values((array)$tabela)[0];
            $nomeTabelaUC=ucfirst($nomeTabela);
            $atributos=$this->buscaAtributos($nomeTabela);
            $attr = "";
            $id="";
            foreach($atributos as $atributo){
                if($atributo->Key=="PRI")
                    $id="{\$dado['{$atributo->Field}']}";

                $attr.= "echo \"<td>{\$dado['{$atributo->Field}']}</td>\";\n";
            }
            $conteudo="";
            $conteudo = <<<HTML

<html>
    <head>
        <title>Lista de {$nomeTabela}</title>
        <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
      <?php
      require_once("../dao/{$nomeTabela}Dao.php");
   \$dao=new {$nomeTabela}DAO();
   \$dados=\$dao->listaGeral();
    echo "<table border=1>";
    foreach(\$dados as \$dado){
        echo "<tr>";
       {$attr}
       echo "<td>".
       "<a href='../control/{$nomeTabela}Control.php?id={$id}&a=2'> Excluir</a>".
       "</td>";
       echo "<td>" . 
        "<a href='../view/{$nomeTabela}.php?id={$id}'> Alterar</a>" .
       "</td>";
       echo "</tr>";
    }
    echo "</table>";
     ?>  
    </body>
</html>
HTML;           
  file_put_contents("sistema/view/lista{$nomeTabelaUC}.php", $conteudo);        
        }
    }//fimView
 function paginaInicial(){
      $listas = "";
        $formularios = "";
        foreach ($this->tabelas as $tabela) {
            $nomeTabela = array_values((array)$tabela)[0];
            $nomeTabelaUCfirst = ucfirst($nomeTabela);
            $formularios .= "<a href='./view/{$nomeTabela}.php' target='iframe'> Cadastrar {$nomeTabela}</a>\n";
            $listas .= "<a href='./view/lista{$nomeTabelaUCfirst}.php' target='iframe'> Listar {$nomeTabelaUCfirst}</a>\n";

        }
        $conteudo = "";
        $conteudo = <<<HTML
    
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema</title>
</head>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Cabeçalho */
        .cabecalho {
            width: 100%;
            height: 200px;
            background-color: #2c3e50;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
        }

        /* Menu principal */
        .menu {
            width: 100%;
            height: 100px;
            background-color: #34495e;
            display: flex;
            align-items: center;
            padding-left: 20px;
        }

        .menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 40px;
        }

        .menu li {
            position: relative;
        }

        .menu a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px;
            display: block;
        }

        /* Submenu */
        .menu li ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #2c3e50;
            list-style: none;
            padding: 0;
            margin: 0;
            min-width: 200px;
        }

        .menu li ul li a {
            padding: 10px;
            font-size: 16px;
        }

        /* Exibir submenu ao passar o mouse */
        .menu li:hover ul {
            display: block;
        }

        /* Conteúdo */
        .conteudo {
            min-height: calc(100vh - 300px); /* altura total - cabeçalho (200) - menu (100) */
            padding: 20px;
            background-color: #ecf0f1;
        }
    </style>
<body>
<div class="cabecalho">
    Sistema
</div>

<div class="menu">
    <ul>
        <li>
            <a href="#">Formulários</a>
            <ul>
                {$formularios}
            </ul>
        </li>
        <li>
            <a href="#">Listas</a>
            <ul>
                {$listas}
            </ul>
        </li>
    </ul>
</div>

<div class="conteudo">
    <h2>Bem-vindo!</h2>
    <p>Esta é a área de conteúdo do sistema.</p>
    <iframe id="contentFrame" name="iframe" seamless style="width:100%; height:80vh; border:none;">
        Seu navegador não suporta iframes.
    </iframe>
</div>
</body>
</html>
HTML;
        file_put_contents("sistema/index.php", $conteudo);

}

}
new Creator();