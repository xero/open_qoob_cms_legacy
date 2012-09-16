Upload Utility
**************

.. php:class:: upload

      upload manager
      a custom class to help manage file uploads for the site
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.4

      :package: qoob

      :subpackage: utils

   .. php:method:: upload::__construct()

      constructor
      set's the default directory

   .. php:method:: upload::setDirectory()

      setter method for for the upload directory
      the $location variable is always a subfolder
      under the QOOB_ROOT/uploads directory, unless
      the keyword "root" is passed. then the directory
      is set to the root for the uploads folder.
      

      :param string $location:

   .. php:method:: upload::getDirectory()

      getter method for the upload directory
      

      :returns: string

   .. php:method:: upload::getExtention()

      returns the extention of a given file
      

      :param string $name: $_FILES["file"]["name"]

      :returns: string

   .. php:method:: upload::testMIME()

      tests if a given file's type is in the allowed list
      

      :param string $type: $_FILES["file"]["type"]

      :returns: boolean

   .. php:method:: upload::exists()

      check to see if a file already exists in the upload directory
      

      :param string $name:

      :returns: boolean

   .. php:method:: upload::setMIMES()

      set allowed file types
      

      :param array $mimes:

   .. php:method:: upload::file()

      upload the file
      

      :param string $tmpfile: $_FILES["file"]["tmp_name"]
      :param string $cleanfilename:

   .. php:method:: upload::delete()

      delete a file from the server.
      chmod the file to 0666 then unlink it.
      this might be dangerious... use with caution!
      

      :param string $filename:

      :returns: boolean

   .. php:method:: upload::writeFile()

      manually write a file to the server.
      

      :param string $filename:
      :param string $data:

      :returns: boolean