<?php 

if (isset($_SESSION['userUid'])) header('Location: ./index.php'); 
$title = "Registracija";

require_once ('header.php');
require_once ('navbar.php');
require_once 'PFBC/Form.php';
require_once 'baza.php';

echo '<h1 style="text-align: center;">Registriraj se</h1>';

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

        if(!$postoji){

            //provjera za slike
            require_once( 'crop.php');
            //potrebrno je odrediti koji su dozvoljeni tipovi podataka, u našem primjeru
            //korisniku dozvoljavamo samo slikovne datoteke pa smo zadali te MIME tipove podataka
            $dozvoljeni_MIME=array("image/jpeg", "image/gif", "image/png", "image/bmp");
            //provjeravamo da tip podataka nije prazan- da je korisnik nešto predao, a
            //s in_array funkcijom provjeravamo da Li tip datoteke odgovara zadanim mime tipovima
            if(!empty ($_FILES['slika']['type'])&&!in_array($_FILES['slika']['type'], $dozvoljeni_MIME)) {
                Form::setError("novi_korisnik", "Niste odabrali ispravan tip datoteke!!! (gif, jpeg ili png) ");
            } else {
                //ako je korisnik predao ispravnu datoteku, s ispravnim tipom podataka
                //provjeravamo da li je nastala neka pogreška prilikom samog upload-a
                $greska = $_FILES['slika']['error'];
                $upload_greske = array(
                    UPLOAD_ERR_OK => "Datoteka je uspješno predana",
                    UPLOAD_ERR_INI_SIZE =>"Datoteka je prevelika",
                    UPLOAD_ERR_FORM_SIZE => "Datoteka je prevelika",
                    UPLOAD_ERR_PARTIAL => "Partial upload." ,
                    UPLOAD_ERR_NO_FILE => "Niste predali datoteku",
                    UPLOAD_ERR_NO_TMP_DIR => "Greška sa serverom",
                    UPLOAD_ERR_CANT_WRITE => "Greška sa serverom",
                    UPLOAD_ERR_EXTENSION=> "Greška vezana uz ekstenziju datoteke."
                );
                if ($greska>0) {
                    Form::setError ("novi_korisnik", $upload_greske[$greska]);
                } else {
                    $privremena_datoteka=$_FILES['slika']['tmp_name'];
                    $datoteka_spremanja=basename($_FILES['slika']['name']);
                    $posljednjaTocka = strrpos($datoteka_spremanja, ".");
                    $ekstenzija= substr($datoteka_spremanja, $posljednjaTocka);
                    $datoteka_spremanja= str_replace(".", "", substr($datoteka_spremanja, 0, $posljednjaTocka));
                    $datoteka_spremanja= str_replace(" ", "", $datoteka_spremanja);
                    if(strlen($datoteka_spremanja)>50) $datoteka_spremanja= substr($datoteka_spremanja,0,50);{
                        $datoteka_spremanja.=$ekstenzija;
                        $upload_dir="slike";
                        $i=0;
                        
                        while (file_exists($upload_dir."/".$datoteka_spremanja)){
                            list ($naziv, $ekstenzija)=explode(".", $datoteka_spremanja);
                            $datoteka_spremanja=rtrim($naziv, strval($i-1)) . $i . "." . $ekstenzija;
                            $i++;
                        }

                        $slika=$upload_dir. "/" . $datoteka_spremanja;
                        if (move_uploaded_file($privremena_datoteka, $slika)){
                            if(true !== ($greska_sa_slikom= image_resize($slika, $slika, 200, 200, 1))){
                                unlink($slika);
                            } else {
                                #$izjava=$veza->prepare("INSERT INTO korisnici SET korisnik=?, lozinka=?, avatar=?");
                                $izjava=$veza->prepare("INSERT INTO korisnici (korisnik, lozinka, avatar) VALUES (?,?,?)");
                                $izjava->bind_param('sss', $korisnik, $lozinka, $datoteka_spremanja);

                                if($izjava->execute()){
                                    echo '<div class="alert alert-success">
                                        <strong>Korisnik je uspješno upisan u bazu podataka</strong>
                                        </div>';
                                } else {
                                    Form::setError("novi_korisnik", "Pogreška s upisivanjem u bazu podataka");
                                }
                            }
                        } else {
                            Form::setError("novi_korisnik", "Slika nije prebačena u folderr na serveru");
                        }
                    }
                }
            }
        }
        else{
            Form::setError("unos", "Korisnik već postoji u  bazi podataka");
            }
        }
    }
    else{Form::setError("novi_korisnik", "Lozinke se ne podudaraju");
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
//Form::open('novi_korisnik', '', array('view'=>'SideBySide4'));
Form::open('novi_korisnik', '', array("enctype" => "multipart/form-data"));
Form::File("Avatar", "slika", array("required" => 1));
Form::Hidden('provjera','provjereno');
Form::Textbox('Korisnik:', 'korisnik', array('required'=>1, 'validation'=>new Validation_RegExp('/^[A-ZČĆŠĐŽÀ-ÿ][a-zčćđšž\.\-\ ]{3,20}$/i',
"%element% mora sadržavati ispravne znakove.
Koriste se samo slova... Ostali znakovi interpunkcije nisu dozvoljeni.")));
Form::Password('Lozinka:', 'lozinka', array('required'=>1));
Form::Password('Ponovite lozinku:', 'ponovljeno', array('required'=>1));
Form::Button('Napravi korisnika');
Form::close(false);


echo "<script> document.getElementById('reg').classList.add('active'); document.getElementById('navbardrop').classList.add('active'); 
</script>"; 

?>
<?php 
require_once("footer.php");
?>