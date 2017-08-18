<?php 

include 'functions.php';

?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
        <style type="text/css">

            #controller {
			    float: none;
			    margin: 50px auto;
			    border-bottom: 2px solid #dcdcdc;
            }
            .col_half {
                width: 48%;
                margin-right: 4%;
                float: left;
            }     
            .col_last {
                margin-right: 0;
            }
            .fleft {
                float: left;
            }
            .fright {
                float: right;
            }
        </style>
    </head>
    <body>

    	<div class="container">
            <div id="controller" class="col-md-5">
                <form id="form1" action="do_scrape.php" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">Scraper</div>
                        <div class="panel-body">

                            <?php form_fields(); ?>

                        </div>
                        <div class="panel-footer clearfix" style="text-align:center;">
                            <button type="submit" class="btn-start btn btn-primary fright">Start</button>
                        </div>                       
                    </div>
                </form>
                <p class="status"></p>
            </div>

            <?php scrape_table(); ?>
 
    	</div>
    
        <script type="text/javascript">

            var pageNext = 0;

            jQuery('#form1').submit(function(e) {

                start();
                jQuery('.btn-start, .form-control').prop('disabled', true);

                e.preventDefault();
            });

            function start() {

                var xhr = jQuery.ajax({
                    url: 'do_scrape.php',
                    data: {
                        site_url : jQuery('input[name="site_url"]').val(),
                        page_start : jQuery('input[name="page_start"]').val(),
                        page_end : jQuery('input[name="page_end"]').val(),
                        pageNext : pageNext
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function(event) {

                    },
                    success: function(data) {

                        var html = '';

                        if( !jQuery.isEmptyObject(data.details) && data.details !== 'Error' ) {
                            pageNext = parseInt(data.pageNext) + 1;
                            console.log(data);                            

                            for (var i = 0, len = data.details.length - 1; i <= len; i++) {

                                html += '<tr>';

                                for (var j = 0, item = data.details[i].length -1; j <= item; j++) {
                                    html += '<td>'+ data.details[i][j].data_value +'</td>';                                                          
                                }     

                                html += '</tr>';
                            }

                            jQuery('#result tbody').append(html);
                        }
                        
                        jQuery('.status').html(data.scrap_status);   

                        if( data.scrap_status !== 'Complete' ) {
                            jQuery('.btn-start, .form-control').prop('disabled', false);
                            start();                     
                        }                        
                    },  
                    error: function(xhr) {
                        console.log('error');
                        console.log(xhr);
                    }

                });
            }


        </script>

    </body>
</html>
