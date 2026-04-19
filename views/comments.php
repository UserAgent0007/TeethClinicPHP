<?php 
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die('Такої сторінки не існує.');
    }

    $id = (int)$_GET['id'];
    $article_id = (int)$id;

    $sql = "SELECT * FROM articles WHERE id = $id";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        die('Помилка запиту: ' . mysqli_error($link));
    }

    if (mysqli_num_rows($result) === 0) {
        die('Такої сторінки не існує.');
    }

    $article_obj = mysqli_fetch_assoc($result);
    
    $sql = "SELECT c.id, c.text, u.login, c.article_id FROM users u JOIN comments c ON u.id=c.user_id JOIN articles a ON a.id = c.article_id WHERE a.id='$id' ORDER BY c.date DESC";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        die('Помилка запиту: ' . mysqli_error($link));
    }

    $errors = [];
    $success = "";
    $text = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === "comments"){
        if (empty($_SESSION['user_id'])) {
            die('Доступ заборонено. Створювати коментарі можуть лише авторизовані користувачі');
        }

        $text =  trim($_POST['text'] ?? '');

        if ($text === '') {
            $errors[] = "Поле 'Текст' не може бути порожнім.";
        }

        if (empty($errors)) {
            $text = mysqli_real_escape_string($link, $text);
            $user_id = (int)$_SESSION['user_id'];
            $article_id = (int)$_GET["id"];

            $create_sql = "INSERT INTO comments (text, user_id, article_id) values ('$text', '$user_id', '$article_id')";

            if (mysqli_query($link, $create_sql)) {
                $success = "коментар успішно додано.";
                $text = '';
                header("Location: index.php?action=comments&id=$article_id");        
            } else {
                $errors[] = "Помилка при додаванні статті: " . mysqli_error($link);
            }
        }
    }
?>

<main>
    <h1>Стаття</h1>
        <div style="border: 2px solid blue;">
            <p><?php echo $article_obj["text"] ?></p>
        </div>
    <h2>Коментарі</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <ul style="display:flex; flex-direction:column; gap: 1em;">
            <?php while($item = mysqli_fetch_assoc($result)): ?>
                <li style="border: 2px solid blue;">
                    <p><?php echo $item["text"] ?></p>
                    <p>Автор: <?php echo $item["login"]; ?></p>
                    <?php if (isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1): ?>
                        <p><a href="index.php?action=comment_update&id=<?php echo $item["id"]; ?>&article_id=<?php echo $item["article_id"]; ?>">Редагувати</a></p>
                        <form action="index.php?action=comment_delete&id=<?php echo $item["id"]; ?>&article_id=<?php echo $item["article_id"]; ?>" method="POST" onsubmit="return confirm('Ви дійсно хочете здійснити видалення');">
                            <button type="submit">Видалити</button>     
                        </form>
                    <?php endif; ?>
                </li>
            <?php endwhile ?>
        </ul>
    <?php else: ?>
        <p>Нема коментарів</p>
    <?php endif; ?>

    <?php if (isset($_SESSION["user_id"])): ?>
        <div class="container_create">
            <h1 class="items_crete_">Додати коментар</h1>

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

            <form action="index.php?action=comments&id=<?php echo $article_id; ?>" method="POST">
                <div class="form-group">
                    <label for="name">Текст коментаря</label>
                    <textarea type="textarea" id="name" name="text" value="<?php echo htmlspecialchars($text); ?>" required></textarea>
                </div>

                <button type="submit" class="btn">Додати коментар</button>
            </form>
        </div>
    <?php endif; ?>
</main>