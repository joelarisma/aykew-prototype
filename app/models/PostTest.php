<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;

class PostTest extends Eloquent {

	protected $table = 'posttest';
	public $timestamps = false;

    public function level()
    {
        return $this->hasOne('Level','id','level_id');
    }

    public function questions()
    {
        return $this->hasMany('PostTestQuestion', 'test_id', 'id');
    }
    
    /**
     * Regenerate word count for content.  If parameter is true, do ALL
     * rows.  This can only be called manually be flipping the param to true
     * in code.  Otherwise, if the parameter is false, then only rows with
     * word count of 0 will be regenerated.
     * 
     * @param boolean $allContent Regenerate all rows, or just zero ones
     */
    public static function regenerateWords($allContent) {
        
        $allRows;
        
        // Get all posttest rows
        if ($allContent === true) {
            $allRows = PostTest::all();
        }
        
        // Only get zero rows
        else {
            $allRows = PostTest::where("words", "=", 0)->get();
        }
        
        // For all rows
        foreach ($allRows as $row) {
            
            // Use PHP lib funcs to get word count
            $content = $row->description;
            $wordCount = str_word_count(strip_tags($content));
            $row->words = $wordCount;
            $row->save();
        }
    }
}

