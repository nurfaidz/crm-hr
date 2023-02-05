<?php

namespace App\Repositories;

use App\Interfaces\ConfigInterface;
use App\Models\Config;
use App\Models\Overtime;
use Carbon\Carbon;
use Exception;

/**
    * Get overtime qouta per weeks in hours
    * @return integer
**/

class ConfigRepository implements ConfigInterface{
    public function overtimeQoutas(){
        try{
            $result = Config::where('key', 'overtimeWeekQouta')->first('value');
            return (int) $result->value;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function overtimeMinHours()
    {
        try {
            $result = Config::where('key', 'overtimeTodayMinimal')->first('value');
            return (int) $result->value;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function overtimeMaxHours()
    {
        try {
            $result = Config::where('key', 'overtimeTodayMaximal')->first('value');
            return (int) $result->value;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}