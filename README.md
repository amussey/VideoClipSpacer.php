VideoClipSpacer.php
===================

Space out video clips by their creation date on an editing timeline!

*Note: This has currently only been tested using Adobe Premiere CS6.  If you test this with any other software, let me know.*

###Before
![Unspaced Clips](http://i.imgur.com/yjN2XVS.png)

###After
![Spaced Clips](http://i.imgur.com/J1JiyGz.png)

## Requirements

 - PHP >= 5.2

## Usage

 - Inside Adobe Premiere, export your video clip as Final Cut XML.
 - From the command line, execute `php VideoClipSpacer.php input.xml output.xml`.
 - Import `output.xml`.  Your clips should be spaced!


## Current Bugs

 - **This currently only works on Windows.**  This is mostly due to file system limitations.  This could potentially be ported to OSX, but cannot be ported to Linux because of file system limitations.  Most Linux file systems (EXT2/3 in particular) do not keep track of file creation dates.
 - **Overlapping.**  This is based off of the creation date on the file rather than the capture date in the EXIF data (not all EXIF data keeps capture date).  This can cause problems if the camera does not start writing as soon as record is pressed, leading to the potential of the end of the clip to be past the beginning of the following clip.
