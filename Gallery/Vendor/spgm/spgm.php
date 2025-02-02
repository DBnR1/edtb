<?php
/*
 * SPGM (Simple Picture Gallery Manager)
 *
 * A basic and configurable PHP script to display picture galleries on the web
 *
 * @author Sylvain Pajot <spajot@users.sourceforge.net>
 * @copyright Copyright 2002-2007, Sylvain Pajot
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * Official website: http://spgm.sourceforge.net
 *
 * @package EDTB\Backend
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

/** @require functions */
require_once $_SERVER['DOCUMENT_ROOT'] . '/source/functions.php';

use \EDTB\source\System;

###### Toggles #############
define('MODE_TRACE', false); // toggles debug mode
define('MODE_WARNING', true); // toggles warning mode
define('DIR_GAL', $settings['new_screendir'] . "\\"); // galleries base directory (relative path from spgm.php or the file requiring it if there's one)
define('URL_GAL', '/screenshots/');
define('DIR_LANG', '../Gallery/Vendor/spgm/lang/'); // language packs (relative path from spgm.php or the file requiring it if there's one)
define('DIR_THEMES', '../Gallery/Vendor/spgm/flavors/'); // themes base directory (relative path from spgm.php or the file requiring it if there's one)
define('DIR_THUMBS', 'thumbs/'); // if defined, points to the directory where thumbnails reside, relatively from the gallery directory

define('FILE_GAL_TITLE', 'gal-title.txt'); // default title file for a gallery
define('FILE_GAL_SORT', 'gal-sort.txt'); // file for explicit gallery sort
define('FILE_GAL_CAPTION', 'gal-desc.txt'); // default caption file for a gallery
define('FILE_GAL_HIDE', 'gal-hide.txt'); // default file to enable gallery hide
define('FILE_PIC_SORT', 'pic-sort.txt'); // file for explicit picture sort
define('FILE_PIC_CAPTIONS', 'pic-desc.txt'); // default caption file for pictures/thumbnails
define('FILE_THEME', 'spgm.thm'); // theme file
define('FILE_CONF', 'spgm.conf'); // config file
define('FILE_LANG', 'lang'); // language file short name (without extension)
define('PREF_THUMB', ''); // prefix for thumbnail pictures
// MUST NOT be empty if DIR_THUMBS is used
define('EXT_PIC_CAPTION', '.cmt'); // file extension for pictures comment (DEPRECATED)
define('CAPTION_DELIMITER', '|');
define('CAPTION_KEEPER', '>');

define('PARAM_PREFIX', 'spgm'); // MUST NOT be empty
define('PARAM_NAME_GALID', PARAM_PREFIX . 'Gal');
define('PARAM_NAME_PICID', PARAM_PREFIX . 'Pic');
define('PARAM_NAME_PAGE', PARAM_PREFIX . 'Page');
define('PARAM_NAME_FILTER', PARAM_PREFIX . 'Filters');
define('PARAM_VALUE_FILTER_NEW', 'n');
define('PARAM_VALUE_FILTER_NOTHUMBS', 't');
define('PARAM_VALUE_FILTER_SLIDESHOW', 's');

define('CLASS_TABLE_WRAPPER', 'table-wrapper');
define('CLASS_TABLE_MAIN_TITLE', 'table-main-title');
define('CLASS_TD_SPGM_LINK', 'td-main-title-spgm-link');
define('CLASS_A_SPGM_LINK', 'a-spgm-link');
define('CLASS_TABLE_GALLISTING_GRID', 'table-gallisting-grid');
define('CLASS_TD_GALLISTING_CELL', 'td-gallisting-cell');
define('CLASS_TABLE_GALITEM', 'table-galitem');
define('CLASS_TD_GALITEM_ICON', 'td-galitem-icon');
define('CLASS_TD_GALITEM_TITLE', 'td-galitem-title');
define('CLASS_TD_GALITEM_CAPTION', 'td-galitem-caption');
define('CLASS_TABLE_PICTURE', 'table-picture');
define('CLASS_TD_PICTURE_NAVI', 'td-picture-navi');
define('CLASS_TD_ZOOM_FACTORS', 'td-zoom-factors');
define('ID_PICTURE', 'picture');
define('ID_PICTURE_CAPTION', 'picture-caption');
define('CLASS_BUTTON_ZOOM_FACTORS', 'button-zoom-factors');
define('CLASS_TD_PICTURE_PIC', 'td-picture-pic');
define('ID_PICTURE_NAVI', 'pic-navi');
define('CLASS_TD_PICTURE_FILENAME', 'td-picture-filename');
define('CLASS_TD_PICTURE_CAPTION', 'td-picture-caption');
define('CLASS_TABLE_THUMBNAILS', 'table-thumbnails');
define('CLASS_TD_THUMBNAILS_THUMB', 'td-thumbnails-thumb');
define('CLASS_TD_THUMBNAILS_THUMB_SELECTED', 'td-thumbnails-thumb-selected');
define('CLASS_TD_THUMBNAILS_NAVI', 'td-thumbnails-navi');
define('CLASS_DIV_THUMBNAILS_CAPTION', 'div-thumbnails-caption');
define('CLASS_TABLE_SHADOWS', 'table-shadows');
define('CLASS_TD_SHADOWS_RIGHT', 'td-shadows-right');
define('CLASS_TD_SHADOWS_BOTTOM', 'td-shadows-bottom');
define('CLASS_TD_SHADOWS_BOTTOMRIGHT', 'td-shadows-bottomright');
define('CLASS_TD_SHADOWS_MAIN', 'td-shadows-main');
define('CLASS_TABLE_ORIENTATION', 'table-orientation');
define('CLASS_TD_ORIENTATION_LEFT', 'td-orientation-left');
define('CLASS_TD_ORIENTATION_RIGHT', 'td-orientation-right');
define('CLASS_SPAN_FILTERS', 'span-filters');
define('CLASS_IMG_PICTURE', 'img-picture');
define('CLASS_IMG_THUMBNAIL', 'img-thumbnail');
define('CLASS_IMG_THUMBNAIL_SELECTED', 'img-thumbnail-selected');
define('CLASS_IMG_FOLDER', 'img-folder');
define('CLASS_IMG_GALICON', 'img-galicon');
define('CLASS_IMG_PICTURE_PREV', 'img-picture-prev');
define('CLASS_IMG_PICTURE_NEXT', 'img-picture-next');
define('CLASS_IMG_THMBNAVI_PREV', 'img-thmbnavi-prev');
define('CLASS_IMG_THMBNAVI_NEXT', 'img-thmbnavi-next');
define('CLASS_IMG_NEW', 'img-new');
define('CLASS_DIV_GALHEADER', 'div-galheader');

define('ANCHOR_PICTURE', 'spgmPicture');
define('ANCHOR_SPGM', 'spgm');

define('ERRMSG_UNKNOWN_GALLERY', 'unknown gallery');
define('ERRMSG_UNKNOWN_PICTURE', 'unknown picture');
define('ERRMSG_INVALID_NUMBER_OF_PICTURES', 'invalid number of picture');
define('ERRMSG_INVALID_VALUE', 'invalid value');
define('WARNMSG_FILE_INSUFFICIENT_PERMISSIONS', 'insufficient permissions (644 required)');
define('WARNMSG_THUMBNAIL_UNREADABLE', 'no associated thumbnail or insufficient permissions');
define('WARNMSG_DIR_INSUFFICIENT_PERMISSIONS', 'insufficient permissions (755 required)');

define('GALICON_NONE', 0);
define('GALICON_RANDOM', 1);
define('ORIGINAL_SIZE', 0);
define('ORIENTATION_TOPBOTTOM', 0);
define('ORIENTATION_LEFTRIGHT', 1);
define('SORTTYPE_CREATION_DATE', 0);
define('SORTTYPE_NAME', 1);
define('SORT_ASCENDING', 0);
define('SORT_DESCENDING', 1);
define('RIGHT', 0);
define('BOTTOM', 1);

/** multi-language support...  */
define('PATTERN_SPGM_LINK', '>SPGM_LINK<');
define('PATTERN_CURRENT_PAGE', '>CURRENT_PAGE<');
define('PATTERN_NB_PAGES', '>NB_PAGES<');
define('PATTERN_CURRENT_PIC', '>CURRENT_PIC<');
define('PATTERN_NB_PICS', '>NB_PICS<');

// Used for variable variables in main function
$strVarGalleryId = PARAM_NAME_GALID;
$strVarPictureId = PARAM_NAME_PICID;
$strVarPageIndex = PARAM_NAME_PAGE;
$strVarFilterFlags = PARAM_NAME_FILTER;

global $spgmCfg;
$spgmCfg = [];
$spgmCfg['conf']['newStatusDuration'] = 120; //minutes
$spgmCfg['conf']['thumbnailsPerPage'] = 12;
$spgmCfg['conf']['thumbnailsPerRow'] = 3;
$spgmCfg['conf']['galleryListingCols'] = 3;
$spgmCfg['conf']['galleryCaptionPos'] = RIGHT;
$spgmCfg['conf']['subGalleryLevel'] = 1;
$spgmCfg['conf']['galleryOrientation'] = ORIENTATION_TOPBOTTOM;
$spgmCfg['conf']['gallerySortType'] = SORTTYPE_CREATION_DATE;
$spgmCfg['conf']['gallerySortOptions'] = SORT_DESCENDING;
$spgmCfg['conf']['pictureSortType'] = SORTTYPE_CREATION_DATE;
$spgmCfg['conf']['pictureSortOptions'] = SORT_DESCENDING;
$spgmCfg['conf']['pictureInfoedThumbnails'] = true;
$spgmCfg['conf']['captionedThumbnails'] = false;
$spgmCfg['conf']['pictureCaptionedThumbnails'] = false;
$spgmCfg['conf']['filenameWithThumbnails'] = false;
$spgmCfg['conf']['filenameWithPictures'] = true;
$spgmCfg['conf']['enableSlideshow'] = false;
$spgmCfg['conf']['enableDropShadows'] = false;
$spgmCfg['conf']['fullPictureWidth'] = 820;
$spgmCfg['conf']['fullPictureHeight'] = 461;
$spgmCfg['conf']['popupOverFullPictures'] = true;
$spgmCfg['conf']['popupPictures'] = false;
$spgmCfg['conf']['popupFitPicture'] = false;
$spgmCfg['conf']['popupWidth'] = 1920;
$spgmCfg['conf']['popupHeight'] = 1080;
$spgmCfg['conf']['filters'] = '';
$spgmCfg['conf']['exifInfo'] = [];
$spgmCfg['conf']['zoomFactors'] = [];
$spgmCfg['conf']['galleryIconType'] = GALICON_NONE;
$spgmCfg['conf']['galleryIconHeight'] = ORIGINAL_SIZE;
$spgmCfg['conf']['galleryIconWidth'] = ORIGINAL_SIZE;
$spgmCfg['conf']['stickySpgm'] = false;
$spgmCfg['conf']['theme'] = 'default';
$spgmCfg['conf']['language'] = 'en';

$spgmCfg['locale']['spgmLink'] = 'a gallery generated by ' . PATTERN_SPGM_LINK;
$spgmCfg['locale']['thumbnailNaviBar'] = 'Page ' . PATTERN_CURRENT_PAGE . ' of ' . PATTERN_NB_PAGES;
$spgmCfg['locale']['filter'] = 'filter';
$spgmCfg['locale']['filterNew'] = 'new';
$spgmCfg['locale']['filterAll'] = 'all';
$spgmCfg['locale']['filterSlideshow'] = 'Slideshow';
$spgmCfg['locale']['pictureNaviBar'] = 'Picture ' . PATTERN_CURRENT_PIC . ' of ' . PATTERN_NB_PICS;
$spgmCfg['locale']['newPictures'] = 'new pictures';
$spgmCfg['locale']['newPicture'] = 'new picture';
$spgmCfg['locale']['newGallery'] = 'new gallery';
$spgmCfg['locale']['pictures'] = 'pictures';
$spgmCfg['locale']['picture'] = 'picture';
$spgmCfg['locale']['rootGallery'] = 'Main gallery';
$spgmCfg['locale']['exifHeading'] = 'EXIF data for';

