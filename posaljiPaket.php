<?php
  include('config/session_user.php');

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
        <h2 class="mt-3 mb-3">Zakazivanje kurira</h2>
      </div>
      <div class="row">
        <div class="col-md-4 col-sm-12">
          <h3><strong>Popuni ručno</strong></h3>
          <!-- POSALJI PAKET RUCNO -->
          <form method='POST'>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Ime i Prezime"
              />
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                placeholder="Ulica"
                name="street" 
                id="term" 
                class="form-control"
              />
            </div>
            <div class="form-group">
              <select
                class="form-control"
                id="municipality"
                name="municipality"
                placeholder="Opština"
              >
            </select>
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Broj telefona"
              />
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Opis pošiljke"
              />
            </div>

            <div class="form-group">
              <label for="inputState">Dostavu plaća</label>
              <select id="inputState" class="form-control">
                <option selected>Primalac</option>
                <option>Pošiljalac</option>
              </select>
            </div>
            <div class="form-group">
              <label for="inputState">PTT : 300rsd</label>
            </div>
            <div class="form-group">
              <input
                type="number"
                class="form-control"
                id="inputAddress"
                placeholder="Otkupnina"
              />
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Napomena"
              />
            </div>
            <button type="submit" class="btn btn-primary mt-3 mb-3">
              Ubaci
            </button>
          </form>
        </div>
        <div class="col-md-8 col-sm-12 border mb-5">
          <div class="row d-flex justify-content-between">
            <div class="col-6 align-self-center p-3">
              <h5>
                POŠILJKE ZA DAN
                <strong><span id="prikazDatumaVremena"></span></strong>
              </h5>
            </div>
            <div class="col-6 align-self-center">
              <div class="row">
                <div class="col-4 text-center">
                  <button class="btn btn-primary">Excel</button>
                </div>
                <div class="col-4 text-center">
                  <button class="btn btn-primary">Štampaj</button>
                </div>
                <div class="col-4 text-center">
                  <a href="poslatiPaketi.html"
                    ><button class="btn btn-success">POŠALJI</button></a
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
              <table class="table table-bordered table-striped mb-0">
                <thead>
                  <tr>
                    <th scope="col ">#ID</th>
                    <th scope="col">QR</th>
                    <th scope="col">Primalac</th>
                    <th scope="col">Opis</th>
                    <th scope="col">Akcija</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td><img class="qr-slika" src="qr kod.png" alt="" /></td>
                    <td>
                      <h6>Milos Mijajlovic</h6>
                      <h6>Krusevac 37000</h6>
                      <h6>Kruševačka 17</h6>
                      <h6>+381621872069</h6>
                    </td>
                    <td>
                      <h6><strong>Otkup: </strong>20.000 rsd</h6>
                      <h6><strong>Vrednost: </strong>20.000 rsd</h6>
                      <h6><strong>Plaća: </strong>Primalac</h6>
                      <h6><strong>napomena: </strong>tekst</h6>
                    </td>
                    <td><button class="btn btn-danger">Obrisi</button></td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td><img class="qr-slika" src="qr kod.png" alt="" /></td>
                    <td>
                      <h6>Milos Mijajlovic</h6>
                      <h6>Krusevac 37000</h6>
                      <h6>Kruševačka 17</h6>
                      <h6>+381621872069</h6>
                    </td>
                    <td>
                      <h6><strong>Otkup: </strong>20.000 rsd</h6>
                      <h6><strong>Vrednost: </strong>20.000 rsd</h6>
                      <h6><strong>Plaća: </strong>Primalac</h6>
                      <h6><strong>napomena: </strong>tekst</h6>
                    </td>
                    <td><button class="btn btn-danger">Obrisi</button></td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td><img class="qr-slika" src="qr kod.png" alt="" /></td>
                    <td>
                      <h6>Milos Mijajlovic</h6>
                      <h6>Krusevac 37000</h6>
                      <h6>Kruševačka 17</h6>
                      <h6>+381621872069</h6>
                    </td>
                    <td>
                      <h6><strong>Otkup: </strong>20.000 rsd</h6>
                      <h6><strong>Vrednost: </strong>20.000 rsd</h6>
                      <h6><strong>Plaća: </strong>Primalac</h6>
                      <h6><strong>napomena: </strong>tekst</h6>
                    </td>
                    <td>
                      <button class="btn btn-danger align-self-center">
                        Obrisi
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">4</th>
                    <td><img class="qr-slika" src="qr kod.png" alt="" /></td>
                    <td>
                      <h6>Milos Mijajlovic</h6>
                      <h6>Krusevac 37000</h6>
                      <h6>Kruševačka 17</h6>
                      <h6>+381621872069</h6>
                    </td>
                    <td>
                      <h6><strong>Otkup: </strong>20.000 rsd</h6>
                      <h6><strong>Vrednost: </strong>20.000 rsd</h6>
                      <h6><strong>Plaća: </strong>Primalac</h6>
                      <h6><strong>napomena: </strong>tekst</h6>
                    </td>
                    <td>
                      <button class="btn btn-danger align-self-center">
                        Obrisi
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">5</th>
                    <td><img class="qr-slika" src="qr kod.png" alt="" /></td>
                    <td>
                      <h6>Milos Mijajlovic</h6>
                      <h6>Krusevac 37000</h6>
                      <h6>Kruševačka 17</h6>
                      <h6>+381621872069</h6>
                    </td>
                    <td>
                      <h6><strong>Otkup: </strong>20.000 rsd</h6>
                      <h6><strong>Vrednost: </strong>20.000 rsd</h6>
                      <h6><strong>Plaća: </strong>Primalac</h6>
                      <h6><strong>napomena: </strong>tekst</h6>
                    </td>
                    <td>
                      <button class="btn btn-danger align-self-center">
                        Obrisi
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">6</th>
                    <td><img class="qr-slika" src="qr kod.png" alt="" /></td>
                    <td>
                      <h6>Milos Mijajlovic</h6>
                      <h6>Krusevac 37000</h6>
                      <h6>Kruševačka 17</h6>
                      <h6>+381621872069</h6>
                    </td>
                    <td>
                      <h6><strong>Otkup: </strong>20.000 rsd</h6>
                      <h6><strong>Vrednost: </strong>20.000 rsd</h6>
                      <h6><strong>Plaća: </strong>Primalac</h6>
                      <h6><strong>napomena: </strong>tekst</h6>
                    </td>
                    <td>
                      <button class="btn btn-danger align-self-center">
                        Obrisi
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- <script src="index.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    ></script>
    <script>
      var availableTags = [];
      $( function() {
        $( "#term" ).autocomplete({
          source: 'ajax-street-db-search.php',
        });

      } );
      $( "#term" ).on( "autocompleteselect", function( event, ui ) {
          $.get( "ajax-municipality-db-search.php?street=" + ui['item']['value'], function( data ) {
            JSON.parse(data).forEach(element => {
              $('#municipality').append('<option value="' + element + '">' + element + '</option>');
              }
            );
          });
      } );

      
    </script>
  </body>

</html>
