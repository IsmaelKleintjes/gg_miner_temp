<?php
// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$form = ModEngContactFormHelper::getForm($params);
require JModuleHelper::getLayoutPath('mod_eng_contactform');