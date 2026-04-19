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

    $errors = [];
    $success = "";

    $name = $item["name"];
    $description = $item["description"];
    $price = $item["price"];
    $visible = $item["visible"];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === "update_item"){
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $visible = isset($_POST['visible']) ? (int)$_POST['visible'] : 0;

        if ($name === '') {
            $errors[] = "Поле 'Назва' не може бути порожнім.";
        }

        if ($description === '') {
            $errors[] = "Поле 'Опис' не може бути порожнім.";
        }

        if ($price === '') {
            $errors[] = "Поле 'Ціна' не може бути порожнім.";
        } elseif (!is_numeric($price) || (float)$price <= 0) {
            $errors[] = "Поле 'Ціна' повинно бути числом більше 0.";
        }

        if (mb_strlen($name) > 255) {
            $errors[] = "Назва не повинна перевищувати 255 символів.";
        }

        if (mb_strlen($description) > 255) {
            $errors[] = "Опис не повинен перевищувати 255 символів.";
        }

        if ($visible !== 0 && $visible !== 1) {
            $errors[] = "Поле видимості заповнене некоректно.";
        }

        if (empty($errors)) {
            $name = mysqli_real_escape_string($link, $name);
            $description = mysqli_real_escape_string($link, $description);
            $price = (float)$price;
            $visible = (int)$visible;

            $update_sql = "
                UPDATE items
                SET name = '$name',
                    description = '$description',
                    price = '$price',
                    visible = '$visible'
                WHERE id = $id
            ";
            if (mysqli_query($link, $update_sql)) {
                $success = "Запис успішно оновлено.";

                $refresh_sql = "SELECT * FROM items WHERE id = $id";
                $refresh_result = mysqli_query($link, $refresh_sql);

                if ($refresh_result && mysqli_num_rows($refresh_result) > 0) {
                    $item = mysqli_fetch_assoc($refresh_result);
                    $name = $item['name'];
                    $description = $item['description'];
                    $price = $item['price'];
                    $visible = $item['visible'];
                }
            } else {
                $errors[] = "Помилка при оновленні: " . mysqli_error($link);
            }

        }
    }
?>

<main>
    <div class="container_create">
        <h1 class="items_crete_">Змінити товар для гігієни ротової порожнини</h1>

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

        <form action="index.php?action=update_item&id=<?php echo $id; ?>" method="POST">
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

            <div class="form-group">
                <fieldset class="radio-group">
                    <legend>Видимість</legend>

                    <label class="radio-label" for="visible_1">
                        <input 
                            type="radio" 
                            name="visible" 
                            id="visible_1" 
                            value="1"
                            <?php echo ((int)$visible === 1) ? 'checked' : ''; ?>
                        >
                        Видимий
                    </label>

                    <label class="radio-label" for="visible_0">
                        <input 
                            type="radio" 
                            name="visible" 
                            id="visible_0" 
                            value="0"
                            <?php echo ((int)$visible === 0) ? 'checked' : ''; ?>
                        >
                        Прихований
                    </label>
                </fieldset>
            </div>

            <button type="submit" class="btn">Змінити товар</button>
        </form>
    </div>
</main>