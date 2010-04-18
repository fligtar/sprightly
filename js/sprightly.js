$(document).ready(function() {
    sprightly.initialize();
});

// Main sprightly object
var sprightly = {
    offices: {
        'mv': 'Mountain View',
        'toronto': 'Toronto'
    },
    office: 'mv',
    last_tweet_date: null,
    firefox36_downloads: 0,
    firefox_dp5: [],
    supports_transitions: false,
    tweet_queue: [],
    caltrain: {},
    loadcount: 0,
    
    // Initialize!
    initialize: function() {
        sprightly.office = $('body').attr('data-office');
        
        // This only works for Firefox and I... don't care.
        if (navigator.userAgent.indexOf('3.7') !== -1)
            sprightly.supports_transitions = true;

        // Call refresh functions and indicate that it's their first load
        sprightly.refresh_five_seconds(true);
        sprightly.refresh_minute(true);
        sprightly.refresh_five_minutes(true);
        sprightly.refresh_hour(true);

        // Set up timers to continually refresh as apporpriate
        window.setInterval(sprightly.refresh_five_seconds, 5000);
        window.setInterval(sprightly.refresh_minute, 60000);
        window.setInterval(sprightly.refresh_five_minutes, 60000 * 5);
        window.setInterval(sprightly.refresh_hour, 60000 * 60);
    },
    
    // Updates loading status message
    update_status: function(msg) {
        $('#loading-message span').text(msg);
    },
    
    // Checks if all of the initial callbacks have completed and shows content
    done_loading: function() {
        sprightly.loadcount++;
        
        // Check if all 3 initial callbacks have completed
        if (sprightly.loadcount < 3) return;
        
        $('title').text('Mozilla ' + sprightly.offices[sprightly.office]);
        
        sprightly.update_status('and we\'re off!');
        $('#loading-message').fadeOut('normal', function() {
            $('#content, #clocks').fadeIn();
        });
    },
    
    // Called every 5 seconds to refresh data
    refresh_five_seconds: function() {
        sprightly.update_firefox_counts();
        sprightly.next_tweet();
    },
    
    // Called every minute to refresh data
    refresh_minute: function(splash) {
        sprightly.update_time();
        sprightly.update_world_time();
        sprightly.update_relative_times();
        
        $.getJSON('data/minutely.txt', function(data) {
            sprightly.update_firefox_downloads(data.firefox_downloads);
            sprightly.update_firefox_tweets(data.firefox_tweets);
            
            // If initial load, also prep some UI
            if (splash) {
                sprightly.update_firefox_counts();
                sprightly.next_tweet(true);
                sprightly.update_status('loaded tweets & deets');
                sprightly.done_loading();
            }
        });
    },
    
    // Called every 5 minutes to refresh data
    refresh_five_minutes: function(splash) {
        sprightly.update_511();
        sprightly.filter_caltrain();
        
        // If initial load
        if (splash) {
            sprightly.update_status('loaded transportation goodies');
            sprightly.done_loading();
        }
    },
    
    // Called every 27 minutes to... just kidding
    refresh_hour: function(splash) {
        sprightly.update_mfbt(); // !important;
        
        $.getJSON('data/hourly.txt', function(data) {
            sprightly.update_weather(data.weather);
            sprightly.update_caltrain(data.caltrain);
            sprightly.update_amo(data.amo);
            sprightly.update_calendar(data.calendar);
            
            // If initial load
            if (splash) {
                sprightly.update_status('guessed weather and additional stats');
                sprightly.done_loading();
            }
        });
    },
    
    // Updates the main clock
    update_time: function() {
        $('#time').text(date_stuff.get_pretty_time());
        $('#date').text(date_stuff.get_pretty_date());
    },
    
    // Updates all relative times
    update_relative_times: function() {
        $('time.relative').each(function(e, t) {
            var time = $(t);
            time.text(date_stuff.time_ago_in_words_with_parsing(time.attr('datetime')));
        });
    },
    
    // Updates all world clocks
    update_world_time: function() {
        $('#world-clock li time').each(function(e, t) {
            var time = $(t);
            time.text(date_stuff.get_pretty_time(date_stuff.world_time(time.attr('data-offset'))));
        });
    },
    
    // Determine whether it is MFBT
    update_mfbt: function() {
        var currentTime = new Date();
        var day = currentTime.getDay();
        var hour = currentTime.getHours();
        
        if ((day > 0 && day < 6) && (hour < 17 && hour > 7)) 
            $('#mfbt').hide(); // get back to work!
        else
        	$('#mfbt').show(); // of course it is!
    },
    
    // New Firefox download data has arrived. Update our array
    update_firefox_downloads: function(data) {
        if (sprightly.firefox36_downloads == 0)
            sprightly.firefox36_downloads = data.total;
        
        sprightly.firefox_dp5 = sprightly.firefox_dp5.concat(data.dp5);
    },
    
    // Every 5 seconds, update the UI counts
    update_firefox_counts: function() {
        if (sprightly.firefox_dp5.length == 0)
            return;
        
        var change = sprightly.firefox_dp5.shift();
        sprightly.firefox36_downloads += change;
        
        // This is pretty hacky but will be improved soon.
        // "total downloads" ~= 3.6 downloads + a guess based on SpreadFirefox total, sorta kinda
        $('#firefox .downloads .fx36 .count').text(add_commas(sprightly.firefox36_downloads));
        $('#firefox .downloads .total .count').text(add_commas(1033197939 + sprightly.firefox36_downloads));
        
        // On Firefox 3.7+ we use a CSS transition for a cool effect.
        // On other browsers we don't because it's an unnecessary perf hit
        if (sprightly.supports_transitions) {
            $('#firefox .downloads .change').append('<span>+' + add_commas(change) + '</span>');
            $('#firefox .downloads .change span').addClass('go').bind('transitionend', function(e) {
                $(this).remove();
            });
        }
    },
    
    // New tweets have arrived! Let us sort and enqueue them.
    update_firefox_tweets: function(data) {
        data.reverse();
        $.each(data, function(i, tweet) {
            // Only add the tweets that are new since last update to the UI push queue
            tweet.dateobj = new Date(tweet.date);
            if (tweet.dateobj > sprightly.last_tweet_date) {
                // Censor bad words for the office children, pets, and interns.
                // Yes, I have seen every one of these words in a tweet with "Firefox" in it.
                tweet.text = tweet.text.replace(/fuck|shit|cunt|nigger|Justin Bieber/gi, '[YAY FIREFOX!]');
            
                // Banned users
                // This bot is super annoying
                if (tweet.author == 'raveranter (raveranter)') return;
            
                // Banned content
                // ow.ly seems to include "Mozilla Firefox" in every tweet. wtf?
                if (tweet.text.indexOf('http://ow.ly') !== -1) return;
            
                // Twitter highlights the OR operator. do not want
                tweet.text = tweet.text.replace(/<b>OR<\/b>/gi, 'or');
            
                sprightly.tweet_queue.push(tweet);
                sprightly.last_tweet_date = tweet.dateobj;
            }
        });
    },
    
    // Add the next tweet to the UI
    next_tweet: function(turbo) {
        if (sprightly.tweet_queue.length == 0)
            return;
        
        // Normally we show one tweet every 5 seconds.
        // If TURBO is engaged, we add them all (used for initial load)
        if (turbo)
            var num_tweets = sprightly.tweet_queue.length;
        else
            var num_tweets = 1;
        
        for (var i = 0; i < num_tweets; i++) {
            var tweet = sprightly.tweet_queue.shift();
        
            $('#firefox .tweets ul').prepend('<li class="box hidden"><img src="' + tweet.avatar + '" /><a class="author" href="' + tweet.url + '">' + tweet.author + '<time datetime="' + tweet.date + '" class="relative">' + date_stuff.time_ago_in_words(tweet.dateobj) + '</time></a>' + tweet.text + '</li>').find('.hidden').slideDown();
        }
        
        // Clean up everything but the last 10 tweets
        $('#firefox .tweets ul li:gt(9)').remove();
    },
    
    // New Caltrain data has arrived. Really, this will only change once a day, but you never know
    // when the computer was put to sleep.
    update_caltrain: function(data) {
        sprightly.caltrain = data;
        sprightly.filter_caltrain();
    },
    
    // Based on the day's schedule and current time, update the table with the next 5 trains
    filter_caltrain: function() {
        // Get the 5 next trains for each direction
        var schedule = {
            northbound: sprightly.compare_trains(sprightly.caltrain.northbound),
            southbound: sprightly.compare_trains(sprightly.caltrain.southbound)
        };
        
        $('#caltrain tbody').empty();
        
        for (var i = 0; i < 5; i++) {
            var row = '<tr>';
            
            // Check if there are any NB trains at all
            if (i == 1 && schedule.northbound.length == 0) {
                row += '<td colspan="2" class="nb notrains">No departures</td>';
            }
            else {
                row += '<td class="nb time">';
                if (schedule.northbound[i]) {
                    row += schedule.northbound[i][0] + '</td>';
                
                    row += '<td class="nb type ' + schedule.northbound[i][1] + '">' + schedule.northbound[i][1];
                }
                else
                    row += '</td><td class="nb type">&nbsp;';
            
                row += '</td>';
            }
            
            // Check if there are any SB trains at all
            if (i == 1 && schedule.southbound.length == 0) {
                row += '<td colspan="2" class="sb notrains">No departures</td>';
            }
            else {
                row += '<td class="sb time">';
                
                if (schedule.southbound[i]) {
                    row += schedule.southbound[i][0] + '</td>';
                
                    row += '<td class="sb type ' + schedule.southbound[i][1] + '">' + schedule.southbound[i][1];
                }
                else
                    row += '</td><td class="sb type">&nbsp;';
            
                row += '</td>';
            }
            
            row += '</tr>';

            $('#caltrain tbody').append(row);
        }
    },
    
    // Given one direction's schedule for the day, find the nearest 5 times
    compare_trains: function(schedule) {
        var filtered = [];
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        
        // Get the 5 next trains
        for (var i in schedule) {
            if (filtered.length >= 5) break;
            
            var train = schedule[i];
            var time = train[0].split(':');
            
            if (time[0] > hours || (time[0] == hours && time[1] >= minutes)) {
                // Filter out Saturday-only trains on non-Saturdays
                if (train[1] == 'saturday' && currentTime.getDay() != 6) continue;
                
                filtered.push([date_stuff.format_hours(time[0]) + ':' + time[1], train[1]]);
            }
        }
        
        return filtered;
    },
    
    // Every 5 minutes, update traffic map with cachebusting from 511.
    update_511: function() {
        var currentTime = new Date();
        
        $('#traffic-map').css('background-image', 'data/traffic.gif?' + currentTime.getTime());
        $('#traffic time').attr('datetime', currentTime).text(date_stuff.time_ago_in_words(currentTime));
    },
    
    // New weather data has arrived. Update the UI
    update_weather: function(data) {
        $.each(data, function(city, weather) {
            $('#weather-' + city + ' img').attr('src', weather.img);
            $('#weather-' + city + ' span').html(weather.conditions.replace(', ', '<br/>').replace(' F', '&deg; F'));
        });
    },
    
    // New AMO data has arrived. Update the UI
    update_amo: function(data) {
        for (var i in data) {
            $('#amo #amo-' + i).text(add_commas(data[i]));
        }
    },
    
    // Updates calendar with new events
    update_calendar: function(events) {
        if (events.length == 0)
            $('#events p').show();
        else
            $('#events p').hide();
        
        $('#events dl').empty();
        
        var lastdate = '';
        
        $.each(events, function(i, event) {
            var time = new Date(event.start);
            var prettydate = date_stuff.get_pretty_date(time);
            
            if (lastdate != prettydate) {
                if (lastdate == '' && prettydate == date_stuff.get_pretty_date())
                    var extra = ' class="today"';
                else
                    var extra = '';
                
                $('#events dl').append('<dt' + extra + '>' + prettydate + '</dt>');
                lastdate = prettydate;
            }
            
            $('#events dl').append('<dd><time>' + date_stuff.get_pretty_time(time) + '</time>' + event.name + '</dd>');
        }); 
        
    }
    
};

