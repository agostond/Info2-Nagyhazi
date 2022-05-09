<!DOCTYPE html>
<?php
$joinsql = getDb();
?>
<html>
    <body>
        <?php $showPlist = false; $editPilot = false; ?>

            <form  method = "post">
                <input type = "submit" name = "pshow" value = "Pilóták mutatása" class="button button2"/>
                Szeretne adatokat szerkeszteni?
                <input type="radio" id ="igen" name="pedit" value= 1>
                <label for="igen">Igen</label>
                <input type="radio" id = "nem" name="pedit" value= 0 checked>
                <label for="nem">Nem</label>
            </form>
            <?php 
                if (isset($_POST['pshow'])) {
                    $showPlist = true;
                    $editPilot = $_POST['pedit'];
                } 
            ?>

            <form  method = "post">
                <input type = "submit" name = "phide" value = "Pilóták elrejtése" class="button button2"/>
            </form>
            <?php if (isset($_POST['phide'])) {$showRlist = false; } ?>

            <?php if ($showPlist):
                    $pilotListCommand = "SELECT id, pilot_name, nationality, birth_date, carnumber, points, wins, podiums, titles FROM pilot GROUP BY pilot_name";
                    $pilotList = mysqli_query($joinsql, $pilotListCommand) or die(mysqli_error($joinsql)); 
                ?>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>Neve</th>
                                <th>Nemzitsége</th>      
                                <th>Születési dátuma</th>      
                                <th>Rajtszáma</th>
                                <th>Pontjai</th>
                                <th>Győzelmei</th>
                                <th>Dobogós helyezései</th>
                                <th>Világbajnoki címei</th>
                <?php endif ?>
                <?php if( ($showPlist == true) && ($editPilot == false) ): ?>
                            </tr>
                        </thead>
                        <tbody> 
                <?php endif ?>
                        <?php if( ($showPlist == true) && ($editPilot == false) ): while ($row = mysqli_fetch_array($pilotList)): ?>     
                            <tr>
                                <td><?=$row['pilot_name']?></td>
                                <td><?=$row['nationality']?></td>
                                <td><?=$row['birth_date']?></td>
                                <td><?=$row['carnumber']?></td>
                                <td><?=$row['points']?></td>
                                <td><?=$row['wins']?></td>
                                <td><?=$row['podiums']?></td>
                                <td><?=$row['titles']?></td>
                            </tr>                
                        <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
                        <?php endif ?>

                        <?php if( ($showPlist == true) && ($editPilot == true) ): ?>
                            <th>Szerkesztések</th>
                            </tr> 
                        </thead>
                        <tbody>
                        <?php endif ?>
                        
                        <?php if( ($showPlist == true) && ($editPilot == true) ): while ($row = mysqli_fetch_array($pilotList)):  
                        ?>
                            <tr>
                                <form method = "post" autocomplete="off">
                                    <input type="hidden" name="pid" value= <?=$row['id']?>>
                                    <td> <input type = "text" name = "pilot_name" value = <?=$row['pilot_name']?> ></td>
                                    <td> <input type = "text" name = "nationality" value = <?=$row['nationality']?> ></td>
                                    <td> <input type = "date" name = "birth_date" value = <?=$row['birth_date']?> ></td>
                                    <td> <input type = "number" name = "carnumber" value = <?=$row['carnumber']?> ></td>
                                    <td> <input type = "number" name = "points" value = <?=$row['points']?> ></td>
                                    <td> <input type = "number" name = "wins" value = <?=$row['wins']?> ></td>
                                    <td> <input type = "number" name = "podiums" value = <?=$row['podiums']?> ></td>
                                    <td> <input type = "number" name = "titles" value = <?=$row['titles']?> ></td>
                                    <td> <input type = "submit" name = "editpilot" value = "Szerkeszt" class="button button2"/> 
                                        <input type = "submit" name = "deletepilot" value = "Töröl" class="button button2"/> </td>
                                </form>
                            </tr>                
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                        <?php endif ?>
                    <?php
                        if (isset($_POST['editpilot'])) {

                            $succesPilotEdit = false;
                            $pname = mysqli_real_escape_string($joinsql, $_POST['pilot_name']);
                            $nationality = mysqli_real_escape_string($joinsql, $_POST['nationality']);
                            $bdate = mysqli_real_escape_string($joinsql, $_POST['birth_date']);
                            $carnumber = mysqli_real_escape_string($joinsql, $_POST['carnumber']);
                            $points = mysqli_real_escape_string($joinsql, $_POST['points']);
                            $wins = mysqli_real_escape_string($joinsql, $_POST['wins']);
                            $podiums = mysqli_real_escape_string($joinsql, $_POST['podiums']);
                            $titles = mysqli_real_escape_string($joinsql, $_POST['titles']);
                            $pilot_id = mysqli_real_escape_string($joinsql, $_POST['pid']);

                            if(check_pilot($joinsql, $pname, $nationality, $bdate, $carnumber)){
                                $updatePilotCommand = sprintf("UPDATE pilot SET pilot_name='$pname', nationality='$nationality', birth_date='$bdate', carnumber='$carnumber', 
                                points='$points', wins = '$wins', podiums = '$podiums', titles = '$titles' WHERE id='$pilot_id'");
                                mysqli_query($joinsql, $updatePilotCommand) or die(mysqli_error($joinsql));
                                $succesPilotEdit = true;
                            }

                            if($succesPilotEdit){
                                echo "<div class='success'>";
                                echo $_SESSION['pedit'];
                                unset($_SESSION['pedit']);
                                echo "</div>";
                            }
                            else {
                                echo "<div class='fail'>";
                                echo $_SESSION['editfail'];
                                unset($_SESSION['editfail']);
                                echo "</div>";
                            }
                        }
                        if (isset($_POST['deletepilot'])) {
                            
                            $getCompetedcommand = "SELECT pilot_id FROM competed";
                            $competedId = mysqli_query($joinsql, $getCompetedcommand) or die(mysqli_error($joinsql));
                            $pilot_id = mysqli_real_escape_string($joinsql, $_POST['pid']);
                            
                            while($deleteComp = mysqli_fetch_array($competedId)){
                                if( $deleteComp['pilot_id'] == $pilot_id) {
                                    $DeleteCompCommand = "DELETE FROM competed WHERE pilot_id = '$pilot_id'";
                                    mysqli_query($joinsql, $DeleteCompCommand) or die(mysqli_error($joinsql));
                                }
                            }

                            $getTMemberCommand = "SELECT pilot_id FROM team_member";
                            $tMemberId = mysqli_query($joinsql, $getTMemberCommand) or die(mysqli_error($joinsql));
                            $pilot_id = mysqli_real_escape_string($joinsql, $_POST['pid']);
                            
                            while($deleteTMember = mysqli_fetch_array($tMemberId)){
                                if( $deleteTMember['pilot_id'] == $pilot_id) {
                                    $DeleteTMemberCommand = "DELETE FROM team_member WHERE pilot_id = '$pilot_id'";
                                    mysqli_query($joinsql, $DeleteTMemberCommand) or die(mysqli_error($joinsql));
                                }
                            }
                            
                            $succesPilotDelete = false;
                            $deletePilotCommand = sprintf("DELETE FROM pilot WHERE id = '$pilot_id'");
                           
                            mysqli_query($joinsql, $deletePilotCommand) or die(mysqli_error($joinsql));
                            $succesPilotDelete = true;

                            if($succesPilotDelete){
                                echo "<div class='success'>";
                                echo $_SESSION['pdelete'];
                                unset($_SESSION['pdelete']);
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