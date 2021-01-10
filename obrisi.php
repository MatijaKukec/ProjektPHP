<?php
session_start();
if (!isset($_SESSION['userUid'])) {
	header('Location: login.php');
} else if(!isset($_GET['id'])){
    header('Location:svi_korisnici.php');
} else {
    $id=$_GET['id'];
    require_once('baza.php');
    $izjava=$veza->prepare('DELETE FROM korisnici WHERE id=?');
    $izjava->bind_param('d',$id); 
    if($izjava->execute())
    {
        header('Location:svi_korisnici.php?obrisano=da');
    }  
    else
    {
        header('Location:svi_korisnici.php?obrisano=ne');
    }
}
?>
 
    
