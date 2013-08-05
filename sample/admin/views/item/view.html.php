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
 * HTML View class for the Tags component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_tags
 * @since       3.1
 */
class SampleViewItem extends JViewLegacy
{
    protected $form;

    protected $item;

    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');
        $this->canDo = SampleHelper::getActions($this->state->get('sample.component'));
        $input = JFactory::getApplication()->input;

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $input->set('hidemainmenu', true);
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since  3.1
     */
    protected function addToolbar()
    {
        $input      = JFactory::getApplication()->input;
        $user       = JFactory::getUser();
        $userId     = $user->get('id');

        $isNew      = ($this->item->id == 0);
        $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

        // Need to load the menu language file as mod_menu hasn't been loaded yet.
        $lang = JFactory::getLanguage();
            $lang->load('com_sample', JPATH_BASE, null, false, false)
        ||  $lang->load('com_sample', JPATH_ADMINISTRATOR.'/components/com_sample', null, false, false)
        ||  $lang->load('com_sample', JPATH_BASE, $lang->getDefault(), false, false)
        ||  $lang->load('com_sample', JPATH_ADMINISTRATOR.'/components/com_sample', $lang->getDefault(), false, false);

        // Load the tags helper.
        require_once JPATH_COMPONENT.'/helpers/sample.php';

        // Get the results for each action.
        $canDo = SampleHelper::getActions('com_sample', $this->item->id);

        $title = JText::_('COM_SAMPLE_BASE_'.($isNew?'ADD':'EDIT').'_TITLE');

        // Prepare the toolbar.
        JToolbarHelper::title($title, 'sample-'.($isNew?'add':'edit').($isNew?'add':'edit'));

        // For new records, check the create permission.
        if ($isNew)
        {
            JToolbarHelper::apply('item.apply');
            JToolbarHelper::save('item.save');
            JToolbarHelper::save2new('item.save2new');
        }

        // If not checked out, can save the item.
        elseif (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_user_id == $userId))) {
            JToolbarHelper::apply('item.apply');
            JToolbarHelper::save('item.save');
            if ($canDo->get('core.create')) {
                JToolbarHelper::save2new('item.save2new');
            }
        }

        // If an existing item, can save to a copy.
        if (!$isNew && $canDo->get('core.create')) {
            JToolbarHelper::save2copy('item.save2copy');
        }

        if (empty($this->item->id))  {
            JToolbarHelper::apply('item.apply');
            JToolbarHelper::save('item.save');
            JToolbarHelper::save2new('item.save2new');
            JToolbarHelper::cancel('item.cancel');
        }
        else {
                   JToolbarHelper::apply('item.apply');
            JToolbarHelper::save('item.save');
            JToolbarHelper::save2new('item.save2new');
    
            JToolbarHelper::cancel('item.cancel', 'JTOOLBAR_CLOSE');
        }
        JToolbarHelper::help('JHELP_COMPONENTS_SAMPLE_MANAGER_EDIT');
        JToolbarHelper::divider();

    }
}
