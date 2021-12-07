<?php defined('_JEXEC') or die;

#	FRAMEWORK
$app = JFactory::getApplication();
$doc = JFactory::getDocument();

#	DEFINES
define('COMPONENT','twins');
define('DS','/');

#	INCLUDES
jimport('joomla.application.component.controllerlegacy');

if (!JFactory::getUser()->authorise('core.manage', 'com_' . COMPONENT)) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$controller = JControllerLegacy::getInstance(COMPONENT);
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
