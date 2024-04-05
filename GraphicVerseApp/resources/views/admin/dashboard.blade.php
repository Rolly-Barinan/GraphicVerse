@extends('layouts.adminlayout')

@section('admin-content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@^3"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@^2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>

    <style>
        /* Custom CSS for Dashboard */
        .dashboard-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .dashboard-card p {
            font-size: 24px;
            font-weight: bold;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="container mt-5">
        <div class="dashboard-container" style="border-left: 25px solid #333;">
            <h2 class="text-center mb-4">ADMIN DASHBOARD</h2>
            <hr>
            <h4>Welcome!</h4>
            <p class="mb-5" style="font-style: italic">Monitor users, categories, and different types of assets.
                Effortlessly add, edit, or delete with ease.</p>
            <div class="row justify-content-center"> <!-- Modified: justify-content-center added -->
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Categories</h3>
                        <p>{{ $categories->count() }}</p>
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Users</h3>
                        <p>{{ $users->count() }}</p>
                        <canvas id="userChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Packages</h3>
                        <p>{{ $packages->count() }}</p>
                        <canvas id="packageChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Assets</h3>
                        <p>{{ $assets->count() }}</p>
                        <canvas id="assetChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Images</h3>
                        <p>{{ $images->count() }}</p>
                        <canvas id="imageChart"></canvas>
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Audios</h3>
                        <p>{{ $packages->where('asset_type_id', 1)->count() }}</p>
                    </div>
                </div> --}}
                <!-- Add more cards for other statistics if needed -->
            </div>
            <div class="col-md-12">
                <div class="dashboard-card" style="border-left: 10px solid #333;">
                    <h3>Total Statistics</h3>
                    <canvas id="combinedChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to generate labels and data arrays without zero counts for missing dates
        function generateChartData(data) {
            var currentDate = moment(data[0]);
            var endDate = moment().endOf('day'); // Assuming current date as end date
            var labels = [currentDate.format('YYYY-MM-DD')];
            var chartData = [];

            while (currentDate.isSameOrBefore(endDate)) {
                var count = data.filter(function(item) {
                    return moment(item).isSame(currentDate, 'hour'); // Check for hour-level granularity
                }).length;

                // Only include non-zero counts
                if (count > 0) {
                    labels.push(currentDate.format()); // Include both date and time
                    chartData.push(count);
                }

                currentDate.add(1, 'hour'); // Increment by one hour
            }

            return {
                labels: labels,
                data: chartData
            };
        }


        // Function to create chart
        function createChart(chartId, chartData, label, borderColor) {
            new Chart(document.getElementById(chartId), {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: label,
                        data: chartData.data,
                        fill: true,
                        borderColor: borderColor,
                        borderWidth: 2,
                        pointStyle: 'circle', // Set point style to 'circle'
                        pointRadius: 5, // Adjust point radius as needed
                        pointBackgroundColor: borderColor,
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                minUnit: 'day',
                            },
                            ticks: {
                                autoSkip: true,
                                maxTicksLimit: 20
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: label
                            }
                        }
                    }
                }
            });
        }

        // Generate chart data for categories, users, packages, assets, and images
        var categoryChartData = generateChartData({!! json_encode($categories->pluck('created_at')) !!});
        var userChartData = generateChartData({!! json_encode($users->pluck('created_at')) !!});
        var packageChartData = generateChartData({!! json_encode($packages->pluck('created_at')) !!});
        var assetChartData = generateChartData({!! json_encode($assets->pluck('created_at')) !!});
        var imageChartData = generateChartData({!! json_encode($images->pluck('created_at')) !!});

        // Create charts
        createChart('categoryChart', categoryChartData, 'Total Categories', 'rgb(75, 192, 192)');
        createChart('userChart', userChartData, 'Total Users', 'rgb(255, 99, 132)');
        createChart('packageChart', packageChartData, 'Total Packages', 'rgb(54, 162, 235)');
        createChart('assetChart', assetChartData, 'Total Assets', 'rgb(255, 205, 86)');
        createChart('imageChart', imageChartData, 'Total Images', 'rgb(153, 102, 255)');

        // Generate chart data for all totals
        var combinedChartData = {
            categories: generateChartData({!! json_encode($categories->pluck('created_at')) !!}),
            users: generateChartData({!! json_encode($users->pluck('created_at')) !!}),
            packages: generateChartData({!! json_encode($packages->pluck('created_at')) !!}),
            assets: generateChartData({!! json_encode($assets->pluck('created_at')) !!}),
            images: generateChartData({!! json_encode($images->pluck('created_at')) !!})
        };

        // Create combined chart
        var ctx = document.getElementById('combinedChart').getContext('2d');
        var combinedChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: combinedChartData.categories.labels,
                datasets: [{
                        label: 'Total Categories',
                        data: combinedChartData.categories.data,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)', // Adjust as needed
                        borderWidth: 1
                    },
                    {
                        label: 'Total Users',
                        data: combinedChartData.users.data,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)', // Adjust as needed
                        borderWidth: 1
                    },
                    {
                        label: 'Total Packages',
                        data: combinedChartData.packages.data,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)', // Adjust as needed
                        borderWidth: 1
                    },
                    {
                        label: 'Total Assets',
                        data: combinedChartData.assets.data,
                        backgroundColor: 'rgba(255, 205, 86, 0.5)', // Adjust as needed
                        borderWidth: 1
                    },
                    {
                        label: 'Total Images',
                        data: combinedChartData.images.data,
                        backgroundColor: 'rgba(153, 102, 255, 0.5)', // Adjust as needed
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            minUnit: 'day',
                        },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 20
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Count'
                        }
                    }
                }
            }
        });
    </script>
@endsection
