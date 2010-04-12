<!DOCTYPE html>
<html lang="en">

<head>
    <title>sprightly</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
</head>

<body>
    <section id="alert">Hey! This is an ambient display prototype. There are still some things to be fixed before rolling out to all displays. Please email fligtar@mozilla.com with feedback.</section>
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
                                <th colspan="2">San Francisco</th>
                                <th colspan="2">San Jos&eacute;</th>
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
            
        </section><!-- /#local -->
        
    </section><!-- /#content -->
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/sprightly.js"></script>
</body>
</html>