$spgmCfg['theme']['gallerySmallIcon'] = '';
$spgmCfg['theme']['galleryBigIcon'] = '';
$spgmCfg['theme']['newItemIcon'] = '';
$spgmCfg['theme']['previousPictureIcon'] = '';
$spgmCfg['theme']['nextPictureIcon'] = '';
$spgmCfg['theme']['previousPageIcon'] = '&laquo;';
$spgmCfg['theme']['previousPageIconNot'] = '&laquo;';
$spgmCfg['theme']['nextPageIcon'] = '&raquo;';
$spgmCfg['theme']['nextPageIconNot'] = '&raquo;';
$spgmCfg['theme']['firstPageIcon'] = '&laquo;&laquo;';
$spgmCfg['theme']['firstPageIconNot'] = '&laquo;&laquo;';
$spgmCfg['theme']['lastPageIcon'] = '&raquo;&raquo;';
$spgmCfg['theme']['lastPageIconNot'] = '&raquo;&raquo;';

$spgmCfg['global']['supportedExtensions'] = [
    '.jpg',
    '.png',
    '.gif'
]; // supported picture file extensions
$spgmCfg['global']['ignoredDirectories'] = [
    'vti_cnf/',
    '_vti_cnf/'
]; // directories to ignore, add some more if needed
if (defined('DIR_THUMBS')) {
    $spgmCfg['global']['ignoredDirectories'][] = DIR_THUMBS;
}

$spgmCfg['global']['propagateFilters'] = false; // used to propagate filters in URLs
$spgmCfg['global']['documentSelf'] = '';
$spgmCfg['global']['tmpPathToPics'] = ''; // hack to avoid comparisons of long
// strings (only used by the
// spgm_CallbackCompareMTime
// callback function)
$spgmCfg['global']['URLExtraParams'] = ''; // Contains the extra paramaters for SPGM
// to be able to link back in template mode


###### REPORTING FUNCTIONS #############################################

function spgm_Error($strErrorMessage)
{
    echo '<div style="color:#ff0000;font-size: 12pt; font-weight: 700">' . $strErrorMessage . '</div>' . "\n";
}

function spgm_Warning($strWarningMessage)
{
    if (MODE_WARNING) {
        echo '<div style="color:#0000ff;font-size: 12pt; font-weight: 700">' . $strWarningMessage . '</div>' . "\n";
    }
}

function spgm_Trace($strTrace)
{
    if (MODE_TRACE) {
        echo '<div style="color:#000;font-size: 12pt">' . $strTrace . '</div>' . "\n";
    }
}


################## DISPLAY FUNCTIONS #####################################

# Builds the A html markup poiting to the URL that is to be built according to the passed parameters
# Parameters description :
# $text : HTML code to click on
# $cssClass : CSS class to apply to the A markup (can be empty)
# $anchor : internal anchor to point to (not generated if empty)
# $galId : gallery to point to (omitted if -1)
# $pageIdx : gallery page to point to (omitted if -1)
# $picId : picture to point to (omitted if -1)
# $filters : filters to set in the URL

function spgm_BuildLink($text, $cssClass, $anchor, $galId, $pageIdx, $picId, $filters)
{
    global $spgmCfg;

    spgm_Trace('<p>function spgm_BuildLink</p>' . "\n" . 'text: ' . $text . '<br>' . "\n" . 'cssClass: ' . $cssClass . '<br>' . "\n" . 'anchor: ' . $anchor . '<br>' . "\n" . 'galId: ' . $galId . '<br>' . "\n" . 'pageIdx: ' . $pageIdx . '<br>' . "\n" . 'picId: ' . $picId . '<br>' . "\n" . 'filters: ' . $filters . '<br>' . "\n");

    $url = $spgmCfg['global']['documentSelf'] . '?';
    if ($galId != '') {
        $url .= PARAM_NAME_GALID . '=' . urlencode($galId);
    }
    if ($pageIdx != -1) {
        $url .= '&amp;' . PARAM_NAME_PAGE . '=' . $pageIdx;
    }
    if ($picId != -1) {
        $url .= '&amp;' . PARAM_NAME_PICID . '=' . $picId;
    }
    if ($filters != '') {
        $url .= '&amp;' . PARAM_NAME_FILTER . '=' . $filters;
    }
    $url .= $spgmCfg['global']['URLExtraParams'];
    if ($anchor != '') {
        $url .= '#' . $anchor;
    } elseif ($spgmCfg['conf']['stickySpgm'] == true) {
        $url .= '#' . ANCHOR_SPGM;
    }

    $url = str_replace('removed', 'r', $url);

    if ($cssClass == 'td-galitem-title' || $cssClass == '') {
        $pjax = 'data-replace="true" data-target=".entries" ';
    }

    $link = '<a ' . $pjax . 'href="' . $url . '" class="' . $cssClass . '">' . $text . '</a>';

    return $link;
}

function spgm_DispSPGMLink()
{
    global $spgmCfg;

    spgm_Trace('<p>function spgm_DispSPGMLink</p>' . "\n");

    // multi-language support
    $spgmCfg['locale']['spgmLink'] = str_replace(PATTERN_SPGM_LINK, '<a href="http://spgm.sourceforge.net" target="_blank" class="' . CLASS_A_SPGM_LINK . '">SPGM</a>', $spgmCfg['locale']['spgmLink']);

    echo $spgmCfg['locale']['spgmLink'];
}

function spgm_DropShadowsBeginWrap($offset = '')
{
    global $spgmCfg;

    spgm_Trace('<p>function spgm_DropShadowsBeginWrap</p>' . "\n");

    // if drop shadows are enabled, draw the beginning of the table
    if ($spgmCfg['conf']['enableDropShadows']) {
        echo $offset . '<table class="' . CLASS_TABLE_SHADOWS . '">' . "\n";
        echo $offset . '    <tr>' . "\n";
        echo $offset . '        <td class="' . CLASS_TD_SHADOWS_MAIN . '">' . "\n";
    }
}

function spgm_DropShadowsEndWrap($offset = '')
{
    global $spgmCfg;

    spgm_Trace('<p>function spgm_DropShadowsEndWrap</p>' . "\n");

    // if drop shadows are enabled, draw the end of the table
    if ($spgmCfg['conf']['enableDropShadows']) {
        echo $offset . '        </td>' . "\n";
        echo $offset . '        <td class="' . CLASS_TD_SHADOWS_RIGHT . '">&nbsp;</td>' . "\n";
        echo $offset . '    </tr>' . "\n";
        echo $offset . '    <tr>' . "\n";
        echo $offset . '        <td class="' . CLASS_TD_SHADOWS_BOTTOM . '">&nbsp;</td>' . "\n";
        echo $offset . '        <td class="' . CLASS_TD_SHADOWS_BOTTOMRIGHT . '">&nbsp;</td>' . "\n";
        echo $offset . '    </tr>' . "\n";
        echo $offset . '</table>' . "\n";
    }
}


################################################################################
# Checks if a file or directory is "new"

function spgm_IsNew($strFilePath)
{
    global $spgmCfg;

    spgm_Trace('<p>function spgm_IsNew</p>' . "\n" . 'strFilePath: ' . $strFilePath . '<br>' . "\n");

    if (!file_exists($strFilePath) || $spgmCfg['conf']['newStatusDuration'] == 0) {
        return false;
    }

    return (filemtime($strFilePath) > (time() - $spgmCfg['conf']['newStatusDuration'] * 60));
}

################################################################################
# Checks for permissions on either pictures, galleries, config files, etc...

function spgm_CheckPerms($strFilePath)
{
    spgm_Trace('<p>function spgm_CheckPerms</p>' . "\n" . 'strFilePath: ' . $strFilePath . '<br>' . "\n");

    return is_readable($strFilePath);
}

################################################################################
# Checks if the filname exists, refers to a picture associated to a thumbnail
# and is granted the necessary access rigths

