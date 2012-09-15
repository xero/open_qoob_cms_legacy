GIT Code Controller
*******************

.. php:class:: code

      code controller
      class to visualize the code in git repos
      

      :author: andrew harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: app

      :subpackage: controllers

   .. php:method:: code::index()

      index function
      depending on the url this function can:
      - display all the git repositories
      - display trees/blobs
      - display commit history
      - export zip/tarballs of files