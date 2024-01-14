<!DOCTYPE html>
<html lang="">
<head>
    <title>Cipher Key Export</title>
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
            font-size: 24px;
            margin-top: 20px;
            border-bottom: 2px solid #334f5b;
            padding-bottom: 5px;
        }

        .image-container {
            margin-bottom: 15px;
        }

        .image-card {
            page-break-inside: avoid;
            border: 2px solid #334f5b;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .image {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .instructions {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="thumb">
            <img src="{{isset($images->toArray(null)[0])?$images->toArray(null)[0]['url']['large']:null}}" alt="thumb"/>
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
            <td>Used To</td>
            <td>{{Carbon\Carbon::parse($used_to)->format('Y-m-d') }}</td>
        </tr>

        <tr>
            <td>Used From</td>
            <td>{{Carbon\Carbon::parse($used_from)->format('Y-m-d') }}</td>
        </tr>

        <tr>
            <td>Used Around</td>
            <td>{{$used_around}}</td>
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
            <td>Complete Structure</td>
            <td>{{$complete_structure}}</td>
        </tr>
        <tr>
            <td>Used chars</td>
            <td>{{$used_chars}}</td>
        </tr>
        <tr>
            <td>Key type</td>
            <td>{{$key_type['name']}}</td>
        </tr>
        <tr>
            <td>Main Users</td>
            <td>
                {{ implode(', ', $users->filter(function ($item) {
                    return $item['is_main_user'];
                })->map(function ($item) {
                    return $item['person']['name'];
                })->values()->toArray()) }}
            </td>
        </tr>
        <tr>
            <td>Other Users</td>
            <td>
                {{ implode(', ', $users->filter(function ($item) {
                    return !$item['is_main_user'];
                })->map(function ($item) {
                    return $item['person']['name'];
                })->values()->toArray()) }}
            </td>
        </tr>
        </tbody>
    </table>

    <h1 class="attachments-header">Attachments</h1>

    <div class="image-container">
        @foreach($images->toArray(null) as $image)
            <div class="image-card">
                <div>
                    <img src="{{ $image['url']['large'] }}" alt="" class="image">
                    <p class="instructions">Has Instructions: {{ $image['has_instructions'] ? 'true' : 'false' }}</p>
                    <p>Structure: {{ $image['structure'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
