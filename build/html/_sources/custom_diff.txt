Custom Diff Utility
*******************

.. php:class:: custom_diff

      custom diff class
      pass two comma separated lists of numbers to the run function
      and it will return an array of the new values, and values removed. ::

   		$result = $this->custom_diff->run('1,2', '2,5');
   			$result = Array (
   			       [add] => Array (
   			            [1] => 5
    			   )
    			   [del] => Array (
   			            [0] => 1
   			        )
   			)

      :author: andrew harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.1

      :package: qoob

      :subpackage: utils

      :example: $this->library(qoob_types::utility, "custom_diff");

   .. php:method:: custom_diff::run()

      run function
      finds the differences, additions and subtractions,
      between two numeric lists.
      

      :param string $old: a comma delimited string
      :param string $new: a comma delimited string

      :returns: array