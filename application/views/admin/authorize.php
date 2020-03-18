<div class="login" style="margin-top: 50px;;">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <div class="login-body">

                <h2>Verify Please! </h2>

            <h3 class="login-heading"><?php echo $code;?></h3>
            <p>Please enter the four digit code which you have just received in your email</p>
            <?php if(isset($errors)){?>
                <div class="alert alert-danger">
                    <?php print_r($errors);?>
                </div>
            <?php }?>
            <div class="login-form">
                <form data-toggle="validator" action="<?php echo base_url().'login/authorize'?>" method="post">
                    <div class="form-group">
                        <label for="username" class="control-label">Enter code here</label>
                        <input type="hidden" name="user_id" value="<?php echo $user_id?>">
                        <input id="username" class="form-control" type="text" name="code"  required placeholder="XXXX">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" type="submit">Let me In</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script src="<?php echo BASE_URL?>js/vendor.minf9e3.js?v=1.1"></script>
<script src="<?php echo BASE_URL?>js/elephant.minf9e3.js?v=1.1"></script>
<script src="<?php echo BASE_URL?>js/application.minf9e3.js?v=1.1"></script>

</body>