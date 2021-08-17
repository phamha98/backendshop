<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>sin chao ba con</title>
    <style>
        body {
            background: rgb(235, 229, 177);
        }

        .container {
            width: 700px;
            min-height: 500px;
            background-color: rgba(5, 255, 234, 0.267);
            margin: auto;
            padding: 10px;
            margin-top: 30px;
            position: relative;
            border-radius: 5px;
            transition: 2s;
        }

        .contentImage {
            margin: auto;
            width: 700px;
            height: 100px;
            background: #ecfaf0;

        }

        .image {
            min-width: 100px;
            height: 100px;
            float: left;
        }

        .text {
            float: left;
            width: 100px;
            height: 100px;
            font-size: 10px;
        }

        .font1 {
            color: rgb(32, 126, 3);
            font-size: 30px;
        }

    </style>
</head>

<body>



    <div class="container">

        <?php foreach ($list as $key => $hocsinh) {
        // echo $hocsinh->id;
        //echo $hocsinh->name;
        ?>
        <div class="contentImage">
            <div class="image">
                <img alt=“some_text“ src="<?php echo $hocsinh->img; ?>"
                    style=“width:100px;height:100px;” />

            </div>
            <div class="text">
                <?php echo $hocsinh->name; ?>
            </div>
            <div class="text">
                <?php echo $hocsinh->img; ?>
            </div>
        </div>
        <?php echo '</br>';} ?>
    </div>

</body>

</html>
