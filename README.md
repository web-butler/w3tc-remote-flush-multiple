# W3TC Remote Flush Multiple Websites (W3 Total Cache)
This PHP utility enables you to flush all cache of multiple W3TC-powered websites from one control panel that is password-protected.

## Requirements
- Requires PHP (of course)
- The domains of websites to be available to flush need to be added to a JSON-file
- The remote webites need to have the flush script installed

## Features
- The ability to flush multiple websites at once
- CURL http status output and remote script output
- Password-protected control panel, with cookie-setting
- Password-protected access of flush script on remote website (only secure enough to protect from accidental discoveries of the file)
- JSON file with list of domains that can easily be maintained
- also works on password-protected staging websites: digest http auth capability (by adding username and password to the JSON file alongside the website's domain)

## Installation
### Install files
/home/ --- not publicly accessible folder
-> git clone this repository. This will create the folder 'w3tc-remote-flush-multiple'

/home/www/ --- a path that's publicly accessible to run control-panel-access.php
-> make sure you change the "correctPassword" in that file to something else than the standard

example.com --- any publicly available website you'd like to flush, it can also be on another server!
-> move a copy of flush-script.php there
-> make sure you change the password for flush-script.php in that file, as well as in w3tc-remote-flush-multiple.php on line 9

### Set up domains in the JSON file
You can see examples in the JSON file. The third example is for a website using http digest authentication.

## Screenshots
Login Screen:
![Login Screen](https://github.com/web-butler/w3tc-remote-flush-multiple/blob/master/screenshots/1-login-screen.png?raw=true)

Flush Screen:
![Login Screen](https://github.com/web-butler/w3tc-remote-flush-multiple/blob/master/screenshots/2-flush-screen.png?raw=true)

Result Screen:
![Login Screen](https://github.com/web-butler/w3tc-remote-flush-multiple/blob/master/screenshots/3-result-screen.png?raw=true)

## Enjoy!
If you have any questions, don't hesitate to contact us at https://web-butler.ch.
