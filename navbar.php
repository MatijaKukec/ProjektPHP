<?php 
echo '

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
<div class="container-fluid">
<!-- Brand -->
<a class="navbar-brand" href="#">Logo</a>

<!-- Links -->
<ul class="navbar-nav">
  <li class="nav-item">
    <a id="index" class="nav-link" href="index.php">Doma</a>
  </li>
  <li class="nav-item">
    <a id="login" class="nav-link" href="login.php">Login</a>
  </li>

  <!-- Dropdown -->
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
      Korisnici
    </a>
    <div class="dropdown-menu">
      <a id="reg" class="dropdown-item" href="novi_korisnik.php">Novi korisnik</a>
      <a id="sviKor" class="dropdown-item" href="svi_korisnici.php">Svi korisnici</a>
    </div>
  </li>
  
  

</ul>

<ul class="nav navbar-nav navbar-right">
  <li>
    <div class="topnav-right">
    <a class="nav-link" href="logout.php">Logout</a>
    </div>
  </li>
  </ul>
</div>

</nav>
' ?>