<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items List</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .td a[href*="update_i.php"] {
            display: inline-block;
            padding: 10px 10px;
            background-color: #6C4E31;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .td a[href*="update_i.php"]:hover {
            background-color: #72BF78;
            cursor: pointer;
            transition: background-color 0.2s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .condition-excellent {
            color: gold;
            font-weight: bold;
            padding: 5px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            /* Text outline effect */
        }

        .condition-good {
            color: greenyellow;
            font-weight: bold;
            padding: 5px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            /* Text outline effect */
        }

        .condition-fair {
            color: orange;
            font-weight: bold;
            padding: 5px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            /* Text outline effect */
        }

        .condition-bad {
            color: red;
            font-weight: bold;
            padding: 5px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            /* Text outline effect */
        }
    </style>
    <?php
    include("nav.php");
    include("database.php");


    $sql = "SELECT 
        i.item_num,
        i.description,
        i.asking_price,
        i.critiqued_comments,
        i.condition,
        i.item_type,
        i.ClientNumber,
        c.givenName,
        c.lastName
    FROM items i
    LEFT JOIN allclients c ON i.ClientNumber = c.ClientNumber
    WHERE i.is_sold = 0 ORDER BY i.item_num DESC;";

    $query = mysqli_query($conn, $sql);

    if (!$query) {
        echo "Error: " . mysqli_error($conn);
    }
    ?>
</head>

<body>
    <div class="table-wrapper">
        <table class="container">
            <thead>
                <tr>
                    <th class="th" colspan="6"><a href="insert_i.php">Add Item</a></th>
                    <th align="right">Stillwater Antique Available Items</th>
                </tr>
                <tr align="left">
                    <th width="150px">Name / Description</th>
                    <th width="150px">Item Owner</th>
                    <th width="50px">Condition</th>
                    <th width="90px">Asking Price</th>
                    <th width="200px">Comments</th>
                    <th width="45px">Type</th>
                    <th align="center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($result = mysqli_fetch_assoc($query)) {
                    $formatPrice = number_format($result['asking_price'], 2);
                    $conditionClass = '';
                    switch (strtolower($result['condition'])) {
                        case 'excellent':
                            $conditionClass = 'condition-excellent';
                            break;
                        case 'good':
                            $conditionClass = 'condition-good';
                            break;
                        case 'fair':
                            $conditionClass = 'condition-fair';
                            break;
                        case 'bad':
                            $conditionClass = 'condition-bad';
                            break;
                    }
                ?>
                    <tr align="left">
                        <td><?php echo $result['description']; ?></td>
                        <td><?php echo !empty($result['givenName']) || !empty($result['lastName']) ? htmlspecialchars($result['givenName'] . ' ' . $result['lastName']) : 'Stillwater Antique'; ?></td>
                        <td class="<?php echo $conditionClass; ?>">
                            <?php echo $result['condition']; ?>
                        </td>
                        <td><span style="color: green;">₱</span> <?php echo $formatPrice; ?></td>
                        <td><?php echo $result['critiqued_comments']; ?></td>
                        <td><?php echo $result['item_type']; ?></td>
                        <td align="center" class="action-buttons">
                            <a class='bx bxs-edit editbtn' href='update_i.php?action=edit&item_num=<?php echo $result["item_num"]; ?>'>Edit</a>
                            <a class='bx bxs-trash deletebtn' href='items.php?action=delete&item_num=<?php echo $result["item_num"]; ?>' onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <?php
            if (isset($_GET['action']) && isset($_GET['item_num'])) {
                $action = $_GET['action'];
                $item_num = $_GET['item_num'];

                if ($action == 'delete') {
                    $sql = "DELETE FROM items WHERE item_num = '$item_num'";
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('Deletion is successful.'); window.location='items.php';</script>";
                    } else {
                        echo "<script>alert('Failed to delete the item.'); window.location='items.php';</script>";
                    }
                }
            }
            ?>
        </table>
    </div>
</body>

</html>