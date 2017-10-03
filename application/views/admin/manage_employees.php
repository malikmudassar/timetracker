<div class="layout-content">
    <div class="layout-content-body">
        <div class="row">
            <div class="col-xs-12">
                <h2>User Management</h2>
                <div class="panel">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="demo-dynamic-tables-2" class="table table-middle nowrap">
                                <thead>
                                <tr>
                                    <th>Sr. #</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Location</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php for($i=0;$i<count($employees);$i++){?>
                                    <tr>
                                        <td class="maw-320">
                                            <strong><?php echo $i+1;?></strong>
                                        </td>
                                        <td class="maw-320">
                                            <span class="truncate"><?php echo $employees[$i]['name']?></span>
                                        </td>
                                        <td><?php echo $employees[$i]['designation']?></td>
                                        <td><?php echo $employees[$i]['location']?></td>
                                        <td><?php echo $employees[$i]['email']?></td>
                                        <td><?php echo $employees[$i]['mobile']?></td>
                                        <td>
                                            <a href="<?php echo base_url().'admin/edit_user/'.$employees[$i]['id'];?>" class="btn btn-default" title="Edit"><i class="icon icon-pencil"></i></a>
                                            
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
<script src="<?php echo BASE_URL?>/js/sweetalert.min.js"></script>
<script>
    $(function(){ TablesDatatables.init(); });
    function validate(a)
    {
        var id= a.value;

        swal({
                title: "Are you sure?",
                text: "You want to delete this User!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Delete it!",
                closeOnConfirm: false }, function()
            {
                swal("Deleted!", "Department has been Deleted.", "success");
                $(location).attr('href','<?php echo base_url()?>admin/del_user/'+id);
            }
        );
    }
</script>