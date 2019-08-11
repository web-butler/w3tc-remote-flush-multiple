# W3TC Remote Flush Multiple Websites
This PHP utility enables you to flush all cache of multiple W3TC-powered websites from one control panel that is password-protected.

## Requirements
- Requires PHP (of course)
- The domains of websites to be available to flush need to be added to the JSON-file
- The remote webites need to have the flush script installed

## Features
- The ability to flush multiple websites at once
- CURL http status output and remote script output
- Flushing multiple websites at once
- Password-protected control panel, with cookie-setting
- Password-protected access of flush script on remote website (only secure enough to protect from accidental discoveries of the file)
- JSON file with list of domains
- also works on password-protected staging websites: digest http auth capable (by adding username and password to the JSON file along with the website's domain)
- sub-domain-ready

## Installation
### Install files
/home/ --- not publicly accessible folder
-> git clone this repository. This will create the folder 'w3tc-remote-flush-multiple'

/home/your-website.com/ --- your website, or more generally, a path that's publicly accessible
-> move the file 'control-panel-access.php' here. It will now be accessible under http://your-website.com/control-panel-access.php
-> make sure you change the password in that file to something else than the standard

/home/website-to-flush.com/ --- any publicly available website you'd like to flush, it can also be on another server!
-> move a copy of flush-script.php here
-> make sure you change the password for flush-script.php in that file, as well as in w3tc-remote-flush-multiple.php on line 105

### Set up domains in the JSON file
You can see examples in the JSON file. The third example is for a website using http digest authentication.

### Screenshots
Login Screen:
![Login Screen](https://github.com/web-butler/w3tc-remote-flush-multiple/blob/master/1-login-screen.png?raw=true)

Flush Screen:
![Login Screen](https://github.com/web-butler/w3tc-remote-flush-multiple/blob/master/2-flush-screen.png?raw=true)

Result Screen:
![Login Screen](https://github.com/web-butler/w3tc-remote-flush-multiple/blob/master/3-result-screen.png?raw=true)

## Enjoy!
If you have any questions, don't hesitate to contact us at https://web-butler.ch.
