@extends('layouts.app')
@section('content')
    <div class="relative ">

        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

        <script src="
        https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js
        "></script>

        </head>

        <body>

            <div>
                <a href="{{ url('admin/hive') }}"
                    class="inline-block px-2 py-2 rounded-sm text-white bg-blue-700 hover:bg-blue-500">Back</a>
            </div>

            {{-- data nav bar goes here --}}
            @include('datanavbar')

        </body>




        <!-- Display the temperature graph -->
        <script type="text/javascript">
            $(function() {

                var start = moment().subtract(1, 'days'); //by default , just display data for the last 30 days
                var end = moment();
                var hiveId = {{ $hive_id }};
                var myChart = echarts.init(document.getElementById('chart'));

                function cb(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

                    // Format dates for the server
                    var startDate = start.format('YYYY-MM-DD HH:mm:ss');
                    var endDate = end.format('YYYY-MM-DD HH:mm:ss');


                    // Send AJAX request to server
                    $.ajax({
                        url: '/hive_data/weight_data/' + hiveId,
                        method: 'GET',
                        data: {
                            start: startDate,
                            end: endDate,
                            table: 'hive_weights' // name of the table you want to fetch data from
                        },
                        success: function(response) {
                            // handle the response data
                            myChart.setOption({
                                // chart configuration options here
                                title: {
                                    text: 'Weight'
                                },
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {},
                                toolbox: {
                                    show: true,
                                    feature: {
                                        dataZoom: {
                                            show: false,
                                            yAxisIndex: 'none'
                                        },
                                        dataView: {
                                            show: false,
                                            readOnly: false
                                        },
                                        magicType: {
                                            show: false,
                                            type: ['line', 'bar']
                                        },
                                        restore: {
                                            show: false
                                        },
                                        saveAsImage: {
                                            show: false
                                        }
                                    }
                                },
                                xAxis: {
                                    type: 'category',
                                    boundaryGap: false,
                                    data: response
                                        .dates // assuming you returned the dates under the key 'dates'
                                },
                                yAxis: {
                                    type: 'value',
                                    min: 10,
                                    max: 40,
                                    axisLabel: {
                                        formatter: '{value} kg'
                                    },
                                    splitNumber: 10
                                },
                                series: [
                                    //   {
                                    //         name: 'Brood Section',
                                    //         type: 'line',
                                    //         data: response.broodSection,
                                    //         markPoint: {
                                    //                     data: [
                                    //                           { type: 'max', name: 'Max' },
                                    //                           { type: 'min', name: 'Min' }
                                    //                           ]
                                    //                     },
                                    //         markLine: {
                                    //                     data: [{ type: 'average', name: 'Avg' }]
                                    //                    },
                                    //                   //  color: 'red' 
                                    //       },
                                    //     {
                                    //         name: 'Exterior',
                                    //         type: 'line',
                                    //         data: response.exterior,
                                    //         markPoint: {
                                    //                     data: [
                                    //                           { type: 'max', name: 'Max' },
                                    //                           { type: 'min', name: 'Min' }
                                    //                           ]
                                    //                     },
                                    //         markLine: {
                                    //                     data: [{ type: 'average', name: 'Avg' }]
                                    //                    },

                                    //         // color: 'green' 

                                    //     },

                                    {
                                        name: 'Overall',
                                        type: 'line',
                                        data: response.overall,
                                        markPoint: {
                                            data: [{
                                                    type: 'max',
                                                    name: 'Max'
                                                },
                                                {
                                                    type: 'min',
                                                    name: 'Min'
                                                }
                                            ]
                                        },
                                        markLine: {
                                            data: [{
                                                type: 'average',
                                                name: 'Avg'
                                            }]
                                        },
                                        // color: 'blue' 
                                    },
                                ]
                            });
                        }

                    });
                }

                $('#reportrange').daterangepicker({
                    ranges: {
                        'Today': [moment().startOf('day'), moment().endOf('day')],
                        'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days')
                            .endOf('day')
                        ],
                        'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    }
                }, cb);

                cb(start, end);
            });
        </script>


        <div class='bg-white mx-3 p-1 mt-2'>
            <div id="chart" style="width: 100%; height: 480px;" class='p-2'></div>
            <script>
                // JavaScript code to create and configure the chart
                var myChart = echarts.init(document.getElementById('chart'));
            </script>
            
        </div>


    </div>
@endsection
