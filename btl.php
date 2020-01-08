<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OpenStreetMap &amp; OpenLayers - Marker Example</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>

    <link rel="stylesheet" href="http://localhost:8081/libs/openlayers/css/ol.css" type="text/css" />
    <script src="http://localhost:8081/libs/openlayers/build/ol.js" type="text/javascript"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>

    <script src="http://localhost:8081/libs/jquery/jquery-3.4.1.min.js" type="text/javascript"></script>
    <style>
        /*
            .map, .righ-panel {
                height: 500px;
                width: 80%;
                float: left;
            }
            */
        .map,
        .righ-panel {
            height: 98vh;
            width: 80vw;
            float: left;
        }

        .map {
            border: 1px solid #000;
        }

        .ol-popup {
            position: absolute;
            background-color: white;
            -webkit-filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
            filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
            padding: 50px;
            border-radius: 10px;
            border: 1px solid #cccccc;
            bottom: 30spx;
            left: 10px;
            min-width: 280px;
        }

        .ol-popup:after,
        .ol-popup:before {
            top: 100%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
        }

        .ol-popup:after {
            border-top-color: white;
            border-width: 10px;
            left: 48px;
            margin-left: -10px;
        }

        .ol-popup:before {
            border-top-color: #cccccc;
            border-width: 11px;
            left: 48px;
            margin-left: -11px;
        }

        .ol-popup-closer {
            text-decoration: none;
            position: absolute;
            top: 2px;
            right: 8px;
        }

        .ol-popup-closer:after {
            content: "✖";
        }
    </style>
</head>

<body onload="initialize_map();">
    <table>
        <tr>
            <td>
                <div id="map" class="map"></div>
                <div id="map" style="width: 50vw; height: 50vh;"></div>
                <div id="popup" class="ol-popup">
                    <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                    <div id="info"></div>
                </div>
                <!--<div id="map" style="width: 80vw; height: 100vh;"></div>-->
            </td>
            <td>
                
                <input onclick="oncheckhydropower();" type="checkbox" id="hydropower" value="Bike"> Thuy Dien<br>
                <input onclick="oncheckriver();" type="checkbox" id="river" value="Bike"> Song <br>
                <input onclick="oncheckvn();" type="checkbox" id="vn" value="Bike" checked> Viet Nam<br>


            </td>
        </tr>
    </table>
    <?php include 'CMR_pgsqlAPI.php' ?>
    <script>
        //$("#document").ready(function () {
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
        var layerCMR_adm1;
        var layer_river;
        var layer_hydropower;

        function oncheckhydropower() {
            if (document.getElementById('hydropower').checked) {
                map.addLayer(layer_hydropower)

            } else {
                map.removeLayer(layer_hydropower);
            }
        }

        function oncheckriver() {
            if (document.getElementById('river').checked) {
                map.addLayer(layer_river)

            } else {
                map.removeLayer(layer_river);
            }
        }


        function oncheckvn() {

            if (document.getElementById('vn').checked) {
                map.addLayer(layerCMR_adm1)

            } else {
                map.removeLayer(layerCMR_adm1);
            }

        }

        function initialize_map() {
            //*
            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });

            //*/
            layerCMR_adm1 = new ol.layer.Image({
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

            layer_river = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8087/geoserver/alo/wms?',
                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.1',
                        STYLES: '',
                        LAYERS: 'thuyhevn2000',
                    }
                })

            });

            layer_hydropower = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8087/geoserver/alo/wms?',
                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.1',
                        STYLES: '',
                        LAYERS: 'hydropower_dams',
                    }
                })

            });



            var viewMap = new ol.View({
                center: ol.proj.fromLonLat([mapLng, mapLat]),
                zoom: mapDefaultZoom
                //projection: projection
            });
            map = new ol.Map({
                target: "map",
                layers: [layerBG, layerCMR_adm1],
                //layers: [layerCMR_adm1],
                view: viewMap
            });
            //map.getView().fit(bounds, map.getSize());

            var styles = {
                'MultiPolygon': new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'orange'
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'yellow',
                        width: 2
                    })
                })
            };
            var styleFunction = function(feature) {
                return styles[feature.getGeometry().getType()];
            };
            var vectorLayer = new ol.layer.Vector({
                //source: vectorSource,
                style: styleFunction
            });
            // map.addLayer(vectorLayer);

            function createJsonObj(result) {
                var geojsonObject = '{' +
                    '"type": "FeatureCollection",' +
                    '"crs": {' +
                    '"type": "name",' +
                    '"properties": {' +
                    '"name": "EPSG:4326"' +
                    '}' +
                    '},' +
                    '"features": [{' +
                    '"type": "Feature",' +
                    '"geometry": ' + result +
                    '}]' +
                    '}';
                return geojsonObject;
            }

            function drawGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource
                });
                map.addLayer(vectorLayer);
            }

            function highLightGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                vectorLayer.setSource(vectorSource);
                /*
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource
                });
                map.addLayer(vectorLayer);
                */
            }

            // function highLightObj(result) {
            //     alert("result: " + result);
            //     var strObjJson = createJsonObj(result);
            //     //alert(strObjJson);
            //     var objJson = JSON.parse(strObjJson);
            //     alert(JSON.stringify(objJson));
            //     drawGeoJsonObj(objJson);
            //     highLightGeoJsonObj(objJson);
            // }

            function displayObjInfo(result, coordinate) {
                // alert("result: " + result);
                //alert("coordinate des: " + coordinate);
                $("#info").html(result);
            }
            map.on('singleclick', function(evt) {
                // alert("coordinate: " + evt.coordinate);
                var myPoint = 'POINT(12,5)';
                var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                var lon = lonlat[0];
                var lat = lonlat[1];
                var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                // alert("myPoint: " + myPoint);
                //*
                // $.ajax({
                //     type: "POST",
                //     url: "CMR_pgsqlAPI.php",
                //     //dataType: 'json',
                //     data: {
                //         functionname: 'getGeoCMRToAjax',
                //         paPoint: myPoint
                //     },
                //     success: function(result, status, erro) {
                //         highLightObj(result);
                //     },
                //     error: function(req, status, error) {
                //         alert(req + " " + status + " " + error);
                //     }
                // });
                //*/

                $.ajax({
                    type: "POST",
                    url: "CMR_pgsqlAPI.php",
                    // dataType: 'json',
                    // data: {functionname: 'reponseGeoToAjax', paPoint: myPoint},
                    data: {
                        functionname: 'getInfoCMRToAjax',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        displayObjInfo(result, evt.coordinate);
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
            });

        };
        //});
    </script>
</body>

</html>