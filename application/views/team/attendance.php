<div class="layout-content">
    <div class="layout-content-body">
        <?php date_default_timezone_set('US/Pacific');?>
        <h2><?php echo date('l jS, F Y');?></h2>
        <?php if(empty($attendance)){?>
            <label>You are not clocked in yet</label><br>
            <a href="<?php echo base_url().'team/clockin'?>" class="btn btn-primary">Clock-In</a>
        <?php } else {?>
        <div class="row">
            <div class="col-md-8">
                <table class="table table-hover table-striped">
                    <tr>
                        <th>Clock-In</th>
                        <th>Clock-Out</th>
                        <th>Clock-out Reason</th>
                    </tr>
                    <?php
                    $i=0;
                    for($i=0;$i<count($attendance);$i++){
                    ?>
                    <tr>
                        <td><?php echo date('h:i A',strtotime($attendance[$i]['check_in']))?></td>
                        <td><?php 
                                if(!empty($attendance[$i]['check_out'])){
                                    echo date('h:i A',strtotime($attendance[$i]['check_out']));
                                } 
                                else 
                                {
                                    echo 'Not Clock-Out yet';
                                }
                            ?>
                                    
                        </td>
                        <td><?php echo $attendance[$i]['remarks'];?></td>
                        <?php $id=$attendance[$i]['id']; $c=$i;?>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <?php if(empty($attendance[$i-1]['check_out'])){?>
            <div class="clearfix"></div>
            <div class="col-md-4">
                <form data-toggle="validator" action="" method="post">
                    <label>Clock-out Reason </label>
                    <input type="hidden" name="id" value="<?php echo $id;?>">
                    <input type="text" name="remarks" class="form-control" required><br>
                    <input type="submit" class="btn btn-primary" value="Clock-out">
                </form>
            </div>
            <?php } else {?>
                <div class="clearfix"></div>
                <div class="col-md-4">
                <a href="<?php echo base_url().'team/clockin'?>" class="btn btn-primary">Clock-In</a>

                </div>
            <?php }?>
        </div>

        <?php }?>

    </div>
</div>



