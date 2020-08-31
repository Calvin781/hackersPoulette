<?php require('countries.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Description" content="Author: A.N. Calvin,
    Illustrator: Calvin, Category: Form, Support,
  ">
  <link rel="icon" type="image/png" href="img/logo_trans.png" />
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalyse.css">
</head>

<body>


    <img id="logo" src="img/logo_trans.png" alt="logo">
    <h3> Contact form</h3>

    <div class="form-conctact">
        <form method="post" action="form.php" id="form">
            <label for="fname" id="label-fname"> First name</label>
            <input type="text" id="fname" name="firstname" placeholder=" " required onchange="testFname();">

            <label for="lname" id="label-lname">Last name</label>
            <input type="text" id="lname" name="lastname" placeholder=" " required onchange="testLname();">
            <br>
            <label for="email" id="label-email">Email</label>
            <input type="text" id="email" name="email" placeholder=" " required onchange="testEmail();">
            <input type="hidden" name="raison">

            <div class="radio-gender">
                <label for="male">Male</label>
                <input type="radio" name="gender" value="H" checked id="male">
                <label for="female">Female</label>
                <input type="radio" name="gender" value="F" id="female">

            </div>

            <br>

            <label for="country">Country</label>
            <select id="country" name="country" required>
                <option value=""> Select a country</option>

                <?php foreach ($countries as $country) {
                    echo "<option value='" . array_search($country, $countries, true) . "'> $country </option>";
                }
                ?>
            </select>

            <label for="subject" id="label-subject">Subject</label>
            <select id="subject" name="subject" required>
                <option value="">Select a subject</option>
                <option value="Technical problem">Technical problem</option>
                <option value="Question">Question</option>
                <option value="Claim">Claim</option>
                <option value="Other">Other</option>
            </select>
            <br>
            <br>
            <label for="comment">How can we help you ?</label>
            <textarea name="comment" id="comment" required minlength="30"></textarea>
            <input type="submit" value="SUBMIT" id="submitbutton" name="submit">

        </form>

        <?php

        ?>
    </div>

</body>

<script src="js/formvalidator.js"></script>

</html>