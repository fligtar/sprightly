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
            <div id="weather">
                <img src="http://l.yimg.com/a/i/us/we/52/33.gif">
                <span>Fair, 51 F</span>
            </div>
            
            <div id="date-time">
                <span id="date">Thursday, April 8</span>
                <span id="time">10:41 AM</span>
            </div>
        </section>
    </header>
    
    <section id="content">
    
        <section id="firefox">
            <img src="images/firefox-128-noshadow.png">
            <h1>Firefox</h1>
            
            <div class="downloads">
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
                <h2>Tweets @firefox</h2>
                <ul>
                    <li><img src="http://a3.twimg.com/profile_images/266432199/13022009_005_-001_normal.jpg"><span>@someone</span>My <a href="#">@firefox</a> is seriously down for the count. I can't even uninstall it...lost all my bookmarks!! Grrrrr!!!</li>
                </ul>
            </div>
        </section>
    
        <section>
            AMO stuff
        </section>
    
        <section>
            Upcoming Events
        </section>
    
        <section id="transportation">
            <h1>Transportation</h1>
        
            <div id="traffic">
                <div id="traffic-map"></div>
                <span>updated 5 minutes ago</span>
            </div>
        
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
        </section>
    </section>
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/sprightly.js"></script>
</body>
</html>
