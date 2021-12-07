<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$cookieLogin = $this->user->get('cookieLogin');
$app = JFactory::getApplication();

if (!empty($cookieLogin) || $this->user->get('guest'))
{
	// The user is not logged in or needs to provide a password.
	echo $this->loadTemplate('login');
}
else
{
    $app->redirect('index.php?option=com_engine&view=workorders&Itemid=214');
    exit;
}
