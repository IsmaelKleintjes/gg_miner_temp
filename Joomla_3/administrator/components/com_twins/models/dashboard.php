<?php defined('_JEXEC') or die('Restricted access');

class TwinsModelDashboard extends JModelAdmin
{
    public function getTable($type = 'Dashboard', $prefix = 'TwinsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_' . COMPONENT . '.edit.dashboard.data', array());

        if(empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getForm($data = array(), $loadData = true)
    {
        return $this->loadForm('com_' . COMPONENT . '.dashboard', 'dashboard', array('control' => 'jform', 'load_data' => $loadData));
    }
}
