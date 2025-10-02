<?php
    require_once('../dao/kathelisDao.php');
    $obj=null;
    if(isset($_GET['id']))
        $obj=(new kathelisDao())->buscaPorId($_GET['id']);
    $acao=$obj?3:1;
?>
<html>
    <head>
        <title>Cadastro de kathelis</title>
        <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
        <form action="../control/kathelisControl.php?a=<?php echo $acao ?>" method="post">
        <h1>Cadastro de kathelis</h1>
            <input type='hidden' value='<?php echo $obj?$obj['id']:''; ?>'name='id' class='mt-3'><br>
<label for='nome'>nome</label>
<input type='text' value='<?php echo $obj?$obj['nome']:''; ?>'name='nome' class='mt-3'><br>
<label for='email'>email</label>
<input type='text' value='<?php echo $obj?$obj['email']:''; ?>'name='email' class='mt-3'><br>

             <button type="submit">Enviar</button>
        </form>
    </body>
</html>