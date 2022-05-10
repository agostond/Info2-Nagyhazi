
<!DOCTYPE html>
<html>
    <body>
        <form method="post">
            <?php 
                if(!isset($_POST['pns'])){
                    $_POST['pns'] = "";
                } 
            ?>
            Keresés: 
            <input type="search" name="pns" value = "<?=$_POST['pns']?>"/>
            <input type="submit" name = "p_search" value="Keres"/>
        </form>

        <?php
            $joinsql = mysqli_connect("localhost", "root", "", "f1") 
            or die("Nem sikerült kapcsolódni az adatbázishoz: " . mysqli_error());
        
            $querySelect = "SELECT pilot_name FROM pilot";
            if (isset($_POST['p_search'])) {
                $querySelect = $querySelect . sprintf(" WHERE LOWER(pilot_name) LIKE '%%%s%%'", mysqli_real_escape_string($joinsql, strtolower($_POST['pns'])));
            }
            $eredmeny = mysqli_query($link, $querySelect) or die(mysqli_error($link));
        ?>
         <?php while ($row = mysqli_fetch_array($eredmeny)): ?>
            <tr>
                <td><?=$row['pilot_name']?></td>
            </tr>
        <?php endwhile?>
    </body>
</html>