
<html>
    <head>
        <title>Lista de cursos</title>
        <link rel="stylesheet" href="../css/estilos.css">
    </head>
    <body>
      <?php
      require_once("../dao/cursosDao.php");
   $dao=new cursosDAO();
   $dados=$dao->listaGeral();
    echo "<table border=1>";
    foreach($dados as $dado){
        echo "<tr>";
       echo "<td>{$dado['id']}</td>";
echo "<td>{$dado['nome']}</td>";
echo "<td>{$dado['turno']}</td>";

       echo "<td>".
       "<a href='../control/cursosControl.php?id={$dado['id']}&a=2'> Excluir</a>".
       "</td>";
       echo "<td>" . 
        "<a href='../view/cursos.php?id={$dado['id']}'> Alterar</a>" .
       "</td>";
       echo "</tr>";
    }
    echo "</table>";
     ?>  
    </body>
</html>