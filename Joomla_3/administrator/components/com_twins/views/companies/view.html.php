<?php defined('_JEXEC') or die;

class TwinsViewCompanies extends JViewLegacy
{
    public $overview;

    public function display($tpl = null)
    {
        // Get data from the model
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

                // Set the toolbar
		$this->addToolBar();
                
		// Display the template
		parent::display($tpl);
    }

    public function addToolbar()
    {
        JToolbarHelper::title(JText::_('Companies'));
	JToolbarHelper::addNew('company.add');
	JToolbarHelper::editList('company.edit');
	JToolbarHelper::deleteList('', 'companies.delete');
    }
}	