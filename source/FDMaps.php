<?php
/**
 * Mappings to convert from FD internal names to proper names
 *
 * No description
 *
 * @package EDTB\Backend
 * @author Mauri Kujala <contact@edtb.xyz>
 * @copyright Copyright (C) 2016, Mauri Kujala
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 */

 /*
 * ED ToolBox, a companion web app for the video game Elite Dangerous
 * (C) 1984 - 2016 Frontier Developments Plc.
 * ED ToolBox or its creator are not affiliated with Frontier Developments Plc.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 */

/**
 * Rank mappings (from https://github.com/Marginal/EDMarketConnector/blob/master/stats.py)
 */

$ranks['combat'] = [
    'Harmless',
    'Mostly Harmless',
    'Novice',
    'Competent',
    'Expert',
    'Master',
    'Dangerous',
    'Deadly',
    'Elite'
];

$ranks['trade'] = [
    'Penniless',
    'Mostly Penniless',
    'Peddler',
    'Dealer',
    'Merchant',
    'Broker',
    'Entrepreneur',
    'Tycoon',
    'Elite'
];

$ranks['explore'] = [
    'Aimless',
    'Mostly Aimless',
    'Scout',
    'Surveyor',
    'Trailblazer',
    'Pathfinder',
    'Ranger',
    'Pioneer',
    'Elite'
];

$ranks['cqc'] = [
    'Helpless',
    'Mostly Helpless',
    'Amateur',
    'Semi Professional',
    'Professional',
    'Champion',
    'Hero',
    'Gladiator',
    'Elite'
];

$ranks['federation'] = [
    'None',
    'Recruit',
    'Cadet',
    'Midshipman',
    'Petty Officer',
    'Chief Petty Officer',
    'Warrant Officer',
    'Ensign',
    'Lieutenant',
    'Lieutenant Commander',
    'Post Commander',
    'Post Captain',
    'Rear Admiral',
    'Vice Admiral',
    'Admiral'
];

$ranks['empire'] = [
    'None',
    'Outsider',
    'Serf',
    'Master',
    'Squire',
    'Knight',
    'Lord',
    'Baron',
    'Viscount',
    'Count',
    'Earl',
    'Marquis',
    'Duke',
    'Prince',
    'King'
];

/**
 * Ship mappings (from https://github.com/Marginal/EDMarketConnector/blob/master/companion.py)
 */

$ships = [
    'adder' => 'Adder',
    'anaconda' => 'Anaconda',
    'asp' => 'Asp Explorer',
    'asp_scout' => 'Asp Scout',
    'cobramkiii' => 'Cobra Mk. III',
    'cobramkiv' => 'Cobra Mk IV',
    'cutter' => 'Imperial Cutter',
    'diamondback' => 'Diamondback Scout',
    'diamondbackxl' => 'Diamondback Explorer',
    'eagle' => 'Eagle Mk. II',
    'empire_courier' => 'Imperial Courier',
    'empire_eagle' => 'Imperial Eagle',
    'empire_fighter' => 'Imperial Fighter',
    'empire_trader' => 'Imperial Clipper',
    'federation_corvette' => 'Federal Corvette',
    'federation_dropship' => 'Federal Dropship',
    'federation_dropship_mkii' => 'Federal Assault Ship',
    'federation_gunship' => 'Federal Gunship',
    'federation_fighter' => 'F63 Condor',
    'ferdelance' => 'Fer-de-Lance',
    'hauler' => 'Hauler',
    'independant_trader' => 'Keelback',
    'orca' => 'Orca',
    'python' => 'Python',
    'sidewinder' => 'Sidewinder Mk. I',
    'type6' => 'Type-6 Transporter',
    'type7' => 'Type-7 Transporter',
    'type9' => 'Type-9 Heavy',
    'viper' => 'Viper Mk III',
    'viper_mkiv' => 'Viper MK IV',
    'vulture' => 'Vulture'
];
