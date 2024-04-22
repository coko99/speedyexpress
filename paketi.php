<?php
  include('config/session_admin.php');
  use chillerlan\QRCode\QRCode;

  require 'vendor/autoload.php';
?><!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

  if(isset($_GET['odabranaFirma']) && ctype_digit($_GET['odabranaFirma'])){
    $odabranaFirma = (int)$_GET['odabranaFirma'];
  }else{
    $odabranaFirma = -1;
  }

  if(isset($_GET['odabraniStatus']) && ctype_digit($_GET['odabraniStatus'])){
    $odabraniStatus = (int)$_GET['odabraniStatus'];
  }else{
    $odabraniStatus = -1;
  }

  if(isset($_GET['odabraniPlaceno']) && ctype_digit($_GET['odabraniPlaceno'])){
    $odabraniPlaceno = (int)$_GET['odabraniPlaceno'];
  }else{
    $odabraniPlaceno = -1;
  }

  $packages = [];
  if(isset($_GET['idsForSearch'])){

    $idsForSearch = mysqli_real_escape_string($db, $_GET['idsForSearch']);

    $sql = "SELECT
    package.*,
    pst.status_tracking_log as status_tracking,
    package_status_tracking.datetime as active_status_date_time,
    municipality.name AS municipality_name, 
    municipality.zip AS zip,
    street.name AS street_name,
    status_tracking.name AS status_name,
    firm_street.name AS firm_street_name,
    firm_municipality.name AS firm_municipality_name, 
    firm_municipality.zip AS firm_zip,
    firm.name AS firm_name,
    firm.street_number AS firm_street_number,
    firm.phone AS firm_phone,
    courier.name AS courier,
    grup.number_of_packages AS number_of_packages_in_group,
    package.group_id AS group_id,
    package.order_in_group AS order_in_group
    FROM
      package
    LEFT JOIN (
      SELECT
          package_id,
          GROUP_CONCAT(s.name, ' - ', datetime order by datetime desc SEPARATOR '<br/>') AS status_tracking_log
      FROM
          package_status_tracking
          left join status s on package_status_tracking.status_id = s.id
      GROUP BY
          package_id
    ) pst ON package.id = pst.package_id
    LEFT JOIN package_status_tracking on package.id = package_status_tracking.package_id
    LEFT JOIN status on package_status_tracking.status_id = status.id
    LEFT JOIN street ON package.street_id = street.id
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    LEFT JOIN firm ON package.firm_id = firm.id
    LEFT JOIN street AS firm_street ON firm.street_id = firm_street.id
    LEFT JOIN municipality AS firm_municipality ON firm_street.municipality_id = firm_municipality.id
    LEFT JOIN status as status_tracking ON package.status_id = status_tracking.id
    LEFT JOIN courier on package.curier_id = courier.id
    LEFT JOIN grup on package.group_id = grup.id
    WHERE 
    (package_status_tracking.status = 1 OR package_status_tracking.status IS NULL)
    AND package.id in ($idsForSearch)
    ";

    $result = mysqli_query($db, $sql);
    while($row = mysqli_fetch_array($result)) {
      array_push($packages, $row);
    }

  }else{

    if(isset($_GET['dateFrom']) && isset($_GET['dateTo'])){

      $datetimeFrom = mysqli_real_escape_string($db, $_GET['dateFrom']);
      $datetimeTo = mysqli_real_escape_string($db, $_GET['dateTo']);
    }else{
      $datetimeFrom = "".date("d/m/Y");
      $datetime = new DateTime('tomorrow');
      $datetimeTo = "".($datetime->format('d/m/Y'));
  
    }
    
   
      
  
    $sql = "SELECT
    package.*,
    pst.status_tracking_log as status_tracking,
    package_status_tracking.datetime as active_status_date_time,
    municipality.name AS municipality_name, 
    municipality.zip AS zip,
    street.name AS street_name,
    status_tracking.name AS status_name,
    firm_street.name AS firm_street_name,
    firm_municipality.name AS firm_municipality_name, 
    firm_municipality.zip AS firm_zip,
    firm.name AS firm_name,
    firm.street_number AS firm_street_number,
    firm.phone AS firm_phone,
    courier.name AS courier,
    grup.number_of_packages AS number_of_packages_in_group,
    package.group_id AS group_id,
    package.order_in_group AS order_in_group
    FROM
      package
    LEFT JOIN (
      SELECT
          package_id,
          GROUP_CONCAT(s.name, ' - ', datetime order by datetime desc SEPARATOR '<br/>') AS status_tracking_log
      FROM
          package_status_tracking
          left join status s on package_status_tracking.status_id = s.id
      GROUP BY
          package_id
    ) pst ON package.id = pst.package_id
    LEFT JOIN package_status_tracking on package.id = package_status_tracking.package_id
    LEFT JOIN status on package_status_tracking.status_id = status.id
    LEFT JOIN street ON package.street_id = street.id
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    LEFT JOIN firm ON package.firm_id = firm.id
    LEFT JOIN street AS firm_street ON firm.street_id = firm_street.id
    LEFT JOIN municipality AS firm_municipality ON firm_street.municipality_id = firm_municipality.id
    LEFT JOIN status as status_tracking ON package.status_id = status_tracking.id
    LEFT JOIN courier on package.curier_id = courier.id
    LEFT JOIN grup on package.group_id = grup.id
    WHERE FROM_UNIXTIME(package.send_time) BETWEEN STR_TO_DATE('$datetimeFrom','%d/%m/%Y') AND STR_TO_DATE('$datetimeTo', '%d/%m/%Y') 
    AND (package_status_tracking.status = 1 OR package_status_tracking.status IS NULL)
    ";
    if($odabranaFirma != -1){
      $sql = $sql." AND package.firm_id = $odabranaFirma";
    }
    if($odabraniStatus != -1){
      $sql = $sql." AND package.status_id = $odabraniStatus";
    }
    if($odabraniPlaceno != -1){
      $sql = $sql." AND package.pay = $odabraniPlaceno";
    }
    $sql = $sql."
    ;
    ";
    $result = mysqli_query($db, $sql);
    while($row = mysqli_fetch_array($result)) {
      array_push($packages, $row);
    }
    
    
    
  }

    $firms = [];
    $sql = "SELECT * FROM firm WHERE status = 1";
    $result = mysqli_query($db, $sql);
    while($row = mysqli_fetch_array($result)) {
      array_push($firms, $row);
    }
    
    $statusi = [];
    $sql = "SELECT * FROM status";
    $result = mysqli_query($db, $sql);
    while($row = mysqli_fetch_array($result)) {
      array_push($statusi, $row);
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
        <div class="row mb-4 text-left">
        <form method="GET">
            <div class="row py-3 px-3">
              <select name="odabranaFirma">
                <option id="-1">Sve firme</option>
                <?php 
                foreach($firms as $firm){
                  if($firm['id'] == $odabranaFirma){
                    echo "<option value=".$firm['id']." selected>".$firm['name']."</option>";
                  }else{
                    echo "<option value=".$firm['id'].">".$firm['name']."</option>";
                  }
                }
                ?>
              </select>
              <select name="odabraniStatus">
                <option id="-1">Svi statusi</option>
                <?php 
                foreach($statusi as $status){
                  if($status['id'] == $odabraniStatus){
                    echo "<option value=".$status['id']." selected>".$status['name']."</option>";
                  }else{
                    echo "<option value=".$status['id'].">".$status['name']."</option>";
                  }
                }
                ?>
              </select>
              <select name="odabraniPlaceno">
                <option <?php if($odabraniPlaceno == -1) echo "selected"; ?> value="-1">Plaćeno i neplaćeno</option>
                <option <?php if($odabraniPlaceno == 1) echo "selected"; ?> value="1">Plaćeno</option>
                <option <?php if($odabraniPlaceno == 0) echo "selected"; ?> value="0">Neplaćeno</option>
              </select>
              <br/>
            </div>
              <div class="row py-3 px-3">
              <div class="input-group input-daterange" id="datepicker">
                  <div class="input-group-addon mx-2 my-2">Datum slanja od</div>
                  <span class="input-group-append">
                      <span class="input-group-text bg-white d-block">
                          <i class="fa fa-calendar"></i>
                      </span>
                  </span>
                  <input autocomplete="off" requried value="<?php if(isset($datetimeFrom)) echo $datetimeFrom;?>" required name='dateFrom' type="text" class="form-control"></input>
                  <div class="input-group-addon mx-2 my-2">do</div>
                  <span class="input-group-append">
                      <span class="input-group-text bg-white d-block">
                          <i class="fa fa-calendar"></i>
                      </span>
                  </span>
                  <input autocomplete="off" requried value="<?php if(isset($datetimeTo)) echo $datetimeTo;?>" required name='dateTo' type="text" class="form-control">
                  <button class='btn btn-info' type="submit" onclick="startSpiner()">Potvrdi</button>

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

            <table style="display:none" id="example" class="table table-bordered table-striped mb-0">
              <thead>
                <tr>
                  <th scope="col ">#ID</th>
                  <th scope="col">QR</th>
                  <th scope="col">Primalac - ime</th>
                  <th scope="col">Primalac - opština</th>
                  <th scope="col">Primalac - poštanski broj</th>
                  <th scope="col">Primalac - ulica i broj</th>
                  <th scope="col">Primalac - telefon</th>
                  <th scope="col">Pošiljalac - naziv</th>
                  <th scope="col">Pošiljalac - opština</th>
                  <th scope="col">Pošiljalac - poštanski broj</th>
                  <th scope="col">Pošiljalac - ulica</th>
                  <th scope="col">Pošiljalac - telefon</th>
                  <th scope="col">Kurir</th>
                  <th scope="col">OTKUP</th>
                  <th scope="col">PLAĆA</th>
                  <th scope="col">NAPOMENA</th>
                  <th scope="col">GRUPA</th>
                  <th scope="col">PTT</th>
                  <th scope="col">TRENUTNI STATUS</th>
                  <th scope="col">DATUM STATUS</th>
                  <th scope="col">VREME STATUS</th>
                  <th scope="col">DATUM SLANJA</th>
                  <th scope="col">VREME SLANJA</th>
                  <th scope="col">STATUS - ISTORIJA</th>
                  <th scope="col">PLAĆENO</th>
                  <th scope="col">OTKUP + PTT</th>
                  <th scope="col">ŠTAMPAJ</th>
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
                  $courier = $package['courier'];
                  $number_of_packages_in_group = $package['number_of_packages_in_group'];
                  $grupId = sprintf('SX%08d', $package['group_id']);
                  $order_in_group = $package['order_in_group'];

                  $firm_name = $package['firm_name'];
                  $firm_street_number = $package['firm_street_number'];
                  $firm_street_name = $package['firm_street_name'];
                  $firm_zip = $package['firm_zip'];
                  $firm_municipality_name = $package['firm_municipality_name'];
                  $firm_phone = $package['firm_phone'];
                  $id_package = $package['id'];
                  
                  $ptt = $package['ptt'];
                  $pay = $package['pay'];

                  $status_track = $package['status_tracking'];
                  $package_status = $package['status_name'];
                  $send_time = date("d/m/Y - H:i:s", $package['send_time']);
                  $status_time = $package['active_status_date_time'];

                  $token = $package['token'];

                  $date_send ="";
                  $time_send="";
                  if(isset($send_time)){
                    $date_send = date("d/m/Y", $package['send_time']);
                    $time_send = date("H:i:s", $package['send_time']);
                  }else{
                    $date_send = date("d/m/Y", $package['created_at']);
                    $time_send = date("H:i:s", $package['created_at']);
                  }

                  if(isset($status_time)){
                    $date_status = date("d/m/Y", strtotime($package['active_status_date_time']));
                    $time_status = date("H:i:s", strtotime($package['active_status_date_time']));
                  }else{
                    $date_status = $date_send;
                    $time_status = $time_send;
                  }
                  
                  $qrtext = $package_id.'-'.$token;

                  echo "
                  <tr>
                  <th scope='row'>$id_package</th>
                  <td><img class='qr-slika' src='".(new QRCode())->render($qrtext)."' alt='' /></td>
                  <td>
                    <h6>$recipient</h6>
                  </td>
                  <td>
                    <h6>$municipality_name</h6>
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
                    <h6>$firm_name</h6>
                  </td>
                  <td>
                    <h6>$firm_municipality_name</h6>
                  </td>
                  <td>
                    <h6>$firm_zip</h6>
                  </td>
                  <td>
                    <h6>$firm_street_name $firm_street_number</h6>
                  </td>
                  <td>
                    <h6>$firm_phone</h6>
                  </td>
                  <td>
                    <h6>$courier</h6>
                  </td>
                  <td>
                    <h6>$ransome</h6>
                  </td>
                  <td>
                    <h6><strong>Plaća: </strong>$paid_by</h6>
                  </td>
                  <td>
                    <h6><strong>napomena: </strong>$comment</h6>
                  </td>
                  <td>
                    <h6>Grupa: $grupId</h6>
                    <h6>$order_in_group/$number_of_packages_in_group</h6>
                  </td>
                  <td><h6>$ptt</h6></td>
                  <td><h6>$package_status</h6></td>
                  <td><h6>$date_status</h6></td>
                  <td><h6>$time_status</h6></td>
                  <td><h6>$date_send</h6></td>
                  <td><h6>$time_send</h6></td>
                  <td>$status_track</td>
                  <td>$pay</td>
                  <td>". ($ransome+$ptt) ."</td>
                  <td>
                    <a class='btn btn-info' href='printPackagesAdmin.php?id=$id_package'>ŠTAMPAJ</a>
                    <br/>
                    <a class='btn btn-info' href='printPackagesAdminZebra.php?id=$id_package'>ŠTAMPAJ</a>
                  </td>
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
          dom: 'Bfrtip',
          buttons: [
              'excel'
          ],
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
                                  if(colIdx === 5){
                                      var searchTerm = this.value.toLowerCase(),
                                    regex = '^' + searchTerm;
                                    console.log(regex);
                                    api
                                      .column(colIdx)
                                      .search(
                                        this.value != ''
                                        ? regex
                                        : '',
                                          this.value != '',
                                          this.value == ''
                                      )
                                      .draw();
                                    
                                  }else{
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
                                  }
                                  
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

        $('#dt_loader').hide();
        $('#example').show(); 
      });

      function startSpiner() {
        $('#dt_loader').show();
        $('#example').hide(); 
      }

  </script>
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
