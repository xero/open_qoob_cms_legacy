Registry Core Class
*******************

.. php:class:: registry

      global registry class
      passing a string name to the static register function will
      first check the previous existance of a class. if found
      it's instance is returned. otherwise, it will check if
      the class exists in the core classes folder, the core
      utilities folder, or the application controllers folder.
      if found, an instance of the class will be created,
      added to the object registry, then returned. if the db
      boolean is passed the database singleton is returned.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.01

      :package: qoob

      :subpackage: core.mvc

      :example: registry::register(qoob_types::utility, $xbd');

   .. php:method:: registry::register()

.. php:class:: qoob_types

      qoob class types

      used by the register function for correct import locations.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: qoob

      :subpackage: core.mvc

   .. php:const:: qoob_types:: application = "app";

      :var: application

   .. php:const:: qoob_types:: core = "core";

      :var: core

   .. php:const:: qoob_types:: utility = "util";

      :var: utility

   .. php:const:: qoob_types:: controller = "controller";

      :var: controller