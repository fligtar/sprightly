$(document).ready(function() {
    sprightly.refresh_minute();
});

var sprightly = {
    
    refresh_minute: function() {
        $.getJSON('data/minutely.txt', function(data) {
            sprightly.update_firefox_downloads(data.firefox_downloads);
            sprightly.update_firefox_tweets(data.firefox_tweets);
        });
    },
    
    refresh_hour: function() {
        $.getJSON('data/hourly.txt', function(data) {
            sprightly.update_amo_downloads(data.amo_downloads);
            sprightly.update_weather(data.weather);
            sprightly.update_caltrain(data.caltrain);
        });
    },
    
    // 5 min - caltrain and 511
    
    update_firefox_downloads: function(data) {
        $('#firefox .downloads .total .count').text(data.total + 1049319991);
        $('#firefox .downloads .fx36 .count').text(data.total);
    },
    
    update_firefox_tweets: function(data) {
        $.each(data, function(i, tweet) {
            $('#firefox .tweets ul').prepend('<li class="hidden"><img src="' + tweet.avatar + '" /><span>' + tweet.author + '</span>' + tweet.text + '</li>');
        });
    }
    
};