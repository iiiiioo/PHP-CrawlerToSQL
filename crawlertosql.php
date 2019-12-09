<html>
<head>
	<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> -->
	<meta charset="utf-8">
	<title>Crawler</title>
</head>
<body>

	<?php
		$url="https://www.worldometers.info/world-population/population-by-country/";

		$conn = mysqli_connect("localhost","root","usbw","countrypopulations");

		$html = file_get_contents($url);
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		$dom->preserveWhiteSpace = false;

		$tables = $dom->getElementsByTagName('table');
		$count = 0;
		foreach($tables as $table)	{
			$tds = $table->getElementsByTagName('td');
			$i = 0;
			foreach($tds as $td){
				$i++;
				switch($i){
					case 2:
						$country_name = trim($td->nodeValue);
						break;
					case 3:
						$populations = str_replace(",", "", trim($td->nodeValue));
						$SQL = sprintf('INSERT INTO populations(country_name, population) VALUE("%s","%s")', $country_name, $populations);
						$result = mysqli_query($conn, $SQL);
						break;
					defualt:break;
				}
				if($i % 12 ==0){
					$i = 0;
					$count++;
				}
			}
			if($count == 233) break;
		}
		mysqli_close($conn);
		echo "抓了" . $count . "筆資料";
	?>
		
</body>
</html>
