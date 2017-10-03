<div class="layout-content">
    <div class="layout-content-body">
        <h2>My Attendance</h2><hr>
        <div class="col-md-4">
        <form action="" method="post">
            <div class="form-group">
                <label>Choose Month</label>
                <select name="month" class="form-control">
                    <?php for($i=0;$i<count($months);$i++){?>
                        <?php if($months[$i]['months']!='0-0'){?>
                        <option value="<?php echo date('Y-m',strtotime($months[$i]['months']))?>"><?php echo date('M-Y',strtotime($months[$i]['months']))?></option>
                    <?php }}?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"> Show </button>
            </div>
        </form>
        </div>
        <div class="col-md-12">

            <div class="login-body">

                <table class="table table-striped table-hover">
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Check-In</th>
                        <th>Check-out</th>
                        <th>Hours</th>
                    </tr>
                    <?php for($i=0;$i<count($attendance);$i++){?>
                        <tr>
                            <td><?php echo date('j M,Y',strtotime($attendance[$i]['date']))?></td>
                            <td><?php echo date('l',strtotime($attendance[$i]['date']))?></td>
                            <td><?php echo date('h:i A',strtotime($attendance[$i]['check_in']))?></td>
                            <td><?php
                                if(date('h:i A',strtotime($attendance[$i]['check_out']))=='12:00 AM'){
                                    echo 'Not checked out yet';
                                }
                                else
                                {
                                    echo date('h:i A',strtotime($attendance[$i]['check_out']));
                                }

                                ?>
                            </td>
                            <td>
                                <?php echo $attendance[$i]['hours']?>
                            </td>
                        </tr>
                    <?php }?>
                </table>
            </div>
        </div>
    </div>
</div>




