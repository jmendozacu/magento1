<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript">
            var urlajax = 'read_file_product.php';
            var countrecord = [0, 0];
            var numajax = 0;
            var ajaxalway = {
                goes: function(urlajax) {
                    if (window.timeout)
                        clearTimeout(window.timeout);
                    (function($) {
                        $.ajax({
                            url: urlajax,
                            type: "POST",
                            dataType: 'json',
                            success: function(response) {
                                if (numajax == 0)
                                    countrecord[1] = response.countLine;
                                if (response.countLine == countrecord[1]) {
                                    countrecord[0] = countrecord[0] + 1;
                                } else {
                                    countrecord[1] = response.countLine;
                                    countrecord[0] = 0;
                                }
                                if (response.text != 'undefined') {
                                    $("#responseajax").html("<div>" + response.text + "</div>");
                                }
                                if ($.trim(response.lastProduct) == 'true' || countrecord[0] == 500) {
                                    alert('Import success!')
                                    $("#fgcloading").hide();
                                } else {
                                    window.timeout = window.setTimeout(function() {
                                        ajaxalway.goes(urlajax);
                                    }, 2000);
                                }
                                numajax = numajax + 1;
                                console.log(response, countrecord[0]);
                            }
                        });
                    })(jQuery);
                }
            }
            ajaxalway.goes(urlajax);
        </script>
        <style type="text/css">
            #fgcloading{
                display: block;
                position: fixed;
                width: 100%;
                top: 100px;
                left: 45%;
            }

            .productid {
                font-weight: bold;
            }
            .fgcchild {
                margin-left: 40px;
            }
            .imageerror{
                color: red;
            }
            .noimage{
                color: #FEA110;
            }
            .existsimage{
                color: #0B4CB1;
            }
            .imagename{
                color: #4CB10B;
            }
        </style>
    </head>
    <body>
        <div id="fgcloading">
            <img src="js/spinner.gif"/>
        </div>
        <div id="responseajax"></div>
    </body>
</html>
