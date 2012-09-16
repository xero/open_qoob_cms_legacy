Gallery Model
*************

.. php:class:: galleryModel

      gallery model
      SQL functions for loading galleries and their images
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 3.2

      :package: app

      :subpackage: models

   .. php:method:: galleryModel::__construct()

      constructor function
      sets the database adapter type to mySQL.

   .. php:method:: galleryModel::getMainCats()

      get main categories
      

      :returns: array

   .. php:method:: galleryModel::getSubCats()

      get sub categories
      

      :param int $id:

      :returns: array

   .. php:method:: galleryModel::getCat()

      get category by url
      

      :param string $url:

      :returns: array

   .. php:method:: galleryModel::getCatImgs()

      get images by category by id
      

      :param int $id:

      :returns: array

   .. php:method:: galleryModel::getImg()

      get image by url
      

      :param string $url:

      :returns: array