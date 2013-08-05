<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Banner table
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.5
 */
class SampleTableItems extends JTable
{
	/**
	 * Constructor
	 *
	 * @since   1.5
	 */
	public function __construct(&$_db)
	{
		parent::__construct('#__sample_items', 'id', $_db);
		$date = JFactory::getDate();
		$this->created = $date->toSql();
	}

	

	/**
	 * Overloaded check function
	 *
	 * @return  boolean
	 * @see     JTable::check
	 * @since   1.5
	 */
	public function check()
	{

		return true;
	}


}
