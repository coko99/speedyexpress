<?php
  include('config/session_admin.php');
  use chillerlan\QRCode\QRCode;

  require 'vendor/autoload.php';
?><!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

  $sql = "SELECT package.*, 
  municipality.name AS municipality_name, 
  municipality.zip AS zip,
  street.name AS street_name,
  status.name AS status_name,
  firm_street.name AS firm_street_name,
  firm_municipality.name AS firm_municipality_name, 
  firm_municipality.zip AS firm_zip,
  firm.name AS firm_name,
  firm.street_number AS firm_street_number,
  firm.phone AS firm_phone,
  package_status_tracking.datetime as pst_date,
  package_status_tracking.status as pst_status
  FROM `package`
  LEFT JOIN street ON package.street_id = street.id
  LEFT JOIN municipality ON street.municipality_id = municipality.id
  LEFT JOIN status ON package.status_id = status.id
  LEFT JOIN firm ON package.firm_id = firm.id
  LEFT JOIN street AS firm_street ON firm.street_id = firm_street.id
  LEFT JOIN package_status_tracking ON package.id = package_status_tracking.package_id 
  LEFT JOIN municipality AS firm_municipality ON firm_street.municipality_id = firm_municipality.id
  WHERE package_status_tracking.status = 1 OR package_status_tracking.status IS NULL;
  ";
  $result = mysqli_query($db, $sql);
  $packages = [];
  while($row = mysqli_fetch_array($result)) {
    array_push($packages, $row);
  }
?>
  <body>

  <?php
      $active = 4;
    include('config/navbar.php');
  ?>

    <div class="container mt-4">
      <div class="row mb-4 text-left">
        <h2 class="mt-3 mb-3">Paketa kurira</h2>
        <div class="row">
          <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
            <table id="example" class="table table-bordered table-striped mb-0">
              <thead>
                <tr>
                  <th scope="col ">#ID</th>
                  <th scope="col">QR</th>
                  <th scope="col">Primalac</th>
                  <th scope="col">Pošiljalac</th>
                  <th scope="col">Opis</th>
                  <th scope="col">PTT</th>
                  <th scope="col">Status</th>
                  <th scope="col">VREME I DATUM STATUSA</th>
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

                  $firm_name = $package['firm_name'];
                  $firm_street_number = $package['firm_street_number'];
                  $firm_street_name = $package['firm_street_name'];
                  $firm_zip = $package['firm_zip'];
                  $firm_municipality_name = $package['firm_municipality_name'];
                  $firm_phone = $package['firm_phone'];
                  $id_package = $package['id'];
                  $package_status = $package['status_name'];
                  $pst_date = $package['pst_date'];
                  $ptt = $package['ptt'];

                  $token = $package['token'];

                  echo "
                  <tr>
                  <th scope='row'>$id_package</th>
                  <td><img class='qr-slika' src='".(new QRCode())->render($package_id.'-'.$token)."' alt='' /></td>
                  <td>
                    <h6>$recipient</h6>
                    <h6>$municipality_name $zip</h6>
                    <h6>$street_name $street_number</h6>
                    <h6>$phone</h6>
                  </td>
                  <td>
                    <h6>$firm_name</h6>
                    <h6>$firm_municipality_name $firm_zip</h6>
                    <h6>$firm_street_name $firm_street_number</h6>
                    <h6>$firm_phone</h6>
                  </td>
                  <td>
                    <h6><strong>Otkup: </strong>$ransome rsd</h6>
                    <h6><strong>Vrednost: </strong>$ransome rsd</h6>
                    <h6><strong>Plaća: </strong>$paid_by</h6>
                    <h6><strong>napomena: </strong>$comment</h6>
                  </td>
                  <td><h6>$ptt RSD</h6></td>
                  <td>$package_status</td>
                  <td>$pst_date</td>
                </tr>
                  ";

                }
                ?>
             
              </tbody>
            </table>
          </div>
        </div>
    </div>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.0.2/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>
    
    <script src="index.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
    <script>

      $(document).ready(function () {

        $('#example thead tr')
              .clone(true)
              .addClass('filters')
              .appendTo('#example thead');

          var collapsedGroups = {};

        var oTable = $('#example').DataTable({
          paging: false,
          // dom: 'Bfrtip',
          // buttons: [
          //     'copy', 'csv', 'excel', 'pdf', 'print'
          // ],
          paging: false,
          language: {
                      "url": "//cdn.datatables.net/plug-ins/1.10.18/i18n/Serbian.json"
                  },
          order: [
            [0, 'desc']
          ],
          orderCellsTop: true,
              fixedHeader: true,
              initComplete: function () {
                  var api = this.api();
      
                  // For each column
                  api
                      .columns()
                      .eq(0)
                      .each(function (colIdx) {
                          // Set the header cell to contain the input element
                          var cell = $('.filters th').eq(
                              $(api.column(colIdx).header()).index()
                          );
                          var title = $(cell).text();
                          $(cell).html('<input type="text" placeholder="' + title + '" />');
      
                          // On every keypress in this input
                          $(
                              'input',
                              $('.filters th').eq($(api.column(colIdx).header()).index())
                          )
                              .off('keyup change')
                              .on('change', function (e) {
                                  // Get the search value
                                  $(this).attr('title', $(this).val());
                                  var regexr = '({search})'; //$(this).parents('th').find('select').val();
      
                                  var cursorPosition = this.selectionStart;
                                  // Search the column for that value
                                  api
                                      .column(colIdx)
                                      .search(
                                          this.value != ''
                                              ? regexr.replace('{search}', '(((' + this.value + ')))')
                                              : '',
                                          this.value != '',
                                          this.value == ''
                                      )
                                      .draw();
                              })
                              .on('keyup', function (e) {
                                  e.stopPropagation();
      
                                  $(this).trigger('change');
                                  $(this)
                                      .focus()[0]
                                      .setSelectionRange(cursorPosition, cursorPosition);
                              });
                      });
              },
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
