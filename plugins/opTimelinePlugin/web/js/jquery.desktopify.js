/* desktopify v1.1
   Encapsulates HTML5 Desktop Notification.
   Copyright (C) 2011 paul pham <http://aquaron.com/jquery/desktopify>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
(function($){$.fn.desktopify=function(o){var _0=$.extend({icon:'data:image/gif;base64,'+'R0lGODlhAQABAID/AMDAwAAAACH5BAEA'+'AAAALAAAAAABAAEAAAICRAEAOw%3D%3D',title:'',remove:true,timeout:15000},o);var _3=function(i,t,b){if(!b){return false}var title=t||_0.title,icon=i||_0.icon,_1;if(window.webkitNotifications){_1=window.webkitNotifications.createNotification(icon,title,b)}else if(navigator.mozNotification){_1=navigator.mozNotification.createNotification(title,b,icon)}_1.show();if(_0.timeout){setTimeout(function(){if(_1.cancel){_1.cancel()}},_0.timeout)}};return this.each(function(){_0.support=(window.webkitNotifications||navigator.mozNotification)?true:false;if(!_0.support){if($.isFunction(_0.unsupported)){_0.unsupported()}return true}var _4=$(this),_2=function(){if(window.webkitNotifications&&window.webkitNotifications.checkPermission()>0){window.webkitNotifications.requestPermission(_2);return false}if(_0.remove){_4.hide()}if($.isFunction(_0.callback)){_0.callback()}};$(this).bind('click notify',function(e,b,t,i){if(e.type==='click'){_2()}else if(e.type==='notify'){_3(i,t,b)}return false});return true})}}(jQuery));
