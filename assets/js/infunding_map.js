/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
google.maps.visualRefresh = true;
(function ($) {
    $.fn.infMap = function (options) {
        $(this).each(function () {
            if ($(this).hasClass('map-rendered')) {
                return;
            }
            $(this).addClass('map-rendered');
            var infMap = this;
            this.map = null;
            this.mapAddress = '';
            this.geocoder = new google.maps.Geocoder();
            this.infowindow = new google.maps.InfoWindow();
            this.marker = null;
            this.markers = [];
            this.markerCluster = null;

            this.renderMap = function () {
                var mapObj = this;
                mapObj.map = new google.maps.Map($(this).find('.map-preview').get(0), options.mapProperties);
                this.loadPlaces(true);
                this.mapMarkerCluster(true);
                google.maps.event.addListener(this.infowindow, 'closeclick', function () {
                    mapObj.marker.setIcon(options.spinurl + 'map-spin.png');
                    mapObj.marker.setAnimation(false);
                });
            };
            this.styleMap = function () {
                if (options.styleObj !== null) {
                    if (options.styleObj.override_default === '1') {
                        options.mapProperties.styles = [{"featureType": "landscape", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "transit", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "water", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "road", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {"stylers": [{"hue": "#00aaff"}, {"saturation": -100}, {"gamma": 2.15}, {"lightness": 12}]}, {"featureType": "road", "elementType": "labels.text.fill", "stylers": [{"visibility": "on"}, {"lightness": 24}]}, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": 57}]}];
                        this.renderMap();
                    }
                    if (options.styleObj.override_default === '0') {
                        var styledMap = new google.maps.StyledMapType(JSON.parse(options.styleObj.styles), {name: options.styleObj.name});
                        options.mapProperties.mapTypeControlOptions = {mapTypeIds: [options.mapProperties.mapTypeId, 'map_style']};
                        this.renderMap();
                        this.map.mapTypes.set('map_style', styledMap);
                        this.map.setMapTypeId('map_style');
                    }
                    if (typeof options.styleObj.override_default === 'undefined') {
                        options.mapProperties.mapTypeControlOptions = null;
                        this.renderMap();
                    }
                } else {
                    this.renderMap();
                }
            };
            //Marker process
            this.createMarker = function (place) {
                var mapObj = this;
                if (!place.latitude || !place.longitude) {
                    infMap.geocoder.geocode({'address': place.address}, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var latlng = results[0].geometry.location;
                            place.latitude = latlng.lat();
                            place.longitude = latlng.lng();
                            mapObj.createMarker(place);
                        } else {
                            alert('Geocode was not successful for the following reason: ' + status);
                        }
                    });
                } else {
                    var placeLoc = new google.maps.LatLng(place.latitude, place.longitude);
                    var marker = new google.maps.Marker({
                        map: mapObj.map,
                        position: placeLoc,
                        title: place.title,
                        animation: false
                    });
                    if (options.detail_page === false) {
                        marker.setIcon(options.spinurl + 'map-spin.png');
                        var html = '<div class="place-des-contain">';
                        html += '<div class="place-image"><img src="' + place.image + '"></div>';
                        html += '<div class="des-content">';
                        html += '<div class="title theme-color iw-capital">' + place.title + '</div>';
                        if (options.show_location) {
                            html += '<div class="location theme-color"><i class="fa fa-map-marker"></i>' + place.address + '</div>';
                        }
                        if (options.show_des) {
                            html += '<div class="description">' + place.description + '</div>';
                        }
                        html += '<div class="read-more"><a href="' + place.link + '"><i class="fa fa-check-circle"></i>' + place.readmore + '</a></div>';
                        html += '</div>';
                        html += '</div>';

                        google.maps.event.addListener(marker, 'click', function () {
                            if (place.description !== '') {
                                mapObj.infowindow.setContent(html);
                            }

                            mapObj.toggleBounce(marker);
                        });
                        this.markers.push(marker);
                    } else {
                        google.maps.event.addListener(marker, 'click', function () {
                            mapObj.infowindow.setContent(place.address);
                            mapObj.infowindow.open(mapObj.map, marker);
                        });
                        marker.setIcon(options.spinurl + 'map_detail_picker.png');
                        mapObj.map.setCenter(placeLoc);
                    }
                }
            };

            this.removeMarker = function () {
                while (this.markers.length) {
                    this.markers.pop().setMap(null);
                }
            };

            this.toggleBounce = function (marker) {
                this.markers.forEach(function (m) {
                    if (marker !== m) {
                        m.setIcon(options.spinurl + 'map-spin.png');
                        m.setAnimation(false);
                    }
                });
                if (marker.getAnimation()) {
                    marker.setIcon(options.spinurl + 'map-spin.png');
                    this.infowindow.close(this.map, marker);
                    this.marker = null;
                    marker.setAnimation(false);
                } else {
                    marker.setIcon(options.spinurl + 'map-spin-active.png');
                    this.infowindow.open(this.map, marker);
                    this.marker = marker;
                    marker.setAnimation(true);
                }
            };
            //Places load     
            this.loadPlaces = function (isShow) {
                if (isShow === true) {
                    if (this.markers.length > 0) {
                        this.markers = [];
                    }
                    var places = options.mapPlaces;
                    for (var i = 0; i < places.length; i++) {
                        this.createMarker(places[i]);
                    }
                } else {
                    this.removeMarker();
                }
            };


            this.mapMarkerCluster = function (isEnable) {
                this.markerCluster = null;
                if (this.markerCluster === null) {
                    this.markerCluster = new MarkerClusterer(this.map, this.markers);
                }
                if (isEnable === true) {
                    this.markerCluster.setMap(this.map);
                } else {
                    this.markerCluster.setMap(null);
                }
            };

            this.styleMap();
        });
    };
})(jQuery);