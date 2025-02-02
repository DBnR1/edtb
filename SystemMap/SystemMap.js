/**
 * Javascript file
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
 * Create a unique id
 *
 * http://stackoverflow.com/questions/14044178/js-or-jquery-create-unique-span-id
 *
 * @author elclanrs
 */
function uniqId() {
    return Math.round(new Date().getTime() + (Math.random() * 100));
}

/**
 * Shuffle Array
 *
 * http://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
 *
 * @param {Array} array
 * @return {Array} array
 * @author ChristopheD
 */
function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;

    // While there remain elements to shuffle...
    while (0 !== currentIndex) {
        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array;
}

/**
 * Calculate approximate value of system
 *
 * @author Mauri Kujala <contact@edtb.xyz>
 */
function update_price() {
    var new_minvalue = "", new_maxvalue = "";

    $(".draggable").each(function() {
        new_maxvalue = new_maxvalue * 1 + $(this).data("max-value-calc") * 1;
        new_minvalue = new_minvalue * 1 + $(this).data("min-value-calc") * 1;
    });

    if (new_maxvalue !== "0" && new_maxvalue !== "") {
        $('#value').fadeIn("fast");
        $('#minval').html(numeral(new_minvalue).format("0,0") + " CR");
        $('#minvaln').html(new_minvalue);
        $('#dash').html("&ndash;");
        $('#maxval').html(numeral(new_maxvalue).format("0,0") + " CR");
        $('#maxvaln').html(new_maxvalue);
    } else {
        $('#value').fadeOut("fast");
    }
}

/**
 * Observe changes in .panzoom and update url accordingly
 *
 * @author Mauri Kujala <contact@edtb.xyz>
 */
function update_url() {
    var newurl = "", system = $('#smsys').html(), panzoom_draggable = $(".panzoom .draggable"), mlink = $('#mlink');

    if (panzoom_draggable.length) {
        panzoom_draggable.each(function() {
            var bodyid = $(this).data("bodyid"), imgid = $(this).data("imgid"), datauniqid = $(this).data("uniqid"),
                dataposleft = ($(this).position().left) / gridsize, datapostop = ($(this).position().top) / gridsize,
                divwidth = $(this).css("width").replace("px", ""), //divid = $(this).attr("id"),
                pringed = "", firstdisc = "", scanned = "", landable = "";

            if ($('#ring_' + datauniqid).is(':checked')) {
                pringed = "1";
            } else {
                pringed = "0";
            }

            if ($('#first_' + datauniqid).is(':checked')) {
                firstdisc = "1";
            } else {
                firstdisc = "0";
            }

            if ($('#scanned_' + datauniqid).is(':checked')) {
                scanned = "1";
            } else {
                scanned = "0";
            }

            if ($('#landable_' + datauniqid).is(':checked')) {
                landable = "1";
            } else {
                landable = "0";
            }

            newurl = newurl + imgid + 'i' + dataposleft + 'i' + datapostop + 'i' + divwidth + 'i' + pringed + firstdisc + scanned + landable + 'i' + bodyid + 'l';
        });

        var grid = 1, bg = 1, names = 1;

        if ($(".panzoom").css("background-image") === "none") {
            grid = 0;
        }

        if ($('#toggle_names').html() === "Show names") {
            names = 0;
        }

        if ($(".rightpanel").css("background-image") === "none") {
            bg = 0;
        }

        newurl = newurl + 'c' + grid + bg + names;

        $.ajax({
            url: "/SystemMap/add_systemMap.php?string=" + newurl + "&system=" + system,
            cache: false,
            dataType: "html",
            success: function() {
                log("Requested /SystemMap/add_systemMap.php succesfully");
            },
            error: function() {
                log("Error occured when requesting /SystemMap/add_systemMap.php");
            }
        });

        if (mlink.html() !== "") {
            $('#maplink').attr("href", "http://map.edtb.xyz?v1=" + newurl);
        } else {
            mlink.html(
                '&nbsp;&ndash;&nbsp;<a href="http://map.edtb.xyz?v1=' + newurl + '" target="_blank" id="maplink" title="View on map.edtb.xyz">View on map.edtb.xyz</a>');
        }
    } else {
        $.ajax({
            url: "/SystemMap/add_systemMap.php?string=delete&system=" + system,
            cache: false,
            dataType: "html",
            success: function() {
                log("Requested /SystemMap/add_systemMap.php succesfully");
            },
            error: function() {
                log("Error occured when requesting /SystemMap/add_systemMap.php");
            }
        });
        mlink.html("");
    }
}

