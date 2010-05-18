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
                        <span class="count"></span>
                        <span class="label">total downloads</span>
                    </div>
                
                    <div class="fx36">
                        <span class="count"></span>
                        <span class="label">3.6 downloads</span>
                    </div>
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
                <span class="featured">Featured Projects<span class="next">next: <span class="label">Support</span> in <time class="relative mins_until"></time>m</span></span>
                
                <div id="amo" class="box active" data-label="Add-ons" data-duration="15">
                    
                    <h1><img src="images/addons.png"/><span>Add-ons</span></h1>
                    
                    <!-- General Stats. inline display:block is required to stop a warping bug. don't ask -->
                    <div id="amo-1" class="panel active" style="display: block;">
                        <div>
                            <span id="amo-public" class="count"></span>
                            <span class="label">approved add-ons and Personas</span>
                        </div>
                        <div>
                            <div>
                                <span id="amo-downloads" class="count"></span>
                                <span class="label">add-on downloads</span>
                            </div>

                            <div>
                                <span id="amo-adu" class="count"></span>
                                <span class="label">add-ons in use</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Collections -->
                    <div id="amo-2" class="panel">
                    <p>Since their launch last June, <strong><span id="amo-collections"></span> collections have been created</strong> by users, generating <strong><span id="amo-collectiondownloads"></span> add-on downloads</strong> from those collections.</p>
                    </div>
                    
                    <!-- Review Queues -->
                    <div id="amo-3" class="panel">
                    <p>Major progress has been made on the review queues, and there are currently <strong><span id="amo-pending"></span> pending updates</strong> to add-ons and <strong><span id="amo-nominated"></span> new add-ons</strong> awaiting Editor review.</p>
                    </div>
                    
                    <!-- RYF -->
                    <div id="amo-4" class="panel">
                        <p>Rock Your Firefox, our new blog featuring 3 consumer-friendly add-ons every week, continues to entertain its <strong><span>5,000+</span> daily readers</strong> and highlight some of the best ways to customize Firefox.</p>
                    </div>
                    
                    <!-- Roadmap -->
                    <div id="amo-5" class="panel">
                        <p>What the add-ons team is currently working on:</p>
                        <ul>
                            <li>rewriting addons.mozilla.org in Django</li>
                            <li>absorbing GetPersonas.com</li>
                            <li>helping with the Firefox 4 Add-ons Manager redesign</li>
                            <li>working on big improvements to core features and tools</li>
                            <li>planning developer and consumer events</li>
                            <li>Jetpack, marketplace, and lots more</li>
                        </ul>
                    </div>
                    
                    <ul class="menu">
                        <li id="amo-1m" class="active">Overview</li>
                        <li id="amo-2m">Collections</li>
                        <li id="amo-3m">Review Queues</li>
                        <li id="amo-4m">Rock Your Firefox</li>
                        <li id="amo-5m">Roadmap</li>
                    </ul>
                    
                </div><!-- /#amo -->
                
                <div id="sumo" class="box" data-label="Support" data-duration="2">

                    <h1><img src="images/sumo.png"/><span>Support</span></h1>

                    <!-- Test 1 -->
                    <div id="support-1" class="panel active">
                        <p>Cheng, give me support data!</p>
                    </div>

                    <ul class="menu">
                        <li id="support-1m" class="active">A Plea</li>
                    </ul>

                </div><!-- /#support -->
                
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
    <script type="text/javascript" src="js/sprightly.js"></script>
</body>
</html>
