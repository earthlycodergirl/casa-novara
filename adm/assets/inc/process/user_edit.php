<form action="" method="post">
    <input type="hidden" name="user_id" value="<?= $user->uid ?>">
    <div class="radio radio-success">
        <input type="radio" value="1" name="active" id="radio2Yes" <?php if($user->active == 1){ echo 'checked="checked"'; } ?>>
        <label for="radio2Yes">Active</label>
        <input type="radio" value="0" name="active" id="radio2No" <?php if($user->active == 0){ echo 'checked="checked"'; } ?>>
        <label for="radio2No">Inactive</label>
    </div>
    <div class="form-group">
        <label class="" for="lastname">Full Name</label>
        <input type="text" class="form-control" id="lastname" name="name" placeholder="Joe Perez" value="<?= $user->name ?>">
    </div>
    <!-- /control-group -->
    <div class="form-group">
        <label class="" for="email">Email</label>
        <input type="text" class="form-control" id="email" name="email" placeholder="me@myemail.com" value="<?= $user->email ?>">
    </div>
    <!-- /control-group -->

    <div class="form-group">
        <label class="" for="email">Position</label>
        <input type="text" class="form-control" id="position" name="position" placeholder="Recepción" value="<?= $user->position ?>">
        <p class="help-block">Position at MIA Realty</p>
    </div>

    <div class="form-group">
        <label class="" for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="miusuario" value="<?= $user->username ?>">

    </div>
    <div class="well well-sm">
        <div class="row">
            <div class="col-sm-12">
                <p>Leave these fields blank if you do not want to update password.</p>
            </div>
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
                    <label class="" for="password2">Confirm password</label>
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="">
                </div>
            </div>
        </div>
    </div>
    <!-- /control-group -->
    <hr>
    <button type="submit" name="edit-user" class="btn btn-primary btn-lg pull-right">Save User</button>
    <div class="clearfix"></div>
</form>
