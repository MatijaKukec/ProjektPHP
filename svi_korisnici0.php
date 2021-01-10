<?php if (isset($_SESSION['userUid'])) header('Location: ./index.php'); ?>

<?php $title = "Svi korisnici"; 

require_once ('header.php'); 
require_once ('navbar.php'); 
require_once ('PFBC/Form.php');
require_once ('baza.php');
require_once ('crop.php');

?>

  <h1 style="text-align: center;">Korisnici</h1>

<?php

//include ('paginacija.php');

session_start();

//provjera da li se nalazi u bazi
//odredi vanje trenutačne stranice
//ukoliko ništa nemamo zapisano u URL -u, stranica se postavlja na 1,
//a ukoliko imamo zapisan broj u $_GET pos stavljamo ga u varijablu stranica
$stranica=(empty($_GET['stranica'])) ? 1 : (int) $_GET['stranica'];
//broj članaka koji će se prikazi vati po stranici
$brojPoStranici=3;
//brojanje koliko je stavki u bazi
$query = "SELECT COUNT(*) FROM korisnici";
$rezultat=$veza->query($query);
if ($rezultat) {
  $polje=$rezultat->fetch_row();
  $ukupno_korisnika=$polje[0];

  //odredivanje koliko ukupno imamo stranica
  $brojStranica=ceil($ukupno_korisnika/$brojPoStranici);
  //ukolko korisnik upiše u URL broj stranice koji ne postoji
  if ($stranica<1) $stranica=1;
  else if ($stranica>$brojStranica-1) $stranica=$brojStranica;
  //Odedivanje koji korisnici će se dohvatiti
  $odmak=$brojPoStranici*($stranica-1);
  /*dohvaćanje korisnika ovisno o stranici na kojoj smo,
  poredamo ih ASC, ukoliko želimo da idu od mladeg prema starijem stavimo DESC*/
  $query="SELECT*FROM korisnici ORDER BY id ASC LIMIT $brojPoStranici OFFSET $odmak" ;
  $rezultat=$veza->query($query, MYSQLI_STORE_RESULT);

  echo "<script>document.getElementById('sviKor').classList.add('active'); 
  document.getElementById('navbardrop').classList.add('active');
  </script>"; 

  echo '<legend>Svi korisnici</legend>';
  if ($rezultat){
    echo "<table><tr><td>Ime:</td></td></tr> \n";
    while ($redak=$rezultat->fetch_assoc()) {
      echo "<tr><td><img width='50' height='50' src='slike/" .$redak['avatar']."' /></td>
      <td>" . $redak['korisnik'] . "</td><td>
      <form action='uredi_korisnik.php' method='post'>
      <input type='hidden' name='id' value=' " .$redak['id'] . " ' />
      <input type='submit' value='Uredi'></input>
      </form> </td><td>

      <form action= 'slika_korisnik.php' method= 'post'>
      <input type='hidden' name='id' value='" .$redak['id']."'/>
      <input type='submit' value='Uredi sliku' class= 'btn btn-default '></input>
      </form> </td><td>
      
      <button class='btn btn-danger'
      id=' ". $redak['id'] . " ' data-btn-ok-label='Da'
      title='Želite li obrisati korisnika?'
      data-btn-cancel-label='Ne' data-toggle='confirmation'
      data-singleton='true'>Obriši</button>

      </td></tr>";
      
    }
  echo "</table>";
  }

  //paginaciju budemo ispisivali jedino ukoliko imamo više od jedne stranice
  if ($brojStranica>1) {
    echo "<div style='clear: left; '>";
    //ukoliko nismo na prvoj stranici ispisujemo prethodna,
    //kad bi bili na prvoj stranici ne bi ispisali prethodna
    echo '<ul class="pagination pagination-sm ">';
    if ($stranica>1){
      //prethodna je promjenjivi link i ovisi o stranici
      //na kojoj se trenutačno nalazimo --> $stranica - 1
      echo "<li><a href= 'svi_korisnici0.php?stranica= ". ($stranica-1)."'>&laquo Prethodna </a></li>";
    }
    for ($i=1; $i<=$brojStranica; $i++) {
      if ($i==$stranica) echo "<li class= 'active '><span> $i </span></li>";
      else echo "<li><a href='svi_korisnici0.php?stranica=$i'> $i </a></li> ";
    }
    if ($stranica<$brojStranica){
      echo "<li><a href='svi_korisnici0.php?stranica=" . ($stranica+1) . " '>
      Sljedeća&raquo </a></li>";
    }
    echo "</ul>";
    echo "</div> ";
  }
}
else echo "Nije bilo moguće pročitati bazu";

?>

<?php 
include ("footer.php");
?>