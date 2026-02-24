@extends('layouts.layout')

@section('css')
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-up-to-date { background-color: #10c469; color: white; }
        .status-update-available { background-color: #f9c851; color: white; }
        .status-obsolete { background-color: #ff5b5b; color: white; }
        
        .resource-card {
            border: 1px solid #e3e7ed;
            padding: 15px;
            border-radius: 5px;
            background: #fdfdfd;
        }
        .resource-label { font-size: 12px; color: #797979; margin-bottom: 5px; }
        .resource-value { font-size: 18px; font-weight: 700; color: #333; }
        .resource-status { font-size: 11px; font-weight: 600; margin-top: 5px; display: inline-block; }
    </style>
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Maintenance</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="#">Administrasi</a></li>
                                <li><a href="#">Pengaturan</a></li>
                                <li class="active">Maintenance</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if(count($alerts) > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @foreach($alerts as $alert)
                                <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <i class="mdi mdi-alert-circle m-r-5"></i> <strong>Security Alert:</strong> {{ $alert }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-sm-4">
                        <div class="card-box resource-card">
                            <div class="resource-label"><i class="mdi mdi-harddisk text-primary m-r-5"></i> Disk Usage (Base Path)</div>
                            <div class="resource-value">{{ $resources['disk']['used'] }} <small class="text-muted" style="font-size: 12px; font-weight: normal;">of {{ $resources['disk']['total'] }}</small></div>
                            <div class="resource-status {{ $resources['disk']['status'] == 'Healthy' ? 'text-success' : 'text-danger' }}">
                                <i class="mdi mdi-checkbox-marked-circle-outline"></i> {{ $resources['disk']['status'] }} ({{ $resources['disk']['free'] }} free)
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card-box resource-card">
                            <div class="resource-label"><i class="mdi mdi-cloud text-info m-r-5"></i> Environment</div>
                            <div class="resource-value text-capitalize">{{ $resources['environment'] }}</div>
                            <div class="resource-status {{ $resources['environment'] == 'production' ? 'text-success' : 'text-warning' }}">
                                <i class="mdi mdi-information-outline"></i> Current deployment mode
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card-box resource-card">
                            <div class="resource-label"><i class="mdi mdi-bug text-danger m-r-5"></i> Debug Mode</div>
                            <div class="resource-value">{{ $resources['debug_mode'] }}</div>
                            <div class="resource-status {{ strpos($resources['debug_mode'], 'Enabled') !== false ? 'text-danger' : 'text-success' }}">
                                <i class="mdi mdi-security"></i> Security preference
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Server Information & Tech Stack Summary</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Detailed overview of the current server environment and software versions to assist with technical maintenance.
                            </p>

                            <div class="table-responsive">
                                <table class="table table-striped m-0">
                                    <thead>
                                        <tr>
                                            <th>Service / Component</th>
                                            <th>Description</th>
                                            <th>Current Version</th>
                                            <th class="text-center">Status</th>
                                            <th>Recommended / Min Version</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tech_stack as $item)
                                            <tr>
                                                <td><i class="{{ $item['icon'] }} m-r-5 {{ $item['color'] }}"></i> <b>{{ $item['service'] }}</b></td>
                                                <td>{{ $item['description'] }}</td>
                                                <td>{{ $item['current_version'] }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $status_class = 'status-up-to-date';
                                                        if (strpos($item['status'], 'Obsolete') !== false) $status_class = 'status-obsolete';
                                                        elseif (strpos($item['status'], 'Update Available') !== false) $status_class = 'status-update-available';
                                                    @endphp
                                                    <span class="status-badge {{ $status_class }}">
                                                        {{ $item['status'] }}
                                                    </span>
                                                </td>
                                                <td>{{ $item['recommended'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>PHP Configuration</b></h4>
                            <div class="table-responsive">
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>Setting</th>
                                            <th>Value</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($php_config as $config)
                                            <tr>
                                                <td><code>{{ $config['setting'] }}</code></td>
                                                <td><b>{{ $config['value'] }}</b></td>
                                                <td>{{ $config['purpose'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>PHP Extensions</b></h4>
                            <div class="table-responsive">
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>Extension Name</th>
                                            <th>Version</th>
                                            <th>Purpose</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($extensions as $ext)
                                            <tr>
                                                <td><b>{{ $ext['name'] }}</b></td>
                                                <td>{{ $ext['version'] }}</td>
                                                <td>{{ $ext['purpose'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
