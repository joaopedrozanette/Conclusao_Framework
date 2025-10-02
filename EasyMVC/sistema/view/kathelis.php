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
            <label for='id_visu'>id</label>
<input id='id_visu' type='text' readonly class='readonly' value='<?php echo $obj ? $obj['id'] : "(automático)"; ?>'><br>
<?php if($obj): ?>
<input type='hidden' name='id' value='<?php echo $obj['id']; ?>'>
<?php endif; ?>
<label for='nome'>nome</label>
<input type='text' value='<?php echo $obj?$obj['nome']:''; ?>' name='nome' id='nome'><br>
<label for='email'>email</label>
<input type='text' value='<?php echo $obj?$obj['email']:''; ?>' name='email' id='email'><br>

             <button type="submit">Enviar</button>
        </form>
    </body>
</html>