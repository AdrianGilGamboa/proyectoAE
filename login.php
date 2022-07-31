<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Acceso</title>
        <style>
            input{
                background: #23A455;
                border-radius: 50px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
                color: white;
                display: inline-block;
                font-size: 20px;
                font-weight: bold;
                padding: 2em 3em;
                text-decoration: none;
                transition: .3s;
                font-family: Ubuntu, sans-serif;
            }
            input:hover{
                background: #1c8a47;
                box-shadow: 0 4px 8px rgba(0, 0, 0, .15);
                transition: .3s;
                cursor: pointer;
            }
        </style>
    </head>
    <body style="text-align:center">
        <?php
        
        if (isset($_POST['btnlogin'])) {
            header("Location: https://localhost/GilGamboaAdrian_ProyectoAE/paginaPrincipal.php");
            $_SESSION['CLIENT_VERIFY'] = $_SERVER['SSL_CLIENT_VERIFY'];
            $_SESSION['CLIENT_V_REMAIN'] = $_SERVER['SSL_CLIENT_V_REMAIN'];
        }
        ?>
        <form action="#" method="post">
            <input style="margin-top:20%" type="submit" value="Acceder con Certificado Digital" name="btnlogin">
        </form>
    </body>
</html>