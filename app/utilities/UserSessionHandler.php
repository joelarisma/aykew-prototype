<?php

/**
 * UserSessionHandler Class Service Provider
 * 
 * - This class will serve as a service layer for every user's activities.
 * - The user activities involves eye speed, comprehension, reading and typing.
 * - This service will serve only when user is currently in an activity.
 * - The current structure might change if the database schema is changed.
 * - This is based also from DynamicController@session flow, the logic is fine
 *	 however we might want it to break it down to smaller methods
 * 
 * @category Service
 * @package  EyeQ
 * @author   Zylun Phils, Inc.
 * 
 */

/**
* notes: 
* - create a hasQuestions function return bool true | false for post-test lvl 21 and comprehension test
* - need to clarify eye exercises speed calculations
* - need to clarify more detailed requirements, user has certain limit to take the exercises per day except super admin
* - need more information on section - i know at this point we are more concern of the system calculation accuracy
* - 
**/

class UserSessionHandler {

	//user who took the activity
	public $user;

	//sessions on a current level
	private $user_sessions;

	//user's current session
	private $session;

	//session no. identifier
	private $session_no;

	//current session's exercises
	private $exercises;

	//test id of the session exercise
	private $test;

	//user's current exercise
	private $exercise;

	//user's last entered report
	private $last_report;

	//user's activity metrics
	protected $user_metrics = [
			'wpm' 			=> 0, //word per minute
			'time_spent'	=> 0, //total time spent on an activity
			'percentage'	=> 0, //percentage rate
			'net' 			=> 0, //typing speed
			'ers' 			=> 0, //effective reading speed - comprehension
			'score'			=> 0,  //user's score - refers to eye_power column\
			'eye_power'		=> 0
		];

	//session status
	private $session_status = [
			1	=> 'done',
			2	=> 'new',
			3	=> 'continue',
			4	=> 'skipped',
		];

	//types of exercises or activities
	protected $exercise_types;

	//constant for eye muscle speed to read a single image
	const EMS = 5;

	//constant for number of words read equivalent to a single image scanned
	const FACTOR = 4;

	//constant minute in secs
	const SECS = 60;

	//set speed to default according to previous implementation
	private $speed = 400;

	public function __construct() 
	{  
		$this->user = (object) ['id' => '1'];
		$this->exercise_types = $this->getExerciseTypes();
	}


	/**
	* start session exercise
	*
	*
	* @return SessionExercise
	**/
	public function startSession($session_level)
	{	
		//sets the user's last entered report
		$this->setLastReport();

		$this->session_no = $session_level;
		$this->session = $this->getUserSession();

		//status needs to be new or continue
		//for the user to take the exercises
		$status = $this->getSessionStatus();
		if(in_array($status, ['done', 'skipped']))
			return true;

		return $this->exercise = $this->getSessionExercise();
	}

	/**
	* returns the current level of the user with corresponding session
	* 
	* @return array (user's current session and sessions within that level)
	*
	**/
	public function newSession()
	{
		$this->setLastReport();

		//check if there is an ongoing session
		//user will force to go back at the dashboard page
		$status = $this->getSessionStatus();
		if($status == 'continue')
			return $this->last_report->courseSession->session;


		$this->session = $this->getNewSession();
		$this->session_no = $this->session->session;
		$this->user_sessions = $this->getLevelSessions(3);

		return [
			'sessions' => $this->user_sessions,
			'current_session' => $this->session
		];
	}

	public function getExerciseTypes()
	{
		$types = SessionExerciseType::all();

		$_types = [];
		foreach ($types as $type) 
		{
			$_types[$type->type_code] = $type;	
		}

		return $_types;
	}

	/**
	* gets user's current session
	* 
	* @return CourseSession
	*
	**/
	public function getUserSession()
	{
		return CourseSession::where('course_id', 3)
					->where('session', $this->session_no)
					->first();
	}

	/**
	* if user has completed a session or user has started 
	* this will return the user's new session
	*
	* @return CourseSession
	*
	**/
	public function getNewSession()
	{
		if(!$this->last_report)
			return $this->getSessionZero();
		else
			return CourseSession::where('course_id', 3)
						->where('session', ($this->last_report->courseSession->session + 1))
						->first();
	}

	/**
	* returns the beginning of the session which is session 0
	*
	* @return CourseSession
	*
	**/
	public function getSessionZero()
	{
		return CourseSession::where('session', 0)
					->where('course_id', 3)
					->first();
	}

	/**
	* returns all sessions given the level
	*
	*
	* @param $course_id
	* @return CourseSession[]
	*
	**/
	public function getLevelSessions($course_id)
	{
		$level_id = $this->session ? $this->session->session_level_id : 1;

		return CourseSession::where('course_id', $course_id)
					//->where('session_level_id', $level_id)
					->orderBy('session', 'ASC')
					->get();
	}

