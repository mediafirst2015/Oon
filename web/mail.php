<?php


$txt = '<div style="background-color:#eb7ba7;">
    <!--[if gte mso 9]>
    <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
        <v:fill type="tile" src="http://i.imgur.com/YJOX1PC.png" color="#eb7ba7"/>
    </v:background>
    <![endif]-->
    <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td valign="top" align="left" background="http://i.imgur.com/YJOX1PC.png">
            тут некий текст
      </td>
    </tr>
  </table>
</div>
        ';

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <tulupov.m@gmail.com>' . "\r\n";
mail('zinoviev.a@evrika.ru','Пример',$txt,$headers);