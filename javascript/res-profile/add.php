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
$name = $_SESSION["name"];

if (isset($_POST['last_name']) && isset($_POST['first_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
    // Input validation
    $msg = validateProfile();
    if (is_string($msg)) {
        // Write error message into session()
        $_SESSION["error"] = $msg;
        header("Location: add.php");
        return;
    } else {
        $msg = validateEdu();
        if (is_string($msg)) {
            // Write error message into session()
            $_SESSION["error"] = $msg;
            header("Location: add.php");
            return;
        } else {
            $msg = validatePos();
            if (is_string($msg)) {
                // Write error message into session()
                $_SESSION["error"] = $msg;
                header("Location: add.php");
                return;
            } else {
                // Do database processing
                $sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':uid' => $_SESSION['user_id'],
                    ':fn' => $_POST['first_name'],
                    ':ln' => $_POST['last_name'],
                    ':em' => $_POST['email'],
                    ':he' => $_POST['headline'],
                    ':su' => $_POST['summary']
                ));

                $profile_id = $pdo->lastInsertId();

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
                            ':pid' => $profile_id,
                            ':rank' => $rank,
                            ':year' => $year,
                            ':desc' => $desc
                        )
                    );
                    $rank++;
                }

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
                            ':pid' => $profile_id,
                            ':rank' => $rank,
                            ':year' => $year,
                            ':iid' => $inst_id
                        )
                    );
                    $rank++;
                }

                $_SESSION["success"] = "Record added.";
                header("Location: index.php");
                return;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Christoph Lansche</title>
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
        <h1>Adding Profile for <?= htmlentities($name) ?></h1>
        <?php
        flashMessage();
        ?>
    </header>
    <main>
        <form method="post">
            <p>First Name:
                <input type="text" name="first_name" size="60" value="" />
            </p>
            <p>Last Name:
                <input type="text" name="last_name" size="60" value="" />
            </p>
            <p>E-Mail:
                <input type="text" name="email" value="" />
            </p>
            <p>Headline:
                <input type="text" name="headline" value="" />
            </p>
            <p>Summary:
                <textarea name="summary" row="8" cols="80"></textarea>
            </p>
            <p>
                Education:
                <input type="submit" id="addEdu" value="+">
            </p>
            <div id="education_fields"></div>

            <p>
                Position:
                <input type="submit" id="addPos" value="+">
            </p>
            <div id="position_fields"></div>

            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </form>

    </main>

    <script>
        countPos = 0;
        countEdu = 0;
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