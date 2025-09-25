
<html>
    <head>
        <title>Lista de alunos</title>
        <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
      <?php
      require_once("../dao/alunosDao.php");
   $dao=new alunosDAO();
   $dados=$dao->listaGeral();
    echo "<table border=1>";
    foreach($dados as $dado){
        echo "<tr>";
       echo "<td>{$dado['id']}</td>";
echo "<td>{$dado['nome']}</td>";
echo "<td>{$dado['idade']}</td>";
echo "<td>{$dado['estrangeiro']}</td>";
echo "<td>{$dado['id_curso']}</td>";

       echo "<td>".
       "<a href='../control/alunosControl.php?id={$dado['id']}&a=2'> Excluir</a>".
       "</td>";
       echo "<td>" . 
        "<a href='../view/alunos.php?id={$dado['id']}'> Alterar</a>" .
       "</td>";
       echo "</tr>";
    }
    echo "</table>";
     ?>  
    </body>
</html>