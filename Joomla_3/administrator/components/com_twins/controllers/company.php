<?php defined('_JEXEC') or die;

class TwinsControllerCompany extends JControllerForm
{
	protected $view_list = "companies";
	
	public function getModel($name = 'Company', $prefix = 'TwinsModel', $config=array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

}