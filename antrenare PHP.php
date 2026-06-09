<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php echo "Mesaj123"; ?>
    <?php echo "<script>console.log('Mesaj consola')</script>"; ?>

    <?php

$numere = [12, 7, 4, 9, 15, 20, 33, 18, 5, 2];

$pare = 0;
$impare = 0;

for ($i = 0; $i < count($numere); $i++) {

    if ($numere[$i] % 2 == 0) {
        echo $numere[$i] . " este PAR <br>";
        $pare++;
    } else {
        echo $numere[$i] . " este IMPAR <br>";
        $impare++;
    }
}

echo "<br>";
echo "Numere pare: " . $pare . "<br>";
echo "Numere impare: " . $impare . "<br>";

?>

</body>
</html>