<?php
$fname=$_POST['fname'];
$sname=$_POST['sname'];
$email=$_POST['email'];
echo $fname.'<br>'.$sname.'<br>'.$email;
mail("\n kristina.kuzmich.33@gmail.com", "\n Заявка с сайта","\n Имя:".$fname,"\n Фамилия:".$sname."\n Email:".$email." ");

if(mail("\n kristina.kuzmich.33@gmail.com", "\n Заявка с сайта","\n Имя:".$fname,"\n Фамилия:".$sname."\n Email:".$email." "))
{
	echo "Сообщение успешно отправлено";
} else {
	echo "При отправке сообщения возникли ошибки";
}