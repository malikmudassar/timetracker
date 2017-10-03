<div class="layout-content">
    <div class="layout-content-body">
        <?php date_default_timezone_set('US/Pacific');?>
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
            
        </div>


    </div>
</div>



