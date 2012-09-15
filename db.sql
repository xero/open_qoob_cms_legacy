--
-- Database: `qoob`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `username`, `password`, `email`) VALUES
(1, 'qoob user', 'qoob', 'gAAJoQ;zoC2oBgi;Ll5cD63H3Yn9vBLzfB+/j1K9iw4=', 'open@qoob.nu');

-- password is: openqoob!

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE IF NOT EXISTS `blog_categories` (
  `qoob_cat_id` int(255) NOT NULL AUTO_INCREMENT,
  `blog_cat_id` double NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  PRIMARY KEY (`qoob_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_meta`
--

CREATE TABLE IF NOT EXISTS `blog_meta` (
  `meta_id` int(255) NOT NULL AUTO_INCREMENT,
  `blog_id` int(255) NOT NULL,
  `meta_key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `post_id` int(255) NOT NULL AUTO_INCREMENT,
  `url` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `subtitle` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `excerpt` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `comments` int(255) NOT NULL,
  `date` int(255) NOT NULL,
  `live` int(1) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_tags`
--

CREATE TABLE IF NOT EXISTS `blog_tags` (
  `tag_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tag_count` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `code`
--

CREATE TABLE IF NOT EXISTS `code` (
  `git_id` int(255) NOT NULL AUTO_INCREMENT,
  `repo` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  `name` varchar(300) NOT NULL,
  `subtitle` varchar(300) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `readme` longtext NOT NULL,
  PRIMARY KEY (`git_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_categories`
--

CREATE TABLE IF NOT EXISTS `gallery_categories` (
  `qoob_cat_id` int(255) NOT NULL AUTO_INCREMENT,
  `gallery_cat_id` double NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  `title` varchar(300) NOT NULL,
  `excerpt` varchar(1000) NOT NULL,
  `description` longtext NOT NULL,
  `mainImg` int(255) NOT NULL,
  `live` int(1) NOT NULL,
  PRIMARY KEY (`qoob_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE IF NOT EXISTS `gallery_images` (
  `image_id` int(255) NOT NULL AUTO_INCREMENT,
  `url` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `subtitle` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `excerpt` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `comments` int(255) NOT NULL,
  `date` int(255) NOT NULL,
  `live` int(1) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_meta`
--

CREATE TABLE IF NOT EXISTS `gallery_meta` (
  `meta_id` int(255) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(255) NOT NULL,
  `meta_key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `url` varchar(30) NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(500) NOT NULL,
  `body` longtext NOT NULL,
  `script` longtext,
  `mainCat` varchar(50) DEFAULT NULL,
  `meta` varchar(1000) DEFAULT NULL,
  `sidebar` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `url`, `title`, `subtitle`, `body`, `script`, `mainCat`, `meta`, `sidebar`) VALUES
(1, 'index', 'open qoob', 'The default page', 'hello world!', '', 'about', '', ''),
(4, 'contact', 'Contact', 'Get in touch', '&#60;form name=&#34;emailForm&#34; method=&#34;post&#34; action=&#34;http://localhost/qoob/email/&#34;&#62;&#13;&#10;&#9;&#60;div id=&#34;formErrors&#34;&#62;&#60;/div&#62;&#13;&#10;&#9;&#60;div id=&#34;emailForm&#34;&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;Name: &#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;input type=&#34;text&#34; name=&#34;txtName&#34; title=&#34;name&#34; value=&#34;&#34; class=&#34;inputBox&#34;/&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;E-mail: &#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;input type=&#34;text&#34; name=&#34;txtEmail&#34; title=&#34;email&#34; value=&#34;&#34; class=&#34;inputBox&#34;/&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;Message: &#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;textarea name=&#34;txtMsg&#34; title=&#34;email message&#34; rows=&#34;5&#34; cols=&#34;23&#34; class=&#34;inputBox&#34;&#62;&#60;/textarea&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;&#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;input type=&#34;submit&#34; name=&#34;btnSubmit&#34; title=&#34; Submit &#34; value=&#34; Submit &#34; onclick=&#34;return validate();&#34;/&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;        &#60;/div&#62;&#13;&#10;&#9;&#60;p&#62;&#60;br class=&#34;clear&#34; /&#62;&#60;/p&#62;&#13;&#10;&#60;/form&#62;', '&#9;&#9;function validate() {&#13;&#10;&#9;&#9;&#9;var error = false;&#13;&#10;&#9;&#9;&#9;var errMsg = &#39;&#60;h3&#62;Error!&#60;/h3&#62;&#39;;&#13;&#10;&#13;&#10;&#9;&#9;&#9;if (document.emailForm.txtName.value==&#34;&#34;) {&#13;&#10;&#9;&#9;&#9;&#9;error = true;&#13;&#10;&#9;&#9;&#9;&#9;errMsg += &#39;You must specify your name.&#60;br/&#62;&#39;;&#13;&#10;&#9;&#9;&#9;} &#13;&#10;&#9;&#9;&#9;if (document.emailForm.txtEmail.value==&#34;&#34;) {&#13;&#10;&#9;&#9;&#9;&#9;error = true;&#13;&#10;&#9;&#9;&#9;&#9;errMsg += &#39;You must specify your e-mail address.&#60;br/&#62;&#39;&#13;&#10;&#9;&#9;&#9;} &#13;&#10;&#9;&#9;&#9;if (document.emailForm.txtMsg.value==&#34;&#34;) {&#13;&#10;&#9;&#9;&#9;&#9;error = true;&#13;&#10;&#9;&#9;&#9;&#9;errMsg += &#39;You must enter a message to send.&#60;br/&#62;&#39;;&#13;&#10;&#9;&#9;&#9;}&#13;&#10;&#9;&#9;&#9;if (error == false) {&#13;&#10;&#9;&#9;&#9;&#9;document.emailForm.submit();&#13;&#10;&#9;&#9;&#9;} else {&#13;&#10;&#9;&#9;&#9;&#9;$(&#39;#formErrors&#39;).html(&#39;&#60;div class=&#34;err&#34;&#62;&#60;div class=&#34;bubble&#34;&#62;&#39;+errMsg+&#39;&#60;/div&#62;&#60;/div&#62;&#39;);&#13;&#10;&#9;&#9;&#9;&#9;return false;&#13;&#10;&#9;&#9;&#9;}&#13;&#10;&#9;&#9;}&#13;&#10;', 'contact', '', ''),
(5, 'contact_thank_you', 'Contact', 'Email Sent!', '&#60;div id=&#34;emailForm&#34;&#62;&#13;&#10;&#60;h3&#62;Thank You!&#60;/h3&#62;&#13;&#10;Your email has been sent.&#13;&#10;&#60;/div&#62;&#13;&#10;&#60;p&#62;&#60;br class=&#34;clear&#34; /&#62;&#60;/p&#62;', '', 'contact', '', ''),
(6, 'contact_missing', 'Contact', 'Missing Required Fields', '&#60;form name=&#34;emailForm&#34; method=&#34;post&#34; action=&#34;http://localhost/qoob/email/&#34;&#62;&#13;&#10;&#9;&#60;div id=&#34;formErrors&#34;&#62;&#13;&#10;&#60;div class=&#34;err&#34;&#62;&#60;div class=&#34;bubble&#34;&#62;&#13;&#10;&#60;h3&#62;Error!&#60;/h3&#62;You are missing required fields.&#13;&#10;&#60;/div&#62;&#60;/div&#62;&#13;&#10;&#60;/div&#62;&#13;&#10;&#9;&#60;div id=&#34;emailForm&#34;&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;Name: &#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;input type=&#34;text&#34; name=&#34;txtName&#34; title=&#34;name&#34; value=&#34;&#34; class=&#34;inputBox&#34;/&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;E-mail: &#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;input type=&#34;text&#34; name=&#34;txtEmail&#34; title=&#34;email&#34; value=&#34;&#34; class=&#34;inputBox&#34;/&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;Message: &#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;textarea name=&#34;txtMsg&#34; title=&#34;email message&#34; rows=&#34;5&#34; cols=&#34;23&#34; class=&#34;inputBox&#34;&#62;&#60;/textarea&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;&#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;input type=&#34;submit&#34; name=&#34;btnSubmit&#34; title=&#34; Submit &#34; value=&#34; Submit &#34; onclick=&#34;return validate();&#34;/&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;        &#60;/div&#62;&#13;&#10;&#60;p&#62;&#60;br class=&#34;clear&#34; /&#62;&#60;/p&#62;&#13;&#10;&#60;/form&#62;', '&#9;&#9;function validate() {&#13;&#10;&#9;&#9;&#9;var error = false;&#13;&#10;&#9;&#9;&#9;var errMsg = &#39;&#60;h3&#62;Error!&#60;/h3&#62;&#39;;&#13;&#10;&#13;&#10;&#9;&#9;&#9;if (document.emailForm.txtName.value==&#34;&#34;) {&#13;&#10;&#9;&#9;&#9;&#9;error = true;&#13;&#10;&#9;&#9;&#9;&#9;errMsg += &#39;You must specify your name.&#60;br/&#62;&#39;;&#13;&#10;&#9;&#9;&#9;} &#13;&#10;&#9;&#9;&#9;if (document.emailForm.txtEmail.value==&#34;&#34;) {&#13;&#10;&#9;&#9;&#9;&#9;error = true;&#13;&#10;&#9;&#9;&#9;&#9;errMsg += &#39;You must specify your e-mail address.&#60;br/&#62;&#39;&#13;&#10;&#9;&#9;&#9;} &#13;&#10;&#9;&#9;&#9;if (document.emailForm.txtMsg.value==&#34;&#34;) {&#13;&#10;&#9;&#9;&#9;&#9;error = true;&#13;&#10;&#9;&#9;&#9;&#9;errMsg += &#39;You must enter a message to send.&#60;br/&#62;&#39;;&#13;&#10;&#9;&#9;&#9;}&#13;&#10;&#9;&#9;&#9;if (error == false) {&#13;&#10;&#9;&#9;&#9;&#9;document.emailForm.submit();&#13;&#10;&#9;&#9;&#9;} else {&#13;&#10;&#9;&#9;&#9;&#9;$(&#39;#formErrors&#39;).html(&#39;&#60;div class=&#34;err&#34;&#62;&#60;div class=&#34;bubble&#34;&#62;&#39;+errMsg+&#39;&#60;/div&#62;&#60;/div&#62;&#39;);&#13;&#10;&#9;&#9;&#9;&#9;return false;&#13;&#10;&#9;&#9;&#9;}&#13;&#10;&#9;&#9;}&#13;&#10;', 'contact', '', ''),
(7, 'contact_bad_email', 'Contact', 'Invalid Email', '&#60;form name=&#34;emailForm&#34; method=&#34;post&#34; action=&#34;http://localhost/qoob/email/&#34;&#62;&#13;&#10;&#9;&#60;div id=&#34;formErrors&#34;&#62;&#13;&#10;&#60;div class=&#34;err&#34;&#62;&#60;div class=&#34;bubble&#34;&#62;&#13;&#10;&#60;h3&#62;Error!&#60;/h3&#62;You&#39;re email address is invalid.&#13;&#10;&#60;/div&#62;&#60;/div&#62;&#13;&#10;&#60;/div&#62;&#13;&#10;&#9;&#60;div id=&#34;emailForm&#34;&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;Name: &#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;input type=&#34;text&#34; name=&#34;txtName&#34; title=&#34;name&#34; value=&#34;&#34; class=&#34;inputBox&#34;/&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;E-mail: &#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;input type=&#34;text&#34; name=&#34;txtEmail&#34; title=&#34;email&#34; value=&#34;&#34; class=&#34;inputBox&#34;/&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;Message: &#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;textarea name=&#34;txtMsg&#34; title=&#34;email message&#34; rows=&#34;5&#34; cols=&#34;23&#34; class=&#34;inputBox&#34;&#62;&#60;/textarea&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;div class=&#34;row&#34;&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;lbl&#34;&#62;&#60;/div&#62;&#13;&#10;&#9;&#9;&#60;div class=&#34;inputs&#34;&#62;&#13;&#10;&#9;&#9;&#9;&#60;input type=&#34;submit&#34; name=&#34;btnSubmit&#34; title=&#34; Submit &#34; value=&#34; Submit &#34; onclick=&#34;return validate();&#34;/&#62;&#13;&#10;&#9;&#9;&#60;/div&#62;&#13;&#10;&#9;&#60;/div&#62;&#13;&#10;        &#60;/div&#62;&#13;&#10;&#9;&#60;p&#62;&#60;br class=&#34;clear&#34; /&#62;&#60;/p&#62;&#13;&#10;&#60;/form&#62;', '&#9;&#9;function validate() {&#13;&#10;&#9;&#9;&#9;var error = false;&#13;&#10;&#9;&#9;&#9;var errMsg = &#39;&#60;h3&#62;Error!&#60;/h3&#62;&#39;;&#13;&#10;&#13;&#10;&#9;&#9;&#9;if (document.emailForm.txtName.value==&#34;&#34;) {&#13;&#10;&#9;&#9;&#9;&#9;error = true;&#13;&#10;&#9;&#9;&#9;&#9;errMsg += &#39;You must specify your name.&#60;br/&#62;&#39;;&#13;&#10;&#9;&#9;&#9;} &#13;&#10;&#9;&#9;&#9;if (document.emailForm.txtEmail.value==&#34;&#34;) {&#13;&#10;&#9;&#9;&#9;&#9;error = true;&#13;&#10;&#9;&#9;&#9;&#9;errMsg += &#39;You must specify your e-mail address.&#60;br/&#62;&#39;&#13;&#10;&#9;&#9;&#9;} &#13;&#10;&#9;&#9;&#9;if (document.emailForm.txtMsg.value==&#34;&#34;) {&#13;&#10;&#9;&#9;&#9;&#9;error = true;&#13;&#10;&#9;&#9;&#9;&#9;errMsg += &#39;You must enter a message to send.&#60;br/&#62;&#39;;&#13;&#10;&#9;&#9;&#9;}&#13;&#10;&#9;&#9;&#9;if (error == false) {&#13;&#10;&#9;&#9;&#9;&#9;document.emailForm.submit();&#13;&#10;&#9;&#9;&#9;} else {&#13;&#10;&#9;&#9;&#9;&#9;$(&#39;#formErrors&#39;).html(&#39;&#60;div class=&#34;err&#34;&#62;&#60;div class=&#34;bubble&#34;&#62;&#39;+errMsg+&#39;&#60;/div&#62;&#60;/div&#62;&#39;);&#13;&#10;&#9;&#9;&#9;&#9;return false;&#13;&#10;&#9;&#9;&#9;}&#13;&#10;&#9;&#9;}&#13;&#10;', 'contact', '', ''),
(8, 'contact_spam', 'Contact', 'S P A M ! ! !', '&#60;div id=&#34;formErrors&#34;&#62;&#13;&#10;&#60;div class=&#34;err&#34;&#62;&#60;div class=&#34;bubble&#34;&#62;&#13;&#10;&#60;h3&#62;Error!&#60;/h3&#62;Your message has been flagged as spam!&#13;&#10;&#60;/div&#62;&#60;/div&#62;&#13;&#10;&#60;/div&#62;&#13;&#10;&#60;p&#62;&#60;br class=&#34;clear&#34; /&#62;&#60;/p&#62;', '', 'contact', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE IF NOT EXISTS `routes` (
  `route_id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'auto id',
  `name` varchar(30) NOT NULL COMMENT 'url segment',
  `controller` varchar(30) NOT NULL COMMENT 'controller class',
  `parent` int(255) NOT NULL COMMENT 'parent url segment',
  PRIMARY KEY (`route_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='url routing data' AUTO_INCREMENT=44 ;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`route_id`, `name`, `controller`, `parent`) VALUES
(1, 'index', 'pages', 0),
(2, 'home', 'pages', 0),
(3, 'email', 'email', 0),
(4, 'contact', 'pages', 0),
(5, 'contact_missing', 'pages', 0),
(6, 'contact_spam', 'pages', 0),
(7, 'contact_bad_email', 'pages', 0),
(8, 'contact_thank_you', 'pages', 0),
(9, 'blog', 'blog', 0),
(10, 'qr', 'qrcode', 0),
(11, 'gallery', 'gallery', 0),
(12, 'code', 'code', 0),
(13, 'search', 'search', 0),
(14, 'feeds', 'feeds', 0),
(15, 'rss', 'rss', 14),
(16, 'atom', 'atom', 14),
(17, 'qoob_stats', 'stats', 0),
(18, 'detect.js', 'js', 17),
(19, 'save', 'save', 17),
(20, 'backdoor', 'admin', 0),
(21, 'console', 'main', 20),
(22, 'logout', 'logout', 20),
(23, 'addAdmin', 'addAdmin', 20),
(24, 'modAdmin', 'modAdmin', 20),
(25, 'delAdmin', 'delAdmin', 20),
(26, 'invite', 'invite', 20),
(27, 'addPage', 'addPage', 20),
(28, 'modPage', 'modPage', 20),
(29, 'delPage', 'delPage', 20),
(30, 'ajax', 'ajax', 20),
(31, 'addBlog', 'addBlog', 20),
(32, 'modBlog', 'modBlog', 20),
(33, 'delBlog', 'delBlog', 20),
(34, 'addGalleryCat', 'addGalleryCat', 20),
(35, 'modGalleryCat', 'modGalleryCat', 20),
(36, 'delGalleryCat', 'delGalleryCat', 20),
(37, 'addGalleryImg', 'addGalleryImg', 20),
(38, 'modGalleryImg', 'modGalleryImg', 20),
(39, 'delGalleryImg', 'delGalleryImg', 20),
(40, 'addCode', 'addCode', 20),
(41, 'modCode', 'modCode', 20),
(42, 'delCode', 'delCode', 20),
(43, 'stats', 'stats', 20);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `auto_id` int(255) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(32) NOT NULL,
  `qoob_id` varchar(32) NOT NULL,
  `fingerprint` varchar(32) NOT NULL,
  `expires` int(255) NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY (`auto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `auto_id` int(255) NOT NULL AUTO_INCREMENT,
  `referer` varchar(500) DEFAULT NULL,
  `referer_checksum` int(10) DEFAULT '0',
  `domain` varchar(255) DEFAULT NULL,
  `domain_checksum` int(10) DEFAULT '0',
  `resource` varchar(300) DEFAULT NULL,
  `resource_checksum` int(10) DEFAULT '0',
  `resource_title` varchar(500) DEFAULT NULL,
  `resolution` varchar(10) DEFAULT '0x0',
  `browser` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `platform` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `useragent` varchar(500) DEFAULT NULL,
  `ipaddress` varchar(100) DEFAULT NULL,
  `hostname` varchar(100) DEFAULT NULL,
  `location` varchar(200) DEFAULT '0',
  `flash_version` int(10) DEFAULT '0',
  `date` int(255) NOT NULL,
  PRIMARY KEY (`auto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------