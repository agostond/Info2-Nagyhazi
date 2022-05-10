<!DOCTYPE html>
<?php
    include 'functions/functions.php';
    $joinsql = getDb();
    include 'functions/session.php';
    include 'functions/cechk.php';
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
        <title>Elem felvétel</title>
    </head>
    <body>
        <h3> Pilóta felvétel: </h3>
        <div class="frame">
        <table>
            <tbody>   
                <form method = "post" autocomplete="off">
                    <tr>
                        <td align="center"> 
                            Neve: <input type = "text" size = "30" name = "pname" placeholder="Pilóta neve"/>
                        </td>   
                        <td align="center">
                            Nemzetisége: <input type = "text" size = "20" name = "nat" placeholder="Nemzetisége"/>
                        </td>
                        <td align="center">
                            Születési dátuma: <input type = "date" name = "bdate"/>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            Rajtszáma: <input type = "number" name = "cnumb" placeholder="Az autó száma"/>
                        </td>
                        <td align="center">
                            Pontjai: <input type = "number" name = "points" value = "0"/>
                        </td>
                        <td align="center">
                            Győzelmei: <input type = "number" name = "wins" value = "0"/>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            Dobogói: <input type = "number" name = "podiums" value = "0"/>
                        </td>
                        <td align="center">
                            Világbajnoki címei: <input type = "number" name = "titles" value = "0"/>
                        </td>
                        <td align="center">
                            <input type = "submit" name = "newdriver" value = "Létrehoz" class="button button2"/>
                        </td>
                    </tr>
                </form>
            </tbody>
        </table>
            <?php
                if (isset($_POST['newdriver'])) {
                    $succesPilot = false;
                    $pname = mysqli_real_escape_string($joinsql, $_POST['pname']);
                    $nat = mysqli_real_escape_string($joinsql, $_POST['nat']);
                    $bdate = mysqli_real_escape_string($joinsql, $_POST['bdate']);
                    $cnumb = mysqli_real_escape_string($joinsql, $_POST['cnumb']);
                    $points = mysqli_real_escape_string($joinsql, $_POST['points']);
                    $podiums = mysqli_real_escape_string($joinsql, $_POST['podiums']);
                    $wins = mysqli_real_escape_string($joinsql, $_POST['wins']);
                    $titles = mysqli_real_escape_string($joinsql, $_POST['titles']);
                    
                    if(check_pilot($joinsql, $pname, $nat, $bdate, $cnumb)){
                        $newPilotCommand = sprintf("INSERT INTO pilot(pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles) 
                        VALUES('$pname', '$nat', '$bdate', '$cnumb', '$points', '$wins', '$podiums', '$titles')");
                        mysqli_query($joinsql, $newPilotCommand) or die(mysqli_error($joinsql));
                        $succesPilot = true;
                    }

                    if($succesPilot){
                        echo "<div class='success'>";
                        echo $_SESSION['psucces'];
                        unset($_SESSION['psucces']);
                        echo "</div>";
                    }
                    else {
                        echo "<div class='fail'>";
                        echo $_SESSION['addfail'];
                        unset($_SESSION['addfail']);
                        echo "</div>";
                    }
                }
            ?>
            <?php include 'lists/pilotlist.php'; ?>
            </div>
            


            <h3> Verseny felvétel: </h3>
            <div class="frame">
                <table>
                    <tbody>   
                        <form method = "post" autocomplete="off">
                            <tr>
                                <td align="center"> 
                                    Ország és megrendezési év: <input type = "text" size = "30" name = "country" placeholder="OrszágÉv / Ország-Év"/>
                                </td>
                                <td align="center">
                                    Megrendezés pontos dátuma: <input type = "date" name = "rdate"/>
                                </td>
                                <td align="center">
                                    Hossza (km): <input type = "float" name = "length" placeholder="Hossz"/>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    Kanyarok száma: <input type = "number" name = "turns" placeholder="Kanyarok száma"/>
                                </td>
                                <td align="center">
                                    Legjobb köridő: <input type = "time" step=".001" name = "blaptime"/>
                                </td>
                                <td align="center">
                                    Rekord tartó:
                                    <input type="text" list="rp" name = "recdriver"/>
                                    <datalist id="rp"> 
                                        <option>Ismeretlen</option>
                                        <?php
                                            $listPilotsCommand = 'SELECT pilot_name FROM pilot';
                                            $resultPlist = mysqli_query($joinsql, $listPilotsCommand) or die(mysqli_error($joinsql));
                                            while ($pilots = mysqli_fetch_array($resultPlist)):
                                            ?>
                                            <option value="<?=$pilots['pilot_name']?>"><?=$pilots['pilot_name']?></option>
                                        <?php endwhile; ?>
                                    </datalist>
                                </td align="center">
                                <td align="center"> <input type = "submit" name = "newrace" value = "Létrehoz" class="button button2"/> </td>
                            </tr>
                        </form>
                    </tbody>
                </table>
            <?php
                if (isset($_POST['newrace'])) {
                    
                    $succesMap = false;
                    $country = mysqli_real_escape_string($joinsql, $_POST['country']);
                    $rdate = mysqli_real_escape_string($joinsql, $_POST['rdate']);
                    $length = mysqli_real_escape_string($joinsql, $_POST['length']);
                    $turns = mysqli_real_escape_string($joinsql, $_POST['turns']);
                    $blaptime = mysqli_real_escape_string($joinsql, $_POST['blaptime']);
                    $recdriver = mysqli_real_escape_string($joinsql, $_POST['recdriver']);

                    if(check_race($joinsql, $country, $rdate, $length, $turns)){
                        $newRaceCommand = sprintf("INSERT INTO race(length, country, turns, best_laptime, best_laptime_driver, race_date) 
                        VALUES('$length', '$country', '$turns', '$blaptime', '$recdriver', '$rdate')");
                        mysqli_query($joinsql, $newRaceCommand) or die(mysqli_error($joinsql));
                        $succesMap = true;
                    }

                    if($succesMap){
                        echo "<div class='success'>";
                        echo $_SESSION['rsucces'];
                        unset($_SESSION['rsucces']);
                        echo "</div>";
                    }
                    else {
                        echo "<div class='fail'>";
                        echo $_SESSION['addfail'];
                        unset($_SESSION['addfail']);
                        echo "</div>";
                    }
                }
            ?>
            <?php include 'lists/racelist.php'; ?>
            </div>


            <h3> Csapat felvétel: </h3>
            <div class="frame">
                <table>
                    <tbody>   
                        <form method = "post" autocomplete="off">
                        <tr>    
                            <td align="center"> 
                                Neve: <input type = "text" size = "30" name = "tname" placeholder="A csapat neve"/>
                            </td>
                            <td align="center">
                                Székhelye: <input type = "text" size = "20" name = "tnat" placeholder="A csapat székhelye"/>
                            <td align="center">
                                Pontjai: <input type = "number" name = "tpoints" value = "0"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                Világbajnoki címei: <input type = "number" name = "ttitles" value = "0"/>
                            </td>
                            <td align="center">
                                Eddigi költésük (millió dollár): <input type = "float" value = "0.0" name = "money_spent"/>
                            </td>
                            <td align="center">
                                <input type = "submit" name = "newteam" value = "Létrehoz" class="button button2"/>
                            </td>
                        </tr>
                        </form>
                    </tbody>
                </table>    
                    <?php
                        if (isset($_POST['newteam'])) {

                            $succesTeam = false;
                            $tname = mysqli_real_escape_string($joinsql, $_POST['tname']);
                            $tnat = mysqli_real_escape_string($joinsql, $_POST['tnat']);
                            $tpoints = mysqli_real_escape_string($joinsql, $_POST['tpoints']);
                            $ttitles = mysqli_real_escape_string($joinsql, $_POST['ttitles']);
                            $money_spent = mysqli_real_escape_string($joinsql, $_POST['money_spent']);

                            if(check_team($joinsql, $tname, $tnat)){
                                $newTeamCommand = sprintf("INSERT INTO team(brand_name, points, money_spent, nationality, titles) 
                                VALUES('$tname', '$tpoints', '$money_spent', '$tnat', '$ttitles')");
                                mysqli_query($joinsql, $newTeamCommand) or die(mysqli_error($joinsql));
                                $succesTeam = true;
                            }
                            if($succesTeam){
                                echo "<div class='success'>";
                                echo $_SESSION['tsucces'];
                                unset($_SESSION['tsucces']);
                                echo "</div>";
                            }
                            else {
                                echo "<div class='fail'>";
                                echo $_SESSION['addfail'];
                                unset($_SESSION['addfail']);
                                echo "</div>";
                            }
                        }
                    ?>
                    <?php include 'lists/teamlist.php'; ?>
                    <?php mysqli_close($joinsql); ?>
                </div>
    </body>

</html>