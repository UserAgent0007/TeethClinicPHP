<?php
    require_once("db.php");

    $sql = "SELECT * FROM articles ORDER BY date DESC, id DESC";
    $result = mysqli_query($link, $sql);

    $errors = [];
    $success = "";
    $text = "";

    if (!$result) {
        die('Помилка запиту: ' . mysqli_error($link));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === "articles"){
        if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
            die('Доступ заборонено. Створювати статті може лише адміністратор.');
        }

        $text =  trim($_POST['text'] ?? '');

        if ($text === '') {
            $errors[] = "Поле 'Текст' не може бути порожнім.";
        }

        if (empty($errors)) {
            $text = mysqli_real_escape_string($link, $text);

            $create_sql = "INSERT INTO articles (text) values ('$text')";

            if (mysqli_query($link, $create_sql)) {
                $success = "Товар успішно додано.";
                $text = '';
                header('Location: index.php?action=articles');        
            } else {
                $errors[] = "Помилка при додаванні статті: " . mysqli_error($link);
            }
        }
    }
?>

<main>
    <?php if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === 1): ?>
        <div class="container_create">
            <h1 class="items_crete_">Додати статтю</h1>

            <?php if (!empty($errors)): ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=articles" method="POST">
                <div class="form-group">
                    <label for="name">Текст статті</label>
                    <textarea type="textarea" id="name" name="text" value="<?php echo htmlspecialchars($text); ?>" required></textarea>
                </div>

                <button type="submit" class="btn">Додати статтю</button>
            </form>
        </div>
    <?php endif; ?>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <ul style="display:flex; flex-direction:column; gap: 1em;">
            <?php while($item = mysqli_fetch_assoc($result)): ?>
                <li style="border: 2px solid blue;">
                    <p><?php echo $item["text"] ?></p>
                    <p><a href="index.php?action=comments&id=<?php echo $item["id"]; ?>">Переглянути коментарі</a></p>
                    <?php if (isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1): ?>
                        <p><a href="index.php?action=article_update&id=<?php echo $item["id"]; ?>">Редагувати</a></p>
                        <form action="index.php?action=article_delete&id=<?php echo $item["id"]; ?>" method="POST" onsubmit="return confirm('Ви дійсно хочете здійснити видалення');">
                            <button type="submit">Видалити</button>     
                        </form>
                    <?php endif; ?>
                </li>
            <?php endwhile ?>
        </ul>
    <?php else: ?>
        <p>Нема статей</p>
    <?php endif; ?>
</main>