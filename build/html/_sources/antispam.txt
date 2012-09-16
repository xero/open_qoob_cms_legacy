AntiSpam Utility
****************

.. php:class:: antispam

      antispam class
      functions for using the Akismet spam protection system.
      check out the full api docs at: http://akismet.com/development/api/ ::

        $vars = array(
            'user_ip'               => $_SERVER['REMOTE_ADDR'],
            'user_agent'            => $_SERVER['HTTP_USER_AGENT'],
            'referrer'              => $_SERVER['HTTP_REFERER'],
            'comment_author'        => $name,
            'comment_author_email'  => $from,
            'comment_content'       => $msg
        );
        if($this->antispam->test($vars)) {
          //---spam!
          header("Location: ".QOOB_DOAMIN."spam");
        } else {
          //---send email
          mail($to, $subject, $msg, $from_header);
          header("Location: ".QOOB_DOAMIN."thank_you");
        }

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 1.1

      :package: qoob

      :subpackage: utils

      :example: $this->library(qoob_types::utility, $antispam');

   .. php:method:: antispam::antispam()

      constructor
      setup the akismet API key, site url and name.
      

      :param string $key: API key
      :param string $site: page being protected
      :param string $name: user-agent string to prepend

   .. php:method:: antispam::test()

      test function
      test your string against the akismet database/ruleset
      

      :param string $vars: info about the comment, in key/val pairs

      :returns: boolean $rue if it's spam, false if not

   .. php:method:: antispam::spam()

      spam function
      mark as spam
      

      :param string $vars: info about the comment, in key/val pairs

      :returns: boolean $rue on success

   .. php:method:: antispam::ham()

      ham function
      mark as ham (not spam)
      

      :param string $vars: info about the comment, in key/val pairs

      :returns: boolean $rue on success