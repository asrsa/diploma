$(document).ready(function() {

    var geoUrl = 'http://ip-api.com/json';
    var weatherUrl = 'http://api.openweathermap.org/data/2.5/forecast';
    var weatherApiKey = 'eee3ab31c9c085c141cca5f5047dcf76';

    if(sessionStorage.getItem('lat') === null || sessionStorage.getItem('lon') === null) {
        $.get(geoUrl, function(data) {
            var lat = data['lat'];
            var lon = data['lon'];
            var city = data['city'];

            sessionStorage['lat'] = lat;
            sessionStorage['lon'] = lon;
            sessionStorage['city'] = city;
        });
    }

    var fullWeatherUrl = weatherUrl + '?lat=' + sessionStorage['lat'] + '&lon=' + sessionStorage['lon'] + '&units=metric' + '&appid=' + weatherApiKey;

    if(true) {
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
            tmp['tempMorning'] = data['list'][nextDayKey]['main']['temp'];
            tmp['tempAfternoon'] = data['list'][nextDayKey + 2]['main']['temp'];
            tmp['date'] = data['list'][nextDayKey]['dt_txt'];
            tmp['icon'] = data['list'][nextDayKey ]['weather'][0]['icon'];
            fourWeather.push(tmp);

            for(i=0; i<3; i++) {
                var tmp = {};
                tmp['tempMorning'] = data['list'][nextDayKey + 8]['main']['temp'];
                tmp['tempAfternoon'] = data['list'][nextDayKey + 8 + 2]['main']['temp'];
                tmp['date'] = data['list'][nextDayKey + 8]['dt_txt'];
                tmp['icon'] = data['list'][nextDayKey + 8 + 1]['weather'][0]['icon'];
                fourWeather.push(tmp);
                nextDayKey += 8;
            }
            sessionStorage.setItem('fourWeather', JSON.stringify(fourWeather));
        });
    }

    setCurrWeather();
    setFourWeather();
});

function minutesPassed(minutes) {
    var originTime = sessionStorage['time'];
    if(originTime == null) {
        return true;
    }
    var currTime = new Date();
    var diff = minutes * 60;

    var ms = currTime - new Date(originTime);
    var s = ms / 1000;

    return s >= diff;
}

function getNextMidday() {
    var currTime = new Date();
    currTime.setHours(9);
    currTime.setMinutes(0);
    currTime.setSeconds(0);
    currTime.setMilliseconds(0);
    currTime.setDate(currTime.getDate() + 1);

    return currTime;
}

function setCurrWeather() {
    var currWeather = JSON.parse(sessionStorage['currWeather']);
    var text = sessionStorage['city'] + '<br/>' + '<i class="wi ' + getWeatherIcon(currWeather['icon']) + ' curr"></i>' + '<br/>' + Math.round(currWeather['temp']) + '°C';
    $('#currWeather').append(text);
}

function setFourWeather() {
    var fourWeather = JSON.parse(sessionStorage['fourWeather']); console.log(fourWeather);
    $.each(fourWeather, function(key, value) {
        var day = new Date(value['date']);
        var text = '<tr>' +
                '<td>' + getDaySlo(day.getDay()) + '</td>' +
                '<td>' + '<i class="wi ' + getWeatherIcon(value['icon']) + '"></i>' + '</td>' +
                '<td>' + Math.round(value['tempMorning']) + '°C </td>' +
                '<td>' + Math.round(value['tempAfternoon']) + '°C </td>' +
                '</tr>';
        $('#fourWeather').append(text);
    });
}

function getDaySlo(day) {
    switch(day) {
        case 0:
            return 'Nedelja';
        case 1:
            return 'Ponedeljek';
        case 2:
            return 'Torek';
        case 3:
            return 'Sreda';
        case 4:
            return 'Četrtek';
        case 5:
            return 'Petek';
        case 6:
            return 'Sobota';
    }
}

function getWeatherIcon(icon) {
    switch(icon) {
        case '01d':
            return 'wi-day-sunny';
        case '02d':
            return 'wi-day-cloudy';
        case '03d':
            return 'wi-cloud';
        case '04d':
            return 'wi-cloudy';
        case '09d':
            return 'wi-showers';
        case '10d':
            return 'wi-rain';
        case '11d':
            return 'wi-thunderstorm';
        case '13d':
            return 'wi-day-snow';
        case '50d':
            return 'wi-fog';
        case '01n':
            return 'wi-night-clear';
        case '02n':
            return 'wi-night-alt-cloudy';
        case '03n':
            return 'wi-cloud';
        case '04n':
            return 'wi-cloudy';
        case '09n':
            return 'wi-showers';
        case '10n':
            return 'wi-rain';
        case '11n':
            return 'wi-thunderstorm';
        case '13n':
            return 'wi-day-snow';
        case '50n':
            return 'wi-fog';
    }
}