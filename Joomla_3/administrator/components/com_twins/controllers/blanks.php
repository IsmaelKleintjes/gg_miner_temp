<?php defined('_JEXEC') or die;

class TwinsControllerBlanks extends JControllerAdmin
{
	public $view_list = "blanks";
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Blank', $prefix = 'TwinsModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}