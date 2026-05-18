<?php
// ================================================================
// MEDICINE MODEL
// ================================================================

function getMedicines($conn)
{
    $sql = "SELECT 
                m.*, 
                c.name AS category_name, 
                c.category_type
            FROM medicines m
            JOIN categories c ON m.category_id = c.id
            ORDER BY m.id DESC";

    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getMedicine($conn, $id)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT 
            m.*, 
            c.name AS category_name, 
            c.category_type
         FROM medicines m
         JOIN categories c ON m.category_id = c.id
         WHERE m.id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    mysqli_stmt_close($stmt);

    return $row;
}

function searchMedicinesByNameAndCategory($conn, $q = '', $categoryId = 0)
{
    $sql = "SELECT 
                m.*, 
                c.name AS category_name, 
                c.category_type
            FROM medicines m
            JOIN categories c ON m.category_id = c.id
            WHERE 1";

    $params = [];
    $types  = '';

    if ($q !== '') {
        $sql .= " AND m.name LIKE ?";
        $params[] = "%$q%";
        $types .= 's';
    }

    if ($categoryId > 0) {
        $sql .= " AND m.category_id = ?";
        $params[] = $categoryId;
        $types .= 'i';
    }

    $sql .= " ORDER BY m.id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);

    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $rows;
}

function searchMedicines($conn, $q = '', $vendor = '', $genre = '', $type = '')
{
    $sql = "SELECT 
                m.*, 
                c.name AS category_name, 
                c.category_type
            FROM medicines m
            JOIN categories c ON m.category_id = c.id
            WHERE 1";

    $params = [];
    $types  = '';

    if ($q !== '') {
        $sql .= " AND m.name LIKE ?";
        $params[] = "%$q%";
        $types .= 's';
    }

    if ($vendor !== '') {
        $sql .= " AND m.vendor_name LIKE ?";
        $params[] = "%$vendor%";
        $types .= 's';
    }

    if ($genre !== '') {
        $sql .= " AND c.name LIKE ?";
        $params[] = "%$genre%";
        $types .= 's';
    }

    if ($type !== '') {
        $sql .= " AND c.category_type = ?";
        $params[] = $type;
        $types .= 's';
    }

    $sql .= " ORDER BY m.id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);

    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $rows;
}

function addMedicine($conn, $name, $categoryId, $vendor, $price, $stock, $description, $image = null)
{
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO medicines
            (name, category_id, vendor_name, price, availability, description, image_path)
         VALUES
            (?, ?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'sisdiss',
        $name,
        $categoryId,
        $vendor,
        $price,
        $stock,
        $description,
        $image
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}

function updateMedicine($conn, $id, $name, $categoryId, $vendor, $price, $stock, $description, $image = null)
{
    if ($image) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE medicines
             SET name = ?, category_id = ?, vendor_name = ?, price = ?, 
                 availability = ?, description = ?, image_path = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            'sisdissi',
            $name,
            $categoryId,
            $vendor,
            $price,
            $stock,
            $description,
            $image,
            $id
        );
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE medicines
             SET name = ?, category_id = ?, vendor_name = ?, price = ?, 
                 availability = ?, description = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            'sisdisi',
            $name,
            $categoryId,
            $vendor,
            $price,
            $stock,
            $description,
            $id
        );
    }

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}

function medicineInPendingOrder($conn, $id)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT oi.id
         FROM order_items oi
         JOIN orders o ON oi.order_id = o.id
         WHERE oi.medicine_id = ?
         AND o.status = 'pending'
         LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $has = mysqli_stmt_num_rows($stmt) > 0;

    mysqli_stmt_close($stmt);

    return $has;
}

function deleteMedicine($conn, $id)
{
    if (medicineInPendingOrder($conn, $id)) {
        return false;
    }

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM medicines WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, 'i', $id);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}

function reduceMedicineStock($conn, $medicineId, $qty)
{
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE medicines
         SET availability = availability - ?
         WHERE id = ?
         AND availability >= ?"
    );

    mysqli_stmt_bind_param($stmt, 'iii', $qty, $medicineId, $qty);

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok;
}

function countMedicines($conn)
{
    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS c FROM medicines"
    );

    return mysqli_fetch_assoc($result)['c'] ?? 0;
}
?>