<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!isset($this->error))
{
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}

// Get language and direction
$doc             = JFactory::getDocument();
$app             = JFactory::getApplication();
$this->language  = $doc->language;
$this->direction = $doc->direction;

$templatePath = $this->baseurl . '/templates/' . $this->template;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $this->error->getCode(); ?> - <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $templatePath; ?>/vendor/css/rtl/bootstrap.css" class="theme-settings-bootstrap-css">
    <link rel="stylesheet" href="<?php echo $templatePath; ?>/vendor/css/rtl/appwork.css" class="theme-settings-appwork-css">
    <link rel="stylesheet" href="<?php echo $templatePath; ?>/vendor/css/rtl/theme-corporate.css" class="theme-settings-theme-css">
    <link rel="stylesheet" href="<?php echo $templatePath; ?>/vendor/css/rtl/colors.css" class="theme-settings-colors-css">
    <link rel="stylesheet" href="<?php echo $templatePath; ?>/vendor/css/rtl/uikit.css">

    <script src="<?php echo $templatePath; ?>/vendor/js/material-ripple.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/js/layout-helpers.js"></script>

    <!-- Core scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Page -->
    <link rel="stylesheet" href="<?php echo $templatePath; ?>/css/error.css">

</head>
<body class="bg-primary">

    <div class="overflow-hidden">
        <div class="container d-flex align-items-stretch ui-mh-100vh p-0">
            <div class="row w-100">
                <div class="d-flex col-md justify-content-center align-items-center order-2 order-md-1 position-relative p-5">
                    <div class="error-bg-skew bg-white"></div>

                    <div class="text-md-left text-center">
                        <h1 class="display-2 font-weight-bolder mb-4">Whoops...</h1>
                        <div class="text-xlarge font-weight-light mb-5">We kunnen de pagina niet vinden<br> welke jij zoekt :(</div>
                        <button type="button" class="btn btn-primary" onclick="window.history.go(-1); return false;">←&nbsp; Terug</button>
                    </div>
                </div>

                <div class="d-flex col-md-5 justify-content-center align-items-center order-1 order-md-2 text-center text-white p-5">
                    <div>
                        <div class="error-code font-weight-bolder mb-2">404</div>
                        <div class="error-description font-weight-light">Niet gevonden</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Core scripts -->
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>

</body>
</html>
