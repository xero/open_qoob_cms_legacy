Library Core Class
******************

.. php:class:: singleton

      singleton class
      a class that can only be instantiated once. every
      subsequent request will return the same instance
      of the class. to be extended.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.0

      :package: qoob

      :subpackage: core.data

   .. php:attr:: $data

      :var: array $data the data held in the singleton class

   .. php:method:: singleton::catalog()

      :static:

      :returns: object

   .. php:method:: singleton::__set()

      set data
      add data to the singleton array
      

      :param string|int $key: the array key
      :param mixed $value: the array value

      :returns: boolean

   .. php:method:: singleton::__get()

      get data
      retrieve data from the singleton array
      

      :param string|int $key: the array key

      :returns: mixed

   .. php:method:: singleton::__clone()

      clone magic method
      calling clone on a singleton will throw an error,
      since the point of a singleton is to only have a single instance of it.
      

      :returns: exception

.. php:class:: qoob_registry

      qoob registry
      a singleton library used to store instances
      of classes in the open qoob framework.

      :see: /qoob/core/mvc/registry.php

.. php:class:: library

      library class
      a singleton library used to store global variables
      in the open qoob framework.

      :see: /qoob/core/data/qoob_config.php