<?php
//include 'classes/build/sql.class.php';
$getPerm = new SqlIt("SELECT * FROM permissions","select",array());
?>

<form action="users" method="post">
    <div class="radio radio-success">
        <input type="radio" checked="checked" value="1" name="active" id="radio2Yes">
        <label for="radio2Yes">Active</label>
        <input type="radio" value="0" name="active" id="radio2No">
        <label for="radio2No">Inactive</label>
    </div>
    <div class="form-group">
        <label class="" for="lastname">Full Name</label>
        <input type="text" class="form-control" id="lastname" name="name" placeholder="Joe Perez">
    </div>
    <!-- /control-group -->
    <div class="form-group">
        <label class="" for="email">Email</label>
        <input type="text" class="form-control" id="email" name="email" placeholder="me@myemail.com">
    </div>
    <!-- /control-group -->

    <div class="form-group">
        <label class="" for="email">Position</label>
        <input type="text" class="form-control" id="position" name="position" placeholder="Recepción">
        <p class="help-block">Position at Kiin Realty</p>
    </div>

    <div class="form-group">
        <label class="" for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="miusuario">
        <p class="help-block">The username cannot be edited </p>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="" for="password1">Password</label>
                <input type="password" class="form-control" id="password1" name="password" placeholder="">
            </div>
            <!-- /control-group -->
        </div>
        <div class="col-sm-6">

            <div class="form-group">
                <label class="" for="password2">Confirm Password</label>
                <input type="password" class="form-control" id="password2" name="password2" placeholder="">
            </div>
        </div>
    </div>
    <!-- /control-group -->
    <hr>
    <button type="submit" name="add-user" class="btn btn-primary btn-lg pull-right">Add User</button>
    <div class="clearfix"></div>
</form>
