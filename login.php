<?php if (isset($_SESSION['userUid'])) header('Location: ./index.php'); ?>
<?php $title = "Logiranje"; require_once('header.php'); require_once ('navbar.php');?>

  <h1 style="text-align: center;">Logiraj se</h1>

<?php 

require_once 'PFBC/Form.php';
require_once 'baza.php';

session_start();
if(Form::isValid("login", false)) {
        $lozinka = htmlentities(trim($_POST['lozinka']));
        $korisnik = htmlentities(trim($_POST['korisnik']));
        
        $query= $veza->prepare("SELECT * FROM korisnici WHERE korisnik=? LIMIT 1");
        $query->bind_param('s', $korisnik);
        if ($query->execute()){
            $result = $query->get_result();
            if ($result->num_rows) {
                if($row = $result->fetch_assoc()) {
                    if(password_verify($lozinka, $row['lozinka'])){
                        session_start();
                        $_SESSION['userId'] = $row['id'];
                        $_SESSION['userUid'] = $row['korisnik'];
                        $_SESSION["message"] = "Uspješna prijava!";
                        $_SESSION["msg_type"] = "success";
                        header("Location: ./index.php?login=success");
                        exit();
                    }
                    else {
                        Form::setError("login", "Lozinke se ne podudaraju!");
                    }
                }
            }   
        else {
            Form::setError("login", "Korisnik ne postoji u bazi!");
        }
    }
}

    
echo "<script> document.getElementById('login').classList.add( 'active'); 
</script>"; 

if(!isset($_POST['predano'])) Form::clearErrors('login');
Form::open('login', '', array("view"=>"sidebyside4"));
Form::Hidden('predano', 'predavanje');
Form::Textbox('Korisnik: ', 'korisnik', array("required"=>1, "validation"=>new Validation_RegExp("/^[a-zčćđšž\.\-\ ]{3,20}$/i","%element% mora sadržavati minimalno 3 znakova. Koriste se samo slova... Ostali znakovi interpunkcije nisu dozvoljeni")));
Form::Password('Lozinka: ', 'lozinka', array("required"=>1/*, "validation"=>new Validation_RegExp("/^[a-zčćđšž\.\-\d\ ]{3,20}$/i","%element% mora sadržavati minimalno 6 znakova. Koriste se samo slova i brojke te _, - i .. Ostali znakovi interpunkcije nisu dozvoljeni.")*/));
Form::Button("Ulogiraj se");
Form::close(false);
  
require_once('footer.php');
?>