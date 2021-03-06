<html>
<head>
	<title>{{ $title }}</title>

	{{ for $file in $JS }}
		{{ HTML::js($file) }}
	{{ end }}
	{{ for $file in $CSS }}
		{{ HTML::css($file) }}
	{{ end }}
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
	<div class="clear"></div>

	<div id="content">{{ Fish::next() }}</div>

	<a href="{{ kSHORT_PREFIX }}admin{{ $adminDirection }}"><div id="footer">{{ kORGANIZATION }} {{ date('Y') }}</div></a>

<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
</div>
</body>
</html>
