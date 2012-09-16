Statistics Controller
*********************
.. php:class:: stats

      stats controller
      class for creating, validating, and saving statistic information. user and browser information is gathered by including a javascript function in the page. that code is generated in the js function.      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 7.7

      :package: app

      :subpackage: controllers

   .. php:method:: stats::index()
      index function
      there is no default action! so this redirects the user to the qoob root.

   .. php:method:: stats::js()
      js function
      display the javascript to gather and send the statistics

   .. php:method:: stats::save()
      save function
      recieves the data from the javascript function, analyzes it, and saves it to the database

   .. php:method:: stats::generateKey()
      generate key function
      generate a simple key to hide in the javascript. the key is the current epoch time with a random number (between 9-18) of random letters mixed in.

   .. php:method:: stats::verifyKey()
      verify key function
      decodes the key and determines if it is older than 25 seconds.

      :param string $key: the key to be tested

   .. php:method:: stats::sanitizeURL()
      sanitize URL function
      removes javascript based attack vectors

      :param string $url: the url to be sanitized