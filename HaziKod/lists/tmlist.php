<!DOCTYPE html>
<?php
$joinsql = getDb();
?>
<html>
    <br>
    --------------------
    <body>
        <?php $showMlist = false; $editMembership = false; ?>

            <form  method = "post">
                <?php 
                    if(!isset($_POST['ts'])){
                        $_POST['ts'] = "";
                    } 
                ?> 
                <div class="select"> 
                    <select name = 'tsearchtype'>
                        <option value='pilot_name'>Pilóta nevére</option>
                        <option value='brand_name'>Csapat nevére</option>
                    </select> 
                </div>
                <input type="search" name="ts" value = "<?=$_POST['ts']?>" placeholder="Keresés..."/>
                <input type="submit" name = "t_search" value="Keres" class="button button2"/>
                <input type = "submit" name = "mshow" value = "Összes szerződés mutatása" class="button button2"/>
                Szeretne adatokat szerkeszteni?
                <input type="radio" id ="igen" name="medit" value= 1>
                <label for="igen">Igen</label>
                <input type="radio" id = "nem" name="medit" value= 0 checked>
                <label for="nem">Nem</label>
            </form>
            <?php 
                if (isset($_POST['mshow']) || isset($_POST['t_search'])) {
                    $showMlist = true;
                    $editMembership = $_POST['medit'];
                } 
            ?>

            <form  method = "post">
                <input type = "submit" name = "mhide" value = "Szerződések elrejtése" class="button button2"/>
            </form>

            <?php if(!isset($_POST['mshow'])): ?>
                </div>
            <?php endif?>

            <?php if (isset($_POST['mhide'])) {$showMlist = false; } ?>

            <?php if ($showMlist):
                    $membershipListCommand = "SELECT team_member.id as tid, pilot_id, team_id, contract_started, contract_expired FROM team_member INNER JOIN pilot ON pilot.id = pilot_id INNER JOIN team ON team.id = team_id";
                    if (isset($_POST['t_search'])) {
                        $st = $_POST['tsearchtype'];
                        $membershipListCommand = $membershipListCommand . sprintf(" WHERE LOWER($st) LIKE '%%%s%%'", mysqli_real_escape_string($joinsql, strtolower($_POST['ts'])));
                    }
                    $membershipList = mysqli_query($joinsql, $membershipListCommand) or die(mysqli_error($joinsql)); 
                ?>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>Pilóta neve</th>
                                <th>Csapat neve</th>      
                                <th>Szerződés kezdete</th>      
                                <th>Szerződés vége</th>
                <?php endif ?>
                <?php if( ($showMlist == true) && ($editMembership == false) ): ?>
                            </tr>
                        </thead>
                        <tbody> 
                <?php endif ?>
                        <?php if( ($showMlist == true) && ($editMembership == false) ): while ($row = mysqli_fetch_array($membershipList)): ?>
                            <?php
                                $pid = mysqli_real_escape_string($joinsql, $row['pilot_id']);
                                $tid = mysqli_real_escape_string($joinsql, $row['team_id']);
                                $getPNameCommand = sprintf("SELECT pilot_name FROM pilot WHERE id = '$pid'");
                                $getTNameCommand = sprintf("SELECT brand_name FROM team WHERE id = '$tid'");
                                $pNameList = mysqli_query($joinsql, $getPNameCommand) or die(mysqli_error($joinsql));
                                $pName = mysqli_fetch_array($pNameList);
                                $tNameList = mysqli_query($joinsql, $getTNameCommand) or die(mysqli_error($joinsql));
                                $tName = mysqli_fetch_array($tNameList);
                            ?>
                            <tr>
                                <td><?=$pName['pilot_name']?></td>
                                <td><?=$tName['brand_name']?></td>
                                <td><?=$row['contract_started']?></td>
                                <td><?=$row['contract_expired']?></td>
                            </tr>                
                        <?php endwhile; ?>
                        <?php endif ?>

                        <?php if(($showMlist == true) && ($editMembership == true) ): ?>
                            <th>Szerkesztések</th>
                            </tr> 
                        </thead>
                        <tbody>
                        </div>
                        <?php endif ?>

                        <?php if( ($showMlist == true) && ($editMembership == true) ): while ($row = mysqli_fetch_array($membershipList)):  
                        ?>
                        <?php
                                $pid = mysqli_real_escape_string($joinsql, $row['pilot_id']);
                                $tid = mysqli_real_escape_string($joinsql, $row['team_id']);
                                $getPNameCommand = sprintf("SELECT pilot_name FROM pilot WHERE id = '$pid'");
                                $getTNameCommand = sprintf("SELECT brand_name FROM team WHERE id = '$tid'");
                                $pNameList = mysqli_query($joinsql, $getPNameCommand) or die(mysqli_error($joinsql));
                                $pName = mysqli_fetch_array($pNameList);
                                $tNameList = mysqli_query($joinsql, $getTNameCommand) or die(mysqli_error($joinsql));
                                $tName = mysqli_fetch_array($tNameList);
                            ?>
                            <tr>
                                <form method = "post" autocomplete="off">
                                    <input type="hidden" name="mid" value= <?=$row['tid']?>>
                                    <td> 
                                        <div class="select"> 
                                        <select name = 'pilot_id'>
                                            <option value="<?=$row['pilot_id']?>"> <?=$pName['pilot_name']?> </option>
                                            <?php
                                                $listPilotsCommand = 'SELECT id, pilot_name FROM pilot';
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
                                        <select name = 'team_id'>
                                            <option value="<?=$row['team_id']?>"> <?=$tName['brand_name']?> </option>
                                            <?php
                                                $listTeamsCommand = 'SELECT id, brand_name FROM team';
                                                $resultTlist = mysqli_query($joinsql, $listTeamsCommand) or die(mysqli_error($joinsql));
                                                while ($teams = mysqli_fetch_array($resultTlist)):
                                                ?>
                                                <option value="<?=$teams['id']?>"> <?=$teams['brand_name']?> </option>
                                            <?php endwhile; ?>
                                            </select>
                                            </div>
                                    </td>
                                    <td> <input type = "date" name = "cstart" value = <?=$row['contract_started']?> ></td>
                                    <td> <input type = "date" name = "cfin" value = <?=$row['contract_expired']?> ></td>
                                    <td> <input type = "submit" name = "editmembership" value = "Szerkeszt" class="button button2"/>
                                        <input type = "submit" name = "deletemembership" value = "Töröl" class="button button2"/> </td>
                                </form>
                            </tr>                
                        <?php endwhile; ?>
                        <?php endif ?>
                        </tbody>
                    </table>
                </div>
                </div>
                    <?php
                        if (isset($_POST['editmembership'])) {
 
                            $succesMembershipEdit = false;
                            $mid = mysqli_real_escape_string($joinsql, $_POST['mid']);
                            $pilot_id = mysqli_real_escape_string($joinsql, $_POST['pilot_id']);
                            $team_id = mysqli_real_escape_string($joinsql, $_POST['team_id']);
                            $cstart = mysqli_real_escape_string($joinsql, $_POST['cstart']);
                            $cfin = mysqli_real_escape_string($joinsql, $_POST['cfin']);

                            if(check_tm($joinsql, $team_id, $pilot_id, $cstart, $cfin, $mid)){
                                $updateMembershipCommand = sprintf("UPDATE team_member SET pilot_id='$pilot_id', team_id='$team_id', contract_started='$cstart',
                                contract_expired='$cfin' WHERE id='$mid'");
                                mysqli_query($joinsql, $updateMembershipCommand) or die(mysqli_error($joinsql));
                                $succesMembershipEdit = true;
                            }

                            if($succesMembershipEdit){
                                echo "<div class='success'>";
                                echo $_SESSION['medit'];
                                unset($_SESSION['medit']);
                                echo "</div>";
                            }
                            else {
                                echo "<div class='fail'>";
                                echo $_SESSION['editfail'];
                                unset($_SESSION['editfail']);
                                echo "</div>";
                            }
                        }
                        if (isset($_POST['deletemembership'])) {
                            
                            $mid = mysqli_real_escape_string($joinsql, $_POST['mid']);
                            $succesMembershipDelete = false;
                            $deleteMembershipCommand = sprintf("DELETE FROM team_member WHERE id = '$mid'");
                           
                            mysqli_query($joinsql, $deleteMembershipCommand) or die(mysqli_error($joinsql));
                            $succesMembershipDelete = true;

                            if($succesMembershipDelete){
                                echo "<div class='success'>";
                                echo $_SESSION['mdelete'];
                                unset($_SESSION['mdelete']);
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