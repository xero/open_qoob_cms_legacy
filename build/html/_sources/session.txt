Session Core Class
******************

.. warning::
   This class is deprecated!

.. php:class:: session

      session class
      used to manipulate session data
      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 2.522

      :package: qoob

      :subpackage: core.users

   .. php:method:: session::__construct()

      constructor
      the magic method that starts the session (if necessary).

   .. php:method:: session::singleton()

      singleton
      the singleton function either returns the existing instance
      of session class. otherwise it creates an instance of the
      class then returns it.
      

      :returns: session

   .. php:method:: session::regenerate()

      regenerator
      creates a new random session id

   .. php:method:: session::set()

      setter
      set values into the session
      

      :param string $key:
      :param mixed $val:

   .. php:method:: session::set_data()

      array setter
      set values into the session from an array
      

      :param array $data:

   .. php:method:: session::get()

      getter
      returns values from the session.
      the the key is not found, it returns false.
      

      :param string $key:

      :returns: mixed $tring|boolean

   .. php:method:: session::destroy()

      destroyer
      removes all data from a session.

   .. php:method:: session::fingerprint()

      fingerprint
      creates an MD5 fingerprint of the user.
      based on user-agent, the first 2 blocks
      of the ip address, the current session id,
      and a user defined salt.
      

      :returns: string

   .. php:method:: session::validate()

      validation
      checks if a users session fingerprint matches
      a newly generated fingerprint.
      

      :returns: boolean

   .. php:method:: session::randomHash()

      random hash
      generates a random MD5 hash.
      

      :returns: string

   .. php:method:: session::setup()

      create a qoob session
      

      :param int $id:
      :param string $name:
      :param string $username:
      :param string $email: