Code Model
**********

.. php:class:: codeModel

      code model
      SQL functions for loading code information from the database
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: app

      :subpackage: models

   .. php:method:: codeModel::__construct()

      constructor function
      sets the database adapter type to mySQL.

   .. php:method:: codeModel::getRepos()

      get repositories
      

      :returns: array

   .. php:method:: codeModel::getRepo()

      get repository by url
      

      :param string $url:

      :returns: array