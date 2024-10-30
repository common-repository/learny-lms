<?php

/**
 * @package Learny
 */

namespace Learny\api\callbacks;

use Learny\base\BaseController;
use Learny\base\Helper;
use WP_User;

defined('ABSPATH') or die('You can not access the file directly');

class AdminCallbacks extends BaseController
{
	protected $current_user_role;

	// DECLARING THIS CONSTRUCTOR FOR INITIALIZING THE CURRENT USER ROLE
	function __construct()
	{
		parent::__construct();
		$this->current_user_role = Helper::get_current_user_role();
	}

	// Method called when user clicks on student menu of the plugin
	public function student()
	{
		return require_once("$this->plugin_path/templates/backend/$this->current_user_role/student/index.php");
	}

	// Method called when user clicks on instructor menu of the plugin
	public function instructor()
	{
		return require_once("$this->plugin_path/templates/backend/$this->current_user_role/instructor/index.php");
	}

	// Method called when user clicks on settings menu of the plugin
	public function settings()
	{
		return require_once("$this->plugin_path/templates/backend/$this->current_user_role/settings/index.php");
	}

	// Method called when user clicks on report menu of the plugin
	public function report()
	{
		return require_once("$this->plugin_path/templates/backend/$this->current_user_role/report/index.php");
	}

	// Method called when user clicks on payout menu of the plugin
	public function payout()
	{
		return require_once("$this->plugin_path/templates/backend/$this->current_user_role/payout/index.php");
	}

	// Method called when user clicks on Learny LMS Pro menu of the plugin
	public function pro_lms()
	{
		return require_once("$this->plugin_path/templates/backend/$this->current_user_role/learny_lms_pro/index.php");
	}
}
