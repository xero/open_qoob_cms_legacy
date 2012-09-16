Hash Utility
************

.. php:class:: hash

      hash class
      functions creating password hashes.
      based on ideas from Nils Reimers (www.php-einfach.de)
      ImprovedHashAlgorithm (IHA) released open-source under
      the GNU Lesser General Public License version 2.1
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.3

      :package: qoob

      :subpackage: utils.crypto

      :category: cryptography

   .. php:method:: hash::make()

      make hash function
      returns a strong hash value from a weak password. ::

         $this->hash->saltLength = 10;
         $this->hash->salt = "aCgbEDzq9h";
         $this->hash->sha1 = true;
         $this->hash->rounds = 5000;
         $hash = $this->hash->make($pass);

         // $hash would be something like:
         // gAATiA;aCgbEDzq9h;iPl9xBKgfBGtE6iR4pQU1g5VgKs=
      

      :example: $this->library(qoob_types::utility, $hash", "crypto/");
      :param string $pass:

      :returns: string

   .. php:method:: hash::compare()

      compare function
      checks if the value of $pass belongs to the value of $hash.      

      :param string $pass:
      :param string $hash:

      :returns: boolean

   .. php:method:: hash::benchmark()

      benchmark function
      makes a benchmark of the key stretching method.
      $times is the number of hashes to preform and average.
      

      :param int $times:

      :returns: string

   .. php:method:: hash::__set()

      variable setter magic method
      

      :param string $var:
      :param string $val:

   .. php:method:: hash::__get()

      variable getter magic method
      

      :param string $var:

      :returns: mixed