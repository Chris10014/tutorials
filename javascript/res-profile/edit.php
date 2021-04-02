<?php
session_start();
require_once "pdo.php";
require_once "util.php";

if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header("Loction: index.php");
}
// Input validation

if (isset($_POST['last_name']) && isset($_POST['first_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
    // Data validation
    $msg = validateProfile();
    if (is_string($msg)) {
        //     
        $_SESSION["error"] = $msg;
        header("Location: edit.php?profile_id=" . $_GET['profile_id']);
        return;
    } else {
        $msg = validatePos();
        if (is_string($msg)) {
            $_SESSION["error"] = $msg;
            header("Location: edit.php?profile_id=" . $_GET['profile_id']);
            return;
        } else {
            // Update validated data
            $sql = "UPDATE profile SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su WHERE (user_id = :uid && profile_id = :id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'],
                ':id' => $_GET['profile_id']
            ));

            // Clear out all position data
            $stmt = $pdo->prepare('DELETE FROM position WHERE profile_id = :pid');
            $stmt->execute(array(':pid' => $_GET['profile_id']));


            // Insert the position data
            $rank = 1;
            for ($i = 1; $i <= 9; $i++) {
                if (!isset($_POST['year' . $i])) continue;
                if (!isset($_POST['desc' . $i])) continue;
                $year = $_POST['year' . $i];
                $desc = $_POST['desc' . $i];

                $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');

                $stmt->execute(
                    array(
                        ':pid' => $_GET['profile_id'],
                        ':rank' => $rank,
                        ':year' => $year,
                        ':desc' => $desc
                    )
                );
                $rank++;
            }

            // Clear out all education data
            $stmt = $pdo->prepare('DELETE FROM education WHERE profile_id = :pid');
            $stmt->execute(array(':pid' => $_GET['profile_id']));


            // Insert the education data
            $rank = 1;
            for ($i = 1; $i <= 9; $i++) {
                if (!isset($_POST['edu_year' . $i])) continue;
                if (!isset($_POST['school' . $i])) continue;
                $year = $_POST['edu_year' . $i];
                $school = $_POST['school' . $i];

                // Retreive institution id
                $sql = "SELECT institution_id FROM Institution WHERE name = :inst";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(":inst" => $school));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row === false) {
                    // Insert new institution
                    $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:na)');

                    $stmt->execute(
                        array(
                            ':na' => $school
                        )
                    );
                    $inst_id = $pdo->lastInsertId();
                } else {
                    $inst_id = $row['institution_id'];
                }

                $stmt = $pdo->prepare('INSERT INTO Education (profile_id, rank, year, institution_id) VALUES ( :pid, :rank, :year, :iid)');

                $stmt->execute(
                    array(
                        ':pid' => $_GET['profile_id'],
                        ':rank' => $rank,
                        ':year' => $year,
                        ':iid' => $inst_id
                    )
                );
                $rank++;
            }

            $_SESSION['success'] = "Profile updated";
            header("Location: index.php");
            return;
        }
    }
}


// Retrieve the profile from the database
$profile_id = $_GET['profile_id'];
$sql = "SELECT * FROM profile WHERE profile_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":id" => $profile_id));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: index.php');
    return;
}

$first_name = htmlentities($row['first_name']);
$last_name = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);

$profile_id = $_GET['profile_id'];

// Retrieve the positions from the database
$sql = "SELECT * FROM position WHERE profile_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":id" => $profile_id));
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve the education from the database
$sql = "SELECT * FROM education JOIN institution ON education.institution_id = institution.institution_id WHERE profile_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":id" => $profile_id));
$educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Chistoph Lansche</title>
    <?php require_once("head.php"); ?>
</head>

<body>
    <header>

        <?php
        $name = isset($_SESSION['name']) ? $_SESSION['name'] : false;

        if ($name == false) {
            header("Location: login.php");
            return;
        }
        ?>
        <h1>Editing Profile for <?= htmlentities($name) ?></h1>
    </header>
    <main>
        <?php
        flashMessage();
        ?>
        <form method="post">
            <p>First Name:
                <input type="text" name="first_name" size="60" value="<?= $first_name ?>" />
            </p>
            <p>Last Name:
                <input type="text" name="last_name" size="60" value="<?= $last_name ?>" />
            </p>
            <p>Email:
                <input type="text" name="email" value="<?= $email ?>" />
            </p>
            <p>Headline:
                <input type="text" name="headline" value="<?= $headline ?>" />
            </p>
            <p>Summary:
                <textarea name="summary" row="8" cols="80"><?= $summary ?></textarea>
            </p>

            <input type="hidden" name="profile_id" id="" value="<?= $_GET['profile_id'] ?>">
            <p>
                Education:
                <input type="submit" id="addEdu" value="+">
            </p>
            <div id="education_fields">
                <?php

                $countEdu = 1;
                foreach ($educations as $education) {
                    echo '<div id="education' . $countEdu . '">';
                    echo '<p>Year: <input type="text" name="edu_year' . $countEdu . '" value="' . htmlentities($education['year']) .  '"/>';
                    echo '<input type="button" value="-"';
                    echo 'onclick="$(\'#education' . $countEdu . '\').remove();return false;" /></p>';
                    echo '<p><input type="text" size="80" name="school' . $countEdu . '" class="school" value="' . htmlentities($education['name']) . '" /></p></div>';
                    $countEdu++;
                }
                ?>
            </div>
            <p>
                Position:
                <input type="submit" id="addPos" value="+">
            </p>
            <div id='position_fields'>
                <?php
                $countPos = 1;
                foreach ($positions as $position) {
                    echo '<div id="position' . $countPos . '">';
                    echo '<p>Year: <input type="text" name="year' . $countPos . '" value="' . htmlentities($position['year']) .  '"/>';
                    echo '<input type="button" value="-"';
                    echo 'onclick="$(\'#position' . $countPos . '\').remove();return false;" /></p>';
                    echo '<p><textarea name="desc' . $countPos . '" rows="8" cols="80">' . htmlentities($position['description']) . '</textarea></p></div>';
                    $countPos++;
                }
                ?>
            </div>
            <p>
                <input type="submit" value="Save">
                <input type="submit" name="cancel" value="Cancel">
            </p>
        </form>

    </main>

    <script>
        countPos = <?= $countPos ?>;
        countEdu = <?= $countEdu ?>;
        $(document).ready(function() {
            window.console && console.log('document loaded.');
            $('#addPos').click(function(event) {
                event.preventDefault();
                if (countPos == 9) {
                    alert('Maximum of nine position entries exceeded');
                    return;
                }
                countPos++;
                window.console && console.log('Adding position.');
                $('#position_fields').append(
                    '<div id="position' + countPos + '"> \
                    <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
                    <input type="button" value="-" onclick="$(\'#position' + countPos + '\').remove();return false;"></p>\
                    <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea> \
                    </div>'
                );
            });

            window.console && console.log('document loaded.');
            $('#addEdu').click(function(event) {
                event.preventDefault();
                if (countEdu == 9) {
                    alert('Maximum of nine education entries exceeded');
                    return;
                }
                countEdu++;
                window.console && console.log('Adding schools.');
                $('#education_fields').append(
                    '<div id="education' + countEdu + '"> \
                    <p>Year: <input type="text" name="edu_year' + countEdu + '" value="" /> \
                    <input type="button" value="-" onclick="$(\'#education' + countEdu + '\').remove();return false;"></p>\
                    School: <input type="text" size="80" name="school' + countEdu + '" class="school" value="" /> \
                    </div>'
                );
                $('.school').autocomplete({

                    source: "school.php"
                });
            });
        });
    </script>

</body>

</html>