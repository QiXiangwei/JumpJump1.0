<?php

//小人的三原色
    define('ME_COLOR', 3750243);
    define('ME_COLOR_RED', (ME_COLOR >> 16) & 0xFF);
    define('ME_COLOR_GREEN', (ME_COLOR >> 8) & 0xFF);
    define('ME_COLOR_BLUE', (ME_COLOR) & 0xFF);

//小人的宽度
    define('ME_WIDTH', 45);

//睡眠时间
    define('SLEEP_TIME', 2.0);

//time = a * distance;a值
    define('A_VALUE', 2.079999);

?>
