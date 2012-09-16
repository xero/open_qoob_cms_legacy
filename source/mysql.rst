MySQL Core Class
****************

.. php:class:: dbException

      the error messages thrown from the main mysql class

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 0.5

      :package: qoob

      :subpackage: core.data

   .. php:method:: dbException::__construct()

      constructor
      sets the error code and messag
      

      :param string $message:
      :param int $code: 500

.. php:class:: mySQL

      the main database class (used as a singleton) containing connection variables, open/close routines, query generation/cleaning/execution and auto-id retrieval

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.0

      :package: qoob

      :subpackage: core.data

   .. php:attr:: $db

      :var: object $db the database reference

   .. php:attr:: $instance

      :var: object $instance the singleton instance of the mySQL class

   .. php:attr:: $sql

      :var: string $sql the sql query

   .. php:method:: mySQL::getInstance()

      getInstance
      singleton pattern for instantiating the DB class
      

      :returns: mySQL

   .. php:method:: mySQL::__construct()

      constructor
      calls the init and connect functions

   .. php:method:: mySQL::init()

      initilizer
      sets up database connection varibles from the config file

   .. php:method:: mySQL::connect()

      connect
      creates the mySQL database connection and selects the
      appropriate table. throws a dbException on failure.

   .. php:method:: mySQL::sanitize()

      sanitizing
      simple method for injection attack protection
      

      :param string $string:

      :returns: string

   .. php:method:: mySQL::escape()

      escape
      simple method for injection attack protection which
      replaces any non-ascii character with its hex code.
      

      :param string $string:

      :returns: string

   .. php:method:: mySQL::query()

      SQL query function
      executes a mySQL query.
      make sure all insert, and update statements have
      the results flag set to false.
      

      :param string $statement:
      :param boolean $results:

      :returns: object|boolean

   .. php:method:: mySQL::makeQuery()

      SQL query generation function
      pass this function a stored procedure and an array of parameters
      in name values pairs to replace in the spored procedure.
      

      :param string $sp: stored procedure
      :param array $args:

      :returns: string

   .. php:method:: mySQL::insertID()

      get insertID
      used to get the last inserted record's id
      

      :returns: int|string

   .. php:method:: mySQL::__destruct()

      destructor
      close the connection when finished

.. php:class:: mySQLquery

      a class that formats the database results into php arrays.
      result["row_id"]["column_name"]

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.01

      :package: qoob

      :subpackage: core.data


   .. php:attr:: $result

   .. php:method:: mySQLquery::__construct()

      constructor
      gets the results of the mySQL query
      or throws a dbException error.
      

      :param string $query:
      :param object $link: mysql_connection

   .. php:method:: mySQLquery::result()

      get result
      returns the results of the mySQL query
      

      :returns: array

   .. php:method:: mySQLquery::num_rows()

      number of rows
      returns the number of rows in a given result
      

      :returns: int

   .. php:method:: mySQLquery::__destruct()

      destructor
      call's free result only if result has be used