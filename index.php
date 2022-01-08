<?php
// Debugging
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// Create connection 
$servername = "";
$username = "";
$password = "";
$database = "";
$conn = new mysqli($servername, $username, $password, $database);

// GET variables
$minprice = 0;
$maxprice = 700;
$apartmentget = 1;
$houseget = 1;
if(isset($_GET['minprice'])) {
$minprice = $_GET['minprice'];
}
if(isset($_GET['maxprice'])) {
$maxprice = $_GET['maxprice'];
}
if(isset($_GET['apartment'])) {
$apartmentget = $_GET['apartment'];
} elseif (isset($_GET['minprice'])) {
$apartmentget = 0;
}
if(isset($_GET['house'])) {
$houseget = $_GET['house'];
} elseif (isset($_GET['minprice'])) {
$houseget = 0;
}
?>

<!DOCTYPE html>
<html>
 <head>
  <title>Nieuwbouwprojecten Gent</title>

  <!-- Responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/jpg" href="map-pin.svg"/>
  
  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

  <!-- Mapbox -->
  <script src='https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.css' rel='stylesheet' />

  <!-- No UI Slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.4/nouislider.min.js" integrity="sha512-0Z2o7qmtl7ixxWcEQxxTCT8mX4PsdffSGoVJ7A80zqt6DvdEHF800xrsSmKPkSoUaHtlIhkLAhCPb/tkf78SCA==" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.4/nouislider.min.css" integrity="sha512-8czuHxKbajKuQfbgBv5iwqftC1PbeLPmgVOYo8ZDlcOdi0OV18E+BbGQdqXs490kV9ZmJQTNupd0kvW8hokJlw==" crossorigin="anonymous" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.2.0/wNumb.min.js" integrity="sha512-igVQ7hyQVijOUlfg3OmcTZLwYJIBXU63xL9RC12xBHNpmGJAktDnzl9Iw0J4yrSaQtDxTTVlwhY730vphoVqJQ==" crossorigin="anonymous"></script>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VGXL9GXMTY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VGXL9GXMTY');
</script>

  <!-- Stylesheet -->
  <style>
   html {
    font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
   }


   #map { 
    position: absolute; 
    bottom: 0; 
    left: 0; 
    right: 0;
   }

   @media (min-width:688px){
    #map { 
     top: 160px; 
    }
   }

   @media (max-width:688px){
    #map {
     top: 175px; 
    }
   }

   @media (max-width:600px){
    #map {
     top: 165px; 
    }
   }

   @media (max-width:550px){
    #map {
     top: 162px; 
    }
   }

   @media (max-width:500px){
    #map {
     top: 157px; 
    }
   }

   @media (max-width:450px){
    #map {
     top: 152px; 
    }
   }

   @media (max-width:450px){
    #map {
     top: 148px; 
    }
   }

   @media (max-width:379px){
    #map { 
     top: 180px; 
    }
   }


   .name, .namered, .price, .leaflet-popup-content p {
    margin: 0;
   }

   .leaflet-popup-content {
    margin: 6px 8px;
   }

   .leaflet-popup-content-wrapper {
    border-radius: 8px;
   }

   .leaflet-popup-tip-container {
    margin-top: -1px;
   }

   #priceslider {
    width: 160px;
    margin: 20px 10px 10px 4px;
    float: left;
   }

   .noUi-connect {
    background-color: #2d84cb;
   }

   .noUi-horizontal {
    height: 10px;
   }

   .noUi-horizontal .noUi-handle {
    height: 12px;
    width: 12px;
    top: -2px;
    right: -6px;
   }

   .noUi-tooltip {
    padding: 2px;
    font-size: 11px;
   }

   .noUi-handle:after, .noUi-handle:before {
    display: none;
   }

   h1 {
    margin: 8px 20px 6px 0px;
    color: #2d84cb;
    display: inline;
    float: left;
    font-size: 22px;
   }

   #submitfilter {
    background-color: #2d84cb;
    color: white;
    font-weight: bold;
    border-radius: 4px;
    border: none;
    padding: 5px;
    font-size: 18px;
    margin: 0px 5px 10px 5px;
   }

   #filters {
    display: inline;
    width: 380px;
    float: left;
   }

   @media (max-width:380px){
    #filters {
     width: 305px;
    }
   }

   @media (min-width:380px){
    #filters {
     width: 365px;
    }
   }

   .realestatetype {
    font-size: 12px;
    line-height: 10px;
   }

   #realestatetypebox {
    display: inline;
    float: left;
    margin: -4px 10px 0px 15px;
    padding-bottom: 5px;
   }

   input[type=checkbox] {
    vertical-align: middle;
    position: relative;
    border: 1px solid black;
   }

   .extension {
       font-size: 10px;
   }

   a {
    color: #2d84cb;
   }

   *,*:focus,*:hover{
    outline:none;
}

.info {
    line-height: 16px;
    margin: 0px 0px 4px 0px;
}

.responsive {
  width: 100%;
  max-width: 728px;
  height: auto; 
}

