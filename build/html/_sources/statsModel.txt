Statistics Model
****************

.. php:class:: statsModel

      stats model
      SQL functions for statistical analysis
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.0

      :package: app

      :subpackage: models

   .. php:method:: statsModel::__construct()

      constructor
      set the database type to mySQL

   .. php:method:: statsModel::save()

      save function
      insert the statistics into the database
      

      :param array $stats:

   .. php:method:: statsModel::browsers()

      browsers
      retrieve statistics about users browsers
      

      :param int $start: starting date
      :param int $end: ending date

      :returns: array

   .. php:method:: statsModel::platforms()

      platforms
      retrieve statistics about users opertating system platforms
      

      :param int $start: starting date
      :param int $end: ending date

      :returns: array

   .. php:method:: statsModel::resolutions()

      resolutuions
      retrieve statistics about screen resolutions
      

      :param int $start: starting date
      :param int $end: ending date

      :returns: array

   .. php:method:: statsModel::resolutions_count()

      resolutions count
      retrieve a count of the statistics about screen resolutions
      

      :param int $start: starting date
      :param int $end: ending date

      :returns: array

   .. php:method:: statsModel::flash()

      flash
      retrieve statistics about users flash plugin version
      

      :param int $start: starting date
      :param int $end: ending date

      :returns: array

   .. php:method:: statsModel::visits()

      visits
      retrieve a count of the statistics about visits
      

      :param int $start: starting date
      :param int $end: ending date

      :returns: array

   .. php:method:: statsModel::visits_div()

      visits
      retrieve statistics about visits
      

      :param int $start: starting date
      :param int $end: ending date

      :returns: array

   .. php:method:: statsModel::location()

      location
      retrieve statistics about users location
      

      :param int $start: starting date
      :param int $end: ending date

      :returns: array

   .. php:method:: statsModel::resource()

      resource
      retrieve statistics about users browsers
      

      :param int $start: starting date
      :param int $end: ending date
      :param int $limit: number of results

      :returns: array

   .. php:method:: statsModel::referrers()

      referrers
      retrieve statistics about referring sites
      

      :param int $start: starting date
      :param int $end: ending date
      :param int $limit: number of results

      :returns: array

   .. php:method:: statsModel::searches()

      searches
      retrieve statistics about referring search engines
      

      :param int $start: starting date
      :param int $end: ending date

      :returns: array