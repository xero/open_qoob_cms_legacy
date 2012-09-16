Tag Cloud Utility
*****************

.. php:class:: cloud

      tag cloud
      takes an array of tags (tag_id, name, url, tag_count)
      and generates an html tag cloud from them.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: qoob

      :subpackage: utils

   .. php:method:: cloud::setMax()

      set maximun font size

      :param int $max:

   .. php:method:: cloud::setMin()

      set minimum font size

      :param int $min:

   .. php:method:: cloud::generate()

      generate function
      returns the html tag cloud from an array.
      

      :param array $tags:

      :returns: string

   .. php:method:: cloud::make()