<?php
if (!App::isLoggedIn())
    App::redirectTo("?");
require_once WPATH . "modules/classes/Transactions.php";
require_once WPATH . "modules/classes/Books.php";
require_once WPATH . "modules/classes/Users.php";
$users = new Users();
$books = new Books();
$transactions = new Transactions();

unset($_SESSION['transaction_detail']);
?>

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Transaction Details</a> </div>
        <h1>Transaction Details</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>Transaction Details</h5>
                        <?php require_once('modules/menus/sub-sub-menu-buttons.php'); ?>
                    </div>
                    <div class="widget-content nopadding">

                        <table class="table table-bordered data-table">
                            <tbody>
                                <tr>
                                    <th><h5>ID</h5></th>
                                    <th><h5>Transaction ID</h5></th>
                                    <th><h5>Book ID</h5></th>
                                    <th><h5>Print Type</h5></th>
                                    <th><h5>Unit Price</h5></th>
                                    <th><h5>Quantity</h5></th>
                                    <th><h5>Publisher</h5></th>
                                    <th><h5>Delivery Status</h5></th>
                                </tr>

                                <?php
                                if (!empty($_POST)) {
                                    $transaction_details[] = $transactions->execute();
                                } else {
                                    if (is_menu_set('view_individual_transaction') != "") {
                                        $transaction_id = $_GET['code'];
                                        $transaction_details[] = $transactions->getTransactionDetails($transaction_id);
                                    } else {
                                        $transaction_details[] = $transactions->getAllTransactionDetails();
                                    }
                                }
                                if (isset($_SESSION['no_records']) AND $_SESSION['no_records'] == true) {
                                    echo "<tr>";
                                    echo "<td>  No record found...</td>";
                                    echo "<td> </td>";
                                    echo "<td> </td>";
                                    echo "<td> </td>";
                                    echo "<td> </td>";
                                    echo "<td> </td>";
                                    echo "<td> </td>";
                                    echo "<td> </td>";
                                    echo "</tr>";
                                    unset($_SESSION['no_records']);
                                } else if (isset($_SESSION['yes_records']) AND $_SESSION['yes_records'] == true) {
                                    foreach ($transaction_details as $key => $value) {
                                        $inner_array[$key] = json_decode($value, true); // this will give key val pair array
                                        foreach ((array) $inner_array[$key] as $key2 => $value2) {
                                            if ($value2['status'] == 1000) {
                                                $delivery_status = "DELETED";
                                            } else if ($value2['status'] == 1001) {
                                                $delivery_status = "AWAITING APPROVAL";
                                            } else if ($value2['status'] == 1002) {
                                                $delivery_status = "NOT ACTIVE";
                                            } else if ($value2['status'] == 1021) {
                                                $delivery_status = "ACTIVE";
                                            } else if ($value2['status'] == 1010) {
                                                $delivery_status = "APPROVAL REJECTED";
                                            } else if ($value2['status'] == 1011) {
                                                $delivery_status = "DELIVERY IN PROGRESS";
                                            } else if ($value2['status'] == 1012) {
                                                $delivery_status = "ASSIGNED";
                                            } else if ($value2['status'] == 1030) {
                                                $delivery_status = "DELIVERY REJECTED";
                                            } else if ($value2['status'] == 1031) {
                                                $delivery_status = "DELIVERY CONFIRMED";
                                            }
                                            
                                            $book_details = $books->fetchBookDetails($value2['book_id']);
                                            $publisher_details = $users->fetchPublisherDetails($value2['publisher']);
                                            
                                            echo "<tr>";
                                            echo "<td>" . $value2['id'] . "</td>";
//                                            echo "<td> <a href='?individual_transaction_detail&code=" . $value2['id'] . "'>" . $value2['transaction_id'] . "</td>";
                                            echo "<td>" . $value2['transaction_id'] . "</td>";
                                            echo "<td>" . $book_details['title'] . "</td>";
                                            echo "<td>" . $value2['print_type'] . "</td>";
                                            echo "<td>" . $value2['unit_price'] . "</td>";
                                            echo "<td>" . $value2['quantity'] . "</td>";
                                            echo "<td>" . $publisher_details['company_name'] . "</td>";
                                            echo "<td>" . $delivery_status . "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    unset($_SESSION['yes_records']);
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>