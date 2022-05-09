<!DOCTYPE html>
<html>
    <head>
        <?php
            if(!isset($_GET['skin'])){
                $_GET['skin'] = 'skins/mclaren.scss';
            }
        ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="skins/sidemenu.css" />
    </head>
    <body>

        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="index.php?skin=<?=$_GET['skin']?>">Fő oldal</a>
            <a href="lekerdezesek.php?skin=<?=$_GET['skin']?>">Lekérdezések</a>
            <a href="uj_elem.php?skin=<?=$_GET['skin']?>">Új elem felvétele</a>
            <a href="hozzarendeles.php?skin=<?=$_GET['skin']?>">Hozzárendelések</a> 
        </div>
        <div id="pos">
            <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menü</span>
        </div>

        <script>
            function openNav() {
            document.getElementById("mySidenav").style.width = "250px"; 
            }

            function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            }
        </script>
        
    </body>
</html> 
