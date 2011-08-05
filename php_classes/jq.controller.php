<?php
/**
 * jqTimeTree
 * A plugin to create a tree of time for your own reporting software
 * Copyright © 2011  Stefanus Diptya
 * 
 * This file is part of PHP controller.
 *
 * controller file is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * controller is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with controller.  If not, see <http://www.gnu.org/licenses/>.
 */
include("jq.timetree.php");

$timeTreeObj = new timetree($_POST['start_date'], $_POST['end_date'], $_POST['root']);
switch ($_POST['action']) {
    case 'getTimeTree':
        echo $timeTreeObj->getTimeTree();
        break;
    case 'getLastWeek':
        echo $timeTreeObj->getLastWeek();
        break;
    default:
        break;
}
?>