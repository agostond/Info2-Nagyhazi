<?php


    function check_team($joinsql, $bname, $nat){
        $empty = "";
        if($nat == $empty || $bname == $empty){
            return 0;
        }
        $querry = "SELECT brand_name FROM team";
        $qList = mysqli_query($joinsql, $querry) or die(mysqli_error($joinsql));
        while ($row = mysqli_fetch_array($qList)){
            if($row['brand_name'] == $bname){
                return 0;
            }
        }
        return 1;
    }

    function check_race($joinsql, $country, $rdate, $length, $turns){
        $empty = "";
        if($country == $empty || $rdate == $empty || $length == $empty || $turns == $empty){
            return 0;
        }
        $querry = "SELECT country FROM race";
        $qList = mysqli_query($joinsql, $querry) or die(mysqli_error($joinsql));
        while ($row = mysqli_fetch_array($qList)){
            if($row['country'] == $country){
                return 0;
            }
        }
        return 1;
    }

    function check_pilot($joinsql, $pname, $nationality, $bdate, $carnumber){
        $empty = "";
        if($pname == $empty || $nationality == $empty || $bdate == $empty || $carnumber == $empty){
            return 0;
        }
        $querry = "SELECT pilot_name FROM pilot";
        $qList = mysqli_query($joinsql, $querry) or die(mysqli_error($joinsql));
        while ($row = mysqli_fetch_array($qList)){
            if($row['pilot_name'] == $pname){
                return 0;
            }
        }
        return 1;
    }

    function check_competed($joinsql, $raceid, $pilotid, $start_pos, $fin_pos, $edit, $cid = -1){  //A $cid-et csak szerekesztéskor kell megadni, ez a versenyeredmény saját id-je
        $empty = "";                                                                                // Az edit értéke 1 szerkesztésnél, 0 létrehozásnál
        if($start_pos == $empty || $fin_pos == $empty){
            return 0;
        }
        $querry = "SELECT id, pilot_id, race_id, start_position, finish_position FROM competed";
        $qList = mysqli_query($joinsql, $querry) or die(mysqli_error($joinsql));
        while ($row = mysqli_fetch_array($qList)){
            if($row['pilot_id'] == $pilotid && $row['race_id'] == $raceid && $edit == 0){
                return 0;
            }
            if($row['race_id'] == $raceid && $row['start_position'] == $start_pos && $row['id'] != $cid){
                return 0;
            }
            if($row['race_id'] == $raceid && $row['finish_position'] == $fin_pos && $row['id'] != $cid){
                return 0;
            }
        }
        return 1;
    }

    function check_tm($joinsql, $team_id, $pilot_id, $cstart, $cfin, $mid = -1){ //A $mid-et csak szerekesztéskor kell megadni, ez a szerződés saját id-je
        $empty = "";                                                                   
        if($cstart == $empty || $cfin == $empty){
            return 0;
        }
        if($cstart > $cfin){
            return 0;
        }
        $querry = "SELECT id, pilot_id, team_id, contract_started, contract_expired FROM team_member";
        $qList = mysqli_query($joinsql, $querry) or die(mysqli_error($joinsql));
        while ($row = mysqli_fetch_array($qList)){
            if($row['pilot_id'] == $pilot_id && $row['contract_started'] < $cstart && $row['contract_expired'] > $cstart && $row['id'] != $mid){
                return 0;
            }
            if($row['pilot_id'] == $pilot_id && $row['contract_started'] < $cfin && $row['contract_expired'] > $cfin && $row['id'] != $mid){
                return 0;
            }
            if($row['pilot_id'] == $pilot_id && $row['contract_started'] > $cstart && $row['contract_expired'] < $cfin && $row['id'] != $mid){
                return 0;
            }
        }
        return 1;
    }

    function check_money($money){
        $empty = "";                                                                   
        if($money == $empty){
            return 0;
        }
        return 1;
    }
?>