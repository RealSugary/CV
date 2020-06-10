
## Projects that I developed
*   Mobile Apps
     * CC OfferGod:  an application with a Local Articulation Enquiry Database Management System (Non-Jupas offer database).
     * WiFiScanner:  an offline database of the Wi-Fi BSSID, Wi-Fi SSID and the geographical locations by wardriving method.
     
*   Web Apps ( using Bootstrap Framework )
    * Personal Profolio:    a website that introducing myself.
    * Broadcast Platform:   a streaming platform ( hosting RTMP server for streaming & Rachet WebSocket server for chatroom )
                            with security measurement ( etc. SSL, X-FRAME-OPTIONS, Content Security Policy, pfsense firewall
                            and so on. )
    * Chat Application:     a online chat application using Rachet WebSocket.
    
## Prerequisites

  Software that you need to install:
  * Android Studio ( for Mobile Applications )
  
  
## How to view the *Source Code* or the *Application*?
Mobile Apps:

  <ul>
    <li> <b> Method 1:  Through Android Studio </b> </li>
                1.  Open Android Studio.<br>
                2.  Click Open an existing Android Studio project.<br>
                3.  Select the file in "../Application Source Code" .<br>
                4.  Wait until the build completed successfully.<br>
                5.  Click "Project" section that on left edge and open the files.<br>
                6.  View the Source Code.
    <li> <b> Method 2:  Run the Application using Android Studio </b> </li>
                1.  Open Android Studio.<br>
                2.  Click Open an existing Android Studio project.<br>
                3.  Select the file in "../Application Source Code" .<br>
                4.  Wait until the build completed successfully.<br>
                5.  Click "Run 'app'" in the "Run" section on top bar.<br>
                6.  Select the emulator and run it.
    <li> <b> Method 3:  Run the Application using Android Phone </b> </li>
                1.  Install the .apk in the Project Folder.<br>
                2.  Run it.

  </ul>

Web Apps:
  <ul>
    <li> <b> Method:  Install the server folder ( htdocs ) into WebServer etc. XAMPP </b> </li>
                1.  Copy the htdocs folder to server root.<br>
                2.  Import the .sql file to phpmyadmin.<br>
                3.  Start apache and mysql server.<br>
                3.1 Host the Rachet WebSocket Server: ( for broadcast platform & online chat application )<br>
                3.1.1.  Run Command Port / Terminal.<br>
                3.1.2.  Go the htdocs/webSocket/server path.<br>
                3.1.3.  Run the server.php by this command “php server.php”.<br>
                4.  Browse the website with modern browser.
