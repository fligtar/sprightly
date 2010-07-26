<?php
/*
    This is the front-end for the ambient displays and should be loaded 
    full-screen in the browser. All data will be refreshed automatically
    at predetermined intervals.
*/

$offices = array('mv', 'toronto');

$office = !empty($_GET['office']) && in_array($_GET['office'], $offices) ? $_GET['office'] : $offices[0];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>sprightly</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="shortcut icon" type="image/png" href="images/favicon.ico" />
</head>

<body class="<?php echo $office; ?>" data-office="<?php echo $office; ?>">
    <div id="debug"></div>
    <!--><header>
        <div>
            <div id="title">
                <h1><span>mozilla</span></h1>
            </div>
            
            <div id="world-clock">
                <ul>
                    <li class="hide-mv"><span>Mountain View</span><time data-offset="-7"></time></li>
                    <li class="hide-toronto"><span>Toronto</span><time data-offset="-4"></time></li>
                    <li><span>Paris</span><time data-offset="+2"></time></li>
                    <li><span>Beijing</span><time data-offset="+8"></time></li>
                    <li><span>Tokyo</span><time data-offset="+9"></time></li>
                    <li><span>Auckland</span><time data-offset="+12"></time></li>
                </ul>
            </div>
        
            <div id="date-time">
                <div class="jquery-annoyance">
                    <span id="date"></span>
                    <span id="time"></span>
                    <span id="mfbt">(possibly MFBT)</span>
                </div>
            </div>
        </div>
    </header>-->
    
    <section id="loading-message">
        <div>
            <p>Creating your ambiance...</p>
            <span></span>
        </div>
    </section>
    
    <section id="addfavorite" class="box">
        <div class="confirm">
            <h1>Favorite this tweet?</h1>
        
            <ul class="tweets">
            </ul>
        
            <p>Please only favorite interesting tweets (good and bad). Remember that the user will see that it was favorited by Mozilla HQ.</p>
        
            <h2>Press [ENTER] to confirm or any other key to cancel</h2>
        </div>
        
        <div class="loading">
            <h1>Are you human?</h1>
            
            <p>Enter the two words below to prove you're human:<br/><br/>
            <img src="images/captcha.png"/><br/><br/>
            <input type="text"/>
            </p>
            
            <h2>Just kidding; your request is processing...</h2>
        </div>
        
        <div class="done">
            <h1>Success! The selected tweet is now a favorite.</h1>
        </div>
        
        <div class="error">
            <h1>Error: there was a problem favoriting this tweet. Contact fligtar!</h1>
        </div>
    </section>
    
    <section id="content">
        
        <!-- Left column -->
        <section class="column">
            
            <div id="firefox">
                <h1><img src="images/firefox-128-noshadow.png"/><span>Firefox</span></h1>
            
                <div class="downloads">
                    <span class="change"></span>
                
                    <div class="total">
                        <span class="count">1,312,797,758</span>
                        <span class="label">total downloads</span>
                    </div>
                
                    <div class="fx36">
                        <span class="count">245,652,441</span>
                        <span class="label">3.6 downloads</span>
                    </div>
                    
                    <p class="note">live counts temporarily unavailable due to bad data</p>
                </div>
            
            </div><!-- /#firefox -->
        
        </section>
        
        <!-- Middle column -->
        <section class="column">
            
            <!-- Tweets -->
            <section id="all-tweets" class="panel">
                <h2>Twitter Stream</h2>
                
                <ul class="tweets">
                </ul>
            </section>
            
        </section>
        
        <!-- Right column -->
        <section class="column">
            
            <!-- Upcoming Events -->
            <section id="events" class="panel">
                <h2>Upcoming Events</h2>
                
                <dl>
                </dl>
                
                <p>No upcoming events</p>
            </section>
            
            <!-- Favorite Tweets -->
            <section id="favorite-tweets" class="panel">
                <h2>Our Favorites<a href="http://twitter.com/mozillafavs">follow @mozillafavs</a></h2>
                <p>See an interesting tweet? Use the keypad to enter its number and preserve it.</p>
                
                <ul class="tweets">
                </ul>
            </section>
            
        </section><!-- /#right -->
        
    </section><!-- /#content -->
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/sprightly.js?1"></script>
</body>
</html>
