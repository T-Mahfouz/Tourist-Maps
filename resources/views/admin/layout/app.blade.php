<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="images/png" href="{{ $resource }}images/design/wolfl.png"/>
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{$resource}}css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
        <link rel="stylesheet" href="{{$resource}}css/admin/admin.css">
		<script type="text/javascript" charset="utf-8" src="{{$resource}}js/admin/jquery-3.3.1.min.js"></script>
    </head>
    <body>
        <div class="container-fluid" id="body">
			<div class="header col-md-12">
				@yield('header')
			</div>
		
		
			<div class="content col-md-12">
				<div class="main col-md-10 pull-left">
					<main>
						@yield('main')
					</main>
				</div>
				<div class="sidebar bg-gray col-md-2 pull-right">
					@yield('sidebar')
				</div>
			</div>
			<div class="clearfix"></div>

			
			<div class="footer col-md-12">
				@yield('footer')
			</div>
		</div>	
        
        
    </body>
</html>