
<?php
session_start();
if (!isset($_SESSION['userUid'])) {	header('Location: login.php'); }
$title="Svi korisnici";

include ('header.php');
include ('navbar.php');
require_once ('PFBC/Form.php');
require_once ('baza.php');
require_once ('crop.php');
include ('paginacija.php');

echo '<div class="forma"><h2>Svi korisnici sustava</h2>';
require_once('baza.php');

if ($rezultat){
  echo "<table><tr><td></td><td>Ime:</td></tr> \n";
  while ($redak=$rezultat->fetch_assoc()) {
	echo "<tr><td><img width='50' height='50' src='slike/" .$redak['avatar']."' /></td>
	<td>" . $redak['korisnik'] . "</td><td>
	<form action='uredi_korisnik.php' method='post'>
	<input type='hidden' name='id' value=' " .$redak['id'] . " ' />
	<input type='submit' value='Uredi'></input>
	</form> </td><td>

	<form action= 'uredi_sliku.php' method= 'post'>
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
} else Form::setError("Došlo je do pogreške pri čitanju podataka iz baze");

//paginaciju budemo ispisivali jedino ukoliko imamo više od jedne stranice
if ($brojStranica>1) {
    echo "<div style='clear: left; '>";
    //ukoliko nismo na prvoj stranici ispisujemo prethodna,
    //kad bi bili na prvoj stranici ne bi ispisali prethodna
    echo "<ul class='pagination pagination-sm'>";
    if ($stranica>1){
      //prethodna je promjenjivi link i ovisi o stranici
      //na kojoj se trenutačno nalazimo --> $stranica - 1

      echo "<li><a href= 'svi_korisnici.php?stranica= ". ($stranica-1)."'>&laquo Prethodna </a></li>";
    }
    for ($i=1; $i<=$brojStranica; $i++) {
      if ($i==$stranica) echo "<li class= 'active '><span> $i </span></li>";
      else echo "<li><a href='svi_korisnici.php?stranica=$i'> $i </a></li> ";
    }
    if ($stranica<$brojStranica){
      echo "<li><a href='svi_korisnici.php?stranica=" . ($stranica+1) . " '>
      Sljedeća&raquo </a></li>";
    }
    echo "</ul>";
    echo "</div> ";
}

echo "</div>";

include('footer.php');
echo "<script>
$('[data-toggle=confirmation]').confirmation({
	rootSelector: '[data-toggle=confirmation]',
	onConfirm: function() {
		location.href='obrisi.php?id=' + $(this).attr('id');
	}
});
document.getElementById('sviKor').classList.add('active'); 
document.getElementById('navbardrop').classList.add('active'); 
</script>";

$veza->close();

?>


