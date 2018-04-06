<!DOCTYPE html>
<html>
<head>
    <title>CorePower Yoga Tracker</title>
<style type="text/css">
.header {
    background-image: url("header_image.png");
    background-size: contain;
    background-repeat: no-repeat;
    background-position: right center;
    font-family: Futura, "Trebuchet MS", Arial, sans-serif;
    font-size: 20px;
    font-style: normal;
    font-variant: normal;
    font-weight: 500;
    line-height: 26.4px;
}
body {
    background-image: url("background_image.jpg");
    background-size:cover;
    background-repeat: no-repeat;
    }
.output{
    font-family: Futura, "Trebuchet MS", Arial, sans-serif;
    font-size: 18px;
    font-style: normal;
    font-variant: normal;
    font-weight: 400;
    line-height: 20px;
    }

</style>
</head>
<body>
    <div class = "header">
        <h1>Class Tracker</h1>
    </div>
<div class = "output">
    <?php
     DEFINE('DB_USERNAME', 'root');
     DEFINE('DB_PASSWORD', 'root');
     DEFINE('DB_HOST', 'localhost');
     DEFINE('DB_DATABASE', 'Yoga_Tracker_db');

     $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

     if (mysqli_connect_error()) {
      die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
     }

     
    $date = $_POST['date_of_class'];
    $time = $_POST['time_of_class'];
    $instructor = $_POST['instructor'];
    $class = $_POST['type'];
    $feeling = $_POST['emoji_feeling'];
    $progress = $_POST['progress'];


     $sql = "INSERT INTO User_Input (Date_of_Class, Time_of_Class, Instructor, Class, Feeling, Progress) VALUES ('$date', '$time', '$instructor', '$class', '$feeling', '$progress')";

     if ($mysqli->query($sql) === TRUE) {
        # Number of Total Classes 
        $sql_class_count = "SELECT * from User_Input";
        if ($result = mysqli_query($mysqli, $sql_class_count)){
            $class_count = mysqli_num_rows($result);
            printf("Class Count: %d \n", $class_count);
            echo "<br><br><br>";
            mysqli_free_result($result);
           
        # Number of Classes with Instructor    
        $sql_teacher_count= "SELECT Instructor FROM User_Input WHERE Instructor = '$instructor'";
        $result_teacher = mysqli_query($mysqli, $sql_teacher_count);
        $teacher_count=mysqli_num_rows($result_teacher);
        if ($teacher_count > 1){
            $strclass = 'classes';
        }
        else{
            $strclass = 'class';
        }
        $teach_name = str_replace('_', ' ', $instructor);
        printf("You've attended %d %s with %s as your instructor\n", $teacher_count, $strclass, $teach_name);
        
        }
        mysqli_free_result($result_teacher);
        echo "<br><br><br>";

        #Number of Type of Class 
        $sql_type_count = "SELECT Class FROM User_Input WHERE Class = '$class'";
        $result_class_type = mysqli_query($mysqli, $sql_type_count);
        $type_count = mysqli_num_rows($result_class_type);
        if ($type_count > 1){
            $strtype = 'classes';
        }
        else{
            $strtype = 'class';
        }
        switch ($class){
            case YS:
                $typeStr = 'Yoga Sculpt';
                break;
            case C2:
                $typeStr = 'C2 Yoga';
                break;
            case C1:
                $typeStr = 'C1 Yoga';
                break;
            default:
                $typeStr = null;
        }
        printf("You've attended %d %s %s", $type_count, $typeStr, $strtype);
        echo "<br><br><br>";
        mysqli_free_result($result_class_type);

        #Cost Per Month 
        $today = date("Y-m-d");
        $comparison_date = substr($today, 0, -3);
        $month = substr($comparison_date, 5);
        $sql_date = "SELECT * FROM User_Input WHERE Date_of_Class LIKE '$comparison_date%'";
        $class_per_month = mysqli_query($mysqli, $sql_date);
        $cost_per_class = 189/mysqli_num_rows($class_per_month);
        
        switch ($month){
            case "01":
                $monthStr = 'January';
                break;
            case "02":
                $monthStr = 'February';
                break;
            case "03":
                $monthStr = 'March';
                break;
            case "04":
                $monthStr = 'April';
                break;
            case "05":
                $monthStr = 'May';
                break;
            case "06":
                $monthStr = 'June';
                break;
            case "07":
                $monthStr = 'July';
                break;
            case "08":
                $monthStr = 'August';
                break;
            case "09":
                $monthStr = 'September';
                break;
            case "10":
                $monthStr = 'October';
                break;
            case "11":
                $monthStr = 'November';
                break;
            case "12":
                $monthStr = 'December';
                break;
            default:
                $monthStr = null;
        }
        
        printf("For the month of %s you've paid $%.2f per class", $monthStr, $cost_per_class);
        echo "<br><br><br>";
        
        $sql_time = "SELECT Time_of_Class FROM (SELECT Time_of_Class, count(*) as count FROM User_Input GROUP BY Time_of_Class ORDER BY count DESC LIMIT 1) time_table";
        $time_query= mysqli_query($mysqli, $sql_time);
        $row = mysqli_fetch_assoc($time_query);
        $top_time = $row['Time_of_Class'];
        printf ("Your favorite time to attend class is %s", $top_time);
        echo "<br>";

    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
    $mysqli->close();

       
    ?>
</div>
</body>
</html>



