AntiHack Utility
****************

.. php:class:: antihack

      antihack
      an advanced attack detection mechanism, providing functions to
      scan incoming data for malicious appearing script fragments.

      detects many variants of XSS, SQL injection, header injection, directory traversal,
      RFE/LFI, DoS and LDAP attacks, and through special conversion algorithms is even
      able to detect heavily obfuscated attacks – this covers several charsets like UTF-7,
      entities of all forms – such as javascript unicode, decimal, and hex-entities as
      well as comment obfuscation, obfuscation through concatenation, shell code and
      many other variants.

      furthermore it is able to detect yet unknown attack patterns with the centrifuge component.
      this component does in depth string analysis and measurement and detects about 85% to 90% of
      all tested vectors given a minimum length of 25 characters.

      antihack is a fork of the PHPIDS (PHP-Intrusion Detection System) by Mario Heiderich
      Copyright (GNU) v3 2008 PHPIDS group (https://phpids.org)
      

      :author: Mario Heiderich

      :author: Christian Matthies

      :author: Lars Strojny

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 3.6

      :package: qoob

      :subpackage: utils.ice

      :category: intrusion countermeasure extensions

   .. php:method:: antihack::__construct()

      constructor
      setup defaults and loads the xml filter rules

   .. php:method:: antihack::run()

      run
      test an array of values $request against the PHP-IDS
      rule set. array keys in $ignore will not be tested.
      

      :param array $request: values to be tested
      :param array $ignore: values to be ignored

      :returns: array

.. php:class:: threat_report

      threat report
      an array of information that is returned to the user

   .. php:method:: threat_report::make()

      make function
      generate the threat report
      

      :returns: array

   .. php:method:: threat_report::checkImpact()

      check impact
      return the current impact value
      

      :returns: int $impact

   .. php:method:: threat_report::setAttacker()

      set attacker
      info about the attack origin point
      

      :param $ip $tring: attackerIP
      :param $host $tring: attackerHostName default 'unknown'

   .. php:method:: threat_report::addImpact()

      add impact
      increase the attack severity level
      

      :param $impact $nt:

   .. php:method:: threat_report::addTag()

      add tag
      metadata about the attack vector
      

      :param $tag $tring:

   .. php:method:: threat_report::addRule()

      add rule
      the rule that was broken
      

      :param $rule $tring:

   .. php:method:: threat_report::addVector()

      the attack vector

      :param $vector $tring:

      .. warning:: 
         live attack code! use at your own risk!