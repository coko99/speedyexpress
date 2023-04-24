<?php
  include('config/session_admin.php');

?><!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

?>
  <body>

  <?php
    $active = 3;
    include('config/navbar.php');
  ?>

    <div class="container mt-4">
      <div class="row mb-4">
        <div class="col">
          <a href="klijenti.php"
            ><button class="btn btn-info">NAZAD</button></a
          >
        </div>
      </div>
      <h2 class="mt-3 mb-3">Ime klijenta</h2>
      <div class="row d-flex justify-content-end">
        <div class="col-sm-12 col-md-4">
          <table class="table">
            <tbody>
              <tr>
                <th scope="row"><h5>Grad</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0">Beograd</h5>
                </td>
              </tr>
              <tr>
                <th scope="row"><h5>Opština</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0">Zemun</h5>
                </td>
              </tr>
              <tr>
                <th scope="row"><h5>Ulica</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0">Krusevacka 17</h5>
                </td>
              </tr>
              <tr>
                <th scope="row"><h5>Poštanski broj</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0">11070</h5>
                </td>
              </tr>
              <tr>
                <th scope="row"><h5>Broj telefona</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0">062185587</h5>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-sm-12 col-md-8">
          <h1>FILTER DATUM???</h1>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button
                class="nav-link active"
                id="isporuceni"
                data-bs-toggle="tab"
                data-bs-target="#home"
                type="button"
                role="tab"
                aria-controls="home"
                aria-selected="true"
              >
                Isporučeni paketi
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button
                class="nav-link"
                id="neisporuceni"
                data-bs-toggle="tab"
                data-bs-target="#profile"
                type="button"
                role="tab"
                aria-controls="profile"
                aria-selected="false"
              >
                Neisporučeni paketi
              </button>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div
              class="tab-pane fade show active border-0"
              id="home"
              role="tabpanel"
              aria-labelledby="isporuceni"
            >
              <div
                class="col-12 table-wrapper-scroll-y my-custom-scrollbar adminPaketi"
              >
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th scope="col ">ID paketa</th>

                      <th scope="col">Kurir</th>

                      <th scope="col">Lokacija</th>
                    </tr>
                  </thead>
                  <tbody class="isporuceni">
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col d-flex justify-content-end mt-3">
                  <button class="btn btn-success">ISPLATI</button>
                  <button class="btn disabled btn-success ml-3">ŠTAMPAJ</button>
                </div>
              </div>
            </div>
            <div
              class="tab-pane fade border-0"
              id="profile"
              role="tabpanel"
              aria-labelledby="neisporuceni"
            >
              <div
                class="col-12 table-wrapper-scroll-y my-custom-scrollbar adminPaketi"
              >
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th scope="col ">ID paketa</th>

                      <th scope="col">Kurir/baza</th>

                      <th scope="col">Lokacija</th>
                    </tr>
                  </thead>
                  <tbody class="neisporučeni">
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">1</th>

                      <td>
                        <h6>Ime kurira</h6>
                      </td>
                      <td>
                        <h6>Beograd-Vracar</h6>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
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
