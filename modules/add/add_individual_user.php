<?php
if (!App::isLoggedIn()) App::redirectTo("?");
require_once WPATH . "modules/classes/Users.php";
$users = new Users();
if (!empty($_POST)) {
    $_SESSION['individual_firstname'] = $_POST['firstname'];
    $_SESSION['individual_lastname'] = $_POST['lastname'];
    $_SESSION['individual_gender'] = $_POST['gender'];
    $_SESSION['individual_idnumber'] = $_POST['idnumber'];
    $_SESSION['user_type'] = 'INDIVIDUAL USER';

    if (isset($_SESSION['individual_firstname'])) {
        App::redirectTo("?add_contact&ref_type=" . $_SESSION['user_type']);
    }
}
?>

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="?home" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="?view_individual_users">Individual Users</a> <a href="?add_individual_user" class="current">Add Individual User</a> </div>
        <h1>Add Individual User</h1>
    </div>
    <div class="container-fluid"><hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">          
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            <input type="hidden" name="action" value="add_individual_user"/>
                            <input type="hidden" name="createdby" value="<?php echo 01; //  echo $_SESSION['userid'];     ?>"/>

                            <div class="control-group">
                                <label class="control-label">First Name</label>
                                <div class="controls">
                                    <input type="text" name="firstname" id="firstname">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Last Name</label>
                                <div class="controls">
                                    <input type="text" name="lastname" id="lastname">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Gender</label>
                                <div class="controls">
                                    <select name="gender" id="gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">ID/Passport Number</label>
                                <div class="controls">
                                    <input type="text" name="idnumber" id="idnumber">
                                </div>
                            </div>
                            <div class="form-actions">
                                <input type="submit" value="Save" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>