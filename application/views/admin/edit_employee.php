<div class="layout-content">
    <div class="layout-content-body">
        
            <h2>Employee Details</h2><hr>
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>User Role</label>
                            <select class="form-control" name="user_role">
                                <?php
                                if(count($user_roles)>0) {
                                    for($i=0;$i<count($user_roles);$i++)
                                    {?>
                                        <option value="<?php echo $user_roles[$i]['id']?>"
                                            <?php 
                                            if($user_roles[$i]['id']==$user['role']){
                                                echo 'selected';
                                            }
                                            ?>
                                            ><?php echo $user_roles[$i]['name']?></option>
                                    <?php
                                    }}
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required value="<?php echo $user['email']?>">
                        </div>
                        
                        <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" name="mobile" class="form-control" required value="<?php echo $user['mobile']?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control" required value="<?php echo $user['location']?>">
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required value="<?php echo $user['name']?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Job Title</label>
                            <input type="text" name="designation" class="form-control" required value="<?php echo $user['designation']?>">
                        </div>
                       
                        
                    </div>
                  
                                       
                </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"> Update User </button>
                    </div>
                </form>
                </div>
            </div>
        
    </div>
</div>




