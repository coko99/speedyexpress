<?php 
    if(isset($_SESSION['login_admin'])){
?>
<div class="container-fluid pt-3">
      <div class="container border-bottom">
        <div class="row">
          <nav class="navbar navbar-expand-lg navbar-light">
            <a href="#" class="navbar-brand"
              ><img src="logo.png" style="width: 120px" alt=""
            /></a>
            <button
              type="button"
              class="navbar-toggler"
              data-bs-toggle="collapse"
              data-bs-target="#navbarCollapse"
            >
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
              <div class="navbar-nav mx-auto">
                <a
                  href="index.php"
                  class="nav-item text-uppercase nav-link <?php if($active == 1) echo "active"; ?>"
                  >Opšte</a
                >
                <a href="kuriri.php" class="nav-item text-uppercase nav-link <?php if($active == 2) echo "active"; ?>"
                  >Kuriri</a
                >
                <a href="klijenti.php" class="nav-item text-uppercase nav-link <?php if($active == 3) echo "active"; ?>"
                  >Klijenti</a
                >
                <a href="paketi.php" class="nav-item text-uppercase nav-link <?php if($active == 4) echo "active"; ?>"
                  >Paketi</a
                >
                <a
                  href="dodaj.php"
                  class="nav-item text-uppercase nav-link <?php if($active == 5) echo "active"; ?>"
                  >Dodaj</a
                >
              </div>
              <div class="navbar-nav">
                <a href="logout.php" class="nav-item nav-link" style="width: 120px"
                  >Odjavi se</a
                >
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
<?php
     }
     else{
?>
<div class="container-fluid pt-3">
      <div class="container border-bottom">
        <div class="row">
          <nav class="navbar navbar-expand-lg navbar-light">
            <a href="#" class="navbar-brand"
              ><img src="logo.png" style="width: 120px" alt=""
            /></a>
            <button
              type="button"
              class="navbar-toggler"
              data-bs-toggle="collapse"
              data-bs-target="#navbarCollapse"
            >
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
              <div class="navbar-nav mx-auto">
                <a
                  href="profil.php"
                  class="nav-item text-uppercase nav-link <?php if($active == 1) echo "active"; ?>"
                  >Profil</a
                >
                <a
                  href="poslatiPaketi.php"
                  class="nav-item text-uppercase nav-link <?php if($active == 2) echo "active"; ?>"
                  >Poslati paketi</a
                >
                <a
                  href="posaljiPaket.php"
                  class="nav-item text-uppercase nav-link <?php if($active == 3) echo "active"; ?>"
                  >Pošalji paket</a
                >
              </div>
              <div class="navbar-nav">
                <a href="logout.php" class="nav-item nav-link" style="width: 120px"
                  >Odjavi se</a
                >
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
<?php 
     }
?>