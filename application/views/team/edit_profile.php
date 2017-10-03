<div class="layout-content">
    <div class="layout-content-body">
        <div class="col-md-4">
            <h2>My Profile</h2><hr>
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
                            <label>Job Title</label>
                            <input type="text" name="designation" value="<?php echo $profile[0]['designation']?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" value="<?php echo $profile[0]['location']?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                           <span class="form-control"><?php echo $profile[0]['name'];?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <span class="form-control"><?php echo $profile[0]['email'];?></span>
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" name="mobile" value="<?php if(isset($profile[0]['mobile'])){ echo($profile[0]['mobile']); } ?>" class="form-control" required>
                        </div>                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"> Update Employee </button>
                            <a href="<?php echo base_url(); ?>" class="btn btn-primary"> Cancel </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>




