<?php
//http://localhost/BossioLuciusAsst1/Asst1Main.php
//Lucius Bossio
//Include file for broadway show ticketing app

function WriteHeaders($Heading="Welcome",$TitleBar="MySite")
{
    echo "
    <link rel =\"stylesheet\" type =\"text/css\" href =\"Asst1Style.css\"/>
    <!doctype html>
    <html lang = \"en\">
    <head>
        <meta charset = \"UTF-8\">
        <title>$TitleBar</title>    \n
    </head>
    <body>  \n
    <h1>$Heading</h1>   \n
    ";
}

function WriteFooters()
{
    DisplayContactInfo();
    echo "</body> \n";
    echo "</html> \n";
}

function DisplayContactInfo()
{
    echo "<footer>Questions? Comments? ";
    echo "<a href = mailto:lucius.bossio@student.sl.on.ca>lucius.bossio@student.sl.on.ca</a>";
    echo "</footer>";
}

function DisplayLabel($prompt)
{
    echo "<label>" .$prompt. "</label>";
}

function DisplayTextBox($Name, $Size, $Value=0)
{
    echo "<input type = text name =\"$Name\" size = $Size value = \"$Value\">";
}

function DisplayDateBox($Name, $Size, $Value)
{
    echo "<input type = date name = \"$Name\" size = $Size value = \"$Value\">";
}

function DisplayTimeBox($Name, $Size, $Value)
{
    echo "<input type = time name = \"$Name\" size = $Size value = \"$Value\">";
}

function DisplayUpDownBox($Name, $Value, $Min, $Max)
{
    echo "<input type = number name = \"$Name\" value = \"$Value\" min = \"$Min\" max = \"$Max\">";
}

function DisplayRadioBox($Name, $ID, $Value)
{
    echo "<input type = radio id = \"$ID\" name = \"$Name\" value = \"$Value\">";
}

function DisplayImage($FileName, $ImageAlt, $ImageWidth, $ImageHeight)
{
    echo "<img src = $FileName height = $ImageHeight width = $ImageWidth alt = $ImageAlt>";
}

function DisplayButton($Name, $ButtonText, $FileName, $ButtonAlt)
{
    if ($FileName == "")
    {
        echo "
            <button type=Submit name=$Name alt=$ButtonAlt>
            $ButtonText
            </button>
        ";
    }
    else
    {
        echo "<button type=Submit name=$Name alt=$ButtonAlt>";
        DisplayImage($FileName, 65, 150, $ButtonAlt);
        echo "</button>";        
    }
}

function CreateConnectionObject()
{
    $fh = fopen('auth.txt','r');
    $Host =  trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh)); 
    fclose($fh);
    $mysqlObj=new mysqli($Host, $UserName, $Password,$Database,$Port);
    
    if ($mysqlObj->connect_errno != 0) 
    {
     echo "<p>Connection failed. Unable to open database $Database. 
          Error: ". $mysqlObj->connect_error . "</p>";
     exit;
    }
    return ($mysqlObj);
}
?>