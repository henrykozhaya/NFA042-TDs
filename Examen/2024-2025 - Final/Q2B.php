<?php
function isDateWithinRange($startDate, $endDate, $date) {
    return strtotime($startDate) <= strtotime($date) 
            && strtotime($date) <= strtotime($endDate); // 1 point
}