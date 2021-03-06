<?php
if (!App::isLoggedIn())
    App::redirectTo("?");
require_once WPATH . "modules/classes/Users.php";
require_once WPATH . "modules/classes/Transactions.php";
$transactions = new Transactions();
$users = new Users();
$code = $_GET['code'];
$update_type = $_GET['update_type'];
$item = $_GET['item'];

if ($update_type == "assign") {
    if (!empty($_POST)) {
        if ($item == 'transaction') {
            if (strtoupper($_POST['publisher_type']) == 'COMPANY') {
                $publisher = $_POST['publisher'];
            } else if (strtoupper($_POST['publisher_type']) == 'SELF') {
                $publisher = $_POST['self_publisher'];
            }
            $success = $transactions->updatePiracy($_POST['publisher_type'], $publisher, $code, $update_type);
        } else if ($item == 'piracy') {
            if (strtoupper($_POST['publisher_type']) == 'COMPANY') {
                $publisher = $_POST['publisher'];
            } else if (strtoupper($_POST['publisher_type']) == 'SELF') {
                $publisher = $_POST['self_publisher'];
            }
            $success = $transactions->assignPiracy($_POST['publisher_type'], $publisher, $code);
            App::redirectTo("?view_piracy_reports");
        }
    }
} else if ($update_type == "delete") {
    if ($item == 'transaction') {
        if (strtoupper($_POST['publisher_type']) == 'COMPANY') {
            $publisher = $_POST['publisher'];
        } else if (strtoupper($_POST['publisher_type']) == 'SELF') {
            $publisher = $_POST['self_publisher'];
        }
        $success = $transactions->updatePiracy($_POST['publisher_type'], $publisher, $code, $update_type);
    } else if ($item == 'piracy') {
        $success = $transactions->deletePiracy($code);
        App::redirectTo("?view_piracy_reports");
    }
} else if ($update_type == "close") {
    if ($item == 'inbox_message') {
        $success = $transactions->closeInboxMessage($code);
        App::redirectTo("?view_inbox_messages");
    } else if ($item == 'piracy') {
        $success = $transactions->closePiracy($code);
        App::redirectTo("?view_piracy_reports");
    }
} else if ($update_type == "approve") {
    if ($item == 'book') {
        $success = $transactions->approveBook($code);
        App::redirectTo("?view_books");
    } else if ($item == 'transaction_item') {
//        $success = $transactions->approveTransactionItem($code);
//        App::redirectTo("?view_transactions");
        if (!empty($_POST)) {
            $success = $transactions->approveTransactionItem($code, $_POST['approval_comment'], $_POST['delivery_time']);
            App::redirectTo("?view_transactions");
        }
    } else if ($item == 'delivery') {
        $success = $transactions->approveItemDelivery($code);
//        App::redirectTo("?view_transactions");
    }
} else if ($update_type == "reject") {
    if ($item == 'book') {
        $success = $transactions->rejectBook($code);
        App::redirectTo("?view_books");
    } else if ($item == 'transaction_item') {
//        $success = $transactions->rejectTransactionItem($code);
//        App::redirectTo("?view_transactions");
        if (!empty($_POST)) {
            $success = $transactions->rejectTransactionItem($code, $_POST['approval_comment']);
            App::redirectTo("?view_transactions");
        }
    } else if ($item == 'delivery') {
        $success = $transactions->rejectItemDelivery($code);
//        App::redirectTo("?view_transactions");
    }
} else if ($update_type == "activate") {
    if ($item == 'book') {
        $success = $transactions->activateBook($code);
        App::redirectTo("?view_books");
    }
} else if ($update_type == "deactivate") {
    if ($item == 'book') {
        $success = $transactions->deactivateBook($code);
        App::redirectTo("?view_books");
    }
}
?>

