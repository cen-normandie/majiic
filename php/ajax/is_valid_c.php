<?php
session_start();

if(isset($_POST["vara"])) {
    if($_POST["vara"] == $_SESSION["n_n"]) {
        echo 'true';
    } else {
        echo 'false '.$_SESSION["n_n"];
    };
}

?>