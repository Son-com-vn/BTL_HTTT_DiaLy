<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>The first webgis: View Background Map</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
        <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
        <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
		
        
        <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
        <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
       
        <link rel="stylesheet" href="http://localhost:8081/libs/openlayers/css/ol.css" type="text/css" />
        <script src="http://localhost:8081/libs/openlayers/build/ol.js" type="text/javascript"></script>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>
       
        <script src="http://localhost:8081/libs/jquery/jquery-3.4.1.min.js" type="text/javascript"></script>
    </head>
    <body onload="initialize_map();">
        <table>
            <tr>
                <td>
                    <div id="map" style="width: 80vw; height: 100vh;"></div>
                </td>
                <td>
                    <button>Button</button>
                </td>
            </tr>
        </table>
        <script>
            var format = 'image/png';
            var map;
            var minX = 8.49874900000009;
            var minY = 1.65254800000014;
            var maxX = 16.1921150000001;
            var maxY = 13.0780600000001;
            var cenX = (minX + maxX) / 2;
            var cenY = (minY + maxY) / 2;
            var mapLat = cenY;
            var mapLng = cenX;
            var mapDefaultZoom = 6;
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
                    layers: [layerBG],
                    view: viewMap
                });
            };
        </script>
    </body>
  
  
  </html>