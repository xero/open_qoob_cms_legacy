Routes Model
************

.. php:class:: routes

      routes model
      SQL functions for url routing
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: app

      :subpackage: models

   .. php:method:: routes::__construct()

      constructor function
      sets the database adapter type to mySQL.

   .. php:method:: routes::checkRoute()

      check route
      checks if a given url segment exists in the database.
      

      :param string $name: url segment
      :param int $parent: id number of parent url segment (default = 0)