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
        <p class="mb-5" style="font-style: italic">Monitor users, categories, and different types of assets. Effortlessly add, edit, or delete with ease.</p>
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
            </div>--}}
            <!-- Add more cards for other statistics if needed -->
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('categoryChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($categories->pluck('created_at')) !!},
            datasets: [{
                label: 'Total Categories',
                data: {!! $categories->map(function($item, $key) use ($categories) { return $categories->take($key + 1)->count(); }) !!},
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
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
                    title: {
                        display: true,
                        text: 'Total Categories'
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('userChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($users->pluck('created_at')) !!},
            datasets: [{
                label: 'Total Users',
                data: {!! $users->map(function($item, $key) use ($users) { return $users->take($key + 1)->count(); }) !!},
                fill: false,
                borderColor: 'rgb(255, 99, 132)',
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
                    title: {
                        display: true,
                        text: 'Total Users'
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('packageChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($packages->pluck('created_at')) !!},
            datasets: [{
                label: 'Total Packages',
                data: {!! $packages->map(function($item, $key) use ($packages) { return $packages->take($key + 1)->count(); }) !!},
                fill: false,
                borderColor: 'rgb(54, 162, 235)',
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
                    title: {
                        display: true,
                        text: 'Total Packages'
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('assetChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($assets->pluck('created_at')) !!},
            datasets: [{
                label: 'Total Assets',
                data: {!! $assets->map(function($item, $key) use ($assets) { return $assets->take($key + 1)->count(); }) !!},
                fill: false,
                borderColor: 'rgb(255, 205, 86)',
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
                    title: {
                        display: true,
                        text: 'Total Assets'
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('imageChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($images->pluck('created_at')) !!},
            datasets: [{
                label: 'Total Images',
                data: {!! $images->map(function($item, $key) use ($images) { return $images->take($key + 1)->count(); }) !!},
                fill: false,
                borderColor: 'rgb(153, 102, 255)',
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
                    title: {
                        display: true,
                        text: 'Total Images'
                    }
                }
            }
        }
    });
</script>

@endsection
