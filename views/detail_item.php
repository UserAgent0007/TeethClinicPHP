<?php
    require_once("db.php");

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die('Такої сторінки не існує.');
    }

    $id = (int)$_GET['id'];

    $sql = "SELECT * FROM items WHERE id = $id";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        die('Помилка запиту: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) === 0) {
        die('Такої сторінки не існує.');
    }

    $item = mysqli_fetch_assoc($result);

    $name = $item["name"];
    $description = $item["description"];
    $price = $item["price"];
?>

<main>
    <div class="container_create">
        <h1 class="items_crete_">Інформація про товар гігієни ротової порожнини</h1>

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
    </div>
</main>