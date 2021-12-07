<?php
/**
 * WEBSITE TEMPLATE
 * CREATED BY EDIT4U WEBSERVICES B.V.
**/

defined('_JEXEC') or die;

$scriptVersion = "?v=17919";

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$user = JFactory::getUser();
$this->language  = $doc->language;
$this->direction = $doc->direction;
$menu = $app->getMenu();

// Getting params from template
$params = $app->getTemplate(true)->params;

// Load Joomla scripts
$this->_script	 = array();
$this->_scripts	 = array();

$templatePath = $this->baseurl . '/templates/' . $this->template;

// Add Stylesheets
$doc->addStyleSheet($templatePath . '/vendor/fonts/ionicons.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/css/bootstrap.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/css/appwork.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/css/theme-twitlight.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/css/colors.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/css/uikit.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/libs/perfect-scrollbar/perfect-scrollbar.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/css/pages/authentication.css' . $scriptVersion);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" class="default-style" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <link rel="manifest" href="manifest.webmanifest">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        .authentication-wrapper .card { border-radius: 10px; }
    </style>

    <jdoc:include type="head" />

</head>

<body>

    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>

    <!-- Content -->

    <div class="authentication-wrapper authentication-2 ui-bg-cover ui-bg-overlay-container px-4"
         style="background-image: url('<?php echo JURI::base(); ?>/images/bg_login.jpg');">
        <div class="ui-bg-overlay bg-dark opacity-25"></div>

        <div class="authentication-inner py-5">

            <jdoc:include type="message" />
            <jdoc:include type="component" />

        </div>
    </div>

    <!-- / Content -->

    <!-- Core scripts -->
    <script src="<?php echo $templatePath; ?>/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/js/bootstrap.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/js/sidenav.js"></script>

    <!-- Libs -->
    <script src="<?php echo $templatePath; ?>/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0PE1ZTHYZH"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-0PE1ZTHYZH');
    </script>


</body>
</html>
