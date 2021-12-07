<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

$app = JFactory::getApplication();
$itemId = $app->input->set('tmpl', 'login');

?>


<div class="card">
    <div class="p-4 p-sm-5">
        <!-- Logo -->
        <div class="d-flex justify-content-center align-items-center">
            <img src="<?php echo JURI::base(); ?>images/logo-bs-logistiek.svg" class="img-fluid">
        </div>
        <!-- / Logo -->

	    <?php if ($this->params->get('logindescription_show') == 1) : ?>
            <h5 class="text-center text-muted font-weight-normal mb-4"><?php echo $this->params->get('login_description'); ?></h5>
	    <?php endif; ?>

        <!-- Form -->
        <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-validate">
            <fieldset>

                <div class="form-group">
                    <label class="form-label d-flex justify-content-between align-items-end">
                        <div>E-mailadres</div>
                    </label>
                    <?php /*<input type="text" class="form-control">*/ ?>
                    <?php $this->form->setFieldAttribute('username','class','form-control'); ?>
	                <?php echo $this->form->getInput('username'); ?>
                </div>
                <div class="form-group">
                    <label class="form-label d-flex justify-content-between align-items-end">
                        <div>Wachtwoord</div>
                        <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>" class="d-block small">
		                    <?php echo JText::_('COM_USERS_LOGIN_RESET'); ?>
                        </a>
                    </label>
	                <?php $this->form->setFieldAttribute('password','class','form-control'); ?>
	                <?php echo $this->form->getInput('password'); ?>
                </div>

	            <?php if ($this->tfa) : ?>
                    <div class="form-group">
			            <?php echo $this->form->getField('secretkey')->label; ?>
			            <?php echo $this->form->getField('secretkey')->input; ?>
                    </div>
	            <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center m-0">
	                <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                    <label class="custom-control custom-checkbox m-0">
                        <input id="remember" type="checkbox" name="remember" class="inputbox custom-control-input" value="yes" />
                        <span class="custom-control-label"><?php echo JText::_('COM_USERS_LOGIN_REMEMBER_ME'); ?></span>
                    </label>
	                <?php endif; ?>
                    <button type="submit" class="btn btn-primary">
		                <?php echo JText::_('JLOGIN'); ?>
                    </button>
                </div>

	            <?php $return = $this->form->getValue('return', '', $this->params->get('login_redirect_url', $this->params->get('login_redirect_menuitem'))); ?>
                <input type="hidden" name="return" value="<?php echo base64_encode($return); ?>" />
	            <?php echo JHtml::_('form.token'); ?>

            </fieldset>
        </form>
        <!-- / Form -->

    </div>

	<?php $usersConfig = JComponentHelper::getParams('com_users'); ?>
	<?php if ($usersConfig->get('allowUserRegistration')) : ?>
        <div class="card-footer py-3 px-4 px-sm-5">
            <div class="text-center text-muted">
                <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
                    <?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?>
                </a>
            </div>
        </div>
	<?php endif; ?>

</div>

<div class="card mt-4">
    <div class="p-4 p-sm-5">
        <h3>Berg en Steegenaar & Zn. Logistiek</h3>
        <p>Goudstraat 99<br>
            2718 RD Zoetermeer<br>
            </p>
        <p>Telefoonnummer: <a href="tel:0031653722124">0653722124</a><br>
            <a href="mailto:info@bergensteegenaar.nl">Stuur een e-mail</a>
        </p>
    </div>
</div>


<div class="card text-white bg-info my-4">
    <div class="p-4">
        <p class="m-0">Onze website wordt momenteel ontwikkeld.</p>
    </div>
</div>