<?php defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$form = modEngineLeadHelper::getForm();

require JModuleHelper::getLayoutPath('mod_eng_lead', $params->get('layout', 'default'));
