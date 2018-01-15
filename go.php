<?php

require 'values.php';


$i = 1;
while($i){
    ob_start();
    system('adb shell screencap -p /sdcard/screen.png');
    system('adb pull /sdcard/screen.png');
    ob_end_clean();

    $image = imagecreatefrompng('screen.png');
    $imageWidth = imagesx($image);
    $imageHight = imagesy($image);

    $up = $imageHight/4;
    $down = ($imageHight/4)*3;

    $start_maxx = -1;
    $start_x = 0;
    $start_y = 0;

    for($imageH = $up; $imageH < $down; $imageH++){
        for($imageW = 0; $imageW < $imageWidth; $imageW++){

            if(isMe(imagecolorat($image, $imageW, $imageH))){
                $left = $imageW;
                $right = $left;
                while($right < $imageWidth && isMe(imagecolorat($image, $right, $imageH))){
                    $right++;
                }
                if(($right - $left) > $start_maxx){
                    $start_maxx = $right - $left;
                    $start_x = $left + ($start_maxx/2);
                    $start_y = $imageH;
                }
            }
        }
    }

    $end_x = 0;
    $end_y = 0;
    $sum = 0;
    $maxx = 0;
    $findFlag = false;
    for($imageH = $up; $imageH < $start_y; $imageH++){
        for($imageW = 0; $imageW < $imageWidth; $imageW++){

            $notEnd = imagecolorat($image, $imageWidth - 1, $imageH);
            if(isEnd(imagecolorat($image, $imageW, $imageH), $notEnd)){
                $left = $imageW;
                $right = $left;
                while($right + 1 < $imageWidth && isEnd(imagecolorat($image, $right+1, $imageH), $notEnd)){
                    $right++;
                }

                if(abs(($left + $right)/2 - $start_x) > ME_WIDTH*0.5){
                    if($right - $left > ME_WIDTH * 0.9){
                        if(!isset($mid)){
                            $mid = ($left + $right)/2;
                        }
                        if($right <= $maxx){
                            $sum++;
                            if($sum == 3){
                                $end_x = round($mid);
                                $end_y = $imageH;
                                $findFlag = true;
                                break;
                            }
                        }else{
                            $sum = 0;
                        }
                        $maxx = $right;
                    }
                }
                $imageW = $right;
            }
        }
        if($findFlag)break;
    }
    if(!$findFlag){
        $end_x = round($mid);
        $end_y = $start_y - round(abs($mid-$start_x)/sqrt(3));
    }

    imagefilledellipse($image, $start_x, $start_y, 10, 10, 0xFF0000);
    imagefilledellipse($image, $end_x, $end_y, 10, 10, 0xFF0000);
    imagepng($image, sprintf(dirname(__FILE__)."/screen/%05d.png", $i));

    $xx = abs($end_x - $start_x) * abs($end_x - $start_x);
    $yy = abs($end_y - $start_y) * abs($end_y - $start_y);
    $distance = sqrt($xx + $yy);
    $time = round(A_VALUE * $distance);

    echo sprintf("%d: (%d, %d), (%d, %d), distance: %lf, time: %lf\n", $i, $start_x, $start_y, $end_x, $end_y, $distance, $time);
    $x1 = rand(300, 500);
    $y1 = rand(300, 700);
    $x2 = $x1 + rand(-5, 5);
    $y2 = $y1 + rand(-5, 5);
    $swipe = sprintf("%s %s %s %s", $x1, $y1, $x2, $y2);
    system('adb shell input swipe ' . $swipe. ' ' . $time);

    $i++;
if($i == 2){break;}
    sleep(SLEEP_TIME);

}


function isEnd($pointOne, $pointTwo){

    $diff = 5;

    $redOne = ($pointOne >> 16) & 0xFF;
    $greenOne = ($pointOne >> 8) & 0xFF;
    $blueOne = $pointOne & 0xFF;

    $redTwo = ($pointTwo >> 16) & 0xFF;
    $greenTwo = ($pointTwo >> 8) & 0xFF;
    $blueTwo = $pointTwo & 0xFF;

    if(abs($redOne - $redTwo) < $diff
    && abs($greenOne - $greenTwo) < $diff
    && abs($blueOne - $blueTwo) < $diff ){
        return false;
    }else{
        return true;
    }

}

function isMe($pointOne){

    $diff = 20;

    $redOne = ($pointOne >> 16) & 0xFF;
    $greenOne = ($pointOne >> 8) & 0xFF;
    $blueOne = $pointOne & 0xFF;

    if(abs($redOne - ME_COLOR_RED) < $diff
    && abs($greenOne - ME_COLOR_GREEN) < $diff
    && abs($blueOne - ME_COLOR_BLUE) < $diff){
      return true;
    }else{
      return false;
    }
}

?>
