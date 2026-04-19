<?php
    require_once("db.php");

    if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
        die('Доступ заборонено. Ця сторінка доступна лише адміністратору.');
    }

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die('Такої сторінки не існує.');
    }

    $id = (int)$_GET['id'];

    $sql = "SELECT * FROM articles WHERE id = $id";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        die('Помилка запиту: ' . mysqli_error($link));
    }

    if (mysqli_num_rows($result) === 0) {
        die('Такої сторінки не існує.');
    }

    $item = mysqli_fetch_assoc($result);

    $success = '';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === "article_delete") {
        $delete_sql = "DELETE FROM articles WHERE id = $id";

        if (mysqli_query($link, $delete_sql)) {
            $success = "статтю успішно видалено.";
            header('Location: index.php?action=articles'); 
        } else {
            $error = "Помилка при видаленні: " . mysqli_error($link);
        }
    }
?>

<?php echo $error; ?>