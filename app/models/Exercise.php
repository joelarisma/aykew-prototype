<?php 

/**
 * This file contains the Laravel model for the exercises table
 * 
 * PHP Version 5.5
 *
 * @category PRO_INFINITEMIND
 * @package  PRO_INFINITEMIND
 * @author   Digital Trike <webmaster@digitaltrike.com>
 * @license  Digital Trike MSA
 * @link     http://www.digitaltrike.com
 */

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * This is the Laravel model for the exercises database table
 *
 * @category Exercise
 * @package  PRO_INFINITEMIND
 * @author   Digital Trike <webmaster@digitaltrike.com>
 * @license  Digital Trike MSA
 * @link     http://www.digitaltrike.com
 */

class Exercise extends Eloquent
{

    protected $table = 'exercise';
    public $timestamps = false;

    // This model is probably not the best place for these, but matches where they were in the old Zend framework

    /**
     * Get WPM calculations for exercises based on current avg WPM
     *
     * @param int $avgwpm user average words per minute
     *
     * @return array
     */
    private static function _getTestWpm($avgwpm)
    {
        /**
        * REPORTING-2-08
        **/
        $arr = [];
        // set wpm speed for text exercises - round to nearest tens digit
        $arr['slow_wpm'] = (int) round($avgwpm * 1.1, -1);
        $arr['medium_wpm'] = (int) round($avgwpm * 2, -1);
        $arr['fast_wpm'] = (int) round($avgwpm * 3, -1);
        $arr['superfast_wpm'] = (int) round($avgwpm * 4, -1);
        // Determine words needed for 15 seconds at each speed (1.1*avg + 2*avg + 3*avg + 3*avg + 4*avg)
        // (this works out to 3.275 * avgwpm)
        $wordsNeeded = (int) round($avgwpm * 3.275);
        $wordsNeeded += 100;    // add 100 more to have extras
        $arr['words'] = $wordsNeeded;
        return $arr;
    }

    /**
     * Get all the exercise data (speeds, instructions) for a given exercise
     *
     * @param int $id     exercise db primary key
     * @param int $avgwpm user average words per minute
     *
     * @return array
     */
    private static function _getExerciseArray($id, $avgwpm)
    {
        /**
        * REPORTING-2-07
        **/
        $row = Exercise::find($id);
        $arrExercise = [];
        $arrExercise['id'] = $row->id;
        $arrExercise['name'] = $row->name;
        $arrExercise['identifier'] = $row->identifier;
        if ($id > 20) {
            // set timer interval for image exercises
            // Fast is fixed, Slow is between slow_min & slow_max (based
            // on avgwpm), Medium is between Fast & Slow
            $fast = (int) $row->fast;
            $slowMin = $row->slow_min;
            $slowMax = $row->slow_max;
            $pct = $avgwpm/1000;  // value should be from .1 to 1
            $diff = $slowMin-$slowMax; // larger interval values are slower, so Min should be larger than Max
            if ($diff > 0) {
                $slow = abs((int) ($slowMin-($diff*$pct)));
            } else {
                $slow = $slowMin;
            }

            /*
                $medium = (int) (($slow+$fast)/2);
                $speeds[] = $slow;
                $speeds[] = $fast;
                $speeds[] = $medium;
            */
            
            // new equations Jan 2016:
            // slow (between slow_min and slow_max) is the base speed
            // and slow, medium and fast are 1.1, 3, and 2 times faster
            // than the base respectively
            $base = $slow;
            $speeds[] = (int) $base/1.1;  // slow
            $speeds[] = (int) $base/3;    // fast
            $speeds[] = (int) $base/2;    // medium
            $arrExercise['speeds'] = $speeds;
        }

        $arrExercise['instructions'] = $row->instructions;
        return $arrExercise;
    }

    /**
     * Get exercise session mapping (legacy function, not currently used)
     *
     * @param int $avgwpm   user average words per minute
     * @param int $seriesId series ID from db
     *
     * @return array
     */
    public static function getSessionMapping($avgwpm, $seriesId)
    {
        $series = ExerciseSeries::find($seriesId);
        // limit exercise base range to 100-2000 wpm
        if ($avgwpm < 100) {
            $avgwpm = 100;
        }
        
        if ($avgwpm > 1000) {
            $avgwpm = 1000;
        }

        $arrResult = Exercise::_getTestWpm($avgwpm);

        for ($i=1; $i<9; $i++) {
            $ex = "ex$i";
            //return $series->$ex;
            $arrResult['exercise'][] = Exercise::_getExerciseArray($series->$ex, $avgwpm);
        }

        return $arrResult;
    }

    /**
     * Get the exercise and speed data for a given exercise
     *
     * @param int $exId   exercise identifer
     * @param int $avgwpm user average words per minute for calcuations
     *
     * @return array
     */
    public static function getOneExercise($exId, $avgwpm)
    {
        $arrResult['exercise'] = Exercise::_getExerciseArray($exId, $avgwpm);
        $arrResult['wpmspeeds'] = Exercise::_getTestWpm($avgwpm);
        return $arrResult;
    }

    /**
     * Find the next id for a new exercise (probably not used, legacy function)
     *
     * @return array
     */
    public function generateIdentifier()
    {
        $exerciseID = array();
        $maxID=DB::table('exercise')->max('id')+1;
        $exerciseID['id']= $maxID;
        $exerciseID['identifier'] = 'ex'.$maxID;
        return $exerciseID;
    }

    /**
     * Model rules
     *
     * @param string $action array key
     *
     * @return array
     */
    public static function rules($action)
    {
        $rules = [
            'add' => [
                'name'                   => 'required',
                'instructions'           => 'required',
            ],
        ];

        return $rules[$action];
    }

}