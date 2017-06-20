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

class UserSessionHandler_ {

	//user who took the activity
	public $user;

	/**
	* session var - stores the current activity of the user
	* this will determine if the user is still on the same
	* activity or level, or the user is going to proceed on
	* to the next level
	**/
	private $user_sessions;

	private $session;

	private $exercises;

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
	**/
	public function startSession($session_level)
	{
		$this->session = $this->getUserSession();

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
		$this->session = $this->getUserSession();

		//improve this method
		if($this->hasCompleted())
			$this->session = $this->getNextUserSession(); //improve this method

		$this->user_sessions = $this->getUserSessions(3);

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
				'session_exercise_id' 		=> 1002,
				'session_exercise_type_id'	=> 8,
				'user_id'					=> 1,
				'session_id'				=> 3,
				'exercise_id'				=> null
			]);
	}

	/**
	* Get user's current sessions by level
	* 
	* @return CourseSession
	*
	**/
	public function getUserSessions(/*Course $course*/$id)
	{			
		$level_id = $this->session ? $this->session->session_level_id : 1;

		return CourseSession::where('course_id', $id)
					->where('session_level_id', $level_id)
					->orderBy('session')
					->get();
	}

	/**
	* Get User's session
	* 
	* @return CourseSession
	*
	**/
	public function getUserSession()
	{
		$session_report = $this->getLastUserActivity();

		//change hardcoded numbers
		if(!$session_report)
				$session = CourseSession::where('session', 0)
								->where('course_id', 3)
								->first();
		else 
			$session = $session_report->courseSession;

		return $session;
	}

	//improve this method
	public function getNextUserSession($last_activity = null)
	{
		/*$last_activity = is_null($last_activity) 
							? $this->getLastUserActivity()
							: $last_activity;*/

		$next_session_no = $this->session->session + 1;
		return CourseSession::where('session', $next_session_no)
							->where('course_id', 3)
							->first();
	}


	public function hasCompleted($last_activity = null)
	{
		$last_activity = is_null($last_activity) 
							? $this->getLastUserActivity()
							: $last_activity;

		$exercises = $this->getSessionExercises($last_activity->session_id);		

		$length = count($exercises);
		if($last_activity->session_exercise_id == $exercises[$length-1]->id)
			return true;

		return false;
	}

	//improve this method
	public function getUserActivities($session_id = null)
	{	
		if(is_null($session_id))
			return 'session id required';

		return SessionReport::with(['courseSession'])
					->where('user_id', $this->user->id)
					->where('session_id', $session_id)
					->orderBy('updated_at', 'DESC')
					->orderBy('created_at', 'DESC')
					->get();
	}

	/**
	* Get last User's submitted exercise
	* 
	* @return SessionReport
	*
	**/
	public function getLastUserActivity() //improve this method
	{
		return SessionReport::with(['courseSession'])->where('user_id', $this->user->id)
					->orderBy('updated_at', 'DESC')
					->orderBy('created_at', 'DESC')
					->first();
	}

	/**
	* Get User's next Session Exercise
	* 
	* @return SessionExercise
	*
	**/
	public function getSessionExercise() //improve this method
	{
		$last_activity = $this->getLastUserActivity();

		if($last_activity)
			$session_id = $last_activity->session_id;
		else $session_id = $this->session->id;

		$exercises = $this->getSessionExercises($session_id);

		$sequence_no = 0;
		foreach ($exercises as $exercise) 
		{	
			if(!$last_activity) 
				break;

			$sequence_no++;
			if($exercise->id == $last_activity->session_exercise_id)
				break;
		}

		return isset($exercises[$sequence_no]) ? $exercises[$sequence_no] : false;
	}

	/**
	* Get User's Session Exercises
	* 
	* @return SessionExercise[]
	*
	**/
	public function getSessionExercises($session_id = null)
	{
		if(is_null($session_id))
			return 'session id required';

		$session = CourseSession::with(['exercises'])
						->find($session_id);

		$_exercises = [];

		foreach ($session->exercises as $exercise)
			$_exercises[] = $exercise;

		
		return $_exercises;
	}
}	