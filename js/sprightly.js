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
    caltrain: {},
    loadcount: 0,
    lastKey: 0,
    
    // Initialize!
    initialize: function() {
        sprightly.office = $('body').attr('data-office');

        // Call refresh functions and indicate that it's their first load
        sprightly.refresh_minute(true);
        sprightly.refresh_five_minutes(true);
        sprightly.refresh_hour(true);

        // Set up timers to continually refresh as apporpriate
        window.setInterval(sprightly.refresh_minute, 60000);
        window.setInterval(sprightly.refresh_five_minutes, 60000 * 5);
        window.setInterval(sprightly.refresh_hour, 60000 * 60);
        
        // Set up keydown listener
        $(document).keydown(function(event) {
            sprightly.keydown(event);
        });
    },
    
    // Interactive tweet favoriting
    keydown: function(event) {
        var showOverlay = false;
        
        // If the favorites overlay is already showing, process next action
        if ($('#addfavorite').is(':visible')) {
            // User hits enter to confirm
            if (event.keyCode == 13) {
                event.preventDefault();
                
                // Get tweet id from URL
                var url = $('#addfavorite .tweets li span a').attr('href');
                var id = url.substring(url.lastIndexOf('/') + 1);
                
                $('#addfavorite .confirm').hide();
                $('#addfavorite .loading').show();
                
                $.getJSON('lib/favorite.php?id=' + id, function(data) {
                    $('#addfavorite .loading').hide();
                    
                    // Successfully rewteeted
                    if (data.error == false) {
                        $('#addfavorite .tweets li').clone().prependTo('#favorites ul').find('.counter').remove();
                        $('#addfavorite .done').show();
                        window.setTimeout("$('#addfavorite').fadeOut();", 2000);
                    }
                    else {
                        // Error from Twitter
                        $('#addfavorite .error h1').text('Twitter Error: ' + data.error);
                        $('#addfavorite .error').show();
                        window.setTimeout("$('#addfavorite').fadeOut();", 7000);
                    }
                });
            }
            else if (event.keyCode != 144){
                // User pressed another key to cancel (other than NumLock)
                $('#addfavorite').hide();
            }
            
            sprightly.lastKey = event.keyCode;
            return;
        }
        else if (event.keyCode >= 48 && event.keyCode <= 57) {
            // Normal keys
            var num = event.keyCode - 48;
            showOverlay = true;
        }
        else if (event.keyCode >= 96 && event.keyCode <= 105) {
            // Numpad keys
            var num = event.keyCode - 96;
            showOverlay = true;
        }
        else if (event.keyCode == 111 || event.keyCode == 191) {
            // Numpad keyboard has a / on it that opens find bar
            event.preventDefault();
            // Instead, let's make this the secret "push twice to refresh" key
            if (sprightly.lastKey == 111 || sprightly.lastKey == 191) {
                window.location.reload();
            }
        }
        
        // If a number was pressed
        if (showOverlay) {
            event.preventDefault();
            $('#addfavorite .tweets').empty();
            $('#firefox .tweets .tweet-' + num).clone().appendTo('#addfavorite .tweets');
            $('#addfavorite .loading, #addfavorite .done, #addfavorite .error').hide();
            $('#addfavorite .confirm, #addfavorite').show();
        }
        
        sprightly.lastKey = event.keyCode;
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
            $('#content, #world-clock ul, #date-time div').fadeIn();
        });
    },
    
    // Called every minute to refresh data
    refresh_minute: function(splash) {
        sprightly.update_time();
        sprightly.update_world_time();
        sprightly.update_relative_times();
        
        var currentTime = new Date();
        
        $.getJSON('data/minutely.txt?' + currentTime.getTime(), function(data) {
            twitter.enqueue_new_tweets(data.firefox_tweets);
            input.enqueue_new_opinions(data.firefox_input);
            
            // If initial load, also prep some UI
            if (splash == true) {
                //sprightly.update_firefox_counts();
                twitter.show_next_tweet(true);
                input.show_next_opinion(true);
                sprightly.update_status('loaded tweets & deets');
                sprightly.done_loading();
            }
        });
    },
    
    // Called every 5 minutes to refresh data
    refresh_five_minutes: function(splash) {
        var currentTime = new Date();
        
        $.getJSON('data/5minutely.txt?' + currentTime.getTime(), function(data) {
            twitter.update_favorite_tweets(data.favorite_tweets);
        });
        
        // If initial load
        if (splash == true) {
            sprightly.update_status('loaded transportation goodies');
            sprightly.done_loading();
        }
    },
    
    // Called every 27 minutes to... just kidding
    refresh_hour: function(splash) {
        sprightly.update_mfbt(); // !important;
        
        var currentTime = new Date();
        
        $.getJSON('data/hourly.txt?' + currentTime.getTime(), function(data) {
            sprightly.update_calendar(data.calendar);
            
            // If initial load
            if (splash == true) {
                sprightly.update_status('guessed additional stats');
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
            if (time.hasClass('mins_until'))
                time.text(date_stuff.mins_until(time.attr('datetime')));
            else
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
    
   
    
    // Updates calendar with new events
    update_calendar: function(events) {
        if (events.length > 0)
            $('#events p').hide();
        else
            $('#events p').show();
        
        $('#events dl').empty();
        
        var lastdate = '';
        var dates = 0;
        
        $.each(events, function(i, event) {
            var time = new Date(event.start);
            var prettydate = date_stuff.get_pretty_date(time);
            
            // Determine if it's a new day
            if (lastdate != prettydate) {
                // Temporarily, only show a maximum of 3 dates for space reasons
                if (dates < 3)
                    dates++;
                else
                    return;
                
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

var twitter = {
    last_tweet_date: null,
    tweet_queue: [],
    tweetcounter: 0,
    
    // New tweets have arrived! Let us sort and enqueue them.
    enqueue_new_tweets: function(data) {
       data.reverse();
       $.each(data, function(i, tweet) {
           // Only add the tweets that are new since last update to the UI push queue
           tweet.dateobj = new Date(tweet.date);
           if (tweet.dateobj > twitter.last_tweet_date) {
               tweet.text = twitter.censor_tweet(tweet.text);

               // Banned users
               // This bot is super annoying
               if (tweet.author == 'raveranter (raveranter)') return;
               if (tweet.author == 'mozillafavs (Mozilla Favorites)') return;

               // Banned content
               // ow.ly seems to include "Mozilla Firefox" in every tweet. wtf?
               if (tweet.text.indexOf('http://ow.ly') !== -1) return;

               // Twitter highlights the OR operator. do not want
               tweet.text = tweet.text.replace(/<b>OR<\/b>/gi, 'or');

               twitter.tweet_queue.push(tweet);
               twitter.last_tweet_date = tweet.dateobj;
           }
       });
       
       twitter.show_next_tweet();
   },

   // Censor bad words for the office children, pets, and interns.
   censor_tweet: function(text) {
       // Yes, I have seen every one of these words in a tweet with "Firefox" in it.
       return text.replace(/fuck|shit|cunt|nigger|Justin Bieber/gi, '[BLEEP!]');
   },

   // Add the next tweet to the UI
   show_next_tweet: function(turbo) {
       // Normally we show one tweet every 5 seconds.
       // If TURBO is engaged, we add them all (used for initial load)
       if (turbo)
           var num_tweets = twitter.tweet_queue.length;
       else
           var num_tweets = 1;

       for (var i = 0; i < num_tweets; i++) {
           var tweet = twitter.tweet_queue.shift();
           
           var display_tweet_count = (twitter.tweetcounter > 9) ? twitter.tweetcounter : '0' + twitter.tweetcounter;
           $('#all-tweets .tweets').prepend('<li class="hidden tweet-' + display_tweet_count + '" data-tweetid=""><div><img style="background-image: url(' + tweet.avatar + ');" /><span><a href="' + tweet.url + '">' + tweet.author + '</a><span><time datetime="' + tweet.date + '" class="relative">' + date_stuff.time_ago_in_words(tweet.dateobj) + '</time><span class="counter">&nbsp;&middot;&nbsp;#' + display_tweet_count + '</span></span></span><p>' + tweet.text + '</p></li>').find('.hidden').slideDown();

           twitter.tweetcounter++;
           if (twitter.tweetcounter > 15)
               twitter.tweetcounter = 0;
       }

       // Clean up everything but the last 15 tweets
       $('#all-tweets .tweets li:gt(14)').remove();
       
       // If there's another tweet to be added, set a timer
       if (twitter.tweet_queue.length > 0)
           window.setTimeout(twitter.show_next_tweet, 5000);
   },

   // Update favorite tweets
   update_favorite_tweets: function(data) {
       $('#favorite-tweets ul').empty();
       data.reverse();

       $.each(data, function(i, tweet) {
           tweet.text = twitter.censor_tweet(tweet.text);

           $('#favorite-tweets ul').prepend('<li><img style="background-image: url(' + tweet.avatar + ');" /><span><a href="' + tweet.url + '">' + tweet.author + '</a><span><time datetime="' + tweet.date + '" class="relative">' + date_stuff.time_ago_in_words(new Date(tweet.date)) + '</time></span></span><p>' + tweet.text + '</p></li>');
       });
   }
};

var input = {
    last_opinion_date: null,
    opinion_queue: [],
    
    // New opinions have arrived! Let us sort and enqueue them.
    enqueue_new_opinions: function(data) {
       data.reverse();
       $.each(data, function(i, opinion) {
           // Only add the opinions that are new since last update to the UI push queue
           opinion.dateobj = new Date(opinion.date);
           if (opinion.dateobj > input.last_opinion_date) {
               input.opinion_queue.push(opinion);
               input.last_opinion_date = opinion.dateobj;
           }
       });
       
       input.show_next_opinion();
   },
   
   // Add the next opinion to the UI
   show_next_opinion: function(turbo) {
       // Normally we show one opinion every 5 seconds.
       // If TURBO is engaged, we add them all (used for initial load)
       if (turbo)
           var num_opinions = input.opinion_queue.length;
       else
           var num_opinions = 1;

       for (var i = 0; i < num_opinions; i++) {
           var opinion = input.opinion_queue.shift();
           
           $('#input .opinions').prepend('<li class="hidden positive"><div><p>' + opinion.text + '</p><span>' + opinion.version + ' / ' + opinion.os + '<time datetime="' + opinion.date + '" class="relative">' + date_stuff.time_ago_in_words(opinion.dateobj) + '</time></span></li>').find('.hidden').slideDown();
       }

       // Clean up everything but the last 15 opinions
       $('#input .opinions li:gt(14)').remove();
       
       // If there's another opinion to be added, set a timer
       if (input.opinion_queue.length > 0)
           window.setTimeout(input.show_next_opinion, 5000);
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
        if (distance_in_minutes < 60) { return distance_in_minutes + ' minutes ago'; }
        if (distance_in_minutes < 90) { return 'about 1 hour ago'; }
        if (distance_in_minutes < 120) { return 'about 2 hours ago'; }
        if (distance_in_minutes < 1440) { return 'about ' + Math.floor(distance_in_minutes / 60) + ' hours ago'; }
        if (distance_in_minutes < 2880) { return '1 day ago'; }
        if (distance_in_minutes < 43200) { return Math.floor(distance_in_minutes / 1440) + ' days ago'; }
        if (distance_in_minutes < 86400) { return 'about 1 month ago'; }
        if (distance_in_minutes < 525960) { return Math.floor(distance_in_minutes / 43200) + ' months ago'; }
        if (distance_in_minutes < 1051199) { return 'about 1 year ago'; }

        return 'over ' + Math.floor(distance_in_minutes / 525960) + ' years ago';
    },
    
    mins_until: function(date) {
        var now = new Date;
        var then = new Date;
        then.setTime(Date.parse(date));
        
        var distance_in_seconds = ((then - now) / 1000);
        var distance_in_minutes = Math.ceil(distance_in_seconds / 60);
        
        return distance_in_minutes;
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
