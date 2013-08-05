<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 


/*

lets add and ACL check here to make sure that the current logged in user, has management rights to the component we are making. 
These are managed in the permissions tab, under the config. see access.xml and config.xml for files relating to this. 


*/
 if (!JFactory::getUser()->authorise('core.manage', 'com_sample'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}




// Execute the task.
//Get the base controller and  instance is  with JControllerLegacy, keep in mind all previous version of joomla would be JController. 
$controller	= JControllerLegacy::getInstance('Sample');
//get the submitted task using the new Jinput
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();