/**
 * Add body
 *
 * @param {Array} options
 * @author Mauri Kujala <contact@edtb.xyz>
 */
function add_body(options) {
    /**
     * create a unique id
     */
    var uniqid = uniqId(), last_position = "", last_width = "", last_height = "", calc_val_max, calc_val_min, posleft = "",
        postop = "", append = "", last_type = $(".panzoom .draggable:last").data("type"), pz = $(".panzoom");

    /**
     * define position for the new element
     */
    if (options.pos_left === false) {
        var left_offset = "", top_offset = "", panzoom_draggable = $(".panzoom .draggable");

        if (options.width === "150") {
            left_offset = 0;
            top_offset = 0;
        } else {
            var diff = (150 - (options.width * 1)) / 2;
            left_offset = diff;
            top_offset = diff;
        }

        if (panzoom_draggable.length) {
            var panzoom_draggable_last = $(".panzoom .draggable:last");

            last_position = panzoom_draggable_last.position();
            last_width = panzoom_draggable_last.width();
            last_height = panzoom_draggable_last.height();

            if (options.type === "planet" && last_type === "star") {
                if (last_width > options.width) {
                    postop = Math.round((last_position.top + top_offset) / gridsize) * gridsize;
                } else {
                    postop = Math.round((last_position.top - top_offset) / gridsize) * gridsize;
                }
                posleft = Math.round((last_position.left + last_width + 120) / gridsize) * gridsize;
            } else if ((options.type === "planet" && last_type === "planet") || (options.type === "planet" && last_type === "other")) {
                if ($(".panzoom .draggable_img_star").length) {
                    var last_star_pos = $(".panzoom .draggable_img_star:last").parent().position();

                    postop = Math.round((last_star_pos.top + top_offset) / gridsize) * gridsize;
                    posleft = Math.round((last_position.left + last_width + 120) / gridsize) * gridsize;
                } else {
                    postop = Math.round((last_position.top) / gridsize) * gridsize;
                    posleft = Math.round((last_position.left + last_width + 120) / gridsize) * gridsize;
                }
            } else if (options.type === "star" && last_type === "star") {
                postop = Math.round((last_position.top + last_height + 80) / gridsize) * gridsize;
                posleft = Math.round((185 + left_offset) / gridsize) * gridsize;
            } else if (options.type === "star" && last_type === "planet") {
                postop = Math.round((last_position.top + last_height + 80) / gridsize) * gridsize;
                posleft = Math.round((185 + left_offset) / gridsize) * gridsize;
            } else if (options.type === "other" && last_type === "other") {
                postop = last_position.top;
                posleft = Math.round((last_position.left + last_width + 90) / gridsize) * gridsize;
            } else if (options.type === "other" && last_type === "star") {
                postop = Math.round((last_position.top + 25) / gridsize) * gridsize;
                posleft = Math.round((last_position.left + last_width + 90) / gridsize) * gridsize;
            } else {
                postop = Math.round((last_position.top) / gridsize) * gridsize;
                posleft = Math.round((last_position.left + last_width + 90) / gridsize) * gridsize;
            }
        } else {
            if (left_offset > 0) {
                if (options.width === 150) {
                    posleft = Math.round((185 + left_offset * 2) / gridsize) * gridsize;
                } else {
                    posleft = Math.round((185 + left_offset) / gridsize) * gridsize;
                }
            } else {
                posleft = Math.round(185 / gridsize) * gridsize;
            }
            postop = Math.round(125 / gridsize) * gridsize;
        }
    } else {
        posleft = options.pos_left;
        postop = options.pos_top;
    }

    /**
     * create and append div element
     */
    if (options.firstdisc === "1") {
        calc_val_max = options.max_value * 1.5;
        calc_val_min = options.min_value * 1.5;
    } else {
        calc_val_max = options.max_value;
        calc_val_min = options.min_value;
    }
    var newhtml = '<div id="id_' + uniqid + '" class="draggable resizeable" data-imgid="' + options.imgid + '"' + 'data-bodyid="' + options.bodyid + '" data-width="' + options.width + '" data-uniqid="' + uniqid + '"' + 'data-min-value-calc="' + calc_val_min + '" data-min-value="' + options.min_value + '"' + 'data-max-value="' + options.max_value + '" data-max-value-calc="' + calc_val_max + '"' + 'data-name="' + options.name + '" data-type="' + options.type + '" data-id="' + options.bid + '">' + '<img id="' + uniqid + '" class="draggable_img_' + options.type + '" src="' + options.src + '" alt="' + options.name + '">' + '<div class="name">' + options.name + '</div>' + '</div>';

    pz.append(newhtml);

    if (options.show_name === "0") {
        $('#id_' + uniqid + ' .name').hide();
    }

    /**
     * set position for new element
     */
    var uniqs = $("#id_" + uniqid), panzoom_draggable_last = $(".panzoom .draggable:last");

    panzoom_draggable_last.css("left", posleft);
    panzoom_draggable_last.css("top", postop);

    /**
     * if body type is star or planet...
     */
    if (options.type === "star" || options.type === "planet") {
        /**
         * set width, height and id
         */
        panzoom_draggable_last.css("width", options.width + "px");
        panzoom_draggable_last.css("height", "auto");
        $(".panzoom .draggable:last .draggable_img_" + options.type).prop("id", uniqid);
        panzoom_draggable_last.prop("id", "id_" + uniqid);

        /**
         * highlight images with color from image
         */
        var colors, colorThief, uniqu = $("#" + uniqid);

        if (uniqu.width() > 0) {
            colorThief = new ColorThief();
            colors = colorThief.getColor(document.getElementById(uniqid));
        } else {
            colors = [
                132,
                132,
                132
            ];
        }

        uniqs.mouseover(function() {
            uniqu.css("box-shadow", "0px 0px 20px 10px rgb(" + colors[0] + "," + colors[1] + "," + colors[2] + ")");
            uniqu.css("border-radius", "100%");
        }).mouseout(function() {
            uniqu.css("box-shadow", "none");
        });

        /**
         * append info panel
         */
        append = '<div class="addinfo" id="info_' + uniqid + '" data-source="' + options.source + '" style="display: none">' + //'<span
                                                                                                              // class="right
                                                                                                              // close"
                                                                                                              // id="close_' +
                                                                                                              // uniqid + '"><a
                                                                                                              // href="javascript:void(0)"
                                                                                                              // title="Close">'
                                                                                                              // +
            //'<img src="/style/img/close.png" alt="X" class="icon"></a></span>' +
            '<input class="scanned" id="scanned_' + uniqid + '" name="scanned" type="checkbox" value="1">' + '<div id="f_s' + uniqid + '" class="in">Scanned with ADS</div><br>' + '<input class="first" id="first_' + uniqid + '" name="first" type="checkbox" value="1">' + '<div id="f_c' + uniqid + '" class="in">First discovery</div><br>' + '<input class="ring" id="ring_' + uniqid + '" name="ring" type="checkbox" value="1">' + '<div id="f_r' + uniqid + '" class="in">Ringed</div><br>' + '<input class="landable" id="landable_' + uniqid + '" name="landable" type="checkbox" value="1">' + '<div id="f_l' + uniqid + '" class="in">Landable</div><br>' + '<input id="remove_' + uniqid + '" class="delete_body" type="button" value="Remove">' + '</div>';

        pz.append(append);

        /**
         * variables for removing bodies, rings, bonuses, etc.
         */
        var uniqfirst = $("#first_" + uniqid), uniqring = $("#ring_" + uniqid), uniqland = $("#landable_" + uniqid),
            uniqscan = $("#scanned_" + uniqid);

        /**
         * add/remove ring from body
         */
        uniqring.click(function() {
            if (uniqring.is(":checked")) {
                var width2 = $('#' + uniqid).prop('width'), ring_width = Math.ceil(1.93 * width2),
                    ring_offset = Math.ceil(0.455555 * width2), rings = [
                        "ring_1.png",
                        "ring_2.png",
                        "ring_3.png"
                    ], ring;

                shuffle(rings);
                ring = rings[0];

                uniqs.append(
                    '<img class="ring" id="ring_img_' + uniqid + '" src="' + bodies + '/' + ring + '" style="position: absolute;  top: -' + ring_offset + 'px; left: -' + ring_offset + 'px;width:' + ring_width + 'px;height: auto">');
            } else {
                $("#ring_img_" + uniqid).remove();
            }

            if (options.source === "php") {
                update_url();
            }
        });

        if (options.ringed === "1") {
            uniqring.trigger("click");
        }

        $("#f_r" + uniqid).not("#first_" + uniqid).click(function() {
            uniqring.trigger("click");
        });

        /**
         * add/remove landable icon
         */
        uniqland.click(function() {
            if (uniqland.is(":checked")) {
                var width = $('#' + uniqid).prop('width'), ringwidth = Math.ceil(1.5625 * width),
                    ringoffset = Math.ceil(0.44444444444444 * width);

                uniqs.append(
                    '<img class="landable" id="landable_img_' + uniqid + '" src="' + bodies + '/landable.png" style="position: absolute;  top: -' + ringoffset + 'px; left: -' + ringoffset + 'px;width:' + ringwidth + 'px;height: auto">');
            } else {
                $("#landable_img_" + uniqid).remove();
            }

            if (options.source === "php") {
                update_url();
            }
        });

        if (options.landable === "1") {
            uniqland.trigger("click");
        }

        $("#f_l" + uniqid).not("#first_" + uniqid).click(function() {
            uniqland.trigger("click");
        });

        /**
         * add/remove first discovery bonus
         */
        uniqfirst.click(function() {
            if (uniqscan.is(":checked")) {
                if (uniqfirst.is(":checked")) {
                    uniqs.data("min-value-calc", options.min_value * 1.5);
                    uniqs.data("max-value-calc", options.max_value * 1.5);
                } else {
                    uniqs.data("min-value-calc", options.min_value);
                    uniqs.data("max-value-calc", options.max_value);
                }
            } else {
                uniqs.data("min-value-calc", 500);
                uniqs.data("max-value-calc", 500);
            }

            if (options.source === "php") {
                update_price();
                update_url();
            }
        });

        $("#f_c" + uniqid).not("#first_" + uniqid).click(function() {
            uniqfirst.trigger("click");
        });

        if (options.firstdisc === "1") {
            uniqfirst.prop("checked", true);
            // uniqs.data("min-value-calc", options.min_value * 1.5);
            // uniqs.data("max-value-calc", options.max_value * 1.5);
            // console.log(options.min_value * 1.5);
            //update_price();
            //uniqfirst.trigger("click");
        }

        /**
         * add/remove scan bonus
         */
        uniqscan.click(function() {
            if ($('#scanned_' + uniqid).is(':checked')) {
            uniqs.data("min-value-calc", uniqs.data("min-value"));
            uniqs.data("max-value-calc", uniqs.data("max-value"));
        }
    else
        {
            uniqs.data("min-value-calc", 500);
            uniqs.data("max-value-calc", 500);
        }

        if (options.source === "php") {
            update_price();
            update_url();
        }
    }
);

if (options.scanned === "1") {
    uniqscan.trigger("click");
    uniqscan.prop("checked", true);
}

$("#f_s" + uniqid).not("#first_" + uniqid).click(function() {
    uniqscan.trigger("click");
});

/**
 * close info screen
 */
$("#close_" + uniqid).click(function() {
    $("#info_" + uniqid).hide();
});
}
/**
 * if type is something else...
 */
else
{
    /**
     * set width, height and id
     */
    panzoom_draggable_last.css("width", options.width + "px");
    panzoom_draggable_last.css("height", "auto");
    $(".panzoom .draggable:last .draggable_img_other").prop("id", uniqid);
    panzoom_draggable_last.prop("id", "id_" + uniqid);

    /**
     * append info panel
     */
    append = '<div class="addinfo" id="info_' + uniqid + '" style="display: none">' + '<input id="remove_' + uniqid + '" class="button" type="button" value="Remove">' + '</div>';

    pz.append(append);
}

var info_uniq = $("#info_" + uniqid);
/**
 * remove body
 */
$("#remove_" + uniqid).click(function() {
    uniqs.data("min-value-calc", 0);
    uniqs.data("max-value-calc", 0);

    uniqs.remove();
    info_uniq.remove();

    update_url();
    update_price();
});

/**
 * show info panel
 */
uniqs.click(function() {
    if ($(this).hasClass("noclick")) {
        $(this).removeClass("noclick");
    } else {
        if (info_uniq.is(":hidden")) {
            var posLeft = $(this).position().left + $(this).width() - 20, posTop = $(this).position().top + $(this).height() - 20;

            info_uniq.fadeToggle("fast");
            info_uniq.css("left", posLeft);

            info_uniq.css("top", posTop);
        }
    }
});

/**
 * start resizeable and draggable element
 */
$(function() {
    $(".resizeable").resizable({
        resize: function(e, ui) {
            var ui_elem = ui.element[0], landable_elem = $('#' + ui_elem.id + ' .landable'),
                ring_elem = $('#' + ui_elem.id + ' .ring'), resizeable_elem = $(".resizeable");

            if (landable_elem.length) {
                var og_width = ui_elem.clientWidth, new_ringwidth = Math.ceil(1.5625 * og_width),
                    new_ringoffset = Math.ceil(0.44444444444444 * og_width);

                landable_elem.css("top", "-" + new_ringoffset + "px");
                landable_elem.css("left", "-" + new_ringoffset + "px");
                landable_elem.css("width", +new_ringwidth + "px");
            }

            if (ring_elem.length) {
                var og_width2 = ui_elem.clientWidth, new_ring_width = Math.ceil(1.93 * og_width2),
                    new_ring_offset = Math.ceil(0.455555 * og_width2);

                ring_elem.css("top", "-" + new_ring_offset + "px");
                ring_elem.css("left", "-" + new_ring_offset + "px");
                ring_elem.css("width", +new_ring_width + "px");
            }

            var imgheight = $('#' + ui_elem.id + ' img').height();

            if (imgheight === "190") {
                resizeable_elem.resizable("option", "maxHeight", ui_elem.clientHeight);
                resizeable_elem.resizable("option", "maxWidth", ui_elem.clientWidth);
            }
        },
        stop: function() {
            update_url();
        },
        containment: ".panzoom",
        aspectRatio: true,
        autoHide: true
    });

    $(".draggable").draggable({
        start: function() {
            $(this).addClass("noclick");
        },
        stop: function() {
            update_url();
        },
        grid: [
            gridsize,
            gridsize
        ]
    });
});

/**
 * stop panning if dragging elements
 */
$(".panzoom div").not(".ui-resizable-handle").on("mousedown touchstart", function(e) {
    e.stopPropagation();
});

update_price();

if (options.do_update === true) {
    update_url();
}
options.source = "php";
}
