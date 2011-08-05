/**
 * jqTimeTree
 * A plugin to create a tree of time for your own reporting software
 * Copyright © 2011  Stefanus Diptya
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>. *
 */
(function($){
    $.jtree = $.jtree || {};
    $.extend($.jtree,{
        fileTree: function(o, h) {
            // Defaults
            if( !o ) var o = {};
            if( o.root == undefined ) o.root = 'years';
            if( o.baseUrl == undefined ) o.baseUrl = '/';
            if( o.script == undefined ) o.script = 'php_classes/jq.controller.php';
            if( o.event == undefined ) o.event = 'click';
            if( o.expandSpeed == undefined ) o.expandSpeed= 500;
            if( o.collapseSpeed == undefined ) o.collapseSpeed= 500;
            if( o.expandEasing == undefined ) o.expandEasing = null;
            if( o.multiTree == undefined ) o.multiTree = false;
            if( o.collapseEasing == undefined ) o.collapseEasing = null;
            if( o.loadYearOnce == undefined ) o.loadYearOnce = true;
            if( o.loadMonthOnce == undefined ) o.loadMonthOnce = true;
            if( o.loadActiveOnce == undefined ) o.loadActiveOnce = true;
            if( o.startDate == undefined ) o.startDate = '2008-01-01 00:00:00';
            if( o.endDate == undefined ) o.endDate = '2011-06-06 00:00:00';
            if( o.loadMessage == undefined ) o.loadMessage = 'Loading...';

            $(this).each( function() {

                function showTree(c, t) {
                    $(c).addClass('wait');
                    $(".jqueryFileTree.start").remove();
                    $.post(o.script, {
                        action: 'getTimeTree',
                        root: t,
                        start_date: o.startDate,
                        end_date: o.endDate
                    }, function(data) {
                        $(c).find('.start').html('');
                        $(c).removeClass('wait').append(data);
                        if( o.root == t ) $(c).find('UL:hidden').show(); else $(c).find('UL:hidden').slideDown({
                            duration: o.expandSpeed,
                            easing: o.expandEasing
                        });
                        bindTree(c);
                        expandThis();
                        addClassActive(t);
                    });
                }
                function addClassActive(t){
                    if(o.loadActiveOnce){
                        $.post(o.script,{
                            action: 'getLastWeek',
                            root: t,
                            start_date: o.startDate,
                            end_date: o.endDate
                        }, function(data) {
                            if($(document.getElementById(data).firstChild).attr('rel')!=undefined){
                                $(document.getElementById(data).firstChild).addClass('active');
                                o.loadActiveOnce = false;
                                showInformation();
                            }
                        });
                    }
                }
                function expandThis(){
                    var date =  o.endDate.split('-');
                    var year = date[0];
                    var month = date[1];
                    var element = document.getElementById(year);
                    if(o.loadYearOnce){
                        if( !o.multiTree ) {
                            $(element).parent().find('UL').slideUp({
                                duration: o.collapseSpeed,
                                easing: o.collapseEasing
                            });
                            $(element).parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
                        }
                        $(element).find('UL').remove();
                        showTree($(element), escape($(element.firstChild).attr('rel')));
                        $(element).removeClass('collapsed').addClass('expanded');
                        o.loadYearOnce = false;
                    }
                    element = document.getElementById(year+'/'+(month*1));
                    if(element && o.loadMonthOnce){
                        if( !o.multiTree ) {
                            $(element).parent().find('UL').slideUp({
                                duration: o.collapseSpeed,
                                easing: o.collapseEasing
                            });
                            $(element).parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
                        }
                        $(element).find('UL').remove();
                        showTree($(element), escape($(element.firstChild).attr('rel')));
                        $(element).removeClass('collapsed').addClass('expanded');
                        o.loadMonthOnce = false;
                    }
                }
                function bindTree(t) {
                    $(t).find('LI').bind(o.event, function() {
                        if( $(this).hasClass('directory') ) {
                            if( $(this).hasClass('collapsed') ) {
                                // Expand
                                if( !o.multiTree ) {
                                    $(this).parent().find('UL').slideUp({
                                        duration: o.collapseSpeed,
                                        easing: o.collapseEasing
                                    });
                                    $(this).parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
                                }
                                $(this).find('UL').remove(); // cleanup
                                showTree( $(this), escape($(this.firstChild).attr('rel')) );
                                //original showTree( $(this).parent(), escape($(this).attr('rel').match( /.*\// )) );
                                $(this).removeClass('collapsed').addClass('expanded');
                            } else {
                                // Collapse
                                $(this).find('UL').slideUp({
                                    duration: o.collapseSpeed,
                                    easing: o.collapseEasing
                                });
                                $(this).removeClass('expanded').addClass('collapsed');
                            }
                        } else {
                        //h($(this.firstChild).attr('rel')); Do nothing when $(this) is a leaf/file/point
                        }
                        return false;
                    });
                    $(t).find('LI A').bind(o.event, function() {
                        var searchData = $(this).attr('rel');
                        h(searchData);
                        return false;
                    });
                    // Prevent A from triggering the # on non-click events
                    if( o.event.toLowerCase != 'click' ) $(t).find('LI A').bind('click', function() {
                        return false;
                    });
                }
                // Loading message
                $(this).html('<ul class="jqueryFileTree start"><li class="wait">' + o.loadMessage + '<li></ul>');
                // Get the initial file list

                showTree( $(this), escape(o.root) );
            });
        }
    });

    $.fn.jqTimeTree = function(action,params){
        var fun = $.jtree.fileTree;
        return fun.apply(this, arguments);
    };
    $.jtree.extend({
        
    });
})(jQuery);