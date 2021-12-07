<?php defined('_JEXEC') or die;

class TwinsViewBlanks extends JViewLegacy
{
    public $overview;

    public function display($tpl = null)
    {/*
        // We don't need toolbar in the modal window.
        if ($this->getLayout() !== 'modal') {

            $this->state = $this->get("State");
            $this->addToolbar();
            $this->sidebar = JHtmlSidebar::render();
        }

        $overview = new JHtmlOverview($this);
        echo $overview->show();
     * 
     */
        
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
    {/*
        JToolbarHelper::title('Blanks');

        JToolbarHelper::addNew('blank.add');
        JToolbarHelper::editList('blank.edit');
        if ($this->state->get('filter.published') == -2){
            JToolbarHelper::deleteList('Weet u het zeker?', 'blanks.delete', 'JTOOLBAR_EMPTY_TRASH');
        } else {
            JToolbarHelper::trash('blanks.trash');
        }
        JToolbarHelper::publish('blanks.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('blanks.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolbarHelper::archiveList('blanks.archive');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter_published',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->get('State')->get('filter.published'), true)
        );*/
        JToolbarHelper::title(JText::_('Blanks'));
	JToolbarHelper::addNew('blank.add');
	JToolbarHelper::editList('blank.edit');
	JToolbarHelper::deleteList('', 'blanks.delete');
    }
}	