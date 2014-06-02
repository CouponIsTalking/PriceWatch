<?php

class PythonInfComponent extends Component {
	
	public $components = array('Session');

	public function set_trigger_for_pending_jobs($val)
	{
		$command = "";
		$output = array();
		
		if (intval($val) == 1)
		{
			$command = PYTHON_SCRIPTS_FOLDER."trigger_pending_job_processing.py 1 > phpout_trigger_pending_job_processing.txt 2>&1 & echo $!";
		}
		else if (intval($val) == 0)
		{
			$command = PYTHON_SCRIPTS_FOLDER."trigger_pending_job_processing.py 0 > phpout_trigger_pending_job_processing.txt 2>&1 & echo $!";
		}
		
		if ($command != "")
		{
			//debug ($command);
			exec($command, $output);
		}
		
		return $output;
	}
	
}

?>