	/**
	* checks the status of the session
	*
	* @return mix bool or integer
	*
	**/
	public function getSessionStatus()
	{
		if(!$this->last_report) {
			$_session = $this->getSessionZero();
			$session_id = $_session->id;
		} else {
			$session_id = $this->last_report->session_id;
			
			if(is_null($this->session_no))
				$this->session_no = $this->last_report->courseSession->session;
		}

		$session = CourseSession::with(['reports', 'exercises'])
						->find($session_id);

		$check_new_session = $this->session_no - $session->session;
		//force to skip session or trying to go back finished sessions
		if($check_new_session > 1 || $check_new_session < 0)
			return $this->session_status[4];

		//current session
		if($check_new_session == 0) {

			//check ongoing session
			if($session->reports->count() > 0 &&
				$session->reports->count() < $session->exercises->count())
					return $this->session_status[3];

			//check if current session is done
			if($session->reports->count() == $session->exercises->count()) {
				
				//check if exercise type is comprehension and hasn't answered the questions
				if( $session->reports[0]->type->type_code == 'comprehension' &&
					($session->reports[0]->ers == 0 || is_null($session->reports[0]->ers)) )
					return $this->session_status[3];

				return $this->session_status[1];
			}

			//newly engaged session
			if($session->reports->count() == 0 && !$this->last_report)
				return $this->session_status[2];
		} 

		//next session
		if($check_new_session == 1) {

			//check if previous session is done then it means
			//this is a new session
			if($session->reports->count() == $session->exercises->count())
				return $this->session_status[2];

			//check ongoing session
			if($session->reports->count() > 0 &&
				$session->reports->count() < $session->exercises->count())
					return $this->session_status[3];
		}

		return $session->session;
	}

	/**
	* sets the last_report property to the last activity of the user
	*
	* @return CourseSession[]
	*
	**/
	public function setLastReport()
	{
		$this->last_report = SessionReport::with(['courseSession'])
					->where('user_id', $this->user->id)
					->orderBy('updated_at', 'DESC')
					->orderBy('created_at', 'DESC')
					->first();
	}

	/**
	* gets a single exercise given the session no
	*
	* @return SessionExercise
	*
	**/
	public function getSessionExercise()
	{
		$session_report = SessionReport::whereHas('courseSession', function($q) {
								$q->where('session', $this->session_no);
							})
							->where('user_id', $this->user->id)
							->orderBy('updated_at', 'DESC')
							->orderBy('created_at', 'DESC')
							->first();

		$sequence_no = 0;
		$exercises = $this->getSessionExercises();
		foreach ($exercises as $exercise) 
		{
			if(!$session_report)
				break;

			$sequence_no++;

			if($exercise->id == $session_report->session_exercise_id) {

				//return to last exercise sequence if test is comprehension type and hasn't filled out the questions
				if( $session_report->type->type_code == 'comprehension' &&
					($session_report->ers == 0 || is_null($session_report->ers)) )
						$sequence_no--;

				break;
			}
		}

		return isset($exercises[$sequence_no]) ? $exercises[$sequence_no] : false;
	}

	/**
	* gets all exercises given the session no
	*
	* @return SessionExercise[]
	*
	**/
	public function getSessionExercises()
	{
		$session = CourseSession::with(['exercises'])
						->where('course_id', 3)
						->where('session', $this->session_no)
						->first();

		$_exercises = [];
		foreach ($session->exercises as $exercise)
			$_exercises[] = $exercise;
		
		return $_exercises;
	}

	/**
	* records the exercise results
	*
	* @param $input[] post variables, $session_no is the current session of the user
	*
	* @return void
	*
	**/
	public function submitExercise($input = null, $session_no)
	{
		if(!$input)
			return [];

		$do_update = false;
		$this->session_no = $session_no;
		$this->session = $this->getUserSession();

		$this->exercise = SessionExercise::find($input['session_exercise_id']);

		if(!$this->exercise)
			dd('something went wrong');
		
		switch($this->exercise->type->type_code)
		{
			case 'pre-test':
			case 'post-test':
				$this->test = PostTest::find($input['exercise_id']);
				$this->user_metrics['wpm'] = $this->getWordsPerMinute($input['wordcount'], $input['seconds']);
			break;
			case 'eye-speed':
				$this->user_metrics['eye_power'] = $this->getEyeMusclePower($input['seconds']);
			break;
			case 'comprehension':
				$this->test = PostTest::find($input['exercise_id']);
				if(!isset($input['score']) && !isset($input['is_questions']))
					$this->user_metrics['wpm'] = $this->getWordsPerMinute($input['wordcount'], $input['seconds']);
				else { 
					$updates = $this->getComprehensionResults($input['exercise_id'], $input['score'], $input['wpm']);
					$do_update = true;
				}
			break;
			case 'eye-exercise':
				$this->test = Exercise::find($input['exercise_id']);
			break;
		}

		if(!$this->test && $this->exercise->type->type_code != 'eye-speed')
			dd('double check');

		if(!$do_update)
			SessionReport::create([
					'session_exercise_id' 		=> $this->exercise->id,
					'session_exercise_type_id'	=> $this->exercise->session_exercise_type_id,
					'user_id'					=> $this->user->id,
					'session_id'				=> $this->exercise->session_id,
					'exercise_id'				=> ($this->test) ? $this->test->id : null,
					'wpm'						=> $this->user_metrics['wpm'],
					'ers'						=> $this->user_metrics['ers'],
					'net'						=> $this->user_metrics['net'],
					'time_spent'				=> $this->user_metrics['time_spent'],
					'percentage'				=> $this->user_metrics['percentage'],
					'eye_power'					=> $this->user_metrics['eye_power'],
				]);
		else
			SessionReport::where('session_id', $this->exercise->session_id)
					->where('user_id', $this->user->id)
					->where('session_exercise_type_id', $this->exercise->session_exercise_type_id)
					->where('session_exercise_id', $this->exercise->id)
					->update($updates);
	}