.namered {
color: #ff002a;
}

  </style>
 </head>
 <body>

  <h1>NieuwbouwprojectenGent<span class="extension">.be</span></h1>

  <!-- Filters -->
  <form id="filters" methode="get">
   <input id="minprice" name="minprice" value="handlevalue[0]" type="hidden" /> 
   <input id="maxprice" name="maxprice" value="handlevalue[1]" type="hidden" /> 
   <div id="priceslider"></div>
   <div id="realestatetypebox">
    <input type="checkbox" name="apartment" id="apartmentinput" value=1 class="realestatetype" <?php if($apartmentget==1) { ?>checked<?php } ?> />
    <label for="apartmentinput" class="realestatetype">Appartement</label><br/>
    <input type="checkbox" name="house" id="houseinput" value=1 class="realestatetype" <?php if($houseget==1) { ?>checked<?php } ?> />
    <label for="houseinput" class="realestatetype">Huis</label>
   </div>
   <input type="submit" value="Filter" id="submitfilter" />
  </form>

  <!-- Code for slider -->
  <script>
   var priceFormat = wNumb({decimals: 0, thousand: '.', prefix: '&euro;', suffix: 'k'});

   var slider = document.getElementById('priceslider');

   noUiSlider.create(priceslider, {
    start: [<?= $minprice ?>, <?= $maxprice ?>],
    tooltips: [true, true],
    connect: true,
    step: 10,
    margin: 50,
    connect: [false, true, false],
    tooltips: [priceFormat, priceFormat],
    range: {'min': 0, 'max': 700},
    format: wNumb({ decimals: 0 })
   });

   var inputpricemin = document.getElementById('minprice');
   var inputpricemax = document.getElementById('maxprice');
   
   priceslider.noUiSlider.on('update', function (values, handle, unencoded) {
    var priceMinValue = values[0];
    var priceMaxValue = values[1];
    inputpricemin.value = priceMinValue;
    inputpricemax.value = priceMaxValue;
   });

   inputprice.addEventListener('change', function () {
    priceslider.noUiSlider.set([null, this.value]);
   });
  </script>

<br/><br/><br/><a href="mailto:info@nieuwbouwprojectengent.be" target="_blank"><img src="banner.png" alt="banner" width="728" height="90" class="responsive" /></a>

  <!-- Map -->
  <div id = "map"></div>

  <!-- Code for map -->
  <script>
  mapboxgl.accessToken = 'pk.eyJ1IjoibG91aXNkZWNvbmluY2siLCJhIjoiY2tuNHM1aW95MXVkMjJudDdkdXhxejE0bSJ9.-YiI1Yg5j4E4MCpL9wybHg';

  var bounds = [
   [3.470349, 50.950000], // Southwest coordinates
   [3.957122, 51.106809] // Northeast coordinates
  ];

  var map = new mapboxgl.Map({
   container: 'map',
   style: 'mapbox://styles/mapbox/streets-v11',
   center: [3.722333, 51.039950],
   zoom: 12,
   maxBounds: bounds // Sets bounds as max
  });

  <?php
  // Create query
  if ($apartmentget == 1 && $houseget == 0) {
   $sql = "SELECT * FROM projects WHERE available = 1 AND pricemin < $maxprice*1000 AND pricemax > $minprice*1000 AND apartments > 0";
  } elseif ($apartmentget == 0 && $houseget == 1) {
   $sql = "SELECT * FROM projects WHERE available = 1 AND pricemin < $maxprice*1000 AND pricemax > $minprice*1000 AND houses > 0";
  } else {
   $sql = "SELECT * FROM projects WHERE available = 1 AND pricemin < $maxprice*1000 AND pricemax > $minprice*1000";
  }

  $result = mysqli_query($conn, $sql);

  // List results
  while($row = mysqli_fetch_assoc($result)) {
$id = $row['id'];
   $name = $row['name'];
   $url = $row['url'];
   $pricemin = number_format($row['pricemin'], 0, ',', '.');
   $pricemax = number_format($row['pricemax'], 0, ',', '.');
   $apartments = $row['apartments'];
   $houses = $row['houses'];
   $roomsmin = $row['roomsmin'];
   $roomsmax = $row['roomsmax'];
   $surfacemin = $row['surfacemin'];
   $surfacemax = $row['surfacemax'];
   $image = $row['image'];
   $coordx = $row['coordinatex'];
   $coordy = $row['coordinatey'];
   $promo = $row['promo'];
   ?>

   var popup<?= $id ?> = new mapboxgl.Popup({ offset: 25 }).setHTML(
    '<a href="<?= $url ?>" target="_blank"><h2 class="name<?php if ($promo == 1) { echo "red"; } ?>"><?= $name ?></h2></a><b class="price">&euro;<?php echo "$pricemin"; if ($pricemin != $pricemax) { ?> - &euro;<?php echo "$pricemax"; } ?></b><p class="info"><?= $apartments ?> appartementen <?php if($houses!=0) {?> & <?= $houses ?> huizen<?php } ?><br/><?php echo"$roomsmin"; if ($roomsmin != $roomsmax) { ?> - <?php echo"$roomsmax"; } ?> slaapkamers | <?php echo"$surfacemin"; if ($surfacemin != $surfacemax) { ?> - <?php echo"$surfacemax"; } ?>m&sup2;</p><img src="/images/<?= $image ?>" alt="Afbeelding <?= $name ?> project" width="180" height="120" /></div>'
   );

   var marker<?= $id ?> = new mapboxgl.Marker({ "color": "#<?php if ($promo == 1) { echo "ff002a"; } else { echo "2d84cb"; } ?>" })
   .setLngLat([<?= $coordy?>, <?= $coordx ?>])
   .setPopup(popup<?= $id ?>)
   .addTo(map);
     
  <?php   
  }
  ?>

  </script>
 </body>
</html>
