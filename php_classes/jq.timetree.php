<?php
/**
 * jqTimeTree
 * A plugin to create a tree of time for your own reporting software
 * Copyright © 2011  Stefanus Diptya
 */
class timetree {
/**
 * This file is part of PHP timetree class.
 *
 * timetree class is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * timetree class is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with timetree class.  If not, see <http://www.gnu.org/licenses/>.
 */
    var $start_date;
    var $end_date;
    var $root;

    function __construct($_start_date, $_end_date, $_root) {
        $this->set_start_date($_start_date);
        $this->set_end_date($_end_date);
        $this->set_root($_root);
    }
    public function set_start_date($_start_date) {
        $this->start_date = $_start_date;
    }
    public function set_end_date($_end_date) {
        $this->end_date = $_end_date;
    }
    public function set_root($_root) {
        $this->root = $_root;
    }
    public function getLastWeek(){
        return date('o W',strtotime($this->end_date));
    }
    public function getStartYear(){
        return date('Y', strtotime($this->start_date));
    }
    public function getStartMonth(){
        return date('n', strtotime($this->start_date));
    }
    public function getStartDay(){
        return date('j', strtotime($this->start_date));
    }
    public function getEndYear(){
        return date('Y', strtotime($this->end_date));
    }
    public function getEndMonth(){
        return date('n', strtotime($this->end_date));
    }
    public function getEndDay(){
        return date('j', strtotime($this->end_date));
    }
    public function getTimeTree() {
        $monthNames = array("January", "February", "March", "April",
            "May", "June", "July", "August", "September",
            "October", "November", "December");
        for ($year = $this->getStartYear(); $year <= $this->getEndYear(); ++$year) {
            $years[] = $year;
        }
        $get_year = explode("/", $this->root);
        $responce = "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
        if ($this->root == 'years') {
            foreach ($years as $year) {
                $responce .= "<li id='" . htmlentities($year) . "' class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($year) . "/\">" . htmlentities($year) . "</a></li>";
            }
        } else if ($get_year[1] == '') {
            if ($get_year[0] == $this->getStartYear()) {
                if ($get_year[0] == $this->getEndYear())
                    $last_month = $this->getEndMonth ();
                else
                    $last_month = 12;
                for ($month = ($this->getStartMonth() * 1) - 1; $month < ($last_month * 1); $month++) {
                    $responce .= "<li id='" . htmlentities($this->root . ($month + 1)) . "' class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($this->root  . ($month + 1)) . "\">" . htmlentities($monthNames[$month]) . "</a></li>";
                }
            } else if ($get_year[0] != $this->getEndYear()) {
                for ($month = 0; $month < 12; $month++) {
                    $responce .= "<li id='" . htmlentities($this->root  . ($month + 1)) . "' class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($this->root  . ($month + 1)) . "\">" . htmlentities($monthNames[$month]) . "</a></li>";
                }
            } else {
                for ($month = 0; $month < ($this->getEndMonth() * 1); $month++) {
                    $responce .= "<li id='" . htmlentities($this->root  . ($month + 1)) . "' class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($this->root  . ($month + 1)) . "\">" . htmlentities($monthNames[$month]) . "</a></li>";
                }
            }
        } else {
            $date = explode("/", $this->root);
            $year = ($date[0] * 1);
            $month = ($date[1] * 1);
            $date = mktime(1, 1, 1, $month, 1, $year);
            $last_day = idate('d', mktime(1, 1, 1, ($month + 1), 0, $year));
            for ($day = 1; $day <= $last_day; $day++) {
                if ($month == $this->getEndMonth() && $year == $this->getEndYear() && $day > $this->getEndDay() ||
                        $month == $this->getStartMonth() && $year == $this->getStartYear() && $day < $this->getStartDay() ) {
                    //do nothing
                } else {
                    $now_date = mktime(1, 1, 1, $month, $day, $year);
                    $day_num = date('w', $now_date);
                    $total_day_in_week = 7 - $day_num;
                    if ($total_day_in_week == 6) {
                        $week = date('W', mktime(1, 1, 1, $month, $day, $year));
                        $year_of_week = date('o', mktime(1, 1, 1, $month, $day, $year));
                        if ($week != 53 && $week != 52 || $month != 1) {
                            $responce .= "<li id='" . $year_of_week . ' ' . $week . "' class=\"file ext_date\"><a href=\"#\" title=\"Week ".$week." of ".$year_of_week."\" rel=\"" . $year_of_week . ' ' .$week. "\">" . htmlentities('Week ' .$week) . "</a></li>";
                        }
                    }
                }
            }
        }
        $responce .= "</ul>";
        return $responce;
    }

}

?>