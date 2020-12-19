
  <?php 
    $title = "Početna";
  require_once('header.php');
  include ('navbar.php');
  require_once('baza.php');
  require_once('PFBC/Form.php');
  session_start();

  if(!isset($_SESSION['userUid'])) {
    header("Location: ./login.php?logged=false");
  }

  if(Form::isValid("upis", false)) {
    if($_POST["lozinka"] != $_POST['ponovljenalozinka']){
        Form::setError("upis", "Lozinke se ne podudaraju");
    } else {

      $lozinka = htmlentities(trim($_POST['lozinka']));
      $ponovljenalozinka = htmlentities(trim($_POST['ponovljenalozinka']));
      $korisnik = htmlentities(trim($_POST['korisnik']));
                
      $rez = $veza->query("SELECT FROM korisnici WHERE korisnik = $korisnik LIMIT 1");
        
      $hashedPwd = password_hash($lozinka, PASSWORD_BCRYPT);
      $priprema = $veza->prepare("INSERT INTO korisnici (korisnik, lozinka) VALUES (?, ?)");
      $priprema->bind_param('ss', $korisnik, $hashedPwd);
    
      if ($priprema->execute()) {
        echo "<div class='alert alert-success'>Uspješan unos u bazu!</div>";
        Form::clearValues('upis');
      }

    }
  }

  
echo "<script> document.getElementById('index').classList.add('active'); 
</script>"; 

  echo 'Dobrodošao, '.$_SESSION['userUid'].'.';
  if(!isset($_POST['predavanje'])) Form::clearErrors('upis');
  Form::open('upis', '', array("view"=>"sidebyside4"));
  Form::Hidden('predavanje', 'predano');
  Form::Textbox('Korisnik: ', 'korisnik', array("required"=>1, "validation"=>new Validation_RegExp("/^[a-zčćđšž\.\-\ ]{5,50}$/i","%element% mora sadržavati minimalno 5 znakova. Koriste se samo slova... Ostali znakovi interpunkcije nisu dozvoljeni")));
  Form::Password('Lozinka: ', 'lozinka', array("required"=>1, "validation"=>new Validation_RegExp("/^[a-zčćđšž\.\-\ ]{6,60}$/i","%element% mora sadržavati minimalno 6 znakova. Koriste se samo slova i brojke te _, - i .. Ostali znakovi interpunkcije nisu dozvoljeni.")));
  Form::Password('Ponovite lozinku: ', 'ponovljenalozinka', array("required"=>1));
  Form::Button("Kreiraj korisnika");
  Form::close(false);
  
  
  ?>
</div>

</body>
</html>
