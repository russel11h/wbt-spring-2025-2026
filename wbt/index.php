<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    echo "Question 1 ";
    echo "<br>";
    $length = 10;
    $width = 5;
    $area = $length * $width;
    echo "The area of the rectangle is: " . $area;
    echo "<br>";
    echo "Question 2";
    echo "<br>";
    echo "The perimeter of the rectangle is: " . (2 * ($length + $width));
    echo "<br>";
    echo "bruh";
    $price = 100;
    echo "Value Added Tax (VAT) is: " . ($price * 0.15);
    echo "<br>";
echo "Question 3";
echo "<br>";
    $num1 = 10;
    if ($num1 % 2 == 0) {
        echo "$num1 is an even number.";
    } else {
        echo "$num1 is an odd number.";
    }
    echo "Question 4";
    echo "<br>";
    $a = 10;
    $b = 20;
    $c = 30;
    if ($a > $b && $a > $c) {
        echo "$a is the largest number.";
    } elseif ($b > $a && $b > $c) {
        echo "$b is the largest number.";
    } else {
        echo "$c is the largest number.";
    }
    echo "<br>";
echo "Question 5";
echo "<br>";
for ($i = 1; $i <= 100; $i++) {
        if ($i%2==1) {
            echo "$i is an odd number.<br>";
        } 
    }
    echo "<br>";
    echo "Question 6";
    echo "<br>";
    $number = array(1, 2, 3, 4, 5);
    $find = 3;
    for ($i = 0; $i < count($number); $i++) {
        if ($number[$i] === $find) {
            echo "$find is found in the array.";
            break;
        }
        
    }
    echo "<br>";
echo "Question 7";
echo "<br>";
echo "Shape 1:";
echo "<br>";
for ($i = 1; $i <= 3; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "* ";
    }
    echo "<br>";
}
echo "br";
echo "Shape 2:";
echo "<br>";
for ($i = 3; $i >= 1; $i--) {
    for ($j = 1; $j <= $i; $j++) {
        echo $j . " ";
    }
    echo "<br>";
}
echo "<br>";
echo "Shape 3:";
echo "<br>";    
$letters = array("A", "B", "C", "D", "E", "F");
$index = 0;

for ($i = 1; $i <= 3; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo $letters[$index] . " ";
        $index++;
    }
    echo "<br>";
}



    ?>
</body>
</html>