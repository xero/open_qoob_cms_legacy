BBcode Parser Utility
*********************

.. php:class:: bbcode

      BBCode Parser
      takes bbcode strings, and returns html equivalents.
      based on http://www.phpit.net/article/create-bbcode-php/
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.0

      :package: qoob

      :subpackage: utils

   .. php:method:: bbcode::format()

      format
      takes a bbcode string and creates it's html equivalent.
      

      :param string $str: the bbcode string

      :returns: string

   .. php:method:: bbcode::quote()

      quote
      create an html quote box from bb codes
      

      :param string $str: the string to be quoted

      :returns: string