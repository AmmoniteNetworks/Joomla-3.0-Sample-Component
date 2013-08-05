<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Tags Component Tag Model
 *
 * @package     Joomla.Administrator
 * @subpackage  com_tags
 * @since       3.1
 */
class SampleModelItem extends JModelAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  3.1
	 */
	protected $text_prefix = 'COM_SAMPLE';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   3.1
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->enabled != -2)
			{
				return;
			}
			$user = JFactory::getUser();

			return parent::canDelete($record);
		}
	}

	/**
	 * Method to test whether a record can have its state changed.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   3.1
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		return parent::canEditState($record);
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   3.1
	*/
	public function getTable($type = 'Items', $prefix = 'SampleTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

	
		// Load the User state.
		$pk = $app->input->getInt('id');
		$this->setState($this->getName() . '.id', $pk);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_sample');
		$this->setState('params', $params);
	}

	/**
	 * Method to get a tag.
	 *
	 * @param   integer  $pk  An optional id of the object to get, otherwise the id from the model state is used.
	 *
	 * @return  mixed  Tag data object on success, false on failure.
	 *
	 * @since   3.1
	 */
	public function getItem($pk = null)
	{
		if ($result = parent::getItem($pk))
		{

			
			// Convert the created and modified dates to local user time for display in the form.
			$tz = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));

			if ((int) $result->created_time)
			{
				$date = new JDate($result->created_time);
				$date->setTimezone($tz);
				$result->created_time = $date->toSql(true);
			}
			else
			{
				$result->created_time = null;
			}

			if ((int) $result->modified_time)
			{
				$date = new JDate($result->modified_time);
				$date->setTimezone($tz);
				$result->modified_time = $date->toSql(true);
			}
			else
			{
				$result->modified_time = null;
			}
		}

		return $result;
	}

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   3.1
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$extension = $this->getState('tag');
		$jinput = JFactory::getApplication()->input;

		// Get the form.
		$form = $this->loadForm('com_sample.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		$user = JFactory::getUser();
		if (!$user->authorise('core.edit.state', 'com_sample' . $jinput->get('id')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   3.1
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_sample.edit.item.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_sample.item', $data);

		return $data;
	}

	/**
	 * Method to preprocess the form.
	 *
	 * @param   JForm   $form    A JForm object.
	 * @param   mixed   $data    The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import.
	 *
	 * @return  void
	 *
	 * @see     JFormField
	 * @since   3.1
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		// Trigger the default form events.
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.1
	 */
	public function save($data)
	{
		$dispatcher = JEventDispatcher::getInstance();
		$table = $this->getTable();
		$input = JFactory::getApplication()->input;
		$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		// Include the content plugins for the on save events.
		//JPluginHelper::importPlugin('content');

		// Load the row if saving an existing tag.
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}

		

		// Alter the title for save as copy
		//if ($input->get('task') == 'save2copy')
		//{
		//	list($title, $alias) = $this->generateNewTitle($data['id'], $data['alias'], $data['title']);
		//	$data['title'] = $title;
		//}

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
			return false;
		}

		// Bind the rules.
		if (isset($data['rules']))
		{
			$rules = new JAccessRules($data['rules']);
			$table->setRules($rules);
		}

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}

		// Trigger the onContentBeforeSave event.
		//$result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, &$table, $isNew));
		//if (in_array(false, $result, true))
		//{
		//	$this->setError($table->getError());
		//	return false;
		//}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
			return false;
		}

		// Trigger the onContentAfterSave event.
		//$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, &$table, $isNew));

		// Rebuild the path for the tag:
		//if (!$table->rebuildPath($table->id))
		//{
		//	$this->setError($table->getError());
		//	return false;
		//}

		// Rebuild the paths of the tag's children:
	//	if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path))
		//{
		//	$this->setError($table->getError());

		//	return false;
		//}

		$this->setState($this->getName() . '.id', $table->id);

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	
	

	
}
