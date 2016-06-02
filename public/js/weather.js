var geoUrl = 'http://ip-api.com/json';
var weatherUrl = 'http://api.openweathermap.org/data/2.5/forecast';
var weatherCurrUrl = 'http://api.openweathermap.org/data/2.5/weather';
var weatherApiKey = 'eee3ab31c9c085c141cca5f5047dcf76';

var currWeatherGlobal = '';
var fourWeatherGlobal = '';
var fullWeatherUrl = '';
var fullWeatherCurrUrl = '';

$(document).ready(function() {
    $.when( //get Geo data first
        $.get(geoUrl, function(data) {
                setGeoData(data);
        })
    ).then(function(){
        if(minutesPassed(10)) {
            $.when( //here you get weather data
                $.get(fullWeatherCurrUrl, function (data) {
                    setCurrData(data);
                }),
                $.get(fullWeatherUrl, function (data) {
                    setFourData(data);
                })
            ).then(function () { //and here you render it all
                    setCurrWeather();
                    setFourWeather();
            })
        }
        else {
            setCurrWeather();
            setFourWeather();
        }
    });
});

function setGeoData(data) {
    if(localStorage.getItem('lat') == null || localStorage.getItem('lon') == null || localStorage.length == 0) {
        var lat = data['lat'];
        var lon = data['lon'];
        var city = data['city'];

        localStorage['lat'] = lat;
        localStorage['lon'] = lon;
        localStorage['city'] = city;

        fullWeatherUrl = weatherUrl + '?lat=' + lat + '&lon=' + lon + '&units=metric' + '&appid=' + weatherApiKey;
        fullWeatherCurrUrl = weatherCurrUrl + '?lat=' + lat + '&lon=' + lon + '&units=metric' + '&appid=' + weatherApiKey;
    }
    fullWeatherUrl = weatherUrl + '?lat=' + localStorage['lat'] + '&lon=' + localStorage['lon'] + '&units=metric' + '&appid=' + weatherApiKey;
    fullWeatherCurrUrl = weatherCurrUrl + '?lat=' + localStorage['lat'] + '&lon=' + localStorage['lon'] + '&units=metric' + '&appid=' + weatherApiKey;
}

function setCurrData(data) {
    localStorage['time'] = new Date();

    var currWeather = {};
    currWeather['temp'] = data['main']['temp'];
    currWeather['icon'] = data['weather'][0]['icon'];
    localStorage['currWeather'] = JSON.stringify(currWeather);
    currWeatherGlobal = currWeather;
}

function setFourData(data) {
    var nextMidday = getNextMidday();

    var nextDayKey;
    $.each(data['list'], function (key, value) {
        var dateTime = new Date(value['dt_txt']);
        if (nextMidday.getTime() == dateTime.getTime()) {
            nextDayKey = key;
        }
    });

    var fourWeather = [];
    var tmp = {};
    tmp['tempMorning'] = data['list'][nextDayKey]['main']['temp'];
    tmp['tempAfternoon'] = data['list'][nextDayKey + 3]['main']['temp'];
    tmp['date'] = data['list'][nextDayKey]['dt_txt'];
    tmp['icon'] = data['list'][nextDayKey]['weather'][0]['icon'];
    fourWeather.push(tmp);

    for (i = 0; i < 3; i++) {
        var tmp = {};
        tmp['tempMorning'] = data['list'][nextDayKey + 8]['main']['temp'];
        tmp['tempAfternoon'] = data['list'][nextDayKey + 8 + 3]['main']['temp'];
        tmp['date'] = data['list'][nextDayKey + 8]['dt_txt'];
        tmp['icon'] = data['list'][nextDayKey + 8 + 2]['weather'][0]['icon'];
        fourWeather.push(tmp);
        nextDayKey += 8;
    }
    localStorage['fourWeather'] = JSON.stringify(fourWeather);
    fourWeatherGlobal = fourWeather;
}

function minutesPassed(minutes) {
    var originTime = localStorage['time'];
    if(originTime == null || localStorage['currWeather'] == null || localStorage['fourWeather'] == null) {
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
    currTime.setHours(6);
    currTime.setMinutes(0);
    currTime.setSeconds(0);
    currTime.setMilliseconds(0);
    currTime.setDate(currTime.getDate() + 1);

    return currTime;
}

function setCurrWeather() {
    if(localStorage['currWeather'] == null) {
        var currWeather = currWeatherGlobal;
    }
    else {
        var currWeather = JSON.parse(localStorage['currWeather']);
    }

    var text = localStorage['city'] + '<br/>' + '<i class="wi ' + getWeatherIcon(currWeather['icon']) + ' curr"></i>' + '<br/>' + Math.round(currWeather['temp']) + '°C';
    $('#currWeather').append(text);
}

function setFourWeather() {
    if(localStorage['fourWeather'] == null) {
        var fourWeather = fourWeatherGlobal;
    }
    else {
        var fourWeather = JSON.parse(localStorage['fourWeather']);
    }

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
            return 'Ned';
        case 1:
            return 'Pon';
        case 2:
            return 'Tor';
        case 3:
            return 'Sre';
        case 4:
            return 'Čet';
        case 5:
            return 'Pet';
        case 6:
            return 'Sob';
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