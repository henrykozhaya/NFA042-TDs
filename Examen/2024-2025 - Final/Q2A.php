<?php
function checkDir($path){ 
    if(!is_dir($path)){ // 0.5 point
        mkdir($path, 0777, true); // 0.5 point - recursive doit être True
    }
}