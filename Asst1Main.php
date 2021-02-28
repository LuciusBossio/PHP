<?php
//http://localhost/BossioLuciusAsst1/Asst1Main.php
//Lucius Bossio
//Main and functions for broadway show ticketing app
require_once("Asst1Include.php");

//Main
date_default_timezone_set ('America/Toronto');
$mysqlObj = CreateConnectionObject(); 
$TableName = "BroadwayShows";

WriteHeaders("Lucius Bossio Assignment 1", "Lucius Bossio Assignment 1");

if (isset($_POST['Save']))      
{
    AddRecordToTable($mysqlObj, $TableName);
}
else if (isset($_POST['CreateTable']))      
{
    CreateTableForm($mysqlObj, $TableName);
}
else if (isset($_POST['AddRecord']))
{
    AddRecordForm();
}   
else if (isset($_POST['ShowData']))
{
    showDataForm($mysqlObj, $TableName);
}
else
{
    DisplayMainForm();
}

echo "<div class = \"navBar\">";
    WriteFooters();
echo "</div>";

function CloseConnection($mysqlObj, $stmt)
{
    $stmt->close();
    mysqli_close($mysqlObj);
}

function DisplayMainForm()
{   
    echo "<div class = \"navBar\">";
        echo "<form action = ? method=post>";
            DisplayButton("CreateTable", "Create Table", "CreateTable.png", "Create Table");
            DisplayButton("AddRecord", "Add Record", "AddRecord.png", "Add Record");
            DisplayButton("ShowData", "Show Data", "ShowData.png", "Show Data");
        echo "</form>";
    echo "</div>";
}

function CreateTableForm($mysqlObj, $TableName)
{   
    //Drop table
    $stmt = $mysqlObj->prepare("Drop Table If Exists $TableName");
    $stmt->execute();

    //Create table
    $showName = "showName varchar(50)";
    $performanceDateAndTime = "performanceDateAndTime datetime";
    $nbrTickets = "nbrTickets integer";
    $ticketPrice = "ticketPrice decimal(5,2)";
    $SQLStatement = "Create Table $TableName($showName, $performanceDateAndTime, $nbrTickets, $ticketPrice)";
        
    $stmt = $mysqlObj->prepare($SQLStatement);
    if ($stmt == false) 
    {	
        echo "Unable to create table $TableName.";
        exit;
    }
    
    $CreateResult = $stmt->execute();
    if ($CreateResult)
    {
        echo "Table $TableName created.";
    }  
    else
    {
        echo "Unable to create table $TableName.";
    }

    echo "<div class = \"navBar\">";
        echo "<form action = ? method=post>";
            DisplayButton("Home", "Home", "Home.png", "Home");
        echo "</form>";
    echo "</div>";

    CloseConnection($mysqlObj, $stmt);
}

function AddRecordForm()
{
    echo "<form action = ? method=post>";
        echo "<div class = \"datapair\">";
            DisplayLabel("Show Name: ");
            DisplayTextBox("showName", 20, "");
        echo "</div>";
        
        echo "<div class = \"datapair\">";
            DisplayLabel("Performance Date: ");
            DisplayDateBox("performanceDate", 20, date('Y-m-d'));
        echo "</div>";

        echo "<div class = \"datapair\">";
            DisplayLabel("Performance Time: ");
            DisplayTimeBox("performanceTime", 20, date('h:i'));
        echo "</div>";

        echo "<div class = \"datapair\">";
            DisplayLabel("Number of Tickets: ");
            DisplayUpDownBox("nbrTickets", 2, 1, 10);
        echo "</div>";

        DisplayLabel("Ticket Price: ");
        echo "<div class = \"datapair\">";
            DisplayRadioBox("ticketPrice", 100, 100);
            DisplayLabel("$100");
        echo "</div>";
        echo "<div class = \"datapair\">";
            DisplayRadioBox("ticketPrice", 150, 150);
            DisplayLabel("$150");
        echo "</div>";
        echo "<div class = \"datapair\">";
            DisplayRadioBox("ticketPrice", 200, 200);
            DisplayLabel("$200");
        echo "</div>";

        echo "<div class = \"navBar\">";
            DisplayButton("Save", "Save", "Save.png", "Save");
            DisplayButton("Home", "Home", "Home.png", "Home");
        echo "</div>";
    echo "</form>";
}

function AddRecordToTable($mysqlObj, $TableName)
{
    $userShowName = $_POST['showName'];
    $userPerformanceDateAndTime = $_POST['performanceDate'] . " " . $_POST['performanceTime'];
    $userNbrTickets = $_POST['nbrTickets'];
    $userTicketPrice = $_POST['ticketPrice'];

    $query = "Insert Into $TableName (showName, performanceDateAndTime, nbrTickets, ticketPrice) values (?,?,?,?)";
    $stmt = $mysqlObj->prepare($query);     
    if ($stmt == false) 
    {	
        echo "Prepare failed on query $query" . $mysqlObj->error;
        exit;
    }

    $BindSuccess = $stmt -> bind_param ("ssid", $userShowName, $userPerformanceDateAndTime, $userNbrTickets, $userTicketPrice);
    if($BindSuccess)
    {
        $success = $stmt -> execute();
    }
    else
    {
        echo "Unable to add record to $TableName";
    }

    if($success)
    {
        echo "Record successfully added to $TableName";
    }    
    else
    {
        echo "Unable to add record to $TableName";
    }
    echo "<div class = \"navBar\">";
        echo "<form action = ? method=post>";
            DisplayButton("Home", "Home", "Home.png", "Home");
        echo "</form>";
    echo "</div>";

    CloseConnection($mysqlObj, $stmt);
}

function showDataForm($mysqlObj, $TableName)
{
    $query = "Select showName, performanceDateAndTime, nbrTickets, ticketPrice From $TableName Order By ticketPrice";
    $stmt = $mysqlObj->prepare($query);
    $stmt->execute();
    $stmt->bind_result($showName, $performanceDateAndTime, $nbrTickets, $ticketPrice);     
    
    echo " <table>
        <tr>
            <th colspan = 4>$TableName</th>
        </tr>
        <tr>
            <th>Show Name</th>
            <th>Date/Time</th>
            <th>Number of Tickets</th>
            <th>Ticket Price</th>
        </tr>
    ";
    while($stmt->fetch())
    {
        echo "
            <tr>
                <td>$showName</th>
                <td>$performanceDateAndTime</th>
                <td>$nbrTickets</th>
                <td>$ticketPrice</th>
            </tr>
        ";
    }
    echo "</table>";
    
    echo "<div class = \"navBar\">";
        echo "<p>$stmt->num_rows bookings to date.</p>";
        echo "<form action = ? method=post>";
            DisplayButton("Home", "Home", "Home.png", "Home");
        echo "</form>";
    echo "</div>";

    CloseConnection($mysqlObj, $stmt);
}
?>