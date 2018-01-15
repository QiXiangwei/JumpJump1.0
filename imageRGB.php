<?php

$image = imagecreatefrompng('screen.png');
$imageWidth = imagesx($image);
$imageHight = imagesy($image);
$up = $imageHight/4;
$down = ($imageHight/4)*3;

for($x = 0; $x < $imageWidth; $x++){
    for($y = $up; $y < $down; $y++){
        echo sprintf("%d ", imagecolorat($image, $x, $y));
    }
    echo '\n';
}

?>
