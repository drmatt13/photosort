<?php
// fix key folder names
// make GitHub + documentation and explain how it works
// make first image big and put metadata on left
// add javascript keyboard capture

// HTML Template, styles, font, header Included.
echo "<!doctype html>\n<html>\n<head>\n<title>PHP Image Sorter</title>\n<link rel='stylesheet' type='text/css' href='./style.css'>\n</head>\n<body>\n<br /><h1>PHP Image Sorter by Charlie</h1>\n";

echo "<script src='httfddjps://cdn.jsdelivr.net/npm/exif-js'></script>/";


// This folder contains all of the images that need to be sorted
// example = "images/";
$imagesDirectory = "images/";

// Count jpg and png files in the images directory
$filecount = 0;
$files = glob($imagesDirectory . "*.{jpg,png}",GLOB_BRACE);
if ($files){ $filecount = count($files); }

// HTML Filecount Output
echo "<p id='fileCount'>There are <span>$filecount</span> images left to sort!</p>";

// Tell us what the last moved file was // TODO:Hide the out put if cookie is not set
echo "<p id='theMove'>The file ".$_COOKIE["lastMoveNam"]." was moved to the folder ".$_COOKIE["lastMoveTgt"]."</p>";

// Make an array out of the images inside of the imagesDirectory
$imagesArray = glob($imagesDirectory.'*.{jpg,png}', GLOB_BRACE);

// Get the first image in the imagesArray array
$focusImage = substr($imagesArray[0], strlen($imagesDirectory));

// For every item in the imagesArray use imageFile as the variable
foreach($imagesArray as $imageFile) {
// Get thumbnail data from each image so the app doesn't crash with thousands of images
$imageThumb = exif_thumbnail($imageFile, $width, $height, $type);   
    if ($imageThumb!==false) {
        // Output the thumbnail as base64 to not create more unneeded files
        echo "<img alt='$imageFile' width='$width' height='$height' src='data:image/jpg;base64,".base64_encode($imageThumb)."'> \n";
    } else {
        // If thumbnail doesn't exist within EXIF data output full sized image // TODO error handling for thumbnails that do not work
        echo "<img alt='$imageFile (no thumbnail available for this image)' src='".$imageFile."'> \n";
    }
};


// Create an array of keyboard shortcuts
$kbKeys = array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l");

// Define Z as the key for the undo function // TODO create undo function
$undoKey = "z";

// create array from folders
$dirs = array_filter(glob('sort/*'), 'is_dir');

// Compare the number of values in each array so they can be combined
function combine_arr($a, $b)
{
    $acount = count($a);
    $bcount = count($b);
    $size = ($acount > $bcount) ? $bcount : $acount;
    $a = array_slice($a, 0, $size);
    $b = array_slice($b, 0, $size);
    return array_combine($a, $b);
}

// Combine the two arrays so each folder is matched with a keyboard key
$combined = combine_arr($kbKeys, $dirs);

// Echo each key and value from combined array of keyboard shortcuts
echo '<div id="kbShorts">';
function printer($v, $k) {
    global $focusImage, $imagesDirectory;
    $shortFolder = $v;
    // Create a link to be sent to mover.php with the Source, Name, and Target of the image.
    echo "<a href='mover.php?source=$imagesDirectory&name=$focusImage&folder=$v'><span>$k</span><br />$shortFolder</a>"; 
};

// Display each keyboard shorcut
array_walk($combined, "printer");

// End the document
echo "</div>\n</body>\n</html>";

?>