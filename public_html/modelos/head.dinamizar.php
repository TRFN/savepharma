<?php function head($ctx){ ?>
    <head>
        <!-- BASICS -->
        <title>SavePharma</title>
        
        <base href="./<?php if(isset($_REQUEST["_urldir_"])): foreach(explode("/", $_REQUEST["_urldir_"]) as $_):
            echo "../";       
        endforeach; endif; ?>" />
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <!-- FAVICONS -->
        <link rel="shortcut icon" href="img/favicon.png" type="image/png">
        <link rel="apple-touch-icon" href="img/favicon.png">

        <!-- FONTS -->
       <link href="https://fonts.googleapis.com/css2?family=Gabriela&family=Montserrat&family=Open+Sans&display=swap" rel="stylesheet">

        <!-- FONT AWESOME -->
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		
		<!-- PLUGINS -->
		<?php if($ctx->pagina_atual()!=="login"): ?>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" integrity="sha256-kH9DlfVOJaHaEYFnLxpJjpiyb3v8bctsIJpzdHJFHkk=" crossorigin="anonymous" />
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" integrity="sha256-sj3qkRTZIL8Kff5fST1TX0EF9lEmSfFgjNvuiw2CV5w=" crossorigin="anonymous" />
			<link href="vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css">
			<link href="vendors/animate.css/animate.css" rel="stylesheet" type="text/css">
			<link href="vendors/summernote/dist/summernote.css" rel="stylesheet" type="text/css">
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.10/dist/css/bootstrap-select.min.css">
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/basic.min.css" integrity="sha256-541URK12Y3BvlX2G/0tXLy9EB+9KE878nxO9NBEFisU=" crossorigin="anonymous" />
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/dropzone.min.css" integrity="sha256-NkyhTCRnLQ7iMv7F3TQWjVq25kLnjhbKEVPqGJBcCUg=" crossorigin="anonymous" />
		<?php endif; ?>
		<link href="vendors/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" type="text/css">
		
        <!-- STYLESHEETS -->
        <link rel="stylesheet" href="css/vendor.css">
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/layout.css">
        <link rel="stylesheet" href="css/components.css">
        <link rel="stylesheet" href="css/pages.css">
        <link rel="stylesheet" href="css/theme.css">

        <!-- MODERNIZR -->
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
<?php } ?>