<?php if ($update_type == "assign" AND $item == 'piracy') { ?>
    <div id="content">
        <div id="content-header">    
            <?php if ($item == 'transaction') { ?>
                <div id="breadcrumb"> <a href="?home" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="?view_books">Books</a> <a href="?add_book" class="current">Add Book</a> </div>
                <h1>Assign Transaction</h1>
            <?php } else if ($item == 'piracy') { ?>
                <div id="breadcrumb"> <a href="?home" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="?view_piracy_reports">Piracy Reports</a> </div>
                <h1>Assign Piracy Case</h1>
            <?php } ?>
        </div>

        <?php
        if (isset($_SESSION['add_success'])) {
            echo "Record successfully added...";
            unset($_SESSION['add_success']);
        } else if (!empty($_POST)) {
            echo "Error adding record...";
        }
        ?>

        <div class="container-fluid"><hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">          
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" name="basic_validate" id="basic_validate" novalidate="novalidate">                            
                                <!--<input type="hidden" name="action" value="add_role"/>-->
                                <div class="control-group">
                                    <label class="control-label">Publisher Type</label>
                                    <div class="controls">
                                        <select name="publisher_type">
                                            <option value="none">Select publisher type</option>
                                            <option value="company">Company/Business</option>
                                            <option value="self">Self Publisher</option>
                                        </select>
                                    </div>
                                </div>

                                --------------To implement show-hide for this------------------------------

                                <?php // if ($publisher_type == "company") {       ?>
                                <div class="control-group">
                                    <label class="control-label">Publisher</label>
                                    <div class="controls">
                                        <select name="publisher" id="publisher">
                                            <?php echo $users->getPublishers(); ?>
                                        </select>
                                    </div>
                                </div>
                                <?php // } else if ($publisher_type == "company") {       ?>
                                <div class="control-group">
                                    <label class="control-label">Publisher</label>
                                    <div class="controls">
                                        <select name="self_publisher">
                                            <?php echo $users->getSelfPublishers(); ?>
                                        </select>
                                    </div>
                                </div>
                                <?php // }       ?>

                                ---------------------------------------------------------------------------

                                <div class="form-actions">
                                    <input type="submit" value="Assign Task" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>

<?php } else if ($update_type == "approve" AND $item == 'transaction_item') { ?>
    <div id="content">
        <div id="content-header">   
            <div id="breadcrumb"> <a href="?home" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="?view_books">Books</a> <a href="?add_book" class="current">Add Book</a> </div>
            <h1>Approve Transaction</h1>
        </div>

        <?php
        if (isset($_SESSION['add_success'])) {
            echo "Record successfully added...";
            unset($_SESSION['add_success']);
        } else if (!empty($_POST)) {
            echo "Error adding record...";
        }
        ?>

        <div class="container-fluid"><hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">          
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" name="basic_validate" id="basic_validate" novalidate="novalidate">                            
                                <!--<input type="hidden" name="action" value="add_role"/>-->
                                <div class="control-group">
                                    <label class="control-label">Delivery Time (Days)</label>
                                    <div class="controls">
                                        <select name="delivery_time">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Approval Comment</label>
                                    <div class="controls">
                                        <textarea class="span11" name="approval_comment" placeholder="Transaction approved." required></textarea>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <input type="submit" value="Approve Transaction" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>


<?php } else if ($update_type == "reject" AND $item == 'transaction_item') { ?>
    <div id="content">
        <div id="content-header">    
            <div id="breadcrumb"> <a href="?home" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="?view_books">Books</a> <a href="?add_book" class="current">Add Book</a> </div>
            <h1>Reject Transaction</h1>
        </div>

        <?php
        if (isset($_SESSION['add_success'])) {
            echo "Record successfully added...";
            unset($_SESSION['add_success']);
        } else if (!empty($_POST)) {
            echo "Error adding record...";
        }
        ?>

        <div class="container-fluid"><hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">          
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" name="basic_validate" id="basic_validate" novalidate="novalidate">                            
                                <!--<input type="hidden" name="action" value="add_role"/>-->

                                <div class="control-group">
                                    <label class="control-label">Rejection Comment</label>
                                    <div class="controls">
                                        <textarea class="span11" name="approval_comment" placeholder="Your transaction has been rejected because ......" required></textarea>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <input type="submit" value="Reject Transaction" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
<?php } ?>

