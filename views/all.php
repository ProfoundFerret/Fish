<html>
<head>
	<title>{{ $title }}</title>
	{{ HTML::js('js/jquery') }}
	{{ HTML::js('js/std') }}
	{{ HTML::css('css/reset') }}
	{{ HTML::css('css/std') }}
	{{ HTML::css('http://fonts.googleapis.com/css?family=Ubuntu') }}
	{{ HTML::css('http://fonts.googleapis.com/css?family=Droid+Sans') }}
</head>
<body>
<div id="container">
	<div id="menu">
		<div id="title">{{ HTML::link(kSHORT_PREFIX,kSITE_NAME) }}</div>
		<div id="nav">
			{{ for $level, $menu in $nav }}
				<ul id="nav-{{$level}}">
					{{ for $url, $text in $menu }}
						<li>{{ HTML::link($url,$text) }}</li>
					{{ end }}
				</ul>
				<br>
			{{ end }}
		</div>
	</div>		

	<div id="content">{{ Fish::next() }}</div>
</div>
</body>
</html>
