Feeds Controller
****************

.. php:class:: feeds

      feeds controller
      generate rss/atom feeds
      

      :author: andrew harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: app

      :subpackage: controllers

   .. php:method:: feeds::index()

      index function
      mine the url and either throw and error or call a sub function

   .. php:method:: feeds::map()

      map function
      list all the possible feeds

   .. php:method:: feeds::generate()

      generate function
      render the rss/atom feed