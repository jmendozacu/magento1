/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 Created on : Dec 4, 2014, 10:48:39 AM
 Author: Tran Trong Thang
 Email: trantrongthang1207@gmai.com
 */

var ListTemContent = {}, i = 0, j = 0, k = 0;
var _path_ = "";
var UNF = "undefined";
var tpl_jsmart = {};
var listCtrlTPL = {};
var objJson = [];

//duong dan cua templ cau truc smart de load
listCtrlTPL['uploadimages'] = "http://localhost/js_function/jquery_tmpl/smart/tmpl/comments.html";

var localCache = {data: {}, remove: function (a) {
        delete localCache.data[a];
    }, exist: function (a) {
        return localCache.data.hasOwnProperty(a) && localCache.data[a] !== null;
    }, get: function (a) {
        return localCache.data[a];
    }, set: function (a, b, c, d) {
        localCache.remove(a);
        localCache.data[a] = b.responseText;
        c(d, b.responseText);
    }};
function getDatajson(i, a) {
    parent.objJson[i] = a;
}
function getTPL(a, b) {
    tpl_jsmart[a] = new jSmart(b);
}
//load file templ smart
function getTemp(c) {
    (function ($) {
        $.ajax({
            url: ListTemContent[c],
            async: false,
            type: "GET",
            cache: true,
            beforeSend: function () {
                if (localCache.exist(ListTemContent[c])) {
                    getTPL(c, localCache.get(ListTemContent[c]));
                    return false;
                }
                return true;
            }, complete: function (a, b) {
                localCache.set(ListTemContent[c], a, getTPL, c);
            }})
    })(jQuery)
}
jQuery(document).ready(function ($) {
    $.ajax({
        url: 'http://localhost/js_function/jquery_tmpl/smart/data.php',
        type: "POST",
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            $('#showpopup').click();
            //console.log(data);
            parent.objJson[k] = data;
            temp = 'uploadimages';
            if (typeof ListTemContent[temp] == UNF) {
                ListTemContent[temp] = listCtrlTPL[temp];
                getTemp(temp);
                console.log(k + "===" + ListTemContent[temp]);
            }
            str_main = tpl_jsmart[temp].fetch(parent.objJson[k]);
            $("#npgallery" + ':eq(' + k + ')').html("");
            $("#npgallery" + ':eq(' + k + ')').html(str_main);
        }
    })
})