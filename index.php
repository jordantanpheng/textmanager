<html>
<head>
<title>Text manager</title>
<link rel="stylesheet" href="https://unpkg.com/mustard-ui@latest/dist/css/mustard-ui.min.css">
<link rel="stylesheet" href="style.css"
</head>
<body>

<header>
<h1>Text manager</h1>
</header>
<br>

<div class="row">
    <div class="col col-sm-5">
        <div class="panel">
            <div class="panel-body">      
				<form action="index.php" method="post">
					<h2>1. Get text</h2>
					<input type="text" name="url" placeholder="Enter the poem url" value="<?php echo isset($_POST["url"])?$_POST["url"]:""?>"><br>
					<button type="submit" class="button" name="fetch">Fetch text</button>
					<h2>2. Find keywords</h2>
					<input type="text" name="keywords" placeholder="Enter text to be highlighted" value="<?php echo isset($_POST["keywords"])?$_POST["keywords"]:""?>"><br>
					<button type="submit" class="button" name="search">Search text</button>
					<h2>3. Check results</h2>
					<div class="stepper">
					<?php
					//3. Check results
					if (isset($_POST['search']) && !empty($_POST['keywords'])) {
						$keywords = explode(" ", $_POST['keywords']);
						if (!empty($_POST['url'])) {
							$url = $_POST['url'];
							if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
								$content = file_get_contents($url);
								if (!empty($content)) {
									foreach ($keywords as $keyword){
										$count = substr_count($content, $keyword);
										echo "<div class='step'>";
										echo "<p class='step-number'>$count</p>";
										echo "<p class='step-title'>";
										echo "<b>Keyword: <i>$keyword</i></b><br>";
										echo "</p>";
										for ($i = 1; $i <= $count; $i++) {
											echo "<a href=#$keyword-$i>$i </a>";
										}
										echo "</div>";
									}
								}
							}
						}			
					}		
					?>
					</div>
				</form>
            </div>
        </div>
    </div>

    <div class="col col-sm-7">
		<?php
		// 1.Get text
		if (isset($_POST['fetch']) && !empty($_POST['url'])) {
			$url = $_POST['url'];
			if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
				$content = file_get_contents($url);
				if (!empty($content)) {
					echo "<pre><code>";
					echo "$content";
					echo "</code></pre>";
				}
			}
		}
		//2. Find keywords - highlighting
		if (isset($_POST['search']) && !empty($_POST['keywords'])) {
			$keywords = explode(" ", $_POST['keywords']);
			if (!empty($_POST['url'])) {
				$url = $_POST['url'];
				if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
					$content = file_get_contents($url);
					if (!empty($content)) {
						foreach ($keywords as $keyword){
							$count = 0;
							$content = preg_replace_callback("/$keyword/", 'countCallback', $content);
						}
						echo "<pre><code>";
						echo "$content";
						echo "</code></pre>";
					}
				}
			}
		}
		function countCallback($keyword) {
			global $count;
			$count++;
			return "<span id='$keyword[0]-$count' class='highlight'>$keyword[0]</span>";
		}
		
		?>
    </div>
</div>

</body>
</html>