Inflector Utility
*****************

.. php:class:: inflector

      inflector class
      functions for formatting string into diffrent nomeclatures.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 3.2

      :package: qoob

      :subpackage: utils

   .. php:method:: inflector::camelize()

      the camelize function takes a space " " or underscore "_"
      delimited string and returns a CamelCase string.
      

      :static:

      :example: "open qoob framework" = "OpenQoobFramework"
      :example: "o_p_e_n q_o_o_b" = "OPENQOOB"
      :param string $str:

      :returns: string

   .. php:method:: inflector::underscore()

      the underscore function takes a space " " delimited
      or CamelCase strings and returns a *lowercase*
      undercore "_" delimited string.
      

      :static:

      :example: "open qoob framework" = "open_qoob_framework"

      :example: "CamelSyntaxIsAwesome" = "camel_syntax_is_awesome"
      :param string $str:

      :returns: string

   .. php:method:: inflector::humanize()

      the humanize function takes an underscore "_" delimited
      string and returns a space " " delimited string.
      

      :example: "open_qoob_framework" = "open qoob framework"
      :param string $str:

      :returns: string

   .. php:method:: inflector::clean()

      a function to remove all characters besides
      letters, numbers, underscores, and hyphens.

   
      :example: "o!p@e#n$ $q^o~o*b" = "open qoob"
      :param string $str:

      :returns: string

   .. php:method:: inflector::ordinalize()

      ordinalize function
      converts a number to its english ordinal form.
   
      :example: 1 = 1st
      :example: 13 = 13th
      :param integer $number:

      :returns: string