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

<form action="<?php echo JRoute::_('index.php?option=com_tags&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate form-horizontal">
	<div class="row-fluid">
	<!-- Begin Content -->
		<div class="span10 form-horizontal">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_TAGS_FIELDSET_DETAILS');?></a></li>
				<li><a href="#publishing" data-toggle="tab"><?php echo JText::_('COM_TAGS_FIELDSET_PUBLISHING');?></a></li>
				<li><a href="#options" data-toggle="tab"><?php echo JText::_('COM_TAGS_FIELDSET_OPTIONS');?></a></li>
				<li><a href="#metadata" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS');?></a></li>
				<?php if ($this->assoc) : ?>
					<li><a href="#associations" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS');?></a></li>
				<?php endif; ?>
			</ul>

			<div class="tab-content">
				<!-- Begin Tabs -->
				<div class="tab-pane active" id="general">
					<fieldset class="adminform">
						<div class="control-group form-inline">
							<?php echo $this->form->getLabel('title'); ?> <?php echo $this->form->getInput('title'); ?> <?php echo $this->form->getLabel('catid'); ?> <?php echo $this->form->getInput('catid'); ?>
						</div>
						<?php echo $this->form->getInput('description'); ?>
					</fieldset>
						<div class="row-fluid">
							<div class="span6">
								<h4><?php echo JText::_('COM_TAGS_FIELDSET_URLS_AND_IMAGES');?></h4>
								<div class="control-group">
									<?php echo $this->form->getLabel('images'); ?>
									<div class="controls">
										<?php echo $this->form->getInput('images'); ?>
									</div>
								</div>
								<?php foreach ($this->form->getGroup('images') as $field) : ?>
									<div class="control-group">
										<?php if (!$field->hidden) : ?>
											<?php echo $field->label; ?>
										<?php endif; ?>
										<div class="controls">
											<?php echo $field->input; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="span6">
								<?php foreach ($this->form->getGroup('urls') as $field) : ?>
									<div class="control-group">
										<?php if (!$field->hidden) : ?>
												<?php echo $field->label; ?>
										<?php endif; ?>
										<div class="controls">
											<?php echo $field->input; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

				</div>

				<div class="tab-pane" id="publishing">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('alias'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('alias'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('id'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('id'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('hits'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('hits'); ?>
						</div>
					</div>
					<?php if (intval($this->item->created_time)) : ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('created_time'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('created_time'); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if ($this->item->modified_user_id) : ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('modified_user_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('modified_user_id'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('modified_time'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('modified_time'); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="tab-pane" id="options">
					<fieldset>
						<?php echo $this->loadTemplate('options'); ?>
					</fieldset>
				</div>
				<div class="tab-pane" id="metadata">
					<fieldset>
						<?php echo $this->loadTemplate('metadata'); ?>
					</fieldset>
				</div>
				<?php if ($this->assoc) : ?>
					<div class="tab-pane" id="associations">
						<fieldset>
							<?php echo $this->loadTemplate('associations'); ?>
						</fieldset>
					</div>
				<?php endif; ?>
				<?php if ($this->canDo->get('core.admin')): ?>
					<div class="tab-pane" id="permissions">
						<fieldset>
							<?php echo $this->form->getInput('rules'); ?>
						</fieldset>
					</div>
				<?php endif; ?>
				<!-- End Tabs -->
			</div>
				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>
		</div>
		<!-- End Content -->
		<!-- Begin Sidebar -->
		<div class="span2">
			<h4><?php echo JText::_('JDETAILS');?></h4>
			<hr />
			<fieldset class="form-vertical">
				<div class="control-group">
					<div class="controls">
						<?php echo $this->form->getValue('title'); ?>
					</div>
				</div>
				<div class="control-group">
					<?php echo $this->form->getLabel('parent_id'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('parent_id'); ?>
					</div>
				</div>
				<div class="control-group">
					<?php echo $this->form->getLabel('published'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('published'); ?>
					</div>
				</div>
				<div class="control-group">
					<?php echo $this->form->getLabel('access'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('access'); ?>
					</div>
				</div>
				<div class="control-group">
					<?php echo $this->form->getLabel('language'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('language'); ?>
					</div>
				</div>
			</fieldset>
		</div>
		<!-- End Sidebar -->
	</div>
</form>
