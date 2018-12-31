<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 30/12/18
 * Time: 17:32
 */

namespace App\Service;

class BuildSortService
{

    /**
     * @param $array
     * @param int $number
     * @return mixed
     */
    public function topSort($array, int $number)
    {
        usort($array,  function($a, $b)
        {
            return strcmp($b->getCountPos() - $b->getCountNeg(), $a->getCountPos() -  $a->getCountNeg());
        });

        for ($i = 0; $i < count($array) ; $i++) {
            if ($i === $number) { break; }
            $result[$i] = $array[$i];
        }

        return $result;
    }
}