Paging Utility
**************

.. php:class:: paging

      paging utility
      this class is used to generate paging links.

      use the "init" function to setup the variables necessary
      to generate the paging links, then call the "render" function
      to create the html code.::

      	$this->library(qoob_types::utility, "paging");
      	$this->paging->init(array("base_url" => "http://dev.cet.edu/qoob/videos/page/",
      						      "total_rows" => $count,
      							  "per_page" => $limit,
      							  "cur_page" => $page,
      							  "num_tag_open" => '<div class="page">',
      							  "num_tag_close" => "</div>",
      							  "cur_tag_open" => '<div class="cur_page">',
      							  "cur_tag_close" => "</div>",
      							  "next_tag_open" => '<div class="next_page">',
      							  "next_tag_close" => "</div>",
      							  "prev_tag_open" => '<div class="prev_page">',
      							  "prev_tag_close" => "</div>",
      							  "first_tag_open" => '<div class="first_page">',
      							  "first_tag_close" => "</div>",
      							  "last_tag_open" => '<div class="last_page">',
      							  "last_tag_close" => "</div>",
      							  "full_tag_open" => '<div id="paging">',
      							  "full_tag_close" => "</div>"));
      	$html["body"] .= $this->paging->render();      

      :author: xero harrison

      :copyright: creative commons - attribution-shareAlike 3.0 unported

      :version: 3.2

      :package: qoob

      :subpackage: utils

   .. php:attr:: $base_url

   .. php:attr:: $total_rows

   .. php:attr:: $per_page

   .. php:attr:: $num_links

   .. php:attr:: $cur_page

   .. php:attr:: $first_link

   .. php:attr:: $next_link

   .. php:attr:: $prev_link

   .. php:attr:: $last_link

   .. php:attr:: $full_tag_open

   .. php:attr:: $full_tag_close

   .. php:attr:: $first_tag_open

   .. php:attr:: $first_tag_close

   .. php:attr:: $last_tag_open

   .. php:attr:: $last_tag_close

   .. php:attr:: $cur_tag_open

   .. php:attr:: $cur_tag_close

   .. php:attr:: $next_tag_open

   .. php:attr:: $next_tag_close

   .. php:attr:: $prev_tag_open

   .. php:attr:: $prev_tag_close

   .. php:attr:: $num_tag_open

   .. php:attr:: $num_tag_close

   .. php:method:: paging::init()

      initilizer function
      used to set the necessary variables to create paging.
      

      :param array $params:

   .. php:method:: paging::render()

      render function
      creates the paging html code
      

      :returns: string