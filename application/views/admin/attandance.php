<div class="layout-content">
    <div class="layout-content-body">
        <?php date_default_timezone_set('Asia/Karachi');?>
        <h2><?php echo date('l jS, F Y');?></h2>
        <h3>House Attendance</h3>
        <?php if(empty($employees)){?>
            <a href="<?php echo base_url().'admin/createAttendance'?>" class="btn btn-primary">Create Attendance</a>
        <?php }?>
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
        <?php if(count($employees)>0){?>
            <table class="table">
                <tr>
                    <td>Sr. #</td>
                    <th>Name</th>
                    <th>Clock-In</th>
                    <th>Mark</th>
                    <th>Clock-Out</th>
                    <th>Mark</th>
                </tr>
                <?php for($i=0;$i<count($employees);$i++){?>

                    <tr>
                        <td><?php echo $i+1;?></td>
                        <td><?php echo $employees[$i]['name']?></td>
                        <td><input type="text" name="check_in"
                                   <?php if(!empty($employees[$i]['check_in'])){?>
                                   value="<?php echo date('h:i A',strtotime($employees[$i]['check_in']));?>"
                                   <?php }else{?>
                                   value="<?php echo date('h:i A');?>"
                                   <?php }?>
                                   class="form-control">  </td>
                        <td>
                            <?php if(!empty($employees[$i]['check_in'])){
                            if(date('h:i A',strtotime($employees[$i]['check_in']))!='12:00 AM'){
                                ?>
                                <span class="btn btn-info"> Clocked-In </span>
                            <?php } else {?>
                            <a href="<?php echo base_url().'admin/checkin/'.$employees[$i]['id']?>" class="btn btn-danger">Clock-In</a>
                            <?php }}?>
                        </td>
                        <td><input type="text" name="check_out" <?php if(!empty($employees[$i]['check_out'])){?>
                                value="<?php echo date('h:i A',strtotime($employees[$i]['check_out']));?>"
                            <?php }else{?>
                                value="<?php echo date('h:i A');?>"
                            <?php }?> class="form-control">  </td>
                        <td>
                            <?php if(!empty($employees[$i]['check_out'])){
                                if(date('h:i A',strtotime($employees[$i]['check_out']))!='12:00 AM'){
                                ?>
                                <span class="btn btn-info"> Clocked-Out </span>
                            <?php } else {?>
                            <a href="<?php echo base_url().'admin/checkout/'.$employees[$i]['id']?>" class="btn btn-warning">Clock-Out</a> </td>
                            <?php }}?>
                    </tr>
                <?php }?>
            </table>
        <?php }?>

    </div>
</div>



