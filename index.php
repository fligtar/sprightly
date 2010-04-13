<?php
/*
    This is the front-end for the ambient displays and should be loaded 
    full-screen in the browser. All data will be refreshed automatically
    at predetermined intervals.
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>sprightly</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
</head>

<body>
    <div id="debug"></div>
    <header>
        <h1>mozilla</h1>
        
        <section id="clocks">
            <div id="world-clock">
                <ul>
                    <li><time offset="-4"></time><span>Toronto, Canada</span></li>
                    <li><time offset="+2"></time><span>Paris, France</span></li>
                    <li><time offset="+8"></time><span>Beijing, China</span></li>
                    <li><time offset="+9"></time><span>Tokyo, Japan</span></li>
                    <li><time offset="+12"></time><span>Auckland, New Zealand</span></li>
                </ul>
            </div>
            
            <div id="date-time">
                <span id="date"></span>
                <span id="time"></span>
                <span id="mfbt">(possibly MFBT)</span>
            </div>
        </section>
    </header>
    
    <section id="loading-message">
        <div>
            <p>Creating your ambiance...</p>
            <span></span>
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
                    <h2>Overheard</h2>
                    <ul>
                    </ul>
                </div>
            
            </div><!-- /#firefox -->
        
        </section><!-- /#left -->
        
        <section id="middle">
            
            <div id="rotating">
                <span class="featured">Featured Projects<span class="next">next: Support</span></span>
                
                <div id="amo">
                    
                    <h1><img src="images/addons.png"/><span>Add-ons</span></h1>
                    
                    <p>The AMO gallery continues to grow, with <strong><span id="amo-public"></span> add-ons and Personas</strong> approved and available for download. Major progress has been made on the review queues, and there are currently <strong><span id="amo-pending"></span> pending updates</strong> to add-ons and <strong><span id="amo-nominated"></span> new add-ons</strong> awaiting Editor review.</p>
                    
                    <p>Since their launch last June, <strong><span id="amo-collections"></span> collections have been created</strong> by users, generating <strong><span id="amo-collectiondownloads"></span> add-on downloads</strong> from those collections.</p>
                    
                    <p>Rock Your Firefox, our new blog featuring 3 consumer-friendly add-ons every week, continues to entertain its <strong><span>5,000+</span> daily readers</strong> and highlight some of the best ways to customize Firefox.</p>
                    
                    <p>The add-ons team is currently kept busy rewriting the site in Django, absorbing GetPersonas.com, helping with the Firefox Add-ons Manager redesign, and planning improvements to a number of features.</p>
                    
                    <div id="amo-counts">
                        <div>
                            <span id="amo-downloads" class="count"></span>
                            <span class="label">add-on downloads</span>
                        </div>
                    
                        <div>
                            <span id="amo-adu" class="count"></span>
                            <span class="label">add-ons in use</span>
                        </div>
                    </div>
                    
                </div><!-- /#amo -->
                
            </div><!-- /#rotating -->
            
            <div id="local">
                
                <div id="traffic">
                    <h2>Current Traffic</h2>
                
                    <div id="traffic-map"><b>&middot;</b></div>
                    <span>updated <time datetime="" class="relative"></time></span>
                </div>
            
                <div id="caltrain-weather">
                
                    <div id="caltrain">
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
                    </div>
                    
                    <div id="weather">
                        <div id="weather-sf">
                            <h3>San Francisco</h3>
                            <img src=""/>
                            <span></span>
                        </div>
                    
                        <div id="weather-mv">
                            <h3>Mountain View</h3>
                            <img src=""/>
                            <span></span>
                        </div>
                    
                        <div id="weather-sj">
                            <h3>San Jos&eacute;</h3>
                            <img src=""/>
                            <span></span>
                        </div>
                    </div>
                
                </div><!-- /#caltrain-weather -->
            
            </div><!-- /#local -->
            
        </section><!-- /#middle -->
        
        <section id="right">
            
            <div id="events">
                <h2>Today's Events</h2>
                
                <p>This will list today's events or some other calendar-like thing.</p>
            </div>
            
            <div id="pto">
                <h2>Out of the Office</h2>
                
                <p>Once authentication is figured out, this will list people out of the office today.</p>
            </div>
            
        </section><!-- /#right -->
        
    </section><!-- /#content -->
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/sprightly.js"></script>
</body>
</html>
