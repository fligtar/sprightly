$(document).ready(function() {
    if (navigator.userAgent.indexOf('3.7') !== -1)
        sprightly.supports_transitions = true;
    
    sprightly.refresh_minute();
    sprightly.refresh_five_minutes();
    sprightly.refresh_hour();
    
    window.setInterval(sprightly.update_firefox_counts, 1000);
    window.setInterval(sprightly.refresh_minute, 60000);
    window.setInterval(sprightly.refresh_five_minutes, 60000 * 5);
    window.setInterval(sprightly.refresh_hour, 60000 * 60);
});

var sprightly = {
    weekdays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'],
    currentDate: 0,
    firefox36_downloads: 0,
    firefox_dps: [],
    supports_transitions: false,
    
    refresh_minute: function() {
        sprightly.update_time();
        
        $.getJSON('data/minutely.txt', function(data) {
            sprightly.update_firefox_downloads(data.firefox_downloads);
            sprightly.update_firefox_tweets(data.firefox_tweets);
        });
    },
    
    refresh_five_minutes: function() {
        sprightly.update_511();
        sprightly.update_caltrain();
    },
    
    refresh_hour: function() {
        $.getJSON('data/hourly.txt', function(data) {
            sprightly.update_amo_downloads(data.amo_downloads);
            sprightly.update_weather(data.weather);
            sprightly.update_caltrain(data.caltrain);
        });
    },
    
    // 5 min - caltrain and 511
    update_time: function() {
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();

        var suffix = "AM";
        if (hours >= 12) {
            suffix = "PM";
            hours = hours - 12;
        }
        if (hours == 0) {
            hours = 12;
        }

        if (minutes < 10)
            minutes = "0" + minutes

        $('#time').text(hours + ":" + minutes + " " + suffix);
        
        // Only update date if it's changed
        if (currentTime.getDate() != sprightly.currentDate) {
            var month = sprightly.months[currentTime.getMonth()];
            var day = sprightly.weekdays[currentTime.getDay()];
            var date = currentTime.getDate();
        
            $('#date').text(day + ', ' + month + ' ' + date);
            sprightly.currentDate = date;
        }
       },
    
    update_firefox_downloads: function(data) {
        if (sprightly.firefox36_downloads == 0)
            sprightly.firefox36_downloads = data.total;
        
        sprightly.firefox_dps = sprightly.firefox_dps.concat(data.rps);
    },
    
    update_firefox_counts: function() {
        if (sprightly.firefox_dps.length == 0)
            return;
        
        var change = sprightly.firefox_dps.shift();
        sprightly.firefox36_downloads += change;
        
        $('#firefox .downloads .fx36 .count').text(add_commas(sprightly.firefox36_downloads));
        $('#firefox .downloads .total .count').text(add_commas(1033197939 + sprightly.firefox36_downloads));
        
        if (sprightly.supports_transitions) {
            $('#firefox .downloads .change').append('<span>+' + change + '</span>');
            $('#firefox .downloads .change span').addClass('go').bind('transitionend', function(e) {
                $(this).remove();
            });
        }
    },
    
    update_firefox_tweets: function(data) {
        $.each(data, function(i, tweet) {
            $('#firefox .tweets ul').prepend('<li class="hidden"><img src="' + tweet.avatar + '" /><span>' + tweet.author + '</span>' + tweet.text + '</li>');
        });
    },
    
    update_caltrain: function() {
        
    },
    
    update_511: function() {
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();

        if (hours >= 12) {
            hours = hours - 12;
        }
        if (hours == 0) {
            hours = 12;
        }

        if (minutes < 10)
            minutes = "0" + minutes;
        
        $('#traffic-map').css('background-image', 'http://traffic.511.org/portalmap2.gif?' + currentTime.getTime());
        $('#traffic span').text('updated at ' + hours + ":" + minutes);
    }
    
};

function add_commas(nStr) {
  nStr += '';

  x       = nStr.split('.');
  x1      = x[0];
  x2      = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;

  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
  }
  return x1 + x2;
}
