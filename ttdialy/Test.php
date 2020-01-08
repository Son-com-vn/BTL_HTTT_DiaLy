<!doctype html>
<html lang="en">

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.1.1/css/ol.css"
    type="text/css">
  <style>
    .map {
      height: 400px;
      width: 100%;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.1.1/build/ol.js"></script>
  <title>OpenLayers example</title>
</head>

<body onload="initialize_map()">
  <h2>My Map</h2>
  <div id="map" class="map"></div>
  <script type="text/javascript">
    var format = 'image/png';
    var map;
    var minX = 102.107963562012;
    var minY = 8.30629825592041;
    var maxX = 109.505798339844;
    var maxY = 23.4677505493164;
    var cenX = (minX + maxX) / 2;
    var cenY = (minY + maxY) / 2;
    var mapLat = cenY;
    var mapLng = cenX;
    var mapDefaultZoom = 4;
    var layerCMR_adm1 = new ol.layer.Image({
      source: new ol.source.ImageWMS({
        ratio: 1,
        url: 'http://localhost:8087/geoserver/alo/wms?',
        params: {
          'FORMAT': format,
          'VERSION': '1.1.1',
          STYLES: '',
          LAYERS: 'gadm36_vnm_1',
        }
      })
    });
    function initialize_map() {
      layerBG = new ol.layer.Tile({
        source: new ol.source.OSM({})
      });
      var viewMap = new ol.View({
        center: ol.proj.fromLonLat([mapLng, mapLat]),
        zoom: mapDefaultZoom
      });
      map = new ol.Map({
        target: "map",
        layers: [layerBG,layerCMR_adm1],
        view: viewMap
      });
    };
  </script>
</body>

</html>