<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{trans('lang.site_name')}}</title>
        <link rel="stylesheet" href="{{$resource}}css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
        <link rel="stylesheet" href="{{$resource}}css/admin/login.css">

    </head>
    <body>
        <div class="container">
            <div class="form-container col-md-4 offset-md-4">
                 <form action="{{route('admin-submit-login')}}" method="post">
                     @csrf
                     <i class="icon-user-circle fa fa-user-circle"></i>
                     <div class="form-group">
                         <input type="text" class="form-control" name="email" placeholder="{{trans('lang.email')}}">
                     </div>
                     <div class="form-group">
                         <input type="password" class="form-control" name="password" placeholder="كلمة المرور">
                     </div>
                     <div class="form-group">
                         <input id="submit" class="btn btn-success btn-block" type="submit" class="form-control" value="دخول" >
                     </div>
                 </form>
            </div>
        </div>
        
    </body>
</html>