<?php
  include('config/session_user.php');


  $sql = "SELECT package.*, 
  municipality.name AS municipality_name, 
  municipality.zip AS zip,
  street.name AS street_name 
  FROM `package`
  LEFT JOIN street ON package.street_id = street.id
  LEFT JOIN municipality ON street.municipality_id = municipality.id
  WHERE firm_id = $firm_id AND status_id = 1";
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
            <th style="display: none;" scope="col">Vreme</th>
              <th style="display: none;" scope="col">ID porudzbine</th>
              <th scope="col">Jelo</th>
              <th scope="col">Cena</th>
              <th scope="col">Količina</th>
              <th scope="col">Ukupna cena</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(count($purchase_orders) > 0){
              $i = 0;
              while($i < count($purchase_orders))
              {
                $purchase_order = $purchase_orders[$i];

                $dish_name = $purchase_order['dish_name'];
                $dish_description = $purchase_order['dish_description'];
                $dish_image_name = $purchase_order['dish_image_name'];
                $dish_weight = $purchase_order['dish_weight'];

                $created_at = $purchase_order['created_at'];
                $note = $purchase_order['note'];
                $quantity = $purchase_order['quantity'];
                $user_email = $purchase_order['user_name']." ".$purchase_order['user_last_name'];
                $price = $purchase_order['price'];
                $purchase_order_id = $purchase_order['purchase_order_id'];
                $sum = $purchase_order['sum'];

                
                $day = $purchase_order['day'];
                $month = $purchase_order['month'];
                $year = $purchase_order['year'];

                $quantity_price = $quantity * $price;

                echo "<tr>
                        <td style='display: none;'>
                        $year-$month-$day;
                      </td>
                        <td style='display: none;'>ID: <strong>$purchase_order_id</strong> <br />
                        Vreme poručivanja: <strong>$created_at </strong><br />
                        Korisnik: <strong>$user_email </strong><br />
                        Ukupna cena: <strong>$sum RSD </strong><br />
                        Datum isporuke: <strong>$day/$month/$year</strong><br />
                        Napomena: <strong>$note</strong>
                        </td>
                        <td>$dish_name</td>
                        <td>$price rsd</td>
                        <td>$quantity</td>
                        <td>$quantity_price rsd</td>

                      </tr>";
                    $i++;
              }
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
      dataSrc: 1,
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
