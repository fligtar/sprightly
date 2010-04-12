<!DOCTYPE html>
<html lang="en">

<head>
    <title>sprightly</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
</head>

<body>
    <header>
        <h1>mozilla</h1>
        
        <section>
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
                <span id="date">Thursday, April 8</span>
                <span id="time">10:41 AM</span>
                <span id="mfbt">(possibly MFBT)</span>
            </div>
        </section>
    </header>
    
    <section id="content">
    
        <section id="firefox">
            <h1><img src="images/firefox-128-noshadow.png"/><span>Firefox</span></h1>
            
            <div class="downloads">
                <span class="change"></span>
                
                <div class="total">
                    <span class="count">3,134,124,124</span>
                    <span class="label">total downloads</span>
                </div>
                
                <div class="fx36">
                    <span class="count">134,124,124</span>
                    <span class="label">3.6 downloads</span>
                </div>
            </div>
        
            <div class="tweets">
                <h2>Scuttlebutt</h2>
                <ul>
                    <li><img src="http://a3.twimg.com/profile_images/266432199/13022009_005_-001_normal.jpg"><span>@someone</span>My <a href="#">@firefox</a> is seriously down for the count. I can't even uninstall it...lost all my bookmarks!! Grrrrr!!!</li>
                </ul>
            </div>
            
        </section><!-- /#firefox -->
        
        <section id="features">
            
            <div id="rotating">
                
                <div id="amo">
                    
                    <h1><img src="images/addons.png"/><span>Add-ons</span></h1>
                    
                    <p>AMO continues to see excellent growth, with <strong><span id="amo-public"></span> add-ons and Personas</strong> approved and available for download. Major progress has been made on the review queues, and there are currently <strong><span id="amo-pending"></span> pending updates</strong> to add-ons and <strong><span id="amo-nominated"></span> new add-ons</strong> awaiting Editor review.</p>
                    
                    <p>Since their launch last June, <strong><span id="amo-collections"></span> collections have been created</strong> by users, generating <strong><span id="amo-collectiondownloads"></span> add-on downloads</strong> from those collections.</p>
                    
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
    
            <div id="events">
                <h2>Today's Events</h2>
                
                <p>This will list today's events or some other calendar-like thing.</p>
            </div>
            
        </section><!-- /#features -->
    
        <section id="local">
            
            <h1>Local Information</h1>
        
            <div id="traffic">
                <h2>Current Traffic</h2>
                
                <div id="traffic-map"><b>&middot;</b></div>
                <span>updated <time datetime="" class="relative">just now</time></span>
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
                            <tr>
                                <td class="nb time">5:23</td>
                                <td class="nb type bullet">Bullet</td>
                                <td class="sb time">5:27</td>
                                <td class="sb type"></td>
                            </tr>
                            <tr>
                                <td class="nb time">5:23</td>
                                <td class="nb type limited">Limited</td>
                                <td class="sb time">5:27</td>
                                <td class="sb type bullet">Bullet</td>
                            </tr>
                            <tr>
                                <td class="nb time">5:23</td>
                                <td class="nb type"></td>
                                <td class="sb time">5:27</td>
                                <td class="sb type"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            
                <div id="weather">
                    <div id="weather-sf">
                        <h3>San Francisco</h3>
                        <img src="http://l.yimg.com/a/i/us/we/52/33.gif">
                        <span>Fair, 51 F</span>
                    </div>
                    
                    <div id="weather-mv">
                        <h3>Mountain View</h3>
                        <img src="http://l.yimg.com/a/i/us/we/52/33.gif">
                        <span>Fair, 51 F</span>
                    </div>
                    
                    <div id="weather-sj">
                        <h3>San Jos&eacute;</h3>
                        <img src="http://l.yimg.com/a/i/us/we/52/33.gif">
                        <span>Fair, 51 F</span>
                    </div>
                </div>
                
            </div><!-- /#caltrain-weather -->
            
            <div id="pto">
                <h2>Out of the Office</h2>
                
                <p>Once authentication is figured out, this will list people out of the office today.</p>
            </div>
            
            <section id="alert">Hey! This is an ambient display prototype. There are still some things to be fixed before rolling out to all displays. Please email fligtar@mozilla.com with feedback.</section>
            
        </section><!-- /#local -->
        
    </section><!-- /#content -->
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/sprightly.js"></script>
</body>
</html>
