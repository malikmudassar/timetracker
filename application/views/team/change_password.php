<?php 
/* echo('<pre>');
print_r($profile);
die;  */ 
?>
<div class="layout-content">
    <div class="layout-content-body">
        <div class="col-md-4">
            <h2>Change Password</h2><hr>
            <div class="login-body">
                <?php if(isset($errors)){?>
                    <div class="alert alert-danger">
                        <?php print_r($errors);?>
                    </div>
                <?php }?>
                <?php if(isset($success)){?>
                    <div class="alert alert-success">
                        <?php print_r($success);?>
                    </div>
                <?php }?>
                <div class="login-form">
                    <form data-toggle="validator" action="" method="post">
                        
                        <div class="form-group">
                            <label>Old Password</label>
                            <input type="password" name="old_pass" class="form-control" required>
						</div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_pass" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="conf_pass" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"> Update Password </button>
                            <a href="<?php echo base_url(); ?>" class="btn btn-primary"> Cancel </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




