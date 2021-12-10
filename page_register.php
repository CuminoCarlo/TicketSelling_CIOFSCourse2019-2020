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
        require_once('login_class_cinema.php');
        
        $modulo=new registrazione();
        echo $modulo->HTML_form_registrazione();
        ?> 
       

    </body>

</html>