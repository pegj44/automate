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
                <form id="form1" action="functions.php" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">Scraper</div>
                        <div class="panel-body">
                            <p>
                                <input type="text" placeholder="URL" id="site_url" name="site_url" class="form-control">
                            </p>
                            <p><input type="number" placeholder="Number of Pages" id="total_page" name="total_page" class="form-control" min="1" step="1"></p>
                     
                        </div>
                        <div class="panel-footer clearfix" style="text-align:center;">
                            <button type="submit" class="btn-start btn btn-primary fright">Start</button>
                        </div>                       
                    </div>
                </form>
                <p class="status"></p>
            </div>

			<table id="result" class="table data-list">
				<thead>
					<th>Title</th>
					<th>Subtitle</th>
                    <th>Description</th>
                    <th>Curriculum</th>
				</thead>
				<tbody>
					
				</tbody>
			</table>
 
    	</div>
    
        <script type="text/javascript">

            var curr_page = 1, shop_items;

            jQuery('#form1').submit(function(e) {

                start();
                jQuery('.btn-start, .form-control').prop('disabled', true);

                e.preventDefault();
            });

            function start() {

                var xhr = jQuery.ajax({
                    url: 'functions.php',
                    data: {
                        site_url : jQuery('#site_url').val(),
                        total_page : jQuery('#total_page').val(),
                        curr_page : curr_page,
                        shop_items : shop_items
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function(event) {

                    },
                    success: function(data) {
                        console.log('success');
                        console.log(data);

                        curr_page = data.curr_page;
                        shop_items = data.shop_items;
                        jQuery('#total_page').val(data.total_page);

                        // if( !jQuery.isEmptyObject(data.details) ) {
                        //     jQuery('#result tbody').append('<tr><td>'+ data.details.item_title +'</td><td>'+ data.details.desc +'</td><td>'+ data.details.sale_price +'</td><td>'+ data.details.price +'</td></tr>');
                        // }
                        
                        if( !jQuery.isEmptyObject(data.details) ) {
                            jQuery('#result tbody').append('<tr><td>'+ data.details.item_title +'</td><td>'+ data.details.subtitle +'</td><td>'+ data.details.desc +'</td><td>'+ data.details.curri +'</td></tr>');
                        }

                        jQuery('.status').html(data.scrap_status);   

                        if( data.scrap_status !== 'Complete' ) {
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
