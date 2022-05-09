<!DOCTYPE html>
<?php
    include 'functions/functions.php';
    $joinsql = getDb();
    include 'functions/session.php';
    include 'side_menu.php';
    include 'functions/cechk.php';
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
        <title>Hozzárendelések</title>
    </head>
    <body>
        <h3>Versenyeredmény felvétele</h3>
        <div class="frame">
        <form method="post" autocomplete="off">
                <table>
                    <tbody>
                        <tr>  
                            <td align="center">Pilóta neve:
                            <div class="select">    
                                     
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

                            <td align="center">Verseny Helyszíne és ideje:  
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

            <td align="center">Rajtpozíciója:<br>
            <input type = "number" name = "start_pos" placeholder="Kezdő pozíció"/> </td>
            <td align="center">Végeredménye:<br>
            <input type = "number" name = "fin_pos" placeholder="Végeredmény"/> </td>
            <td><input type = "submit" name = "competed" value = "Létrehoz" class="button button2"/> </td>
                        </tr>
                    </tbody>
                </table>
            <?php
            if (isset($_POST['competed'])) {

                $succesComp = false;
                $raceid = mysqli_real_escape_string($joinsql, $_POST['raceid']);
                $pilotid = mysqli_real_escape_string($joinsql, $_POST['pilotid']);
                $start_pos = mysqli_real_escape_string($joinsql, $_POST['start_pos']);
                $fin_pos = mysqli_real_escape_string($joinsql, $_POST['fin_pos']);

                if(check_competed($joinsql, $raceid, $pilotid, $start_pos, $fin_pos, 0)){
                    $newCompCommand = sprintf("INSERT INTO competed(pilot_id, race_id, start_position, finish_position) 
                    VALUES('$pilotid', '$raceid', '$start_pos', '$fin_pos')");
                    mysqli_query($joinsql, $newCompCommand) or die(mysqli_error($joinsql));
                    $succesComp = true;
                }

                if($succesComp){
                    reSetPoints($pilotid, $raceid, $zeroPoint, $fin_pos);
                    echo "<div class='success'>";
                    echo $_SESSION['csucces'];
                    unset($_SESSION['csucces']);
                    echo "</div>";
                }
                else {
                    echo "<div class='fail'>";
                    echo $_SESSION['connectfail'];
                    unset($_SESSION['connectfail']);
                    echo "</div>";
                }
            }
        ?>
        <br>
        <?php include 'lists/competedlist.php'; ?>

        <h3>Csapatszerződés felvétele</h3>
        
        <div class="frame">
            <table>
                <tbody>
                    <tr>  
                        <form method="post" autocomplete="off">
                            <td>Pilóta neve:    
                                <div class="select"> 
                                <select name = 'pilotid_forteam'>
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

                            <td>Csapat neve:  
                                <div class="select"> 
                                <select name = 'teamid'>
                                    <?php
                                        $listTeamsCommand = 'SELECT id, brand_name FROM team';
                                        $resultTeams = mysqli_query($joinsql, $listTeamsCommand) or die(mysqli_error($joinsql));
                                        while ($teams = mysqli_fetch_array($resultTeams)):
                                    ?>
                                    <option value="<?=$teams['id']?>"><?=$teams['brand_name']?></option>
                                    <?php endwhile; ?>
                                </select>
                                </div>
                            </td>

                            <td>Szerződés kezdete: <br>
                                <input type = "date" name = "cstart" value="0001-01-01"/>
                            </td>

                            <td>Szerződés vége: <br>
                                <input type = "date" name = "cfinish" value="0001-01-01"/>
                            </td>
                            
                            <td>
                                <input type = "submit" name = "team_member" value = "Létrehoz" class="button button2"/>
                            </td>
                    <tr>
                </tbody>
            </table>

            <?php
            if (isset($_POST['team_member'])) {

                $succesMemb = false;
                $teamid = mysqli_real_escape_string($joinsql, $_POST['teamid']);
                $pilotid_forteam = mysqli_real_escape_string($joinsql, $_POST['pilotid_forteam']);
                $cstart = mysqli_real_escape_string($joinsql, $_POST['cstart']);
                $cfinish = mysqli_real_escape_string($joinsql, $_POST['cfinish']);

                if(check_tm($joinsql, $teamid, $pilotid_forteam, $cstart, $cfinish)){
                    $newCompCommand = sprintf("INSERT INTO team_member(pilot_id, team_id, contract_started, contract_expired) 
                    VALUES('$pilotid_forteam', '$teamid', '$cstart', '$cfinish')");
                    mysqli_query($joinsql, $newCompCommand) or die(mysqli_error($joinsql));
                    $succesMemb = true;
                }

                if($succesMemb){
                    echo "<div class='success'>";
                    echo $_SESSION['msucces'];
                    unset($_SESSION['msucces']);
                    echo "</div>";
                }
                else {
                    echo "<div class='fail'>";
                    echo $_SESSION['connectfail'];
                    unset($_SESSION['connectfail']);
                    echo "</div>";
                }
            }
        ?>
        <br>
         <?php include 'lists/tmlist.php'; ?>


         <h3>Csapat költés hozzáadása</h3>
         <div class="frame">
            <table>
                <tbody>
                    <tr>  
                        <form method="post" autocomplete="off">
                            <td>Csapat neve:  
                            <div class="select"> 
                            <select name = 'teamid'>
                            <?php
                                $listTeamsCommand = 'SELECT id, brand_name FROM team';
                                $resultTeams = mysqli_query($joinsql, $listTeamsCommand) or die(mysqli_error($joinsql));
                                while ($teams = mysqli_fetch_array($resultTeams)):
                            ?>
                            <option value="<?=$teams['id']?>"><?=$teams['brand_name']?></option>
                            <?php endwhile; ?>
                            </select>
                            </div><td>

                            <td>Elköltött pénz (millió dollár): <br>
                            <input type = "float" name = "money" placeholder="Pénzösszeg (millió dollár)"/></td>
                            <td><input type = "submit" name = "addmoney" value = "Hozzáad" class="button button2"/></td>
                    </tr>
                </tbody>
            </table>
        </div>
            <?php
            if (isset($_POST['addmoney'])) {

                $succesMADD = false;
                $teamid = mysqli_real_escape_string($joinsql, $_POST['teamid']);
                $currentMoneyCommand = "SELECT money_spent FROM team WHERE id = '$teamid'";
                $rawCurrentMoney = mysqli_query($joinsql, $currentMoneyCommand) or die(mysqli_error($joinsql));
                $CurrentMoney = mysqli_fetch_array($rawCurrentMoney);

                if(check_money(mysqli_real_escape_string($joinsql, $_POST['money']))){
                    $money = mysqli_real_escape_string($joinsql, $_POST['money']) + $CurrentMoney['money_spent'];
                    $addMoneyCommand = sprintf("UPDATE team SET money_spent = '$money' WHERE id = '$teamid'");
                    mysqli_query($joinsql, $addMoneyCommand) or die(mysqli_error($joinsql));
                    $succesMADD = true;
                }

                if($succesMADD){
                    echo "<div class='success'>";
                    echo $_SESSION['maddsucces'];
                    unset($_SESSION['maddsucces']);
                    echo "</div>";
                }
                else {
                    echo "<div class='fail'>";
                    echo $_SESSION['maddfail'];
                    unset($_SESSION['maddfail']);
                    echo "</div>";
                }
            }
            ?>
            <?php mysqli_close($joinsql); ?>


    </body>