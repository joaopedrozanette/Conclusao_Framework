<?php
    require_once('../dao/cursosDao.php');
    $obj=null;
    if(isset($_GET['id']))
    $obj=(new cursosDao())->buscaPorId($_GET['id']);
    $acao=$obj?3:1;
?>
<html>
    <head>
        <title>Cadastro de cursos</title>
        <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
        <form action="../control/cursosControl.php?a=<?php echo $acao ?>" method="post">
        <h1>Cadastro de cursos</h1>
            <label for='id'>id</label>
<input type='text' value='<?php echo $obj?$obj['id']:''; ?>'name='id'><br>
<label for='nome'>nome</label>
<input type='text' value='<?php echo $obj?$obj['nome']:''; ?>'name='nome'><br>
<label for='turno'>turno</label>
<input type='text' value='<?php echo $obj?$obj['turno']:''; ?>'name='turno'><br>

             <button type="submit">Enviar</button>
        </form>
    </body>
</html>