<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title><?=$title;?></title>
    <?= csrf_meta() ?>
    <!-- Icons-->
    <link href="<?=base_url();?>/aset/vendors/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <!-- <link href="<?=base_url();?>/aset/vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet"> -->
    <link href="<?=base_url();?>/aset/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/aset/vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main st<?=base_url();?>/aset/yles for this application-->
    <link href="<?=base_url();?>/aset/css/style.css" rel="stylesheet">
    <link href="<?=base_url();?>/aset/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    <script src="https://code.iconify.design/1/1.0.1/iconify.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

    <script src="<?=base_url();?>/aset/vendors/jquery/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <!-- ALERTIFY -->
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>

    <!-- DATATABLE -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>
    <!-- Select 2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    
    <link href="https://fonts.googleapis.com/css?family=Teko&display=swap" rel="stylesheet">

    <style type="text/css">/* Chart.js */
    @-webkit-keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}@keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}

    /* select2 fix bootstrap 4 */
    .select2-container .select2-selection--single {
        height: 34px;
        width: 100% !important;
    }
    .sidebar .nav-link.active {
        color: #2f353a;
        background: #e4e5e6;
    }

    @media only screen and (max-width: 768px) {
        .main .container-fluid {
            padding: 0px 10px !important;
        }
    }

    .url_button_main:hover {
        text-decoration: none;
    }

    .table-responsive {
        min-height: 500px;
    }

    .captcha {
    	border-top-left-radius: 5px;
    	border-bottom-left-radius: 5px;
    }

    </style>

    <script type="text/javascript">
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="X-CSRF-TOKEN"]').attr('content')
	    }
	});
	</script>

    <!-- <script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5b3ed3ef07712f00113786c2&product=inline-share-buttons' async='async'></script> -->
</head>
