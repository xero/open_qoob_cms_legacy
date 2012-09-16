Controller Core Class
*********************

.. php:class:: controller

      controller class
      this base class has all the necessary loading
      functions so that controllers can load models,
      views, and libraries (e.g. utilities).
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 3.7

      :package: qoob

      :subpackage: core.mvc

   .. php:method:: controller::__construct()

      controller constructor
      the php magic method to be overridden by your custom
      controller classes. use the $classes parameter to
      auto-load needed utility classes (e.g. session)
      

      :param array $classes:

      :example: parent::__construct(array("session" $> array("type" => qoob_types::core, "class" => "session", "dir" => "users/")));

   .. php:method:: controller::library()

      library loader function
      used to register classes into the qoob framework as public functions
      in your controller. use them in $this->class->method format.
      

      :param string $type:
      :param string $class:
      :param string $path:
      :param boolean $singleton:

   .. php:method:: controller::view()

      view loader function
      used to load a view into the qoob framework. any data in the optional
      $data array is extracted and becomes available in the php code in the
      view file loaded. in the $string boolean is set to true, the rended
      view code is returned from this function. otherwise the view is just
      included and immediatly rendered.
      

      :param string $view:
      :param array $data:
      :param boolean $string:

      :returns: string

   .. php:method:: controller::model()

      model loader function
      used to load a database model into the qoob framework.
      if the $data array is not null, it will be passed to
      the models constructor.
      

      :param string $model:
      :param array $data:

      :returns: class

   .. php:method:: controller::logMSG()

      log message function
      writes data to a given log file.
      

      :param string $msg: the message to save
      :param string $file: the filename to save to