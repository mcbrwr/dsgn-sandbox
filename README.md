# dsgn-sandbox
Sketching some new design patterns for html/css layouts

# Automated index
Never do things that can be automated. So the creation of the index in index.html is automated with a php script.
Every html file in the 'designs' folder is parsed. HTML title tag and meta description tag are used to create a new definition list that will replace the contents of the div with id 'index' in index.html.

## Usage:

- Make sure the title tag is your design html file is filled
- Write something to describe the design in the meta description tag
- Folder names in the 'design' folder are used as section headers

Then run the build script to update the table of contents in index.html:

    php build_index.php
