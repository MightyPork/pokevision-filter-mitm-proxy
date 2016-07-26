<?php

// This somehow works. Better don't touch it or it'll stop working ^^

function get_data($url)
{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

	$response = curl_exec($ch);

	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);

	curl_close($ch);

	return [$body, get_headers_from_curl_response($header)];
}

function get_headers_from_curl_response($response)
{
	$headers = array();
	$header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
	foreach (explode("\r\n", $header_text) as $i => $line) {
		if ($i === 0) {
			//
		} else {
			list ($key, $value) = explode(': ', $line);

			$headers[$key] = $value;
		}
	}
	return $headers;
}

list($data, $headers) = get_data('https://pokevision.com/');

foreach ($headers as $name => $value) {
	if ($name == 'Transfer-Encoding') continue;
	header("$name: $value");
}

// Fix links
$data = str_replace('href="/asset', 'href="https://pokevision.com/asset', $data);
$data = str_replace('src="/asset', 'src="https://pokevision.com/asset', $data);
$data = str_replace('https://www.paypalobjects.com/en_US/i/scr/pixel.gif', '', $data);

// Remove crap
$data = preg_replace('/<script async id="factorem".*?>/', '<!-- REMOVED ADS LINK -->', $data);
$data = preg_replace('/<iframe.*?<\/iframe>/', '<!-- REMOVED IFRAME -->', $data);
$data = preg_replace('/\(function\(i,s,o,g,r,a,m\).*?pageview\'\);/s', '/* REMOVED GA */', $data);

// Inject our scripts
$inject = '';
$inject .= "<link rel=stylesheet href=/pvf/injected.css>\n";
$inject .= "<link rel=stylesheet href=/pvf/glyphicons.css>\n";
$inject .= "<link rel=stylesheet href=/pvf/patch.css>\n";
$inject .= "<script src=/pvf/patch.js></script>\n";
$inject .= "<script src=/pvf/lib/lodash.min.js></script>\n";
$inject .= "<script src=/pvf/resources/dex.js></script>\n";
$inject .= "<script src=/pvf/resources/names.js></script>\n";
$inject .= "<script src=/pvf/injected.js></script>\n";
$data = str_replace('<meta name="twitter:card" content="summary">', $inject, $data);

echo $data;

echo "<!-- Page served by @MightyPork's PokéVision MiTM proxy -->";
