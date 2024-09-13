<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Hack</title>
<style type="text/css">
      body { text-align: center; padding: 150px; background-image: url("tmp.jpg"); }
      h1 { font-size: 40px; }
      body { font: 20px Helvetica, sans-serif; color: #333; }
      #article { display: block; text-align: center; width: 500px; margin: 0 auto; }
      a { color: #dc8100; text-decoration: none; }
      a:hover { color: #333; text-decoration: none; }
      .classic {
      background-color:#fff;
      }
      .cen {
          color: #9d0061
      }
      .blink {
        animation: blink 5s infinite;
      }
      @-moz-keyframes blink { 
        0%  { opacity:0; }
        20% { opacity:0; }
        21% { opacity:1; }
        40% { opacity:1; }
        41% { opacity:0; } 
        60% { opacity:1; }
        61% { opacity:0; } 
        80% { opacity:0; } 
        100%{ opacity:0; }
      }
    </style>
</head>
<?php
include '../php/properties.php';

$ip = '';
if( isset($_SERVER['HTTP_CLIENT_IP']) ) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} else if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else if (isset($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
}
if( isset($_GET['mail']) ) {
    
} else {

}

    $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
                $result = pg_prepare($dbconn, "sql", "INSERT INTO $hack_ (ip, mail) VALUES ( $1, $2 );");
                $result = pg_execute($dbconn, "sql", array($ip, $_GET['mail'])) or die ('Connexion impossible :'. pg_last_error());
    pg_close($dbconn);

?>
<body>

<div id="article">
<h2><span class="cen blink"><bold>Thank You ! <?php echo ' '.$ip ;?></bold></span></h2>
</div>
</html>
<script type="text/javascript">


$(document).ready(function() {

    setTimeout(() => {
        document.location.href = 'http://cen-normandie.fr';
    }, 10000);

});


</script>