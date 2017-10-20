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
