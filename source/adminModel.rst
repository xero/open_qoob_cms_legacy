Admin Model
***********

.. php:class:: adminModel

      admin model
      SQL functions for adding information to the database from the backend.
      functions for adding, modifying, and deleting records for administrators,
      pages, blogs, code, and galleries.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 7.2

      :package: app

      :subpackage: models

   .. php:method:: adminModel::__construct()

      constructor function
      sets the database adapter type to mySQL.

   .. php:method:: adminModel::checkUser()

      checks a submitted username and password against the database
      

      :param string $username:

      :returns: boolean

   .. php:method:: adminModel::checkAdmin()

      checks an email against the admin table of the database
      

      :param string $email:

      :returns: boolean

   .. php:method:: adminModel::addAdmin()

      adds an administrator to the database
      

      :param array $args:

   .. php:method:: adminModel::getAllAdmins()

      returns all admins
      

      :returns: array

   .. php:method:: adminModel::getAdminByID()

      returns an admin by ID
      

      :param int $id:

      :returns: array

   .. php:method:: adminModel::modAdmin()

      modify the properties of an admin

      :param array $args:

   .. php:method:: adminModel::deleteAdmin()

      delete an admin by ID
      

      :param int $id:

   .. php:method:: adminModel::checkPageRoute()

      check to see if a route for a page url is used or not.
      

      :param string $url:

   .. php:method:: adminModel::checkPageRouteChange()

      check to see if a page url has change during page modification
      

      :param int $id:
      :param string $url:

      :returns: boolean $rue = url change / false = no change

   .. php:method:: adminModel::getPageRouteIDs()

      get page and route ids.
      

      :param string $url:

   .. php:method:: adminModel::getPages()

      returns all pages id and url fields.

   .. php:method:: adminModel::getPage()

      returns a single page from the database
      

      :param int $id:

   .. php:method:: adminModel::addPage()

      add a page and a route to the page to the database.
      

      :param array $args:

   .. php:method:: adminModel::modPage()

      modify a page and it's route to the page to the database.
      

      :param array $args:

   .. php:method:: adminModel::delPage()

      deletes a page and a route from the database.
      

      :param array $args:

   .. php:method:: adminModel::getBlogByID()

      returns a blog post by id
      

      :param int $id:

   .. php:method:: adminModel::getAllBlogPosts()

      return all blog posts
      

   .. php:method:: adminModel::checkBlogRoute()

      check if a blog url is used or not
      

      :param string $url:

      :returns: boolean $alse is url is used

   .. php:method:: adminModel::addBlogCategory()

      adds a blog category to the database
      

      :param string $name:
      :param string $url:
      :param number $parent:

   .. php:method:: adminModel::getBlogCategories()

      returns all blog categories
      

      :returns: array

   .. php:method:: adminModel::checkBlogCategory()

      check if a blog category name or url already exist
      

      :param string $name:
      :param string $url:

      :returns: array

   .. php:method:: adminModel::addBlogTag()

      add a new blog tag to the database
      

      :param string $name:
      :param string $url:

   .. php:method:: adminModel::checkBlogTag()

      check if a blog tag name or url already exist
      

      :param string $name:
      :param string $url:

      :returns: array

   .. php:method:: adminModel::getBlogTags()

      returns all blog tags
      

      :returns: array

   .. php:method:: adminModel::addBlogPost()

      add a blog post to the database
      

      :param string $url:
      :param string $title:
      :param string $subtitle:
      :param string $excerpt:
      :param string $body:
      :param int $epoch:
      :param int $live:

      :returns: int $id

   .. php:method:: adminModel::modBlogPost()

      modify a blog post int the database
      

      :param int $post_id:
      :param string $url:
      :param string $title:
      :param string $subtitle:
      :param string $excerpt:
      :param string $body:
      :param int $epoch:
      :param int $live:

      :returns: int $id

   .. php:method:: adminModel::delBlogPost()

      delete blog post from the database
      

      :param int $id:

   .. php:method:: adminModel::addBlogMeta()

      add blog meta data to the database
      

      :param int $id:
      :param string $key:
      :param string $val:

   .. php:method:: adminModel::delBlogMeta()

      delete blog meta data to the database
      

      :param int $id:
      :param string $key:
      :param string $val:

   .. php:method:: adminModel::getBlogAndMetaByID()

      returns a blog post and it's meta by id
      

      :param int $id:

   .. php:method:: adminModel::getGalleryCategories()

      returns all gallery categories
      

      :returns: array

   .. php:method:: adminModel::getGalleryCatByID()

      returns a gallery category by id
      

      :param int $id:

      :returns: array

   .. php:method:: adminModel::checkGalleryCategory()

      check if a gallery category name or url already exists
      

      :param string $name:
      :param string $url:

      :returns: array

   .. php:method:: adminModel::addGalleryCategory()

      adds a gallery category to the database
      

      :param number $parent:
      :param string $name:
      :param string $url:
      :param string $title:
      :param string $excerpt:
      :param string $description:
      :param number $live:

   .. php:method:: adminModel::modGalleryCategory()

      modify a gallery category in the database
      

      :param number $id:
      :param number $parent:
      :param string $name:
      :param string $url:
      :param string $title:
      :param string $excerpt:
      :param string $description:
      :param number $live:

   .. php:method:: adminModel::checkGalleryImg()

      check if a gallery image url already exists
      

      :param string $url:

      :returns: array

   .. php:method:: adminModel::addGalleryImg()

      adds a gallery image to the database
      

      :param string $url:
      :param string $filename:
      :param string $title:
      :param string $subtitle:
      :param string $excerpt:
      :param string $description:
      :param number $live:

      :returns: int

   .. php:method:: adminModel::addGalleryImgMeta()

      add gallery image meta data to the database
      

      :param int $id:
      :param string $key:
      :param string $val:

   .. php:method:: adminModel::getGalleryImgByCat()

      get gallery images by category id
      

      :param int $id:

      :returns: array

   .. php:method:: adminModel::getGalleryImgAndMetaByID()

      get gallery image and metadata by id
      

      :param int $id:

      :returns: array

   .. php:method:: adminModel::modGalleryImg()

      modify a gallery image in the database
      

      :param int $id:
      :param string $url:
      :param string $title:
      :param string $subtitle:
      :param string $excerpt:
      :param string $description:
      :param number $live:

   .. php:method:: adminModel::delGalleryImg()

      delete gallery image from the database
      

      :param int $id:

   .. php:method:: adminModel::delGalleryImgMeta()

      delete gallery image meta data from the database
      

      :param int $id:
      :param string $key:
      :param string $val:

   .. php:method:: adminModel::getGalleryImgCount()

      get a count of images in a given gallery category
      

      :param int $id:

   .. php:method:: adminModel::getSubGalleryCount()

      get a count of subgalleries for a given gallery category
      

      :param int $id:

   .. php:method:: adminModel::delGalleryAndImgs()

      deletes a category from the database. if the second parameter is 1
      the images in that category will be deleted. if the parameter is 0
      the images will become uncategorized.
      

      :param int|float $id: gallery_id
      :param int $delete: boolean

      :returns: array $ist of file names

   .. php:method:: adminModel::checkCodeRoute()

      check to see if a url route for code is used or not.
      

      :param string $url:

   .. php:method:: adminModel::addCode()

      add a git repo to the database
      

      :param array $args:

   .. php:method:: adminModel::getCodes()

      return all repos

   .. php:method:: adminModel::getCode()

      return all repos
      

      :param int $id:

   .. php:method:: adminModel::modCode()

      modify a git repo in the database
      

      :param array $args:

   .. php:method:: adminModel::delCode()

      deletes a git repo from the database.
      

      :param int $id: