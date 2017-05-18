<?php

/**
 * Member Directory Plugin
 *
 * Creates a directory of members within your
 * Zenbership membership website.
 *
 * This class controls the listing page of the
 * member directory.
 *
 * @package		Zenbership Membership Software
 * @author 		Ascad Networks
 * @link 		http://www.zenbership.com/
 * @link 		http://www.ascadnetwork.com/
 */

class listings extends db {
	
	protected $plugin, $user;
	protected $display, $order, $dir, $limit_low, $url;
	public $results, $searchQuery, $total;


	function __construct($pluginClass)
	{
		$this->user = new user;
		$this->plugin = $pluginClass;
	}


	/**
	 * Render the appropriate template.
	 */
	public function renderOutput()
	{
		if ($this->total > 0) {
			return $this->plugin->renderTemplate('member_listings', $this->results);
		} else {
			return $this->plugin->renderTemplate('member_listings_none', $this->results);
		}
	}


	/**
	 * Based on the query built in $this->buildQuery(),
	 * we now run the query to find results.
	 */
	public function getResults()
	{
		$entries = '';

		$query = $this->buildQuery();
		$run = $this->run_query($query);
		while ($row = $run->fetch()) {
			$entries .= $this->renderEntry($row['id']);
		}

		// Changes
		$this->results['entries'] = $entries;
		$this->results['pagination'] = $this->paginate();
		$this->results['total'] = $this->total;
		$this->results['page'] = $this->page;
		$this->results['order'] = $this->order;
		$this->results['dir'] = $this->dir;
		$this->results['meta_title'] = $this->plugin->option('listing_title');
		$this->results['title'] = $this->plugin->option('listing_title');
		$this->results['desc'] = $this->plugin->option('listing_desc');
		$this->results['breadcrumbs'] = '<a href="' . $this->url . '">' . $this->plugin->option('listing_title') . '</a>';
		$this->results['query'] = $this->searchQuery;
	}


	/**
	 * Generate a simple pagination based on the
	 * results.
	 */
	protected function paginate()
	{
		$total_pages = ceil($this->total / $this->display);
		// First page
		$nextLink = '';
		$prevLink = '';
		$nextPage = $this->page + 1;
		$prevPage = $this->page - 1;
		$this->url = $this->current_url('0') . '?' . $this->queryString();
		if ($this->page <= 1 && $total_pages > 1) {
			$nextLink = '<a href="' . $this->url . '&page=' . $nextPage . '">Next &raquo;</a>';
			$prevLink = '&laquo; Previous';
		}
		// Last page
		else if ($this->page >= $total_pages && $total_pages > 1) {
			$nextLink = 'Next &raquo;';
			$prevLink = '<a href="' . $this->url . '&page=' . $prevPage . '">&laquo; Previous</a>';
		}
		else if ($total_pages <= 1) {
			$nextLink = 'Next &raquo;';
			$prevLink = '&laquo; Previous';
		}
		// Middle pages
		else {
			$nextLink = '<a href="' . $this->url . '&page=' . $nextPage . '">Next &raquo;</a>';
			$prevLink = '<a href="' . $this->url . '&page=' . $prevPage . '">&laquo; Previous</a>';
		}
		return $prevLink . ' ' . $nextLink;
	}


	/**
	 * For a pagination this is the query string.
	 */
	protected function queryString()
	{
		return  'display=' . $this->display .
				'&order=' . $this->order . 
				'&dir=' . $this->dir . 
				'&query=' . $this->searchQuery;
	}


	/**
	 * Determine the correct query for
	 * the requested listings.
	 *
	 * @param Int 		$page
	 * @param Int 		$display
	 * @param String 	$order
	 * @param String 	$direction
	 * @param String 	$searchQuery
	 */
	protected function buildQuery()
	{

		// Prep the basics.
		$this->buildQueryComponents();

		// Build where.
		$this->buildWhere();

		// Final Query
		$query = "SELECT ppSD_members.id
			FROM ppSD_members
			JOIN ppSD_member_data
			ON ppSD_members.id = ppSD_member_data.member_id
			$this->where
			ORDER BY " . $this->useOrder . " " . $this->dir . "
			LIMIT " . $this->limit_low . ", " . $this->display;

		// Count query for total entries.
		$queryA = $this->get_array("SELECT COUNT(*)
			FROM ppSD_members
			JOIN ppSD_member_data
			ON ppSD_members.id = ppSD_member_data.member_id" .
			$this->where);

		// Up total found.
		$this->total = $queryA['0'];

		return $query;
	}


