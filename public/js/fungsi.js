function round(number, precision) {
    var shift = function (number, precision, reverseShift) {
        if (reverseShift) {
            precision = -precision;
        }
        var numArray = ("" + number).split("e");
        return +(numArray[0] + "e" + (numArray[1] ? (+numArray[1] + precision) : precision));
    };
    return shift(Math.round(shift(number, precision, false)), precision, true);
}
function select2isi(el, id, text) {
    if ($(el).find("option[value='" + id + "']").length) {
        $(el).val(id).trigger('change');
    } else {
        var newOption = new Option(text, id, true, true);
        $(el).append(newOption).trigger('change');
    }
}
function tgl(t, v) {
    var d = new Date(t);
    d.setMonth( d.getMonth() + v );
    var h = (d.getDate().toString().length == 1 ? '0' + d.getDate() : d.getDate()) + '-' + ((d.getMonth() + 1).toString().length == 1 ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1) ) + '-' + d.getFullYear();
    return h;
}
function tglnormal(t) {
    var tgl = t.split("-");
    return tgl[2] + '-' + tgl[1] + '-' + tgl[0];
}
function request(el, crsf = null, method = null) {
    var form = $(el).serializeArray();
    var jadi = {};
    $.map(form, function(n, i){
        jadi[n['name']] = n['value'];
    });
    if (crsf != null) {
        jadi.push({_token: crsf});
    }
    if (method != null) {
        jadi.push({_method: method});
    }
    return jadi;
}
function notifikasi(notif) {
    new PNotify({
        title: notif.title,
        text: notif.text,
        type: notif.type,
        desktop: {
            desktop: true
        }
    }).get().click(function(e) {
        if ($('.ui-pnotify-closer, .ui-pnotify-sticker, .ui-pnotify-closer *, .ui-pnotify-sticker *').is(e.target)) return;
        // alert(notif.link);
    });
}

// function round(n,r){var t=function(n,r,t){t&&(r=-r);var u=(""+n).split("e");return+(u[0]+"e"+(u[1]?+u[1]+r:r))};return t(Math.round(t(n,r,!1)),r,!0)}