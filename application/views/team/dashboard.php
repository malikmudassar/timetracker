<div class="layout-content">
    <div class="layout-content-body">
        <div class="col-md-8">
            <h2>My Attendance</h2><hr>
            <div class="login-body">
                <table class="table table-striped table-hover">
                <tr>
                    <th>Date</th>
                    <th>Check-In</th>
                    <th>Check-out</th>
                    <th>Hours</th>
                </tr>
                <?php for($i=0;$i<count($attendance);$i++){?>
                    <tr>
                        <td><?php echo date('j M,Y',strtotime($attendance[$i]['date']))?></td>
                        <td><?php echo date('h:i A',strtotime($attendance[$i]['check_in']))?></td>
                        <td><?php
                            if(empty($attendance[$i]['check_out'])){
                                echo 'Not checked out yet';
                            }
                            else
                            {
                                echo date('h:i A',strtotime($attendance[$i]['check_out']));
                            }

                            ?>
                        </td>
                        <td>
                            <?php
                            if(empty($attendance[$i]['check_out'])){
                                echo '0';
                            }
                            else
                            {
                                echo (round(abs(strtotime($attendance[$i]['check_out']) - strtotime($attendance[$i]['check_in'])) / (60*60))). " hrs";
                            }

                            ?>
                            <?php //echo (round(abs(strtotime($attendance[$i]['check_out']) - strtotime($attendance[$i]['check_in'])) / (60*60))-1). " hrs";?>
                        </td>
                    </tr>
                <?php }?>
            </table>
            </div>
        </div>
    </div>
</div>




