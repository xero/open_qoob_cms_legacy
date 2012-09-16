QOOB Config Core Class
**********************

.. php:class:: qoob_config

      qoob_config class
      acts as install qoob_config file. it's uses for storing variables needed to
      run the qoob in the global library (e.g. database connection information).
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: qoob

      :subpackage: core.data

   .. php:attr:: $data

      data the array that holds the data

      :var: array

   .. php:method:: qoob_config::__construct()

      constructor magic method
      calls the init then execute functions

   .. php:method:: qoob_config::init()

      init function
      to be extended by the user to set variables in

   .. php:method:: qoob_config::execute()

      execute function
      adds data into the global library
      

      :see: library.php