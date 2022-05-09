<!DOCTYPE html>
<?php
    include 'side_menu.php';
    include 'functions/functions.php';
?>
<html>
    <head>
    <?php
        if(!isset($_GET['skin'])){
            $_GET['skin'] = 'skins/mclaren.scss';
        }
        echo " <link rel='stylesheet' type='text/css' href=".$_GET['skin']." /> ";
    ?>
        <meta charset="UTF-8"> 
        <title>Főoldal</title>
    </head>
    <body>
            <h1>Üdvözlöm a Forma1 admin oldalon</h1>
            <h3>Itt különbőző Froma1-es adatok lekérdezésére, szerkesztésére és létrehozására lesz lehetősége</h3>
            <div class = "frame">
            <h4 align="center">Csapat témájú kinézet választása</h4>
            <br>
            <table align="center">
                <tbody>
                    <form  method = "get">
                        <tr>  
                            <td align="center"> <label for="mclaren"><img src="skins/images/mclaren.jpg" title="Mclaren" alt="Mclaren" width="250px"></label> </td>
                            <td align="center"> <label for="ferrari"><img src="skins/images/ferrari.jpg" title="Ferrari" alt="Ferrari" width="250px"></label> </td>
                            <td align="center"> <label for="mercedes"><img src="skins/images/mercedes.jpg" title="Mercedes" alt="Mercedes" width="250px"></label> </td>
                        </tr>
                        <tr>
                            <td align="center"> <input type="radio" id ="mclaren" name="skin" value= "skins/mclaren.scss"> </td>
                            <td align="center"> <input type="radio" id ="ferrari" name="skin" value= "skins/ferrari.scss"> </td>
                            <td align="center"> <input type="radio" id ="mercedes" name="skin" value= "skins/mercedes.scss"> </td>
                        </tr>
                            <td></td>
                            <td align="center"> <input type = "submit" name = "skinset" value = "Választ" class="button button2"/> </td>
                    </form>
                </tbody>
            </table>
    </div>
            
    <body>
        
    </body>

</html>