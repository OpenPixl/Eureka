<?php

namespace App\Service;

class timeService
{
    public function Sems()
    {
        $now = new \DateTime('now') ;
        $now = strtotime($now->format('Y/m/d'));
        $rows = array();

        for($i = 0; $i<=35; $i++)
        {
            if(date('w',$now) == 1 ){
                $interval = new \DateInterval('P'.($i*7).'D');
                $monday = date_add(new \DateTime('now'), $interval);
                $friday = date_add(new \DateTime('now'), new \DateInterval('P'.(($i*7)+5).'D'));
                $row = array('monday' => $monday, 'friday' => $friday);
            }else{
                $interval = new \DateInterval('P'.($i*7).'D');
                $lastMonday = date('Y/m/d',strtotime('this week', $now));
                $monday = date_add(new \DateTime($lastMonday), $interval);
                $friday = date_add(new \DateTime($lastMonday), new \DateInterval('P'.(($i*7)+5).'D'));
                $row = array('monday' => $monday, 'friday' => $friday);
            }
            //dd($interval, $monday, $friday, $row);
            array_push($rows, $row);
        }

        return $rows;
    }
}