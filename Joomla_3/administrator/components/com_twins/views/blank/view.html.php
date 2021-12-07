<?php defined('_JEXEC') or die('Restricted access');

class TwinsViewBlank extends JViewLegacy
{
    public function display($tpl = null)
    {
        $form = $this->get('Form');
        $item = $this->get('Item');

        $this->form = $form;
        $this->item = $item;

        /*$this->detail = new JHtmlDetail($this);*/
        
        $this->addToolBar();

        parent::display($tpl);
    }
    
    protected function addToolBar()
    {
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
        JToolbarHelper::title($isNew ? JText::_('Nieuw item')
            : JText::_('Wijzig item'));
        JToolbarHelper::apply('blank.apply');
        JToolbarHelper::save('blank.save');
        JToolbarHelper::save2new('blank.save2new');
        JToolbarHelper::cancel('blank.cancel');
    }
    /*
    protected function addToolBar()
	{
		$input = JFactory::getApplication()->input;

		// Hide Joomla Administrator Main menu
		$input->set('hidemainmenu', true);

		$isNew = ($this->item->id == 0);

		if ($isNew)
		{
			$title = JText::_('Nieuwe blank');
		}
		else
		{
			$title = JText::_('Blank edit');
		}

		JToolbarHelper::title($title, 'blank');
		JToolbarHelper::save('blank.save');
		JToolbarHelper::cancel(
			'blank.cancel',
			$isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
		);
	}*/

}