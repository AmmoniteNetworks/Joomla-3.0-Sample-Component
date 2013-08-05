<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_sample
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Sample component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_sample
 * @since       1.6
 */
class SampleHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string	The name of the active view.
	 *
	 * @return  void
	 * @since   1.6
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_SAMPLE_SUBMENU_ITEMS'),
			'index.php?option=com_sample&view=items',
			$vName == 'items'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_SAMPLE_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_sample',
			$vName == 'categories'
		);
		if ($vName == 'categories')
		{
			JToolbarHelper::title(
				JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', JText::_('com_sample')),
				'sample-categories');
		}

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  The category ID.
	 *
	 * @return  JObject
	 * @since   1.6
	 */
	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($categoryId))
		{
			$assetName = 'com_sample';
			$level = 'component';
		}
		else
		{
			$assetName = 'com_sample.category.'.(int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_sample', $level);

		foreach ($actions as $action)
		{
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}

		return $result;
	}

}
