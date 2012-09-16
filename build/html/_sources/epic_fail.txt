Epic Fail Core Class
********************

.. php:class:: epic_fail

      epic fail class
      global error and exception handlers.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.01

      :package: qoob

      :subpackage: core.data

   .. php:method:: epic_fail::__construct()

      constructor function
      setup global error and exception handlers.

   .. php:method:: epic_fail::exception_handler()

      exception handler
      create qoob pages for exceptions.
      

      :param object $exc: the php exception object

   .. php:method:: epic_fail::error_handler()

      error handler
      create qoob pages for errors.
      

      :param int $num: the error code
      :param string $str: the error message
      :param string $file: the file throwing the error
      :param int $line: the line number in the file throwing the error
      :param array $ctx: the context of the error