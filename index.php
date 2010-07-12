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
    <header>
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
    </header>
    
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
    
        <section id="left">
            
            <div id="firefox">
                <h1><img src="images/firefox-128-noshadow.png"/><span>Firefox</span></h1>
            
                <div class="downloads">
                    <span class="change"></span>
                
                    <div class="total">
                        <span class="count">1,312,797,758</span>
                        <span class="label">total downloads</span>
                    </div>
                
                    <div class="fx36">
                        <span class="count">323,871,380</span>
                        <span class="label">3.6 downloads</span>
                    </div>
                    
                    <p class="note">live counts temporarily unavailable due to bad data</p>
                </div>
        
                <div class="tweets">
                    <h2>on Twitter</h2>
                    <ul>
                    </ul>
                </div>
            
            </div><!-- /#firefox -->
        
        </section><!-- /#left -->
        
        <section id="middle">
            
            <div id="rotating">
                <span class="featured">Featured Projects<!--><span class="next">next: <span class="label">Support</span> in <time class="relative mins_until"></time>m</span>--></span>
                
                <?php /*<div id="summit" class="box active" data-label="Summit" data-duration="30">

                    <h1><img src="images/summit-header.png"/></h1>
                    <h2><time datetime="2010-07-07T00:00:00-07:00" class="relative days_until"></time> days</h2>
                    
                    <div>
                        <h3>Latest News</h3>
                        
                        <ul>
                            <li>All attendees should receive their travel itinerary by Wednesday, June 9th</li>
                            <li>Register a Breakout Session, Science Fair exhibit, or Lightning Talk before June 15th</li>
                            <li>Tag your tweets and photos with <code>moz10</code> and join us in <code>#moz10</code> on IRC</li>
                        </ul>
                        
                        <p>Learn more at <u>http://wiki.mozilla.org/Summit2010</u></p>
                    </div>

                </div> */?>
                
                <div id="amo" class="box active" data-label="Add-ons" data-duration="15">
                    
                    <h1><img src="images/addons.png"/><span>Add-ons</span></h1>
                    
                    <!-- General Stats. inline display:block is required to stop a warping bug. don't ask -->
                    <div id="amo-1" class="panel active" style="display: block;">
                        <div class="stats-container">
                                <div>
                                    <span id="amo-public" class="count"></span>
                                    <span class="label">approved add-ons and Personas</span>
                                </div>
                                
                                <div>
                                    <span id="amo-collections" class="count"></span>
                                    <span class="label">collections created</span>
                                </div>
                        </div>
                        <div class="stats-container">
                            <div>
                                <span id="amo-downloads" class="count"></span>
                                <span class="label">add-on downloads</span>
                            </div>

                            <div>
                                <span id="amo-adu" class="count"></span>
                                <span class="label">add-ons in use</span>
                            </div>
                        </div>
                        
                        <p class="caption"><em>(imagine this text is a chart)</em></p>
                    </div>
                    
                    <!-- Review Queues -->
                    <div id="amo-2" class="panel">
                        <p class="intro">Our review queues have been consistently under control since Firefox 3.6.</p>
                        
                        <div class="stats-container">
                                <div>
                                    <span id="amo-pending" class="count"></span>
                                    <span class="label">pending updates to public add-ons</span>
                                </div>
                                
                                <div>
                                    <span id="amo-nominated" class="count"></span>
                                    <span class="label">new add-ons awaiting review</span>
                                </div>
                        </div>
                        
                        <div class="stats-container">
                                <div>
                                    <span class="count">90%</span>
                                    <span class="label">of pending updates reviewed in</span>
                                    <span class="count">under 5 days</span>
                                </div>
                                
                                <div>
                                    <span class="count">97%</span>
                                    <span class="label">of new add-ons reviewed in</span>
                                    <span class="count">under 2 weeks</span>
                                </div>
                        </div>
                        
                        <p>Thanks to our editor team of volunteers, contractors, and employees!</p>
                    </div>
                    
                    <!-- What's New -->
                    <div id="amo-3" class="panel">
                        <h2>Recent News</h2>
                        <ul class="spaced">
                            <li>Our first Zamboni pages (Django rewrite) went live in early May and are now serving 100% of homepage and add-on details page traffic</li>
                            <li>We're holding a Mozilla Add-ons Workshop in London on June 30</li>
                            <li>Rock Your Firefox continues to feature several consumer-friendly add-ons each week with great commentary</li>
                            
                        </ul>
                    </div>
                    
                    <!-- Coming Soon -->
                    <div id="amo-4" class="panel">
                        <h2>Coming Up</h2>
                        <ul class="spaced">
                            <li>New AMO interactions in the Firefox 4 Add-ons Manager</li>
                            <li>Aiming to launch all public pages in Django rewrite by end of Q2</li>
                            <li>Major revamps of Developer Tools and our review process coming in Q3</li>
                            <li>Add-ons Marketplace discussion at the Summit</li>
                        </ul>
                    </div>
                    
                    <!-- Get Involved -->
                    <div id="amo-5" class="panel">
                        <h2>Non-technical Ways to Help </h2>
                        <p>Get involved with AMO by writing reviews of add-ons you use, creating collections, testing the site, and filing bugs and enhancement requests.</p>
                        
                        <h2>Technical Ways to Help</h2>
                        <p>Becoming an editor and reviewing add-ons is a great way to see how people are using the platform and what problems they encounter. Answering questions on forums.addons.mozilla.org will unblock a stuck developer.</p>
                        <p>And please, if you write an add-on, host it on AMO and not your people.mozilla.com account! It's safer, more discoverable, promotes AMO, and provides stats.</p>
                    </div>
                    
                    <ul class="menu">
                        <li id="amo-1m" class="active">Statistics</li>
                        <li id="amo-2m">Review Queues</li>
                        <li id="amo-3m">What's New</li>
                        <li id="amo-4m">Coming Soon</li>
                        <li id="amo-5m">Get Involved</li>
                    </ul>
                    
                </div><!-- /#amo -->
                
                <!--><div id="sumo" class="box" data-label="Support" data-duration="2">

                    <h1><img src="images/sumo.png"/><span>Support</span></h1>

                    <div id="support-1" class="panel active">
                        <p>Cheng, give me support data!</p>
                    </div>

                    <ul class="menu">
                        <li id="support-1m" class="active">A Plea</li>
                    </ul>

                </div>-->
                

                
            </div><!-- /#rotating -->
            
            <div id="favorites" class="tweets">
                <h2>Our Favorites<a href="http://twitter.com/mozillafavs">follow @mozillafavs</a></h2>
                <p>See an interesting tweet? Use the keypad to enter its number and preserve it.</p>
                
                <ul>
                </ul>
            </div><!-- /#favorites -->
            
        </section><!-- /#middle -->
        
        <section id="right">
            
            <div id="events" class="box">
                <h2>Upcoming Events</h2>
                
                <dl>
                </dl>
                
                <p>No upcoming events</p>
            </div><!-- /#events -->
            
            <div id="caltrain" class="box hide-toronto">
                <h2>Mountain View <span class="logo">Cal<span class="train">train</span></span></h2>
    
                <table>
                    <thead>
                        <tr>
                            <th colspan="2"><span>San Francisco</span></th>
                            <th colspan="2"><span>San Jos&eacute;</span></th>
                        </tr>
                    </thead>
        
                    <tbody>
                    </tbody>
                </table>
            </div><!-- /#caltrain -->
            
            <div id="traffic" class="box hide-toronto">
                <h2>Current Traffic</h2>
            
                <div id="traffic-map"><b>&middot;</b></div>
                <span>updated <time datetime="" class="relative"></time></span>
            </div><!-- /#traffic -->
            
        </section><!-- /#right -->
        
    </section><!-- /#content -->
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/sprightly.js?1"></script>
</body>
</html>
