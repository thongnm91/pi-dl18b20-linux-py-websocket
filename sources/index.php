<?php
	error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Include database connection
    require_once 'db_connection.php';

    //----------------------------------
	
?><html>
  <head>
    <title><?php echo exec('hostname'); ?></title>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript">

		function check(){
			$.get( "read_temperature.php", function( data ) {
				  $( "#result" ).html( data );
				  
				});
		}
		
	</script>
	<script type="text/javascript">
	<!--
		setInterval( "check()" ,1000);
	//-->
	</script>
    
    
  </head>
  <body>

  <div id="result"></div>

  </body>
</html>