Database Session Core Class
***************************

.. php:class:: dbsession

      database session class
      used to manipulate session data, but saved in the database.
      why? for added security and use across domains.
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.0

      :package: qoob

      :subpackage: core.users

   .. php:method:: dbsession::__construct()

      constructor
      the magic method that starts the session (if necessary).
      

   .. php:method:: dbsession::regenerate()

      regenerator
      creates a new random session id
      

      :todo: should this also reset the expiration???

   .. php:method:: dbsession::open()

      open function
      load the session model

   .. php:method:: dbsession::close()

      close function
      does nothing???

   .. php:method:: dbsession::read()

      read function
      

      :param string $session_id:

      :returns: string

   .. php:method:: dbsession::write()

      write function
      

      :param string $session_id:
      :param string $session_data:

   .. php:method:: dbsession::destroy()

      destroy function
      

      :param string $session_id:

   .. php:method:: dbsession::clean()

      garbage collection
      

      :param int $maxlifetime:

   .. php:method:: dbsession::countUsers()

      count the users currently online
      

      :returns: int

   .. php:method:: dbsession::fingerprinting()

      fingerprinting
      creates an MD5 fingerprint of the user.
      based on user-agent, the first 2 blocks
      of the ip address, the current session id,
      and a user defined salt.
      

      :returns: string

   .. php:method:: dbsession::randomHash()

      random hash
      generates a random MD5 hash.
      

      :returns: string

   .. php:method:: dbsession::validate()

      validation
      checks if a users session fingerprint matches
      a newly generated fingerprint.
      

      :returns: boolean