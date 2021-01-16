<?php

$veza=new mysqli ('localhost', 'adminphp', 'adminphp7', 'logiranje');
    if ($veza->connect_errno){
        echo "Greška s povezivanjem na bazu " . $veza->connect_error;
        die();
    }

$veza->set_charset('utf8');

?>