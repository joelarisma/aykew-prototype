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
 * @author   Joe Larisma III
 * 
 */

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
	protected $activity_types;

	//constant for eye muscle speed to read a single image
	const EMS = 5;

	//constant for number of words read equivalent to a single image scanned
	const FACTOR = 4;

	//constant minute in secs
	const SECS = 60;

	public function __construct() 
	{  
		$this->user = (object) ['id' => '1'];
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

	public function submitExercise($input = null)
	{
		if(!$input)
			return [];

		//evaluate exam type here
		//check user submitted results
		//save to database
		SessionReport::create([
				'session_exercise_id' 		=> 1004,
				'session_exercise_type_id'	=> 8,
				'user_id'					=> 1,
				'session_id'				=> 4,
				'exercise_id'				=> null
			]);

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
					->where('session_level_id', $level_id)
					->orderBy('session')
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
			if($session->reports->count() == $session->exercises->count())
				return $this->session_status[1];

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

			if($exercise->id == $session_report->session_exercise_id)
				break;
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

	public function getReadingExercise(SessionExercise $exercise)
	{
        $exclude = SessionReport::select('session_exercise_id')
        				->where('user_id', $this->user->id)
        				->where('session_exercise_type_id', $exercise->type->id)
        				->where('session_id', $this->session->id)
        				->get()->toArray();
        				
		$type = $exercise->type->type_code == 'post-test' ? 'Post-Reading' : 'Pre-Reading';

		return PostTest::where('type', '=', $type)
				->where('status', '=', 1)
				->whereNotIn('id', $exclude)
				->get()->random(1);
	}
}	