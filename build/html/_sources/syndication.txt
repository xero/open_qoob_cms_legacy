Syndication Utility
*******************

.. php:class:: syndication

      syndication class
      for generation of RSS and ATOM feeds
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.2

      :package: qoob

      :subpackage: utils

   .. php:attr:: $feed

   .. php:attr:: $type

   .. php:attr:: $link

   .. php:attr:: $title

   .. php:attr:: $posts

   .. php:attr:: $author

   .. php:attr:: $description

   .. php:attr:: $descriptionHtml

   .. php:method:: syndication::setType()

      feed type setter function
      

      :param int $type:

      :example: $this->syndication->setType(feed_types::ATOM);

   .. php:method:: syndication::setDescrip()

      feed description setter function
      args is an array of values.
      :public:
      :param array $args:

      :example: $this->syndication->setDescrip(array(

   .. php:method:: syndication::setData()

      feed data setter function
      the data param is a multi-dimensional array of post data.::

      	$result = $blog->getNewest();
      	$posts = array();
      	if(count($result) > 0) {
      		for($i = 0; $i < count($result); $i++) {
      			$posts[$i]['title'] = $result[$i]['title'];
      			$posts[$i]['link'] = 'http://blog.xero.nu/'.$result[$i]['url'];
      			$posts[$i]['description'] = $result[$i]['excerpt'];
      			$posts[$i]['descriptionHtml'] = true;
      			$posts[$i]['date'] = $result[$i]['date'];
      			$posts[$i]['author'] = 'xero harrison';
      		}
      	}
      	$this->syndication->setData($posts);

      :public:
      :param array $data:

      :example: $blog $ $this->model("blogModel");

   .. php:method:: syndication::generate()

      feed generate function
      optionally set feed type, description, and post data
      arrays in the same ways as their public method counterparts.::

         $descrip = array(
      			'link' => 'http://blog.xero.nu/',
      			'title' => 'blog.xero.nu',
      			'description' => 'a blog about code, art, hacks, technology, video games, life and random stuff.',
      			'descriptionHtml' => false
      	);
      	$blog = $this->model("blogModel");
      	$result = $blog->getNewest();
      	$posts = array();
      	if(count($result) > 0) {
      		for($i = 0; $i < count($result); $i++) {
      			$posts[$i]['title'] = $result[$i]['title'];
      			$posts[$i]['link'] = 'http://blog.xero.nu/'.$result[$i]['url'];
      			$posts[$i]['description'] = $result[$i]['excerpt'];
      			$posts[$i]['descriptionHtml'] = true;
      			$posts[$i]['date'] = $result[$i]['date'];
      			$posts[$i]['author'] = 'xero harrison';
      		}
      	}
      	$this->library(qoob_types::utility, "syndication");
      	$type = strtolower(library::catalog()->feedtype) == "atom" ? feed_types::ATOM : feed_types::RSS;
      	die ($this->syndication->generate($type, $descrip, $posts));

      :public:
      :param array $data:

      :example: $descrip $ array(

.. php:class:: feed_types

      feed types
      constants used for feed generation code hinting
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: qoob

      :subpackage: utils

   .. php:const:: feed_types:: RSS = 0;

      :var: RSS

   .. php:const:: feed_types:: ATOM = 1;

      :var: ATOM