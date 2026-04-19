<?php
    require_once("db.php");

    if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
        die('Доступ заборонено. Ця сторінка доступна лише адміністратору.');
    }

    if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['article_id']) || !is_numeric($_GET['article_id'])) {
        die('Такої сторінки не існує.');
    }

    $id = (int)$_GET['id'];
    $article_id = (int)$_GET['article_id'];

    $sql = "SELECT * FROM comments WHERE id = $id AND article_id = $article_id";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        die('Помилка запиту: ' . mysqli_error($link));
    }

    if (mysqli_num_rows($result) === 0) {
        die('Такої сторінки не існує.');
    }

    $item = mysqli_fetch_assoc($result);

    $errors = [];
    $success = "";

    $text = $item["text"];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === "comment_update"){
        $text = trim($_POST['text'] ?? '');

        if ($text === '') {
            $errors[] = "Поле 'Текст' не може бути порожнім.";
        }

        if (empty($errors)) {
            $text = mysqli_real_escape_string($link, $text);
            
            $update_sql = "
                UPDATE comments
                SET text = '$text'
                WHERE id = $id
            ";

            if (mysqli_query($link, $update_sql)) {
                $success = "Запис успішно оновлено.";

                $refresh_sql = "SELECT * FROM comments WHERE id = $id";
                $refresh_result = mysqli_query($link, $refresh_sql);

                if ($refresh_result && mysqli_num_rows($refresh_result) > 0) {
                    $item = mysqli_fetch_assoc($refresh_result);
                    $text = $item['text'];

                } 
            } else {
                $errors[] = "Помилка при оновленні: " . mysqli_error($link);
            }
        }
    }
?>

<main>
    <div class="container_create">
        <h1 class="items_crete_">Зміна коментаря</h1>

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

        <form action="index.php?action=comment_update&id=<?php echo $id; ?>&article_id=<?php echo $article_id ?>" method="POST">
            <div class="form-group">
                <label for="name">Текст коментаря</label>
                <textarea type="textarea" id="name" name="text" required><?php echo htmlspecialchars($text); ?></textarea>
            </div>
            <button type="submit" class="btn">Змінити коментар</button>
        </form>
        <p><a href="index.php?action=comments&id=<?php echo $article_id; ?>">Повернутися назад</a></p>
    </div>
</main>