<?php defined('_JEXEC') or die;

class TwinsModelContacts extends JModelList
{	
     protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
                ->from($db->quoteName('#__contact'));

		return $query;  
	}

} 