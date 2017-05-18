<?php

/**
 * Member Directory Plugin
 *
 * Creates a directory of members within your
 * Zenbership membership website.
 *
 * This class controls the "profile page"
 * component.
 *
 * @package		Zenbership Membership Software
 * @author 		Ascad Networks
 * @link 		http://www.zenbership.com/
 * @link 		http://www.ascadnetwork.com/
 */

class profile extends db {
	

	protected $plugin, $user;
	protected $display, $order, $dir, $limit_low;
	public $results, $searchQuery;


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
		if (empty($this->results)) {
			return $this->plugin->renderTemplate('member_profile_not_found', array());
		} else {
			return $this->plugin->renderTemplate('member_profile', $this->results);
		}
	}


	/**
	 * Get a user's profile
	 * and render it.
	 */
	public function getProfile()
	{
		if (! empty($_GET['username'])) {
			$find = $this->user->get_user('', $_GET['username']);
			if (! empty($find['data']['id'])) {
				$url = PP_URL . '/directory';
				$title = $this->updateTitle($find['data']);
				$this->results = $find['data'];
				$this->results['member_picture'] = $find['get_profile_pic_url'];;
				$this->results['meta_title'] = $title;
				$this->results['title'] = $title;
				$this->results['desc'] = '';
				$this->results['breadcrumbs'] = '<a href="' . $url . '">' . $this->plugin->option('listing_title') . '</a> / <a href="' . $this->current_url() . '">' . $title . '</a>';
			}
		} else {
			$this->results = array();
		}
	}


	/**
	 * Set the title of the page.
	 */
	protected function updateTitle($data)
	{
		$title = $this->plugin->option('profile_title');
		foreach ($data as $name => $value) {
			$title = str_replace('%' . $name . '%', $value, $title);
		}
		return $title;
	}


}