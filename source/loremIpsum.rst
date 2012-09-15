Lorem Ipsum Utility
*******************

.. php:class:: loremIpsum

      loremIpsum
      Lorem Ipsum is non-sense, placeholder text used in publishing and design.
      It allows the developer to see their work completely populated with text,
      without having to actually create the text.

      generates content in three modes: plain, HTML (content blocks nested in <p> tags),
      and text (plain text in paragraph form). sentences are punctuated and vary in length
      based on statistics collected here: http://hearle.nahoo.net/Academic/Maths/Sentence.html.
      sentence length will vary on a Guassian distribution. HTML output is 'clean-code'
      formatted with tabs and new lines rather than just blobs of code.

      released open-source under the BSD License
      see <http://opensource.org/licenses/bsd-license.php>.
      

      :author: Mathew Tinsley http://tinsology.net/scripts/php-lorem-ipsum-generator/

      :version: 1.0

      :package: qoob

      :subpackage: utils

      :category: place $older

   .. php:method:: loremIpsum::__construct()

   .. php:method:: loremIpsum::getContent()

      get content function
      returns the desired amount of content as a string.
      

      :param int $count: word count
      :param string $format: the return type (html, txt, or plain)
      :param boolean $loremipsum: if true, start the text with lorem ipsum

      :returns: mixed $tring|html