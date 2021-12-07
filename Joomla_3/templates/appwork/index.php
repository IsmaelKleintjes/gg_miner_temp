<?php
/**
 * WEBSITE TEMPLATE
 * CREATED BY EDIT4U WEBSERVICES B.V.
**/

defined('_JEXEC') or die;

$scriptVersion = "?v=".mktime();

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
//$user = User4U::getActive();
$user->firstname = 'Ismael';
$this->language  = $doc->language;
$this->direction = $doc->direction;
$menu = $app->getMenu();
$view = $app->input->get('view');

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
$doc->addStyleSheet($templatePath . '/vendor/css/theme-corporate.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/css/colors.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/css/uikit.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/fonts/linearicons.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/libs/timepicker/timepicker.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/libs/perfect-scrollbar/perfect-scrollbar.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/libs/select2/select2.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/vendor/libs/sweetalert2/sweetalert2.css' . $scriptVersion);
//$doc->addStyleSheet($templatePath . '/css/chosen.css' . $scriptVersion);
$doc->addStyleSheet($templatePath . '/css/template.css' . $scriptVersion);

// Add scripts
$doc->addScript(JUri::base() . 'media/system/js/core.js' . $scriptVersion);

$hiddenTopBar = ['workorder', 'product', 'category', 'location', 'invoice', 'vehicle', 'user', 'client', 'profile'];

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" class="default-style layout-fixed layout-navbar-fixed" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

    <!-- TESSTTT -->

    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <link rel="manifest" href="manifest.webmanifest">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script>
        var base = '<?php echo JUri::base(); ?>';
    </script>
    <jdoc:include type="head" />

</head>

<body>

    <div class="fullpage-loader">
        <div class="fullpage-loader-content">
            <img src="<?php echo JURI::base(); ?>images/pageloader.png">
        </div>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <!-- Layout sidenav -->
            <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-primary-darker sidenav-dark">

                <!-- Brand demo (see assets/css/demo/demo.css) -->
                <div class="app-brand demo">
                    <a href="<?php echo JURI::base(); ?>" class="sidenav-text font-weight-normal ml-2">
                        <img src="<?php echo JURI::base(); ?>images/BS-logo-platform.svg" class="logo logo-light img-fluid">
                    </a>
                </div>

                <div class="sidenav-divider mt-0"></div>

                <div class="row d-block d-lg-none">
                    <div class="col-12 px-4">
                        <jdoc:include type="modules" name="navbar-left" />
                    </div>
                </div>

                <jdoc:include type="modules" name="mainmenu" />

            </div>
            <!-- / Layout sidenav -->

            <!-- Layout container -->
            <div class="layout-container">
                <!-- Layout navbar -->
                <nav <?php if(in_array($view, $hiddenTopBar)): ?>style="display:none;"<?php endif; ?> class="layout-navbar navbar navbar-expand-lg align-items-lg-center container-p-x bg-navbar-theme" id="layout-navbar">

                    <!-- Sidenav toggle (see assets/css/demo/demo.css) -->
                    <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
                        <a class="nav-item nav-link px-0 mr-lg-4" href="javascript:void(0)">
                            <i class="ion ion-md-menu text-large align-middle"></i>
                        </a>
                    </div>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="navbar-collapse collapse" id="layout-navbar-collapse">
                        <!-- Divider -->
                        <hr class="d-lg-none w-100 my-2">

                        <div class="navbar-nav align-items-lg-center w-100">
                            <jdoc:include type="modules" name="navbar-left" />
                            <div class="demo-navbar-user nav-item dropdown ml-auto">
                                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                    <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                                        <?php if(!empty($user->photo) && file_exists(JPATH_ROOT . $user->photo)): ?>
                                            <img class="d-block ui-w-30" src="<?php echo JUri::base(); ?><?php echo $user->photo; ?>">
                                        <?php else: ?>
                                            <img class="d-block ui-w-30" src="<?php echo JUri::base(); ?>/images/placeholder-person-round.png">
                                        <?php endif; ?>
                                        <span class="px-1 mr-lg-2 ml-2 ml-lg-0"><?php echo $user->firstname; ?></span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <jdoc:include type="modules" name="usermenu" />
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- / Layout navbar -->

                <!-- Layout content -->
                <div class="layout-content">

                    <!-- Content -->
                    <div class="container-fluid flex-grow-1 container-p-y">

                        <jdoc:include type="modules" name="component-top" />
                        <jdoc:include type="message" />
                        <jdoc:include type="component" />
                        <jdoc:include type="modules" name="component-bottom" />

                    </div>
                    <!-- / Content -->

                    <!-- Layout footer -->
                    <?Php /*
                    <nav class="layout-footer footer bg-footer-theme">
                        <div class="container-fluid d-flex flex-wrap justify-content-between text-center container-p-x pb-3">
                            <div class="pt-3">
                                © <span class="footer-text font-weight-bolder">Berg & Steegenaar</span> • <?php echo $user->groupName; ?>
                            </div>
                            <div>
                                <a href="https://www.BergEnSteegenaar.nl" target="_blank" class="footer-link pt-3 ml-4">BergenSteegenaar.nl</a>
                            </div>
                        </div>
                    </nav> */ ?>
                    <!-- / Layout footer -->

                </div>
                <!-- Layout content -->

            </div>
            <!-- / Layout container -->

        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <script src="<?php echo $templatePath; ?>/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/moment/moment.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/js/bootstrap.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/js/layout-helpers.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/js/sidenav.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/spin/spin.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/validate/validate.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/bootbox/bootbox.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/dropzone/dropzone.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/timepicker/timepicker.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.nl.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/select2/select2.js"></script>
    <script src="<?php echo $templatePath; ?>/vendor/libs/sweetalert2/sweetalert2.js"></script>

    <script src="<?php echo $templatePath; ?>/js/main.js"></script>
    <script src="<?php echo $templatePath; ?>/js/jquery.validate.min.js"></script>
    <script src="<?php echo $templatePath; ?>/js/localization/messages_<?php echo substr($this->language, 0, 2); ?>.js"></script>
    <!--<script src="<?php echo $templatePath; ?>/js/chosen.jquery.min.js" type="text/javascript"></script>-->

    <script src="<?php echo $templatePath; ?>/js/functions.js<?php echo $scriptVersion; ?>"></script>
</body>
</html>
