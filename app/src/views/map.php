<html>
  <head>
    <title>GPS Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <style>
      #map {
        height: 100%;
      }
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .filter {
        background-color: #e9e9e9;
        border-bottom: 1px solid #ccc;
      }
      form {
        padding: 0px;
        margin: 0px;
      }
    </style>
  </head>
  <body>
    <div class="filter">
      <form action="" method="GET">
        <table cellspacing="8">
          <tr>
            <th align="left">Device:</th>
            <th align="left">Time:</th>
            <th align="left">Accuracy:</th>
            <th align="left">Provider:</th>
          </tr>
          <tr>
            <td>
              <select name="device" onchange="this.form.submit()">
              <?php 
                foreach($devices as $v) {
                  $selected = (isset($_GET["device"]) && $_GET["device"] == $v->device || !isset($_GET["device"]) && !empty($default["device"]) && $default["device"] == $v->device) ? "selected" : "";
                  echo '<option value="'.$v->device.'" '.$selected.'>'.$v->device.'</option>';
                }
              ?>
              </select>
            </td>
            <td>
              <select name="time" onchange="this.form.submit()">
              <?php 
                foreach($time as $v) {
                  $selected = (isset($_GET["time"]) && $_GET["time"] == $v || !isset($_GET["time"]) && !empty($default["time"]) && $default["time"] == $v) ? "selected" : "";
                  echo '<option value="'.$v.'" '.$selected.'>'.$v.'</option>';
                }
              ?>
              </select>
            </td>
            <td>
              <select name="accuracy" onchange="this.form.submit()">
              <?php 
                foreach($accuracy as $v) {
                  $selected = (isset($_GET["accuracy"]) && $_GET["accuracy"] == $v || !isset($_GET["accuracy"]) && !empty($default["accuracy"]) && $default["accuracy"] == $v) ? "selected" : "";
                  echo '<option value="'.$v.'" '.$selected.'>'.$v.'</option>';
                }
              ?>
              </select>
            </td>
            <td>
              <select name="provider" onchange="this.form.submit()">
              <?php 
                foreach($provider as $v) {
                  $selected = (isset($_GET["provider"]) && $_GET["provider"] == $v || !isset($_GET["provider"]) && !empty($default["provider"]) && $default["provider"] == $v) ? "selected" : "";
                  echo '<option value="'.$v.'" '.$selected.'>'.$v.'</option>';
                }
              ?>
              </select>
            </td>
          </tr>
        </table>
      </form>
    </div>

    <div id="map"></div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<KEY>&callback=initMap&v=weekly" defer></script>
    <script>
    function initMap() {
      var coordinates = [];
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });
      
      $.ajax({
        url: '<?php echo $api_url; ?>',
        headers: {
            'Authorization': 'Bearer test',
        },
        method: 'POST',
        dataType: 'json',
        data: {
          device: '<?php echo isset($_GET["device"]) ? $_GET["device"] : $default['device']; ?>',
          time: '<?php echo isset($_GET["time"]) ? $_GET["time"] : $default['time']; ?>',
          accuracy: '<?php echo isset($_GET["accuracy"]) ? $_GET["accuracy"] : $default['accuracy']; ?>',
          provider: '<?php echo isset($_GET["provider"]) ? $_GET["provider"] : $default['provider']; ?>',
        },
        error: function (xhr, status, error) {
            alert(error);
        },
        success: function(data){
          console.log(data);

          var startPoint  = new google.maps.LatLng(data.data[0]);
          var startMarker = new google.maps.Marker({
            position: startPoint,
            map: map,
          });
          var endPoint  = new google.maps.LatLng(data.data.slice(-1)[0]);
          var endMarker = new google.maps.Marker({
            position: endPoint,
            map: map
          });

          // Create the markers.
          // data.data.forEach((position) => {
          //   const marker = new google.maps.Marker({
          //     position,
          //     map,
          //     title: "1",
          //     label: "2",
          //     optimized: false,
          //   });
      
          //   marker.addListener("click", () => {
          //     infoWindow.close();
          //     infoWindow.setContent(marker.getTitle());
          //     infoWindow.open(marker.getMap(), marker);
          //   });
          // });

          var flightPath = new google.maps.Polyline({
            path: data.data,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 5
          });
        
          flightPath.setMap(map);
          map.setCenter(startPoint);
        }
      });
    }

    window.initMap = initMap;
    </script>
  </body>
</html>