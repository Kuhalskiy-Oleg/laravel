<!DOCTYPE html >
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>
<body>

	<img src="{{ $message->embedData($file_mini_copy, $file_mini_name) }}">

	<p>Ссылка на оригинальную картинку: {{ $file_url }}</p>
	<p>Название оригинальной картинки: {{ $file_name }}</p>
	<p>Размер оиринальной картинки: {{ $file_size }} байт</p>

	<hr>

	<p>Название уменьшенной копии картинки: {{ $file_mini_name }}</p>
	<p>Размер уменьшенной копии картинки: {{ $file_mini_size }} байт</p>
		
</body>
</html>




