<?php
$db = new mysqli('localhost', 'root', '', 'bucketlist');
if ($db->connect_errno > 0) {
    die('Fel vid anslutning: ' . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $priority = trim($_POST["priority"] ?? "");

    if ($name !== "" && $description !== "" && in_array($priority, ["1", "2", "3"], true)) {
        $sql = "INSERT INTO bucketlist_items (name, description, priority) VALUES (?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssi", $name, $description, $priority);
        $stmt->execute();
        header("Location: bucketlist.php");
        exit;
    } else {
        $error = "Fyll i alla fält och välj en prioritet i listan.";
    }
}



$sql = "SELECT * FROM bucketlist_items";
$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bucketlist</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <nav>
            <?php include("menu.php"); ?>
        </nav>
    </header>
    <main>
        <h1>Min bucketlist</h1>
        <section class="bucketlist-items">
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<article>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p>Prioritet: " . htmlspecialchars($row['priority']) . "</p>";
                echo "<p>Tillagd: " . htmlspecialchars($row['created_at']) . "</p>";
                echo "</article>";
            }
            ?>
        </section>
        <?php
        if (isset($error)) {
            echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
        }
        ?>
        <form action="bucketlist.php" method="post">
            <label for="name">Vad vill du göra?</label>
            <input type="text" id="name" name="name" required><br>
            <label for="description">Beskrivning</label>
            <input type="text" id="description" name="description" required><br>
            <label for="priority">Prioritet</label>
            <select id="priority" name="priority" required>
                <option value="">Välj prioritet</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select><br>
            <button type="submit">Lägg till</button>
        </form>
    </main>
    <footer>
        <div class="footer-size">
            <p>Laboration 2 - Webbutveckling för Wordpress</p>
        </div>
    </footer>
</body>

</html>