<?php
$server = "localhost" ;
$username = "root" ;
$password = "" ;
$dbname = "buspassdb" ;

$conn = mysqli_connect($server, $username ,$password, $dbname) ;

if(isset($_POST['submit'])){

    if(!empty($_POST['Fullname'] && !empty($_POST['Gender'] && !empty($_POST['Age'] && !empty($_POST['Contactnumber'] && !empty($_POST['Emailid'] && !empty($_POST['image'] && !empty($_POST['Idimage'] && !empty($_POST['identitytype'] && !empty($_POST['icnum'] && !empty($_POST['fromdate'] && !empty($_POST['Todate'] && !empty($_POST['source'] && !empty($_POST['destination'] && !empty($_POST['amount'])){
        $Fullname = $_POST['Fullname'];
        $Gender = $_POST['Gender'];
        $Age = $_POST['Age'];
        $Contactnumber = $_POST['Contactnumber'];
        $Emailid = $_POST['Emailid'];
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $Idimage = $_FILES['Idimage']['name'];
        $Idimage_tmp = $_FILES['Idimage']['tmp_name'];
        $identitytype = $_POST['identitytype'];
        $icnum = $_POST['icnum'];
        $fromdate = $_POST['fromdate'];
        $Todate = $_POST['Todate'];
        $source = $_POST['source'];
        $destination = $_POST['destination'];
        $amount = $_POST['amount'];
$query = "insert into form(Fullname,Gender,Age,Contactnumber,Emailid,image,Idimage,identitytype,icnum,fromdate,Todate,source,destination,amount) values('$Fullname' , '$Gender' , '$Age' , '$Contactnumber' , '$Emailid' , '$image' , '$Idimage' , '$identitytype' , '$icnum' , '$fromdate' , '$Todate' , '$source' , '$destination' , '$amount')" ;

$run = mysqli_query($conn,$query) or die(mysqli_error());

if($run){
    echo "form submitted succesfully" ;
}
else{
    echo "form not submitted" ;
}
    }
    else{
        echo "all fields required" ;
    }
}
?>