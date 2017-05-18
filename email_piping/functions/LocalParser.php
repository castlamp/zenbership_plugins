<?php

/**
 *
 * 
 * Zenbership Membership Software
 * Copyright (C) 2013-2016 Castlamp, LLC
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Castlamp
 * @link        http://www.castlamp.com/
 * @link        http://www.zenbership.com/
 * @copyright   (c) 2013-2016 Castlamp
 * @license     http://www.gnu.org/licenses/gpl-3.0.en.html
 * @project     Zenbership Membership Software
 */

class LocalParser {

    public function parseAddress($from)
    {
        $listed = array();

        $all = explode(',', $from);
        foreach ($all as $one) {
            $exp = explode('<', $one);

            if (! empty($exp['1'])) {
                $fromName = trim($exp['0']);
                $fromEmail = trim($exp['1'], '> ');
            } else {
                $fromName = '';
                $fromEmail = trim($exp['0']);
            }

            $listed[] = array(
                'name' => $fromName,
                'email' => $fromEmail,
            );
        }

        return $listed;
    }

}