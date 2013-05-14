**SMF - YOURLS Integration**, http://missallsunday.com

This software is licensed under [MPL 2.0 license](http://www.mozilla.org/MPL/2.0/).

######What is this:

This mod allows you to use your YOURLS domain to add a short url version of all topics on your forum, it also creates a new BBC for users to be able to create short urls from any message.

This mod will add a short url version on topic creation, if a topic doesn't have any short url yet, it will create it and store it on the fly.
Keeps a column on topics table for minimizing the calls on YOURLS server.

Has separate master settings for the BBC and the topic url shortening, you can disable either without affecting the other.
Uses SMF hooks wherever possible to avoid file edits.

######Requirements:

- SMF 2.0.x or greater.
- PHP 5.2 or greater.
- cURL library.
- [YOURLS script](https://github.com/YOURLS/YOURLS) 1.5.1 or greater already installed and properly functioning.

######Installation:

Please use SMF's [Package Manager](http://wiki.simplemachines.org/smf/Package_manager) and make sure to mark the "Install in other themes" checkbox during install to ensure the proper installation on any theme different than the default theme.

######Notes:

Please do note that this script relies on third party software/servers and although the mod tries to mitigate as much as possible any downtime to your forum, I cannot offer a full guarantee.

######Support:

Please go to the authors website if you need support for this modification.