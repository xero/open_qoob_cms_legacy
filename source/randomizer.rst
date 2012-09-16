Randomizer Utility
******************

.. php:class:: randomizer

      randomizer class
      a php pseudo-random number generator (PRNG) using a mersenne twister algorithm
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 8.06

      :package: qoob

      :subpackage: utils

      :category: math

   .. php:attr:: $data

   .. php:method:: randomizer::add()

   .. php:method:: randomizer::getData()

   .. php:method:: randomizer::optimize()

   .. php:method:: randomizer::shuffle()

      shuffle
      shuffles the array using the Fisher-Yates shuffle.

      D. E. Knuth: The Art of Computer Programming, Volume 2,
      Third edition. Section 3.4.2, Algorithm P, pp 145. Reading:
      Addison-Wesley, 1997. ISBN: 0-201-89684-2.

      R. A. Fisher and F. Yates: Statistical Tables. London, 1938.

   .. php:method:: randomizer::reseed()

   .. php:method:: randomizer::select()

   .. php:method:: randomizer::select_unique()

   .. php:method:: randomizer::select_weighted()

   .. php:method:: randomizer::select_weighted_unique()