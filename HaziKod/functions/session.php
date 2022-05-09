<?php
    session_start();

    $_SESSION['maddsucces'] = "Elköltött pénz sikeresen hozzáadva!";
    $_SESSION['maddfail'] = "Hiba történt az összeg hozzáadása közben!";

    $_SESSION['tsucces'] = "Új csapat felvéve!";
    $_SESSION['tedit'] = "Csapat módosítva!";
    $_SESSION['tdelete'] = "Csapat törölve!";

    $_SESSION['psucces'] = "Új pilóta felvéve!";
    $_SESSION['pedit'] = "Pilóta módosítva!";
    $_SESSION['pdelete'] = "Pilóta törölve!";

    $_SESSION['rsucces'] = "Új verseny felvéve!";
    $_SESSION['redit'] = "Verseny módosítva!";
    $_SESSION['rdelete'] = "Verseny törölve!";

    $_SESSION['msucces'] = "Új szerződés felvéve!";
    $_SESSION['medit'] = "Szerződés módosítva!";
    $_SESSION['mdelete'] = "Szerződés törölve!";

    $_SESSION['csucces'] = "Új versenyeredmény felvéve!";
    $_SESSION['cedit'] = "Versenyeredmény módosítva!";
    $_SESSION['cdelete'] = "Versenyeredmény törölve!";

    $_SESSION['connectfail'] = "Hiba történt a hozzárendelés közben!";
    $_SESSION['addfail'] = "Hiba történt az új elem felvétele közben!";
    $_SESSION['editfail'] = "Hiba történt a módosítás közben!";
    $_SESSION['deletefail'] = "Hiba történt a törlés közben!";

    function padd_error(){
        echo "<div class='fail'>";
        echo $_SESSION['addfail'];
        unset($_SESSION['addfail']);
        echo "</div>";
        die;
    }
?>