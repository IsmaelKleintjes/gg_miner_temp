<?php defined('_JEXEC') or die;

class TwinsControllerBlank extends JControllerForm
{
	protected $view_list = "blanks";
	
	public function getModel($name = 'Blank', $prefix = 'TwinsModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

}