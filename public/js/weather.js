$(document).ready(function() {

    var geoUrl = 'http://ip-api.com/json';
    var weatherUrl = 'http://api.openweathermap.org/data/2.5/forecast';
    var weatherApiKey = 'eee3ab31c9c085c141cca5f5047dcf76';

    if(sessionStorage.getItem('lat') === null || sessionStorage.getItem('lon') === null) {
        $.get(geoUrl, function(data) {
            var lat = data['lat'];
            var lon = data['lon'];

            sessionStorage['lat'] = lat;
            sessionStorage['lon'] = lon;
        });
    }

    var fullWeatherUrl = weatherUrl + '?lat=' + sessionStorage['lat'] + '&lon=' + sessionStorage['lon'] + '&units=metric' + '&appid=' + weatherApiKey;

    console.log(sessionStorage);
    if(minutesPassed(10)) {
        $.get(fullWeatherUrl, function (data) {
            sessionStorage['time'] = new Date();
            var nextMidday = getNextMidday();

            var currWeather = {};
            currWeather['temp'] = data['list'][0]['main']['temp'];
            currWeather['date'] = data['list'][0]['dt_txt'];
            currWeather['icon'] = data['list'][0]['weather'][0]['icon'];
            sessionStorage.setItem('currWeather', JSON.stringify(currWeather));

            var nextDayKey;
            $.each(data['list'], function(key, value) {
                var dateTime = new Date(value['dt_txt']);
                if(nextMidday.getTime() == dateTime.getTime()) {
                    nextDayKey = key;
                }
            });

            var fourWeather = [];
            var tmp = {};
            tmp['temp'] = data['list'][nextDayKey]['main']['temp'];
            tmp['date'] = data['list'][nextDayKey]['dt_txt'];
            tmp['icon'] = data['list'][nextDayKey ]['weather'][0]['icon'];
            fourWeather.push(tmp);

            for(i=0; i<3; i++) {
                var tmp = {};
                tmp['temp'] = data['list'][nextDayKey + 8]['main']['temp'];
                tmp['date'] = data['list'][nextDayKey + 8]['dt_txt'];
                tmp['icon'] = data['list'][nextDayKey + 8]['weather'][0]['icon'];
                fourWeather.push(tmp);
                nextDayKey += 8;
            }
            sessionStorage.setItem('fourWeather', JSON.stringify(fourWeather));
        });
    }
});

function minutesPassed(minutes) {
    var originTime = sessionStorage['time'];
    var currTime = new Date();
    var diff = minutes * 60;

    var ms = currTime - new Date(originTime);
    var s = ms / 1000;

    return s >= diff;
}

function getNextMidday() {
    var currTime = new Date();
    currTime.setHours(12);
    currTime.setMinutes(0);
    currTime.setSeconds(0);
    currTime.setMilliseconds(0);
    currTime.setDate(currTime.getDate() + 1);

    return currTime;
}