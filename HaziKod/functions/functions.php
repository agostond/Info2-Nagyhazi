<?php

$zeroPoint = 20;


function getDb() {
    $link = mysqli_connect("localhost", "root", "", "f1") 
            or die("Nem sikerült kapcsolódni az adatbázishoz: " . mysqli_error());
    mysqli_select_db($link, "f1");
    mysqli_query ($link, "set character_set_results='utf8'");
    mysqli_query ($link, "set character_set_client='utf8'");
    return $link;   
}

function countPointsGain($pos){
    $pointsGain = 0;
    switch($pos){
        case 1: {
            $pointsGain = 25;
            break;
        }
        case 2:{
            $pointsGain = 18;
            break;
        }
        case 3:{
            $pointsGain = 15;
            break;
        }
        case 4:{
            $pointsGain = 12;
            break;
        }
        case 5:{
            $pointsGain = 10;
            break;
        }
        case 6:{
            $pointsGain = 8;
            break;
        }
        case 7:{
            $pointsGain = 6;
            break;
        }
        case 8:{
            $pointsGain = 4;
            break;
        }
        case 9:{
            $pointsGain = 2;
            break;
        }
        case 10:{
            $pointsGain = 1;
            break;
        }
        default: {
            $pointsGain = 0;
        }

    }
    return $pointsGain;
}

function reSetPoints($pilot_id, $race_id, $oldpos, $newpos){
    $joinsql = getDb();

    $minusPoint = countPointsGain($oldpos);
    $plusPoint = countPointsGain($newpos);
    $getRaceDateCommand = sprintf("SELECT race_date FROM race where id = '$race_id'");
    $resultDate = mysqli_query($joinsql, $getRaceDateCommand) or die(mysqli_error($joinsql));
    $race_dateList = mysqli_fetch_array($resultDate);
    $race_date = $race_dateList['race_date'];

    $getPPointsCommand = sprintf("SELECT points FROM pilot where id = '$pilot_id'");
    $result = mysqli_query($joinsql, $getPPointsCommand) or die(mysqli_error($joinsql));
    $ppointsList = mysqli_fetch_array($result);
    $ppoints = $ppointsList['points'] + $plusPoint - $minusPoint;
    $addPPointCommand = sprintf("UPDATE pilot SET points = '$ppoints' WHERE id = '$pilot_id'");
    mysqli_query($joinsql, $addPPointCommand) or die(mysqli_error($joinsql));

    $getTeamCommand = sprintf("SELECT team_id, contract_started, contract_expired FROM team_member WHERE pilot_id = '$pilot_id'");
    $teamList = mysqli_query($joinsql, $getTeamCommand) or die(mysqli_error($joinsql));
    while ($teams = mysqli_fetch_array($teamList)){
        if($race_date >= $teams['contract_started'] && $race_date <= $teams['contract_expired']){
            $team_id = $teams['team_id'];
            $getTPointsCommand = sprintf("SELECT points FROM team WHERE id = '$team_id'");
            $result = mysqli_query($joinsql, $getTPointsCommand) or die(mysqli_error($joinsql));
            $tpointsList = mysqli_fetch_array($result);
            $tpoints = $tpointsList['points'] + $plusPoint - $minusPoint;
            $addTPointCommand = sprintf("UPDATE team SET points = '$tpoints' WHERE id = '$team_id'");
            mysqli_query($joinsql, $addTPointCommand) or die(mysqli_error($joinsql));
        }
    }
}

function closeDb($link) {
    mysqli_close($link);
}
?>