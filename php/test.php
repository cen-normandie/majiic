<?php
$domain = 'cren-haute-normandie';
if(checkdnsrr($domain)) {
     // Domain at least has an MX record, necessary to receive email
     echo 'Enregistrement MX ok pour le domaine : '.$domain.'</br>';
    } else {
        echo 'Enregistrement MX Inexistant pour le domaine : '.$domain.'</br>';
}

$domain = 'cen-normandie.fr';
if(checkdnsrr($domain)) {
    // Domain at least has an MX record, necessary to receive email
    echo 'Enregistrement MX ok pour le domaine : '.$domain.'</br>';
} else {
    echo 'Enregistrement MX Inexistant pour le domaine : '.$domain.'</br>';
}
$domain = 'gmail.com';
if(checkdnsrr($domain)) {
    // Domain at least has an MX record, necessary to receive email
    echo 'Enregistrement MX ok pour le domaine : '.$domain.'</br>';
} else {
    echo 'Enregistrement MX Inexistant pour le domaine : '.$domain.'</br>';
}
?>