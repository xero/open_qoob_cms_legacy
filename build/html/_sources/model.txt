Model Core Class
****************

.. php:class:: model

      model class
      this base class has the necessary functions
      to load a database adapter or a library class
      (e.g. utilities).
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.0

      :package: qoob

      :subpackage: core.mvc

   .. php:attr:: $DB

      database hook
      use this variable to access database functions like so:
      $yourModel->DB->query("SELECT * FROM `whatever` LIMIT 1");
      

      :var: iDB

   .. php:method:: model::__construct()

      model constructor
      change the database type by passing the string name
      to the constructor. mysql is the default.
      

      :param string $dbtype:

   .. php:method:: model::library()

      library loader function
      used to register classes into the qoob framework as public functions
      in your controller. use them in $this->class->method format.
      

      :param string $type:
      :param string $class:
      :param string $path:
      :param boolean $singleton: