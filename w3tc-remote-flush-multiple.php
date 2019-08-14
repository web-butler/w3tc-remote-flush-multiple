<?php
/*
 * Control Panel for flushing multiple websites' W3TC Plugin's Cache entirely
 * Developed by webButler (https://web-butler.ch)
 */
ignore_user_abort(false);
set_time_limit(30);

$flushScriptPassword = 'password';

// retrieve information about which websites need to be flushed
if (isset($_POST)) {
    $websitesToFlush = $_POST;
}

// Generate an object with websites and IDs from the JSON file
// (relative to flush-admin-access.php , cause that's where it's being executed)
$jsonFile = file_get_contents('../websites.json'); 
$websiteArray = json_decode( $jsonFile, true );

// define startsWith to filter stage envs (will output true if string starts with startString)
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
} 

// in case the page is a stage and has page url, user, and pass as array as value, choose the page
function createCheckboxes($pageOrArray,$key) {
    if (is_array($pageOrArray)) {
        $page = $pageOrArray[0];
    } else {
        $page = $pageOrArray;
    }
    echo "<label><input type='checkbox' name='";
    echo $key;
    echo "' value='";
    echo $page;
    echo "'>";
    echo $page;
    echo "</label><br />";
}

function createFlushForm() {
    echo "<h2>Choose the website(s) to flush</h2>
    <p>Caution: it's wiser to choose only one website at a time! (when do you really need to flush several websites at once?)</p>
    <div class='columns'>
    <form action='' method='post'>";
    global $websiteArray;
    array_walk($websiteArray,"createCheckboxes"); // throws the array into the function for each element
    echo "</div>
    <input type='submit' value='Flush All W3TC Cache'>
    </form>";
}

function doCurl($page,$key) {
    $isStaging = startsWith($page,'stage.');
    // in case the page is a stage and has page url, user, and pass as array as value, assign variables accordingly
    if ($isStaging) {
        global $websiteArray;
        $stagingArray = $websiteArray[$key];
        $username = $stagingArray[1];
        $password = $stagingArray[2];
    }
    global $flushScriptPassword;
    $url = 'https://'.$page.'/flush-script.php?password='.$flushScriptPassword;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // set max. execution time for the curl
    curl_setopt($ch, CURLOPT_POST, 1); // require post
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // make curl_exec return instead of output data
    echo '<p>';
    // check if the site is a stage, and if so, add user and password
    if ($isStaging) {
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        echo "[http digest auth] ";
    }
    // this line executes curl and postes the script output (http result) of the executed url
    $curl_result = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // store http status
    if ($http_status != 200) {
        echo 'Error'; // if curl doesn't return http status 200, print 'Error'
    } else {
        echo $curl_result; // otherwise print text in remote script (here: 'Script executed')
    };
    echo ' with a http status of ';
    echo $http_status;
    echo ' on ';
    // add the url and a link to the website
    echo '<a href="https://'.$page.'" target="_blank">'.$page.'<a/>';
    echo '</p>';
    curl_close($ch);
}

function flushWebsites() {
    echo "<h2>Results are here:</h2>";
    global $websitesToFlush;
    array_walk($websitesToFlush,"doCurl"); // throws the array into the function for each element
echo "<form action=''>
<input type='submit' value='Reset and flush again/more'>
</form>";
}

// if nothing was chosen to be flushed, create form to choose what needs to be flushed
if (!$websitesToFlush) {
    createFlushForm();
// if something needs to be flushed, do that using curl
} else {
    flushWebsites();
}
