<script>

    var chart_url = "";
    var token = "{{ Session::token() }}";

    window.onload = function () {

        var zone = $('#durations').val();
        var event = $('#events').val();

        var chartData = [];
        $.ajax({
            method:'GET',
            url: chart_url,
            data:{ _token: token,duration:zone,event:event }
        }).done(function(data){
            console.log(data);
            var me = [];
            var employers = [];
            var len = data.length;
            for (var i = 0; i < len; i++) {
                $eventMine = $eventSpeed = 0;
                if(data[i]['mine'])
                 $eventMine = data[i]['mine'].event;
             if(data[i]['highest'])
                 $eventSpeed = data[i]['highest'].event;
             me.push({
                label: data[i]['zone'],
                y: $eventMine
            });
             employers.push({
                label: data[i]['zone'],
                y: $eventSpeed
            });
         }


         var chart = new CanvasJS.Chart("chartContainer", {
            theme:"light2",
            animationEnabled: true,
            title:{
                    //text: "Game of Thrones Viewers of the First Airing on HBO"
                },
                axisY :{
                    includeZero: false,
                    //title: "Number of Viewers",
                    suffix: "%",
                    minimum: "0",
                    maximum: "100",
                },
                toolTip: {
                    shared: "true"
                },
                legend:{
                    cursor:"pointer",
                    itemclick : toggleDataSeries
                },
                data: [
                
                {
                    type: "line", 
                    showInLegend: true,
                    yValueFormatString: "#"/100,
                    name: "My Driver's AVG Score",
                    dataPoints: me
                },
                {
                    type: "line", 
                    showInLegend: true,
                    yValueFormatString: "#"/100,
                    name: "Highest employer's AVG Score",
                    dataPoints: employers
                }]
            });
         chart.render();

         function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }


    });
        
    }
</script>


<div class="main-dash-item col-md-3 col-xs-6 pull-right">
  <!-- small box -->
  <div class="small-box col-md-12 bg-red pull-right">
	<div class="inner">
	  <h3>{{ count($users) }}</h3>

	  <p><h4>{{trans('lang.users')}}</h4></p>
	</div>
	<div class="icon">
	  <i class="fa fa-users"></i>
	</div>
	<a href="{{route('admin-users')}}" class="small-box-footer">{{trans('lang.more')}} <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>

<div class="main-dash-item col-md-3 col-xs-6 pull-right">
  <!-- small box -->
  <div class="small-box col-md-12 bg-aqua pull-right">
  <div class="inner">
    <h3>{{ count($continents) }}</h3>

    <p><h4>{{trans('lang.continents')}}</h4></p>
  </div>
  <div class="icon">
    <i class="fa fa-globe"></i>
  </div>
  <a href="{{route('admin-continents')}}" class="small-box-footer">{{trans('lang.more')}} <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>

<div class="main-dash-item col-md-3 col-xs-6 pull-right">
  <!-- small box -->
  <div class="small-box col-md-12 bg-yellow pull-right">
  <div class="inner">
    <h3>{{ count($countries) }}</h3>

    <p><h4>{{trans('lang.countries')}}</h4></p>
  </div>
  <div class="icon">
    <i class="fa fa-flag-o"></i>
  </div>
  <a href="{{route('admin-countries')}}" class="small-box-footer">{{trans('lang.more')}} <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>

<div class="main-dash-item col-md-3 col-xs-6 pull-right">
  <!-- small box -->
  <div class="small-box col-md-12 bg-cyan pull-right">
	<div class="inner">
	  <h3>{{ count($places) }}</h3>

	  <p><h4>
      {{trans('lang.places')}}
    </h4></p>
	</div>
	<div class="icon">
	  <i class="fa fa-map-marker"></i>
	</div>
	<a href="{{route('admin-places')}}" class="small-box-footer">{{trans('lang.more')}} <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
