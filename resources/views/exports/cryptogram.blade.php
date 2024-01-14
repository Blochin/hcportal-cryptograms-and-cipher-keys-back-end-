<!DOCTYPE html>
<html lang="">
<head>
    <title>Cryptogram Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
        }

        .header {
            overflow: hidden;
        }

        .thumb {
            float: left;
            width: 50%;
        }

        .description {
            float: left;
            width: 50%;
        }

        .clear {
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #334f5b;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #334f5b;
            color: #ececec;
        }

        .attachments-header {
            page-break-before: always;
            font-size: 24px;
            margin-top: 20px;
            border-bottom: 2px solid #334f5b;
            padding-bottom: 5px;
        }

        .attachment-description{
            border-bottom: 1px solid #334f5b;
        }

        .attachment-group {
            border: 2px solid #334f5b;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .attachment-item {
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        .attachment-title {
            font-weight: bold;
        }

        .attachment-image {
            width: 100%;
            margin-top: 10px;
        }

        .attachment-link {
            color: #007bff;
            text-decoration: none;
        }

        .attachment-text {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="thumb">
            <img src="{{ $thumb }}" alt="thumb"/>
        </div>
        <div class="description">
            <div style="padding-left: 5px">
                <h1>{{ $name }}</h1>
                <p>Created At: {{$created_at}}</p>
                {!! $description !!}
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <table border="1">
        <thead>
        <tr>
            <th>Name</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Category</td>
            <td>{{isset($sub_category['name']) ? $sub_category['name']: $category['name']}}</td>
        </tr>
        <tr>
            <td>Sub Category</td>
            <td>{{isset($sub_category['name']) ? $category['name']: null}}</td>
        </tr>
        <tr>
            <td>Date</td>
            <td>{{Carbon\Carbon::parse($date)->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td>Date Around</td>
            <td>{{$date_around}}</td>
        </tr>
        <tr>
            <td>Language</td>
            <td>{{$language['name']}}</td>
        </tr>
        <tr>
            <td>Continent</td>
            <td>{{$location['continent']}}</td>
        </tr>
        <tr>
            <td>Used chars</td>
            <td>{{$used_chars}}</td>
        </tr>
        <tr>
            <td>Location</td>
            <td>{{$location['name']}}</td>
        </tr>
        <tr>
            <td>Availability</td>
            <td>{{$availability}}</td>
        </tr>
        <tr>
            <td>Archive</td>
            <td>{{isset($folder) ?$folder['fond']['archive']['name'] : null}}</td>
        </tr>
        <tr>
            <td>Fond</td>
            <td>{{ isset($folder) ? $folder['fond']['name'] : null}}</td>
        </tr>
        <tr>
            <td>Folder</td>
            <td>{{isset($folder) ? $folder['name'] : null}}</td>
        </tr>
        <tr>
            <td>Sender</td>
            <td>{{$sender['name']}}</td>
        </tr>
        <tr>
            <td>Recipient</td>
            <td>{{$recipient['name']}}</td>
        </tr>
        </tbody>
    </table>

    <h1 class="attachments-header">Attachments</h1>

    @foreach($datagroups as $datagroup)
        <div class="attachment-group">
            <div>
                <h3 class="attachment-description">Description: {{$datagroup['description']}}</h3>
            </div>
            <div class="border attachment-item">
                @foreach($datagroup['data'] as $data)
                    <div class="attachment-item">
                        <div class="attachment-title">Title: {{$data['title']}}</div>
                        @if(isset($data['image']))
                            <img class="attachment-image" src="{{$data->image}}" alt="">
                        @endif
                        @if($data['filetype']=='link')
                            <a class="attachment-link" href="{{$data['blob']}}">{{$data['blob']}}</a>
                        @endif
                        @if($data['filetype']=='text')
                            <div class="attachment-text">{{$data['blob']}}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
</body>
</html>
