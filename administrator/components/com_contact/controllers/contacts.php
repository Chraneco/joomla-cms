<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Articles list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 * @since       1.6
 */
class ContactControllerContacts extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config	An optional associative array of configuration settings.
	 *
	 * @return  ContactControllerContacts
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('unfeatured',	'featured');
	}

	/**
	 * Method to toggle the featured setting of a list of contacts.
	 *
	 * @return  void
	 * @since   1.6
	 */
	public function featured()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user   = JFactory::getUser();
		$ids    = $this->input->get('cid', array(), 'array');
		$values = array('featured' => 1, 'unfeatured' => 0);
		$task   = $this->getTask();
		$value  = JArrayHelper::getValue($values, $task, 0, 'int');

		// Get the model.
		$model  = $this->getModel();

		// Access checks.
		foreach ($ids as $i => $id)
		{
			$item = $model->getItem($id);
			if (!$user->authorise('core.edit.state', 'com_contact.category.'.(int) $item->catid))
			{
				// Prune items that you can't change.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
		}

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_CONTACT_NO_ITEM_SELECTED'));
		}
		else
		{
			// Publish the items.
			if (!$model->featured($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_contact&view=contacts');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string	$name	The name of the model.
	 * @param   string	$prefix	The prefix for the PHP class name.
	 *
	 * @return  JModel
	 * @since   1.6
	 */
	public function getModel($name = 'Contact', $prefix = 'ContactModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$pks   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
	/**
	 * Function that allows child controller access to model data
	 * after the item has been deleted.
	 *
	 * @param   JModelLegacy  $model  The data model object.
	 * @param   integer       $ids    The array of ids for items being deleted.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
		// If an item has been tagged we need to untag it and delete it from #__core_content.
		$task = $this->getTask();

		$item = $model->getItem();

		$tags = new JTags;
		$tags->deleteTagData($ids, 'com_contact.contact');

	}

}