	/**
	 * Based on GET elements, build the necessary
	 * components for the query.
	 */
	protected function buildQueryComponents()
	{
		// Page
		if (! empty($_GET['page'])) {
			if (is_numeric($_GET['page'])) {
				$this->page = $_GET['page'];
			} else {
				$this->page = 1;
			}
		} else {
			$this->page = 1;
		}

		// Display
		if (! empty($_GET['display'])) {
			if (is_numeric($_GET['display'])) {
				$this->display = $_GET['display'];
			} else {
				$this->display = 25;
			}
		} else {
			$this->display = 25;
		}

		// Direction
		if (! empty($_GET['dir'])) {
			$this->dir = $_GET['dir'];
		} else {
			$this->dir = 'ASC';
		}

		// Query
		if (! empty($_GET['query'])) {
			$this->searchQuery = strip_tags($_GET['query']);
		} else {
			$this->searchQuery = '';
		}

		$this->limit_low = ($this->page * $this->display) - $this->display;
	}


	/**
	 * Build the WHERE component of the
	 * listing query. Also builds the
	 * ORDER component. They are combined
	 * because they share requirements.
	 *
	 * @param String 	$searchQuery
	 *
	 * @return WHERE statement without the pro
	 */
	protected function buildWhere()
	{

		$opt = $this->plugin->option('weighted_results');
		$primary = $this->user->get_primary_fields();

		$whereAdd = '';
		$where = "ppSD_members.status='A'";
		if (! empty($opt)) {
			$exp = explode(',', $opt);
			$whereUp = '';
			$where .= ' AND (';
			foreach ($exp as $memtype) {
				$whereUp .= " OR ppSD_members.member_type='" . $this->mysql_cleans($memtype) . "'";
			}
			$where .= substr($whereUp, 4);
			$where .= ')';
		}
		if (! empty($this->searchQuery)) {
			$options = explode(',', $this->plugin->option('searchable'));
			foreach ($options as $anOption) {
				if (in_array($anOption, $primary)) {
					$whereAdd .= " OR ppSD_members." . $anOption . " LIKE '%" . $this->mysql_cleans($this->searchQuery) . "%'";
				} else {
					$whereAdd .= " OR ppSD_member_data." . $anOption . " LIKE '%" . $this->mysql_cleans($this->searchQuery) . "%'";
				}
			}
			$where .= ' AND (' . substr($whereAdd, 4) . ')';
		}

		// Order
		if (! empty($opt)) {
			$this->useOrder = 'FIELD(ppSD_members.member_type';
			foreach ($exp as $memtype) {
				$this->useOrder .= ', ' . $memtype;
			}
			$this->useOrder .= ')';
		}

		if (! empty($_GET['order'])) {
			if (in_array($_GET['order'], $primary)) {
				$this->order = $_GET['order'];
				$this->useOrder .= ', ppSD_members.' . $this->mysql_cleans($_GET['order']);
			} else {
				$this->order = $_GET['order'];
				$this->useOrder .= ', ppSD_member_data.' . $this->mysql_cleans($_GET['order']);
			}
		} else {
			if (! empty($this->useOrder)) {
				$this->useOrder .= ', ';
			}
			$this->useOrder .= 'ppSD_member_data.last_name';
			$this->order = 'last_name';
		}

		$this->where = ' WHERE ' . $where;
	}


	/**
	 * Render each result from $this->getResults();
	 */
	protected function renderEntry($id)
	{
		// Get the user in question
		$uData = $this->user->get_user($id, '', '', true);

		// Only proceed is the member exists.
		if (! empty($uData['data']['id'])) {
		
			// Prepare the "replacement" data for the template.
			$changes = $uData['data'];
			$changes['member_picture'] = $uData['get_profile_pic_url'];
			$changes['profile_link'] = PP_URL . '/directory/' . $uData['data']['username'];

			// Render the entry for the directory.
            $filename = 'member_listing_entry_' . $uData['data']['member_type'];
            $file = dirname(dirname(__FILE__)) . '/templates/' . $filename . '.php';
            if (file_exists($file)) {
                return $this->plugin->renderTemplate($filename, $changes, '0');
            } else {
                return $this->plugin->renderTemplate('member_listing_entry', $changes, '0');
            }
		} else {
			return '';
		}
	}

}