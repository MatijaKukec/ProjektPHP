<?php if (isset($_SESSION['userUid'])) header('Location: ./index.php'); ?>
<?php $title = "Registracija"; require_once('header.php'); require_once ('navbar.php');?>

  <h1 style="text-align: center;">Registriraj se</h1>

<?php 

require_once 'PFBC/Form.php';
require_once 'baza.php';
session_start();

if(Form::isValid('novi_korisnik', false)){

    if($_POST['lozinka']==$_POST['ponovljeno']){
        $pass = $_POST['lozinka'];
        $pass_enc = password_hash($pass, PASSWORD_DEFAULT);
        $korisnik=htmlentities(trim($_POST['korisnik']));

        $query=$veza->prepare('SELECT*FROM korisnici WHERE korisnik=? LIMIT 1');
            
        $query->bind_param('s', $korisnik);
        $query->execute();
        $postoji = $query->fetch();

        if($postoji){
            Form::setError("unos", "Korisnik već postoji u  bazi podataka");
            }
        else{     
            $priprema=$veza->prepare('INSERT INTO korisnici (korisnik, lozinka) VALUES (?, ?)');
            $priprema->bind_param('ss', $korisnik, $pass_enc);
            if($priprema->execute()){
                echo '<div class="alert alert-success">Uspješan unos u bazu!</div>';
                Form::clearValues('unos');
                }
            }
        }
    }
else{
    Form::setError("novi_korisnik", "Lozinke se ne podudaraju");
}


$query="SELECT * FROM korisnici";
$redci=$veza->query($query);
echo "<table><tr><td>Ime</td><td>Lozinka</td><td>id</td></tr>";
while ($rez=$redci->fetch_object()) {
    echo "<tr><td>" . $rez->korisnik ."</td><td>" . $rez->lozinka."</td><td>" . $rez->id ;
}
echo "</table>";


if(!isset($_POST['provjera'])) Form::clearErrors('novi_korisnik');
echo '<legend>Kreiraj novog korisnika</legend>';
Form::open('novi_korisnik', '', array('view'=>'SideBySide4'));
Form::Hidden('provjera','provjereno');
Form::Textbox('Korisnik:', 'korisnik', array('required'=>1, 'validation'=>new Validation_RegExp('/^[A-ZČĆŠĐŽÀ-ÿ][a-zčćđšž\.\-\ ]{3,20}$/i',
"%element% mora sadržavati ispravne znakove.
Koriste se samo slova... Ostali znakovi interpunkcije nisu dozvoljeni.")));
Form::Password('Lozinka:', 'lozinka', array('required'=>1));
Form::Password('Ponovite lozinku:', 'ponovljeno', array('required'=>1));
Form::Button('Uredi korisnika');
Form::close(false);


echo "<script> document.getElementById('reg').classList.add('active'); document.getElementById('navbardrop').classList.add('active'); 
</script>"; 

?>
<?php 
require_once("footer.php");
?>