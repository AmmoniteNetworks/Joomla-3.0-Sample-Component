<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * HelloWorldList Model
 */
class SampleModelItems extends JModelList
{       



        /**
         * Constructor.
         *
         * @param   array  An optional associative array of configuration settings.
         * @see     JController
         * @since   1.6
         */
        public function __construct($config = array())
        {
                if (empty($config['filter_fields']))
                {
                        $config['filter_fields'] = array(
                                'id', 'a.id',
                                'cid', 'a.cid', 'client_name',
                                'name', 'a.name',
                                'alias', 'a.alias',
                                'state', 'a.state',
                                'ordering', 'a.ordering',
                                'language', 'a.language',
                                'catid', 'a.catid', 'category_title',
                                'checked_out', 'a.checked_out',
                                'checked_out_time', 'a.checked_out_time',
                                'created', 'a.created',
                                'impmade', 'a.impmade',
                                'imptotal', 'a.imptotal',
                                'clicks', 'a.clicks',
                                'publish_up', 'a.publish_up',
                                'publish_down', 'a.publish_down',
                                'state', 'sticky', 'a.sticky',
                        );
                }

                parent::__construct($config);
        }


        

        /**
         * Build an SQL query to load the list data.
         *
         * @return  JDatabaseQuery
         * @since   1.6
         */
        protected function getListQuery()
        {
                $db = $this->getDbo();
                $query = $db->getQuery(true);

                // Select the required fields from the table.
                $query->select(
                        $this->getState(
                                'list.select',
                                'a.id AS id, a.title AS title, a.file AS file,' .
                                        'a.description AS description,' .
                                        'a.checked_out AS checked_out,' . 
                                         'a.published AS published,' .       
                                        'a.ordering AS ordering, a.catid AS catid,' .
                                        'a.access AS access, a.created_time AS created_time, a.modified_time AS modified_time,' .
                                        'a.modified_time AS modified_time'
                        )
                );
                $query->from($db->quoteName('#__sample_items') . ' AS a');

                // Join over the language
                //$query->select('l.title AS language_title')
               //         ->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

                // Join over the users for the checked out user.
               // $query->select('uc.name AS editor')
                 //       ->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

                // Join over the categories.
                $query->select('c.title AS category_title')
                        ->join('LEFT', '#__categories AS c ON c.id = a.catid');

                // Filter by published state
                $published = $this->getState('filter.published');
                if (is_numeric($published))
                {
                        $query->where('a.published = ' . (int) $published);
                }
                elseif ($published === '')
                {
                        $query->where('(a.published IN (0, 1))');
                }

                // Filter by category.
                $categoryId = $this->getState('filter.category_id');
                if (is_numeric($categoryId))
                {
                        $query->where('a.catid = ' . (int) $categoryId);
                }

                // Filter by search in title
                $search = $this->getState('filter.search');
                if (!empty($search))
                {
                        if (stripos($search, 'id:') === 0)
                        {
                                $query->where('a.id = ' . (int) substr($search, 3));
                        }
                        else
                        {
                                $search = $db->quote('%' . $db->escape($search, true) . '%');
                                $query->where('(a.title LIKE ' . $search . ' OR a.description LIKE ' . $search . ')');
                        }
                }

                // Filter on the language.
               // if ($language = $this->getState('filter.language'))
               // {
              //          $query->where('a.language = ' . $db->quote($language));
              //  }

                // Add the list ordering clause.
                $orderCol = $this->state->get('list.ordering', 'ordering');
                $orderDirn = $this->state->get('list.direction', 'ASC');
                if ($orderCol == 'ordering' || $orderCol == 'category_title')
                {
                        $orderCol = 'c.title ' . $orderDirn . ', a.ordering';
                }
             
                $query->order($db->escape($orderCol . ' ' . $orderDirn));
                //echo $query;
                //echo nl2br(str_replace('#__','jos_',$query));
                return $query;
        }


        /**
         * Method to change the published state of one or more records.
         *
         * @param   array       &$pks   A list of the primary keys to change.
         * @param   integer     $value  The value of the published state.
         *
         * @return  boolean  True on success.
         *
         * @since   1.6
         */
        public function publish(&$pks, $value = 1)
        {
                $table = $this->getTable();
                $pks = (array) $pks;

              
                // Clean the cache
                $this->cleanCache();

                // Ensure that previous checks doesn't empty the array
                if (empty($pks))
                {
                        return true;
                }

                return parent::publish($pks, $value);
        }


     


}