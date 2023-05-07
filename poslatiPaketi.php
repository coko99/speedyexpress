<?php
  use chillerlan\QRCode\QRCode;

  include('config/session_user.php');
  require 'vendor/autoload.php';


  $sql = "SELECT package.*, 
  municipality.name AS municipality_name, 
  municipality.zip AS zip,
  street.name AS street_name,
  status.name AS status_name
  FROM `package`
  LEFT JOIN street ON package.street_id = street.id
  LEFT JOIN municipality ON street.municipality_id = municipality.id
  LEFT JOIN status ON package.status_id = status.id
  WHERE firm_id = $firm_id AND status_id != 1";
  $result = mysqli_query($db, $sql);
  $packages = [];
  while($row = mysqli_fetch_array($result)) {
    array_push($packages, $row);
  }

?><!DOCTYPE html>
<html lang="sr">
  <?php
    include('config/head.php');

  ?>
  
  <body onload="prikaziDatumVreme()">
    
    <?php
      $active = 2;
      include('config/navbar.php');
    ?>


    <div class="container mt-4">
      <div class="row mb-4 text-left">
        <h2 class="mt-3 mb-3">Poslati paketi</h2>
      </div>

      <div class="row">
        <div class="col-12">
        <table  id="example" class="display" style="width:100%" >
          <thead>
            <tr>
            <th style="display: none;" scope="col">Send time</th>
              <th scope="col">#</th>
              <th scope="col">ID paketa</th>
              <th scope="col">Paket</th>
              <th scope="col">Cena</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $counter = 0;
              foreach($packages as $package){
                $counter += 1;
                $recipient = $package['recipient'];
                $phone = $package['phone'];
                $ransome = $package['shipping_fee'];
                $paid_by = ($package['ransom_type_id'] == 1) ? 'Primalac' : 'Pošiljalac';
                $comment = $package['comment'];
                $package_id = $package['id'];
                $street_number = $package['street_number'];
                $street_name = $package['street_name'];
                $zip = $package['zip'];
                $municipality_name = $package['municipality_name'];
                $token = $package['token'];
                $send_time = date("d/m/Y - H:i:s", $package['send_time']);
                $package_status = $package['status_name'];
                
                

                echo "<tr>
                        <td style='display: none;' >$send_time</td>
                        <th scope='row'>$counter</th>
                        <td>
                          <img class='qr-slika' src='".(new QRCode())->render($package_id.'-'.$token)."' alt='QR Code' />
                        </td>
                        <td>
                          <h6>$recipient</h6>
                          <h6>$municipality_name $zip</h6>
                          <h6>$street_name $street_number</h6>
                          <h6>$phone</h6>
                        </td>
                        <td>
                          <h6><strong>Otkup: </strong>$ransome rsd</h6>
                          <h6><strong>Vrednost: </strong>$ransome rsd</h6>
                          <h6><strong>Plaća: </strong>$paid_by</h6>
                          <h6><strong>napomena: </strong>$comment</h6>
                        </td>
                        <td>$package_status</td>
                      </tr>";
              }

            ?>
          </tbody>
        </table>
        </div>
      </div>
    </div>

    <script src="index.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.0.2/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>


    <script>

    $(document).ready(function () {

        var collapsedGroups = {};

      var oTable = $('#example').DataTable({
        paging: false,
        language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.18/i18n/Serbian.json"
                },
        order: [
          [0, 'desc']
        ],
        rowGroup: {
          // Uses the 'row group' plugin
          dataSrc: 0,
          startRender: function(rows, group) {
            var collapsed = !!collapsedGroups[group];

            rows.nodes().each(function(r) {
              r.style.display = 'none';
              if (collapsed) {
                r.style.display = '';
              }
            });

            // Add category name to the <tr>. NOTE: Hardcoded colspan
            return $('<tr/>')
              .append('<td colspan="7">' + group + ' <br /> Broj elemenata: <strong>' + rows.count() + '</strong></td>')
              .attr('data-name', group)
              .toggleClass('collapsed', collapsed);
          }
        }
      });
    
      $('#example tbody').on('click', 'tr.group-start', function() {
        var name = $(this).data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        oTable.draw(false);
      });

      
    });
  </script>
  </body>
</html>
