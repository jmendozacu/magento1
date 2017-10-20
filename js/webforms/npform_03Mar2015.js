/*
  Created on : Jan 7, 2015, 9:24:34 AM
  Author: Tran Trong Thang
  Email: trantrongthang1207@gmai.com
 */

jQuery(document).ready(function ($) {
    $(".fgcfreevisual img").click(function () {
        $(".fgcfreevisualform").css({
            top: 0,
            left: "40%"
        });
    });
    $(".fgctitleform > span").click(function () {
        $(".fgcfreevisualform").css({
            top: -10000 + "px",
            left: -10000 + "px"
        });
    });
    $(".fgcfreevisualform h2.legend").text("Help us to stop spammers:");
    
    $(".fgcfreequote img").click(function () {
        $(".fgcfreesalesforce").css({
            top: 0,
            left: "40%"
        });
    });
    $(".fgcfreesalesforce .fgctitleform > span").click(function () {
        $(".fgcfreesalesforce").css({
            top: -10000 + "px",
            left: -10000 + "px"
        });
    });
    $(".fgcfreesalesforce h2.legend").text("Help us to stop spammers:");

})