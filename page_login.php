<html>

    <head>
        <!-- Nella head sono inclusi elementi di conoscenze pregresse  -->
        <title>Cineclick - Registrazione</title>
        <meta charset="utf-8"/> 
        <link href="css/style.css" rel="stylesheet" type="text/css">
    </head>

    <body>
    <img src='img/logo.png' alt="Logo col nome del sito" width="150" height="100">
        <?php 
        
        include'login_class_cinema.php';

        $login=new login();

        echo $login->HTML_form_login();

        ?>
    </body>
</html>