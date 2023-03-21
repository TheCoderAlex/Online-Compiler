<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Online Compiler Alpha</title>
	<link rel="stylesheet" href="./CSS/Style.css">
    <link rel="shortcut icon" href="./img/icon.gif" type="image/x-icon">
    <link href="./codemirror/lib/codemirror.css" rel="stylesheet" >
    <script src="./codemirror/lib/codemirror.js"></script>
    <script src="./codemirror/mode/clike/clike.js"></script>
    <script src="./codemirror/mode/clike/clike.js"></script>
    <script src="./codemirror/mode/python/python.js"></script>
    <script src="./codemirror/addon/edit/matchbrackets.js"></script>
    <script src="./codemirror/addon/edit/closebrackets.js"></script>
    <script src="./codemirror/addon/hint/show-hint.js"></script>
    <script src="./codemirror/addon/hint/sql-hint.js"></script>
    <script src="./codemirror/addon/hint/anyword-hint.js"></script>
    <link href="./codemirror/addon/hint/show-hint.css" rel="stylesheet">
    <link href="./codemirror/theme/ayu-dark.css" rel="stylesheet">
    <link href="./codemirror/theme/eclipse.css" rel="stylesheet">
</head>
<body>
<?php
$indata = $code = "";
$codeerr = "";
$val = $val2 = 0;
$errout = "";
$res = "";
$totaltime = -1;
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (empty($_POST["indata"])){
        $indata = "";
    }else{
        $indata = $_POST["indata"];
    }
    if (empty($_POST["code"])){
        $codeerr = "代码是必须的";
    }else{
        $code = $_POST["code"];
    }
    $choice = $_POST["lang"];
    file_put_contents('D:\wamp64\www\bin\a.in',$indata);
    file_put_contents('D:\wamp64\www\bin\err.out','');
    if($choice == "Cpp" || $choice == "C"){
        file_put_contents('D:\wamp64\www\bin\a.cpp', $code);
        exec('cd D:\wamp64\www\bin & g++ a.cpp -o a.exe 2> err.out',$out,$val);
    }
    else if($choice == "Java"){
        file_put_contents('D:\wamp64\www\bin\Main.java', $code);
        exec('cd D:\wamp64\www\bin & javac Main.java 2> err.out',$out,$val);
    }
    else if($choice == "Python"){
        file_put_contents('D:\wamp64\www\bin\a.py', $code);
        $val = 0;
    }

    if ($val == 1){
        $errout = file_get_contents('D:\wamp64\www\bin\err.out');
    }else{
        $stime = microtime(true);
        if($choice == "Cpp" || $choice == "C")
            exec("cd D:\wamp64\www\bin & a.exe <a.in>a.out",$out,$val2);
        else if($choice == "Java")
            exec("cd D:\wamp64\www\bin & java Main <a.in>a.out",$out,$val2);
        else if($choice == "Python")
            exec("cd D:\wamp64\www\bin & Python a.py <a.in>a.out",$out,$val2);
        $etime = microtime(true);
        $totaltime = $etime - $stime;
        $res = file_get_contents('D:\wamp64\www\bin\a.out');
        $errout = "Success!";
    }
}
?>
	<header>
		<h1>Online Compiler Alpha</h1>
	</header>

    <hr>
	<div class="clearfix"></div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<main>
		<div class="bigbox" style="margin:0 auto;">
			<textarea name="code" id="code" placeholder="Edit your code here..." spellcheck="false"><?php echo $code;?></textarea>
			<div class="box">
				<textarea name="compiler_info" id="compiler_info" cols="60" rows="35" placeholder="Compiler information here." spellcheck="false" readonly="true" style="font-family: Consolas;"><?php echo $errout;if($totaltime != -1){echo "                                                RunTime:{$totaltime}sec.";}?></textarea>
			</div>
		</div>
		<div class="bigbox" align="center">
			<div class="Ibox">
			<textarea name="indata" id="indata" cols="30" rows="12" placeholder="Edit input data here..." spellcheck="false" style="font-family: Consolas;"><?php echo $indata;?></textarea>

			<textarea name="res" id="res" cols="30" rows="12" placeholder="Result here..." spellcheck="false" style="font-family: Consolas;"><?php echo $res;?></textarea>
			</div>
			<div class="Option" aria-required="true">
				<select name="lang" id="lang_choose">
					<option value="Cpp">C++</option>
					<option value="C">C</option>
                    			<option value="Python">Python3</option>
                    			<option value="Java">Java</option>
				</select>
				<button type="submit" id="run">Run</button>
			</div>
			

			<div class="clearfix"></div>
		</div>
	</main>
    </form>
	<footer>
		<p>
		Copyright@2021-2022 Online Complier All Rights Reserved.
		</p>
	</footer>

	<script type="text/javascript">
        var editor = CodeMirror.fromTextArea(document.getElementById('code'), {
            mode : "text/x-c++src",
            // mode : "python",

            indentWithTabs : true,
            lineNumbers : true,
            smartIndent : true,
            theme : 'eclipse',
            matchBrackets : true,
            indentUnit: 4,
            line : true,
            lineWiseCopyCut : true,
            readOnly : false,
            showCursorWhenSelecting : true,
            autoCloseBrackets: true,
            foldGutter: true, 
            extraKeys : {
                "Ctrl" : "autocomplete"
            },
            styleSelectedText : true
        });
        editor.setSize('60%', '550px');
    </script>
    <script>
    document.forms[0].lang.value = '<?php echo $choice; ?>';
    </script>
</body>
</html>
