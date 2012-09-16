Status Codes Utility
********************

.. php:class:: statusCodes

      status codes
      a class for setting HTTP protocol status codes.
      also provides constants for easy use.

      thanx to Kris Jordan of the Recess Framework (http://www.recessframework.com/)
      for coming up with such a cool idea.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.0

      :package: qoob

      :subpackage: utils

      :category: header

   .. php:const:: statusCodes:: HTTP_CONTINUE = 100;

   .. php:const:: statusCodes:: HTTP_SWITCHING_PROTOCOLS = 101;

   .. php:const:: statusCodes:: HTTP_OK = 200;

   .. php:const:: statusCodes:: HTTP_CREATED = 201;

   .. php:const:: statusCodes:: HTTP_ACCEPTED = 202;

   .. php:const:: statusCodes:: HTTP_NONAUTHORITATIVE_INFORMATION = 203;

   .. php:const:: statusCodes:: HTTP_NO_CONTENT = 204;

   .. php:const:: statusCodes:: HTTP_RESET_CONTENT = 205;

   .. php:const:: statusCodes:: HTTP_PARTIAL_CONTENT = 206;

   .. php:const:: statusCodes:: HTTP_MULTIPLE_CHOICES = 300;

   .. php:const:: statusCodes:: HTTP_MOVED_PERMANENTLY = 301;

   .. php:const:: statusCodes:: HTTP_FOUND = 302;

   .. php:const:: statusCodes:: HTTP_SEE_OTHER = 303;

   .. php:const:: statusCodes:: HTTP_NOT_MODIFIED = 304;

   .. php:const:: statusCodes:: HTTP_USE_PROXY = 305;

   .. php:const:: statusCodes:: HTTP_UNUSED= 306;

   .. php:const:: statusCodes:: HTTP_TEMPORARY_REDIRECT = 307;

   .. php:const:: statusCodes:: errorCodesBeginAt = 400;

   .. php:const:: statusCodes:: HTTP_BAD_REQUEST = 400;

   .. php:const:: statusCodes:: HTTP_UNAUTHORIZED  = 401;

   .. php:const:: statusCodes:: HTTP_PAYMENT_REQUIRED = 402;

   .. php:const:: statusCodes:: HTTP_FORBIDDEN = 403;

   .. php:const:: statusCodes:: HTTP_NOT_FOUND = 404;

   .. php:const:: statusCodes:: HTTP_METHOD_NOT_ALLOWED = 405;

   .. php:const:: statusCodes:: HTTP_NOT_ACCEPTABLE = 406;

   .. php:const:: statusCodes:: HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;

   .. php:const:: statusCodes:: HTTP_REQUEST_TIMEOUT = 408;

   .. php:const:: statusCodes:: HTTP_CONFLICT = 409;

   .. php:const:: statusCodes:: HTTP_GONE = 410;

   .. php:const:: statusCodes:: HTTP_LENGTH_REQUIRED = 411;

   .. php:const:: statusCodes:: HTTP_PRECONDITION_FAILED = 412;

   .. php:const:: statusCodes:: HTTP_REQUEST_ENTITY_TOO_LARGE = 413;

   .. php:const:: statusCodes:: HTTP_REQUEST_URI_TOO_LONG = 414;

   .. php:const:: statusCodes:: HTTP_UNSUPPORTED_MEDIA_TYPE = 415;

   .. php:const:: statusCodes:: HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

   .. php:const:: statusCodes:: HTTP_EXPECTATION_FAILED = 417;

   .. php:const:: statusCodes:: HTTP_INTERNAL_SERVER_ERROR = 500;

   .. php:const:: statusCodes:: HTTP_NOT_IMPLEMENTED = 501;

   .. php:const:: statusCodes:: HTTP_BAD_GATEWAY = 502;

   .. php:const:: statusCodes:: HTTP_SERVICE_UNAVAILABLE = 503;

   .. php:const:: statusCodes:: HTTP_GATEWAY_TIMEOUT = 504;

   .. php:const:: statusCodes:: HTTP_VERSION_NOT_SUPPORTED = 505;

   .. php:method:: statusCodes::getHeader()

      returns the current status code

   .. php:method:: statusCodes::setHeader()

      sets the http header with a given status code

   .. php:method:: statusCodes::getMessage()

      returns the message for a given status code