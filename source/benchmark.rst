Benchmarking Utility
********************

.. php:class:: benchmark

      benchmark class
      this class enables you to mark points and calculate the
      time difference between them.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.01

      :package: qoob

      :subpackage: utils

   .. php:attr:: $markers

   .. php:method:: benchmark::mark()

      set a benchmark marker
      multiple calls to this function can be made so that several
      execution points can be timed.
      

      :param string $name: name of the marker

      :returns: void

   .. php:method:: benchmark::elapsed_time()

      elapsed time function
      calculates the time difference between two marked points.
      

      :param string $point1: a particular marked point
      :param string $point2: a particular marked point
      :param int $decimals: the number of decimal places

      :returns: mixed