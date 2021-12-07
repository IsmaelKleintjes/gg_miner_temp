<?php defined('_JEXEC') or die;

class TwinsControllerContact extends JControllerForm
{
	protected $view_list = "contacts";
	
	public function getModel($name = 'Contact', $prefix = 'TwinsModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

}