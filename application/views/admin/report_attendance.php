<?php date_default_timezone_set('Asia/Karachi');?>
<div class="layout-content">
    <div class="layout-content-body">
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="panel panel-body" data-toggle="match-height">
                    <h5>Select Date</h5>
                    <div class="input-with-icon">
                        <form action="" method="post">
                        <input class="form-control" type="text" name="date" data-provide="datepicker" data-date-today-highlight="true" required>
                        <span class="icon icon-calendar input-icon"></span>
                        <button type="submit" class="btn btn-primary">Get Attendance</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
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

                <div class="clearfix"></div>
                <div class="panel">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="demo-dynamic-tables-2" class="table table-middle nowrap">
                                <thead>
                                <tr>
                                    <th>Sr. #</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Work Time</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php for($i=0;$i<count($employees);$i++){?>
                                    <tr>
                                        <td class="maw-320">
                                            <strong><?php echo $i+1;?></strong>
                                        </td>
                                        <td class="maw-320">
                                            <span class="truncate"><?php echo $employees[$i]['user']?></span>
                                        </td>
                                        <td><?php echo date('jS-M,Y',strtotime($employees[$i]['date']))?></td>
                                        <td><?php echo date('h:i A',strtotime($employees[$i]['check_in']))?></td>
                                        <td><?php 
                                            if(!empty($employees[$i]['check_out'])){
                                            echo date('h:i A',strtotime($employees[$i]['check_out']));}else{?><span>Not Checked out yet</span><?php }?></td>
                                        <td>
                                            <?php echo gmdate('H:i',$employees[$i]['seconds']).' hrs';?>
                                        </td>
                                        
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url()?>assets/js/sweetalert.min.js"></script>
<script>
    $(function(){ TablesDatatables.init(); });
    function validate(a)
    {
        var id= a.value;

        swal({
                title: "Are you sure?",
                text: "You want to delete this Department!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Delete it!",
                closeOnConfirm: false }, function()
            {
                swal("Deleted!", "Department has been Deleted.", "success");
                $(location).attr('href','<?php echo base_url()?>admin/del_admin_menu/'+id);
            }
        );
    }
</script>