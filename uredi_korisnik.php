<?php if (isset($_SESSION['userUid'])) header('Location: ./index.php'); ?>
<?php $title = "Uređivanje"; require_once('header.php'); require_once ('navbar.php');?>

  <h1 >Uredi korisnika</h1>

<?php 

require_once 'PFBC/Form.php';
require_once 'baza.php';
session_start();

if(Form::isValid("uredi_korisnik", false)){
    $ime=htmlentities(trim($_POST['ime']));
    $id=htmlentities(trim($_SESSION['IDuredi']));
    //$pass = $_POST['lozinka'];

    if(isset($_POST['ime'])){
        $provjera = $veza->prepare("SELECT * FROM korisnici WHERE korisnik=? AND id<>? LIMIT 1");
        $provjera->bind_param('si', $ime, $id);
        $provjera->execute();
        $postoji = $provjera->fetch();
        if (!$postoji){
            $ime = $_POST['ime'];

            $upit = $veza->prepare("SELECT * FROM korisnici WHERE korisnik=? AND id=? LIMIT 1");
            $upit->bind_param('si', $ime, $id);
            $upit->execute();
            $provjera = $upit->fetch();
            
            if (!$provjera){
                if(!isset($_POST['lozinka'])){
                    $query=$veza->prepare('UPDATE korisnici SET korisnik=? WHERE id=?');
                    $query->bind_param('si', $ime, $id);
                    izvrši($query);
                }
                else if(isset($_POST['lozinka'])){
                    if($_POST['lozinka']==$_POST['ponovljeno']){
                        $pass = $_POST['lozinka'];
                        $query=$veza->prepare('UPDATE korisnici SET korisnik=?, lozinka=? WHERE id=?');
                        $pass_enc=password_hash($pass, PASSWORD_DEFAULT);
                        $query->bind_param('ssi', $ime, $pass_enc, $id);
                        izvrši($query);
                    }
                    else Form::setError("novi_korisnik", "Lozinke se ne podudaraju");
                }
            }
            else{
                echo '<div class="alert alert-success">Ime je isto.</div>';
                if(isset($_POST['lozinka'])){
                    if($_POST['lozinka']==$_POST['ponovljeno']){
                        $pass = $_POST['lozinka'];
                        $query=$veza->prepare('UPDATE korisnici SET lozinka=? WHERE id=?');
                        $pass_enc=password_hash($pass, PASSWORD_DEFAULT);
                        $query->bind_param('si', $pass_enc, $id);
                        izvrši($query);
                    }
                    else Form::setError("novi_korisnik", "Lozinke se ne podudaraju");
                }
            }
        }
        else Form::setError("uredi_korisnik","Korisnik već postoji u bazi podataka!");
    }
    else if(!isset($_POST['ime'])){
        echo "Ime nije postavljeno";
    }
}
else Form::setError("uredi_korisnik","Loš unos!");


if(!isset($_POST['uredeno'])) {
    Form::clearErrors('uredi_korisnik');
    if(isset($_POST["id"])){
        $id=$_POST["id"];
        $vadiId=$veza->prepare('SELECT korisnik FROM korisnici WHERE id=?');
        $vadiId->bind_param('i', $id);
        $vadiId->execute();
        $rez = $vadiId->get_result();
        $rezultat = $rez -> fetch_assoc();
        $korisnik=$rezultat['korisnik'];
        echo '<legend>Uredi korisnika - '. $korisnik .'</legend>';
        $_SESSION['IDuredi']=$id;
    }
}
else echo '<legend>Uredi korisnika </legend>';

Form::open('uredi_korisnik', '', array('view'=>'SideBySide4'));
Form::Hidden('uredeno','uredivanje');
Form::Textbox('Korisnik: ', 'ime', array("required"=>1, "validation"=>new Validation_RegExp("/^[a-zčćđšž\.\-\ ]{3,20}$/i","%element% mora sadržavati minimalno 3 znakova. Koriste se samo slova... Ostali znakovi interpunkcije nisu dozvoljeni")));
Form::Password('Lozinka:', 'lozinka', array("validation"=>new Validation_RegExp("/^[a-zčćđšž\.\-\d\ ]{6,20}$/i","%element% mora sadržavati minimalno 6 znakova. Koriste se samo slova i brojke te _, - i .. Ostali znakovi interpunkcije nisu dozvoljeni.")));
Form::Password('Ponovite lozinku:', 'ponovljeno');
Form::Button('Uredi');
Form::Button ("Odustani", "reset");
Form::close(false);

function izvrši($query){
    if($query->execute()){
        echo '<div class="alert alert-success">Uspješno uređivanje korisnika!</div>';
        Form::clearValues('uredi_korisnik');
        return $query->affected_rows;
    }
    else Form::setError("uredi_korisnik","Neuspješno uređivanje!");
}

?>

<?php 
include ("footer.php");
?>