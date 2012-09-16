Open QOOB Core Class
********************

.. php:class:: open_qoob

      open qoob main class
      this class calls the bootstrapper function to
      load the necessary base classes, then initilizes
      the error handeling and main url routing classes.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.2

      :package: qoob

   .. php:method:: open_qoob::__construct()

      open_qoob constructor
      loads the core classes with the the bootstrapper,
      sets up error handeling, initilizes the config,
      then executes the url routing.