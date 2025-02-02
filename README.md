ED ToolBox
==========

ED ToolBox is a companion web app for [Elite Dangerous] that runs on the user's computer, allowing a virtually real-time updating of location based data.

![Screenshot of Elite Dangerous Toolbox](style/img/elite-dangerous-toolbox.png)
[screenshot album](https://imgur.com/a/2daWF)

The best way to enjoy ED ToolBox is to run Elite Dangerous in borderless window mode and have a second monitor for ED ToolBox so you can see information at a glance and interact with ED ToolBox easily. Run ED ToolBox  in full screen mode (press F11) for maximum coolness. You can even access ED ToolBox from a secondary device such as your smart phone or tablet as long as it's in the same local network.

Key features
------------

- Real time system and station data based on your in-game location (data downloaded to the user's system from ED ToolBox server, which in turn gets its data from [EDDB])
- General & System specific captains log
- Two maps: Galaxy Map and a dynamically updating "Neighborhood Map", both showing your visited systems, points of interest and bookmarked systems + some other stuff.
- Add Points of Interest
- Bookmark systems
- Find nearest system/station by allegiance, power, or what modules or ships they are selling
- Read latest GalNet news
- Screenshot gallery: screenshots automatically converted to jpg and categorized by the system they were taken in. Option to upload to imgur straight from ED ToolBox
- VoiceAttack module: Meet "Marvin", the foul mouthed ship computer; get information about current system, closest station, latest GalNet articles + more with voice commands. Marvin really hates the Federation, so don't have any little kids or stuck up adults around when you're in Federation space.
- A notepad for taking some quick notes (mission directives, kill orders, etc.)
- Show currently playing song from Foobar2000 or any player that can store the current song in a text file, or from VLC Media Player using the web interface.

Installation
-------------

The ED ToolBox installer installs a basic web server on the user's computer (Apache, PHP and MySQL)

Before installing, review the requirements below.

- [Download the latest release](https://github.com/DBnR1/EDTB/releases/latest) and uncompress the file.
- Start EDTBManager_x_x_x.exe and follow the instructions in the setup wizard.
- After install is successfull, start ED ToolBox. This will start Apache webserver and MySQL database server. 
- A tray icon will appear. If the tray icon turns blue, that means the MySQL service is running.
- Right click the tray icon and choose "Open ED ToolBox". This will open the app in your web browser.
- An install prompt will appear that will quide you trough the rest of the process.

Requirements
------------

- ED ToolBox requires the [Visual C++ Redistributable for Visual Studio 2015] - **32 bit version**  vc_redist.x86.exe -- Without it, you'll get a missing dll error during install.

- Latest version of **Google Chrome** browser recommended for optimal experience. Latest versions of Mozilla Firefox and Microsoft Edge also work but to a limited degree.
- VerboseLogging needs to be on. To do this, locate your AppConfig.xml file.
	- In **Elite Dangerous: Horizons** the file is located in the ```elite-dangerous-64``` folder, which is located in one of the following folders, depending on your install:
		- C:\Users\%USERNAME%\AppData\Local\Frontier_Developments\Products
		- C:\Program Files (x86)\Frontier\EDLaunch\Products
		- C:\Program Files (x86)\Steam\steamapps\common\Elite Dangerous Horizons\Products
		- C:\Program Files (x86)\Frontier\Products
	- In **Elite Dangerous 1.5** it's located in the folder named ```FORC-FDEV-D-XX``` which will be located in one of the above locations, once again depending on your install.
  * Open the file in a text editor and scroll to the bottom. Replace this part:


    ```
    	<Network
    	  Port="0"
          upnpenabled="1"
    	  LogFile="netLog"
    	  DatestampLog="1"
    	  >
    ```
    * with this:
    ```
    	  <Network
    	  Port="0"
          upnpenabled="1"
    	  LogFile="netLog"
    	  DatestampLog="1"
    	  VerboseLogging="1"
    	  >
    ```
    * and save the file.
- VoiceAttack feature requires [VoiceAttack]


[Visual C++ Redistributable for Visual Studio 2015]: <https://www.microsoft.com/en-us/download/details.aspx?id=48145>
[EDDB]: <http://eddb.io>
[VoiceAttack]: <http://www.voiceattack.com/>
[Elite Dangerous]: <http://www.elitedangerous.com>
[edtb.xyz]: <http://edtb.xyz>
