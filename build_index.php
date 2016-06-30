<?php

$index = array();

/**
 *
 * build index of files
 *
 */

foreach (glob("designs/*/*.html") as $fullpath) {
    $pathArr = explode('/',$fullpath);
    $subject = $pathArr[1];
    $filename = $pathArr[2];

    $content = @file_get_contents($fullpath);
    if (!$content) continue;

    // file link
    $fileinfo['link'] = $fullpath;

    // parse title
    if (preg_match('/<title>(.*?)<\/title>/', $content, $matches)) {
        $fileinfo['title'] = $matches[1];
    } else {
        $fileinfo['title'] = $filename;
    }

    // parse description
    if (preg_match('/<meta name="description" content="(.*?)">/', $content, $matches)) {
        $fileinfo['description'] = $matches[1];
    } else {
        $fileinfo['description'] = $fileinfo['title'];
    }

    // append file info to index
    $index[$subject]['files'][] = $fileinfo;

}


/**
 *
 * open the index.html file and reset the index div
 *
 */

$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
$doc->formatOutput = true;
$doc->loadHTMLFile("index.html");
$docIndex = $doc->getElementById('index');
$docIndex->nodeValue = '';



/**
 *
 * loop the index & build html
 *
 */

foreach ($index as $subject  => $items) {
    
    // add new h2 header with title of the section
    $header = ucwords(str_replace("_", " ", $subject));
    $el = $doc->createElement('h2', $header);
    $docIndex->appendChild($el);

    // create new list of files in this section
    $dl = $doc->createElement('dl');
    foreach ($items['files'] as $file) {
        // create a tag with link & title
        echo "-> adding \"" . $file['title'] . "\" to index." . PHP_EOL;
        $a = $doc->createElement('a', $file['title']);
        $a->setAttribute('href', $file['link']);
        // create dt tag
        $dt = $doc->createElement('dt');
        // link all to dom
        $dt->appendChild($a);
        $dl->appendChild( $dt );
        // add dd tag with description
        $dl->appendChild( $doc->createElement('dd',$file['description']) );
    }
    $docIndex->appendChild($dl);
}

$result = @file_put_contents('index.html', $doc->saveHTML());

if ($result) {
    echo "-- done";
    exit;
} else {
    echo "-- failed to write html to file";
    exit;
}