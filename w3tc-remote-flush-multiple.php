<?php
/*
 * Control Panel for flushing multiple websites' W3TC Plugin's Cache entirely
 * Developed by webButler (https://web-butler.ch)
 */
ignore_user_abort( false );

// Generate an array with websites and IDs from the JSON file
$json = file_get_contents('../w3tc-remote-flush-multiple/websites.json'); // (relative to flush-admin-access.php , cause that's where it's being executed)
$websitesArray = json_decode( $json, true );
// print_r($websitesArray);

// print basic html file with bootstrap css and some custom styles
echo "<html>
    <head>
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
    <style>
    a { color: #fff; }
    a:hover { color: #fff; }
    body { color: #ccc; background: -webkit-gradient(linear,left top, right top,from(#000000),color-stop(80%, #4d4d4d)); background: -o-linear-gradient(left,#000000,#4d4d4d 80%); background: linear-gradient(to right,#000000,#4d4d4d 80%); }
    .logo { width: 150px; margin-left: 10px; }
    input[type=checkbox] { margin-right: 5px; }
    input[type=submit] { background-color: #353535; border: none; color: white; padding: 10px 16px; text-decoration: none; margin: 5px 0; cursor: pointer; font-size: 1rem; border-radius: 4px; }
    h1 { margin-top: 10px; font-size: 1.5rem; color: #fff; }
    h2 { margin-top: 5px; font-size: 1.2rem; color: #dedede; }
    @media screen and (min-width: 600px) {
        h1 { margin-top: 10px; font-size: 1.6rem; }
        h2 { margin-top: 5px; font-size: 1.3rem; }
        .columns {
            -webkit-column-count: 2; /* Old Chrome, Safari and Opera */
            -moz-column-count: 2; /* Old Firefox */
            column-count: 2;
        }
    }
    @media screen and (min-width: 900px) {
        h1 { margin-top: 10px; font-size: 1.9rem; }
        h2 { margin-top: 5px; font-size: 1.5rem; }
        .columns {
            -webkit-column-count: 3; /* Old Chrome, Safari and Opera */
            -moz-column-count: 3; /* Old Firefox */
            column-count: 3;
        }
    }
    @media screen and (min-width: 1200px) {
        h1 { margin-top: 10px; font-size: 2.3rem; }
        h2 { margin-top: 5px; font-size: 1.8rem; }
        .columns {
            -webkit-column-count: 4; /* Old Chrome, Safari and Opera */
            -moz-column-count: 4; /* Old Firefox */
            column-count: 4;
        }
    }
    </style>
    </head>
    <body>
    <div class='container' id='main-content'>
    <h1>Flush W3TC Cache remotely</h1>";

// retrieve information about what needs to be flushed
$toFlush = $_GET;

// define startsWith to filter stage envs (will output true if string starts with startString)
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
} 

// if nothing was chosen to be flushed, create form to choose what needs to be flushed
if (!$toFlush) {
    echo "<p>..powered by:  <a href='https://web-butler.ch/' target='_blank'>webButler</a></p>
    <h2>Choose the website(s) to flush</h2>
	<p>Caution: it's wiser to choose only one website at a time! (when do you really need to flush several websites at once?)</p>
    <div class='columns'>
    <form action=''>";
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
    array_walk($websitesArray,"createCheckboxes"); // throws the array into the function for each element
    echo "</div>
    <input type='submit' value='Flush All W3TC Cache'>
    </form>";
// if something needs to be flushed, do that using curl
} else {
    echo "<h2>Results are here:</h2>";
    function flushWebsites($page,$key) {
        // in case the page is a stage and has page url, user, and pass as array as value, assign variables accordingly
        if (startsWith($page,'stage')) {
            global $websitesArray;
            $array = $websitesArray[$key];
            $username = $array[1];
            $password = $array[2];
        }
        $url = 'https://' . $page . '/flush-script.php?pass=password';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // set max. execution time for the curl
        curl_setopt($ch, CURLOPT_POST, 1); // require post
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // make curl_exec return instead of output data
        // print_r($username);
        // print_r($password);
        echo '<p>';
        if (startsWith($page,'stage')) {
            // check if the site is a stage, and if so, add user and password
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            echo "[http digest auth] ";
        }
        // this line executes curl and postes the script output (http result) of the executed url
        $curl_result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // store http status
        if ($http_status != 200) {
            echo 'Error'; // if curl doesnt' return http status 200, print 'Error'
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
    array_walk($toFlush,"flushWebsites"); // throws the array into the function for each element
    echo "<form action=''>
    <input type='submit' value='Reset and flush again/more'>
    </form> 
    </div>
    </body>
    </html>";
}
