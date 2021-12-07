<?php defined('_JEXEC') or die('Restricted access');

class TwinsModelCompany extends JModelAdmin
{
    public function getTable($type = 'Company', $prefix = 'TwinsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.company.data', array());

        if(empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getForm($data = array(), $loadData = true)
    {
        return $this->loadForm('com_' . COMPONENT . '.company', 'company', array('control' => 'jform', 'load_data' => $loadData));
    }

    public function save($data)
    {
        
        if(!parent::save($data)){
            return false;
        }

        $blankId = $this->getState('company.id');

        return $blankId;
    }
}
