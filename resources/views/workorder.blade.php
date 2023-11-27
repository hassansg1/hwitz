<!DOCTYPE html>
<html>
<head>
    <title>WorkOrder # {{ $data['id'] }}</title>
    <style>
    table{width:100%;}td{border:1px solid;vertical-align:top;}.property_data
	{
		width:35%;
		margin: 10px 2% 10px 0;
		border:1px solid;
		padding:5px;
		display:inline-block;
		vertical-align:top;
		height:150px;
	}
	.details_data,.history_data,.desc_data
	{
		width:28%;
		margin: 10px 2% 10px 0;
		border:1px solid;
		padding:5px;
		display:inline-block;
		vertical-align:top;
		min-height:150px !important;
	}
	.fancybox img{padding:5px}
	.history_data,.desc_data
	{
		margin-right:0%;
	}
	.headingDiv
	{
		font-weight:bold;
		border:0;
		width:auto;
		padding:5px;
		font-size:12px;
		line-height:20px;
		margin-bottom:0;
	}
	.timeAvailMain
	{
		padding-left:50px;
	}
	.timeAvail
	{
		width:45px;
		display:inline-block;
		float:left;
	}
	.desc_data
	{
		width:98%;
	}
	.col-xs-6{width:35%;float:left;padding:0 15px;}
    </style>
</head>
<body>
    <h1>Workorder # {{ $data['id'] }}</h1>
    <table>
        <tbody>
            <tr>
                <td>
                    <h4>Property</h4> 
                    {{ $data['resident'] ? $data['resident']['name'] : '' }}<br>   
                    Building name : {{ $building['name'] ? $building['name'] : '' }}<br>   
                    Building Address : {{ $unit['unit_no'] ? $unit['unit_no'] : '' }}<br>   
                    Home Telephone : {{$data['resident'] ? $data['resident']['mobile'] : ''}} <br>
                    Alt Telephone : {{ $data['alt_phone'] ?? ''}}
                </td>
                <td>
                    <h4>Details</h4>
                    Status : {{ $data['status_id']}}<br>   
                    Priority : {{ $data['priority'] }}<br>   
                    Issue Date : {{date('d/m/Y',strtotime($data['created']))}} <br>
                    Created by : {{$data['created_by'] ? $data['created_by']['name'] : ''}}
                </td>
                <td>
                    <h4>History</h4>
                    Opened: {{ date('d/m/Y', strtotime($log['open_date'])) }}<br />

                    @if ($log['due_date'] != "")
                        Due: {{ date('d/m/Y', strtotime($log['due_date'])) }}<br />
                    @else
                        Due: - <br />
                    @endif

                    @if ($log['inprocess_date'] != "")
                        InProcess: {{ date('d/m/Y', strtotime($log['inprocess_date'])) }}<br />
                    @else
                        InProcess: - <br />
                    @endif

                    @if ($log['resolved_date'] != "")
                        Resolved: {{ date('d/m/Y', strtotime($log['resolved_date'])) }}<br />
                    @else
                        Resolved: - <br />
                    @endif

                    @if ($log['closed_date'] != "")
                        Closed: {{ date('d/m/Y', strtotime($log['closed_date'])) }}<br />
                    @else
                        Closed: - <br />
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <h4>What time you will be available?</h4>
    <p>
        @if($data['time_available'])
            @foreach($data['time_available'] as $day => $time)
                <b>{{ucfirst($day)}} : </b>
                <span>{{ $time['from'] ? date('h:i a', strtotime($time['from'])) : '-'}}</span>
                -
                <span>{{$time['to'] ? date('h:i a', strtotime($time['to'])) : '-'}}</span>
                <br>
            @endforeach
        @endif
    </p>
    <br>
    <h4>How long this has been an issue?</h4>
    <p>{{$data['how_long_issue']}}</p>
    <br>
    <h4>Description</h4>
    <p>{{$data['description']}}</p>
    <br>
    <h4>Images</h4>
    @if($data['images'] && count($data['images']) > 0)
        @foreach($data['images'] as $image)
            <img src="{{$image}}" width="100" height="100" style="padding-right:5px;">
        @endforeach
    @endif
</body>
</html>
