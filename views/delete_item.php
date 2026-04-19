<?php
    require_once("db.php");

    if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
        die('Доступ заборонено. Ця сторінка доступна лише адміністратору.');
    }

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die('Такої сторінки не існує.');
    }

    $id = (int)$_GET['id'];

    $sql = "SELECT * FROM items WHERE id = $id";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        die('Помилка запиту: ' . mysqli_error($link));
    }

    if (mysqli_num_rows($result) === 0) {
        die('Такої сторінки не існує.');
    }

    $item = mysqli_fetch_assoc($result);

    $name = $item["name"];
    $description = $item["description"];
    $price = $item["price"];

    $success = '';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === "delete_item") {
        $delete_sql = "DELETE FROM items WHERE id = $id";

        if (mysqli_query($link, $delete_sql)) {
            $success = "засіб успішно видалено.";
        } else {
            $error = "Помилка при видаленні: " . mysqli_error($link);
        }
    }
?>

<main>
    <div class="container_create">
        <h1 class="items_crete_">Видалення товару</h1>
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

            <div class="form-group">
                <h3>Назва товару</h3>
                <p><?php echo htmlspecialchars($name); ?></p>
            </div>

            <div class="form-group">
                <h3>Опис товару</h3>
                <p><?php echo htmlspecialchars($description); ?></p>
            </div>

            <div class="form-group">
                <h3>Ціна</h3>
                <p><?php echo htmlspecialchars($price); ?></p>
            </div>
            <form action="index.php?action=delete_item&id=<?php echo $id ?>" method="POST" onsubmit="return confirm('Ви дійсно хочете здійснити видалення');">
                <button type="submit">Видалити</button>
            </form>
    </div>
</main>

