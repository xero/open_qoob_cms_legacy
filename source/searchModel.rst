Search Model
************

.. php:class:: searchModel

      search model
      SQL functions for user searches
      

      :author: andrew harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: app

      :subpackage: models

   .. php:method:: searchModel::__construct()

      constructor function
      sets the database adapter type to mySQL.

   .. php:method:: searchModel::searchPages()

      search the pages table of the database
      

      :param string $terms:

      :returns: array

   .. php:method:: searchModel::searchBlog()

      search the blog table of the database
      

      :param string $terms:

      :returns: array

   .. php:method:: searchModel::searchCode()

      search the code table of the database
      

      :param string $terms:

      :returns: array

   .. php:method:: searchModel::searchGallery()

      search the gallery table of the database
      

      :param string $terms:

      :returns: array

   .. php:method:: searchModel::getParentGallery()

      get the parent gallery
      

      :param int $id:

      :returns: array