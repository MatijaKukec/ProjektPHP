<?php
    require_once("baza.php");
    require_once("crop.php");
    $dozvoljeni_MIME =array ("image/jpeg", "image/gif", "image/png","image/bmp");
    if(!empty ($_FILES['slika']['type'])&&!in_array ($_FILES[ 'slika ']['type'], $dozvoljeni_MIME)) {
    Form::setError("slikaKorisnik", "Niste odabrali ispravan tip datoteke !!! (gif, jpeg ili png) ");
    }else {
        $greska=$_FILES['slika ']['error'];
    $upload_greske = array(
    UPLOAD_ERR_OK => "Datoteka je uspješno predana",
    UPLOAD_ERR_INI_SIZE => "Datoteka je prevelika",
    UPLOAD_ERR_FORM_SIZE => "Datoteka je prevelika",
    UPLOAD_ERR_PARTIAL => "Partial upload. ",
    UPLOAD_ERR_NO_FILE => "Niste predali datoteku",
    UPLOAD_ERR_NO_THP_DIR => "Greška sa serverom",
    UPLOAD_ERR_CANT_WRITE => "Greška sa serverom",
    UPLOAD_ERR_EXTENSION=> "Greška vezana uz ekstenziju datoteke. "
    );
    if ($greska>0) {
    Form::setError ("slikaKorisnik", $upload_greske [$greska]);
     } else {
        $privremena_datoteka=$_FILES[ 'slika']['tmp_name'];
        $datoteka_spremanja=basename ($_FILES['slika']['name']);
        $datoteka_spremanja=basename ($_FILES['slika']["name" ]);
        $posljednjaTocka = strrpos ($datoteka_spremanja, ".");
        $ekstenzija= substr ($datoteka_spremanja,$posljednjaTocka);
        $datoteka_spremanja= str_replace(".", "", substr($datoteka_spremanja, 0,$posljednjaTocka));
        $datoteka_spremanja= str_replace(" ", "", $datoteka_spremanja) ;
        if (strlen ($datoteka_spremanja) >50) $datoteka_spremanja= substr($datoteka_spremanja, 0, 50);
        $datoteka_spremanja.=$ekstenzija;
        $upload_dir="slike";
        $i=0;
        while (file_exists ($upload_dir. "/". $datoteka_spremanja)){
        list ($naziv, $ekstenzija) =explode (".", $datoteka_spremanja) ;
        $datoteka_spremanja=rtrim ($naziv, strval($i-1)) . $i. "." . $ekstenzija;
        $i++;
        }
        $slika=$upload_dir. "/" . $datoteka_spremanja;
        if (move_uploaded_file ($privremena_datoteka, $slika)) {
        if (true !== ($greska_sa_slikom= image_resize ($slika, $slika, 200, 200, 1))) {
        unlink($slika);
        }else {
        }
    }else{
        Form::setError ("slikaKorisnik", "Slika nije prebačena u folder na serveru ") ;
    }
}
    }