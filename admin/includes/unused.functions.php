<?php
    function finalized($uid, $mysqli)
    {
        $query = "SELECT finalized FROM fertig WHERE uid = '$uid'";
        $result = $mysqli->query($query);
        if ($result->num_rows >= 1) {
            return true;
        } else {
            return false;
        }
    }
    ?>
