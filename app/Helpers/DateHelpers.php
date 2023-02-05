<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;

class DateHelpers
{
    /**
     * 
     * Calculate end time - start time
     * Result on seconds value
     * 
     * @return int
     * 
     **/
    public static function secondsDifference($startTime, $endTime){
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $duration = $end->diffInSeconds($start);
        return (int)$duration;
    }

    /**
     * 
     * Calculate end time - start time
     * Result on seconds value
     * 
     * @return int
     * 
     **/
    public static function minutesDifference($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $duration = $end->diffInMinutes($start);
        return (int)$duration;
    }

    /**
     * 
     * Convert Minutes into Hours
     * 
     * @return object
     * 
     **/
    public static function minutesToHours($time){
        if($time >= 60){
            $h = (int)floor($time / 60);
            $m = $time % 60;
            return (object)[
                "h" => $h,
                "m" => $m
            ];
        }
        return (object)[
            "h" => 0,
            "m" => (int)$time
        ];
    }

    public static function workDaysShortener($arr){
        try{
            $size = count($arr);

            if($size === 1){
                return(object)[
                    "data" => $arr[0]->day_name
                ];
            }
            
            /**  
             * check jika hari lengkap 
            **/
            if($size === 7){
                return (object)[
                    "data" => "All Days"
                ];
            }

            /**  
             * check jika hari berurutan
             **/
            $arrDaysID = [];
            foreach($arr as $d){
                array_push($arrDaysID, $d->days_id);
            }
            $isDaysSortedAndUnique = self::isSortedAndUnique($arrDaysID);

            $data = "";
            if($isDaysSortedAndUnique){
                $firstDay = substr($arr[0]->day_name, 0, 3);
                $lastDay = substr($arr[$size-1]->day_name, 0, 3);

                return (object)[
                    "data" => "{$firstDay} - $lastDay"
                ];
            }
            
            for($i = 0; $i < $size; $i++){
                $day = substr($arr[$i]->day_name, 0, 3);
                $data .= "{$day}, ";
                
            }
            $data = substr($data, 0, strlen($data)-2);

            return (object)[
                "data" => $data
            ];
        }
        catch(Exception $ex){
            return $ex;
        }
    }

    public static function isSortedAndUnique($arr){
        $arrLength = count($arr) - 1;
        $t = 0;
        for($i = 0; $i <= $arrLength-1; $i++){
            if($arr[$i]+1 == $arr[$i+1]){
                $t++;
            }
        }
        return ($arrLength === $t ? true : false);
    }
}