// Adds commas to an integer. I found this on mozilla.com somewhere!
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

// Date stuff goes here
var date_stuff = {
    weekdays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'],
    
    // Optionally given a time, return the pretty time, like: 7:34 PM
    get_pretty_time: function(time) {
        if (!time)
            time = new Date();
        
        var hours = time.getHours();
        var minutes = time.getMinutes();

        var suffix = "AM";
        if (hours >= 12)
            suffix = "PM";
        
        hours = this.format_hours(hours);

        if (minutes < 10)
            minutes = "0" + minutes

        return hours + ":" + minutes + " " + suffix;
    },
    
    // Convert 24-hour time to 12-hour time
    format_hours: function(hour) {
        if (hour > 12)
            hour = hour - 12;
        else if (hour == 0)
            hour = 12;
        
        return hour;
    },
    
    // Optionally given a time, return the pretty date, like: Tuesday, April 13
    get_pretty_date: function(time) {
        if (!time)
            time = new Date();

        var month = this.months[time.getMonth()];
        var day = this.weekdays[time.getDay()];
        var date = time.getDate();
    
        return day + ', ' + month + ' ' + date;
    },
    
    // These functions mostly from http://gist.github.com/58761
    time_ago_in_words_with_parsing: function(from) {
        var date = new Date;
        date.setTime(Date.parse(from));
        return this.time_ago_in_words(date);
    },

    time_ago_in_words: function(from) {
        return this.distance_of_time_in_words(new Date, from);
    },

    distance_of_time_in_words: function(to, from) {
        var distance_in_seconds = ((to - from) / 1000);
        var distance_in_minutes = Math.floor(distance_in_seconds / 60);

        if (distance_in_minutes == 0) { return 'less than a minute ago'; }
        if (distance_in_minutes == 1) { return 'a minute ago'; }
        if (distance_in_minutes < 45) { return distance_in_minutes + ' minutes ago'; }
        if (distance_in_minutes < 90) { return 'about 1 hour ago'; }
        if (distance_in_minutes < 1440) { return 'about ' + Math.floor(distance_in_minutes / 60) + ' hours ago'; }
        if (distance_in_minutes < 2880) { return '1 day ago'; }
        if (distance_in_minutes < 43200) { return Math.floor(distance_in_minutes / 1440) + ' days ago'; }
        if (distance_in_minutes < 86400) { return 'about 1 month ago'; }
        if (distance_in_minutes < 525960) { return Math.floor(distance_in_minutes / 43200) + ' months ago'; }
        if (distance_in_minutes < 1051199) { return 'about 1 year ago'; }

        return 'over ' + Math.floor(distance_in_minutes / 525960) + ' years ago';
    },
    
    // mostly from http://articles.techrepublic.com.com/5100-10878_11-6016329.html
    world_time: function(offset) {
        // create Date object for current location
        var d = new Date();

        // convert to msec
        // add local time zone offset
        // get UTC time in msec
        var utc = d.getTime() + (d.getTimezoneOffset() * 60000);

        // create new Date object for different city
        // using supplied offset
        var nd = new Date(utc + (3600000*offset));

        return nd;
    }
};
