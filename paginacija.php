<?php 

require_once ('PFBC/Form.php');
require_once ('baza.php');

//provjera da li se nalazi u bazi
//odredivanje trenutačne stranice
//ukoliko ništa nemamo zapisano u URL -u, stranica se postavlja na 1,
//a ukoliko imamo zapisan broj u $_GET pos stavljamo ga u varijablu stranica
$stranica = (empty($_GET['stranica'])) ? 1 : (int) $_GET['stranica'];
//broj članaka koji će se prikazivati po stranici
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
  //dohvaćanje korisnika ovisno o stranici na kojoj smo,
  //poredamo ih ASC, ukoliko želimo da idu od mladeg prema starijem stavimo DESC
  $query="SELECT*FROM korisnici ORDER BY id ASC LIMIT $brojPoStranici OFFSET $odmak" ;
  $rezultat=$veza->query($query, MYSQLI_STORE_RESULT);

}
else echo "Nije bilo moguće pročitati bazu";

?>