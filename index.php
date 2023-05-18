<?php
session_start();

// Set default values if session variables are not set
if (!isset($_SESSION['price_range'])) {
    $_SESSION['price_range'] = 13600000;
}

if (!isset($_SESSION['sort_option'])) {
    $_SESSION['sort_option'] = 'ASC';
}

if (isset($_POST['price_filter_submit'])) {
    $_SESSION['price_range'] = $_POST['price_range'];
}

if (isset($_POST['sort_submit'])) {
    $_SESSION['sort_option'] = $_POST['sort_option'];
}

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}

if (isset($_GET['logout'])) {
    // session_destroy();
    unset($_SESSION["cart_item"]);
    unset($_SESSION['username']);
    header("location: login.php");
}

require_once("dbcontroller.php");
$db_handle = new DBController();

/* Check if action is set */
if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            if (!empty($_POST["quantity"])) {
                $productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
                $itemArray = array($productByCode[0]["code"] => array(
                    'name' => $productByCode[0]["name"],
                    'code' => $productByCode[0]["code"],
                    'quantity' => $_POST["quantity"],
                    'price' => $productByCode[0]["price"],
                    'image' => $productByCode[0]["image"]
                ));

                if (!empty($_SESSION["cart_item"])) {
                    if (array_key_exists($productByCode[0]["code"], $_SESSION["cart_item"])) {
                        $_SESSION["cart_item"][$productByCode[0]["code"]]["quantity"] += $_POST["quantity"];
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $cart_data = serialize($_SESSION['cart_item']);
                $update_query = "UPDATE users SET favourites = '$cart_data' WHERE username = '$username'";
                $db_handle->runQuery($update_query);
            }
            break;

        case "remove":
            if (!empty($_SESSION["cart_item"]) && isset($_GET["code"])) {
                $code = $_GET["code"];
                if (array_key_exists($code, $_SESSION["cart_item"])) {
                    unset($_SESSION["cart_item"][$code]);
                    if (empty($_SESSION["cart_item"])) {
                        unset($_SESSION["cart_item"]);
                    }
                }
            }
            break;

        case "empty":
            unset($_SESSION["cart_item"]);
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $update_query = "UPDATE users SET favourites = '' WHERE username = '$username'";
                $db_handle->runQuery($update_query);
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="style2.css">
</head>
<body>

<div class="header">
    <h2>Home Page</h2>
</div>
<div class="content">
    <!-- notification messages -->
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="error success">
            <h3>
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </h3>
        </div>
    <?php endif ?>

    <!-- logged in user information -->
    <?php if (isset($_SESSION['username'])) : ?>
        <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
        <p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>

    </div>
    <!-- Start of code (shopping)-->
    <div id="shopping-cart">
        <div class="txt-heading">Shopping Cart</div>

        <a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>
        <?php
        if (isset($_SESSION["cart_item"])) {
            $total_quantity = 0;
            $total_price = 0;
            ?>
            <table class="tbl-cart" cellpadding="10" cellspacing="1">
                <tbody>
                <tr>
                    <th style="text-align:left;">Name</th>
                    <th style="text-align:left;">Code</th>
                    <th style="text-align:right;" width="5%">Quantity</th>
                    <th style="text-align:right;" width="10%">Unit Price</th>
                    <th style="text-align:right;" width="10%">Price</th>
                    <th style="text-align:center;" width="5%">Remove</th>
                </tr>
                <?php
                foreach ($_SESSION["cart_item"] as $item) {
                    $item_price = $item["quantity"] * $item["price"];
                    ?>
                    <tr>
                        <td><img src="<?php echo $item["image"]; ?>" class="cart-item-image"/><?php echo $item["name"]; ?></td>
                        <td><?php echo $item["code"]; ?></td>
                        <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                        <td style="text-align:right;"><?php echo "$ " . $item["price"]; ?></td>
                        <td style="text-align:right;"><?php echo "$ " . number_format($item_price, 2); ?></td>
                        <td style="text-align:center;"><a
                                    href="index.php?action=remove&code=<?php echo $item["code"]; ?>"
                                    class="btnRemoveAction"><img src="product-images/icon-delete.png"
                                                                 alt="Remove Item"/></a></td>
                    </tr>
                    <?php
                    $total_quantity += $item["quantity"];
                    $total_price += ($item["price"] * $item["quantity"]);
                }
                ?>

                <tr>
                    <td colspan="2" align="right">Total:</td>
                    <td align="right"><?php echo $total_quantity; ?></td>
                    <td align="right" colspan="2"><strong><?php echo "$ " . number_format($total_price, 2); ?></strong>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="6" align="right">
                        <?php
                        if (isset($_SESSION['username'])) {
                            $username = $_SESSION['username'];
                            $query = "SELECT favourites FROM users WHERE username = '$username'";
                            $result = $db_handle->runQuery($query);
                            if (!empty($result[0]["favourites"])) {
                                echo '<span class="favorited">Favorited</span>';
                            } else {
                                echo '<a href="index.php?code=' . $product_code . '">Add to Favorites</a>';
                            }
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <div class="no-records">Your Cart is Empty</div>
            <?php
        }
        ?>
    </div>
    <div id="product-grid">
        <div class="txt-heading">Products</div>

        <!-- Price filter -->
        <div class="price-filter">
            <form method="post" action="">
                <label for="price-slider">Price Range:</label>
                <input id="price-slider" type="range" name="price_range" min="3000" max="13700000" step="1000"
                       value="<?php echo isset($_POST['price_range']) ? $_POST['price_range'] : '13700000'; ?>">
                <span class="price-value"><?php echo number_format(isset($_POST['price_range']) ? $_POST['price_range'] : '1400000000'); ?></span>

                <input type="submit" name="price_filter_submit" value="Filter">
            </form>
        </div>
        <!-- Filter ends -->

        <!-- Sort by price -->
        <div class="sort-by">
            <form method="post" action="index.php">
                <label for="sort-by">Sort by Price:</label>
                <select name="sort_option" id="sort-by">
                    <option value="ASC" <?php if (isset($_POST['sort_option']) && $_POST['sort_option'] == 'ASC') echo ' selected'; ?>>
                        Low to High
                    </option>
                    <option value="DESC" <?php if (isset($_POST['sort_option']) && $_POST['sort_option'] == 'DESC') echo ' selected'; ?>>
                        High to Low
                    </option>
                </select>
                <input type="submit" name="sort_submit" value="Sort">
            </form>
        </div>

        <?php
        // Get products from the database and apply filters
        $query = "SELECT * FROM tblproduct";
        if (isset($_POST['price_filter_submit'])) {
            $_SESSION['price_range'] = $_POST['price_range'];
        }
        if (isset($_SESSION['price_range'])) {
            $price_range = $_SESSION['price_range'];
            $query .= " WHERE price <= $price_range";
        }
        if (isset($_POST['sort_submit'])) {
            $sort_option = $_POST['sort_option'];
            $_SESSION['sort_option'] = $sort_option;
        }
        if (isset($_SESSION['sort_option'])) {
            $sort_option = $_SESSION['sort_option'];
            $query .= " ORDER BY price $sort_option";
        }
        $product_array = $db_handle->runQuery($query);
        ?>
    </div>
    <div id="product-grid">
        <?php
        if (!empty($product_array)) {
            foreach ($product_array as $key => $value) {
                ?>
                <div class="product-item">
                    <form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
                        <div class="product-image">
                            <img src="<?php echo $product_array[$key]["image"]; ?>">
                        </div>
                        <div class="product-tile-footer">
                            <div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
                            <div class="product-price"><?php echo "$" . $product_array[$key]["price"]; ?></div>
                            <div class="cart-action">
                                <input type="text" class="product-quantity" name="quantity" value="1" size="2"/>
                                <input type="submit" value="Add to Cart" class="btnAddAction"/>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "No records found.";
        }
        ?>
    </div>

    <?php endif ?>
    <!-- End of code -->
</div>
</body>
</html>