	/**
	* calculate the words per minte
	* 
	* @param (int) $wordcount number of words in a content, 
	* (float) $seconds number of second spent in reading
	* 
	* @return (float) words per minute
	*
	**/
	public function getWordsPerMinute($wordcount, $seconds)
	{
		//formula for words per minute
		return ($wordcount/$seconds) *  60;
	}

	/**
	* calculate percentage and ers
	* 
	* @param (int) $exercise_id, (int) $score, (int) $wpm
	* 
	* @return (array) ers and percentage
	*
	**/
	public function getComprehensionResults($exercise_id, $score, $wpm)
	{	
		$count = PostTestQuestion::where('test_id', $exercise_id)->get()->count();
		$percentage = ($score / $count) * 100;
		$ers = round($wpm * ($percentage/100));

		return [
			'ers'			=> $ers,
			'percentage' 	=> $percentage
		];
	}

	/**
	* calculate eye muscle power
	* 
	* @param (int) $seconds number of second spent in the exercise
	* 
	* @return (float) eye muscle power
	*
	**/
	public function getEyeMusclePower($seconds)
	{	
		//need to clarify these magic numbers
		return (22*5*10) / $seconds;
	}

	/**
	* randomly gets a single reading material
	*
	* @param SessionExercise - must be an object from SessionExercise model
	*
	* @return PostTest
	*
	**/
	public function getReadingExercise(SessionExercise $exercise)
	{
        $exclude = SessionReport::select('session_exercise_id')
        				->where('user_id', $this->user->id)
        				->where('session_exercise_type_id', $exercise->type->id)
        				->where('session_id', $this->session->id)
        				->get()->toArray();

		$type = $exercise->type->type_code == 'post-test' ? 'Post-Reading' : 'Pre-Reading';

		return PostTest::where('type', $type)
				->where('status', 1)
				->whereNotIn('id', $exclude)
				->get()->random(1);
	}

	/**
	* randomly gets a single reading material with questions
	*
	* @param SessionExercise - must be an object from SessionExercise model
	*
	* @return PostTest
	*
	**/
	public function getComprehensionExercise(SessionExercise $exercise)
	{
		$exclude = SessionReport::select('session_exercise_id')
        				->where('user_id', $this->user->id)
        				->where('session_exercise_type_id', $exercise->type->id)
        				->where('session_id', $this->session->id)
        				->get()->toArray();

        return PostTest::with(['questions'])
	        		->has('questions')
	        		->where('type', 'Comprehension')
					->where('status', 1)
					->whereNotIn('id', $exclude)
					->get()->random(1);
	}

	/**
	* generates the questions from the recent reading comprehension material
	*
	* @param SessionExercise - must be an object from SessionExercise model
	*
	* @return bool | array() PostTestQuestions, wpm, test_id
	*
	**/
	public function doComprehensionQuestions(SessionExercise $exercise)
	{
		$session_report = SessionReport::where('user_id', $this->user->id)
								->where('session_exercise_id', $exercise->id)
								->where('session_id', $this->session->id)
								->orderBy('updated_at', 'DESC')
								->orderBy('created_at', 'DESC')
								->first();

		if(!$session_report)
			return false;

		if($session_report->wpm == 0)
			return false;

		return [
			'questions' 	=> PostTestQuestion::where('test_id', $session_report->exercise_id)->get(),
			'wpm'			=> $session_report->wpm,
			'test_id'		=> $session_report->exercise_id
		];
	}

	//not stated in the current document requirement
	//the current logic will remain as it is
	public function getEyeExercises(SessionExercise $exercise)
	{
		$session_report = SessionReport::where('user_id', $this->user->id)
								->orderBy('updated_at', 'DESC')
								->orderBy('created_at', 'DESC');
		//image exercises
		if($exercise->exercise_id > 20) {
			$report = $session_report->where('session_exercise_type_id', $exercise->session_exercise_type_id)
											->first();

			if($report)
				$this->speed = ($report->eye_power /self::EMS) * self::SECS * self::FACTOR;
		} else { //text exercises

			$report = $session_report->where(function($q) {
								$q->where('session_exercise_type_id', $this->exercise_types['post-test']->id);
								$q->orWhere('session_exercise_type_id', $this->exercise_types['pre-test']->id);
								$q->orWhere('session_exercise_type_id', $this->exercise_types['comprehension']->id);
							})->first();

			if($report)
				$this->speed = $report->wpm;
		}

		return Exercise::getOneExercise($exercise->exercise_id, $this->speed);
	}
}	