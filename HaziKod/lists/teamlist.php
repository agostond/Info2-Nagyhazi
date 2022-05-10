<!DOCTYPE html>
<?php
$joinsql = getDb();
?>
<html>
    <body>
        <br>
        --------------------
        <?php $showTlist = false; $editTeam = false; ?>

            <form  method = "post">
                <?php 
                    if(!isset($_POST['tns'])){
                        $_POST['tns'] = "";
                    } 
                ?>
                <input type="search" name="tns" value = "<?=$_POST['tns']?>" placeholder="Csapat nevére"/>
                <input type="submit" name = "t_search" value="Keres" class="button button2"/>
                <input type = "submit" name = "tshow" value = "Összes csapat mutatása" class="button button2"/>
                Szeretne adatokat szerkeszteni?
                <input type="radio" id ="igen" name="tedit" value= 1>
                <label for="igen">Igen</label>
                <input type="radio" id = "nem" name="tedit" value= 0 checked>
                <label for="nem">Nem</label>
            </form>
            <?php 
                if (isset($_POST['tshow']) || isset($_POST['t_search'])) {
                    $showTlist = true;
                    $editTeam = $_POST['tedit'];
                } 
            ?>

            <form  method = "post">
                <input type = "submit" name = "thide" value = "Csapatok elrejtése" class="button button2"/>
            </form>
            <?php if (isset($_POST['thide'])) {$showTlist = false; } ?>

            <?php if ($showTlist):
                    $teamListCommand = "SELECT id, brand_name, points, money_spent, nationality, titles FROM team";
                    if (isset($_POST['t_search'])) {
                        $teamListCommand = $teamListCommand . sprintf(" WHERE LOWER(brand_name) LIKE '%%%s%%'", mysqli_real_escape_string($joinsql, strtolower($_POST['tns'])));
                    }
                    $teamList = mysqli_query($joinsql, $teamListCommand) or die(mysqli_error($joinsql)); 
                ?>
                <div class="table-wrapper">
                    <table class="fl-table">
                        <thead>
                            <tr>
                                <th>Neve</th>
                                <th>Székhelye</th>      
                                <th>Pontjai</th>      
                                <th>Világbajnoki címek</th>
                                <th>Elköltött Pénz (Millió dollár)</th>
                <?php endif ?>
                <?php if( ($showTlist == true) && ($editTeam == false) ): ?>
                            </tr>
                        </thead>
                        <tbody> 
                <?php endif ?>
                        <?php if( ($showTlist == true) && ($editTeam == false) ): while ($row = mysqli_fetch_array($teamList)): ?>
                            <tr>
                                <td><?=$row['brand_name']?></td>
                                <td><?=$row['nationality']?></td>
                                <td><?=$row['points']?></td>
                                <td><?=$row['titles']?></td>
                                <td><?=$row['money_spent']?></td>
                            </tr>                
                        <?php endwhile; ?>
                        <?php endif ?>

                        <?php if( ($showTlist == true) && ($editTeam == true) ): ?>
                            <th>Szerkesztések</th>
                            </tr> 
                        </thead>
                        <tbody>
                        <?php endif ?>
                        <?php if( ($showTlist == true) && ($editTeam == true) ): while ($row = mysqli_fetch_array($teamList)):  
                        ?>
                            <tr>
                                <form method = "post" autocomplete="off">
                                    <input type="hidden" name="tid" value= <?=$row['id']?>>
                                    <td> <input type = "text" name = "brand_name" value = <?=$row['brand_name']?> ></td>
                                    <td> <input type = "text" name = "nationality" value = <?=$row['nationality']?> ></td>
                                    <td> <input type = "number" name = "points" value = <?=$row['points']?> ></td>
                                    <td> <input type = "number" name = "titles" value = <?=$row['titles']?> ></td>
                                    <td> <input type = "float" name = "money_spent" value = <?=$row['money_spent']?> ></td>
                                    <td> <input type = "submit" name = "editteam" value = "Szerkeszt" class="button button2"/>
                                        <input type = "submit" name = "deleteteam" value = "Töröl" class="button button2"/> </td>
                                </form>
                            </tr>                
                        <?php endwhile; ?>
                        <?php endif ?>
                        </tbody>
                    </table>
                </div>
                    <?php
                        if (isset($_POST['editteam'])) {

                            $succesTeamEdit = false;
                            $bname = mysqli_real_escape_string($joinsql, $_POST['brand_name']);
                            $nationality = mysqli_real_escape_string($joinsql, $_POST['nationality']);
                            $points = mysqli_real_escape_string($joinsql, $_POST['points']);
                            $money_spent = mysqli_real_escape_string($joinsql, $_POST['money_spent']);
                            $titles = mysqli_real_escape_string($joinsql, $_POST['titles']);
                            $team_id = mysqli_real_escape_string($joinsql, $_POST['tid']);
                            
                            if(check_team($joinsql, $bname, $nationality)){
                                $updateTeamCommand = sprintf("UPDATE team SET brand_name='$bname', nationality='$nationality', points='$points', titles = '$titles', money_spent = '$money_spent' WHERE id='$team_id'");
                                mysqli_query($joinsql, $updateTeamCommand) or die(mysqli_error($joinsql));
                                $succesTeamEdit = true;
                            }

                            if($succesTeamEdit){
                                echo "<div class='success'>";
                                echo $_SESSION['tedit'];
                                unset($_SESSION['tedit']);
                                echo "</div>";
                            }
                            else {
                                echo "<div class='fail'>";
                                echo $_SESSION['editfail'];
                                unset($_SESSION['editfail']);
                                echo "</div>";
                            }
                        }
                        if (isset($_POST['deleteteam'])) {
                            
                            $getTMemberCommand = "SELECT team_id FROM team_member";
                            $tMemberId = mysqli_query($joinsql, $getTMemberCommand) or die(mysqli_error($joinsql));
                            $team_id = mysqli_real_escape_string($joinsql, $_POST['tid']);
                            
                            while($deleteTMember = mysqli_fetch_array($tMemberId)){
                                if( $deleteTMember['team_id'] == $team_id) {
                                    $DeleteTMemberCommand = "DELETE FROM team_member WHERE team_id = '$team_id'";
                                    mysqli_query($joinsql, $DeleteTMemberCommand) or die(mysqli_error($joinsql));
                                }
                            }
                            
                            $succesTeamDelete = false;
                            $deleteTeamCommand = sprintf("DELETE FROM team WHERE id = '$team_id'");
                           
                            mysqli_query($joinsql, $deleteTeamCommand) or die(mysqli_error($joinsql));
                            $succesTeamDelete = true;

                            if($succesTeamDelete){
                                echo "<div class='success'>";
                                echo $_SESSION['tdelete'];
                                unset($_SESSION['tdelete']);
                                echo "</div'>";
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