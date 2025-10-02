
<html>
    <head>
        <title>Lista de kathelis</title>
        <link rel="stylesheet" href="../css/estilos.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="p-4">
      <?php
      require_once("../dao/kathelisDao.php");
      $dao=new kathelisDao();
      $dados=$dao->listaGeral();
      echo "<table class='table table-striped table-bordered'>";
      foreach($dados as $dado){
          echo "<tr>";
          echo "<td>{$dado['id']}</td>";
echo "<td>{$dado['nome']}</td>";
echo "<td>{$dado['email']}</td>";

          echo "<td>".
          "<a href='../control/kathelisControl.php?id={$dado['id']}&a=2' class='btn btn-sm btn-danger'>Excluir</a>".
          "</td>";
          echo "<td>" . 
          "<a href='../view/kathelis.php?id={$dado['id']}' class='btn btn-sm btn-primary'>Alterar</a>" .
          "</td>";
          echo "</tr>";
      }
      echo "</table>";
     ?>  
    </body>
</html>