function spgm_IsPicture($strPictureFileName, $strGalleryId)
{
    global $spgmCfg;

    $strPicturePath = DIR_GAL . $strGalleryId . '/' . $strPictureFileName;
    $strThumbnailPath = DIR_GAL . $strGalleryId . '/' . PREF_THUMB . $strPictureFileName;
    if (defined('DIR_THUMBS')) {
        $strThumbnailPath = DIR_GAL . $strGalleryId . '/' . DIR_THUMBS . PREF_THUMB . $strPictureFileName;
    }

    spgm_Trace('<p>function spgm_IsPicture</p>' . "\n" . 'strPictureFileName: ' . $strPictureFileName . '<br>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'strPicturePath: ' . $strPicturePath . '<br>' . "\n" . 'strThumbnailPath: ' . $strThumbnailPath . '<br>' . "\n");

    // check filename patterns
    //if (PREF_THUMB != '' AND preg_match('^' . PREF_THUMB . '*', $strPictureFileName))
    if (PREF_THUMB != '' and strripos($strPictureFileName, PREF_THUMB)) {
        return false;
    }
    $validated = false;
    $extnb = count($spgmCfg['global']['supportedExtensions']);
    for ($i = 0; $i < $extnb; $i++) {
        //if (preg_match($spgmCfg['global']['supportedExtensions'][$i] . '$', $strPictureFileName))
        if (strripos($strPictureFileName, $spgmCfg['global']['supportedExtensions'][$i]) !== false) {
            $validated = true;
            break;
        }
    }
    if (!$validated) {
        return false;
    }

    // does it exist, is it a regular file and does it have the expected permissions ?
    if (!spgm_CheckPerms($strPicturePath)) {
        return false;
    }

    // an associated thumbnail is required... same job again !
    if (!spgm_CheckPerms($strThumbnailPath)) {
        spgm_Warning($strPicturePath . ': ' . WARNMSG_THUMBNAIL_UNREADABLE . '<br>');

        return false;
    }

    return true;
}

##############################################################################
# Checks if the directory corresponding the gallery is well-formed, exists
# and is granted the necessary access rights
# $galid can be empty

function spgm_IsGallery($strGalleryId)
{
    global $spgmCfg;

    $strPathToPictures = DIR_GAL . $strGalleryId;

    spgm_Trace('<p>function spgm_IsGallery</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'strPathToPictures: ' . $strPathToPictures . '<br>' . "\n");

    // searching for hazardous patterns
    if (strrpos($strGalleryId, '^/') || strrpos($strGalleryId, '\.\.') || strrpos($strGalleryId, '/$')) {
        return false;
    }


    // does it exist, is it a directory ?
    if (!is_dir($strPathToPictures)) {
        return false;
    }

    // ... is it part of the ignore list ?
    foreach ($spgmCfg['global']['ignoredDirectories'] as $key => $value) {
        if (basename($strGalleryId) . '/' == $value) {
            return false;
        }
    }

    // ... does it have the expected permissions ?
    if (!spgm_CheckPerms($strPathToPictures)) {
        spgm_Warning($strPathToPictures . ': ' . WARNMSG_FILE_INSUFFICIENT_PERMISSIONS . '<br>');

        return false;
    }

    if ($strGalleryId == 'Imgur') {
        return false;
    }

    return true;
}


################################################################################
# Loads a flavor

function spgm_LoadFlavor($strThemeName)
{
    global $spgmCfg;

    spgm_Trace('<p>function spgm_LoadFlavor</p>' . "\n" . 'strThemeName: ' . $strThemeName . '<br>' . "\n");

    if (spgm_CheckPerms(DIR_THEMES . $strThemeName . '/' . FILE_THEME)) {
        include DIR_THEMES . $strThemeName . '/' . FILE_THEME;
    } else {
        spgm_Warning('unable to load ' . DIR_THEMES . $strThemeName . '/' . FILE_THEME . ': ' . WARNMSG_FILE_INSUFFICIENT_PERMISSIONS . '<br>');
    }
}

################################################################################
# Loads textual ressources from an SPGM language file.

function spgm_LoadLanguage($strCountryCode)
{
    global $spgmCfg;

    spgm_Trace('<p>funtion spgm_LoadLanguage</p>' . "\n" . 'country code: ' . $strCountryCode . '<br>' . "\n");

    if ($strCountryCode != '') {
        $filenameLang = DIR_LANG . FILE_LANG . '.' . $strCountryCode;
        if (file_exists($filenameLang)) {
            if (spgm_CheckPerms($filenameLang)) {
                include $filenameLang;
            }
        } else {
            spgm_Warning('No support for lang. ' . $strCountryCode . ' &raquo; default: english<br>');
        }
    }
}


###############################################################################
# Loads picture/thumbnail captions for a given gallery

function spgm_LoadPictureCaptions($strGalleryId)
{
    global $spgmCfg;

    spgm_Trace('<p>funtion spgm_LoadPictureCaption</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n");


    $strCaptionsFilename = DIR_GAL . $strGalleryId . '/' . FILE_PIC_CAPTIONS;
    if (spgm_CheckPerms($strCaptionsFilename)) {
        $arrCaptions = file($strCaptionsFilename);
        $Max = count($arrCaptions);
        for ($i = 0; $i < $Max; $i++) {
            // are we on a line that should append the current caption ?
            if ($arrCaptions[$i][0] == CAPTION_KEEPER and $strCurrentPicture != '') {
                $spgmCfg['captions'][$strCurrentPicture] .= substr(trim($arrCaptions[$i]), strlen(CAPTION_KEEPER));
            } elseif (strpos($arrCaptions[$i], CAPTION_DELIMITER) !== false) {
                list($strPictureFilename, $strCaption) = explode(CAPTION_DELIMITER, $arrCaptions[$i]);
                $strCurrentPicture = trim($strPictureFilename);
                $spgmCfg['captions'][$strCurrentPicture] = trim($strCaption);
            }
        }
    }
}

##################################################################
# Loads Exif Data from and returns it as an XHTML-formatted string

function spgm_LoadExif($strPictureURL)
{
    global $spgmCfg;

    $arrExifData = exif_read_data($strPictureURL);
    $strExifData = '';

    if ($spgmCfg['conf']['exifInfo'][0] == 'ALL') {
        foreach ($arrExifData as $key => $value) {
            if (!is_array($arrExifData[$key])) {
                $strExifData .= '&lt;b&gt;' . $key . '&lt;/b&gt; ' . $value . '&lt;br /&gt;';
            }
        }
        $strExifData = str_replace("\n", '', $strExifData);
    } else {
        $max = count($spgmCfg['conf']['exifInfo']);
        for ($i = 0; $i < $max; $i++) {
            $key = $spgmCfg['conf']['exifInfo'][$i];
            $strExifData .= '&lt;b&gt;' . $key . '&lt;/b&gt; ' . $arrExifData[$key] . '&lt;br /&gt;';
        }
    }

    return $strExifData;
}


################################################################################

function spgm_PostInitCheck()
{
    global $spgmCfg;

    spgm_Trace('<p>funtion spgm_PostInitCheck</p>' . "\n");

    $Mix = $spgmCfg['conf']['newStatusDuration'];
    if (!is_int($Mix) || ($Mix < 0)) {
        spgm_Error('spgm_cfg[conf][newStatusDuration]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['thumbnailsPerPage'];
    if (!is_int($Mix) || ($Mix < 1)) {
        spgm_Error('spgm_cfg[conf][thumbnailsPerPage]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['thumbnailsPerRow'];
    if (!is_int($Mix) || ($Mix < 1)) {
        spgm_Error('spgm_cfg[conf][thumbnailsPerRow]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['galleryListingCols'];
    if (!is_int($Mix) || ($Mix < 1)) {
        spgm_Error('spgm_cfg[conf][galleryListingCols]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['subGalleryLevel'];
    if (!is_int($Mix) || ($Mix < 0)) {
        spgm_Error('spgm_cfg[conf][subGalleryLevel]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['galleryIconType'];
    if (!is_int($Mix) || ($Mix != GALICON_NONE && $Mix != GALICON_RANDOM)) {
        spgm_Error('spgm_cfg[conf][galleryIconType]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['galleryIconHeight'];
    if (!is_int($Mix) || ($Mix < ORIGINAL_SIZE)) {
        spgm_Error('spgm_cfg[conf][galleryIconHeight]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['galleryIconWidth'];
    if (!is_int($Mix) || ($Mix < ORIGINAL_SIZE)) {
        spgm_Error('spgm_cfg[conf][galleryIconWidth]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['galleryCaptionPos'];
    if (!is_int($Mix) || ($Mix != RIGHT && $Mix != BOTTOM)) {
        spgm_Error('spgm_cfg[conf][galleryCaptionPos]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['galleryOrientation'];
    if (!is_int($Mix) || ($Mix != ORIENTATION_TOPBOTTOM && $Mix != ORIENTATION_LEFTRIGHT)) {
        spgm_Error('spgm_cfg[conf][galleryOrientation]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['gallerySortType'];
    if (!is_int($Mix) || ($Mix != SORTTYPE_CREATION_DATE && $Mix != SORTTYPE_NAME)) {
        spgm_Error('spgm_cfg[conf][gallerySortType]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['gallerySortOptions'];
    if (!is_int($Mix) || ($Mix != SORT_ASCENDING && $Mix != SORT_DESCENDING)) {
        spgm_Error('spgm_cfg[conf][gallerySortOptions]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['pictureSortType'];
    if (!is_int($Mix) || ($Mix != SORTTYPE_CREATION_DATE && $Mix != SORTTYPE_NAME)) {
        spgm_Error('spgm_cfg[conf][pictureSortType]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['pictureSortOptions'];
    if (!is_int($Mix) || ($Mix != SORT_ASCENDING && $Mix != SORT_DESCENDING)) {
        spgm_Error('spgm_cfg[conf][pictureSortOptions]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_bool($spgmCfg['conf']['pictureInfoedThumbnails'])) {
        spgm_Error('spgm_cfg[conf][pictureInfoedThumbnail]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_bool($spgmCfg['conf']['captionedThumbnails'])) {
        spgm_Error('spgm_cfg[conf][captionedThumbnails]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_bool($spgmCfg['conf']['pictureCaptionedThumbnails'])) {
        spgm_Error('spgm_cfg[conf][pictureCaptionedThumbnails]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['fullPictureWidth'];
    if (!is_int($Mix) || ($Mix < ORIGINAL_SIZE)) {
        spgm_Error('spgm_cfg[conf][fullPictureWidth]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['fullPictureHeight'];
    if (!is_int($Mix) || ($Mix < ORIGINAL_SIZE)) {
        spgm_Error('spgm_cfg[conf][fullPictureHeight]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_bool($spgmCfg['conf']['popupOverFullPictures'])) {
        spgm_Error('spgm_cfg[conf][popupOverFullPictures]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_bool($spgmCfg['conf']['popupPictures'])) {
        spgm_Error('spgm_cfg[conf][popupPictures]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['popupWidth'];
    if (!is_int($Mix) || $Mix < 1) {
        spgm_Error('spgm_cfg[conf][popupWidth]: ' . ERRMSG_INVALID_VALUE);
    }

    $Mix = $spgmCfg['conf']['popupHeight'];
    if (!is_int($Mix) || $Mix < 1) {
        spgm_Error('spgm_cfg[conf][popupHeight]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_string($spgmCfg['conf']['filters'])) {
        spgm_Error('spgm_cfg[conf][filters]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_array($spgmCfg['conf']['zoomFactors'])) {
        spgm_Error('spgm_cfg[conf][zoomFactors]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_array($spgmCfg['conf']['exifInfo'])) {
        spgm_Error('spgm_cfg[conf][exifInfo]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_string($spgmCfg['conf']['theme'])) {
        spgm_Error('spgm_cfg[conf][theme]: ' . ERRMSG_INVALID_VALUE);
    }

    if (!is_string($spgmCfg['conf']['language'])) {
        spgm_Error('spgm_cfg[conf][language]: ' . ERRMSG_INVALID_VALUE);
    }


    # Link labels initialization

    $arrIconInfo = [
        // key in $spgmCfg | ALT value | CLASS value | alternative (if resource is N/A)
        [
            'gallerySmallIcon',
            '',
            CLASS_IMG_FOLDER,
            ''
        ],
        [
            'galleryBigIcon',
            '',
            CLASS_IMG_FOLDER,
            '&raquo;'
        ],
        [
            'previousPageIcon',
            'Previous thumbnail page',
            CLASS_IMG_THMBNAVI_PREV,
            '&laquo;'
        ],
        [
            'previousPageIconNot',
            'Disabled previous thumbnail page',
            CLASS_IMG_THMBNAVI_PREV,
            '&laquo;'
        ],
        [
            'firstPageIcon',
            'First thumbnail page',
            CLASS_IMG_THMBNAVI_PREV,
            '&laquo;&laquo;'
        ],
        [
            'firstPageIconNot',
            'Disabled first thumbnail page',
            CLASS_IMG_THMBNAVI_PREV,
            '&laquo;&laquo;'
        ],
        [
            'nextPageIcon',
            'Next thumbnail page',
            CLASS_IMG_THMBNAVI_NEXT,
            '&raquo;'
        ],
        [
            'nextPageIconNot',
            'Disabled next thumbnail page',
            CLASS_IMG_THMBNAVI_NEXT,
            '&raquo;'
        ],
        [
            'lastPageIcon',
            'Last thumbnail page',
            CLASS_IMG_THMBNAVI_NEXT,
            '&raquo;&raquo;'
        ],
        [
            'lastPageIconNot',
            'Disabled last thumbnail page',
            CLASS_IMG_THMBNAVI_NEXT,
            '&raquo;&raquo;'
        ],
        [
            'previousPictureIcon',
            'Previous picture',
            CLASS_IMG_PICTURE_PREV,
            '&laquo;'
        ],
        [
            'nextPictureIcon',
            'Next picture',
            CLASS_IMG_PICTURE_NEXT,
            '&raquo;'
        ],
        [
            'newItemIcon',
            '',
            CLASS_IMG_NEW,
            ''
        ]
    ];

    $dim = [];
    $iIconNumber = count($arrIconInfo);
    $strIconFileName = '';
    $Key = '';
    $LblAlt = '';
    $LblClass = '';
    $LblNa = '';

    for ($i = 0; $i < $iIconNumber; $i++) {
        $Key = $arrIconInfo[$i][0];
        $LblAlt = $arrIconInfo[$i][1];
        $LblClass = $arrIconInfo[$i][2];
        $LblNa = $arrIconInfo[$i][3];
        $strIconFileName2 = DIR_THEMES . $spgmCfg['conf']['theme'] . '/' . $spgmCfg['theme'][$Key];
        $strIconFileName = DIR_THEMES . $spgmCfg['conf']['theme'] . '/' . rawurlencode($spgmCfg['theme'][$Key]);

        if ($spgmCfg['theme'][$Key] != '' && spgm_CheckPerms($strIconFileName)) {
            $dim = getimagesize($strIconFileName2);
            $spgmCfg['theme'][$Key] = '<img src="' . $strIconFileName . '"';
            $spgmCfg['theme'][$Key] .= ' alt="' . $LblAlt . '"';
            $spgmCfg['theme'][$Key] .= ' class="' . $LblClass . '"';
            $spgmCfg['theme'][$Key] .= ' width="' . $dim[0] . '"';
            $spgmCfg['theme'][$Key] .= ' height="' . $dim[1] . '" />';
        } else {
            if ($LblNa != '') {
                $spgmCfg['theme'][$Key] = $LblNa;
            }
        }
    }
}


################################################################################
# Loads config files from the possible different locations.
# To allow properties inheritance, it includes all the config file from the
# top level gallery to the gallery itself.
# TODO: support for INI files (PHP4) ?

function spgm_LoadConfig($strGalleryId)
{
    global $spgmCfg;

    spgm_Trace('<p>funtion spgm_LoadConfig</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n");


    if (spgm_IsGallery($strGalleryId)) {

        // always load the default config file
        $strConfigurationFileName = DIR_GAL . FILE_CONF;

        if (spgm_CheckPerms($strConfigurationFileName)) {
            include $strConfigurationFileName;
        }

        // now, include all the possible config files
        if ($strGalleryId != '') {
            $strConfigurationPathElements = explode('/', $strGalleryId);
            $iPathDepth = count($strConfigurationPathElements);
            $StrConfigurationPath = ''; // grows inside the follwing loop ("gal1" -> "gal1/gal2"...)
            for ($i = 0; $i < $iPathDepth; $i++) {
                // use "foreach ($strConfigurationPathElements as $dirName) {" in PHP4

                $StrConfigurationPath .= $strConfigurationPathElements[$i] . '/';
                $strConfigurationFileName = DIR_GAL . $StrConfigurationPath . FILE_CONF;
                if (spgm_CheckPerms($strConfigurationFileName)) {
                    include $strConfigurationFileName;
                }
            }
        }
    }

    spgm_LoadLanguage($spgmCfg['conf']['language']);
    spgm_LoadFlavor($spgmCfg['conf']['theme']);
    spgm_PostInitCheck();
}


################################################################################
# returns an array containing various information for a given gallery and its
# provided pictures.
# returned array:
# $array[0] = total number of pictures
# $array[1] = number of new pictures
# $array[2] = the thumbnail's filename to use for the gallery icon

function spgm_GetGalleryInfo($strGalleryId, $arrPictureFilenames)
{
    global $spgmCfg;

    $iPictureNumber = 0;
    $iNewPictureNumber = 0;
    $strPathToGalleries = DIR_GAL . $strGalleryId;
    $iPictureNumber = count($arrPictureFilenames);
    $iNewPictureNumber = 0;
    for ($i = 0; $i < $iPictureNumber; $i++) {
        if (spgm_IsNew($strPathToGalleries . '/' . $arrPictureFilenames[$i])) {
            $iNewPictureNumber++;
        }
    }

    spgm_Trace('<p>function spgm_GetGalleryInfo</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'iPictureNumber: ' . $iPictureNumber . '<br>' . "\n" . 'strPathToGalleries: ' . $strPathToGalleries . '<br>' . "\n");

    $arrGalleryInfo[0] = $iPictureNumber;
    $arrGalleryInfo[1] = $iNewPictureNumber;
    if ($spgmCfg['conf']['galleryIconType'] == GALICON_RANDOM && $iPictureNumber > 0) {
        @$arrGalleryInfo[2] = $arrPictureFilenames[rand(0, $iPictureNumber - 1)];
    } else {
        $arrGalleryInfo[2] = '';
    }

    return $arrGalleryInfo;
}


###############################################################################
# Callback function used to sort galleries/pictures against modification date
# The two parameters are automatically passed by the usort() function

function spgm_CallbackCompareMTime($strFilePath1, $strFilePath2)
{
    global $spgmCfg;

    if (!strcmp($strFilePath1, $strFilePath2)) {
        return 0;
    }

    return (filemtime($spgmCfg['global']['tmpPathToPics'] . $strFilePath1) > filemtime($spgmCfg['global']['tmpPathToPics'] . $strFilePath2)) ? 1 : -1;
}


################################################################################
# Creates a sorted array containing first level sub-galleries of a given gallery
# $galid - the gallery ID to introspect
# $display - boolean indicating that galleries will be rendered and that sort
#            options consequently have to be turned on
# returns: a sorted array containing the sub-gallery filenames for the given
#            gallery

function spgm_CreateGalleryArray($strGalleryId, $bToBeDisplayed)
{
    global $spgmCfg;

    $strPathToGallery = DIR_GAL . $strGalleryId;

    spgm_Trace('<p>function spgm_CreateGalleryArray</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'strPathToGallery: ' . $strPathToGallery . '<br>' . "\n" . 'bToBeDisplayed: ' . $bToBeDisplayed . '<br>' . "\n");

    if (spgm_IsGallery($strGalleryId)) {
        $HDir = @opendir($strPathToGallery);
    } else {
        spgm_Error($strGalleryId . ': ' . ERRMSG_UNKNOWN_GALLERY);
    }
    if ($strGalleryId != '') {
        $strGalleryId .= '/';
    } // little hack

    if ($strPathToGallery == DIR_GAL) {
        $strSortFilePath = $strPathToGallery . FILE_GAL_SORT;
    } else {
        $strSortFilePath = $strPathToGallery . '/' . FILE_GAL_SORT;
    }

    $arrSubGalleries = [];
    if (spgm_CheckPerms($strSortFilePath)) {
        $strGalleryNames = file($strSortFilePath);
        $iGalleryNumber = count($strGalleryNames);
        for ($i = 0; $i < $iGalleryNumber; $i++) {
            $strGalleryName = trim($strGalleryNames[$i]);
            if (spgm_IsGallery($strGalleryId . $strGalleryName)) {
                $arrSubGalleries[] = $strGalleryName;
            }
        }
    } else {
        while (false !== ($StrFilename = readdir($HDir))) {
            if ($StrFilename != '.' && $StrFilename != '..' && spgm_IsGallery($strGalleryId . $StrFilename)) {
                // add the gallery to the list if not hidden
                if (!file_exists($strPathToGallery . '/' . $StrFilename . '/' . FILE_GAL_HIDE)) {
                    $arrSubGalleries[] = $StrFilename;
                }
            }
        }
        closedir($HDir);

        // Apply sort options if needed
        if ($bToBeDisplayed) {
            if (count($arrSubGalleries) > 0) {
                if ($spgmCfg['conf']['gallerySortType'] == SORTTYPE_NAME) {
                    if ($spgmCfg['conf']['gallerySortOptions'] == SORT_DESCENDING) {
                        rsort($arrSubGalleries);
                    } else {
                        sort($arrSubGalleries);
                    }
                } elseif ($spgmCfg['conf']['gallerySortType'] == SORTTYPE_CREATION_DATE) {
                    $spgmCfg['global']['tmpPathToPics'] = DIR_GAL . $strGalleryId;
                    usort($arrSubGalleries, 'spgm_CallbackCompareMTime'); // TODO: omit it ?
                    if ($spgmCfg['conf']['gallerySortOptions'] == SORT_DESCENDING) {
                        $arrSubGalleries = array_reverse($arrSubGalleries);
                    }
                }
            }
        }
    }

    return $arrSubGalleries;
}


################################################################################
# Creates a sorted array of the pictures to diplay for a given gallery
# $galid - the gallery ID (must be always valid)
# $filter - the filter that defines the pictures to include in the list
# $display - boolean indicating that thumbnails will be rendered and that sort
#            options consequently have to be turned on
# returns: a sorted array containing the thumbnails' basenames of the gallery

function spgm_CreatePictureArray($strGalleryId, $strFilterFlags, $bForDisplayPurpose)
{
    global $spgmCfg;

    $strPathToGallery = DIR_GAL . $strGalleryId . '/';
    $hDir = opendir($strPathToGallery);

    spgm_Trace('<p>function spgm_CreatePictureArray</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'strFilterFlags: ' . $strFilterFlags . '<br>' . "\n" . 'strPathToGallery: ' . $strPathToGallery . '<br>' . "\n" . 'bForDisplayPurpose: ' . $bForDisplayPurpose . '<br>' . "\n");

    $arrPictureFilenames = [];
    $strPathToSortFile = $strPathToGallery . FILE_PIC_SORT;
    if (spgm_CheckPerms($strPathToSortFile)) {
        $arrSortedPictureFilenames = file($strPathToSortFile);
        $Max = count($arrSortedPictureFilenames);
        for ($i = 0; $i < $Max; $i++) {
            $strPictureName = trim($arrSortedPictureFilenames[$i]);
            if (spgm_IsPicture($strPictureName, $strGalleryId)) {
                if (false !== strpos($strFilterFlags, PARAM_VALUE_FILTER_NEW)) {
                    if (spgm_IsNew($strPathToGallery . $strPictureName)) {
                        $arrPictureFilenames[] = $strPictureName;
                    }
                } else {
                    $arrPictureFilenames[] = $strPictureName;
                }
            }
        }
    } else {
        while (false !== ($strFileName = readdir($hDir))) {
            if (spgm_IsPicture($strFileName, $strGalleryId)) {
                if (false !== strpos($strFilterFlags, PARAM_VALUE_FILTER_NEW)) {
                    if (spgm_IsNew($strPathToGallery . $strFileName)) {
                        $arrPictureFilenames[] = $strFileName;
                    }
                } else {
                    $arrPictureFilenames[] = $strFileName;
                }
            }
        }
        closedir($hDir);

        // Apply sort optionsif needed
        if ($bForDisplayPurpose) {
            if (count($arrPictureFilenames) > 0) {
                if ($spgmCfg['conf']['pictureSortType'] == SORTTYPE_NAME) {
                    if ($spgmCfg['conf']['pictureSortOptions'] == SORT_DESCENDING) {
                        rsort($arrPictureFilenames);
                    } else {
                        sort($arrPictureFilenames);
                    }
                } elseif ($spgmCfg['conf']['pictureSortType'] == SORTTYPE_CREATION_DATE) {
                    $spgmCfg['global']['tmpPathToPics'] = $strPathToGallery;
                    usort($arrPictureFilenames, 'spgm_CallbackCompareMTime'); // TODO: omit it ?
                    if ($spgmCfg['conf']['pictureSortOptions'] == SORT_DESCENDING) {
                        $arrPictureFilenames = array_reverse($arrPictureFilenames);
                    }
                }
            }
        }
    }

    return $arrPictureFilenames;
}


################################################################################

function spgm_DisplayThumbnailNavibar($iCurrentPageIndex, $iPageNumber, $strGalleryId, $strFilterFlags)
{
    global $spgmCfg;

    spgm_Trace('<p>function spgm_DisplayThumbnailNavibar</p>' . "\n" . 'iCurrentPageIndex: ' . $iCurrentPageIndex . '<br>' . "\n" . 'iPageNumber: ' . $iPageNumber . '<br>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n");

    // display left arrows
    if ($iCurrentPageIndex > 1) {
        $iPreviousPageIndex = $iCurrentPageIndex - 1;
        echo spgm_BuildLink($spgmCfg['theme']['firstPageIcon'], '', '', $strGalleryId, 1, -1, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
        echo '&nbsp; ';
        echo spgm_BuildLink($spgmCfg['theme']['previousPageIcon'], '', '', $strGalleryId, $iPreviousPageIndex, -1, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
    } else {
        echo ' ' . $spgmCfg['theme']['firstPageIconNot'];
        echo ' &nbsp; ' . $spgmCfg['theme']['previousPageIconNot'];
    }
    echo ' &nbsp; ';

    // display the page numbers
    for ($i = 1; $i <= $iPageNumber; $i++) {
        if ($i != $iCurrentPageIndex) {
            echo spgm_BuildLink($i, 'navi', '', $strGalleryId, $i, -1, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
        } else {
            echo $i;
        } // don't make it an anchor if this is the current page
        if ($i < $iPageNumber) {
            echo ' &nbsp; ';
        }
    }

    // display right arrows
    echo ' &nbsp;';
    if ($iCurrentPageIndex < $iPageNumber) {
        $iNextPageIndex = $iCurrentPageIndex + 1;
        echo spgm_BuildLink($spgmCfg['theme']['nextPageIcon'], '', '', $strGalleryId, $iNextPageIndex, -1, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
        echo '&nbsp; ';
        echo spgm_BuildLink($spgmCfg['theme']['lastPageIcon'], '', '', $strGalleryId, $iPageNumber, -1, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
    } else {
        echo ' ' . $spgmCfg['theme']['nextPageIconNot'];
        echo '  ' . $spgmCfg['theme']['lastPageIconNot'];
    }
}

################################################################################

function spgm_DisplayFilterToggles($strGalleryId, $strFilterFlags, $arrGalleryInfo)
{
    global $spgmCfg;

    spgm_Trace('<p>function spgm_DisplayFilterToggles</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'strFilterFlags: ' . $strFilterFlags . '<br>' . "\n");

    $strHtmlToggles = '';
    $bFilterNewOn = strstr($strFilterFlags, PARAM_VALUE_FILTER_NEW);
    if (($arrGalleryInfo[1] > 0 && $arrGalleryInfo[0] != $arrGalleryInfo[1]) || $bFilterNewOn) {
        if ($bFilterNewOn) {
            $strHtmlToggles .= spgm_BuildLink($spgmCfg['locale']['filterAll'], '', '', $strGalleryId, -1, -1, str_replace(PARAM_VALUE_FILTER_NEW, '', $strFilterFlags));
        } else {
            $strHtmlToggles .= spgm_BuildLink($spgmCfg['locale']['filterNew'], '', '', $strGalleryId, -1, -1, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags) . PARAM_VALUE_FILTER_NEW);
        }

        echo ' &nbsp;&nbsp;<span class="' . CLASS_SPAN_FILTERS . '">[' . $spgmCfg['locale']['filter'] . ' &raquo; ' . $strHtmlToggles . ']</span>' . "\n";
    }
}


################################################################################
# Prerequisite: spgm_IsGallery($galid) == true

function spgm_DisplayGalleryNavibar($strGalleryId, $strFilterFlags, $mixPictureId = '', $arrPictureFilenames)
{
    global $spgmCfg;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/source/functions.php';

    spgm_Trace('<p>function spgm_DisplayGalleryNavibar</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'strFilterFlags: ' . $strFilterFlags . '<br>' . "\n" . 'mixPictureId: ' . $mixPictureId . '<br>' . "\n");

    $arrExplodedPathToGallery = explode('/', $strGalleryId);

    echo '  <div class="' . CLASS_DIV_GALHEADER . '">' . "\n";

    // display main gallery link
    $filters = '';
    if ($spgmCfg['global']['propagateFilters']) {
        $filters = str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags);
    }
    if ($spgmCfg['theme']['gallerySmallIcon'] != '') {
        echo spgm_BuildLink($spgmCfg['theme']['gallerySmallIcon'], CLASS_TD_GALITEM_TITLE, '', '', -1, -1, $filters);
    } else {
        echo spgm_BuildLink($spgmCfg['locale']['rootGallery'], CLASS_TD_GALITEM_TITLE, '', '', -1, -1, $filters);
    }

    // display each gallery of the hierarchy
    $strHtmlGalleryLink = $arrExplodedPathToGallery[0]; // to avoid the first '/'
    $Max = count($arrExplodedPathToGallery);
    $StrGalleryId = '';

    for ($i = 0; $i < $Max; $i++) {
        $StrGalleryId .= $arrExplodedPathToGallery[$i] . '/';
        $StrPathToGallery = DIR_GAL . $StrGalleryId;
        $StrPathToGalleryTitle = $StrPathToGallery . FILE_GAL_TITLE;
        $strHtmlGalleryName = '';
        if (spgm_CheckPerms($StrPathToGalleryTitle)) {
            $arrTitle = file($StrPathToGalleryTitle);
            $strHtmlGalleryName = $arrTitle[0];
        } else {
            $strHtmlGalleryName = str_replace('_', ' ', $arrExplodedPathToGallery[$i]);
        }

        echo ' &raquo; ';

        /**
         * provide crosslinks to screenshot gallery, log page, etc
         */
        $gCrosslinks = System::crosslinks($arrExplodedPathToGallery[$i], false, true);

        if ($i < ($Max - 1)) {
            echo spgm_BuildLink($strHtmlGalleryName, CLASS_DIV_GALHEADER, '', $strHtmlGalleryLink, -1, -1, $filters);
            $strHtmlGalleryLink .= '/' . $arrExplodedPathToGallery[$i + 1];
        } else {
            // Final gallery display
            $iCurrentPageIndex = 1;

            if (false !== strpos($strFilterFlags, PARAM_VALUE_FILTER_NOTHUMBS) || false !== strpos($strFilterFlags, PARAM_VALUE_FILTER_SLIDESHOW)) {
                if ($mixPictureId == '') {
                    echo $strHtmlGalleryName;
                } else {
                    $iCurrentPageIndex = ((int)($mixPictureId / $spgmCfg['conf']['thumbnailsPerPage'])) + 1;
                    echo spgm_BuildLink($strHtmlGalleryName, CLASS_DIV_GALHEADER, '', $strHtmlGalleryLink, $iCurrentPageIndex, -1, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
                }
            } else {
                echo $strHtmlGalleryName;
            }
        }
        echo $gCrosslinks;
    }

    // Notify if we are in "new picture mode"
    if (false !== strpos($strFilterFlags, PARAM_VALUE_FILTER_NEW)) {
        echo ' (' . $spgmCfg['locale']['newPictures'] . ')';
    }

    // Link to slideshow mode
    if ($spgmCfg['conf']['enableSlideshow'] == true) {
        if (false === strpos($strFilterFlags, PARAM_VALUE_FILTER_SLIDESHOW) && count($arrPictureFilenames) > 0) {
            echo ' [';
            echo spgm_BuildLink($spgmCfg['locale']['filterSlideshow'], CLASS_DIV_GALHEADER, '', $strHtmlGalleryLink, $iCurrentPageIndex, 0, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags) . PARAM_VALUE_FILTER_SLIDESHOW);
            echo ']';
        }
    }


    echo "\n" . '      </div>' . "\n";
}


################################################################################
# Recursive function to display all galleries as a hierarchy

function spgm_DisplayGalleryHierarchy($strGalleryId, $iGalleryDepth, $strFilterFlags)
{
    global $spgmCfg;

    $strPathToGallery = DIR_GAL . $strGalleryId;

    spgm_Trace('<p>function spgm_DisplayGalleryHierarchy</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'iGalleryDepth: ' . $iGalleryDepth . '<br>' . "\n" . 'strFilterFlags: ' . $strFilterFlags . '<br>' . "\n" . 'strPathToGallery: ' . $strPathToGallery . '<br>' . "\n");

    $strHtmlOffset = '';

    // check for super gallery.
    if ($strGalleryId == '') {
        $strPathToSuperGallery = '';
    } else {
        $strPathToSuperGallery = $strGalleryId . '/';
        for ($i = 0; $i < $iGalleryDepth; $i++) {
            $strHtmlOffset .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
    }

    # 'new' label tuning according to the actual new item
    if ($spgmCfg['theme']['newItemIcon'] != '') {
        $strHtmlNewGallery = $spgmCfg['theme']['newItemIcon'];
        $strHtmlNewPictures = $spgmCfg['theme']['newItemIcon'];
        $strNewPicture = $spgmCfg['theme']['newItemIcon'];
    } else {
        $strHtmlSpanNewItem = '<span style="color: #ffd600">';
        $strHtmlNewGallery = $strHtmlSpanNewItem . $spgmCfg['locale']['newGallery'] . '</span>';
        $strHtmlNewPictures = $strHtmlSpanNewItem . $spgmCfg['locale']['newPictures'] . '</span>';
        $strNewPicture = $strHtmlSpanNewItem . $spgmCfg['locale']['newPicture'] . '</span>';
    }

    $arrSubGalleryFilenames = spgm_CreateGalleryArray($strGalleryId, true);
    $Max = count($arrSubGalleryFilenames);

    if ($iGalleryDepth == 1 && $Max > 0) {
        echo '<table class="' . CLASS_TABLE_GALLISTING_GRID . '">' . "\n";
        echo '<tr>' . "\n";
    }

    for ($i = 0; $i < $Max; $i++) {
        $strGalleryName = $arrSubGalleryFilenames[$i]; //**
        $strPathToSubGallery = $strPathToSuperGallery . $strGalleryName; //**
        $strPathToGalleryTitle = $strPathToGallery . '/' . $strGalleryName . '/' . FILE_GAL_TITLE;
        $strGalleryThumbnailBasename = DIR_GAL . urlencode($strPathToSuperGallery) . PREF_THUMB . urlencode($strGalleryName);
        $strHtmlGalleryName = '';
        if (spgm_CheckPerms($strPathToGalleryTitle)) {
            $arrTitle = file($strPathToGalleryTitle);
            $strHtmlGalleryName = $arrTitle[0];
        } else {
            $strHtmlGalleryName = str_replace('_', ' ', $strGalleryName);
        }
        $arrPictureFilenames = spgm_CreatePictureArray($strPathToSubGallery, '', false); // no filter is provided to get all the pictures
        $arrGalleryInfo = spgm_GetGalleryInfo($strPathToSubGallery, $arrPictureFilenames);
        $iPictureNumber = $arrGalleryInfo[0];
        $iNewPictureNumber = $arrGalleryInfo[1];
        $strRandomPictureFilename = $arrGalleryInfo[2];

        // should never happen
        if ($iPictureNumber < 0 || $iNewPictureNumber < 0) {
            spgm_Error('Error while generating gallery ' . ERRMSG_INVALID_NUMBER_OF_PICTURES);
        } else {
            if ($spgmCfg['conf']['thumbnailsPerPage'] > 0) {
                $strUrlParamPage = '&amp;' . PARAM_NAME_PAGE . '=1';
            }
            if ($iPictureNumber == 0) {
                $strHtmlPictureNumber = '';
            } else {
                if ($iPictureNumber > 1) {
                    $strHtmlPictureNumber = '&nbsp;&nbsp;[' . $iPictureNumber . ' ' . $spgmCfg['locale']['pictures'];
                } else {
                    $strHtmlPictureNumber = '&nbsp;&nbsp;[' . $iPictureNumber . ' ' . $spgmCfg['locale']['picture'];
                }
                $bAllPicturesNew = ($iPictureNumber == $iNewPictureNumber);
                if ($bAllPicturesNew) {
                    $strHtmlPictureNumber = $strHtmlNewGallery . ' ' . $strHtmlPictureNumber;
                }
                if ($iNewPictureNumber > 0 && !$bAllPicturesNew) {
                    $strHtmlPictureNumber .= ' - ' . $iNewPictureNumber . ' ';
                    $filters = '';
                    if ($spgmCfg['global']['propagateFilters']) {
                        $filters = str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags);
                    }
                    if (false === strpos($strFilterFlags, PARAM_VALUE_FILTER_NEW)) {
                        $filters .= PARAM_VALUE_FILTER_NEW;
                    }
                    if ($iNewPictureNumber == 1) {
                        $strHtmlPictureNumber .= spgm_BuildLink($strNewPicture, '', '', $strPathToSubGallery, -1, -1, $filters);
                    } else {
                        $strHtmlPictureNumber .= spgm_BuildLink($strHtmlNewPictures, '', '', $strPathToSubGallery, -1, -1, $filters);
                    }
                }
                $strHtmlPictureNumber .= ']';
            }

            if ($iGalleryDepth <= 1) {
                if (($i % $spgmCfg['conf']['galleryListingCols'] == 0) && ($i != 0)) {
                    echo '      </tr>' . "\n" . '      <tr>' . "\n";
                }
                echo '  <td class="' . CLASS_TD_GALLISTING_CELL . '">' . "\n";
            }

            echo '  <table class="' . CLASS_TABLE_GALITEM . '">' . "\n";
            echo '      <tr>' . "\n";

            // display the gallery icon
            $iRowSpan = ($spgmCfg['conf']['galleryCaptionPos'] == BOTTOM) ? 1 : 2;
            echo '      <td rowspan="' . $iRowSpan . '" style="vertical-align: top" class="' . CLASS_TD_GALITEM_ICON . '">' . "\n";
            if ($strHtmlOffset != '') {
                echo '      ' . $strHtmlOffset . "\n";
            }

            // look for the icon...
            $strHtmlIcon = '';
            $bNeedDropShadows = true; // only default icons don't need them
            // find out if there is a fixed thumbnail
            $bGalleryThumbnailFound = false;
            $iSupportedExtensionNumber = count($spgmCfg['global']['supportedExtensions']);
            for ($j = 0; $j < $iSupportedExtensionNumber; $j++) {
                $strGalleryThumbnailFilename = $strGalleryThumbnailBasename . $spgmCfg['global']['supportedExtensions'][$j];
                if (spgm_CheckPerms($strGalleryThumbnailFilename)) {
                    $arrPictureSize = getimagesize($strGalleryThumbnailFilename);
                    $strHtmlIcon = '<img src="' . $strGalleryThumbnailFilename . '" width="';
                    $strHtmlIcon .= $arrPictureSize[0] . '" height="' . $arrPictureSize[1];
                    $strHtmlIcon .= '" alt="" class="' . CLASS_IMG_GALICON . '" />';
                    $bGalleryThumbnailFound = true;
                    break;
                }
            }
            if (!$bGalleryThumbnailFound) {
                // random thumbnails are used
                if ($strRandomPictureFilename != '') {
                    if (defined('DIR_THUMBS')) {
                        $strGalleryThumbnailFilename = DIR_GAL . $strPathToSubGallery . '/' . DIR_THUMBS;
                        $strGalleryThumbnailFilename .= PREF_THUMB . $strRandomPictureFilename;
                    } else {
                        $strGalleryThumbnailFilename = DIR_GAL . $strPathToSubGallery . '/';
                        $strGalleryThumbnailFilename .= PREF_THUMB . $strRandomPictureFilename;
                    }
                    $arrPictureSize = getimagesize($strGalleryThumbnailFilename);
                    if ($spgmCfg['conf']['galleryIconHeight'] != ORIGINAL_SIZE) {
                        $strHtmlHeight = 'height="' . $spgmCfg['conf']['galleryIconHeight'] . '"';
                    } else {
                        if ($spgmCfg['conf']['galleryIconWidth'] != ORIGINAL_SIZE) {
                            $iHeight = (int)$arrPictureSize[1] * ($spgmCfg['conf']['galleryIconWidth'] / $arrPictureSize[0]);
                            $strHtmlHeight = 'height="' . $iHeight . '"';
                        } else {
                            $strHtmlHeight = 'height="' . $arrPictureSize[1] . '"';
                        }
                    }

                    if ($spgmCfg['conf']['galleryIconWidth'] != ORIGINAL_SIZE) {
                        $strHtmlWidth = 'width="' . $spgmCfg['conf']['galleryIconWidth'] . '"';
                    } else {
                        if ($spgmCfg['conf']['galleryIconHeight'] != ORIGINAL_SIZE) {
                            $iWidth = (int)$arrPictureSize[0] * ($spgmCfg['conf']['galleryIconHeight'] / $arrPictureSize[1]);
                            $strHtmlWidth = 'width="' . $iWidth . '"';
                        } else {
                            $strHtmlWidth = 'width="' . $arrPictureSize[0] . '"';
                        }
                    }

                    $strHtmlIcon = '<img src="' . $strGalleryThumbnailFilename . '" ';
                    $strHtmlIcon .= $strHtmlHeight . ' ' . $strHtmlWidth . ' alt="" class="';
                    $strHtmlIcon .= CLASS_IMG_GALICON . '" />';
                } // nor fixed and random thumbnails => default icons
                else {
                    $bNeedDropShadows = false;
                    if ($spgmCfg['conf']['galleryIconType'] == GALICON_NONE) {
                        $fnameGalleryIcon = $spgmCfg['theme']['gallerySmallIcon'];
                    } else {
                        $fnameGalleryIcon = $spgmCfg['theme']['galleryBigIcon'];
                    }
                    $strHtmlIcon = ($fnameGalleryIcon != '') ? $fnameGalleryIcon : '&raquo;';
                }
            }

            // display the link
            if ($bNeedDropShadows == true) {
                spgm_DropShadowsBeginWrap();
            }
            $filters = '';
            if ($spgmCfg['global']['propagateFilters']) {
                $filters = str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', str_replace(PARAM_VALUE_FILTER_NEW, '', $strFilterFlags));
            }
            echo '          ' . spgm_BuildLink($strHtmlIcon, CLASS_TD_GALITEM_TITLE, '', $strPathToSubGallery, -1, -1, $filters) . "\n";

            if ($bNeedDropShadows == true) {
                spgm_DropShadowsEndWrap();
            }

            echo '      </td>' . "\n";

            if ($spgmCfg['conf']['galleryCaptionPos'] == BOTTOM) {
                echo '      </tr>' . "\n" . '      <tr>' . "\n";
            }

            /**
             * provide crosslinks to screenshot gallery, log page, etc
             */
            $g2_crosslinks = System::crosslinks($strHtmlGalleryName, false, false);

            // display the gallery title
            echo '      <td class="' . CLASS_TD_GALITEM_TITLE . '">' . "\n";
            echo '          ' . spgm_BuildLink($strHtmlGalleryName, CLASS_TD_GALITEM_TITLE, '', $strPathToSubGallery, -1, -1, $filters);
            echo ' ' . $g2_crosslinks . $strHtmlPictureNumber . ' ' . "\n";
            echo '      </td>' . "\n";
            echo '      </tr>' . "\n";

            // display the gallery caption
            echo '      <tr>' . "\n";
            echo '      <td class="' . CLASS_TD_GALITEM_CAPTION . '">' . "\n";
            $strPathToGalleryCaption = $strPathToGallery . '/' . $strGalleryName . '/' . FILE_GAL_CAPTION;
            if (spgm_CheckPerms($strPathToGalleryCaption)) {
                // check perms

                echo '          ';
                include $strPathToGalleryCaption;
            }
            echo '      </td>' . "\n";
            echo '      </tr>' . "\n";
            echo '  </table>' . "\n";
        }

        // TODO check this: one test ?
        if ($spgmCfg['conf']['subGalleryLevel'] == 0) {
            spgm_DisplayGalleryHierarchy($strPathToSubGallery, $iGalleryDepth + 1, $strFilterFlags);
        } elseif ($iGalleryDepth < $spgmCfg['conf']['subGalleryLevel'] - 1) {
            spgm_DisplayGalleryHierarchy($strPathToSubGallery, $iGalleryDepth + 1, $strFilterFlags);
        }

        if ($iGalleryDepth <= 1) {
            echo '  </td>' . "\n";
        }
    } // endfor

    if ($iGalleryDepth == 1 && $Max > 0) {
        echo ' </tr>' . "\n";
        echo '</table>' . "\n";
    }
}

################################################################################

function spgm_DisplayPicture($strGalleryId, $iPictureId, $strFilterFlags)
{
    global $spgmCfg, $settings;

    $arrPictureFilenames = spgm_CreatePictureArray($strGalleryId, $strFilterFlags, true);
    $iPictureNumber = count($arrPictureFilenames);
    $strPathToPictures = DIR_GAL . $strGalleryId . '/';
    $urlPathToPictures = URL_GAL . $strGalleryId . '/';
    $strPictureFilename = $arrPictureFilenames[$iPictureId];
    $StrFileExtension = strrchr($strPictureFilename, '.');
    $strPictureBasename = substr($strPictureFilename, 0, -strlen($StrFileExtension));
    $strPictureURL = $urlPathToPictures . rawurlencode($strPictureFilename);
    $strPictureURL2 = $strPathToPictures . $strPictureFilename;
    $strCaptionURL = $strPictureURL . EXT_PIC_CAPTION; // DEPRECATED
    $strGalleryName = str_replace('_', ' ', $strGalleryId);
    $strGalleryName = str_replace('/', ' &raquo; ', $strGalleryName);
    $bSlideshowMode = false !== strpos($strFilterFlags, PARAM_VALUE_FILTER_SLIDESHOW);

    if ($spgmCfg['conf']['thumbnailsPerPage'] != 0) {
        $iPageNumber = $iPictureNumber / $spgmCfg['conf']['thumbnailsPerPage'];
        if ($iPageNumber > (int)($iPictureNumber / $spgmCfg['conf']['thumbnailsPerPage'])) {
            $iPageNumber = (int)++$iPageNumber;
        }
    }

    spgm_Trace('<p>function spgm_DisplayPicture</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'strPictureFilename: ' . $strPictureFilename . '<br>' . "\n" . 'strPathToPictures: ' . $strPathToPictures . '<br>' . "\n" . 'strPictureURL: ' . $strPictureURL . '<br>' . "\n");


    if (($iPictureId < 0) || ($iPictureId > $iPictureNumber - 1) || $iPictureId == '') {
        spgm_Error(ERRMSG_UNKNOWN_PICTURE);
    }

    if (!spgm_IsGallery($strGalleryId)) {
        spgm_Error(ERRMSG_UNKNOWN_GALLERY);
    }


    if (spgm_IsPicture($strPictureFilename, $strGalleryId)) {
        $arrPictureDim = getimagesize($strPictureURL2);
        $iPreviousPictureId = $iPictureId - 1;
        $iNextPictureId = $iPictureId + 1;

        // always display the gallery header
        spgm_DisplayGalleryNavibar($strGalleryId, $strFilterFlags, $iPictureId, $arrPictureFilenames);

        // thumbnails are only displayed if wanted
        if (false === strpos($strFilterFlags, PARAM_VALUE_FILTER_NOTHUMBS) && !$bSlideshowMode) {
            spgm_DisplayThumbnails($strGalleryId, $arrPictureFilenames, $iPictureId, '', $strFilterFlags);
        }

        // left-right orientation
        if ($spgmCfg['conf']['galleryOrientation'] == ORIENTATION_LEFTRIGHT) {
            echo '  <td class="' . CLASS_TD_ORIENTATION_RIGHT . '">' . "\n\n";
        }

        // Prepare layout for stuff left
        echo '<br><br>' . "\n";
        echo '<table cellspacing="0" class="' . CLASS_TABLE_PICTURE . '">' . "\n";

        // display the previous/next arrow section if we are not in slideshow mode
        if (!$bSlideshowMode) {
            echo ' <tr>' . "\n";
            echo ' <td class="' . CLASS_TD_PICTURE_NAVI . '"><a id="' . ID_PICTURE_NAVI . '"></a>' . "\n";

            if ($iPreviousPictureId >= 0) {
                echo spgm_BuildLink($spgmCfg['theme']['previousPictureIcon'], 'h', ANCHOR_PICTURE, $strGalleryId, -1, $iPreviousPictureId, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
            }
            //multi-language support
            $spgmCfg['locale']['pictureNaviBar'] = str_replace(PATTERN_CURRENT_PIC, "$iNextPictureId", $spgmCfg['locale']['pictureNaviBar']);
            $spgmCfg['locale']['pictureNaviBar'] = str_replace(PATTERN_NB_PICS, "$iPictureNumber", $spgmCfg['locale']['pictureNaviBar']);
            echo ' ' . $spgmCfg['locale']['pictureNaviBar'] . ' ';

            if ($iNextPictureId < $iPictureNumber) {
                echo spgm_BuildLink($spgmCfg['theme']['nextPictureIcon'], 'h', ANCHOR_PICTURE, $strGalleryId, -1, $iNextPictureId, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
            }
            echo '  </td>' . "\n" . ' </tr>' . "\n";
        }

        // Client side zoom buttons
        if (count($spgmCfg['conf']['zoomFactors']) > 0) {
            echo '</tr>' . "\n" . '<tr>' . "\n" . '    <td class="' . CLASS_TD_ZOOM_FACTORS . '">' . "\n";
            for ($i = 0, $iMax = count($spgmCfg['conf']['zoomFactors']); $i < $iMax; $i++) {
                $iHeight = (int)($arrPictureDim[1] * $spgmCfg['conf']['zoomFactors'][$i] / 100);
                $iWidth = (int)($arrPictureDim[0] * $spgmCfg['conf']['zoomFactors'][$i] / 100);
                echo '<input type="button" class="' . CLASS_BUTTON_ZOOM_FACTORS . '" value=" ' . $spgmCfg['conf']['zoomFactors'][$i] . '% " ';
                echo 'onClick="document.getElementById(' . "'" . ID_PICTURE . "'" . ').setAttribute(' . "'" . 'height' . "'" . ', ' . $iHeight . '); ';
                echo 'document.getElementById(' . "'" . ID_PICTURE . "'" . ').setAttribute(' . "'" . 'width' . "'" . ', ' . $iWidth . '); ';
                echo 'document.getElementById(' . "'" . ID_PICTURE_NAVI . "'" . ').scrollIntoView()">' . "\n";
            }
            echo "\n" . '  </td>' . "\n" . '</tr>' . "\n";
        }

        // EXIF data
        if (count($spgmCfg['conf']['exifInfo']) > 0) {
            if (extension_loaded('exif')) {
                // ... where available

                echo '<tr><td>' . "\n";
                $strExifData = spgm_LoadExif($strPictureURL);
                echo '[<span onmouseover="return overlib(\'' . $strExifData . '\', CAPTION, \'' . $spgmCfg['locale']['exifHeading'] . ' ' . $strPictureFilename . '\', STICKY)" onmouseout="return nd()" style="color: #2e408d; font-weight:700; font-size: 9pt">Exif</span>]';
                echo '</td></tr>' . "\n";
            }
        }

        // Load pictures if slideshow mode is enabled
        if ($bSlideshowMode) {
            echo '<script>' . "\n";
            $iPictureNumber = count($arrPictureFilenames);
            $Dim = [];
            $StrPicturePath = '';
            for ($i = 0; $i < $iPictureNumber; $i++) {
                $StrPicturePath = $strPathToPictures . $arrPictureFilenames[$i];
                $Dim = getimagesize($StrPicturePath);
                $StrPictureCaption = '';
                if (isset($spgmCfg['captions'][$arrPictureFilenames[$i]])) {
                    $StrPictureCaption = $spgmCfg['captions'][$arrPictureFilenames[$i]];
                }
                echo '  addPicture(\'' . $StrPicturePath . '\', \'' . addslashes($StrPictureCaption) . '\', ' . $Dim[0] . ', ' . $Dim[1] . ');' . "\n";
            }
            echo '</script>' . "\n";
        }

        // compute image dimensions
        $iWidth = $arrPictureDim[0];
        $iHeight = $arrPictureDim[1];
        if ($spgmCfg['conf']['fullPictureWidth'] != ORIGINAL_SIZE) {
            $iWidth = $spgmCfg['conf']['fullPictureWidth'];
            if ($spgmCfg['conf']['fullPictureHeight'] == ORIGINAL_SIZE) {
                $iHeight = (int)$arrPictureDim[1] * ($spgmCfg['conf']['fullPictureWidth'] / $arrPictureDim[0]);
            } else {
                $iHeight = $spgmCfg['conf']['fullPictureHeight'];
            }
        } else {
            if ($spgmCfg['conf']['fullPictureHeight'] != ORIGINAL_SIZE) {
                $iHeight = $spgmCfg['conf']['fullPictureHeight'];
                $iWidth = (int)$arrPictureDim[0] * ($spgmCfg['conf']['fullPictureHeight'] / $arrPictureDim[1]);
            }
        }

        // Eventually display the picture
        echo '<tr>' . "\n";
        echo '  <td class="' . CLASS_TD_PICTURE_PIC . '">' . "\n";

        // Overlib hidden span for EXIF data
        echo '  <div id="overDiv" style="position: absolute; visibility:hidden;z-index: 1000"></div>' . "\n";

        spgm_DropShadowsBeginWrap();

        $strHtmlPicture = '<img id="' . ID_PICTURE . '" src="' . $strPictureURL . '" width="' . $iWidth . '" height="' . $iHeight . '"';
        $strHtmlPicture .= ' alt="' . $strPictureURL . '" class="' . CLASS_IMG_PICTURE . '" />';

        if (!($iNextPictureId < $iPictureNumber)) {
            $iNextPictureId = 0;
        } // to link to the appropriate next pic
        if (!$bSlideshowMode) {
            if ($spgmCfg['conf']['popupOverFullPictures'] == true) {
                $iPopupWidth = $spgmCfg['conf']['popupWidth'];
                $iPopupHeight = $spgmCfg['conf']['popupHeight'];
                $strJustPicture = 'false';
                if ($spgmCfg['conf']['popupFitPicture'] == true) {
                    $iPopupWidth = $arrPictureDim[0];
                    $iPopupHeight = $arrPictureDim[1];
                    $strJustPicture = 'true';
                }
                echo '      <a id="spgmPicture" target="_blank" href="' . $strPictureURL . '">';
                echo $strHtmlPicture;
                echo '</a>' . "\n";
            } else {
                echo spgm_BuildLink($strHtmlPicture, '', ANCHOR_PICTURE, $strGalleryId, -1, $iNextPictureId, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
            }
        } else {
            echo $strHtmlPicture;
        }

        spgm_DropShadowsEndWrap();

        echo '  </td>' . "\n";
        echo '</tr>' . "\n";

        $file = base64_encode(file_get_contents($strPictureURL2));
        // display the picture's filename if needed
        if ($spgmCfg['conf']['filenameWithPictures'] == true) {
            echo '<tr>' . "\n";
            echo '  <td class="' . CLASS_TD_PICTURE_FILENAME . '">' . "\n";
            echo '<span class="left"><a href="javascript:void(0)" onclick="confirmation(\'' . addslashes($strPictureURL2) . '\', \'screenshot\')" title="Delete screenshot"><div class="delete_button" style="position: relative; left:-6px;top: 0"><img src="/style/img/delete.png" alt="Delete"></div></a></span>' . "\n";
            echo $strPictureBasename . '' . $StrFileExtension . '';

            $imgurfile = $settings['new_screendir'] . '/Imgur/' . urldecode($strPictureBasename) . '.txt';

            if (!file_exists($imgurfile)) {
                echo '<span id="uploaded" Class="right"><a href="javascript:void(0)" onclick="imgurUpload(\'' . addslashes($file) . '\', \'' . addslashes($strPictureBasename) . '\')"><img src="/style/img/upload.png" alt="upload">&nbsp;Upload to Imgur</a></span><br>' . "\n";
            } else {
                $imgurUrl = file_get_contents($imgurfile);
                echo '<span id="uploaded" class="right"><a href="' . $imgurUrl . '">Link to your image on imgur.com</a><img class="ext_icon" src="/style/img/external_link.png" style="margin-bottom: 3px" alt="ext"></span><br>' . "\n";
            }

            echo ' </td>' . "\n";
            echo '</tr>' . "\n";
        }

        // display the caption
        echo '<tr>' . "\n";
        echo '  <td id="' . ID_PICTURE_CAPTION . '" class="' . CLASS_TD_PICTURE_CAPTION . '">&nbsp;' . "\n";
        if (isset($spgmCfg['captions'][$strPictureFilename])) {
            echo $spgmCfg['captions'][$strPictureFilename];
        }

        echo '  </td>' . "\n";
        echo '</tr>' . "\n";
        echo '</table>' . "\n";

        // left-right orientation
        if ($spgmCfg['conf']['galleryOrientation'] == ORIENTATION_LEFTRIGHT) {
            echo '  </td>' . "\n";
            echo '</tr>' . "\n";
            echo '</table>' . "\n";
        }

        if ($bSlideshowMode) {
            echo '<script>runSlideShow();</script>' . "\n";
        }
    } else {
        spgm_Error(ERRMSG_UNKNOWN_PICTURE);
    }
}

################################################################################

function spgm_DisplayGallery($strGalleryId, $iPageIndex, $strFilterFlags)
{
    spgm_Trace('<p>function spgm_DisplayGallery</p>' . "\n" . 'strGalleryId: ' . $strGalleryId . '<br>' . "\n" . 'iPageIndex: ' . $iPageIndex . '<br>' . "\n" . 'strFilterFlags: ' . $strFilterFlags . '<br>' . "\n");


    if (!spgm_IsGallery($strGalleryId)) {
        spgm_Error(ERRMSG_UNKNOWN_GALLERY);
    } else {
        $arrPictureFilenames = spgm_CreatePictureArray($strGalleryId, $strFilterFlags, true);
        if ($iPageIndex == '') {
            $iPageIndex = 1;
        }
        spgm_DisplayGalleryNavibar($strGalleryId, $strFilterFlags, '', $arrPictureFilenames);
        // display sub-galleries in a hierarchical manner
        spgm_DisplayGalleryHierarchy($strGalleryId, 1, $strFilterFlags);
        if (count($arrPictureFilenames) > 0) {
            spgm_DisplayThumbnails($strGalleryId, $arrPictureFilenames, '', $iPageIndex, $strFilterFlags);
        }
        // extra vertical padding before displaying the subgalleries
        echo '<br>' . "\n\n";
    }
}


################################################################################

function spgm_DisplayThumbnails($strGalleryId, $arrPictureFilenames, $iPictureId, $iPageIndex, $strFilterFlags)
{
    global $spgmCfg;

    $strPathToPictures = DIR_GAL . rawurlencode($strGalleryId) . '/';
    $strPathToPictures2 = DIR_GAL . $strGalleryId . '/';
    $urlPathToPictures = URL_GAL . $strGalleryId . '/';
    $iPictureNumber = count($arrPictureFilenames);
    $iPageNumber = $iPictureNumber / $spgmCfg['conf']['thumbnailsPerPage'];
    if ($iPageNumber > (int)($iPictureNumber / $spgmCfg['conf']['thumbnailsPerPage'])) {
        $iPageNumber = (int)++$iPageNumber;
    }
    if (!isset($iPageIndex)) {
        $iPictureOffsetStart = 0;
        $iPageFrom = 1;
    } else {
        if (($iPageIndex == '') || ($iPageIndex < 1) || ($iPageIndex > $iPageNumber)) {
            $iPageIndex = 1;
        }
    }

    if ($iPictureId == '') {
        $iPictureId = -1;
    } // so picture information are not highlighted
    else {
        $iPageIndex = ((int)($iPictureId / $spgmCfg['conf']['thumbnailsPerPage'])) + 1;
    }

    $iPictureOffsetStart = ($iPageIndex - 1) * $spgmCfg['conf']['thumbnailsPerPage'];
    $iPictureOffsetStop = $iPictureOffsetStart + $spgmCfg['conf']['thumbnailsPerPage'];
    if ($iPictureOffsetStop > $iPictureNumber) {
        $iPictureOffsetStop = $iPictureNumber;
    }
    $iPageFrom = $iPageIndex;

    spgm_Trace('<p>function spgm_DisplayThumbnails</p>' . "\n" . 'strPathToPictures: ' . $strPathToPictures . '<br>' . "\n" . 'iPictureNumber: ' . $iPictureNumber . '<br>' . "\n" . 'iPictureId: ' . $iPictureId . '<br>' . "\n" . 'iPictureOffsetStart: ' . $iPictureOffsetStart . '<br>' . "\n" . 'iPictureOffsetStop: ' . $iPictureOffsetStop . '<br>' . "\n" . 'iPageFrom: ' . $iPageFrom . '<br>' . "\n" . 'iPageNumber: ' . $iPageNumber . '<br>' . "\n" . 'iPageIndex: ' . $iPageIndex . '<br>' . "\n");


    // left-right orientation
    if ($spgmCfg['conf']['galleryOrientation'] == ORIENTATION_LEFTRIGHT and $iPictureId != -1) {
        echo '<table class="' . CLASS_TABLE_ORIENTATION . '">' . "\n";
        echo '<tr>' . "\n";
        echo '  <td class="' . CLASS_TD_ORIENTATION_LEFT . '">' . "\n\n";
    }


    echo '<table cellpadding="0" cellspacing="0" class="' . CLASS_TABLE_THUMBNAILS . '">' . "\n";
    echo '<tr>' . "\n";

    $iItemCounter = 0;

    for ($i = $iPictureOffsetStart; $i < $iPictureOffsetStop; $i++) {
        $strPictureFilename = $arrPictureFilenames[$i];
        $StrFileExtension = strrchr($strPictureFilename, '.');
        $strPictureBasename = substr($strPictureFilename, 0, -strlen($StrFileExtension));
        $strPictureURL = $strPathToPictures . $strPictureFilename;
        $strPictureURL2 = $strPathToPictures2 . $strPictureFilename;
        $strThumbnailFilename = PREF_THUMB . $arrPictureFilenames[$i];
        if (defined('DIR_THUMBS')) {
            $strThumbnailFilename = DIR_THUMBS . PREF_THUMB . rawurlencode($arrPictureFilenames[$i]);
            $strThumbnailFilename2 = DIR_THUMBS . PREF_THUMB . $arrPictureFilenames[$i];
        }
        $strThumbnailURL = $urlPathToPictures . $strThumbnailFilename;
        $strThumbnailURL2 = $strPathToPictures2 . $strThumbnailFilename2;
        $arrThumbnailDim = getimagesize($strThumbnailURL2);
        $iCurrentPictureIndex = $i + 1; // index that is displayed
        $strClassThumbnailThumb = CLASS_TD_THUMBNAILS_THUMB;
        $strClassImgThumbnail = CLASS_IMG_THUMBNAIL;
        if ($i == $iPictureId) {
            $strClassThumbnailThumb = CLASS_TD_THUMBNAILS_THUMB_SELECTED;
            $strClassImgThumbnail = CLASS_IMG_THUMBNAIL_SELECTED;
        }


        // new line
        if (($iItemCounter++ % $spgmCfg['conf']['thumbnailsPerRow']) == 0) {
            if ($iItemCounter > 1) {
                echo '</tr>' . "\n" . '<tr>' . "\n";
            }
        } // test for HTML 4.01 compatibility

        // TD opening for XHTML compliance when MODE_TRACE is on
        // TODO: valign=top does not work when new pictures reside amongst old ones
        echo '  <td style="vertical-align: top" class="' . $strClassThumbnailThumb . '">' . "\n";
        // ...

        if (spgm_IsNew($strPictureURL) && false === strpos($strFilterFlags, PARAM_VALUE_FILTER_NEW)) {
            if ($spgmCfg['theme']['newItemIcon'] != '') {
                $strHtmlNew = $spgmCfg['theme']['newItemIcon'] . '<br>' . "\n";
            } else {
                $strHtmlNew = '<center><span style="color: #ffd600">' . $spgmCfg['locale']['filterNew'];
                $strHtmlNew .= '</span></center>' . "\n";
            }
        } else {
            $strHtmlNew = '';
        }

        $arrPictureDim = getimagesize($strPictureURL2);

        // ...
        echo '  ' . $strHtmlNew . "\n";

        spgm_DropShadowsBeginWrap();

        $strHtmlThumbnail = '<img src="' . $strThumbnailURL . '" width="' . $arrThumbnailDim[0] . '"';
        $strHtmlThumbnail .= ' height="' . $arrThumbnailDim[1] . '" alt="' . $strThumbnailURL;
        $strHtmlThumbnail .= '" class="' . $strClassImgThumbnail . '" />';

        if ($spgmCfg['conf']['popupPictures']) {
            if (false === strpos($strFilterFlags, PARAM_VALUE_FILTER_NOTHUMBS)) {
                $strFilterFlags .= PARAM_VALUE_FILTER_NOTHUMBS;
            }

            $iWidth = $spgmCfg['conf']['popupWidth'];
            $iHeight = $spgmCfg['conf']['popupHeight'];
            $strURL = $spgmCfg['global']['documentSelf'] . '?' . PARAM_NAME_GALID . '=' . $strGalleryId . '&amp;' . PARAM_NAME_PICID . '=' . $i . '&amp;' . PARAM_NAME_FILTER . '=' . str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags) . $spgmCfg['global']['URLExtraParams'] . '#' . ANCHOR_PICTURE;

            $strJustPicture = 'false';

            if ($spgmCfg['conf']['popupFitPicture'] == true) {
                $iWidth = $arrPictureDim[0];
                $iHeight = $arrPictureDim[1];
                $strURL = $strPictureURL;
                $strJustPicture = 'true';
            }

            echo '  <a href="#?" onclick="popupPicture(\'' . $strURL . '\', ' . $iWidth . ', ' . $iHeight . ', ' . $strJustPicture . ')">';
            echo $strHtmlThumbnail;
            echo '</a>' . "\n";
        } else {
            echo '  ' . spgm_BuildLink($strHtmlThumbnail, 'yui3-pjax', ANCHOR_PICTURE, $strGalleryId, -1, $i, str_replace(PARAM_VALUE_FILTER_SLIDESHOW, '', $strFilterFlags));
        }

        spgm_DropShadowsEndWrap();

        echo '<br>' . "\n";

        // display picture extra information if wanted
        if ($spgmCfg['conf']['filenameWithThumbnails'] == true) {
            echo $strPictureBasename . '<br>';
        }
        if ($spgmCfg['conf']['pictureInfoedThumbnails'] == true) {
            $picsize = (int)(filesize($strPictureURL2) / 1024);
            echo '  [ ' . $arrPictureDim[0] . 'x' . $arrPictureDim[1] . ' - ' . $picsize . ' KB ]' . "\n";
        }

        // display caption along with the thumbnail
        if ($spgmCfg['conf']['captionedThumbnails'] == true) {
            if (isset($spgmCfg['captions'][PREF_THUMB . $strPictureFilename])) {
                echo '      <div class="' . CLASS_DIV_THUMBNAILS_CAPTION . '">';
                echo $spgmCfg['captions'][PREF_THUMB . $strPictureFilename];
                echo '</div>' . "\n";
            } elseif ($spgmCfg['conf']['pictureCaptionedThumbnails']) {
                if (isset($spgmCfg['captions'][$strPictureFilename])) {
                    echo "\n" . '  <div class="' . CLASS_DIV_THUMBNAILS_CAPTION . '">';
                    echo $spgmCfg['captions'][$strPictureFilename];
                    echo '</div>' . "\n";
                }
            }
        }

        echo '  </td>' . "\n";
    }

    // navi bar generation
    if ($iPictureNumber > 0) {
        echo '</tr>' . "\n";
        echo '<tr>' . "\n";
        echo '  <td colspan="' . $spgmCfg['conf']['thumbnailsPerRow'] . '" class="' . CLASS_TD_THUMBNAILS_NAVI . '">';
        // display "thumbnail navi" if all the thumbs are not displayed on the same page
        spgm_DisplayThumbnailNavibar($iPageIndex, $iPageNumber, $strGalleryId, $strFilterFlags);

        // toggles
        $galleryInfo = spgm_GetGalleryInfo($strGalleryId, $arrPictureFilenames);
        spgm_DisplayFilterToggles($strGalleryId, $strFilterFlags, $galleryInfo);
    }

    // for HTML 4.01 compatibility ...
    // if there are no thumbnails, then format the <td> markup correctly
    if ($iItemCounter == 0) {
        echo '  <td>' . "\n";
    }

    echo '  </td>' . "\n";
    echo '</tr>' . "\n";
    echo '</table>' . "\n";

    // left-right orientation
    if ($spgmCfg['conf']['galleryOrientation'] == ORIENTATION_LEFTRIGHT and $iPictureId != -1) {
        echo "\n" . '  </td>' . "\n";
    }
}

#############
# Main
#############

$strParamGalleryId = '';
$strParamPictureId = '';
$strParamPageIndex = '';
$strParamFilterFlags = '';

// extract URL parameters
if (ini_get('register_globals') == '1') {
    $spgmCfg['global']['documentSelf'] = '/Gallery';
    if (isset($$strVarGalleryId)) {
        $strParamGalleryId = $$strVarGalleryId;
    }
    if (isset($$strVarPictureId)) {
        $strParamPictureId = $$strVarPictureId;
    }
    if (isset($$strVarPageIndex)) {
        $strParamPageIndex = $$strVarPageIndex;
    }
    if (isset($$strVarFilterFlags)) {
        $strParamFilterFlags = $$strVarFilterFlags;
        $spgmCfg['global']['propagateFilters'] = true;
    }
} else {
    $spgmCfg['global']['documentSelf'] = '/Gallery';
    if (isset($_GET[PARAM_NAME_GALID])) {
        $strParamGalleryId = $_GET[PARAM_NAME_GALID];
    }
    if (isset($_GET[PARAM_NAME_PICID])) {
        $strParamPictureId = $_GET[PARAM_NAME_PICID];
    }
    if (isset($_GET[PARAM_NAME_PAGE])) {
        $strParamPageIndex = $_GET[PARAM_NAME_PAGE];
    }
    if (isset($_GET[PARAM_NAME_FILTER])) {
        $strParamFilterFlags = $_GET[PARAM_NAME_FILTER];
        $spgmCfg['global']['propagateFilters'] = true;
    }
    // Auto-template mode (available for register_globals = false only)
    if (isset($_GET)) {
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, strlen(PARAM_PREFIX)) != PARAM_PREFIX) {
                $spgmCfg['global']['URLExtraParams'] .= '&amp;' . $key . '=' . $value;
            }
        }
    }
}


// load external resources
spgm_LoadConfig($strParamGalleryId);
spgm_LoadPictureCaptions($strParamGalleryId);

// User filter initialization
if ($spgmCfg['conf']['filters'] != '') {
    if (!$spgmCfg['global']['propagateFilters']) {
        if (false !== strpos($spgmCfg['conf']['filters'], PARAM_VALUE_FILTER_NOTHUMBS) && false === strpos($strParamFilterFlags, PARAM_VALUE_FILTER_NOTHUMBS)) {
            $strParamFilterFlags .= PARAM_VALUE_FILTER_NOTHUMBS;
        }
        if (false !== strpos($spgmCfg['conf']['filters'], PARAM_VALUE_FILTER_NEW) && false === strpos($strParamFilterFlags, PARAM_VALUE_FILTER_NEW)) {
            $strParamFilterFlags .= PARAM_VALUE_FILTER_NEW;
        }
    }
}


echo "\n\n" . '<!-- begin table wrapper -->' . "\n";
echo '<a></a>' . "\n";
echo '<table class="' . CLASS_TABLE_WRAPPER . '">' . "\n" . ' <tr>' . "\n";

if ($strParamGalleryId == '') {
    // the gallery is not specified -> generate the gallery "tree"
    spgm_DisplayGalleryHierarchy('', 0, $strParamFilterFlags);
} else {
    echo '  <td>' . "\n";
    if ($strParamPictureId == '') {
        // we've got a gallery but no picture -> display thumbnails
        spgm_DisplayGallery($strParamGalleryId, $strParamPageIndex, $strParamFilterFlags);
    } else {
        spgm_DisplayPicture($strParamGalleryId, $strParamPictureId, $strParamFilterFlags);
    }
    echo '  </td>' . "\n";
}

echo ' </tr>' . "\n";

//display the link to SPGM website
echo ' <tr>' . "\n" . '  <td colspan="' . $spgmCfg['conf']['galleryListingCols'] . '" class="' . CLASS_TD_SPGM_LINK . '">' . "\n";
spgm_DispSPGMLink();
echo '  </td>' . "\n" . ' </tr>' . "\n";

echo '</table>' . "\n" . '<!-- end table wrapper -->' . "\n\n";
