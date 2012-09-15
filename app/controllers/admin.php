<?php
/**
 * admin controller
 * backend class.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 7.14
 * @package app
 * @subpackage controllers
 */
class admin extends controller {
	/**
	 * constructor
	 * load the session manager by default
	 */
	function __construct() {
		$imports = array(
			"session" => array(
				"type" => qoob_types::core, 
				"class" => "dbsession", 
				"dir" => "users/"));
		parent::__construct($imports);
	}
//___________________________________________________________________________________________________________
//                                                                                               login/logout
	/**
	 * logout
	 * destroy the dession
	 */
	function logout() {
		$this->session->destroy(session_id());
		$this->session->regenerate();
		header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
	}
	/**
	 * index
	 * login screen
	 */
	function index() {
		$html["title"] = 'backdoor';
		$html["meta"] = '';
		$html["sidebar"] = $this->view("blog/sidebar_qr", array(), true);
		$html["selected"] = '';
		$html["script"] = '';
		$html["body"] = '';
		$html["error"] = "";
		$html["username"] = '';
		$html["password"] = '';
		$loginAttempt = false;

		if($_POST) {
			$loginAttempt = true;
			$html["username"] = getRequest("txtUser", "post", FILTER_SANITIZE_STRING);
			$html["password"] = getRequest("txtPass", "post", FILTER_SANITIZE_STRING);
		} 
		if($html["username"] === "" or $html["password"] === "") {
			$html["error"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
		} else {
			$admin = $this->model("adminModel");
			$result = $admin->checkUser($html["username"]);
			if (count($result) > 0) {
				//--load the hash utility
				$this->library(qoob_types::utility, "hash", "crypto/");
				//--compare pass to hash
				if($this->hash->compare($html["password"], $result[0]["password"])) {
					//---setup session
					$_SESSION["admin_id"] = $result[0]["admin_id"];
					$_SESSION["name"] = $result[0]["name"]; 
					$_SESSION["username"] = $result[0]["username"];
					$_SESSION["email"] = $result[0]["email"];
					header("location: ".QOOB_DOMAIN."backdoor/console/");
				} else {
					$html["error"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Bad username / password combination!'), true);
				}
			} else {
				$html["error"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Bad username / password combination!'), true);
			}
		}
		if(!$loginAttempt) {
			$html["error"] = "";
		}

		$post = array(
			'mainCat' => '',
			'url' => '',
			'title' => 'Backdoor',
			'subtitle' => 'the login screen',
			'content' => $this->view("admin/login", array('errors' => $html["error"]), true),
			'comments' => 0
		);
		$html["body"] = $this->view("post", $post, true);
		$this->view("pixelgraff", $html);
	}
	/**
	 * main 
	 * display the main menu after login
	 */
	function main() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/console';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = '';
			$post = array(
				'mainCat' => '',
				'url' => '',
				'title' => 'Backdoor',
				'subtitle' => 'the console',
				'content' => '<p>Hello <strong>'.$_SESSION['name'].'</strong>,<br/> and welcome to the qoob backend.<br/>Use the menu on the right to moderate the site.</p>',
				'comments' => 0
			);			
			$html["body"] = $this->view("post", $post, true);
			$this->view("pixelgraff", $html);
		}
	}
//___________________________________________________________________________________________________________
//                                                                                                      admin
	/**
	 * add admin
	 * add new administrators to the database
	 */
	function addAdmin() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/addAdmin';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addAdminJS", array(), true);
			$html["body"] = '';
			$saveAttempt = false;

			if($_POST) {
				$clean["name"] = getRequest("txtName", "post", FILTER_SANITIZE_STRING);
				$clean["user"] = getRequest("txtUser", "post", FILTER_SANITIZE_STRING);
				$clean["email"] = getRequest("txtEmail", "post", FILTER_SANITIZE_EMAIL);
				$clean["pass"] = getRequest("txtPass", "post", FILTER_SANITIZE_STRING);
				$saveAttempt = true;
				$data = array(
					'txtName' => $clean["name"],
					'txtUser' => $clean["user"],
					'txtEmail' => $clean["email"],
					'txtPass' => $clean["pass"],
					'errors' => ''
				);
				if($clean["name"] === "" or $clean["user"] === "" or $clean["email"] === "" or $clean["pass"] === "") {
					$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
					$form = $this->view("admin/addAdmin", $data, true);
					$post = array(
						'mainCat' => '',
						'url' => '',
						'title' => 'Backdoor',
						'subtitle' => 'Add Administrators',
						'content' => $form,
						'comments' => 0
					);
					$html["body"] = $this->view("post", $post, true);
				} else {
					$am = $this->model("adminModel");
					$result = $am->checkAdmin($clean["email"]);
					if(isset($result[0])) {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'That email is already in use!'), true);
						$form = $this->view("admin/addAdmin", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Administrators',
							'content' => $form,
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					} else {
						//---generate random values
						$algo = mt_rand(0, 1) == 0 ? false : true;
						list($usec, $sec) = explode(' ', microtime());   // reseed the
						$seed = (float) $sec + ((float) $usec * 100000); // random number
						mt_srand($seed);                                 // generator
						$rounds = mt_rand(2000, 3000);
						//---hash password
						$this->library(qoob_types::utility, "hash", "crypto/");
						$this->hash->sha1 = $algo;
						$this->hash->rounds = $rounds;
						$clean["pass"] = $this->hash->make($clean["pass"]);

						$am->addAdmin($clean);
						$html["error"] = '';
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Administrators',
							'content' => 'Administrator added successfully!',
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					}
				}
			}
			if(!$saveAttempt) {
				$form = array(
					'txtName' => '',
					'txtUser' => '',
					'txtEmail' => '',
					'txtPass' => '',
					'errors' => ''
				);
				$form = $this->view("admin/addAdmin", $form, true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Add Administrators',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * modify admin
	 * update the administrators info in the database
	 */
	function modAdmin() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/modAdmin';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addAdminJS", array(), true);
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["admin_id"] = getRequest("whichAdmin", "post", FILTER_SANITIZE_NUMBER_INT);
					$admin = $am->getAdminByID($clean["admin_id"]);
					if(isset($admin[0])) {
						$data = array(
							'admin_id' => $admin[0]["admin_id"],
							'txtName' => $admin[0]["name"],
							'txtUser' => $admin[0]["username"],
							'txtEmail' => $admin[0]["email"],
							'errors' => ''
						);
						$form = $this->view("admin/modAdmin", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Administrator',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						throw new Exception("Invalid admin id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["name"] = getRequest("txtName", "post", FILTER_SANITIZE_STRING);
					$clean["user"] = getRequest("txtUser", "post", FILTER_SANITIZE_STRING);
					$clean["email"] = getRequest("txtEmail", "post", FILTER_SANITIZE_EMAIL);
					$clean["pass"] = getRequest("txtPass", "post", FILTER_SANITIZE_STRING);
					$clean["admin_id"] = getRequest("admin_id", "post", FILTER_SANITIZE_NUMBER_INT);
					$data = array(
							'txtName' => $clean["name"],
							'txtUser' => $clean["user"],
							'txtEmail' => $clean["email"],
							'txtPass' => $clean["pass"],
							'admin_id' => $clean["admin_id"]					
					);
					if($clean["name"] === "" or $clean["user"] === "" or $clean["email"] === "" or $clean["pass"] === "" or $clean["admin_id"] === "") {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
						$form = $this->view("admin/modAdmin", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Administrator',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						//---generate random values
						$algo = mt_rand(0, 1) == 0 ? false : true;
						list($usec, $sec) = explode(' ', microtime());   // reseed the
						$seed = (float) $sec + ((float) $usec * 100000); // random number
						mt_srand($seed);                                 // generator
						$rounds = mt_rand(2000, 3000);
						//---hash password
						$this->library(qoob_types::utility, "hash", "crypto/");
						$this->hash->sha1 = $algo;
						$this->hash->rounds = $rounds;
						$clean["pass"] = $this->hash->make($clean["pass"]);

						$am->modAdmin($clean);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Administrators',
							'content' => 'Administrator modified successfully!',
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$admins = $am->getAllAdmins();
				$adminList = '';
				foreach ($admins as $admin) {
					$adminList .= '<option value="'.$admin['admin_id'].'">'.$admin['name'].'</option>\n';
				}				
				$form = $this->view("admin/modAdminSelect", array('adminList' => $adminList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Modify Administrators',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * delete admin
	 * remove administrators from the database
	 */
	function delAdmin() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/delAdmin';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/delPageJS", array(), true);
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["admin_id"] = getRequest("whichAdmin", "post", FILTER_SANITIZE_NUMBER_INT);
					$admin = $am->getAdminByID($clean["admin_id"]);
					if(isset($admin[0])) {
						$data = array(
							'name' => $admin[0]["name"],
							'admin_id' => $admin[0]["admin_id"]
						);
						$form = $this->view("admin/delAdmin", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Administrator',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						throw new Exception("Invalid admin id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["admin_id"] = getRequest("admin_id", "post", FILTER_SANITIZE_NUMBER_INT);
					if($clean["admin_id"] === "") {
						throw new Exception("Invalid admin id.", 500);
					} else {
						$am->deleteAdmin($clean["admin_id"]);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Administrators',
							'content' => 'Administrator deleted successfully!',
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$admins = $am->getAllAdmins();
				$adminList = '';
				foreach ($admins as $admin) {
					$adminList .= '<option value="'.$admin['admin_id'].'">'.$admin['name'].'</option>\n';
				}				
				$form = $this->view("admin/delAdminSelect", array('adminList' => $adminList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Delete Administrators',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * invite new admins
	 */
	function invite() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/invite';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = '';
			$post = array(
				'mainCat' => '',
				'url' => '',
				'title' => 'Backdoor',
				'subtitle' => 'Invitations',
				'content' => 'Administrator invites coming soon...',
				'comments' => 0
			);			
			$html["body"] = $this->view("post", $post, true);
			$this->view("pixelgraff", $html);
		}
	}
//___________________________________________________________________________________________________________
//                                                                                                      pages
	/**
	 * display page functions
	 */
	function pages() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/pages';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = '';
			$post = array(
				'mainCat' => '',
				'url' => '',
				'title' => 'Backdoor',
				'subtitle' => 'Pages',
				'content' => 'Administrator invites coming soon...',
				'comments' => 0
			);			
			$html["body"] = $this->view("post", $post, true);
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * add page
	 * add new pages to the database
	 */
	function addPage() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/addPage';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addPageJS", array(), true);
			$saveAttempt = false;

			if($_POST) {
				$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
				$clean["title"] = getRequest("txtTitle", "post", FILTER_SANITIZE_STRING);
				$clean["subtitle"] = getRequest("txtSubTitle", "post", FILTER_SANITIZE_STRING);
				$clean["body"] = getRequest("txtBody", "post", FILTER_SANITIZE_SPECIAL_CHARS);
				$clean["script"] = getRequest("txtScript", "post", FILTER_SANITIZE_SPECIAL_CHARS);
				$clean["selected"] = getRequest("txtSelected", "post", FILTER_SANITIZE_STRING);
				$clean["meta"] = getRequest("txtMeta", "post", FILTER_SANITIZE_STRING);
				$clean["sidebar"] = getRequest("txtSidebar", "post", FILTER_SANITIZE_STRING);
				$saveAttempt = true;
				$data = array(
						'errors' => '',
						'txtTitle' => $clean["title"],
						'txtSubTitle' => $clean["subtitle"],
						'txtURL' => $clean["url"],
						'txtBody' => $clean["body"],
						'txtScript' => $clean["script"],
						'txtSelected' => $clean["selected"],
						'txtMeta' => $clean["meta"],
						'txtSidebar' => $clean["sidebar"]
				);
				if($clean["url"] === "" or $clean["title"] === "" or $clean["body"] === "") {
					$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
					$form = $this->view("admin/addPage", $data, true);
					$post = array(
						'mainCat' => '',
						'url' => '',
						'title' => 'Backdoor',
						'subtitle' => 'Add Page',
						'content' => $form,
						'comments' => 0
					);			
					$html["body"] = $this->view("post", $post, true);
				} else {
					$am = $this->model("adminModel");
					$result = $am->checkPageRoute($clean["url"]);
					if(isset($result[0])) {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'That URL is already in use!'), true);
						$form = $this->view("admin/addPage", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Page',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						$am->addPage($clean);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Page',
							'content' => 'New page added successfully!',
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					}
				}
			}
			if(!$saveAttempt) {
				$data = array(
					'txtTitle' => '',
					'txtSubTitle' => '',
					'txtURL' => '',
					'txtBody' => '',
					'txtScript' => '',
					'txtSelected' => '',
					'txtMeta' => '',
					'txtSidebar' => '',
					'errors' => '',
				);
				$form = $this->view("admin/addPage", $data, true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Add Page',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * modify pages
	 * update the contents of pages in the database
	 */
	function modPage() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/modPage';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addPageJS", array(), true);
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["page_id"] = getRequest("whichPage", "post", FILTER_SANITIZE_NUMBER_INT);
					$page = $am->getPage($clean["page_id"]);
					if(isset($page[0])) {
						$route = $am->getPageRouteIDs($page[0]["url"]);
						$data = array(
							'errors' => '',
							'txtTitle' => $page[0]["title"],
							'txtSubTitle' => $page[0]["subtitle"],
							'txtURL' => $page[0]["url"],
							'txtBody' => $page[0]["body"],
							'txtScript' => $page[0]["script"],
							'txtSelected' => $page[0]["mainCat"],
							'txtMeta' => $page[0]["meta"],
							'txtSidebar' => $page[0]["sidebar"],
							'page_id' => $route["p_id"],
							'route_id' => $route["r_id"]
						);						
						$form = $this->view("admin/modPage", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Page',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);						
					} else {
						throw new Exception("Invalid page id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["page_id"] = getRequest("page_id", "post", FILTER_SANITIZE_NUMBER_INT);
					$clean["route_id"] = getRequest("route_id", "post", FILTER_SANITIZE_NUMBER_INT);
					$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
					$clean["title"] = getRequest("txtTitle", "post", FILTER_SANITIZE_STRING);
					$clean["subtitle"] = getRequest("txtSubTitle", "post", FILTER_SANITIZE_STRING);
					$clean["body"] = getRequest("txtBody", "post", FILTER_SANITIZE_SPECIAL_CHARS);
					$clean["script"] = getRequest("txtScript", "post", FILTER_SANITIZE_SPECIAL_CHARS);
					$clean["selected"] = getRequest("txtSelected", "post", FILTER_SANITIZE_STRING);
					$clean["meta"] = getRequest("txtMeta", "post", FILTER_SANITIZE_STRING);
					$clean["sidebar"] = getRequest("txtSidebar", "post", FILTER_SANITIZE_STRING);
					$data = array(
							'errors' => '',
							'txtTitle' => $clean["title"],
							'txtSubTitle' => $clean["subtitle"],
							'txtURL' => $clean["url"],
							'txtBody' => $clean["body"],
							'txtScript' => $clean["script"],
							'txtSelected' => $clean["selected"],
							'txtMeta' => $clean["meta"],
							'txtSidebar' => $clean["sidebar"],
							'page_id' => $clean["page_id"],
							'route_id' => $clean["route_id"]
					);
					if($clean["url"] === "" or $clean["title"] === "" or $clean["body"] === "") {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
						$form = $this->view("admin/modPage", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Page',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						if($am->checkPageRouteChange($clean["page_id"], $clean["url"])) {
							$test =$am->checkPageRoute($clean["url"]);
							if(isset($test[0])) {
								$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'That URL is already in use!'), true);
								$form = $this->view("admin/modPage", $data, true);
								$post = array(
									'mainCat' => '',
									'url' => '',
									'title' => 'Backdoor',
									'subtitle' => 'Modify Page',
									'content' => $form,
									'comments' => 0
								);			
								$html["body"] = $this->view("post", $post, true);								
							} 
						}
						if($data["errors"] == '') {
							$am->modPage($clean);
							$post = array(
								'mainCat' => '',
								'url' => '',
								'title' => 'Backdoor',
								'subtitle' => 'Modify Page',
								'content' => 'Your page has been modified successfully!',
								'comments' => 0
							);			
							$html["body"] = $this->view("post", $post, true);
						}
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$pages = $am->getPages();
				$pageList = '';
				foreach ($pages as $page) {
					$pageList .= '<option value="'.$page['id'].'">'.$page['url'].'</option>\n';
				}				
				$form = $this->view("admin/modPageSelect", array('pageList' => $pageList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Modify Page',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);						
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * delete page
	 * remove pages from the database
	 */
	function delPage() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/delPage';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/delPageJS", array(), true);
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["page_id"] = getRequest("whichPage", "post", FILTER_SANITIZE_NUMBER_INT);
					$page = $am->getPage($clean["page_id"]);
					if(isset($page[0])) {
						$route = $am->getPageRouteIDs($page[0]["url"]);
						$data = array(
							'url' => $page[0]["url"],
							'page_id' => $route["p_id"],
							'route_id' => $route["r_id"]
						);
						$form = $this->view("admin/delPage", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Page',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);	
					} else {
						throw new Exception("Invalid page id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["page_id"] = getRequest("page_id", "post", FILTER_SANITIZE_NUMBER_INT);
					$clean["route_id"] = getRequest("route_id", "post", FILTER_SANITIZE_NUMBER_INT);
					if($clean["page_id"] === "" or $clean["route_id"] === "") {
						throw new Exception("Invalid page id.", 500);
					} else {
						$am->delPage($clean);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Page',
							'content' => 'Your page as been deleted successfully!',
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);	
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$pages = $am->getPages();
				$pageList = '';
				foreach ($pages as $page) {
					$pageList .= '<option value="'.$page['id'].'">'.$page['url'].'</option>\n';
				}				
				$form = $this->view("admin/delPageSelect", array('pageList' => $pageList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Delete Page',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);						
			}
			$this->view("pixelgraff", $html);
		}
	}
//___________________________________________________________________________________________________________
//                                                                                                       blog
	/**
	 * add blog
	 * insert a blog post into the database
	 */
	function addBlog() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/addBlog';
			$html["meta"] = '<link rel="stylesheet" type="text/css" id="ui-css" href="'.BASE_URL.'style/css/jquery.ui.css" media="screen"/>';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addBlogJS", array('taglist' => '', 'catlist' =>'', 'post' => 'draft'), true);
			$html["jsfiles"] = '<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.ui.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.cal.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.sortable.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.compatibility.js" charset="utf-8"></script>'.PHP_EOL;
			$html["body"] = '';
			$saveAttempt = false;

			if($_POST) {
				$clean["date"] = getRequest("txtDateTime", "post", FILTER_SANITIZE_STRING);
				$clean["post"] = getRequest("postMenu", "post", FILTER_SANITIZE_STRING);
				$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
				$clean["title"] = getRequest("txtTitle", "post", FILTER_SANITIZE_STRING);
				$clean["subtitle"] = getRequest("txtSubTitle", "post", FILTER_SANITIZE_STRING);
				$clean["body"] = getRequest("txtBody", "post", FILTER_SANITIZE_SPECIAL_CHARS);
				$clean["excerpt"] = getRequest("txtExcerpt", "post", FILTER_SANITIZE_SPECIAL_CHARS);
				$clean["tags"] = getRequest("txtTags", "post", FILTER_SANITIZE_STRING);
				$cats = getRequest("txtCats", "post");
				$clean["cats"] = is_array($cats) ? implode(",", $cats) : $cats; 
				switch ($clean["post"]) {
					case "draft":
						$clean["live"] = 0;
						$clean["date"] = time();
					break;
					case "now":
						$clean["live"] = 1;
						$clean["date"] = time();
					break;
					case "date":
						$clean["date"] = strtotime($clean["date"]);
						$now = time();
						$clean["live"] = ($clean["date"] <= $now) ? 1 : 0;
					break;									
					default:
						throw new Exception("Bad post type value.", 500);
					break;
				}				
				$saveAttempt = true;

				$html["script"] = $this->view("admin/addBlogJS", array('taglist' => $clean["tags"], 'catlist' => $clean["cats"], 'post' => $clean['post']), true);
				$data = array(
					'errors' => '',
					'date' => $clean["date"],
					'postMenu' => $clean["post"],
					'txtURL' => $clean["url"],
					'txtTitle' => $clean["title"],
					'txtSubTitle' => $clean["subtitle"],
					'txtBody' => $clean["body"],
					'txtExcerpt' => $clean["excerpt"],
					'txtDateTime' => $clean["date"]
				);
				if($clean["url"] === "" || $clean["title"] === "" || $clean["subtitle"] === "" || $clean["body"] === "" or $clean["excerpt"] === "") {
					$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
					$form = $this->view("admin/addBlog", $data, true);
					$post = array(
						'mainCat' => '',
						'url' => '',
						'title' => 'Backdoor',
						'subtitle' => 'Add Blog Entry',
						'content' => $form,
						'comments' => 0
					);			
					$html["body"] = $this->view("post", $post, true);
				} else {
					$am = $this->model("adminModel");
					if(!$am->checkBlogRoute($clean["url"])) {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'That URL is already in use!'), true);
						$form = $this->view("admin/addBlog", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Blog Entry',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);						
					} else {
						//---add post post
						$id = $am->addBlogPost($clean["url"], $clean["title"], $clean["subtitle"], $clean["excerpt"], $clean["body"], $clean["date"], $clean["live"]);
						//---add tags
						if(!empty($clean["tags"])) {
							$tags = explode(",", $clean["tags"]);
							foreach($tags as $tag) {
								$am->addBlogMeta($id, "tag", $tag);
							}
						}
						//---add categories
						if(!empty($clean["cats"])) {
							$cats = explode(",", $clean["cats"]);
							foreach($cats as $cat) {
								$am->addBlogMeta($id, "category", $cat);
							}
						}						
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Blog Entry',
							'content' => 'New blog post added successfully!',
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					}
				}
			}
			if(!$saveAttempt) {
				$data = array(
						'errors' => '',
						'txtTitle' => '',
						'txtSubTitle' => '',
						'txtURL' => '',
						'txtBody' => '',
						'txtExcerpt' => '',
						'txtTags' => '',
						'chkLive' => '',
						'postMenu' => 'draft'
				);
				$form = $this->view("admin/addBlog", $data, true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Add Blog Entry',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	
	/**
	 * modify blog
	 * update a blog post in the database
	 */
	function modBlog() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/modBlog';
			$html["meta"] = '<link rel="stylesheet" type="text/css" id="ui-css" href="'.BASE_URL.'style/css/jquery.ui.css" media="screen"/>';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addBlogJS", array('taglist' => '', 'catlist' =>'', 'post' => 'draft'), true);
			$html["jsfiles"] = '<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.ui.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.cal.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.sortable.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.compatibility.js" charset="utf-8"></script>'.PHP_EOL;
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["post_id"] = getRequest("whichBlog", "post", FILTER_SANITIZE_NUMBER_INT);
					$post = $am->getBlogAndMetaByID($clean["post_id"]);
					if(isset($post[0])) {
						$data = array(
								'errors' => '',
								'post_id' => $clean["post_id"],
								'txtTitle' => $post[0]["title"],
								'txtSubTitle' => $post[0]["subtitle"],
								'txtURL' => $post[0]["url"],
								'theRealURL' => $post[0]["url"],
								'txtBody' => $post[0]["content"],
								'txtExcerpt' => $post[0]["excerpt"],
								'txtCats' => $post[0]["cats"],
								'txtTags' => $post[0]["tags"],
								'txtDateTime' => $post[0]["date"],
								'postMenu' => ($post[0]["live"] == 0) ? 'draft' : 'date'
						);
						$html["script"] = $this->view("admin/addBlogJS", array('taglist' => $post[0]["tags"], 'catlist' => $post[0]["cats"], 'post' => $data["postMenu"]), true);
						$html["body"] = $this->view("admin/modBlog", $data, true);
					} else {
						throw new Exception("Invalid post id.", 500);
					}
				} else {
					$clean["post_id"] = getRequest("post_id", "post", FILTER_SANITIZE_NUMBER_INT);
					$clean["date"] = getRequest("txtDateTime", "post", FILTER_SANITIZE_STRING);
					$clean["post"] = getRequest("postMenu", "post", FILTER_SANITIZE_STRING);
					$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
					$clean["title"] = getRequest("txtTitle", "post", FILTER_SANITIZE_STRING);
					$clean["subtitle"] = getRequest("txtSubTitle", "post", FILTER_SANITIZE_STRING);
					$clean["body"] = getRequest("txtBody", "post", FILTER_SANITIZE_SPECIAL_CHARS);
					$clean["excerpt"] = getRequest("txtExcerpt", "post", FILTER_SANITIZE_SPECIAL_CHARS);
					$clean["tags"] = getRequest("txtTags", "post", FILTER_SANITIZE_STRING);
					$cats = getRequest("txtCats", "post");
					$clean["cats"] = is_array($cats) ? implode(",", $cats) : $cats; 
					switch ($clean["post"]) {
						case "draft":
							$clean["live"] = 0;
							$clean["date"] = time();
						break;
						case "now":
							$clean["live"] = 1;
							$clean["date"] = time();
						break;
						case "date":
							$clean["date"] = strtotime($clean["date"]);
							$now = time();
							$clean["live"] = ($clean["date"] <= $now) ? 1 : 0;
						break;									
						default:
							throw new Exception("Bad post type value.", 500);
						break;
					}				
					$html["script"] = $this->view("admin/addBlogJS", array('taglist' => $clean["tags"], 'catlist' => $clean["cats"], 'post' => $clean["post"]), true);
					$saveAttempt = true;
					$data = array(
						'errors' => '',
						'post_id' => $clean["post_id"],
						'date' => $clean["date"],
						'post' => $clean["post"],
						'txtURL' => $clean["url"],
						'txtTitle' => $clean["title"],
						'txtSubTitle' => $clean["subtitle"],
						'txtBody' => $clean["body"],
						'txtExcerpt' => $clean["excerpt"],
						'postMenu' => $clean["post"],
						'txtDateTime' => $clean["date"]
					);
					if($clean["url"] === "" || $clean["title"] === "" || $clean["subtitle"] === "" || $clean["body"] === "" or $clean["excerpt"] === "") {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
						$form = $this->view("admin/modBlog", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Blog Entry',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						$oldpost = $am->getBlogAndMetaByID($clean["post_id"]);
						if($clean["url"] != $oldpost[0]["url"]) {
							if(!$am->checkBlogRoute($clean["url"])) {
								$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'That URL is already in use!'), true);
								$form = $this->view("admin/modBlog", $data, true);
								$post = array(
									'mainCat' => '',
									'url' => '',
									'title' => 'Backdoor',
									'subtitle' => 'Modify Blog Entry',
									'content' => $form,
									'comments' => 0
								);			
								$html["body"] = $this->view("post", $post, true);
							}
						} 
						if($data["errors"] == "") {
							//---modify post post
							$am->modBlogPost($clean["post_id"], $clean["url"], $clean["title"], $clean["subtitle"], $clean["excerpt"], $clean["body"], $clean["date"], $clean["live"]);
							//---check tags
							$this->library(qoob_types::utility, "custom_diff");
							$tagTest = $this->custom_diff->run($oldpost[0]["tags"], $clean["tags"]);
							if(isset($tagTest["add"])) {
								foreach($tagTest["add"] as $newtag) {
									$am->addBlogMeta($clean["post_id"], "tag", $newtag);
								}
							}
							if(isset($tagTest["del"])) {
								foreach($tagTest["del"] as $oldtag) {
									$am->delBlogMeta($clean["post_id"], "tag", $oldtag);
								}
							}
							//---check categories
							$catTest = $this->custom_diff->run($oldpost[0]["cats"], $clean["cats"]);
							if(isset($catTest["add"])) {
								foreach($catTest["add"] as $newcat) {
									$am->addBlogMeta($clean["post_id"], "category", $newcat);
								}
							}
							if(isset($catTest["del"])) {
								foreach($catTest["del"] as $oldcat) {
									$am->delBlogMeta($clean["post_id"], "category", $oldcat);
								}
							}							
							$post = array(
								'mainCat' => '',
								'url' => '',
								'title' => 'Backdoor',
								'subtitle' => 'Modify Blog Entry',
								'content' => 'Blog post modified successfully!',
								'comments' => 0
							);			
							$html["body"] = $this->view("post", $post, true);
						}
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$posts = $am->getAllBlogPosts();
				$blogList = '';

				if(isset($posts[0])) {
					foreach ($posts as $post) {
						$blogList .= '<option value="'.$post['post_id'].'">'.$post['url'].'</option>\n';
					}					
				} else {
					$blogList = '<option value="x">No Blog Posts</option>\n';
				}
				$form = $this->view("admin/modBlogSelect", array('blogList' => $blogList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Modify Blog Entry',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}	
	/**
	 * delete blog
	 * remove posts from the blog
	 */
	function delBlog() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/delBlog';
			$html["meta"] = '<link rel="stylesheet" type="text/css" id="ui-css" href="'.BASE_URL.'style/css/jquery.ui.css" media="screen"/>';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/delBlogJS", array('taglist' => ''), true);
			$html["jsfiles"] = '<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.ui.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.cal.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.sortable.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.compatibility.js" charset="utf-8"></script>'.PHP_EOL;
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["post_id"] = getRequest("whichBlog", "post", FILTER_SANITIZE_NUMBER_INT);
					$post = $am->getBlogByID($clean["post_id"]);
					if(isset($post[0])) {
						$data = array(
							'url' => $post[0]["url"],
							'post_id' => $post[0]["post_id"]
						);
						$form = $this->view("admin/delBlog", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Blog Entry',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);						
					} else {
						throw new Exception("Invalid post id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["post_id"] = getRequest("post_id", "post", FILTER_SANITIZE_NUMBER_INT);
					if($clean["post_id"] === "") {
						throw new Exception("Invalid post id.", 500);
					} else {
						$am->delBlogPost($clean["post_id"]);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Blog Entry',
							'content' => 'Blog entry deleted successfully!',
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);						
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$posts = $am->getAllBlogPosts();
				$blogList = '';
				if(isset($posts[0])) {
					foreach ($posts as $post) {
						$blogList .= '<option value="'.$post['post_id'].'">'.$post['url'].'</option>\n';
					}
				} else {
					$blogList = '<option value="x">No Blog Posts</option>\n';
				}

				$form = $this->view("admin/delBlogSelect", array('blogList' => $blogList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Delete Blog Entry',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
//___________________________________________________________________________________________________________
//                                                                                                    gallery
	/**
	 * add gallery category
	 * add a new gallery categories to the database
	 */
	function addGalleryCat() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/addGalleryCat';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addGalleryCatJS", array('parent' => ''), true);
			$html["body"] = '';
			$saveAttempt = false;

			if($_POST) {
				$clean["parent"] = getRequest("selNewCat", "post", FILTER_SANITIZE_NUMBER_INT);
				$clean["name"] = getRequest("txtName", "post", FILTER_SANITIZE_STRING);
				$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
				$clean["title"] = getRequest("txtTitle", "post", FILTER_SANITIZE_STRING);
				$clean["excerpt"] = getRequest("txtExcerpt", "post", FILTER_SANITIZE_SPECIAL_CHARS);
				$clean["description"] = getRequest("txtDescript", "post", FILTER_SANITIZE_SPECIAL_CHARS);
				$clean["live"] = getRequest("chkLive", "post", FILTER_SANITIZE_STRING);
				$clean["live"] = $clean["live"] == "on" ? 1 : 0;
				$saveAttempt = true;
				$data = array(
					'txtName' => $clean["name"],
					'txtURL' => $clean["url"],
					'txtTitle' => $clean["title"],
					'txtExcerpt' => $clean["excerpt"],
					'txtDescript' => $clean["description"],
					'chkLive' => $clean["live"],
					'errors' => ''
				);
				if($clean["parent"] === "" || $clean["name"] === "" || $clean["title"] === "" || $clean["url"] === "") {
					$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
					$html["script"] = $this->view("admin/addGalleryCatJS", array('parent' => $clean["parent"]), true);
					$form = $this->view("admin/addGalleryCat", $data, true);
					$post = array(
						'mainCat' => '',
						'url' => '',
						'title' => 'Backdoor',
						'subtitle' => 'Add Gallery',
						'content' => $form,
						'comments' => 0
					);
					$html["body"] = $this->view("post", $post, true);
				} else {
					$am = $this->model("adminModel");
					$result = $am->checkGalleryCategory($clean["name"], $clean["url"]);
					if(isset($result[0])) {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'That category already exists!'), true);
						$html["script"] = $this->view("admin/addGalleryCatJS", array('parent' => $clean["parent"]), true);
						$form = $this->view("admin/addGalleryCat", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Gallery',
							'content' => $form,
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					} else {
						$am->addGalleryCategory($clean["parent"], $clean["name"], $clean["url"], $clean["title"], $clean["excerpt"], $clean["description"], $clean["live"]);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Gallery',
							'content' => 'Gallery added successfully!',
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					}
				}
			}
			if(!$saveAttempt) {
				$form = array(
					'txtName' => '',
					'txtURL' => '',
					'txtTitle' => '',
					'txtExcerpt' => '',
					'txtDescript' => '',
					'chkLive' => '',
					'errors' => ''
				);
				$form = $this->view("admin/addGalleryCat", $form, true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Add Gallery',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * modify gallery category
	 * update the gallery info in the database
	 */
	function modGalleryCat() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/modGalleryCat';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addGalleryCatJS", array('parent' => ''), true);
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["gallery_cat_id"] = getRequest("whichGallery", "post", FILTER_SANITIZE_NUMBER_FLOAT);
					$cat = $am->getGalleryCatByID($clean["gallery_cat_id"]);
					if(isset($cat[0])) {
						if(substr_count($clean["gallery_cat_id"], ".") > 0) {
							$html["script"] = $this->view("admin/addGalleryCatJS", array('parent' => intval($clean["gallery_cat_id"])), true);
						}
						$data = array(
							'cat_id' => $clean["gallery_cat_id"],
							'txtName' => $cat[0]["name"],
							'txtURL' => $cat[0]["url"],
							'txtTitle' => $cat[0]["title"],
							'txtExcerpt' => $cat[0]["excerpt"],
							'txtDescript' => $cat[0]["description"],
							'chkLive' => $cat[0]["live"],
							'errors' => ''
						);
						$form = $this->view("admin/modGalleryCat", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Gallery',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						throw new Exception("Invalid admin id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["gallery_cat_id"] = getRequest("cat_id", "post", FILTER_SANITIZE_NUMBER_FLOAT);
					$clean["parent"] = getRequest("selNewCat", "post", FILTER_SANITIZE_NUMBER_INT);
					$clean["name"] = getRequest("txtName", "post", FILTER_SANITIZE_STRING);
					$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
					$clean["title"] = getRequest("txtTitle", "post", FILTER_SANITIZE_STRING);
					$clean["excerpt"] = getRequest("txtExcerpt", "post", FILTER_SANITIZE_SPECIAL_CHARS);
					$clean["description"] = getRequest("txtDescript", "post", FILTER_SANITIZE_SPECIAL_CHARS);
					$clean["live"] = getRequest("chkLive", "post", FILTER_SANITIZE_STRING);
					$clean["live"] = $clean["live"] == "on" ? 1 : 0;
					$data = array(
							'cat_id' => $clean["gallery_cat_id"],
							'txtName' => $clean["name"],
							'txtURL' => $clean["url"],
							'txtTitle' => $clean["title"],
							'txtExcerpt' => $clean["excerpt"],
							'txtDescript' => $clean["description"],
							'chkLive' => $clean["live"],
							'errors' => ''
					);
					if($clean["parent"] === "" || $clean["name"] === "" || $clean["title"] === "" || $clean["url"] === "") {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
						$html["script"] = $this->view("admin/addGalleryCatJS", array('parent' => $clean["parent"]), true);
						$form = $this->view("admin/modGalleryCat", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Gallery',
							'content' => $form,
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					} else {
						$am->modGalleryCategory($clean["gallery_cat_id"], $clean["parent"], $clean["name"], $clean["url"], $clean["title"], $clean["excerpt"], $clean["description"], $clean["live"]);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Gallery',
							'content' => 'Gallery modified successfully!',
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$galleries = $am->getGalleryCategories();
				$galleryList = '';
				foreach ($galleries as $gallery) {
					$spacer = (substr_count($gallery["gallery_cat_id"], ".") > 0) ? " &nbsp; . " : "";
					$galleryList .= '<option value="'.$gallery['gallery_cat_id'].'">'.$spacer.$gallery['name'].'</option>\n';
				}				
				$form = $this->view("admin/modGalleryCatSelect", array('galleryList' => $galleryList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Modify Gallery',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * delete gallery
	 * remove galleries from the database
	 */
	function delGalleryCat() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/delGalleryCat';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/delGalleryCatJS", array(), true);
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["gallery_cat_id"] = getRequest("whichGallery", "post", FILTER_SANITIZE_NUMBER_FLOAT);
					if($clean["gallery_cat_id"] === '') {
						throw new Exception("Invalid gallery id.", 500);
					}
					$cat = $am->getGalleryCatByID($clean["gallery_cat_id"]);
					if(isset($cat[0])) {
						$data = array(
							'name' => $cat[0]["name"],
							'gallery_cat_id' => $cat[0]["gallery_cat_id"],
							'deleteCheck' => '',
							'images' => 0,
							'subcats' => 0
						);
						if(strpos($clean["gallery_cat_id"], ".") == 0) {
							$catcount = $am->getSubGalleryCount($clean["gallery_cat_id"]);
							if(isset($catcount[0])) {
								$subcats = intval($catcount[0]['theCount']);
								if($subcats > 0) {
									$data['subcats'] = $subcats;
								}
							}
						}
						$imgcount = $am->getGalleryImgCount($clean["gallery_cat_id"]);
						if(isset($imgcount[0])) {
							$theCount = intval($imgcount[0]['theCount']);
							if($theCount > 0) {
								$data['images'] = $theCount;								
								$data['deleteCheck'] = '<strong>Delete Images? &nbsp;&nbsp;&nbsp;&nbsp; <label><input type="checkbox" name="chkDelete" id="chkDelete" title="Delete images from the server and database?" onclick="checkChange(\''.$theCount.'\');" /> Yes</label></strong>';
							}
						}
						$form = $this->view("admin/delGalleryCat", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Gallery',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						throw new Exception("Invalid gallery id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["gallery_cat_id"] = getRequest("gallery_cat_id", "post", FILTER_SANITIZE_NUMBER_FLOAT);
					$clean["delete"] = getRequest("chkDelete", "post", FILTER_SANITIZE_STRING);
					$clean["delete"] = $clean["delete"] == "on" ? 1 : 0;
					if($clean["gallery_cat_id"] === "") {
						throw new Exception("Invalid gallery id.", 500);
					} else {
						$files = $am->delGalleryAndImgs($clean["gallery_cat_id"], $clean["delete"]);
						$msg = '';
						if($clean["delete"] == 1) {
							$total = count($files);
							$imgs = 0;
							$thumbs = 0;
							$this->library(qoob_types::utility, "upload");
							$this->upload->setDirectory("root");
							foreach ($files as $file) {
								$test = $this->upload->delete($file);
								if($test) {
									$imgs++;
								}
								$num = strrpos($file,".");
								$filename = substr($file, 0, $num);
								$ext = substr($file, $num, strlen($file));
								$test = $this->upload->delete($filename.'_thumb'.$ext);
								if($test) {
									$thumbs++;
								}
							}
							$msg = '<br/><br/>'.$imgs.'&nbsp;of&nbsp;'.$total.'&nbsp;images deleted.<br/>'.$thumbs.'&nbsp;of&nbsp;'.$total.'&nbsp;thumbnails deleted';
						}
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Gallery',
							'content' => 'Galery deleted successfully!'.$msg,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);						
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$galleryList = '';
				$galleries = $am->getGalleryCategories();
				if(count($galleries) > 1) {
					foreach ($galleries as $gallery) {
						if(strtolower($gallery["name"]) != 'uncategorized') {
							$spacer = (substr_count($gallery["gallery_cat_id"], ".") > 0) ? " &nbsp; . " : "";
							$galleryList .= '<option value="'.$gallery['gallery_cat_id'].'">'.$spacer.$gallery['name'].'</option>\n';
						}
					}				
				} else {
					$galleryList .= '<option value="x">There are no galleries</option>\n';
				}

				$form = $this->view("admin/delGalleryCatSelect", array('galleryList' => $galleryList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Delete Gallery',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * add gallery image
	 * add a new image to the gallery
	 */
	function addGalleryImg() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/addGalleryImg';
			$html["meta"] = '<link rel="stylesheet" type="text/css" id="ui-css" href="'.BASE_URL.'style/css/jquery.ui.css" media="screen"/>';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addGalleryImgJS", array('cats' => ''), true);
			$html["jsfiles"] = '<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.ui.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.cal.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.sortable.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.compatibility.js" charset="utf-8"></script>'.PHP_EOL;
			$html["body"] = '';
			$saveAttempt = false;

			if($_POST) {
				$clean["title"] = getRequest("txtTitle", "post", FILTER_SANITIZE_STRING);
				$clean["subtitle"] = getRequest("txtSubTitle", "post", FILTER_SANITIZE_STRING);
				$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
				$clean["excerpt"] = getRequest("txtExcerpt", "post", FILTER_SANITIZE_STRING);
				$clean["description"] = getRequest("txtDescript", "post", FILTER_SANITIZE_STRING);
				$clean["live"] = getRequest("chkLive", "post", FILTER_SANITIZE_STRING);
				$clean["live"] = $clean["live"] == "on" ? 1 : 0;
				$cats = getRequest("txtCats", "post");
				$clean["cats"] = is_array($cats) ? implode(",", $cats) : $cats; 
				$saveAttempt = true;

				//---nullbyte filename exploit countermeasue
				$clean['theFile'] = str_replace(chr(0), '', $_FILES["theFile"]["name"]);
				$clean['theFile'] = str_replace("\0", '', $clean['theFile']);

				$data = array(
					'txtTitle' => $clean["title"],
					'txtSubTitle' => $clean["subtitle"],
					'txtURL' => $clean["url"],
					'txtExcerpt' => $clean["excerpt"],
					'txtDescript' => $clean["description"],
					'chkLive' => $clean["live"],
					'errors' => ''
				);
				if($clean['theFile'] === "" || $clean["cats"] === "" || $clean["title"] === ""|| $clean["subtitle"] === "" || $clean["url"] === "") {
					$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
					$html["script"] = $this->view("admin/addGalleryImgJS", array('cats' => $clean["cats"]), true);
					$form = $this->view("admin/addGalleryImg", $data, true);
					$post = array(
						'mainCat' => '',
						'url' => '',
						'title' => 'Backdoor',
						'subtitle' => 'Add Image',
						'content' => $form,
						'comments' => 0
					);
					$html["body"] = $this->view("post", $post, true);
				} else {
					$am = $this->model("adminModel");
					$result = $am->checkGalleryImg($clean["url"]);
					if(isset($result[0])) {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'That image URL already exists!'), true);
						$html["script"] = $this->view("admin/addGalleryImgJS", array('cats' => $clean["cats"]), true);
						$form = $this->view("admin/addGalleryImg", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Image',
							'content' => $form,
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					} else {
						if($_FILES["theFile"]["error"] > 0) {
							$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Your image is corrupt or became corrupt in upload!'), true);
							$html["script"] = $this->view("admin/addGalleryImgJS", array('cats' => $clean["cats"]), true);
							$form = $this->view("admin/addGalleryImg", $data, true);
							$post = array(
								'mainCat' => '',
								'url' => '',
								'title' => 'Backdoor',
								'subtitle' => 'Add Image',
								'content' => $form,
								'comments' => 0
							);
							$html["body"] = $this->view("post", $post, true);							
						} else {
							$this->library(qoob_types::utility, "upload");
							$this->upload->setMIMES(array('image/jpeg','image/pjpeg','image/jpg','image/x-jps','image/png','image/tiff','image/x-tiff','image/gif','image/bmp'));
							if(!$this->upload->testMIME($_FILES["theFile"]["type"])) {
								$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'The file you selected was not an image!'), true);
								$html["script"] = $this->view("admin/addGalleryImgJS", array('cats' => $clean["cats"]), true);
								$form = $this->view("admin/addGalleryImg", $data, true);
								$post = array(
									'mainCat' => '',
									'url' => '',
									'title' => 'Backdoor',
									'subtitle' => 'Add Image',
									'content' => $form,
									'comments' => 0
								);
								$html["body"] = $this->view("post", $post, true);
							} else {
								//save image
								$this->upload->setDirectory("root");
								$ext = strtolower($this->upload->getExtention($clean['theFile']));
								$name = $clean["url"];
								while($this->upload->exists($name.".".$ext)) {
									$rand = str_split(md5(microtime()), 5);
									$name .= $rand[0];
								}
								$clean["filename"] = $name.".".$ext;
								$clean["thumbname"] = $name."_thumb.".$ext;
								$this->upload->file($_FILES["theFile"]["tmp_name"], $clean["filename"]);
								//create thumbnail
								$target_path = QOOB_ROOT.SLASH."style".SLASH."img".SLASH."projects".SLASH.$clean["filename"];
								/**
								 * @todo thumbnail size needs to be user defined somewhere...
								 */
								$size = 300;
								switch ($ext) {
									case 'png':
										$img = imagecreatefrompng($target_path);
										$createIMG = 'ImagePNG';
								        ImageAlphaBlending($img,true); 
								        ImageSaveAlpha($img,true); 										
									break;
									case 'gif':
										$img = imagecreatefromgif($target_path);
										$createIMG = 'ImageGIF';
								        $transparent_index = ImageColorTransparent($img); 
								        if($transparent_index!=(-1)) $transparent_color = ImageColorsForIndex($img,$transparent_index);
									break;
									case 'jpg':
									case 'jpeg':
										$img = imagecreatefromjpeg($target_path); 
										$createIMG = 'ImageJPEG';
									break;									
									default:
										throw new Exception("Failed to create thumbnail.<br/>Invalid image type.", 500);
									break;
								}
							    list($w,$h) = GetImageSize($target_path); 
							    if( $w==0 or $h==0 ) throw new Exception("Image size is zero.", 500);
							    $percent = $size / (($w>$h)?$w:$h); 
						        $nw = intval($w*$percent); 
						        $nh = intval($h*$percent); 
						        $thumb = ImageCreateTrueColor($nw,$nh); 
						        if($ext=='png') { 
						            ImageAlphaBlending($thumb,false); 
						            ImageSaveAlpha($thumb,true); 
						        } 
						        if(!empty($transparent_color)) { 
						            $transparent_new = ImageColorAllocate($thumb, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']); 
						            $transparent_new_index = ImageColorTransparent($thumb,$transparent_new);
						            ImageFill($thumb, 0,0, $transparent_new_index);
						        } 
						        if(ImageCopyResized($thumb,$img, 0,0,0,0, $nw,$nh, $w,$h)) { 
						            ImageDestroy($img); 
						            $img = $thumb;						            
						        } 
						        ob_start();
						        $createIMG($img);
						        $thumbdata = ob_get_clean();
								$this->upload->writeFile($clean["thumbname"], $thumbdata);
								ImageDestroy($img); 
								//add to database
								$id = $am->addGalleryImg($clean["url"], $clean["filename"], $clean["title"], $clean["subtitle"], $clean["excerpt"], $clean["description"], $clean["live"]);
								if(!empty($clean["cats"])) {
									$cats = explode(",", $clean["cats"]);
									foreach($cats as $cat) {
										$am->addGalleryImgMeta($id, "category", $cat);
									}
								}
								$post = array(
									'mainCat' => '',
									'url' => '',
									'title' => 'Backdoor',
									'subtitle' => 'Add Image',
									'content' => 'Gallery image uploaded successfully!',
									'comments' => 0
								);
								$html["body"] = $this->view("post", $post, true);
							}
						}
					}
				}
			}
			if(!$saveAttempt) {
				$form = array(
					'txtTitle' => '',
					'txtSubTitle' => '',
					'txtURL' => '',
					'txtExcerpt' => '',
					'txtDescript' => '',
					'chkLive' => '',
					'errors' => ''
				);
				$form = $this->view("admin/addGalleryImg", $form, true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Add Image',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * modify gallery image
	 * update the gallery image info in the database
	 */
	function modGalleryImg() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/modGalleryImg';
			$html["meta"] = '<link rel="stylesheet" type="text/css" id="ui-css" href="'.BASE_URL.'style/css/jquery.ui.css" media="screen"/>';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addGalleryImgJS", array('cats' => ''), true);
			$html["jsfiles"] = '<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.ui.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.cal.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.sortable.js" charset="utf-8"></script>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/jquery.bsmselect.compatibility.js" charset="utf-8"></script>'.PHP_EOL;
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["image_id"] = getRequest("selectImgID", "post", FILTER_SANITIZE_NUMBER_INT);
					$img = $am->getGalleryImgAndMetaByID($clean["image_id"]);
					if(isset($img[0])) {
						$data = array(
							'image_id' => $clean["image_id"],
							'txtURL' => $img[0]["url"],
							'theFile' => $img[0]["filename"],
							'txtTitle' => $img[0]["title"],
							'txtSubTitle' => $img[0]["subtitle"],
							'txtExcerpt' => $img[0]["excerpt"],
							'txtDescript' => $img[0]["description"],
							'chkLive' => $img[0]["live"],
							'errors' => ''
						);
						$form = $this->view("admin/modGalleryImg", $data, true);
						$html["script"] = $this->view("admin/addGalleryImgJS", array('cats' => $img[0]["cats"]), true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Image',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						throw new Exception("Invalid image id.", 500);
					}
				} else {
					$clean["image_id"] = getRequest("image_id", "post", FILTER_SANITIZE_NUMBER_INT);
					$clean["theFile"] = getRequest("theFile", "post", FILTER_SANITIZE_STRING);
					$clean["title"] = getRequest("txtTitle", "post", FILTER_SANITIZE_STRING);
					$clean["subtitle"] = getRequest("txtSubTitle", "post", FILTER_SANITIZE_STRING);
					$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
					$clean["excerpt"] = getRequest("txtExcerpt", "post", FILTER_SANITIZE_STRING);
					$clean["description"] = getRequest("txtDescript", "post", FILTER_SANITIZE_STRING);
					$clean["live"] = getRequest("chkLive", "post", FILTER_SANITIZE_STRING);
					$clean["live"] = $clean["live"] == "on" ? 1 : 0;
					$cats = getRequest("txtCats", "post");
					$clean["cats"] = is_array($cats) ? implode(",", $cats) : $cats; 
					$saveAttempt = true;

					$data = array(
						'image_id' => $clean["image_id"],						
						'theFile' => $clean["theFile"],
						'txtTitle' => $clean["title"],
						'txtSubTitle' => $clean["subtitle"],
						'txtURL' => $clean["url"],
						'txtExcerpt' => $clean["excerpt"],
						'txtDescript' => $clean["description"],
						'chkLive' => $clean["live"],
						'errors' => ''
					);
					if($clean["cats"] === "" || $clean["title"] === ""|| $clean["subtitle"] === "" || $clean["url"] === "") {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
						$html["script"] = $this->view("admin/addGalleryImgJS", array('cats' => $clean["cats"]), true);
						$form = $this->view("admin/modGalleryImg", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Image',
							'content' => $form,
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					} else {
						$oldpost = $am->getGalleryImgAndMetaByID($clean["image_id"]);
						if($clean["url"] != $oldpost[0]["url"]) {
							$result = $am->checkGalleryImg($clean["url"]);
							if(isset($result[0])) {
								$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'That URL is already in use!'), true);
								$html["script"] = $this->view("admin/addGalleryImgJS", array('cats' => $clean["cats"]), true);
								$form = $this->view("admin/modGalleryImg", $data, true);
								$post = array(
									'mainCat' => '',
									'url' => '',
									'title' => 'Backdoor',
									'subtitle' => 'Modify Image',
									'content' => $form,
									'comments' => 0
								);			
								$html["body"] = $this->view("post", $post, true);
							}
						} 
						if($data["errors"] == "") {
							//---modify image
							$am->modGalleryImg($clean["image_id"], $clean["url"], $clean["title"], $clean["subtitle"], $clean["excerpt"], $clean["description"], $clean["live"]);
							//---check categories
							$this->library(qoob_types::utility, "custom_diff");
							$catTest = $this->custom_diff->run($oldpost[0]["cats"], $clean["cats"]);
							if(isset($catTest["add"])) {
								foreach($catTest["add"] as $newcat) {
									$am->addGalleryImgMeta($clean["image_id"], "category", $newcat);
								}
							}
							if(isset($catTest["del"])) {
								foreach($catTest["del"] as $oldcat) {
									$am->delGalleryImgMeta($clean["image_id"], "category", $oldcat);
								}
							}
							$post = array(
								'mainCat' => '',
								'url' => '',
								'title' => 'Backdoor',
								'subtitle' => 'Modify Image',
								'content' => 'Image modified successfully!',
								'comments' => 0
							);
							$html["body"] = $this->view("post", $post, true);
						}
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$galleries = $am->getGalleryCategories();
				$galleryList = '';
				foreach ($galleries as $gallery) {
					$spacer = (substr_count($gallery["gallery_cat_id"], ".") > 0) ? " &nbsp; . " : "";
					$galleryList .= '<option value="'.$gallery['gallery_cat_id'].'">'.$spacer.$gallery['name'].'</option>\n';
				}				
				$form = $this->view("admin/modGalleryImgSelect", array('galleryList' => $galleryList, 'errors' => ''), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Modify Image',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	/**
	 * delete image 
	 * remove images from the gallery
	 */
	function delGalleryImg() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/delGalleryImg';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/delGalleryImgJS", array(), true);
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["image_id"] = getRequest("selectImgID", "post", FILTER_SANITIZE_NUMBER_INT);
					$img = $am->getGalleryImgAndMetaByID($clean["image_id"]);
					if(isset($img[0])) {
						$data = array(
							'image_id' => $clean["image_id"],
							'theFile' => $img[0]["filename"],
						);
						$form = $this->view("admin/delGalleryImg", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Image',
							'content' => $form,
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					} else {
						throw new Exception("Invalid image id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["image_id"] = getRequest("image_id", "post", FILTER_SANITIZE_NUMBER_INT);
					if($clean["image_id"] === "") {
						throw new Exception("Invalid post id.", 500);
					} else {
						$img = $am->getGalleryImgAndMetaByID($clean["image_id"]);
						if(!isset($img[0])) {
							throw new Exception("Invalid post id.", 500);
						} else {
							$cats = explode(',', $img[0]['cats']);
							foreach ($cats as $cat) {
								$am->delGalleryImgMeta($clean["image_id"], "category", $cat);								
							}
							$am->delGalleryImg($clean["image_id"]);
							$this->library(qoob_types::utility, "upload");
							$this->upload->setDirectory("root");
							$test = $this->upload->delete($img[0]['filename']);
							$msg = '';
							if(!$test) {
								$msg = "Failed to delete the image from the server.";
							}
							$num = strrpos($img[0]['filename'],".");
							$file = substr($img[0]['filename'], 0, $num);
							$ext = substr($img[0]['filename'], $num, strlen($img[0]['filename']));
							$test = $this->upload->delete($file.'_thumb'.$ext);
							if(!$test) {
								$msg = "<br/>Failed to delete thumbnail from the server.";
							}							
							$post = array(
								'mainCat' => '',
								'url' => '',
								'title' => 'Backdoor',
								'subtitle' => 'Delete Image',
								'content' => 'Gallery image deleted successfully!<br/>'.$msg,
								'comments' => 0
							);			
							$html["body"] = $this->view("post", $post, true);						
						}
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$galleries = $am->getGalleryCategories();
				$galleryList = '';
				foreach ($galleries as $gallery) {
					$spacer = (substr_count($gallery["gallery_cat_id"], ".") > 0) ? " &nbsp; . " : "";
					$galleryList .= '<option value="'.$gallery['gallery_cat_id'].'">'.$spacer.$gallery['name'].'</option>\n';
				}				
				$form = $this->view("admin/delGalleryImgSelect", array('galleryList' => $galleryList, 'errors' => ''), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Delete Image',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
//___________________________________________________________________________________________________________
//                                                                                                       code
	/**
	 * add code
	 * add a git repo to the database
	 */
	function addCode() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/addCode';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/addCodeJS", array(), true);
			$saveAttempt = false;

			if($_POST) {
				$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
				$clean["repo"] = getRequest("txtRepo", "post", FILTER_SANITIZE_STRING);
				$clean["name"] = getRequest("txtName", "post", FILTER_SANITIZE_STRING);
				$clean["subtitle"] = getRequest("txtSubTitle", "post", FILTER_SANITIZE_STRING);
				$clean["description"] = getRequest("txtDescription", "post", FILTER_SANITIZE_SPECIAL_CHARS);
				$clean["readme"] = getRequest("txtReadMe", "post", FILTER_SANITIZE_SPECIAL_CHARS);
				$saveAttempt = true;
				$data = array(
					'errors' => '',
					'txtRepo' => $clean["repo"],
					'txtName' => $clean["name"],
					'txtSubTitle' => $clean["subtitle"],
					'txtURL' => $clean["url"],
					'txtDescription' => $clean["description"],
					'txtReadMe' => $clean["readme"],
				);
				if($clean["url"] === "" or $clean["repo"] === "" or $clean["name"] === "" or $clean["description"] === "" or $clean["readme"] === "") {
					$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
					$form = $this->view("admin/addCode", $data, true);
					$post = array(
						'mainCat' => '',
						'url' => '',
						'title' => 'Backdoor',
						'subtitle' => 'Add Code',
						'content' => $form,
						'comments' => 0
					);			
					$html["body"] = $this->view("post", $post, true);
				} else {
					$am = $this->model("adminModel");
					$result = $am->checkCodeRoute($clean["url"]);
					if(isset($result[0])) {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'That URL is already in use!'), true);
						$form = $this->view("admin/addCode", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Code',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						$am->addCode($clean);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Add Code',
							'content' => 'New Git Repo added successfully!',
							'comments' => 0
						);
						$html["body"] = $this->view("post", $post, true);
					}
				}
			}
			if(!$saveAttempt) {
				$data = array(
					'txtRepo' => '',
					'txtName' => '',
					'txtSubTitle' => '',
					'txtURL' => '',
					'txtDescription' => '',
					'txtReadMe' => '',
					'errors' => '',
				);
				$form = $this->view("admin/addCode", $data, true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Add Code',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);
			}
			$this->view("pixelgraff", $html);
		}
	}
	function modCode() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/modCode';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/modCodeJS", array(), true);
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["git_id"] = getRequest("whichRepo", "post", FILTER_SANITIZE_NUMBER_INT);
					$repo = $am->getCode($clean["git_id"]);
					if(isset($repo[0])) {
						$data = array(
							'errors' => '',
							'txtRepo' => $repo[0]["repo"],
							'txtName' => $repo[0]["name"],
							'txtSubTitle' => $repo[0]["subtitle"],
							'txtURL' => $repo[0]["url"],
							'txtDescription' => $repo[0]["description"],
							'txtReadMe' => $repo[0]["readme"],
							'git_id' => $repo[0]["git_id"],
						);
						$form = $this->view("admin/modCode", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Code',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);						
					} else {
						throw new Exception("Invalid code id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["git_id"] = getRequest("git_id", "post", FILTER_SANITIZE_NUMBER_INT);
					$clean["url"] = getRequest("theRealURL", "post", FILTER_SANITIZE_STRING);
					$clean["repo"] = getRequest("txtRepo", "post", FILTER_SANITIZE_STRING);
					$clean["name"] = getRequest("txtName", "post", FILTER_SANITIZE_STRING);
					$clean["subtitle"] = getRequest("txtSubTitle", "post", FILTER_SANITIZE_STRING);
					$clean["description"] = getRequest("txtDescription", "post", FILTER_SANITIZE_SPECIAL_CHARS);
					$clean["readme"] = getRequest("txtReadMe", "post", FILTER_SANITIZE_SPECIAL_CHARS);
					$data = array(
							'errors' => '',
							'txtRepo' => $clean["repo"],
							'txtName' => $clean["name"],
							'txtSubTitle' => $clean["subtitle"],
							'txtURL' => $clean["url"],
							'txtDescription' => $clean["description"],
							'txtReadMe' => $clean["readme"],
							'git_id' => $clean["git_id"]
					);
					if($clean["url"] === "" or $clean["repo"] === "" or $clean["name"] === "" or $clean["description"] === "" or $clean["readme"] === "") {
						$data["errors"] = $this->view("admin/errorBubble", array('title' => 'Error!', 'msg' => 'Please complete the entire form!'), true);
						$form = $this->view("admin/modCode", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Modify Code',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);
					} else {
						if($data["errors"] == '') {
							$am->modCode($clean);
							$post = array(
								'mainCat' => '',
								'url' => '',
								'title' => 'Backdoor',
								'subtitle' => 'Modify Code',
								'content' => 'Your Git Repo has been modified successfully!',
								'comments' => 0
							);			
							$html["body"] = $this->view("post", $post, true);
						}
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$codes = $am->getCodes();
				$repoList = '';
				if(isset($codes[0])) {
					foreach ($codes as $repo) {
						$repoList .= '<option value="'.$repo['git_id'].'">'.$repo['url'].'</option>\n';
					}				
				} else {
					$repoList = '<option value="x">No Repositories</option>\n';
				}
				$form = $this->view("admin/modCodeSelect", array('repoList' => $repoList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Modify Code',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);						
			}
			$this->view("pixelgraff", $html);
		}
	}
	function delCode() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/delCode';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/delCodeJS", array(), true);
			$html["body"] = '';
			$saveAttempt = false;
			$loadAttempt = false;

			$am = $this->model("adminModel");

			if($_POST) {
				$action = getRequest("action", "post", FILTER_SANITIZE_STRING);
				if($action == "load") {
					$loadAttempt = true;
					$clean["git_id"] = getRequest("whichRepo", "post", FILTER_SANITIZE_NUMBER_INT);
					$repo = $am->getCode($clean["git_id"]);
					if(isset($repo[0])) {
						$data = array(
							'url' => $repo[0]["url"],
							'git_id' => $clean["git_id"],
						);
						$form = $this->view("admin/delCode", $data, true);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Code',
							'content' => $form,
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);	
					} else {
						throw new Exception("Invalid code id.", 500);
					}
				} else {
					$saveAttempt = true;
					$clean["git_id"] = getRequest("git_id", "post", FILTER_SANITIZE_NUMBER_INT);
					if($clean["git_id"] === "") {
						throw new Exception("Invalid code id.", 500);
					} else {
						$am->delCode($clean["git_id"]);
						$post = array(
							'mainCat' => '',
							'url' => '',
							'title' => 'Backdoor',
							'subtitle' => 'Delete Code',
							'content' => 'Your Git Repo as been deleted successfully!',
							'comments' => 0
						);			
						$html["body"] = $this->view("post", $post, true);	
					}
				}
			}
			if(!$saveAttempt && !$loadAttempt) {
				$codes = $am->getCodes();
				$repoList = '';
				if(isset($codes[0])) {
					foreach ($codes as $repo) {
						$repoList .= '<option value="'.$repo['git_id'].'">'.$repo['url'].'</option>\n';
					}				
				} else {
					$repoList = '<option value="x">No Repositories</option>\n';
				}
				$form = $this->view("admin/delCodeSelect", array('repoList' => $repoList), true);
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Backdoor',
					'subtitle' => 'Delete Code',
					'content' => $form,
					'comments' => 0
				);			
				$html["body"] = $this->view("post", $post, true);						
			}
			$this->view("pixelgraff", $html);
		}
	}
//___________________________________________________________________________________________________________
//                                                                                                      stats
	function stats() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$html["title"] = 'backdoor/stats';
			$html["meta"] = '';
			$html["sidebar"] = $this->view("admin/sidebar", array(), true).$this->view("blog/sidebar_qr", array(), true);
			$html["selected"] = '';
			$html["script"] = $this->view("admin/statsJS", array(), true);
			$html["body"] = '';
			$form = $this->view("admin/stats", array(), true);
			$post = array(
				'mainCat' => '',
				'url' => '',
				'title' => 'Backdoor',
				'subtitle' => 'View Statistics',
				'content' => $form,
				'comments' => 0
			);			
			$html["body"] = $this->view("post", $post, true);
			$this->view("pixelgraff", $html);
		}
	}
//___________________________________________________________________________________________________________
//                                                                                                       ajax	
	/**
	 * ajax
	 * dynamic javascript backend
	 */
	function ajax() {
		if(!$this->session->validate()){
			header("location: ".QOOB_DOMAIN.QOOB_CONTROLLER_URL."/");
		} else {
			$action 	= getRequest("action", "request", FILTER_SANITIZE_STRING);
			$this->stats = false;

			switch ($action) {
				case "inflection":
					$str	= getRequest("str", "post", FILTER_SANITIZE_STRING);
					$type	= getRequest("type", "post", FILTER_SANITIZE_STRING);

					$this->library(qoob_types::utility, "inflector");
					if($str === "") {
						die("error");
					} else {
						switch ($type) {
							case "camel":
								$url = $this->inflector->camelize($str);
							break;
							case "underscore":
								$url = $this->inflector->underscore($str);
							break;
							default:
								$url = $this->inflector->underscore($str);
							break;
						}
						die($url);
					}
				break;
				case "addTag":
					$name	= getRequest("name", "post", FILTER_SANITIZE_STRING);
					$url	= getRequest("url", "post", FILTER_SANITIZE_STRING);
					
					if($name == "" || $url == "") {
						die("missing");
					}
					$cat = $this->model("adminModel");
					$check = $cat->checkBlogTag($name, $url);
					if(count($check) > 0) {
						die("used");
					}
					$cat->addBlogTag($name, $url);
					die("success");					
				break;
				case "getTags":
					$tag	= $this->model("adminModel");
					$tags	= $tag->getBlogTags();
					if(isset($tags[0])) {
						$this->library(qoob_types::utility, "cloud");
						$this->cloud->setMax(200);
						$this->cloud->setMin(90);
						$html = $this->cloud->generate($tags);
					} else {
						$html = 'No tags found.';
					}
					die($html);
				break;				
				case "addCategory":
					$name	= getRequest("name", "post", FILTER_SANITIZE_STRING);
					$url	= getRequest("url", "post", FILTER_SANITIZE_STRING);
					$parent	= getRequest("parent", "post", FILTER_SANITIZE_STRING);
						
					if($name == "" || $url == "" || $parent == "") {
						die("missing");
					}
					$cat = $this->model("adminModel");
					/**
					 * @todo perhaps allow for subcat to have the same name as a main cat?
					 * 		 possibility for collisions...? :P
					 */
					$check = $cat->checkBlogCategory($name, $url);
					if(count($check) > 0) {
						die("used");
					}
					$cat->addBlogCategory($name, $url, $parent);
					die("success");
				break;
				case "getCategories":
					$type	= getRequest("type", "post", FILTER_SANITIZE_STRING);
					$cat	= $this->model("adminModel");
					$cats	= $cat->getBlogCategories();
					
					$i = 0;
					if($type == "new") {
						$html = '<select name="selNewCat" id="selNewCat" class="inputs"><option value="0">root category</option>';
						while ($i < count($cats)) {
							if(substr_count($cats[$i]["blog_cat_id"], ".") == 0) {
								if($cats[$i]["name"] != "uncategorized") {
									$html.='<option value="'.$cats[$i]["blog_cat_id"].'">'.$cats[$i]["name"].'</option>';
								}
							}
							$i++;
						}						
					} else {
						$html = '<select name="txtCats[]" id="selMainCat" multiple="multiple" title="Select Categories" class="inputs">'; 						
						while ($i < count($cats)) {
							$spacer = (substr_count($cats[$i]["blog_cat_id"], ".") > 0) ? " &nbsp; . " : "";
							$html.='<option value="'.$cats[$i]["blog_cat_id"].'">'.$spacer.$cats[$i]["name"].'</option>';
							$i++;
						}						
					}
					$html.= "</select>";
					die($html);
				break;
				case "getGalleryCategories":
					$type	= getRequest("type", "post", FILTER_SANITIZE_STRING);
					$cat	= $this->model("adminModel");
					$cats	= $cat->getGalleryCategories();
					$i = 0;
					if($type == "new") {
						$html = '<select name="selNewCat" id="selNewCat" class="inputs"><option value="0">Root Category</option>';
						while ($i < count($cats)) {
							if(substr_count($cats[$i]["gallery_cat_id"], ".") == 0) {
								if(strtolower($cats[$i]["name"]) != "uncategorized") {
									$html.='<option value="'.$cats[$i]["gallery_cat_id"].'">'.$cats[$i]["name"].'</option>';
								}
							}
							$i++;
						}						
					} else {
						$html = '<select name="txtCats[]" id="selMainCat" multiple="multiple" title="Select Categories" class="inputs">'; 						
						while ($i < count($cats)) {
							$spacer = (substr_count($cats[$i]["gallery_cat_id"], ".") > 0) ? " &nbsp; . " : "";
							$html.='<option value="'.$cats[$i]["gallery_cat_id"].'">'.$spacer.$cats[$i]["name"].'</option>';
							$i++;
						}						
					}
					$html.= "</select>";
					die($html);
				break;
				case "getGalleryImages":
					$id		= $name	= getRequest("cat_id", "post", FILTER_SANITIZE_NUMBER_FLOAT);
					$img	= $this->model("adminModel");
					$imgs	= $img->getGalleryImgByCat($id);
					if(!isset($imgs[0])) {
						$html = '<select name="selectImgID" id="selectImgID" class="inputs"><option value="0">No images in this category.</option></select>';
					} else {
						$html = '<select name="selectImgID" id="selectImgID" class="inputs"><option value="0">Select an image...</option>';
						$i = 0;
						while ($i < count($imgs)) {
							$html.='<option value="'.$imgs[$i]["image_id"].'">'.$imgs[$i]["url"].'</option>';
							$i++;
						}
						$html .= '</select>';
					}
					die($html);
				break;
				case "stats":
					$type 	= getRequest("type", "request", FILTER_SANITIZE_STRING);
					$sm = $this->model("statsModel");
					switch ($type) {
						case 'visits':
							$range 	= getRequest("range", "request", FILTER_SANITIZE_NUMBER_INT);
							$view 	= getRequest("view", "request", FILTER_SANITIZE_NUMBER_INT);
							$html = "";
							$visitsType = getRequest("visitsType", "request", FILTER_SANITIZE_STRING);
							if($visitsType == "undefined"){
								$visitsType = 1;
							}							
							switch ($range) {
								case 1:
									$starttime = strtotime('-1 month');
									break;
								case 2:
									$starttime = strtotime('-6 months');
									break;
								case 3:
									$starttime = strtotime('-1 year');
									break;
								case 4:
								default:
									$starttime = 0;
									break;
							}
							$visits = $sm->visits($starttime, time());
							if(!isset($visits[0])) {
								die('<div class="row titleRow"><strong>No data to display</strong></div>');
							}
							$unique_visits = count($visits);
							$total_visits = 0;
							foreach($visits as $visit){
								$total_visits += intval($visit['total']);
							}
							$visits_div = $sm->visits_div($starttime, time());
							$int2hour = array(
								0 => "12pm - 1am",
								1 => "1 - 2am",
								2 => "2 - 3am",
								3 => "3 - 4am",
								4 => "4 - 5am",
								5 => "5 - 6am",
								6 => "6 - 7am",
								7 => "7 - 8am",
								8 => "8 - 9am",
								9 => "9 - 10am",
								10 => "10 - 11am",
								11 => "11 - 12pm",
								12 => "12 - 1pm",
								13 => "1 - 2pm",
								14 => "2 - 3pm",
								15 => "3 - 4pm",
								16 => "4 - 5pm",
								17 => "5 - 6pm",
								18 => "6 - 7pm",
								19 => "7 - 8pm",
								20 => "8 - 9pm",
								21 => "9 - 10pm",
								22 => "10 - 11pm",
								23 => "11pm - 12am"
							);
							$int2wkday = array(
								0 => "Sun",
								1 => "Mon",
								2 => "Tues",
								3 => "Wed",
								4 => "Thu",
								5 => "Fri",
								6 => "Sat"									
							);
							$s1 = "";
							$s2 = "";
							$s3 = "";
							$s4 = "";
							switch ($visitsType){
								case 1:
									$s1 = 'selected="selected"';
									break;
								case 2:
									$s2 = 'selected="selected"';
									break;
								case 3:
									$s3 = 'selected="selected"';
									break;
								case 4:
									$s4 = 'selected="selected"';
									break;
							}
							$html = '<div class="row titleRow">
							<div class="lbl"><strong>Total visits</strong></div>
							<div class="inputs"><strong>Unique visits</strong></div>
							<br/>
							</div>';
							$html .= '<div class="row">
							<div class="lbl">'.number_format($total_visits).'</div>
							<div class="inputs">'.number_format($unique_visits).'</div>
							</div>';
							$html .= '<select name="visitsType" id="visitsType" onchange="getVisits()">
							<option value="1" '.$s1.'>Hours of the Day</option>
							<option value="2" '.$s2.'>Days of the Week</option>
							<option value="3" '.$s3.'>Days of the Month</option>
							<option value="4" '.$s4.'>Months of the Year</option>
							</select>';
							
							if($view == 1) {
								switch ($visitsType){
									case 1:
										$hours = array();
										if(!isset($visits_div[0])) {
											die('<div class="row titleRow"><strong>No data to display</strong></div>');
										}
										foreach($visits_div as $row){
											$epoch = intval($row['date']);
											$ipaddress = $row['ipaddress'];
											$hour = intval(strftime('%H', $epoch));
											if(!array_key_exists($hour, $hours)){
												$hours[$hour] = array(1,array($ipaddress));
											} else {
												$hours[$hour][0] += 1;
												if(!in_array($ipaddress, $hours[$hour][1])){
													array_push($hours[$hour][1], $ipaddress);
												}
											}
										}
										ksort($hours);
										$html .= '<div class="row titleRow">
										<div class="lbl"><strong>Hour of day</strong></div>
										<div class="inputs"><strong>Total / Unique</strong></div>
										<br/>
										</div>';
										foreach($hours as $hour => $totals){
											$html .= '<div class="row">
											<div class="lbl">'.$int2hour[$hour].'</div>
											<div class="inputs">'.number_format($totals[0])." / ".count($totals[1]).'</div>
											</div>';
										}
										break;
									case 2:
										$weekdays = array();
										if(!isset($visits_div[0])) {
											die('<div class="row titleRow"><strong>No data to display</strong></div>');
										}
										foreach($visits_div as $row){
											$epoch = intval($row['date']);
											$weekday = intval(strftime('%w', $epoch));
											$ipaddress = $row['ipaddress'];
											if(!array_key_exists($weekday, $weekdays)){
												$weekdays[$weekday] = array(1,array($ipaddress));
											} else {
												$weekdays[$weekday][0] += 1;
												if(!in_array($ipaddress, $weekdays[$weekday][1])){
													array_push($weekdays[$weekday][1], $ipaddress);
												}
											}
										}
										ksort($weekdays);
										$html .= '<div class="row titleRow">
										<div class="lbl"><strong>Day of the Week</strong></div>
										<div class="inputs"><strong>Total / Unique</strong></div>
										<br/>
										</div>';
										foreach($weekdays as $weekday => $totals){
											$html .= '<div class="row">
											<div class="lbl">'.$int2wkday[$weekday].'</div>
											<div class="inputs">'.number_format($totals[0])." / ".count($totals[1]).'</div>
											</div>';
										}
										break;
									case 3:
										$monthdays = array();
										if(!isset($visits_div[0])) {
											die('<div class="row titleRow"><strong>No data to display</strong></div>');
										}
										foreach($visits_div as $row){
											$epoch = intval($row['date']);
											$monthday = intval(strftime('%d', $epoch));
											$ipaddress = $row['ipaddress'];
											if(!array_key_exists($monthday, $monthdays)){
												$monthdays[$monthday] = array(1,array($ipaddress));
											} else {
												$monthdays[$monthday][0] += 1;
												if(!in_array($ipaddress, $monthdays[$monthday][1])){
													array_push($monthdays[$monthday][1], $ipaddress);
												}
											}
										}
										ksort($monthdays);
										$html .= '<div class="row titleRow">
										<div class="lbl"><strong>Day of the Month</strong></div>
										<div class="inputs"><strong>Total / Unique</strong></div>
										<br/>
										</div>';
										foreach($monthdays as $monthday => $totals){
											$html .= '<div class="row">
											<div class="lbl">'.$monthday.'</div>
											<div class="inputs">'.number_format($totals[0])." / ".count($totals[1]).'</div>
											</div>';
										}
										break;
									case 4:
										$months = array();
										if(!isset($visits_div[0])) {
											die('<div class="row titleRow"><strong>No data to display</strong></div>');
										}
										foreach($visits_div as $row){
											$epoch = intval($row['date']);
											$month = strftime('%Y', $epoch) ."-". strftime('%m', $epoch);
											//$year = intval(strftime('%Y', $epoch));
											$ipaddress = $row['ipaddress'];
											if(!array_key_exists($month, $months)){
												$months[$month] = array(1,array($ipaddress));
											} else {
												$months[$month][0] += 1;
											}
											if(!in_array($ipaddress, $months[$month][1])){
												array_push($months[$month][1], $ipaddress);
											}
										}
										ksort($months);
										$html .= '<div class="row titleRow">
										<div class="lbl"><strong>Months of the Year</strong></div>
										<div class="inputs"><strong>Total / Unique</strong></div>
										<br/>
										</div>';
										foreach($months as $month => $totals){
											$html .= '<div class="row">
											<div class="lbl">'.$month.'</div>
											<div class="inputs">'.number_format($totals[0])." / ".count($totals[1]).'</div>
											</div>';
										}
										break;
									default:
								}
							} else {
								switch ($visitsType){
									case 1:
									//hours in the day
										$hours = array();
										if(!isset($visits_div[0])) {
											die('<div class="row titleRow"><strong>No data to display</strong></div>');
										}
										foreach($visits_div as $row){
											$epoch = intval($row['date']);
											$ipaddress = $row['ipaddress'];
											$hour = intval(strftime('%H', $epoch));
											if(!array_key_exists($hour, $hours)){
												$hours[$hour] = array(1,array($ipaddress));
											} else {
												$hours[$hour][0] += 1;
												if(!in_array($ipaddress, $hours[$hour][1])){
													array_push($hours[$hour][1], $ipaddress);
												}
											}
										}
										ksort($hours);
										$this->library(qoob_types::utility, "pChart", "pCharts/");
										$DataSet = new pData();
										$Serie1 = array();
										$Serie2 = array();
										$Serie3 = array();
										$hourkeys = array_keys($hours);
										foreach($hourkeys as $key){
											array_push($Serie3, $int2hour[$key]); 
										}										
										foreach($hours as $hour => $value){
											array_push($Serie2, $value[0]);			// total
											array_push($Serie1, count($value[1]));	// unique
										}

										$DataSet->AddPoint($Serie1,"Serie1");
										$DataSet->AddPoint($Serie2,"Serie2");
										$DataSet->AddPoint($Serie3,"Serie3");
										$DataSet->AddAllSeries();  
						 				$DataSet->RemoveSerie("Serie3");
						 				$DataSet->SetAbsciseLabelSerie("Serie3");
										$DataSet->SetSerieName("Total hits","Serie2");
										$DataSet->SetSerieName("Unique hits","Serie1");
										$DataSet->SetYAxisName("HIT COUNT");
										$Title = "Hits by hour of the day";
										break;
									case 2:		
									//days of the week
										$weekdays = array();
										if(!isset($visits_div[0])) {
											die('<div class="row titleRow"><strong>No data to display</strong></div>');
										}
										foreach($visits_div as $row){
											$epoch = intval($row['date']);
											$weekday = intval(strftime('%w', $epoch));
											$ipaddress = $row['ipaddress'];
											if(!array_key_exists($weekday, $weekdays)){
												$weekdays[$weekday] = array(1,array($ipaddress));
											} else {
												$weekdays[$weekday][0] += 1;
												if(!in_array($ipaddress, $weekdays[$weekday][1])){
													array_push($weekdays[$weekday][1], $ipaddress);
												}
											}
										}
										ksort($weekdays);
										$this->library(qoob_types::utility, "pChart", "pCharts/");
										$DataSet = new pData();
										$Serie1 = array();
										$Serie2 = array();
										$Serie3 = array_keys($weekdays);
										for ($i=0;$i<count($Serie3);$i++){
											$Serie3[$i] = $int2wkday[$Serie3[$i]];
										}
										foreach($weekdays as $weekday => $value){
											array_push($Serie2, $value[0]);			// total
											array_push($Serie1, count($value[1]));	// unique
										}
										$DataSet->AddPoint($Serie1,"Serie1");
										$DataSet->AddPoint($Serie2,"Serie2");
										$DataSet->AddPoint($Serie3,"Serie3");
										$DataSet->AddAllSeries();
										$DataSet->RemoveSerie("Serie3");
										$DataSet->SetAbsciseLabelSerie("Serie3");
										$DataSet->SetSerieName("Total hits","Serie2");
										$DataSet->SetSerieName("Unique hits","Serie1");
										$DataSet->SetYAxisName("Hit count");
										$Title = "Hits by day of the week";										
										break;
									case 3:
									//days of the month
										$monthdays = array();
										if(!isset($visits_div[0])) {
											die('<div class="row titleRow"><strong>No data to display</strong></div>');
										}
										foreach($visits_div as $row){
											$epoch = intval($row['date']);
											$monthday = intval(strftime('%d', $epoch));
											$ipaddress = $row['ipaddress'];
											if(!array_key_exists($monthday, $monthdays)){
												$monthdays[$monthday] = array(1,array($ipaddress));
											} else {
												$monthdays[$monthday][0] += 1;
												if(!in_array($ipaddress, $monthdays[$monthday][1])){
													array_push($monthdays[$monthday][1], $ipaddress);
												}
											}
										}
										ksort($monthdays);
										for($i=1;$i<31;$i++){
											if(!array_key_exists($i, $monthdays)){
												$monthdays[$i] = array(0,array());
											}
										}
										ksort($monthdays);
										$this->library(qoob_types::utility, "pChart", "pCharts/");
										$DataSet = new pData();
										$Serie1 = array();
										$Serie2 = array();
										$Serie3 = array_keys($monthdays);
										foreach($monthdays as $monthday => $value){
											array_push($Serie2, $value[0]);			// total
											array_push($Serie1, count($value[1]));	// unique
										}
										$DataSet->AddPoint($Serie1,"Serie1");
										$DataSet->AddPoint($Serie2,"Serie2");
										$DataSet->AddPoint($Serie3,"Serie3");
										$DataSet->AddAllSeries();
										$DataSet->RemoveSerie("Serie3");
										$DataSet->SetAbsciseLabelSerie("Serie3");
										$DataSet->SetSerieName("Total hits","Serie2");
										$DataSet->SetSerieName("Unique hits","Serie1");
										$DataSet->SetYAxisName("Hit count");
										$Title = "Hits by day of the month";
										break;
									case 4:
									//months of the year
										$months = array();
										if(!isset($visits_div[0])) {
											die('<div class="row titleRow"><strong>No data to display</strong></div>');
										}
										foreach($visits_div as $row){
											$epoch = intval($row['date']);
											$month = strftime('%Y', $epoch) ."-". strftime('%m', $epoch);
											//$year = intval(strftime('%Y', $epoch));
											$ipaddress = $row['ipaddress'];
											if(!array_key_exists($month, $months)){
												$months[$month] = array(1,array($ipaddress));
											} else {
												$months[$month][0] += 1;
											}
											if(!in_array($ipaddress, $months[$month][1])){
												array_push($months[$month][1], $ipaddress);
											}
										}
										ksort($months);
										$this->library(qoob_types::utility, "pChart", "pCharts/");
										$DataSet = new pData();
										$Serie1 = array();
										$Serie2 = array();
										$Serie3 = array_keys($months);
										foreach($months as $month => $value){
											array_push($Serie2, $value[0]);			// total
											array_push($Serie1, count($value[1]));	// unique
										}
										$DataSet->AddPoint($Serie1,"Serie1");
										$DataSet->AddPoint($Serie2,"Serie2");
										$DataSet->AddPoint($Serie3,"Serie3");
										$DataSet->AddAllSeries();
										$DataSet->RemoveSerie("Serie3");
										$DataSet->SetAbsciseLabelSerie("Serie3");
										$DataSet->SetSerieName("Total hits","Serie2");
										$DataSet->SetSerieName("Unique hits","Serie1");
										$DataSet->SetYAxisName("Hit count");
										$Title = "Hits by month of the year";
										break;
									default:
								}
								//set colors
								//$this->pChart->setColorPalette(0, 122, 160, 84);
								$this->pChart->setColorPalette(0, 110, 144, 75);
								$this->pChart->setColorPalette(1, 171, 219, 108);
								//init graph
								$Graph = $this->pChart->makepChart(320, 350);
								$this->pChart->drawGraphAreaGradient(102, 102, 102, 1, TARGET_BACKGROUND);
								$this->pChart->setFontProperties(QOOB_PATH."/utils/pCharts/fonts/tahoma.ttf", 6);
								$this->pChart->setGraphArea(42, 30, 310, 295);
								$this->pChart->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALL,0, 0, 0,TRUE, 60, 0, TRUE);
								$this->pChart->drawGraphAreaGradient(80, 80, 80, 100);
								$this->pChart->drawGrid(4, TRUE, 100, 100, 100, 0);
								//draw stacked bar graph
								$this->pChart->drawStackedBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),80);
								//graph title
								$this->pChart->setFontProperties(QOOB_PATH."/utils/pCharts/fonts/tahoma.ttf", 14);
								$this->pChart->drawTextBox(10, 1, 157, 25, $Title, 0, 210, 210, 210, ALIGN_LEFT, false, -1, -1, -1, 100);
								//legend
								$this->pChart->setFontProperties(QOOB_PATH."/utils/pCharts/fonts/tahoma.ttf", 8);
								$this->pChart->drawLegend(230, 10, $DataSet->GetDataDescription(), 200, 200, 200, -1, -1, -1, 0, 0, 0, TRUE);										
								//render & display
								$this->pChart->Render(QOOB_ROOT.SLASH."cache/stacked_bar1.png");
								$html .= '<br style="clear:both"><img src="'.BASE_URL.'cache/stacked_bar1.png">';
							}
							die($html);
						break;
						case 'browsers':
							$data 	= getRequest("datatype", "request", FILTER_SANITIZE_NUMBER_INT);
							$range 	= getRequest("range", "request", FILTER_SANITIZE_NUMBER_INT);
							$view 	= getRequest("view", "request", FILTER_SANITIZE_NUMBER_INT);
							switch ($range) {
								case 1:
									$starttime = strtotime('-1 month');
								break;
								case 2:
									$starttime = strtotime('-6 months');
								break;
								case 3:
									$starttime = strtotime('-1 year');
								break;
								case 4:
								default:
									$starttime = 0;
								break;
							}
							switch ($data) {
								case 1:
									$browsers = $sm->browsers($starttime, time());
									if(!isset($browsers[0])) {
										die('<div class="row titleRow"><strong>No data to display</strong></div>');
									}
									for ($i=0; $i<count($browsers); $i++){
										$browsername = $browsers[$i]['browser'];
										if ($browsername == ''){
											$browsername = 'Unknown';
										}elseif ($browsername == 'iphone'){
											$browsername = 'iPhone';
										}elseif ($browsername == 'msie'){
											$browsername = 'IE';
										}elseif($browsername == 'msnbot'){
											$browsername = 'MSNbot';
										}elseif($browsername == 'htc'){
											$browsername = 'Android';
										} else {
											$browsername = ucwords($browsername);
										}
										$browsers[$i]['browser'] = $browsername;
									}
									if($view == 1) {
										$html = '<div class="row titleRow">
													<div class="lbl"><strong>Browser</strong></div>
													<div class="inputs"><strong>Hits</strong></div>
													<br/>
												</div>';								
										foreach ($browsers as $browser) {
											$html .= '<div class="row">
														<div class="lbl">'.$browser['browser'].'</div>
														<div class="inputs">'.number_format($browser['total']).'</div>
													</div>';
										}
									} else {
										$grand_ttl = 0;
										foreach ($browsers as $browser) {
											$grand_ttl += $browser['total'];
										}
										$top_browsers = array();
										if (array_key_exists('Unknown', $browsers)){
											$top_browsers['Unknown'] = $browsers['Unknown'];
										} else {
											$top_browsers['Unknown'] = 0;
										}
										foreach($browsers as $browser){
											$top_browsers[$browser['browser']] = $browser['total'];
											/*
											//limit results
											$pct = ($browser['total']/$grand_ttl)*100;
											if ($pct > 1){
												$top_browsers[$browser['browser']] = $browser['total'];
											} else {
												$top_browsers['Unknown'] += 1;
											}
											*/
										}
										arsort($top_browsers);
										$this->library(qoob_types::utility, "pChart", "pCharts/");
										$DataSet = new pData();
										foreach($top_browsers as $key => $val){
											if($val > 0) {
												$DataSet->AddPoint($top_browsers[$key],$Serie="Serie1",$Description=$key);
											}
										}
										$DataSet->AddSerie("Serie1");
										//create color pallette
										$colors_needed = count($top_browsers);
										$this->pChart->setColorPalette(0, 143, 197, 0);
										$this->pChart->setColorPalette(1, 97, 77, 63);
										$this->pChart->setColorPalette(2, 23, 123, 159);
										$this->pChart->setColorPalette(3, 97, 113, 63);
										for ($i=4; $i<$colors_needed+1; $i++){
											$this->pChart->setColorPalette($i, rand(0, 255), rand(0, 255), rand(0, 255));
										}
										//dynamic height
										$extra_height = (count($top_browsers) - 4)*14;
										$extra_height = $extra_height > 0 ? $extra_height : 0;
										//init graph
										$Browser_graph = $this->pChart->makepChart(320,240+$extra_height);
										$this->pChart->drawBackground(102, 102, 102);
										//draw pie graph
										$this->pChart->setFontProperties(QOOB_PATH."/utils/pCharts/fonts/tahoma.ttf", 8);
										$this->pChart->drawPieGraph($DataSet->GetData(), $DataSet->GetDataDescription(), 157, 80, 100, PIE_PERCENTAGE, TRUE, 50, 20, 10, 2);
										//draw legend
										$this->pChart->drawPieLegend(128, 180, $DataSet->GetData(), $DataSet->GetDataDescription(), 200, 200, 200);
										//render & display
										$this->pChart->Render(QOOB_ROOT."/cache/browsers.png");
										$html = '<img src="'.BASE_URL.'cache/browsers.png">';
									}
								break;
								case 2:
									$platforms = $sm->platforms($starttime, time());
									if(!isset($platforms[0])) {
										die('<div class="row titleRow"><strong>No data to display</strong></div>');
									}
									
									for ($i=0; $i<count($platforms); $i++){
										$platforms[$i]['platform'] = ucwords($platforms[$i]['platform']);
									}
									arsort($platforms);
									if($view == 1) {
										$html = '<div class="row titleRow">
													<div class="lbl"><strong>Platform</strong></div>
													<div class="inputs"><strong>Hits</strong></div>
													<br/>
												</div>';								
										foreach ($platforms as $platform) {
											$html .= '<div class="row">
														<div class="lbl">'.$platform['platform'].'</div>
														<div class="inputs">'.number_format($platform['total']).'</div>
													</div>';
										}
									} else {
										$this->library(qoob_types::utility, "pChart", "pCharts/");
										$DataSet = new pData();
										foreach($platforms as $platform){
											if($platform['total'] > 0) {
												$DataSet->AddPoint($platform['total'],$Serie="Serie1",$platform['platform']);
											}
										}
										$DataSet->AddSerie("Serie1");
										//create color pallette
										$colors_needed = count($platforms);
										$this->pChart->setColorPalette(0, 143, 197, 0);
										$this->pChart->setColorPalette(1, 97, 77, 63);
										$this->pChart->setColorPalette(2, 23, 123, 159);
										$this->pChart->setColorPalette(3, 97, 113, 63);
										for ($i=4; $i<$colors_needed+1; $i++){
											$this->pChart->setColorPalette($i, rand(0, 255), rand(0, 255), rand(0, 255));
										}
										//dynamic height
										$extra_height = (count($platforms)-4)*12;
										$extra_height = $extra_height > 0 ? $extra_height : 0;
										//init graph
										$Platform_graph = $this->pChart->makepChart(320,240+$extra_height);
										$this->pChart->drawBackground(102, 102, 102);
										$this->pChart->setFontProperties(QOOB_PATH."/utils/pCharts/fonts/tahoma.ttf", 8);
										//draw pie graph
										$this->pChart->drawPieGraph($DataSet->GetData(), $DataSet->GetDataDescription(), 157, 80, 100, PIE_PERCENTAGE, TRUE, 50, 20, 5, 2);
										$this->pChart->drawPieLegend(128, 180, $DataSet->GetData(), $DataSet->GetDataDescription(), 200, 200, 200);
										//render & display
										$this->pChart->Render(QOOB_ROOT."/cache/platforms.png");
										$html = '<img src="'.BASE_URL.'cache/platforms.png">';
									}									
								break;
								case 3:
									$resolutions = $sm->resolutions($starttime, time());
									$resolutions_count = $sm->resolutions_count($starttime, time());
									if(!isset($resolutions[0])) {
										die('<div class="row titleRow"><strong>No data to display</strong></div>');
									}
									$resolutions_assoc = array();
									$running_total = 0;
									for ($i=0; $i<count($resolutions); $i++){
										$array_key = $resolutions[$i]['resolution'];
										$running_total += intval($resolutions[$i]['total']);
										if(!array_key_exists($array_key, $resolutions_assoc)){
											$resolutions_assoc[$array_key] = intval($resolutions[$i]['total']);
										} else {
											$resolutions_assoc[$array_key] += intval($resolutions[$i]['total']);
										}
									}									
									arsort($resolutions_assoc);
									$resolutions_assoc['Other'] = intval($resolutions_count[0]['res_count']) - $running_total;
									if($view == 1) {
										$html = '<div class="row titleRow">
													<div class="lbl big"><strong>Top 20 Resolutions</strong></div>
													<div class="inputs lil"><strong>Hits</strong></div>
													<br/>
												</div>';								
										foreach ($resolutions_assoc as $key=>$value) {
											$html .= '<div class="row">
														<div class="lbl big">'.$key.'</div>
														<div class="inputs lil">'.number_format($value).'</div>
													</div>';
										}
									} else {
										$this->library(qoob_types::utility, "pChart", "pCharts/");
										$DataSet = new pData();
										foreach($resolutions_assoc as $key=>$value){
											if($value > 0) {
												$DataSet->AddPoint($value, $Serie="Serie1",$Description=$key);
											}
										}
										$DataSet->AddSerie("Serie1");
										//create color pallette
										$colors_needed = count($resolutions_assoc);
										$this->pChart->setColorPalette(0, 143, 197, 0);
										$this->pChart->setColorPalette(1, 97, 77, 63);
										$this->pChart->setColorPalette(2, 23, 123, 159);
										$this->pChart->setColorPalette(3, 97, 113, 63);
										for ($i=4; $i<$colors_needed+1; $i++){
											$this->pChart->setColorPalette($i, rand(0, 255), rand(0, 255), rand(0, 255));
										}
										//dynamic height
										$extra_height = (count($resolutions_assoc)-10)*12;
										$extra_height = $extra_height > 0 ? $extra_height : 0;
										//init graph
										$Resolutions_graph = $this->pChart->makepChart(320,320+$extra_height);
										$this->pChart->drawBackground(102, 102, 102);
										//draw pie graph
										$this->pChart->setFontProperties(QOOB_PATH."/utils/pCharts/fonts/tahoma.ttf", 8);
										$this->pChart->drawPieGraph($DataSet->GetData(), $DataSet->GetDataDescription(), 157, 80, 100, PIE_PERCENTAGE, TRUE, 50, 20, 13, 2);
										//draw legend
										$this->pChart->drawPieLegend(128, 180, $DataSet->GetData(), $DataSet->GetDataDescription(), 200, 200, 200);
										//render & display
										$this->pChart->Render(QOOB_ROOT."/cache/resolutions.png");
										$html = "<img src='".BASE_URL."cache/resolutions.png'>";
									}									
								break;
								case 4:
									$flash = $sm->flash($starttime, time());
									if(!isset($flash[0])) {
										die('<div class="row titleRow"><strong>No data to display</strong></div>');
									}
									if($view == 1) {
										$html = '<div class="row titleRow">
													<div class="lbl"><strong>Flash Version</strong></div>
													<div class="inputs"><strong>Hits</strong></div>
													<br/>
												</div>';								
										foreach ($flash as $flashver) {
											$html .= '<div class="row">
														<div class="lbl">'.$flashver['flash_version'].'</div>
														<div class="inputs">'.number_format($flashver['total']).'</div>
													</div>';
										}
									} else {
										$this->library(qoob_types::utility, "pChart", "pCharts/");
										$DataSet = new pData();
										foreach ($flash as $line){
											if ($line['flash_version'] != "0"){
												$DataSet->AddPoint($line['total'], $Serie="Serie1", 'Flash '.$line['flash_version']);
											} else {
												$DataSet->AddPoint($line['total'], $Serie="Serie1", 'None');
											}
										}
										$DataSet->AddSerie("Serie1");
										$colors_needed = count($flash);
										//create color pallette
										$this->pChart->setColorPalette(0, 143, 197, 0);
										$this->pChart->setColorPalette(1, 97, 77, 63);
										$this->pChart->setColorPalette(2, 23, 123, 159);
										$this->pChart->setColorPalette(3, 97, 113, 63);
										for ($i=4; $i<$colors_needed+1; $i++){
											$this->pChart->setColorPalette($i, rand(0, 255), rand(0, 255), rand(0, 255));
										}
										//dynamic height
										$extra_height = (count($flash)-5)*12;
										$extra_height = $extra_height > 0 ? $extra_height : 0;
										//init graph
										$graph = $this->pChart->makepChart(320,260+$extra_height);
										$this->pChart->drawBackground(102, 102, 102);
										$this->pChart->setFontProperties(QOOB_PATH."/utils/pCharts/fonts/tahoma.ttf", 7);
										//draw pie graph
										$this->pChart->setFontProperties(QOOB_PATH."/utils/pCharts/fonts/tahoma.ttf", 7);
										$this->pChart->drawPieGraph($DataSet->GetData(), $DataSet->GetDataDescription(), 157, 80, 100, PIE_PERCENTAGE, TRUE, 50, 20, 5, 2);
										//draw legend
										$this->pChart->setFontProperties(QOOB_PATH."/utils/pCharts/fonts/tahoma.ttf", 8);
										$this->pChart->drawPieLegend(128, 180, $DataSet->GetData(), $DataSet->GetDataDescription(), 200, 200, 200);
										//render & display
										$this->pChart->Render(QOOB_ROOT."/cache/flash.png");
										$html = "<img src='".BASE_URL."cache/flash.png'>";
									}									
								break;																
							}
							die($html);
						break;
						case 'pages':
							$range 	= getRequest("range", "post", FILTER_SANITIZE_NUMBER_INT);
							$limit 	= getRequest("limit", "post", FILTER_SANITIZE_NUMBER_INT);
							switch ($range) {
								case 1:
									$starttime = strtotime('-1 month');
								break;
								case 2:
									$starttime = strtotime('-6 months');
								break;
								case 3:
									$starttime = strtotime('-1 year');
								break;
								case 4:
								default:
									$starttime = 0;
								break;
							}
							switch ($limit) {
								case 1:
									$limit = 10;
								break;
								case 2:
									$limit = 25;
								break;
								case 3:
									$limit = 50;
								break;
								case 4:
									$limit = 75;
								break;
								case 5:
									$limit = 100;
								break;
								case 6:
								default:
									$limit = 0;
								break;
							}
							$sm = $this->model("statsModel");
							$resources = $sm->resource($starttime, time(), $limit);
							if(!isset($resources[0])) {
								die('<div class="row titleRow"><strong>No data to display</strong></div>');
							}
							$html = '<div class="row titleRow">
										<div class="lbl big"><strong>Page</strong></div>
										<div class="inputs lil"><strong>Hits</strong></div>
										<br/>
									</div>';								
							foreach ($resources as $resource) {
								//clean up urls for display
								$url = $resource['resource'];
								if($url != QOOB_DOMAIN && $url != BASE_URL) {
									$url = str_replace('www.', '', $url);
									$url = str_replace(BASE_URL, '', $url);
									$url = str_replace('?/', '', $url);
								}
								$url = str_replace('http://', '', $url);
								$url = rtrim($url, '/');
								$url = ltrim($url, '/');
								if($url != '') {
									$url = strlen($url) > 28 ? substr($url, 0, 28).'...' : $url;
									$html .= '<div class="row">
												<div class="lbl big"><a style="color: #ccc!important" title="'.$resource['resource'].'" alt="'.$resource['resource'].'">'.$url.'</div>
												<div class="inputs lil">'.$resource['total'].'</div>
											</div>';
								}
							}
							die($html);
						break;
						case 'referrers':
							$range 	= getRequest("range", "post", FILTER_SANITIZE_NUMBER_INT);
							$limit 	= getRequest("limit", "post", FILTER_SANITIZE_NUMBER_INT);
							switch ($range) {
								case 1:
									$starttime = strtotime('-1 month');
								break;
								case 2:
									$starttime = strtotime('-6 months');
								break;
								case 3:
									$starttime = strtotime('-1 year');
								break;
								case 4:
								default:
									$starttime = 0;
								break;
							}
							switch ($limit) {
								case 1:
									$limit = 10;
								break;
								case 2:
									$limit = 25;
								break;
								case 3:
									$limit = 50;
								break;
								case 4:
									$limit = 75;
								break;
								case 5:
									$limit = 100;
								break;
								case 6:
								default:
									$limit = 0;
								break;
							}
							$sm = $this->model("statsModel");
							$referrers = $sm->referrers($starttime, time(), $limit);
							if(!isset($referrers[0])) {
								die('<div class="row titleRow"><strong>No data to display</strong></div>');
							}
							$html = '<div class="row titleRow">
										<div class="lbl big"><strong>Page</strong></div>
										<div class="inputs lil"><strong>Hits</strong></div>
										<br/>
									</div>';								
							foreach ($referrers as $domain) {
								if(trim($domain['domain']) != '' && trim($domain['domain']) != 'unknown') {
									$html .= '<div class="row">
												<div class="lbl big">'.$domain['domain'].'</div>
												<div class="inputs lil">'.$domain['total'].'</div>
											</div>';
								}
							}
							die($html);
						break;
						case 'locations':
							$range 	= getRequest("range", "post", FILTER_SANITIZE_NUMBER_INT);
							$view 	= getRequest("view", "post", FILTER_SANITIZE_NUMBER_INT);
							switch ($range) {
								case 1:
									$starttime = strtotime('-1 month');
								break;
								case 2:
									$starttime = strtotime('-6 months');
								break;
								case 3:
									$starttime = strtotime('-1 year');
								break;
								case 4:
								default:
									$starttime = 0;
								break;
							}
							$sm = $this->model("statsModel");
							$locations = $sm->location($starttime, time());
							if(!isset($locations[0])) {
								die('<div class="row titleRow"><strong>No data to display</strong></div>');
							}
							if($view == 1) {
								$html = '<div class="row titleRow">
											<div class="lbl big"><strong>Country</strong></div>
											<div class="inputs lil"><strong>Hits</strong></div>
											<br/>
										</div>';								
								foreach ($locations as $country) {
									$html .= '<div class="row">
												<div class="lbl big">'.$country['location'].'</div>
												<div class="inputs lil">'.$country['total'].'</div>
											</div>';
								}
							} else {
								$html = 'display locations image...';
							}
							die($html);
						break;
						case 'searches':
							$range 	= getRequest("range", "post", FILTER_SANITIZE_NUMBER_INT);
							$limit 	= getRequest("limit", "post", FILTER_SANITIZE_NUMBER_INT);
							switch ($range) {
								case 1:
									$starttime = strtotime('-1 month');
								break;
								case 2:
									$starttime = strtotime('-6 months');
								break;
								case 3:
									$starttime = strtotime('-1 year');
								break;
								case 4:
								default:
									$starttime = 0;
								break;
							}
							switch ($limit) {
								case 1:
									$limit = 10;
								break;
								case 2:
									$limit = 25;
								break;
								case 3:
									$limit = 50;
								break;
								case 4:
									$limit = 75;
								break;
								case 5:
									$limit = 100;
								break;
								case 6:
								default:
									$limit = 0;
								break;
							}
							$sm = $this->model("statsModel");
							$searches = $sm->searches($starttime, time(), $limit);
							if(!isset($searches[0])) {
								die('<div class="row titleRow"><strong>No data to display</strong></div>');
							}
							//regex for finding search terms in the url
							$pattern1 = '/[;|\?][p|q](uery)?=(.*?)([\?|;|&])/';
							$pattern2 = '/[;|\?][p|q]=([^&].*)([\?|;|&])?/';
							$pattern3 = '/[;|\?]search(for)?=(.*?)([\?|;|&])/';
							$terms = array();
							$term = null;
							foreach ($searches as $search) {
								preg_match($pattern1, $search['referer'], $q_terms);
								if(count($q_terms)>0 && trim($q_terms[2]) != '') {
									$term = $q_terms[2];
								} else {
									preg_match($pattern2, $search['referer'], $q_terms);
									if(count($q_terms)>0 && trim($q_terms[1]) != '') {
										$term = $q_terms[1];
									} else {
										preg_match($pattern3, $search['referer'], $q_terms);
										if(count($q_terms)>0 && trim($q_terms[2]) != '') {
											$term = $q_terms[2];
										}
									}
								}
								$term = preg_replace('/\%2F/i', '/', $term);
								$term = preg_replace('/\%2B/i', '+', $term);
								$term = preg_replace('/\%2C/i', ',', $term);
								$term = preg_replace('/\%3D/i', '=', $term);
								$term = preg_replace('/\%3F/i', '?', $term);
								$term = preg_replace('/\%3A/i', ':', $term);
								//$term = preg_replace('/\%3B/i', ';', $term);
								$term = preg_replace('/\%3B/i', ' ', $term);
								$term = preg_replace('/%uF076/', '', $term);
								$term = preg_replace('/\%u([0-9a-z]{4})/i', '&#x$1;', $term); // converts #uNNNN to UTF character
								$term = trim($term);
								if(count($term)>0 && $term != null) {
									if(!array_key_exists(strtolower($term), $terms)) {
										$terms[strtolower($term)] = array(1,array($search['auto_id']));
									} else {
										$terms[strtolower($term)][0] += 1;
										array_push($terms[strtolower($term)][1], $search['auto_id']);
									}
								}
							}
							arsort($terms);
							$term_keys = array_keys($terms);
							if(count($term_keys) == 0) {
								die('<div class="row titleRow"><strong>No data to display</strong></div>');
							}
							$html = '<div class="row titleRow">
										<div class="lbl big"><strong>Search Term</strong></div>
										<div class="inputs lil"><strong>Hits</strong></div>
										<br/>
									</div>';
							if($limit == 0) {
								$limit = count($term_keys);
							}
							$i = 0;
							foreach($term_keys as $term) {
								if($i < $limit) {
									$theTerm = strlen($term) > 28 ? substr($term, 0, 28).'...' : $term;
									$html .= '<div class="row">
												<div class="lbl big"><a style="color: #ccc!important" title="'.$term.'" alt="'.$term.'">'.$theTerm.'</a></div>
												<div class="inputs lil">'.$terms[$term][0].'</div>
											</div>';
								}
								$i++;
							}
							die($html);
						break;
						default:
							throw new Exception("Unknown stat type", 404);
						break;
					}
				break;
				default:
					throw new Exception("Bad SubMethod", 404);
				break;
			}
		}
	}
}
//___________________________________________________________________________________________________________
//                                                                                                        EOF
?>