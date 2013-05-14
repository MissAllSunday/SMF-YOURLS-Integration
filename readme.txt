[center][color=purple][size=15pt]SMF - YOURLS Integration[/size][/color] [/center]


Created by [url=http://missallsunday.com]Suki[/url] 

[color=red]important, support for this modification will only be provided by it's author on the developer's site.[/color]

[b]This mod needs PHP 5.2, cURL library and SMF 2.0.x or greater[/b]

[color=purple][b][size=12pt]License[/size][/b][/color]
[code]
 * Version: MPL 2.0
 *
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file,
 * You can obtain one at http://mozilla.org/MPL/2.0/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
[/code]


[size=12pt][color=purple]Introduction[/color][/size]

This mod allows you to use your YOURLS domain to add a short url version of all topics on your forum, it also creates a new BBC for users to be able to create short urls from any message.

This mod will add a short url version on topic creation, if a topic doesn't have any short url yet, it will create it and store it on the fly.
Keeps a column on topics table for minimizing the calls on YOURLS server.

Has separate master settings for the BBC and the topic url shortening, you can disable either without affecting the other.
Uses SMF hooks wherever possible to avoid file edits.

[size=12pt][color=purple]Supported Languages[/color][/size]

o English/utf8
o Spanish Latin/utf8

I welcome translations, please post it on the support site ;)


[size=12pt][color=purple]Installation[/color][/size]

Use the [url=http://wiki.simplemachines.org/smf/Package_manager]Package Manager[/url] and make sure to mark the "Install in other themes" checkbox during install to ensure the proper installation on any theme different than the default theme.


[size=12pt][color=purple]Changelog[/color][/size]

[code]

[b]1.0[/b]
Initial release

[/code]