<!DOCTYPE html>
<?php
    include 'functions/functions.php';
    $joinsql = getDb();
    include 'functions/session.php';
    include 'side_menu.php';
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
        <title>Lekérdezések</title>
    </head>
        <body>

            <h3>Globális pilóta lista lekérő</h3>
            <div class="frame">     
                <form method="post" autocomplete="off">            
                    <input type="submit" name = "globallist" value = "Globális bajnoki lista mutatása" class="button button2">
                    <input type="submit" name = "globallistoff" value = "Globális bajnoki lista elrejtése" class="button button2">
                </form>
            <?php if (!isset($_POST['globallist'])): ?>
                </div>
            <?php endif ?>
            <?php if (isset($_POST['globallist'])):
                    $i = 0;
                    $pilotListCommand = "SELECT pilot_name, pilot.nationality, birth_date, carnumber, pilot.points, wins, podiums, pilot.titles FROM pilot ORDER BY points DESC;";
                    $pilotList = mysqli_query($joinsql, $pilotListCommand) or die(mysqli_error($joinsql));
                    $teammember = "SELECT brand_name, contract_started, contract_expired FROM team_member INNER JOIN team ON team_id = team.id"
            ?> 
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>Globális pozíciója</th>
                                <th>Neve</th>
                                <th>Nemzitsége</th>      
                                <th>Születési dátuma</th>      
                                <th>Rajtszáma</th>
                                <th>Pontjai</th>
                                <th>Győzelmei</th>
                                <th>Dobogós helyezései</th>
                                <th>Világbajnoki címei</th>
                            </tr> 
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_array($pilotList)): $i++?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$row['pilot_name']?></td>
                                <td><?=$row['nationality']?></td>
                                <td><?=$row['birth_date']?></td>
                                <td><?=$row['carnumber']?></td>
                                <td><?=$row['points']?></td>
                                <td><?=$row['wins']?></td>
                                <td><?=$row['podiums']?></td>
                                <td><?=$row['titles']?></td>
                            </tr>                
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                </div>
            </div>
            <?php endif ?>
            <br>


            <h3>Globális csapat lista lekérő</h3>
            <div class="frame"> 
                <form method="post" autocomplete="off">            
                    <input type="submit" name = "globalteamlist" value = "Globális konstrokturi bajnoki lista mutatása" class="button button2">
                    <input type="submit" name = "globalteamlistoff" value = "Globális konstrokturi bajnoki lista elrejtése" class="button button2">
                </form>
            <?php if (!isset($_POST['globalteamlist'])): ?>
                </div>
            <?php endif ?>
            <?php if (isset($_POST['globalteamlist'])):
                    $i = 0;
                    $teamListCommand = "SELECT id, brand_name, points, money_spent, nationality, titles FROM team ORDER BY points DESC";
                    $teamList = mysqli_query($joinsql, $teamListCommand) or die(mysqli_error($joinsql)); 
            ?>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>Globális pozíciója</th>
                                <th>Neve</th>
                                <th>Nemzitsége</th>      
                                <th>Pontjai</th>      
                                <th>Világbajnoki címek</th>
                                <th>Elköltött Pénz (Millió dollár)</th>
                            </tr> 
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_array($teamList)): $i++?>
                            <tr>
                                <td><?=$i?></td>    
                                <td><?=$row['brand_name']?></td>
                                <td><?=$row['nationality']?></td>
                                <td><?=$row['points']?></td>
                                <td><?=$row['titles']?></td>
                                <td><?=$row['money_spent']?></td>
                            </tr>                
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                </div>
            </div>
            <?php endif ?>
            <br> 
        
        <h3>Pálya adat lekérő</h3>
        <div class="frame"> 
        <table>
            <tbody>    
                <tr>
                    <form method="post" autocomplete="off">   
                        <td> Pálya választása: </td>   
                        <td>     
                            <div class="select">
                                <select name = 'raceid'> 
                                    <?php
                                        $listRaceCommand = 'SELECT id, country FROM race';
                                        $resultRaces = mysqli_query($joinsql, $listRaceCommand) or die(mysqli_error($joinsql));
                                        while ($races = mysqli_fetch_array($resultRaces)):
                                    ?>
                                    <option value="<?=$races['id']?>"><?=$races['country']?></option>
                                    <?php endwhile; ?>
                                </select> 
                            </div>
                        </td>          
                        <td> <input type="submit" name = "map" value = "Pálya adatainak kilistázása" class="button button2"> </td>
                        <td> <input type="submit" name = "mapoff" value = "Pálya adatainak elrejtése" class="button button2"> </td>
                    </form>
                </tr>
            </tbody> 
        </table>
            <?php if(!isset($_POST['map'])): ?>
                </div>
            <?php endif?>
            <?php if(isset($_POST['map'])):
                    $raceid = $_POST['raceid'];
                    $raceListCommand = "SELECT IF(competed.finish_position = 1, pilot_name, 'Ismeretlen') as pname, country, length, turns, race_date, IFNULL(best_laptime, '---') best_laptime, IFNULL(best_laptime_driver, 'Ismeretlen') best_laptime_driver
                    FROM race INNER JOIN competed ON race.id = race_id INNER JOIN pilot ON pilot_id = pilot.id WHERE race.id = '$raceid'";
                    $raceListRaw = mysqli_query($joinsql, $raceListCommand) or die(mysqli_error($joinsql));
                    $raceList = mysqli_fetch_array($raceListRaw);
                    if(!$raceList){
                        $raceListCommand = "SELECT id, country, length, turns, race_date, IFNULL(best_laptime, '---') best_laptime, IFNULL(best_laptime_driver, 'Ismeretlen') best_laptime_driver FROM race WHERE race.id = '$raceid'";
                        $raceListRaw = mysqli_query($joinsql, $raceListCommand) or die(mysqli_error($joinsql));
                        $raceList = mysqli_fetch_array($raceListRaw);
                        $raceList['pname'] = "Még nem volt nyertes";
                    }
            ?>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>Ország</th>
                                <th>Hossza (km)</th>      
                                <th>Kanyarok száma</th>      
                                <th>Megrendezés Dátuma</th>
                                <th>Legjobb köridő</th>
                                <th>Rekord tartó</th>
                                <th>Nyert rajta</th>
                            </tr> 
                        </thead>
                        <tbody>
                            <tr>
                                <td><?=$raceList['country']?></td>
                                <td><?=$raceList['length']?></td>
                                <td><?=$raceList['turns']?></td>
                                <td><?=$raceList['race_date']?></td>
                                <td><?=$raceList['best_laptime']?></td>
                                <td><?=$raceList['best_laptime_driver']?></td>
                                <td><?=$raceList['pname']?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>                
            <?php endif ?>
            
            
            <br>
            <h3>Éves lista lekérő</h3>
            <div class="frame">
                <form method="post" autocomplete="off">
                    Év választása:
                    <input type="number" name = "year" value = "2022">        
                    <input type="submit" name = "yearlist" value = "Évi bajnoki lista mutatása" class="button button2">
                    <input type="submit" name = "yearlistoff" value = "Éves bajnoki lista elrejtése" class="button button2">
                </form>
            <?php if (!isset($_POST['yearlist'])): ?>
                </div>
            <?php endif?>
            <?php if (isset($_POST['yearlist'])):
                    $year = $_POST['year'];
                    $nextyear = $year + 1;
                    $pointListCommand = "SELECT pilot.id as pid, finish_position FROM pilot INNER JOIN competed ON pilot.id = pilot_id INNER JOIN race ON race_id = race.id WHERE race_date >= '$year-01-01' AND race_date < '$nextyear-01-01' ORDER BY pilot.id";
                    $pilotListCommand = "SELECT pilot.id as pid, pilot_name, nationality, carnumber, titles FROM pilot INNER JOIN competed ON pilot.id = pilot_id INNER JOIN race ON race_id = race.id WHERE race_date >= '$year-01-01' AND race_date < '$nextyear-01-01' GROUP BY pilot.id";
                    $pilotList = mysqli_query($joinsql, $pilotListCommand) or die(mysqli_error($joinsql));
            ?>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>Neve</th>
                                <th>Nemzitsége</th>          
                                <th>Rajtszáma</th>
                                <th>Idei pontjai</th>
                                <th>Idei győzelmei</th>
                                <th>Idei dobogós helyezései</th>
                                <th>Világbajnoki címei</th>
                            </tr> 
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_array($pilotList)): 
                            $point = 0;
                            $wins = 0;
                            $podiums = 0;
                            $pointList = mysqli_query($joinsql, $pointListCommand) or die(mysqli_error($joinsql));
                            while($prow = mysqli_fetch_array($pointList)){
                                $pos = $prow['finish_position'];
                                $pointGain = countPointsGain($pos);
                                if($row['pid'] == $prow['pid']){
                                    $point += $pointGain;
                                    if($pos < 4){
                                        $podiums++;
                                        if($pos == 1){
                                            $wins++;
                                        }
                                    }
                                }
                            }
                        ?>
                            <tr>
                                <td><?=$row['pilot_name']?></td>
                                <td><?=$row['nationality']?></td>
                                <td><?=$row['carnumber']?></td>
                                <td><?=$point?></td>
                                <td><?=$wins?></td>
                                <td><?=$podiums?></td>
                                <td><?=$row['titles']?></td>
                            </tr>                
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>
            <br>

            <h3>Csapat lekérő</h3>
            <div class= "frame">
            <table>
            <tbody>
            <tr>
                <form method="post" autocomplete="off">
                <td>Év választása: </td>
                <td> <input type="number" name = "tyear" value = "2022"> </td>
                <td>    
                    <div class="select">
                        <select name = 'pilotid'>
                            <?php
                                $listPilotsCommand = 'SELECT id, pilot_name FROM pilot';
                                $resultPlist = mysqli_query($joinsql, $listPilotsCommand) or die(mysqli_error($joinsql));
                                while ($pilots = mysqli_fetch_array($resultPlist)):
                            ?>
                            <option value="<?=$pilots['id']?>"><?=$pilots['pilot_name']?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </td>        
                <td><input type="submit" name = "team" value = "Csapat mutatása" class="button button2"></td>
                <td><input type="submit" name = "teamoff" value = "Csapat elrejtése" class="button button2"></td>
                </form>
            </tr>
            </tbody>
            </table>
            <?php if (!isset($_POST['team'])): ?>
                </div>
            <?php endif?>
            <?php if (isset($_POST['team'])):
                    $tyear = $_POST['tyear'];
                    $pid = $_POST['pilotid'];
                    $teamCommand = "SELECT brand_name, contract_expired FROM team INNER JOIN team_member on team.id = team_id INNER JOIN pilot on pilot_id = pilot.id WHERE contract_started <= '$tyear-01-01' AND contract_expired > '$tyear-01-01' AND pilot.id = $pid";
                    $rawteam = mysqli_query($joinsql, $teamCommand) or die(mysqli_error($joinsql));
                    $team = mysqli_fetch_array($rawteam);
                    if(!$team){
                        $team['brand_name'] = "Ebben az évben a pilóta sehova sincs leszerződve!";
                        $team['contract_expired'] = "---";
                    }
            ?>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>Csapat neve</th>
                                <th>Szerződése lejár</th>          
                            </tr> 
                        </thead>
                        <tbody>
                            <tr>
                                <td><?=$team['brand_name']?></td>
                                <td><?=$team['contract_expired']?></td>
                            </tr>                
                            </tbody>
                        </table>
                </div>
            </div>
            <?php endif ?>
            <?php mysqli_close($joinsql); ?>
    </body>

</html>