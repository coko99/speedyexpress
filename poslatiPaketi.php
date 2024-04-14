<?php
  use chillerlan\QRCode\QRCode;

  include('config/session_user.php');
  require 'vendor/autoload.php';
  $packages = [];

  if(isset($_GET['dateFrom']) && isset($_GET['dateTo'])){

    $datetimeFrom = mysqli_real_escape_string($db, $_GET['dateFrom']);
    $datetimeTo = mysqli_real_escape_string($db, $_GET['dateTo']);

  // $sql = "SELECT package.*, 
  // municipality.name AS municipality_name, 
  // municipality.zip AS zip,
  // street.name AS street_name,
  // status.name AS status_name,
  // package_status_tracking.datetime AS datetime_status
  // FROM `package`
  // LEFT JOIN street ON package.street_id = street.id
  // LEFT JOIN municipality ON street.municipality_id = municipality.id
  // LEFT JOIN status ON package.status_id = status.id
  // LEFT JOIN package_status_tracking ON package.id = package_status_tracking.package_id 
  // WHERE package.firm_id = $firm_id AND package.status_id != 1
  // AND (package_status_tracking.status = 1 OR package_status_tracking.status IS NULL)
  // AND FROM_UNIXTIME(package.send_time) BETWEEN STR_TO_DATE('$datetimeFrom','%d/%m/%Y') AND STR_TO_DATE('$datetimeTo', '%d/%m/%Y') ";
  
  $sql = "SELECT package.*, 
  municipality.name AS municipality_name, 
  municipality.zip AS zip,
  street.name AS street_name,
  status.name AS status_name,
  grup.number_of_packages AS number_of_packages,
  GROUP_CONCAT(status_tracking.name, '-', package_status_tracking.datetime SEPARATOR '<br\>') as status_tracking_gr
  FROM `package`
  LEFT JOIN street ON package.street_id = street.id
  LEFT JOIN municipality ON street.municipality_id = municipality.id
  LEFT JOIN status ON package.status_id = status.id
  LEFT JOIN package_status_tracking ON package.id = package_status_tracking.package_id 
  LEFT JOIN status as status_tracking ON package_status_tracking.status_id = status_tracking.id
  LEFT JOIN grup ON package.group_id = grup.id
  WHERE package.firm_id = $firm_id AND package.status_id != 1 AND
  FROM_UNIXTIME(package.send_time) BETWEEN STR_TO_DATE('$datetimeFrom','%d/%m/%Y') AND STR_TO_DATE('$datetimeTo', '%d/%m/%Y')
  GROUP BY package.id;";
  
  $result = mysqli_query($db, $sql);
  while($row = mysqli_fetch_array($result)) {
    array_push($packages, $row);
  }

}

