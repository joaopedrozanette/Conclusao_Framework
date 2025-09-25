<?php
    require_once('../dao/alunosDao.php');
    $obj=null;
    if(isset($_GET['id']))
    $obj=(new alunosDao())->buscaPorId($_GET['id']);
    $acao=$obj?3:1;
?>
<html>
    <head>
        <title>Cadastro de alunos</title>
        <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
        <form action="../control/alunosControl.php?a=<?php echo $acao ?>" method="post">
        <h1>Cadastro de alunos</h1>
            <label for='id'>id</label>
<input type='text' value='<?php echo $obj?$obj['id']:''; ?>'name='id'><br>
<label for='nome'>nome</label>
<input type='text' value='<?php echo $obj?$obj['nome']:''; ?>'name='nome'><br>
<label for='idade'>idade</label>
<input type='text' value='<?php echo $obj?$obj['idade']:''; ?>'name='idade'><br>
<label for='estrangeiro'>estrangeiro</label>
<input type='text' value='<?php echo $obj?$obj['estrangeiro']:''; ?>'name='estrangeiro'><br>
<label for='id_curso'>id_curso</label>
<input type='text' value='<?php echo $obj?$obj['id_curso']:''; ?>'name='id_curso'><br>

             <button type="submit">Enviar</button>
        </form>
    </body>
</html>