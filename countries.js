resultDiv = document.getElementById("resultDiv");
weatherDiv = document.getElementById("weather");
windDiv = document.getElementById("windDiv");
temperatureDiv = document.getElementById("temperatureDiv");
pressureDiv = document.getElementById("pressureDiv");
capitalCity = document.getElementById("capital_city");
countryDiv = document.getElementById("country");
latDiv = document.getElementById("lat");
lonDiv = document.getElementById("lon");
const button = document.getElementById("button");

// https://developers.google.com/maps/documentation/javascript/examples/places-searchbox#maps_places_searchbox-javascript
function initAutocomplete() {
    const cityInput = document.getElementById("city-input");
    const searchBox = new google.maps.places.SearchBox(cityInput);

    searchBox.addListener("places_changed", () => {
        places = searchBox.getPlaces();
    })
}

window.initAutocomplete = initAutocomplete;

button.addEventListener('click', () => {
    resultDiv.innerHTML = "";

    places.forEach((place) => {
        let size = place.address_components.length;

        countryCode = place.address_components[size - 1].short_name;

        var state = place.address_components[size - 1].long_name;
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();

        latDiv.innerHTML = "Latitude: " + latitude;
        lonDiv.innerHTML = "Longitude: " + longitude;
        countryDiv.innerHTML = "Country: " + state;


        //aktualne pocasie: https://openweathermap.org/current
        fetch('https://api.openweathermap.org/data/2.5/weather?lat=' + latitude + '&lon=' + longitude + '&appid=' + "API-KEY")
            .then(res => res.json())
            .then(data => {
                weatherDiv.innerHTML = "Weather description: " + data['weather'][0]['description']
                temperatureDiv.innerHTML = "Temperature " + kelvinToCelsius(data['main']['temp']) + ' °C'
                windDiv.innerHTML = "Wind speed: " + data['wind']['speed'] + ' km/h'
                pressureDiv.innerHTML = "Pressure: " + data['main']['pressure'] + ' hPa'
            })

        fetch("visits_solver.php", {
            method: "POST",
            body: JSON.stringify({
                "latitude": latitude,
                "longitude": longitude,
                "state": state,
                "code": countryCode,
                "city": place.address_components[0].long_name,
                "time": time
            })
        }).then(response => response.json()).then(result => JSON.stringify(result, undefined, 4));
    });

})

function kelvinToCelsius(temp) {
    return round((temp - 273), 2)
}

function round(value, precision) {
    var multiplier = Math.pow(10, precision || 0);
    return Math.round(value * multiplier) / multiplier;
}















