Blog Model
**********

.. php:class:: blogModel

      blog model
      SQL functions for loading blog information from the database
      

      :author: andrew harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 3.72

      :package: app

      :subpackage: models

   .. php:method:: blogModel::__construct()

      constructor function
      sets the database adapter type to mySQL.

   .. php:method:: blogModel::checkCategory()

      check category
      check if a category url exists in the database
      

      :param string $url:

      :returns: array

   .. php:method:: blogModel::checkSubCategory()

      check subcategory
      check if a subcategory exists in the database
      

      :param int $cat:
      :param string $sub:

      :returns: array

   .. php:method:: blogModel::checkPost()

      check post
      check if a post url exists in the database
      

      :param string $url:

      :returns: array

   .. php:method:: blogModel::getNewest()

      get newest post
      fetches a given nuber of the newest blog posts
      

      :param int $count:

      :returns: array

   .. php:method:: blogModel::getPosts()

      get posts by page
      fetches a given nuber of the blog posts by page offset
      

      :param int $page:
      :param int $per:

      :returns: array

   .. php:method:: blogModel::getTotalPostCount()

      get total post count
      returns the total number of posts
      in the blog. used for paging functions
      

      :returns: array $total'

   .. php:method:: blogModel::getPostTags()

      get post tags
      returns tags for a given post
      

      :param int $id:

      :returns: array

   .. php:method:: blogModel::getPostCats()

      get post categories
      returns all categories for a given post
      

      :param int $id:

      :returns: array

   .. php:method:: blogModel::getCatByID()

      get category by id
      returns a category for a given id
      

      :param int $id:

      :returns: array

   .. php:method:: blogModel::getPostByURL()

      get post by url
      returns a post for a given url segment
      

      :param string $url:

      :returns: array

   .. php:method:: blogModel::getPostsBySubCat()

      get posts by sub category
      returns posts from a given sub category
      page is the offset and per is the number
      of items returned.
      

      :param int $subid:
      :param int $page:
      :param int $per:

      :returns: array

   .. php:method:: blogModel::getSubCatPostCount()

      get post count by sub category
      returns the total number of posts
      in a sub category. used for paging functions
      

      :param int $subid:

      :returns: array

   .. php:method:: blogModel::getPostsByCat()

      get posts by category
      returns posts from a given category
      page is the offset and per is the
      number of items returned.
      

      :param int $catid:
      :param int $page:
      :param int $per:

      :returns: array

   .. php:method:: blogModel::getCatPostCount()

      get post count by category
      returns the total number of posts
      in a category. used for paging functions
      

      :param int $catid:

      :returns: array

   .. php:method:: blogModel::checkTag()

      check tag
      returns the id of a tag url
      

      :param string $tag:

      :returns: array

   .. php:method:: blogModel::getPostsByTag()

      get posts by tag
      returns posts with a given tag.
      page is the offset and per is the
      number of items returned.
      

      :param int $tag:
      :param int $page:
      :param int $per:

      :returns: array

   .. php:method:: blogModel::getTagPostCount()

      get post count by tag
      returns the total number of posts
      with a given tag. used for paging functions
      

      :param int $tag:

      :returns: array

   .. php:method:: blogModel::getTags()

      returns all blog tags
      

      :returns: array

   .. php:method:: blogModel::getNewestTweet()

      returns the newest tweet
      

      :returns: array

   .. php:method:: blogModel::getFirstPost()

      get first post
      returns the first post
      

      :returns: array

   .. php:method:: blogModel::getPrevPost()

      get previous post
      returns the previous post
      relative to the given date
      

      :param int $date:

      :returns: array

   .. php:method:: blogModel::getNextPost()

      get next post
      returns the next post
      relative to the given date
      

      :param int $date:

      :returns: array

   .. php:method:: blogModel::getBlogCategories()

      returns all blog categories
      

      :returns: array