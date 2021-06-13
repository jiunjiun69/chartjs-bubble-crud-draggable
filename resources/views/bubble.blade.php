<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">


    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->


    <title>Chart.js Drag Data Points Plugin</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-dragdata@2.0.2/dist/chartjs-plugin-dragdata.min.js"></script>
    <!-- <script src="assets/chartjs-plugin-dragdata.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{{ asset('js/style.css') }}">
    <script src={{ asset('js/analytics.js')}}></script>
    <!-- <script src={{ asset('js/Chart.min.js')}}></script> -->
    <script src={{ asset('js/utils.js')}}></script>

    <!-- <link rel="stylesheet" type="text/css" href="./Scriptable _ Bubble _ Chart.js sample_files/style.css">
	<script async="" src="./Scriptable _ Bubble _ Chart.js sample_files/analytics.js.下載"></script>
    <script src="./Scriptable _ Bubble _ Chart.js sample_files/Chart.min.js.下載"></script>
	<script src="./Scriptable _ Bubble _ Chart.js sample_files/utils.js.下載"></script> -->

    <!-- <script src="https://cdn.jsdelivr.net/npm/@interisk-software/chartjs-plugin-selectdata"></script>
    <script src="https://unpkg.com/@interisk-software/chartjs-plugin-selectdata"></script> -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
    html,body{
      /* margin: 0;
      padding: 0; */
    }
    canvas {
      /* background-color : #eee; */
      /* position: absolute; */
      /* margin: auto;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0; */
      /* background-size: cover;　//設定背景圖片的填滿方式 */
      background-image: url({{ asset('image/map.png') }});

    }
    </style>
  </head>
  <body>
    @error('msg')
        <div class="alert alert-info text-center">{{ $message }}</div>
    @enderror

    <div class="row">
        <div class="col-2">
            <ul class="list-group">
                <li class="list-group-item active">已紀錄的圖案</li>
                @foreach ($bubbles as $bubble)
                    <li class="list-group-item">{{ $bubble->id }}: {{ json_decode($bubble->label) }}</li>
                @endforeach
            </ul>

        </div>

        <div class="col-10">

            <!-- <canvas id="chartJSContainer" style="height: 90%; width: 90%;"></canvas> -->
            <div class="content">
            <label for="select">現在選擇的是:</label><input type="text" readonly="readonly" id="select" name="select">
                <div class="wrapper"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div><canvas id="chartJSContainer" width="998" height="707" class="chartjs-render-monitor" style="display: block; width: 998; height: 707;"></canvas></div>
                    <div class="toolbar">
                        <!-- <button onclick="" href="{{ url('/chart') }}">Save</button> -->
                        <form method="POST" action="{{ route('save') }}">
                        @csrf
                            <input id="save" type="text" class="form-control @error('save') is-invalid @enderror" name="save" value="{{ old('save') }}" required autocomplete="save" autofocus style="display:none">
                            <button id="save2" type="submit" class="btn btn-primary" style="display:none">
                                <!-- {{ __('Save') }} -->
                            </button>
                        </form>
                        <button onclick="save(this)">Save</button>

                        <!-- <button onclick="load(this)">{{ $bubbles }}</button> -->
                        <!-- <button onclick="saveprint(this)">SavePrint</button> -->

                        <button onclick="randomize(this)">Randomize</button>

                        <label for="name">請輸入增加的名稱:</label>
                        <input type="text" id="name" name="name"minlength="1" maxlength="100" size="10">
                        <button onclick="addDataset(this)">Add Dataset</button>

                        <button onclick="removeDataset(this)">Remove Dataset</button>
                    </div>
                </div>
            </div>

        </div>

    </div>


    <script>
    // function ggData() {
    // 		// var data = [];
    // 		// var i;

    // 		// for (i = 0; i < DATA_COUNT; ++i) {
    // 		// 	data.push({
    // 		// 		x: utils.rand(MIN_XY, MAX_XY),
    // 		// 		y: utils.rand(MIN_XY, MAX_XY),
    // 		// 		r: 30
    // 		// 	});
    // 		// }
    //     //       console.log(data);
    //     data = {!! $bubbles !!}
    // 		return data;
    // 	}

      // function load() {
      //   data = {!! $bubbles !!}
      //   alert(data.length);
      //   for (var index = 0; index < data.length; ++index) {
      //       chart.data.datasets.push({
      //           label: '噴灌',
      //                 data: data[index],
      //                 borderWidth: 1,
      //                 backgroundColor: 'rgb(189, 80, 105, 1)',
      //                 pointHitRadius: 25
      //       });
      //       chart.update();
      //   }

      // 	// chart.data.datasets.push({
      // 	//     label: '噴灌',
      //   //           data: ggData(),
      //   //           borderWidth: 1,
      //   //           backgroundColor: 'rgb(189, 80, 105, 1)',
      //   //           pointHitRadius: 25
      // 	// });
      // 	// chart.update();
      // }
    </script>

    <script>
      // var onSelect = function(dataSelection) {
      //     console.log(dataSelection)
      // };

      // var onSelectClear = function(dataSelection) {
      //     console.log('it is clean')
      // };

      var options = {
        type: 'bubble',
        data: {
          labels: ["Red"],
          datasets: [
            {
              label: '噴灌',
              // data: {!! $bubbles !!},
              data: [],
              borderWidth: 1,
              backgroundColor: 'rgb(189, 80, 105, 1)',
              pointHitRadius: 25
            }
          ]
        },
        options: {
          // selectdata: {
          //   onSelection: onSelection,
          //   onSelectionClear: onSelectionClear
          // },
          scales: {
            y: {
                display: false,
              max: 100,
              min: 0
            },
            x: {
                display: false,
              max: 100,
              min: 0
            }
          },
          responsive: false,
          onHover: function(e) {
            const point = e.chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, false)
            if (point.length) e.native.target.style.cursor = 'grab'
            else e.native.target.style.cursor = 'default'
          },
          plugins: {
            dragData: {
              round: 2,
              dragX: true,
              showTooltip: true,
              onDragStart: function(e) {
                // console.log(e)
              },
              onDrag: function(e, datasetIndex, index, value) {
                e.target.style.cursor = 'grabbing'
                // console.log(e, datasetIndex, index, value)
                // console.log(datasetIndex)
                // console.log(document.getElementById("select").value='aa')


                // console.log({!! $bubbles !!}[datasetIndex])
                document.getElementById("select").value = JSON.stringify({!! $bubbles !!}[datasetIndex])
              },
              onDragEnd: function(e, datasetIndex, index, value) {
                e.target.style.cursor = 'default'
                // console.log(datasetIndex, index, value)
              }
            },
            // selectdata: {
            //     onSelect: onSelect,
            //     onSelectClear: onSelectClear
            // },
            // dragzone: {
            //   // Drag directions.
            //   // Allow only 'vertical', 'horizontal', 'all'
            //   direction: 'all',

            //   // Color the drag area
            //   color: 'rgba(70,146,202,0.3)',

            //   // This function calls the selected data after the drag is completed.
            //   // It is stored for each dataset.
            //   onDragSelection: function (datasets) {
            //     const datas = datasets[0];
            //     console.log('Selected data: ' + datas.length);
            //   }
            // }
          }
        }
      }




      var ctx = document.getElementById('chartJSContainer').getContext('2d');
      var chart = new Chart(ctx, options);

      var DATA_COUNT = 1;
      var MIN_XY = 0;
      var MAX_XY = 100;
      var utils = Samples.utils;
      utils.srand(110);

      // alert({!! $bubbles !!});
      chart.data.datasets.shift();
      chart.update();
    //   var temp = {!! $bubbles !!};
      // alert(temp.length);
      // alert({!! $bubbles !!});
      // foreach(temp as $bubble)
      //   // alert($bubble->name);
      // endforeach

      // temp.forEach(element => console.log(element));
      // temp.forEach(
      //   element =>
      //   chart.data.datasets.push({
      //     element
      //     })

      //   );
      // chart.update();
        // console.log(temp);

        {!! $bubbles !!}.forEach(function(value, index, array){
        // console.log(JSON.stringify(value));
        chart.data.datasets.push({
          label: JSON.parse(value.label),
          data:  JSON.parse(value.data),
          borderWidth:  JSON.parse(value.borderWidth),
          backgroundColor: JSON.parse(value.backgroundColor),
          pointHitRadius: JSON.parse(value.pointHitRadius)
        });
        chart.update();
      });


      // for (var index = 0; index < temp.length; ++index) {


      //   // chart.data.datasets.push({
      //   //     label: '噴灌',
      //   //     data: [temp[index]],
      //   //     borderWidth: 1,
      //   //     backgroundColor: 'rgb(189, 80, 105, 1)',
      //   //     pointHitRadius: 25
      //   //   });

      //   var tt = temp[index];
      //   alert(tt=>label);
      //   chart.data.datasets.push({
      //       tt
      //     });
      //   chart.update();
      // }
      // console.log({!! $bubbles !!}[0]);
      // chart.data.datasets.push({
      // 	    label: '噴灌',
      //             data: [{!! $bubbles !!}[0]],
      //             // data: generateData(),
      //             // data: [{x:15.03,y:74.58,r:10}],
      //             borderWidth: 1,
      //             backgroundColor: 'rgb(189, 80, 105, 1)',
      //             pointHitRadius: 25
      // 	});
      // chart.update();






        function colorize(opaque, context) {
        var value = context.dataset.data[context.dataIndex];
        var x = value.x / 100;
        var y = value.y / 100;
        var r = x < 0 && y < 0 ? 250 : x < 0 ? 150 : y < 0 ? 50 : 0;
        var g = x < 0 && y < 0 ? 0 : x < 0 ? 50 : y < 0 ? 150 : 250;
        var b = x < 0 && y < 0 ? 0 : x > 0 && y > 0 ? 250 : 150;
        var a = opaque ? 1 : 0.5 * value.v / 1000;

        return 'rgba(' + r + ',' + g + ',' + b + ',' + a + ')';
      }

        function generateData() {
        var data = [];
        var i;

        for (i = 0; i < DATA_COUNT; ++i) {
          data.push({
            x: utils.rand(MIN_XY, MAX_XY),
            y: utils.rand(MIN_XY, MAX_XY),
            r: 10
          });
        }
        console.log(data);
        return data;
      }

        // eslint-disable-next-line no-unused-vars
      function randomize() {
        chart.data.datasets.forEach(function(dataset) {
          dataset.data = generateData();
        });
        chart.update();
      }

      // eslint-disable-next-line no-unused-vars
      function addDataset() {
        var name = document.getElementById("name").value;
        chart.data.datasets.push({
            label: name,
                  // data: {!! $bubbles !!},
                  data: generateData(),
                  borderWidth: 1,
                  backgroundColor: 'rgb(189, 80, 105, 1)',
                  pointHitRadius: 25
        });
        chart.update();
      }

      // eslint-disable-next-line no-unused-vars
      function removeDataset() {
        // chart.data.datasets.shift();
        // chart.update();
        var select = document.getElementById("select").value;
        var yes = confirm('確定要刪除' + JSON.parse(select)['id'] + ':' + JSON.parse(JSON.parse(select)['label']) + '嗎?');
        if (yes) {
            alert('已刪除');
            chart.data.datasets[JSON.parse(select)['id']-1].data.shift();
            save();
        } else {
            alert('已取消');
        }
        // console.log(chart.data.datasets[JSON.parse(select)['id']-1].data);
        // console.log(JSON.parse(select)['label']);
      }

      function saveprint() {
          // chart.data.datasets.forEach(dataset => {
          //     console.log(chart.data.datasets.data);
          // });
          // console.log(chart.data.datasets.length);
          let array = new Array(chart.data.datasets.length)
          for (var index = 0; index < chart.data.datasets.length; ++index) {
              // data.datasets[index].data.push(Utils.bubbles({count: 1, rmin: 5, rmax: 15, min: 0, max: 100})[0]);
              array[index] = chart.data.datasets[index].data[0];
              console.log(chart.data.datasets[index].data[0]);
          }
          console.log(array);
      }

      function save() {
        // alert(JSON.stringify(chart.data.datasets));
        // console.log(JSON.stringify(chart.data.datasets));
        // let array = new Array(chart.data.datasets.length)
        // // let nameArray = new Array(chart.data.datasets.length)
        //   for (var index = 0; index < chart.data.datasets.length; ++index) {
        //       // array[index] = chart.data.datasets[index].data[0];
        //       array[index] = chart.data.datasets[index];

        //       // nameArray[index] = chart.data.datasets[index].label;
        //       // alert(JSON.stringify(chart.data.datasets[index]));
        //   }
        // console.log(array);
        document.getElementById("save").value = JSON.stringify(chart.data.datasets);
        document.getElementById("save2").click();
      }

      // chart.data.datasets.forEach((dataset) => {
      //     dataset.data = [];
      // });

      // //載入儲存資料
      // var temp = {!! $bubbles !!}
      // for (var index = 0; index < temp.length; ++index) {
      //   chart.data.datasets.push({
      //       label: '噴灌',
      //       data: temp[index],
      //       borderWidth: 1,
      //       backgroundColor: 'rgb(189, 80, 105, 1)',
      //       pointHitRadius: 25
      //     });
      // }
      // chart.update();
      // //載入儲存資料

      // chart.data.datasets.push({
      //     label: '噴灌',
      //     data: {!! $bubbles !!},
      //     borderWidth: 1,
      //     backgroundColor: 'rgb(189, 80, 105, 1)',
      //     pointHitRadius: 25
      // });
      // chart.update();


    </script>
  </body>
</html>
