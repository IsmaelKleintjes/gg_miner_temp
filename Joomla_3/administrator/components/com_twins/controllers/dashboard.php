<?php defined('_JEXEC') or die;

class TwinsControllerDashboard extends JControllerForm
{
	protected $view_list = "dashboard";
	
	public function getModel($name = 'Dashboard', $prefix = 'TwinsModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

}