?><!DOCTYPE html>
<html lang="sr">
  <?php
    include('config/head.php');

  ?>
  
  <body>
    
    <?php
      $active = 2;
      include('config/navbar.php');
    ?>


    <div class="container mt-4">
      <div class="row mb-4 text-left">
        <h2 class="mt-3 mb-3">Poslati paketi</h2>
      </div>

      <div class="row mb-4 text-left">
        <form method="GET">
            <div class="row py-3 px-3">
              <div class="input-group input-daterange" id="datepicker">
                  <div class="input-group-addon mx-2 my-2">Datum slanja od</div>
                  <span class="input-group-append">
                      <span class="input-group-text bg-white d-block">
                          <i class="fa fa-calendar"></i>
                      </span>
                  </span>
                  <input autocomplete="off" requried value="<?php if(isset($_GET['dateFrom'])) echo $_GET['dateFrom'];?>" required name='dateFrom' type="text" class="form-control"></input>
                  <div class="input-group-addon mx-2 my-2">do</div>
                  <span class="input-group-append">
                      <span class="input-group-text bg-white d-block">
                          <i class="fa fa-calendar"></i>
                      </span>
                  </span>
                  <input autocomplete="off" requried value="<?php if(isset($_GET['dateTo'])) echo $_GET['dateTo'];?>" required name='dateTo' type="text" class="form-control">
                  <button class='btn btn-info' onclick="startSpiner()" type="submit">Potvrdi</button>

                </div>
            </div> 
          </form>
      </div>


      <div class="row">
      <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">

          <div  class="d-flex justify-content-center">
                <div id="dt_loader" class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>
            <div class="col-12">
            <table  id="example" style="display:none" class="display" style="width:100%" >
              <thead>
                <tr>
                <th style="display: none;" scope="col">Send time</th>
                  <th scope="col">#</th>
                  <th scope="col">ID paketa</th>
                  <th scope="col">Primalac</th>
                  <th scope="col">Primalac - opština</th>
                  <th scope="col">Primalac - poštanski broj</th>
                  <th scope="col">Primalac - adresa</th>
                  <th scope="col">Primalac - telefon</th>
                  <th scope="col">Cena</th>
                  <th scope="col">Plaća</th>
                  <th scope="col">Napomena</th>
                  <th scope="col">Grupa</th>
                  <th scope="col">Broj paketa u grupi</th>
                  <th scope="col">Status</th>
                  <th scope="col">Opcije</th>
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
                    $status_id =$package['status_id'];
                    $numOfPackages = $package['number_of_packages'];
                    $orderInGrupu = $package['order_in_group'];
                    $grupId = sprintf('SX%08d', $package['group_id']);
                    
                    $status_tracking = $package['status_tracking_gr'];

                    echo "<tr>
                            <td style='display: none;' >$send_time</td>
                            <th scope='row'>$package_id</th>
                            <td>
                              <img class='qr-slika' src='".(new QRCode())->render($package_id.'-'.$token)."' alt='QR Code' />
                            </td>
                            <td>
                              <h6>$recipient</h6>
                            </td>
                            <td>
                              <h6>$municipality_name</h6
                            </td>
                            <td>
                              <h6>$zip</h6>
                            </td>
                            <td>
                              <h6>$street_name $street_number</h6>
                            </td>
                            <td>
                              <h6>$phone</h6>
                            </td>
                            <td>
                              <h6>$ransome</h6>
                            </td>
                            <td>
                              <h6>$paid_by</h6>
                            </td>
                            <td>
                              <h6>$comment</h6>
                            </td>
                            <td>
                              <h6>$grupId</h6>
                            </td>
                            <td>
                              <h6>$orderInGrupu/$numOfPackages</h6>
                            </td>
                          <td>$status_tracking</td>";
                            if($status_id != 4){
                              echo "<td><a href='izmeniPaket.php?id=$package_id' class='btn btn-info'>Izmeni</a></td>";
                            }else{
                              echo "<td><button disabled class='btn btn-info'>Izmeni</button></td>";
                            }
                          echo "</tr>";
                  }

                ?>
              </tbody>
            </table>
        </div>
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
        dom: 'Bfrtip',
          buttons: [
              'excel'
          ],
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
              .append('<td colspan="14">' + group + ' <br /> Broj elemenata: <strong>' + rows.count() + '</strong></td>')
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
      $('#dt_loader').hide();
        $('#example').show(); 
      
    });

    function startSpiner() {
        $('#dt_loader').show();
        $('#example').hide(); 
      }
  </script>

<script src="index.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
    <script src="bootstrap-datetimepicker.js"></script>
    <script type="text/javascript">
    $(function() {
        $('#datepicker').datepicker({
          format: 'dd/mm/yyyy',
          language: 'rs-latin',
          
        });
    });

    var startDate,
        endDate;
        
      $('#weekpicker').datepicker({
        autoclose: true,
        format :'dd/mm/yyyy',
        forceParse :false
    }).on("changeDate", function(e) {
        //console.log(e.date);
        var date = e.date;
        startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
        endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay()+6);
        //$('#weekpicker').datepicker("setDate", startDate);
        $('#weekpicker').datepicker('update', startDate);
        $('#weekpicker').val(startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getDate()  + ' / ' + endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getDate());
    });
        
    </script>
  </body>
</html>
