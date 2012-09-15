Pages Model
***********

.. php:class:: pagesModel

      pages model
      SQL functions for loading pages
      

      :author: andrew harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: app

      :subpackage: models

   .. php:method:: pagesModel::__construct()

      constructor function
      sets the database adapter type to mySQL.

   .. php:method:: pagesModel::getPage()

      get page function
      fetches a page's content.
      

      :param string $url:

      :returns: array