    let properties= [];
    let map = null;
    let markers = [];
    let loading = false;
    init();

    function init() {
        loading = true;
        //Waste 5 seconds
        setTimeout(() => {
            loading = false;
        }, 1000);
        // map in index page
        let mapOptions = {
            scrollwheel: true,
            zoom: 6,
            zoomControl: true,
            gestureHandling: 'cooperative',
            center: new google.maps.LatLng(	21.543333, 39.172779) // map coordinates
        };
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
        let input = document.getElementById('searchfor');
        let searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });
        on_search();


        // more details for that place.
        searchBox.addListener('places_changed', function () {
            let places = searchBox.getPlaces();

            // For each place, get the icon, name and location.
            let bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            // let str = jQuery('#city_name2').val();
            // let valAfterSub = str.substring(0, str.lastIndexOf("S"));
            // jQuery('#city_name').val(valAfterSub.trim());
            on_search();
            map.fitBounds(bounds);
        });
    }

    function on_search() {
        //Show Loader
        loading = true;
        //Waste 5 seconds
        setTimeout(() => {
            loading = false;
        }, 1000);
        let url = "https://marketing-api.asaas.sa/api/get_offers";
        let typeData = jQuery('form.wpcf7-form.init').serialize();
        // jQuery.get('https://marketing-api.asaas.sa/api/get_offers', typeData, function (data) {
        //     if (data.status) {
        //         deleteMarkers();
        //         properties = data.properties;
        //         console.log(typeData);
        //         console.log(properties);
        //         properties = array_offer;
        //         jQuery.each(properties, function (key, value) {
        //            addMarker(value.map_lat, value.map_lng, value);
        //         });
        //     }
        // });
        properties = array_offer;
        jQuery.each(properties, function (key, value) {
            addMarker(value.map_lat, value.map_lng, value);
        });
        return false;
    }

    function addMarker(lat, lng, property) {
        let icon;
        if (property.offer_type === "FOR_RENT") {
            icon = 'img/map-marker-icon-blue.png'
        } else {
            icon = 'https://asaas.sa/img/map-marker-icon.png'
        }
        let price;
        let area;
        if (property.price !== '' && property.price !== null && property.price !== "null") {
            price = (property.price + ' ريال ')
        } else {
            price = ''
        }
        if (property.area !== '' && property.area !== null && property.area !== "null") {
            area = (property.area + ' م² ')
        } else {
            area = ''
        }
        let image_url = property.image_url;
        if(!imageExists(property.image_url)){
            image_url = 'https://dummyimage.com/300x50/7dc9ff/ffffff&text=Sorry+No+image+Available';
        }
        let contentString =
            '<a class="row" id="content" style="display: block; color: black" href="?post_type=estate&p=' + property.id + '">' +
            '<div class="col-xs-6" style="margin-top: 10px;padding-left: 0">' +
            '<p class="one-line" style="margin-bottom: 3px; font-size: 12px"><strong > ' + property.title + ' </strong></p>' +
            '<p class="one-line" style="margin-bottom: 5px; font-size: 12px">' +
            area +
            '</p>' +
            '<p class="one-line" style="margin-bottom: 5px; font-size: 12px">' +
            price +
            '</p>' +
            '</div>' +
            '<div class="col-xs-6">' +
            '<img style="width:100%;margin: auto" src="' + image_url + '"/>' +
            '</div>' +
            '</a>';
        let marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            icon: icon,
            map: map,
        });
        let infowindow;

        marker.addListener('mouseover', function () {
            infowindow = new google.maps.InfoWindow({
                "content": "<div class='map-info-window'" +
                    " style='width:300px; height:80px;display:block;'>" +
                    contentString +
                    "</div>"
            });
            infowindow.open(map, marker);
        });

        marker.addListener('mouseout', function () {
            infowindow.close();
        });

        marker.addListener('click', function () {
            infowindow = new google.maps.InfoWindow({
                "content": "<div class='map-info-window'" +
                    " style='width:300px; height:80px;display:block;'>" +
                    contentString +
                    "</div>"
            });
            infowindow.open(map, marker)
        });

        markers.push(marker);
    }

    function deleteMarkers() {
        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    }
    function imageExists(image_url){
        let http = new XMLHttpRequest();
        try {
            http.open('HEAD', image_url, false);
            http.send();
        }
        catch (e) {
        }
        return http.status !== 404;
    }
