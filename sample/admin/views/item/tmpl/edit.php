<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

$app = JFactory::getApplication();
$input = $app->input;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

// Create shortcut to parameters.
$params = $this->state->get('params');
$params = $params->toArray();

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'tag.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_sample&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate form-horizontal">
	<div class="row-fluid">
	<!-- Begin Content -->
		<div class="span10 form-horizontal">
			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_TAGS_FIELDSET_DETAILS', true)); ?>
					<fieldset class="adminform">
						<div class="control-group form-inline">
							<?php echo $this->form->getLabel('title'); ?> <?php echo $this->form->getInput('title'); ?> <?php echo $this->form->getLabel('catid'); ?> <?php echo $this->form->getInput('catid'); ?>
						</div>
						<div class="control-group form-inline">
							<?php echo $this->form->getLabel('file'); ?><?php echo $this->form->getInput('file'); ?>
						</div>
						<?php echo $this->form->getInput('description'); ?>
						
					</fieldset>
						
				<?php echo JHtml::_('bootstrap.endTab'); ?>

						
									</div>
				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>
		</div>

	</div>
</form>
