<!DOCTYPE html>
<?php
$joinsql = getDb();
?>
<html>
    <body>
        <br>
        --------------------
        <?php $showRlist = false; $editRace = false; ?>
            <form  method = "post" autocomplete="off">
                <?php 
                    if(!isset($_POST['rns'])){
                        $_POST['rns'] = "";
                    } 
                ?>
                Keresés: 
                <input type="search" name="rns" value = "<?=$_POST['rns']?>" placeholder="Verseny helyére"/>
                <input type="submit" name = "r_search" value="Keres" class="button button2"/>
                <input type = "submit" name = "rshow" value = "Összes verseny mutatása" class="button button2"/>
                Szeretne adatokat szerkeszteni?
                <input type="radio" id ="igen" name="redit" value= 1>
                <label for="igen">Igen</label>
                <input type="radio" id = "nem" name="redit" value= 0 checked>
                <label for="nem">Nem</label>
            </form>
            <?php 
                if (isset($_POST['rshow']) || isset($_POST['r_search'])) {
                    $showRlist = true;
                    $editRace = $_POST['redit'];
                } 
            ?>

            <form  method = "post">
                <input type = "submit" name = "rhide" value = "Versenyek elrejtése" class="button button2"/>
            </form>
            
            
            <?php if (isset($_POST['rhide'])) {$showRlist = false; } ?>

                <?php if ($showRlist):
                    $raceListCommand = "SELECT id, country, length, turns, race_date, IFNULL(best_laptime, '---') best_laptime, IFNULL(best_laptime_driver, 'Ismeretlen') best_laptime_driver FROM race";
                    if (isset($_POST['r_search'])) {
                        $raceListCommand = $raceListCommand . sprintf(" WHERE LOWER(country) LIKE '%%%s%%'", mysqli_real_escape_string($joinsql, strtolower($_POST['rns'])));
                    }
                    $raceList = mysqli_query($joinsql, $raceListCommand) or die(mysqli_error($joinsql));
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
                <?php endif ?>
                <?php if( ($showRlist == true) && ($editRace == false) ): ?>
                            </tr>
                        </thead>
                        <tbody> 
                <?php endif ?>
                        <?php if( ($showRlist == true) && ($editRace == false) ): while ($row = mysqli_fetch_array($raceList)): ?>
                            <tr>
                                <td><?=$row['country']?></td>
                                <td><?=$row['length']?></td>
                                <td><?=$row['turns']?></td>
                                <td><?=$row['race_date']?></td>
                                <td><?=$row['best_laptime']?></td>
                                <td><?=$row['best_laptime_driver']?></td>
                            </tr>                
                        <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
                        <?php endif ?>
                        <?php if( ($showRlist == true) && ($editRace == true) ): ?>
                            <th>Szerkesztések</th>
                            </tr> 
                        </thead>
                        <tbody>
                        <?php endif ?>
                        <?php if( ($showRlist == true) && ($editRace == true) ): while ($row = mysqli_fetch_array($raceList)):  
                        ?>
                            <tr>
                                <form method = "post" autocomplete="off">
                                    <input type="hidden" name="rid" value= <?=$row['id']?>>
                                    <td> <input type = "text" name = "country" value = <?=$row['country']?> ></td>
                                    <td> <input type = "float" name = "length" value = <?=$row['length']?> ></td>
                                    <td> <input type = "number" name = "turns" value = <?=$row['turns']?> ></td>
                                    <td> <input type = "date" name = "race_date" value = <?=$row['race_date']?> ></td>
                                    <td> <input type = "time" name = "best_laptime" step = "0.001" value = <?=$row['best_laptime']?> ></td>
                                    <td> 
                                        <input type="text" list="rp" name = "best_laptime_driver" value = <?=$row['best_laptime_driver']?> />
                                        <datalist id="rp"> 
                                            <option> <?=$row['best_laptime_driver']?> </option>
                                            <option>Ismeretlen</option>
                                            <?php
                                                $listPilotsCommand = 'SELECT pilot_name FROM pilot';
                                                $resultPlist = mysqli_query($joinsql, $listPilotsCommand) or die(mysqli_error($joinsql));
                                                while ($pilots = mysqli_fetch_array($resultPlist)):
                                                ?>
                                                <option value="<?=$pilots['pilot_name']?>"><?=$pilots['pilot_name']?></option>
                                            <?php endwhile; ?>
                                        </datalist>                   
                                    </td>
                                    <td><input type = "submit" name = "editrace" value = "Szerkeszt" class="button button2"/>
                                    <input type = "submit" name = "deleterace" value = "Töröl" class="button button2"/></td>
                                </form>
                            </tr>                
                        <?php endwhile; ?>
                        <?php endif ?>
                        </tbody>
                    </table>
                </div>
                    <?php
                        if (isset($_POST['editrace'])) {

                            $succesRaceEdit = false;
                            $country = mysqli_real_escape_string($joinsql, $_POST['country']);
                            $rdate = mysqli_real_escape_string($joinsql, $_POST['race_date']);
                            $length = mysqli_real_escape_string($joinsql, $_POST['length']);
                            $turns = mysqli_real_escape_string($joinsql, $_POST['turns']);
                            $best_laptime = mysqli_real_escape_string($joinsql, $_POST['best_laptime']);
                            $best_laptime_driver = mysqli_real_escape_string($joinsql, $_POST['best_laptime_driver']);
                            $race_id = mysqli_real_escape_string($joinsql, $_POST['rid']);

                            if(check_race($joinsql, $country, $rdate, $length, $turns)){
                                $updateRaceCommand = sprintf("UPDATE race SET country='$country', race_date='$rdate', length='$length', turns='$turns', 
                                best_laptime='$best_laptime', best_laptime_driver = '$best_laptime_driver' WHERE id='$race_id'");
                                mysqli_query($joinsql, $updateRaceCommand) or die(mysqli_error($joinsql));
                                $succesRaceEdit = true;
                            }

                            if($succesRaceEdit){
                                echo "<div class='success'>";
                                echo $_SESSION['redit'];
                                unset($_SESSION['redit']);
                                echo "</div>";
                            }
                            else {
                                echo "<div class='fail'>";
                                echo $_SESSION['editfail'];
                                unset($_SESSION['editfail']);
                                echo "</div>";
                            }
                        }
                        if (isset($_POST['deleterace'])) {
                            
                            $getCompetedcommand = "SELECT race_id, pilot_id, finish_position FROM competed";
                            $competedId = mysqli_query($joinsql, $getCompetedcommand) or die(mysqli_error($joinsql));
                            $race_id = mysqli_real_escape_string($joinsql, $_POST['rid']);

                            
                            while($deleteComp = mysqli_fetch_array($competedId)){
                                if( $deleteComp['race_id'] == $race_id) {
                                    reSetPoints($deleteComp['pilot_id'], $deleteComp['race_id'], $deleteComp['finish_position'], $zeroPoint);
                                    $DeleteCompCommand = "DELETE FROM competed WHERE race_id = '$race_id'";
                                    mysqli_query($joinsql, $DeleteCompCommand) or die(mysqli_error($joinsql));
                                }
                            }
                            
                            $succesRaceDelete = false;
                            $deleteRaceCommand = sprintf("DELETE FROM race WHERE id = '$race_id'");
                           
                            mysqli_query($joinsql, $deleteRaceCommand) or die(mysqli_error($joinsql));
                            $succesRaceDelete = true;

                            if($succesRaceDelete){
                                echo "<div class='success'>";
                                echo $_SESSION['rdelete'];
                                unset($_SESSION['rdelete']);
                                echo "</div>";
                            }
                            else {
                                echo "<div class='fail'>";
                                echo $_SESSION['deletefail'];
                                unset($_SESSION['deletefail']);
                                echo "</div>";
                            }
                        }
                    ?>

    </body>