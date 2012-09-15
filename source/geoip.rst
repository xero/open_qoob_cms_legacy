GeoIP Utility
*************

.. php:class:: geoip

      geo ip look up functions GeoIP provides a non-invasive way to determine geographical information about their visitors in real-time buy utilizing precompiled lookup databases.

      :copyright: 2007 MaxMind LLC

      :package: qoob

      :subpackage: utils.geoip

   .. php:attr:: $flags

   .. php:attr:: $filehandle

   .. php:attr:: $memory_buffer

   .. php:attr:: $databaseType

   .. php:attr:: $databaseSegments

   .. php:attr:: $record_length

   .. php:attr:: $shmid

   .. php:attr:: $GEOIP_COUNTRY_CODE_TO_NUMBER

   .. php:attr:: $GEOIP_COUNTRY_CODES

   .. php:attr:: $GEOIP_COUNTRY_CODES3

   .. php:attr:: $GEOIP_COUNTRY_NAMES

   .. php:attr:: $GEOIP_CONTINENT_CODES

   .. php:method:: geoip::getCountry()

.. php:function:: geoip_load_shared_mem()

.. php:function:: _setup_segments()

.. php:function:: geoip_open()

.. php:function:: geoip_close()

.. php:function:: geoip_country_id_by_name_v6()

.. php:function:: geoip_country_id_by_name()

.. php:function:: geoip_country_code_by_name_v6()

.. php:function:: geoip_country_code_by_name()

.. php:function:: geoip_country_name_by_name_v6()

.. php:function:: geoip_country_name_by_name()

.. php:function:: geoip_country_id_by_addr_v6()

.. php:function:: geoip_country_id_by_addr()

.. php:function:: geoip_country_code_by_addr_v6()

.. php:function:: geoip_country_code_by_addr()

.. php:function:: geoip_country_name_by_addr_v6()

.. php:function:: geoip_country_name_by_addr()

.. php:function:: _geoip_seek_country_v6()

.. php:function:: _geoip_seek_country()

.. php:function:: _common_get_org()

.. php:function:: _get_org_v6()

.. php:function:: _get_org()

.. php:function:: geoip_name_by_addr_v6()

.. php:function:: geoip_name_by_addr()

.. php:function:: geoip_org_by_addr()

.. php:function:: _get_region()

.. php:function:: geoip_region_by_addr()

.. php:function:: getdnsattributes()