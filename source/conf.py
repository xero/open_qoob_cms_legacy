# -*- coding: utf-8 -*-
#
# open qoob documentation build configuration file, created by
# sphinx-quickstart on Fri Aug 31 22:15:13 2012

import sys, os

# -- General configuration -----------------------------------------------------
# sphinx extensions. including the custom phpdomain
extensions = ['sphinx.ext.autodoc', 'sphinx.ext.doctest', 'sphinxcontrib.phpdomain']

# templates dir
templates_path = ['_templates']

# reST
source_suffix = '.rst'

# master toctree
master_doc = 'index'

# info
project = u'open qoob cms'
copyright = u'2012, xero harrison'

# version
version = '1.0'
# release
release = '1.0'

# excludes
exclude_patterns = []

# syntax highlighting
pygments_style = 'sphinx'

# -- Options for HTML output ---------------------------------------------------

# theme
html_theme = 'default'

# theme colors
html_theme_options = {
	'footerbgcolor': "#333",
	'relbarbgcolor': '#666',
	'relbartextcolor': '#aaa',
	'relbarlinkcolor': '#fff',
	'headbgcolor': "#eee",
	'headtextcolor': "#000",
	'headlinkcolor': "#000",
	'sidebarbgcolor': "#ccc",
	'sidebarlinkcolor': "#000",
	'sidebartextcolor': "#333",
	'visitedlinkcolor': "#222",
	'linkcolor': "#222",
	'codebgcolor': "#efefef"
}

# project title
html_title = 'open qoob cms dox v1.0'

# static path
html_static_path = ['_static']

# sidebar templates
html_sidebars = {
	'**': ['localtoc.html', 'relations.html', 'qoob.html' ]
}

# basename
htmlhelp_basename = 'openqoobcmsdox'


# -- Options for LaTeX output --------------------------------------------------

latex_elements = {
# The paper size ('letterpaper' or 'a4paper').
#'papersize': 'letterpaper',

# The font size ('10pt', '11pt' or '12pt').
#'pointsize': '10pt',

# Additional stuff for the LaTeX preamble.
#'preamble': '',
}

# latex info
latex_documents = [
  ('index', 'openqoob.tex', u'open qoob cms dox',
   u'xero harrison', 'manual'),
]

# man pages
man_pages = [
    ('index', 'openqoob', u'open qoob cms dox',
     [u'xero harrison'], 1)
]

# -- Options for Texinfo output ------------------------------------------------

# test info
texinfo_documents = [
  ('index', 'openqoob', u'open qoob cms dox',
   u'xero harrison', 'openqoobcmsdox', 'open source php mvc framework cms implementation',
   'Miscellaneous'),
]
