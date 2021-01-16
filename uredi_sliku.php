<?php
session_start();
if(!isset($_SESSION['userUid'])){
    header('Location:login.php');
} else if (!isset($_POST['id'])){
    header('Location:svi_korisnici.php');
}
    $title="Izmjena slike";
    include('header.php');
    include('navbar.php');
    echo "<script>
        document.getElementById('korisnici').classList.add('active);
        </script>";
    echo '<div class="forma"><h2>Izmijeni sliku korisnika</h2>';

    require_once 'PFBC/Form.php';

    if (isset($_POST['predano'])){


    } else {
        $id=$_POST['id'];
        require_once('baza.php');
        $izjava=$veza->prepare('SELECT * FROM korisnici WHERE id=? LIMIT 1');
        $izjava->bind_param('d',$id);
        if($izjava->execute()){
                $rezultat=$izjava->get_result();
                $redak=$rezultat->fetch_assoc();
                $korisnik=$redak['korisnik'];
                $staraSlika=$redak['avatar'];
        }
        Form::clearErrors('slikaKorisnik');
        Form::clearValues('slikaKorisnik');
    }
    echo"<p>izmjena slike korisnika: " . $korisnik . "<p>";
    echo"<p><img src='slike/" . $staraSlika . "' /></p>";

        if(isset($_POST['predano'])){
            $id=$_POST['id'];
            $staraSlika=$_POST['avatar'];
            $korisnik=$_POST['korisnik'];
            if(Form::isValid("slikaKorisnik",false)){

            }
        }else{

    Form::open ('slikaKorisnik', '', array("enctype"=>"multipart/form-data"));
    Form::Hidden ('predano', 'da');
    Form::Hidden ('id',$id);
    Form::Hidden ('korisnik',$korisnik);
    Form::Hidden ('avatar',$staraSlika);
    Form::File ("Avatar","slika",array("required"=>1));
    Form::Button ('Izmijeni sliku korisnika');
    Form::Button ('Odustani', 'button', array("onclick"=>"location.href='svi_korisnici.php';"));
    Form::close (false);
    echo"/div>";
    include('footer.php');

        }
?>