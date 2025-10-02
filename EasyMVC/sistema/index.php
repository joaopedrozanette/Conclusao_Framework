    
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
                <a href='./view/Kathelis.php' target='iframe'> Cadastrar Kathelis</a>

            </ul>
        </li>
        <li>
            <a href="#">Listas</a>
            <ul>
                <a href='./view/listaKathelis.php' target='iframe'> Listar Kathelis</a>

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