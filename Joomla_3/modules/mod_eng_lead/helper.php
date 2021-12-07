<?php defined( '_JEXEC' ) or die( 'Restricted access' );

class modEngineLeadHelper
{
    public static function getForm()
    {
        $leadForm = new JForm('Intake', array('control' => 'jform'));
        $leadForm->loadFile(JPATH_SITE . '/components/com_engine/models/forms/lead.xml');

        $session = JFactory::getSession();
        $formData = $session->get('leadformdatamain');

        if($formData){
            foreach($formData as $fieldName => $data){
                $leadForm->setValue($fieldName, null, $data);
            }
        }

        return $leadForm;
    }
}