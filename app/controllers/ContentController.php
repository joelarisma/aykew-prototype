<?php

/**
 * PHP Version 5.5
 *
 * @category PRO_INFINITEMIND
 * @package  PRO_INFINITEMIND
 * @author   Digital Trike <webmaster@digitaltrike.com>
 * @license  Digital Trike MSA
 * @link     http://eyeqadvantage.com
 **/

/**
 * ContentController serves up public exercise content and images
 *
 * @category ContentController
 * @package  PRO_INFINITEMIND
 * @author   Digital Trike <webmaster@digitaltrike.com>
 * @license  Digital Trike MSA
 * @link     http://eyeqadvantage.com
 */

class ContentController extends \BaseController
{
    /**
     * Get a random exercise text with a minimum number of words
     *
     * @return string
     */
    public function exerciseText()
    {
        // Regenerate any zero-word contents (true would be all rows)
        PostTest::regenerateWords(false);
        
        // Get user reading level
        $readingLevel = 4; //Auth::user()->level_id;
        
        // Get exercise's need words
        $wordsNeeded = Input::get('words', 3400); // default 3400

        // Get exercise that fulfills full request
        $eText = PostTest::where('type', '=', 'Exercise')
            ->where('words', '>=', $wordsNeeded)
            ->where('status', '=', 1)
            ->where("level_id", "=", $readingLevel)
            ->get()
            ->random(1);
        
        // Failed by reading level - get random of lesser reading levels
        if (!$eText) {
            $eText = PostTest::where('type', '=', 'Exercise')
                ->where('words', '>=', $wordsNeeded)
                ->where('status', '=', 1)
                ->where("level_id", "<", $readingLevel)
                ->get()
                ->random(1);
        }
        
        // Failed by words need - largest Exercise content regardless of reading level
        if (!$eText) {
            $eText = PostTest::where('type', '=', 'Exercise')
                ->where('status', '=', 1)
                ->orderBy('words', 'DESC')
                ->first();
        }

        // Return text
        return $eText->description;
    }

    /**
     * Get html string of a set of random exercise images
     *
     * @return string
     */
    public function exerciseImages()
    {
        $imagesNeeded = Input::get('images');
        // $getIds = Input::get('ids');
        $ids = !empty(Input::get('ids'));
        // $ids = !empty($getIds);
        // $ids = (Input::get('ids') == true);
        $images = ExerciseImage::where('status', '=', 1)->get()->random($imagesNeeded);
        $html = '';
        $i = 0;
        foreach ($images as $image) {
            $img = $image->filename;
            $html .= "<img src='/images/exercises/$img'";
            if ($ids) {
                $html .= "id='image_$i'";
            }
            $i++;
            $html .= '/>';
        }
        return $html;
    }

    /**
     * Get a random set of exercise images
     *
     * @return json Response
     */
    public function exerciseImageList()
    {
        $imagesNeeded = Input::get('images');
        $images = ExerciseImage::where('status', '=', 1)->get()->random($imagesNeeded);
        foreach ($images as $image) {
            $image_array[] = $image->filename;
        }
        $data['images'] = $image_array;
        $data['path'] = '/images/exercises/';
        return Response::json($data);
    }
    
}