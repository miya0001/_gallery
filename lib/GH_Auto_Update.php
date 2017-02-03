<?php

class GH_Auto_Update
{
	/**
	 * The plugin current version
	 * @var string
	 */
	private $current_version;

	/**
	 * The user name of the GitHub
	 * @var string
	 */
	private $gh_api;

	/**
	 * Plugin Slug (plugin_directory/plugin_file.php)
	 * @var string
	 */
	private $plugin_slug;

	/**
	 * Plugin name (plugin_file)
	 * @var string
	 */
	private $slug;

	/**
	 * Initialize a new instance of the WordPress Auto-Update class
	 * @param string $current_version
	 * @param string $gh_user
	 * @param string $gh_repo
	 * @param string $plugin_slug
	 */
	public function __construct( $current_version, $gh_user, $gh_repo, $plugin_slug )
	{
		// Set the class public variables
		$this->current_version = $current_version;
		$this->gh_api = sprintf(
			'https://api.github.com/repos/%s/%s/releases/latest',
			$gh_user,
			$gh_repo
		);
		$this->gh_user = $gh_user;
		$this->gh_repo = $gh_repo;

		// Set the Plugin Slug
		$this->plugin_slug = $plugin_slug;
		list ($t1, $t2) = explode( '/', $plugin_slug );
		$this->slug = str_replace( '.php', '', $t2 );

		// define the alternative API for updating checking
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );

		// Define the alternative response for information checking
		add_filter( 'plugins_api', array( $this, 'check_info' ), 10, 3 );
	}

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param $transient
	 * @return object $ transient
	 */
	public function check_update( $transient )
	{
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// Get the remote version
		$res = wp_remote_get( $this->gh_api );
		$body = wp_remote_retrieve_body( $res );
		$remote_version = json_decode( $body );

		// If a newer version is available, add the update
		if ( version_compare( $this->current_version, $remote_version->tag_name, '<' ) ) {
			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->new_version = $remote_version->tag_name;
			$obj->url = $remote_version->html_url;
			$obj->plugin = $this->plugin_slug;
			$obj->package = $this->get_download_url( $remote_version->tag_name );
			// $obj->tested = $remote_version->tested;
			$transient->response[$this->plugin_slug] = $obj;
		}
		return $transient;
	}

	/**
	 * Add our self-hosted description to the filter
	 *
	 * @param boolean $false
	 * @param array $action
	 * @param object $arg
	 * @return bool|object
	 */
	public function check_info( $obj, $action, $arg )
	{
		if ( ( 'query_plugins' === $action || 'plugin_information' === $action ) &&
				isset( $arg->slug ) && $arg->slug === $this->slug ) {
			// Get the remote version
			$res = wp_remote_get( $this->gh_api );
			$body = wp_remote_retrieve_body( $res );
			$remote_version = json_decode( $body );

			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->plugin_name = $this->slug;
			$obj->new_version = $remote_version->tag_name;
			$obj->last_updated = $remote_version->published_at;
			$obj->sections = array(
				'changelog' => $remote_version->body
			);
			$obj->download_link = $this->get_download_url( $remote_version->tag_name );
			return $obj;
		}

		return $obj;
	}

	private function get_download_url( $tag_name )
	{
		return sprintf(
			'https://github.com/%s/%s/archive/%s.zip',
			$this->gh_user,
			$this->gh_repo,
			$tag_name
		);
	}
}
