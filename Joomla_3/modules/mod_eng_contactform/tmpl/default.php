<?php defined('_JEXEC') or die; ?>

<div class="row">
    <div class="col-md-12">
        <form action="<?php echo JUri::base(); ?>index.php?option=com_engine" method="post" name="adminForm" id="form-validate" class="form-validate form-contact contact-form">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <?php $form->setFieldAttribute('name', 'label', 'Naam');
                        echo $form->getLabel('name'); ?>
                        <?php echo $form->getInput('name'); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?php $form->setFieldAttribute('email', 'label', 'Email');
                        echo $form->getLabel('email'); ?>
                        <?php echo $form->getInput('email'); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?php $form->setFieldAttribute('phone', 'label', 'Telefoon');
                        echo $form->getLabel('phone'); ?>
                        <?php echo $form->getInput('phone'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php $form->setFieldAttribute('message', 'label', 'Bericht');

                        echo $form->getLabel('message'); ?>

                        <?php echo $form->getInput('message'); ?>
                    </div>
                </div>
            </div>

            <button class="btn btn-success"><?php echo JText::_('Versturen'); ?></button>
            <div>
                <input type="hidden" name="task" id="task" value="contactform.save">
                <?php echo $form->getInput('id'); ?>
                <?php echo $form->renderField('captcha'); ?>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </div>
</div>
