QR Code Utility
***************

.. php:class:: qr

      QRcode image generator
      This program outputs a png image of "QRcode model 2".
      This version supports QRcode model2 version 1-40.
      This program requires PHP4.1+ and gd 1.6+.
      based on code by Y.Swetake - http://www.swetake.com/qr/ ::

        d= data         QR code data.
        e= ECC level    L or M or Q or H   (default M)
        s= module size  (dafault PNG:4 JPEG:8)
        v= version      1-40 or Auto select if you do not set.
        t= image type   J:jpeg image , other: PNG image

        d : Data you want to encode to QRcode.
            A special letter like '%'.space or 8bit letter must be URL-encoded.
            You cannot omit this parameter.

        e : Error correct level
            You can set 'L','M','Q' or 'H'.

            If unet, 'M' is the default.

        s : module size
            This parameter is no effect in HTML mode.
            You can set a number more than 1.
            Image size depends on this parameter.

            If unset, defaults are '4' in PNG mode or '8' in JPEG mode.

        v : version
            You can set 1-40.
            
            If unset, program automatically selects.

        t : image type
            You can set 'J' or 'P'
            'J' : jpeg mode.
            'P' : png mode.

            If unset, PNG mode is the default.
      

      :author: Y.Swetake

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :package: utils

      :version: 2.01

      :category: quick $esponse code

      :example: $this->qr->generate("http://andrew.harrison.nu/", "L", 3, 5);

   .. php:method:: qr::generate()