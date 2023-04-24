<?php
  include("config/config.php");
  session_start();

  if(isset($_SESSION['login_admin'])){
    header("location: index.php");
    die();
  }else if(isset($_SESSION['login_user'])){
    header("location: profil.php");
    die();
  }
   
  if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form 
      
    $myusername = mysqli_real_escape_string($db, $_POST['username']);
    $mypassword = $_POST['password']; 
      
    $sql = "SELECT * FROM admin WHERE username = '$myusername'";

    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      
    $count = mysqli_num_rows($result);
    // If result matched $myusername and $mypassword, table row must be 1 row
    if($count == 1 && password_verify($mypassword, $row['password']) && $row['status'] == 1) {
        if(isset($_SESSION['login_user'])){
            unset($_SESSION['login_user']);
          }
      $_SESSION['login_admin'] = $myusername;
         
      header("location: index.php");
    }else {
      // $error = "Neispravno korisničko ime ili lozinka!";
      $sql = "SELECT * FROM user WHERE email = '$myusername'";

      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        
      $count = mysqli_num_rows($result);
      if($count == 1 && password_verify($mypassword, $row['password']) && $row['status'] == 1) {
        if(isset($_SESSION['login_user'])){
            unset($_SESSION['login_user']);
          }
        $_SESSION['login_user'] = $myusername;
          
        header("location: profil.php");
      }else{
        $error = "Neispravno korisničko ime ili lozinka!";
      }
    }
   }
?>
<!DOCTYPE html>
<html lang="sr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Speedy Express</title>
    <link rel="stylesheet" href="index.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <section class="gradient-form" style="background-color: #eee">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-xl-10">
            <div class="card rounded-3 text-black">
              <div class="row g-0">
                <div class="col-lg-6">
                  <div class="card-body p-md-5 mx-md-4">
                    <div class="text-center mb-5">
                      <img src="logo.png" style="width: 140px" alt="logo" />
                    </div>

                    <?php
                      if(isset($error) && !empty($error)){
                        echo '<div class="alert alert-danger" role="alert">
                        '.$error.'
                      </div>';
                      }
                    ?>

                    <form method="POST">
                      <p class="text-center">Prijava korisnika</p>

                      <div class="form-outline mb-4">
                        <input
                          type="email"
                          id="form2Example11"
                          class="form-control"
                          placeholder="Korisničko ime"
                          name="username"
                        />
                      </div>

                      <div class="form-outline mb-4">
                        <input
                          type="password"
                          id="form2Example22"
                          class="form-control"
                          placeholder="Šifra"
                          name="password"
                        />
                      </div>

                      <div class="text-center pt-1 mb-5 pb-1">
                        <button
                          class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3"
                          type="submit"
                        >
                          Prijavi se
                        </button>
                      </div>

                      <div
                        class="d-flex align-items-center justify-content-center pb-4"
                      >
                        <p class="mb-0 me-2">Nemaš nalog?</p>
                        <button type="button" class="btn btn-outline-danger">
                          Kontaktiraj nas!
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
                <div
                  class="col-lg-6 d-flex align-items-center gradient-custom-2"
                >
                  <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                    <h4 class="mb-4">Ko smo mi?</h4>
                    <p class="small mb-0">
                      Nudimo brzu i pouzdanu uslugu dostave dokumenata, paketa i
                      pošiljki na vašu adresu. <br />Naša usluga je jednostavna
                      za korišćenje, a cene su konkurentne na tržištu. Uz nas
                      možete biti sigurni da će vaša pošiljka stići na vreme i u
                      savršenom stanju. Naručite brzu poštu danas i uživajte u
                      našim uslugama dostave.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="index.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
