<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

$app = JFactory::getApplication();
$itemId = $app->input->set('tmpl', 'login');
?>
<div class="reset-confirm<?php echo $this->pageclass_sfx; ?>">
    <div class="card">
        <div class="p-4 p-sm-5">
            <?php if ($this->params->get('show_page_heading')) : ?>
                <div class="d-flex justify-content-center align-items-center pb-2 mb-4">
                    <h2>
                        <?php echo $this->escape($this->params->get('page_heading')); ?>
                    </h2>
                </div>
            <?php endif; ?>
            <form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.confirm'); ?>" method="post" class="form-validate form-horizontal well">
                <?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
                    <fieldset>
                        <?php if (isset($fieldset->label)) : ?>
                            <p><?php echo JText::_($fieldset->label); ?></p>
                        <?php endif; ?>

                        <?php
                        $fields = $this->form->getFieldset( $fieldset->name );

                        foreach($fields as $field): ?>
                            <div class="form-group">
                                <label class="form-label d-flex justify-content-between align-items-end">
                                    <?php echo $field->label; ?>
                                </label>
                                <?php $this->form->setFieldAttribute($field->getAttribute('name'),'class','form-control'); ?>
                                <?php echo $this->form->getInput($field->getAttribute('name')); ?>
                            </div>

                        <?php endforeach; ?>
                    </fieldset>
                <?php endforeach; ?>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary validate">
                            <?php echo JText::_('JSUBMIT'); ?>
                        </button>
                    </div>
                </div>
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
    </div>
</div>
