<?php
  include('config/session_admin.php');

?>
<!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

?>
  <body>
  <?php
    $active = 5;
    include('config/navbar.php');
  ?>
<!--DODAJ KURIRA-->
    <div class="container mt-4">
      <div class="row">
        <div class="col-md-6 col-sm-12  ">
          <section class="">
            <div class="row">
              <div class="col-md-9 col-sm-12">
                <h2 class="h1-responsive font-weight-bold text-center my-4">
                  Dodaj kurira
                </h2>
              </div>
            </div>
            <div class="row">
              <div class="col-md-9">
                <form id="" name="" action="" method="">
                  <div class="row">
                    <div class="col-12">
                      <div class="md-form mb-3">
                        <input
                        placeholder="Ime i prezime"
                          type="text"
                          id="name"
                          name="name"
                          class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="md-form mb-3">
                        <input
                        placeholder="Broj telefona"
                          type="number"
                          id=""
                          name=""
                          class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="md-form mb-3">
                        <input
                        placeholder="Šifra"
                          type="number"
                          id=""
                          name=""
                          class="form-control"
                        />

                      </div>
                    </div>
                  </div>
                  <button class="btn btn-success" type="submit">
                    Dodaj kurira
                  </button>
                  <button class="btn btn-warning disabled  " type="submit">
                    Sačuvaj izmene
                  </button>
                </form>
              </div>
            </div>
          </section>
        </div>
        <div class="col-md-6 col-sm-12 ">
          <div class="col-12">
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <h2 class="h1-responsive font-weight-bold text-center my-4">
                Spisak kurira
              </h2>
            </div>
          </div>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">#ID</th>
                <th scope="col">Ime i Prezime</th>
                <th scope="col">Akcija</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td class="align-items-center">
                  <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                </td>
                <td>
                  <a href="kurir.html">
                    <button class="btn btn-danger align-self-center">
                      Izbriši
                    </button></a
                  >
                  <a href="kurir.html">
                    <button class="btn btn-warning align-self-center">
                      Izmeni
                    </button></a
                  >
                </td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td class="align-items-center">
                  <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                </td>
                <td>
                  <a href="kurir.html">
                    <button class="btn btn-danger align-self-center">
                      Izbriši
                    </button></a
                  >
                  <a href="kurir.html">
                    <button class="btn btn-warning align-self-center">
                      Izmeni
                    </button></a
                  >
                </td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td class="align-items-center">
                  <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                </td>
                <td>
                  <a href="kurir.html">
                    <button class="btn btn-danger align-self-center">
                      Izbriši
                    </button></a
                  >
                  <a href="kurir.html">
                    <button class="btn btn-warning align-self-center">
                      Izmeni
                    </button></a
                  >
                </td>
              </tr>   
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>


    <div class="row mt-5  mb-5" style="height:10px;background-color: #1A75F0;"></div>
<!--DODAJ FIRMU-->
    <div class="row">
      <div class="col-md-6 col-sm-12  ">
        <section class="">
          <div class="row">
            <div class="col-md-9 col-sm-12">
              <h2 class="h1-responsive font-weight-bold text-center my-4">
                Dodaj klijenta
              </h2>
            </div>
          </div>
          <div class="row">
            <div class="col-md-9">
              <form id="" name="" action="" method="">
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Ime firme"
                        id="name"
                        name="name"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Mesto"
                        id="name"
                        name="name"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Adresa"
                        id="name"
                        name="name"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
             
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="number"
                      placeholder="Broj telefona"
                        id=""
                        name=""
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      placeholder="Korisničko ime"
                        id="name"
                        name="name"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Šifra"
                        type="number"
                        id=""
                        name=""
                        class="form-control"
                      />

                    </div>
                  </div>
                </div>
                <button class="btn btn-success" type="submit">
                  Dodaj klijenta
                </button>
                <button class="btn btn-warning disabled  " type="submit">
                  Sačuvaj izmene
                </button>
              </form>
            </div>
          </div>
        </section>
      </div>

      
      <div class="col-md-6 col-sm-12 ">
        <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <h2 class="h1-responsive font-weight-bold text-center my-4">
              Spisak klijenata
            </h2>
          </div>
        </div>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">#ID</th>
              <th scope="col">Ime i Prezime</th>
              <th scope="col">Akcija</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td class="align-items-center">
                <h4 class="m-0 p-0">Milos Mijajlovic</h4>
              </td>
              <td>
                <a href="kurir.html">
                  <button class="btn btn-danger align-self-center">
                    izbriši
                  </button></a
                >
                <a href="kurir.html">
                  <button class="btn btn-warning align-self-center">
                    Izmeni
                  </button></a
                >
              </td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td class="align-items-center">
                <h4 class="m-0 p-0">Milos Mijajlovic</h4>
              </td>
              <td>
                <a href="kurir.html">
                  <button class="btn btn-danger align-self-center">
                    Izbriši
                  </button></a
                >
                <a href="kurir.html">
                  <button class="btn btn-warning align-self-center">
                    Izmeni
                  </button></a
                >
              </td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td class="align-items-center">
                <h4 class="m-0 p-0">Milos Mijajlovic</h4>
              </td>
              <td>
                <a href="kurir.html">
                  <button class="btn btn-danger align-self-center">
                    Izbriši
                  </button></a
                >
                <a href="kurir.html">
                  <button class="btn btn-warning align-self-center">
                    Izmeni
                  </button></a
                >
              </td>
            </tr>   
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
    </div>



    
    <script src="index.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
