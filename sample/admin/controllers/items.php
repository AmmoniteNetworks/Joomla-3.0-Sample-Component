<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of HelloWorld component
 */
class SampleControllerItems extends JControllerAdmin
{
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false, $urlparams = false) 
        {
                // set default view if not set
                $input = JFactory::getApplication()->input;
                $input->set('view', $input->getCmd('view', 'items'));
 
                // call parent behavior
                parent::display($cachable);
        }

        /**
         * Proxy for getModel.
         * @since   1.6
         */
        public function getModel($name = 'Item', $prefix = 'SampleModel', $config = array('ignore_request' => true))
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
                $pks = $this->input->post->get('cid', array(), 'array');
                $order = $this->input->post->get('order', array(), 'array');
                FB::log($pks, 'primary keys');
                FB::log($order, 'ordering');
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



}