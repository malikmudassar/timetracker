<div class="layout-content">
    <div class="layout-content-body">
        <?php date_default_timezone_set('US/Pacific');?>
        <div class="row">
            <div class="col-md-8">
                <?php if(isset($success)){?>
                    <div class="alert alert-success">
                        <?php print_r($success);?>
                    </div>
                <?php }?>
                <table class="table table-hover table-striped">
                    <tr>
                        <th>Clock-In</th>
                        <th>Clock-Out</th>
                        <th>Clock-out Reason</th>
                    </tr>
                    <form action="" method="POST">
                    <?php
                    $i=0;
                    for($i=0;$i<count($attendance);$i++){
                    ?>
                    <tr>
                        <td><input type="text" name="<?php echo $attendance[$i]['id']?>-check_in" value="<?php echo date('h:i A',strtotime($attendance[$i]['check_in']))?>" class="form-control"></td>
                        <td>
                            <input type="text" name="<?php echo $attendance[$i]['id']?>-check_out" 
                            value="<?php 
                                if(!empty($attendance[$i]['check_out'])){
                                    echo date('h:i A',strtotime($attendance[$i]['check_out']));
                                } 
                                else 
                                {
                                    echo date('h:i A');
                                }
                            ?>" class="form-control">
                            
                                    
                        </td>
                        <td><input type="text" name="<?php echo $attendance[$i]['id']?>-remarks" value="<?php echo $attendance[$i]['remarks'];?>" class="form-control"></td>
                        
                    </tr>
                    <?php
                    }
                    ?>
                </table>
                <button type="submit" class="btn btn-primary"> Update </button>

            </form>
            </div>
            
        </div>


    </div>
</div>



