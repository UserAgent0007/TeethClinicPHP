<?php
    require_once("db.php");

    if (empty($_SESSION['user_id'])) {
        die('Доступ заборонено. Увійдіть в акаунт.');
    }

    $errors = [];
    $success = '';

    $name =  '';
    $description = '';
    $price = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'create_item') {

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = trim($_POST['price'] ?? '');
        
        if ($name === '') {
            $errors[] = "Поле 'Назва товару' не може бути порожнім.";
        }

        if ($description === '') {
            $errors[] = "Поле 'Опис товару' не може бути порожнім.";
        }

        if ($price === '') {
            $errors[] = "Поле 'Ціна' не може бути порожнім.";
        } elseif (!is_numeric($price) || (float)$price <= 0) {
            $errors[] = "Поле 'Ціна' повинно бути числом більше 0.";
        }

        if (mb_strlen($name) > 255) {
            $errors[] = "Назва товару не повинна перевищувати 255 символів.";
        }

        if (mb_strlen($description) > 255) {
            $errors[] = "Опис товару не повинен перевищувати 255 символів.";
        }

        if (empty($errors)){
            $user_id = (int)$_SESSION['user_id'];
            $is_admin = !empty($_SESSION['is_admin']) ? (int)$_SESSION['is_admin'] : 0;
            $visible = ($is_admin === 1) ? 1 : 0;

            $name = mysqli_real_escape_string($link, $name);
            $description = mysqli_real_escape_string($link, $description);
            $price = (float)$price;

            $sql = "INSERT INTO items (name, description, price, author_id, visible)
                VALUES ('$name', '$description', '$price', '$user_id', '$visible')";

            if (mysqli_query($link, $sql)) {
                $success = "Товар успішно додано.";
                $name = '';
                $description = '';
                $price = '';
            } else {
                $errors[] = "Помилка при додаванні товару: " . mysqli_error($link);
            }

        }
    }
?>

<main>
    <div class="container_create">
        <h1 class="items_crete_">Додати товар для гігієни ротової порожнини</h1>

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

        <form action="index.php?action=create_item" method="POST">
            <div class="form-group">
                <label for="name">Назва товару</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Опис товару</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Ціна</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01" value="<?php echo htmlspecialchars($price); ?>" required>
            </div>

            <button type="submit" class="btn">Додати товар</button>
        </form>
    </div>
</main>