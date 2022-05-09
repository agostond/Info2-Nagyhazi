<!DOCTYPE html>
<?php
$joinsql = getDb();
?>
<html>
    <body>
        <?php $showClist = false; $editCompeted = false; ?>

            <form  method = "post">
                <input type = "submit" name = "cshow" value = "Versenyeredmények mutatása" class="button button2"/>
                Szeretne adatokat szerkeszteni?
                <input type="radio" id ="igen" name="cedit" value= 1>
                <label for="igen">Igen</label>
                <input type="radio" id = "nem" name="cedit" value= 0 checked>
                <label for="nem">Nem</label>
            </form>
            
            <?php 
                if (isset($_POST['cshow'])) {
                    $showClist = true;
                    $editCompeted = $_POST['cedit'];
                } 
            ?>

            <form  method = "post">
                <input type = "submit" name = "chide" value = "Versenyeredmények elrejtése" class="button button2"/>
            </form>

            <?php if(!isset($_POST['cshow'])): ?>
                </div>
            <?php endif?>

            <?php if (isset($_POST['chide'])) {$showClist = false; } ?>

            <?php if ($showClist):
                    $competedListCommand = "SELECT id, pilot_id, race_id, start_position, finish_position FROM competed ORDER BY race_id";
                    $competedList = mysqli_query($joinsql, $competedListCommand) or die(mysqli_error($joinsql)); 
                ?>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>Pilóta neve</th>
                                <th>Verseny Helyszíne</th>      
                                <th>Kezdő pozíciója</th>      
                                <th>Végső pozíciója</th>
                                <th>Szerzett pontok</th>
                <?php endif ?>
                <?php if( ($showClist == true) && ($editCompeted == false) ): ?>
                            </tr>
                        </thead>
                        <tbody> 
                <?php endif ?>
                        <?php if( ($showClist == true) && ($editCompeted == false) ): while ($row = mysqli_fetch_array($competedList)): ?>
                            <?php
                                $pid = mysqli_real_escape_string($joinsql, $row['pilot_id']);
                                $rid = mysqli_real_escape_string($joinsql, $row['race_id']);
                                $getPNameCommand = sprintf("SELECT pilot_name FROM pilot WHERE id = '$pid'");
                                $getRNameCommand = sprintf("SELECT country FROM race WHERE id = '$rid'");
                                $pNameList = mysqli_query($joinsql, $getPNameCommand) or die(mysqli_error($joinsql));
                                $pName = mysqli_fetch_array($pNameList);
                                $rNameList = mysqli_query($joinsql, $getRNameCommand) or die(mysqli_error($joinsql));
                                $rName = mysqli_fetch_array($rNameList);
                                $pointsGain = countPointsGain($row['finish_position']);
                            ?>
                            <tr>
                                <td><?=$pName['pilot_name']?></td>
                                <td><?=$rName['country']?></td>
                                <td><?=$row['start_position']?></td>
                                <td><?=$row['finish_position']?></td>
                                <td><?=$pointsGain?></td>
                            </tr>           
                        <?php endwhile; ?>
                        <?php endif ?>
                        
                        <?php if( ($showClist == true) && ($editCompeted == true) ): ?>
                            <th>Szerkesztések</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </div>
                        <?php endif ?>

                        <?php if( ($showClist == true) && ($editCompeted == true) ): while ($row = mysqli_fetch_array($competedList)):  
                        ?>
                        <?php
                                $pid = mysqli_real_escape_string($joinsql, $row['pilot_id']);
                                $rid = mysqli_real_escape_string($joinsql, $row['race_id']);
                                $getPNameCommand = sprintf("SELECT pilot_name FROM pilot WHERE id = '$pid'");
                                $getRNameCommand = sprintf("SELECT country FROM race WHERE id = '$rid'");
                                $pNameList = mysqli_query($joinsql, $getPNameCommand) or die(mysqli_error($joinsql));
                                $pName = mysqli_fetch_array($pNameList);
                                $rNameList = mysqli_query($joinsql, $getRNameCommand) or die(mysqli_error($joinsql));
                                $rName = mysqli_fetch_array($rNameList);
                                $pointsGain = countPointsGain($row['finish_position']);
                            ?>
                            <tr>
                                <form method = "post" autocomplete="off">
                                    <input type="hidden" name="cid" value= <?=$row['id']?>>
                                    <td> 
                                        <div class="select"> 
                                        <select name = 'pilot_id'>
                                            <option value="<?=$row['pilot_id']?>"> <?=$pName['pilot_name']?> </option>
                                            <?php
                                                $listPilotsCommand = 'SELECT id, pilot_name FROM pilot ORDER BY pilot_name';
                                                $resultPlist = mysqli_query($joinsql, $listPilotsCommand) or die(mysqli_error($joinsql));
                                                while ($pilots = mysqli_fetch_array($resultPlist)):
                                                ?>
                                                <option value="<?=$pilots['id']?>"> <?=$pilots['pilot_name']?> </option>
                                            <?php endwhile; ?>
                                        </select>
                                        </div>
                                    </td>
                                    <td> 
                                        <div class="select"> 
                                        <select name = 'race_id'>
                                            <option value="<?=$row['race_id']?>"> <?=$rName['country']?> </option>
                                            <?php
                                                $listRacesCommand = 'SELECT id, country FROM race ORDER BY country';
                                                $resultRlist = mysqli_query($joinsql, $listRacesCommand) or die(mysqli_error($joinsql));
                                                while ($races = mysqli_fetch_array($resultRlist)):
                                                ?>
                                                <option value="<?=$races['id']?>"> <?=$races['country']?> </option>
                                            <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td> <input type = "number" name = "start_position" value = <?=$row['start_position']?> ></td>
                                    <td> <input type = "number" name = "finish_position" value = <?=$row['finish_position']?> ></td>
                                    <td> <input readonly type = "number" name = "pg" value = <?=$pointsGain?> ></td>
                                    <td> <input type = "submit" name = "editcompeted" value = "Szerkeszt" class="button button2"/>
                                        <input type = "submit" name = "deletecompeted" value = "Töröl" class="button button2"/> </td>
                                    <input type="hidden" name="oldfinpos" value= <?=$row['finish_position']?>>
                                </form>
                            </tr>                
                        <?php endwhile; ?>
                        <?php endif ?>
                        </tbody>
                    </table>
                </div>
                </div>
                    <?php
                        if (isset($_POST['editcompeted'])) {
 
                            $succesCompetedEdit = false;
                            $cid = mysqli_real_escape_string($joinsql, $_POST['cid']);
                            $pilot_id = mysqli_real_escape_string($joinsql, $_POST['pilot_id']);
                            $race_id = mysqli_real_escape_string($joinsql, $_POST['race_id']);
                            $startpos = mysqli_real_escape_string($joinsql, $_POST['start_position']);
                            $finpos = mysqli_real_escape_string($joinsql, $_POST['finish_position']);

                            if(check_competed($joinsql, $race_id, $pilot_id, $startpos, $finpos, 1, $cid)){
                                reSetPoints($pilot_id, $race_id, $_POST['oldfinpos'], $_POST['finish_position']);
                                $updateCompetedCommand = sprintf("UPDATE competed SET pilot_id='$pilot_id', race_id='$race_id', start_position='$startpos',
                                finish_position='$finpos' WHERE id='$cid'");
                                mysqli_query($joinsql, $updateCompetedCommand) or die(mysqli_error($joinsql));
                                $succesCompetedEdit = true;
                            }

                            if($succesCompetedEdit){
                                echo "<div class='success'>";
                                echo $_SESSION['cedit'];
                                unset($_SESSION['cedit']);
                                echo "</div>";
                            }
                            else {
                                echo "<div class='fail'>";
                                echo $_SESSION['editfail'];
                                unset($_SESSION['editfail']);
                                echo "</div>";
                            }
                        }
                        if (isset($_POST['deletecompeted'])) {
                            
                            $pilot_id = mysqli_real_escape_string($joinsql, $_POST['pilot_id']);
                            $race_id = mysqli_real_escape_string($joinsql, $_POST['race_id']);
                            $cid = mysqli_real_escape_string($joinsql, $_POST['cid']);
                            $succesCompetedDelete = false;
                            $deleteCompetedCommand = sprintf("DELETE FROM competed WHERE id = '$cid'");
                            
                            reSetPoints($pilot_id, $race_id, $_POST['oldfinpos'], $zeroPoint);
                           
                            mysqli_query($joinsql, $deleteCompetedCommand) or die(mysqli_error($joinsql));
                            $succesCompetedDelete = true;

                            if($succesCompetedDelete){
                                echo "<div class='success'>";
                                echo $_SESSION['cdelete'];
                                unset($_SESSION['cdelete']);
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